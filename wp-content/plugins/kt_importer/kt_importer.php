<?php
/*
Plugin Name: KiteThemes Import Demo Content
Description: Replicate any of the Atelier example sites in just a few clicks!
Author: KiteThemes
Author URI: http://kitethemes.com
Version: 1.0
Text Domain: kt_importer
License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/



class KT_IMPORTER_DEMOS
{

    public $kt_importer_dir         =  '';
    public $kt_importer_url         =  '';
    public $kt_importer_opt_name    =  '';
    public $theme_options_file      =  '';
    public $widgets_file_name       =  '';
    public $content_file_name       =  '';
    public $demoid                  =  '';

    /**
     * Start up
     */
    public function __construct()
    {

        if( !is_admin() )
            return;

        add_action( 'admin_init', array( $this, 'kt_importer_init' ));
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'kt_importer_scripts' ));
        add_action( 'wp_ajax_kt_importer_content', array( $this,'kt_importer_content_callback') );
        add_action( 'wp_ajax_kt_importer_options', array( $this,'kt_importer_options_callback') );
        add_action( 'wp_ajax_kt_importer_widgets', array( $this,'kt_importer_widgets_callback') );


    }

    function kt_importer_init(){
        load_plugin_textdomain( 'kt_importer', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

        $this->kt_importer_dir = apply_filters('kt_importer_dir', '');
        $this->kt_importer_url = apply_filters('kt_importer_url', '');
        $this->kt_importer_opt_name = apply_filters('kt_importer_opt_name', '');

    }



    function kt_importer_content_callback(){

        $this->demoid = sanitize_title($_POST['demo']);
        $count = ( isset($_POST['count']) ) ? intval($_POST['count']) : 0;

        if($count){
            if ( !defined( 'WP_LOAD_IMPORTERS' ) ) define( 'WP_LOAD_IMPORTERS', true );

            require_once ABSPATH . 'wp-admin/includes/import.php';

            if ( !class_exists( 'WP_Importer' ) ) {
                $class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
                require_once $class_wp_importer;
            }

            if ( !class_exists( 'WP_Import' ) ) {
                $class_wp_import = dirname( __FILE__ ) .'/includes/wordpress-importer.php';
                require_once $class_wp_import;
            }

            $this->content_file_name = $this->kt_importer_dir.$this->demoid.'/content_'.$count.'.xml';

            if ( !is_file( $this->content_file_name ) ) {
                echo "The XML file containing the dummy content is not available or could not be read .. You might want to try to set the file permission to chmod 755.<br/>If this doesn't work please use the Wordpress importer and import the XML file (should be located in your download .zip: Sample Content folder) manually ";
            } else {
                $wp_import = new WP_Import();
                $wp_import->fetch_attachments = true;
                $returned_value = $wp_import->import( $this->content_file_name );

                if ( is_wp_error($returned_value) ){
                    echo "An Error Occurred During Import";
                }

            }
        }else{
            //Content imported successfully
            do_action( 'kt_importer_after_content_import', $this->demoid );
        }

        wp_die(); // this is required to terminate immediately and return a proper response
    }

    function kt_importer_options_callback(){
        $this->demoid = sanitize_title($_POST['demo']);
        $this->theme_options_file = $this->kt_importer_dir.$this->demoid.'/theme-options.json';

        if ( !empty( $this->theme_options_file ) && is_file( $this->theme_options_file ) ) {

            // File exists?
            if ( ! file_exists( $this->theme_options_file ) ) {
                wp_die(
                    __( 'Theme options Import file could not be found. Please try again.', 'kt_importer' ),
                    '',
                    array( 'back_link' => true )
                );
            }

            // Get file contents and decode
            $data = file_get_contents( $this->theme_options_file );
            $data = json_decode( $data, true );
            $data = maybe_unserialize( $data );

            if ( !empty( $data ) || is_array( $data ) ) {

                // Hook before import
                $data = apply_filters( 'kt_importer_import_theme_options', $data );
                update_option( $this->kt_importer_opt_name, $data );
            }

            do_action( 'kt_importer_after_theme_options_import', $this->demoid, $this->theme_options_file );

        }

        wp_die(); // this is required to terminate immediately and return a proper response

    }



    function kt_importer_widgets_callback(){
        $this->demoid = sanitize_title($_POST['demo']);
        $this->widgets_file_name = $this->kt_importer_dir.$this->demoid.'/widgets.json';

        // File exists?
        if ( ! file_exists( $this->widgets_file_name ) ) {
            wp_die(
                __( 'Widget Import file could not be found. Please try again.', 'kt_importer' ),
                '',
                array( 'back_link' => true )
            );
        }

        // Get file contents and decode
        $data = file_get_contents( $this->widgets_file_name );

        $data = json_decode( $data );

        // Import the widget data
        // Make results available for display on import/export page
        $this->widget_import_results = $this->import_widgets( $data );


        wp_die(); // this is required to terminate immediately and return a proper response
    }

    /**
     * Available widgets
     *
     * Gather site's widgets into array with ID base, name, etc.
     * Used by export and import functions.
     *
     * @since 1.0
     *
     * @global array $wp_registered_widget_updates
     * @return array Widget information
     */
    function available_widgets() {

        global $wp_registered_widget_controls;

        $widget_controls = $wp_registered_widget_controls;

        $available_widgets = array();

        foreach ( $widget_controls as $widget ) {

            if ( ! empty( $widget['id_base'] ) && ! isset( $available_widgets[$widget['id_base']] ) ) { // no dupes

                $available_widgets[$widget['id_base']]['id_base'] = $widget['id_base'];
                $available_widgets[$widget['id_base']]['name'] = $widget['name'];

            }

        }
        return apply_filters( 'kt_importer_import_widget_available_widgets', $available_widgets );

    }

    /**
     * Import widget JSON data
     *
     * @since 1.0
     * @global array $wp_registered_sidebars
     * @param object  $data JSON widget data from .wie file
     * @return array Results array
     */
    public function import_widgets( $data ) {

        global $wp_registered_sidebars;

        // Have valid data?
        // If no data or could not decode
        if ( empty( $data ) || ! is_object( $data ) ) {
            return;
        }

        // Hook before import
        $data = apply_filters( 'kt_importer_theme_import_widget_data', $data );

        // Get all available widgets site supports
        $available_widgets = $this->available_widgets();

        // Get all existing widget instances
        $widget_instances = array();
        foreach ( $available_widgets as $widget_data ) {
            $widget_instances[$widget_data['id_base']] = get_option( 'widget_' . $widget_data['id_base'] );
        }

        // Begin results
        $results = array();

        // Loop import data's sidebars
        foreach ( $data as $sidebar_id => $widgets ) {

            // Skip inactive widgets
            // (should not be in export file)
            if ( 'wp_inactive_widgets' == $sidebar_id ) {
                continue;
            }

            // Check if sidebar is available on this site
            // Otherwise add widgets to inactive, and say so
            if ( isset( $wp_registered_sidebars[$sidebar_id] ) ) {
                $sidebar_available = true;
                $use_sidebar_id = $sidebar_id;
                $sidebar_message_type = 'success';
                $sidebar_message = '';
            } else {
                $sidebar_available = false;
                $use_sidebar_id = 'wp_inactive_widgets'; // add to inactive if sidebar does not exist in theme
                $sidebar_message_type = 'error';
                $sidebar_message = __( 'Sidebar does not exist in theme (using Inactive)', 'kt_importer' );
            }

            // Result for sidebar
            $results[$sidebar_id]['name'] = ! empty( $wp_registered_sidebars[$sidebar_id]['name'] ) ? $wp_registered_sidebars[$sidebar_id]['name'] : $sidebar_id; // sidebar name if theme supports it; otherwise ID
            $results[$sidebar_id]['message_type'] = $sidebar_message_type;
            $results[$sidebar_id]['message'] = $sidebar_message;
            $results[$sidebar_id]['widgets'] = array();

            // Loop widgets
            foreach ( $widgets as $widget_instance_id => $widget ) {

                $fail = false;

                // Get id_base (remove -# from end) and instance ID number
                $id_base = preg_replace( '/-[0-9]+$/', '', $widget_instance_id );
                $instance_id_number = str_replace( $id_base . '-', '', $widget_instance_id );

                // Does site support this widget?
                if ( ! $fail && ! isset( $available_widgets[$id_base] ) ) {
                    $fail = true;
                    $widget_message_type = 'error';
                    $widget_message = __( 'Site does not support widget', 'kt_importer' ); // explain why widget not imported
                }

                // Filter to modify settings before import
                // Do before identical check because changes may make it identical to end result (such as URL replacements)
                $widget = apply_filters( 'kt_importer_import_widget_settings', $widget );

                // Does widget with identical settings already exist in same sidebar?
                if ( ! $fail && isset( $widget_instances[$id_base] ) ) {

                    // Get existing widgets in this sidebar
                    $sidebars_widgets = get_option( 'sidebars_widgets' );
                    $sidebar_widgets = isset( $sidebars_widgets[$use_sidebar_id] ) ? $sidebars_widgets[$use_sidebar_id] : array(); // check Inactive if that's where will go

                    // Loop widgets with ID base
                    $single_widget_instances = ! empty( $widget_instances[$id_base] ) ? $widget_instances[$id_base] : array();
                    foreach ( $single_widget_instances as $check_id => $check_widget ) {

                        // Is widget in same sidebar and has identical settings?
                        if ( in_array( "$id_base-$check_id", $sidebar_widgets ) && (array) $widget == $check_widget ) {

                            $fail = true;
                            $widget_message_type = 'warning';
                            $widget_message = __( 'Widget already exists', 'kt_importer' ); // explain why widget not imported

                            break;

                        }

                    }

                }

                // No failure
                if ( ! $fail ) {

                    // Add widget instance
                    $single_widget_instances = get_option( 'widget_' . $id_base ); // all instances for that widget ID base, get fresh every time
                    $single_widget_instances = ! empty( $single_widget_instances ) ? $single_widget_instances : array( '_multiwidget' => 1 ); // start fresh if have to
                    $single_widget_instances[] = (array) $widget; // add it

                    // Get the key it was given
                    end( $single_widget_instances );
                    $new_instance_id_number = key( $single_widget_instances );

                    // If key is 0, make it 1
                    // When 0, an issue can occur where adding a widget causes data from other widget to load, and the widget doesn't stick (reload wipes it)
                    if ( '0' === strval( $new_instance_id_number ) ) {
                        $new_instance_id_number = 1;
                        $single_widget_instances[$new_instance_id_number] = $single_widget_instances[0];
                        unset( $single_widget_instances[0] );
                    }

                    // Move _multiwidget to end of array for uniformity
                    if ( isset( $single_widget_instances['_multiwidget'] ) ) {
                        $multiwidget = $single_widget_instances['_multiwidget'];
                        unset( $single_widget_instances['_multiwidget'] );
                        $single_widget_instances['_multiwidget'] = $multiwidget;
                    }

                    // Update option with new widget
                    update_option( 'widget_' . $id_base, $single_widget_instances );

                    // Assign widget instance to sidebar
                    $sidebars_widgets = get_option( 'sidebars_widgets' ); // which sidebars have which widgets, get fresh every time
                    $new_instance_id = $id_base . '-' . $new_instance_id_number; // use ID number from new widget instance
                    $sidebars_widgets[$use_sidebar_id][] = $new_instance_id; // add new instance to sidebar
                    update_option( 'sidebars_widgets', $sidebars_widgets ); // save the amended data

                    // Success message
                    if ( $sidebar_available ) {
                        $widget_message_type = 'success';
                        $widget_message = __( 'Imported', 'kt_importer' );
                    } else {
                        $widget_message_type = 'warning';
                        $widget_message = __( 'Imported to Inactive', 'kt_importer' );
                    }

                }

                // Result for widget instance
                $results[$sidebar_id]['widgets'][$widget_instance_id]['name'] = isset( $available_widgets[$id_base]['name'] ) ? $available_widgets[$id_base]['name'] : $id_base; // widget name or ID if name not available (not supported by site)
                $results[$sidebar_id]['widgets'][$widget_instance_id]['title'] = $widget->title ? $widget->title : __( 'No Title', 'kt_importer' ); // show "No Title" if widget instance is untitled
                $results[$sidebar_id]['widgets'][$widget_instance_id]['message_type'] = $widget_message_type;
                $results[$sidebar_id]['widgets'][$widget_instance_id]['message'] = $widget_message;

            }

        }

        // Hook after import
        do_action( 'kt_importer_import_widget_after_import' );

        // Return results
        return apply_filters( 'kt_importer_import_widget_results', $results );

    }









    /**
     * Add scripts
     *
     *
     */
    function kt_importer_scripts(){
        wp_enqueue_style( 'kt-importer-css', plugins_url( '/assets/css/kt-importer.css', __FILE__ ) );
        wp_enqueue_script( 'kt-importer-js', plugins_url( '/assets/js/kt-importer.js', __FILE__ ), array( 'jquery' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {

        // This page will be under "Settings"
        add_menu_page(
            __('Install Demos', 'kt_importer'),
            __('Install Demos', 'kt_importer'),
            'manage_options',
            'kt-importer-demos',
            array( $this, 'create_admin_page' ),
            'dashicons-megaphone',
            61
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {

        $demos = apply_filters( 'kt_import_demo', array() );

        ?>
        <div class="wrap kt-importer-demos">
            <h1><?php _e('Demo Content Importer', 'kt_importer' ); ?></h1>

            <?php

                $note = '';
                $importer_errors = array();
                $max_execution_time  = ini_get("max_execution_time");
                $max_input_time      = ini_get("max_input_time");
                $upload_max_filesize = ini_get("upload_max_filesize");

                if ($max_execution_time < 120) {
                    $importer_errors[] = '<li><strong>Maximum Execution Time (max_execution_time) : </strong>' . $max_execution_time . ' seconds. <span style="color:red"> Recommended max_execution_time should be at least 120 Seconds.</span></li>';
                }
                if ($max_input_time < 120)
                    $importer_errors[] = '<li><strong>Maximum Input Time (max_input_time) : </strong>' . $max_input_time . ' seconds. <span style="color:red"> Recommended max_input_time should be at least 120 Seconds.</span></li>';

                if(intval(WP_MEMORY_LIMIT) < 40){
                    $importer_errors[] = '<li><strong>WordPress Memory Limit (WP_MEMORY_LIMIT) : </strong>' . WP_MEMORY_LIMIT . ' <span style="color:red"> Recommended memory limit should be at least 40MB.</span></li>';
                }
                if (intval($upload_max_filesize) < 15) {
                    $importer_errors[] = '<li><strong>Maximum Upload File Size (upload_max_filesize) : </strong>' . $upload_max_filesize . ' <span style="color:red"> Recommended Maximum Upload Filesize should be at least 15MB.</li>';
                }

                if(count($importer_errors)){
                    $note .= '<ul>'.implode('', $importer_errors).'</ul>';
                }

                $note .= sprintf('<p>%s</p>',__('Make sure you have installed all recommended plugins from WordPress repository (WordPress.org) and built-in plugins which come in the full package', 'kt_importer'));

                $note = apply_filters('kt_importer_note', $note);

                if($note){
                    printf(
                        '<div class="kt-importer-note"><h2>%s</h2>%s</div>',
                        __('Please Read!', 'kt_importer'),
                        $note
                    );
                }

            ?>


            <div class="theme-browser rendered">
                <div class="themes">
                    <?php foreach($demos as $id => $demo){ ?>
                        <div class="theme">
                            <div class="theme-screenshot">
                                <img alt="<?php echo esc_attr($demo['title']) ?>" src="<?php echo esc_url($this->kt_importer_url.$id) ?>/screen-image.jpg">
                            </div>
                            <h2 class="theme-name"><?php echo esc_html($demo['title']) ?></h2>
                            <?php if(!isset($demo['coming'])){ ?>
                                <div class="theme-actions">
                                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" class="button button-primary kt-importer-imported">
                                        <?php esc_html_e('Imported', 'kt_importer') ?>
                                    </a>
                                    <a href="#" class="button button-primary kt-importer-button" data-id="<?php echo esc_attr($id); ?>" data-count="<?php echo intval($demo['xml_count']); ?>">
                                        <?php esc_html_e('Install', 'kt_importer') ?>
                                    </a>
                                    <a href="<?php echo esc_url($demo['previewlink']); ?>" target="_blank" class="button button-primary">
                                        <?php esc_html_e('Live Preview', 'kt_importer') ?>
                                    </a>
                                </div>
                            <?php } ?>
                            <?php
                            if(isset($demo['status'])){
                                printf('<div class="theme-status">%s</div>', $demo['status']);
                            }
                            ?>
                            <div class="demo-import-loader">
                                <div class="demo-import-process"><span></span></div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php
    }
}


$kt_importer_demos = new KT_IMPORTER_DEMOS();
