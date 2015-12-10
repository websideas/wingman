<?php
/*
Plugin Name:  KT Mailchimp
Plugin URI:   http://kitethemes.com/
Description:  MailChimp for wordpress
Version:      1.0
Author:       KiteThemes
Author URI:   http://themeforest.net/user/kite-themes

Copyright (C) 2014-2015, by Cuongdv
All rights reserved.
*/

define( 'KTM_PLUGIN', __FILE__ );

define( 'KTM_PLUGIN_BASENAME', plugin_basename( KTM_PLUGIN ) );

define( 'KTM_PLUGIN_DIR', untrailingslashit( dirname( KTM_PLUGIN ) ) );

define( 'KTM_PLUGIN_URL', plugins_url('', __FILE__) );



add_action( 'plugins_loaded', 'kt_mailchimp_localization' );
/**
 * Setup localization
 *
 * @since 2.4
 */
function kt_mailchimp_localization() {
    load_plugin_textdomain( 'kt_mailchimp', false, 'kt-mailchimp/languages/' );
}


/**
 * Include Mailchim API.
 *
 */
if(!class_exists('MCAPI')){
    require_once ( KTM_PLUGIN_DIR . '/MCAPI.class.php' );
}


/**
 * Include Widget.
 *
 */
require_once ( KTM_PLUGIN_DIR . '/widget.php' );


/**
 * Include Mailchim Settings.
 *
 */
if(is_admin()){
    require_once ( KTM_PLUGIN_DIR . '/mailchimp_settings.php' );
}


class KT_MailChimp
{
    private $options;

    public function __construct() {

        $this->options = get_option( 'kt_mailchimp_option' );

        // Add ajax for frontend
        add_action( 'wp_ajax_frontend_mailchimp', array( $this, 'frontend_mailchimp_callback') );
        add_action( 'wp_ajax_nopriv_frontend_mailchimp', array( $this, 'frontend_mailchimp_callback') );

        // Add scripts
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ));

        if ( !$this->options['api_key'] ) {
            add_action( 'admin_notices', array( $this, 'admin_notice' ));
        }

        // Add shortcode mailchimp
        add_shortcode('kt_mailchimp', array($this, 'mailchimp_handler'));

        if ( class_exists( 'Vc_Manager', false ) ) {
            $this->addMapShortCode();
        }
    }

    public function admin_notice()
    {

        ?>
        <div class="updated">
            <p><?php
                printf(
                    __('Please enter Mail Chimp API Key in <a href="%s">here</a>', 'kt_mailchimp' ),
                    admin_url( 'options-general.php?page=kt-mailchimp-settings')
                );
                ?></p>
        </div>
        <?php
    }

    public function enqueue_scripts()
    {
        wp_enqueue_style( 'font-awesome', KTM_PLUGIN_URL . 'font-awesome/css/font-awesome.min.css', array());
        wp_enqueue_style( 'kt_mailchimp', KTM_PLUGIN_URL . '/assets/styles.css', array());
        wp_enqueue_script( 'kt_mailchimp-script', KTM_PLUGIN_URL . '/assets/functions.js', array( 'jquery' ), null, true );
    }

    public  function addMapShortCode()
    {
        $lists_arr = array(__('Select option', 'kt_mailchimp') => '');


        if ( isset ( $this->options['api_key'] ) && !empty ( $this->options['api_key'] ) ) {
            $mcapi = new MCAPI($this->options['api_key']);
            $lists = $mcapi->lists();
            if($lists['data']){
                foreach ($lists['data'] as $item) {
                    $lists_arr[$item['name']] = $item['id'];
                }
            }
        }

        vc_map( array(
            "name" => __( "KT Mailchimp", 'kt_mailchimp'),
            "base" => "kt_mailchimp",
            "category" => __('by Theme', 'kt_mailchimp' ),
            "description" => __( "Mailchimp", 'kt_mailchimp'),
            "wrapper_class" => "clearfix",
            "params" => array(
                array(
                    'type' => 'textfield',
                    'param_name' => 'title',
                    'heading' => __( 'Widget title', 'js_composer' ),
                    'description' => __( 'Enter text used as widget title (Note: located above content element).', 'js_composer' ),
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __( 'Mailchimp layout', 'kt_mailchimp' ),
                    'param_name' => 'layout',
                    'admin_label' => true,
                    'value' => array(
                        __( 'Layout 1', 'kt_mailchimp' ) => '1',
                        __( 'Layout 2', 'kt_mailchimp' ) => "2"
                    ),
                    'description' => __( 'Select your layout', 'kt_mailchimp' )
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __( 'Mailchimp List', 'kt_mailchimp' ),
                    'param_name' => 'list',
                    'admin_label' => true,
                    'value' => $lists_arr,
                    'description' => __( 'Select your List', 'kt_mailchimp' )
                ),
                array(
                    "type" => 'checkbox',
                    "heading" => __( 'Double opt-in', 'kt_mailchimp' ),
                    "param_name" => 'opt_in',
                    "description" => __("", 'kt_mailchimp'),
                    "value" => array( __( 'Yes, please', 'js_composer' ) => 'yes' ),
                ),
                array(
                    "type" => 'checkbox',
                    "heading" => __( 'Disable names', 'kt_mailchimp' ),
                    "param_name" => 'disable_names',
                    "description" => __("", 'kt_mailchimp'),
                    "value" => array( __( 'Yes, please', 'js_composer' ) => 'yes' ),
                ),

                array(
                    "type" => "textarea",
                    "heading" => __("Text before form", 'kt_mailchimp'),
                    "param_name" => "text_before",
                    "description" => __("", 'kt_mailchimp')
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __( 'CSS Animation', 'js_composer' ),
                    'param_name' => 'css_animation',
                    'admin_label' => true,
                    'value' => array(
                        __( 'No', 'js_composer' ) => '',
                        __( 'Top to bottom', 'js_composer' ) => 'top-to-bottom',
                        __( 'Bottom to top', 'js_composer' ) => 'bottom-to-top',
                        __( 'Left to right', 'js_composer' ) => 'left-to-right',
                        __( 'Right to left', 'js_composer' ) => 'right-to-left',
                        __( 'Appear from center', 'js_composer' ) => "appear"
                    ),
                    'description' => __( 'Select type of animation if you want this element to be animated when it enters into the browsers viewport. Note: Works only in modern browsers.', 'js_composer' )
                ),
                array(
                    "type" => "textfield",
                    "heading" => __( "Extra class name", "js_composer"),
                    "param_name" => "el_class",
                    "description" => __( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer" ),
                ),
                array(
                    'type' => 'css_editor',
                    'heading' => __( 'Css', 'js_composer' ),
                    'param_name' => 'css',
                    // 'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'js_composer' ),
                    'group' => __( 'Design options', 'js_composer' )
                ),

            )
        ) );
    }


    public function mailchimp_handler( $atts )
    {

        $atts = shortcode_atts( array(
            'title' => '',
            'list' => '',
            'opt_in' => '',
            'disable_names' => '',
            'text_before' => '',
            'layout' => '1',
            'css' => '',
        ), $atts );

        extract( $atts );

        if ( isset ( $this->options['api_key'] ) && !empty ( $this->options['api_key'] ) ) {
            $elementClass = '';
            if(function_exists('vc_shortcode_custom_css_class')){
                $elementClass = vc_shortcode_custom_css_class( $css, ' ' );
            }

            $this->uniqeID  = 'mailchimp-wrapper-'.uniqid();
            $this->atts = $atts;

            $output = '';

            $output .= '<div class="kt-mailchimp-wrapper '.esc_attr($elementClass).'" id="'.esc_attr($this->uniqeID).'">';

            $output .= wpb_widget_title( array( 'title' => $title, 'extraclass' => 'kt-mailchimp-heading' ) );

            if ( isset ( $this->options['api_key'] ) && !empty ( $this->options['api_key'] ) ) {

                if(!$this->options['messages_successfully']){
                    $this->options['messages_successfully'] = __('Thank you, your sign-up request was successful! Please check your email inbox to confirm.', 'kt_mailchimp');
                }

                $name = '';

                $output .= ($text_before) ? '<div class="mailchimp-before">'.$text_before.'</div>' : '';

                $email = '<div class="mailchimp-input-email"><input name="email" class="mailchimp-email" type="text" placeholder="'.__('E-mail address*', 'kt_mailchimp').'"/></div>';
                $button = '<div class="mailchimp-input-button"><button data-loading="'.esc_attr(__('Loading', 'kt_mailchimp')).'" data-text="'.esc_attr(__('Subscribe', 'kt_mailchimp')).'"  class="btn btn-default mailchimp-submit" type="submit">'.__('Subscribe', 'kt_mailchimp').'</button></div>';

                if($disable_names != 'yes'){
                    $name .= '<div class="mailchimp-input-fname"><input name="firstname" class="mailchimp-firstname" type="text" placeholder="'.__('First Name', 'kt_mailchimp').'"/></div>';
                    $name .= '<div class="mailchimp-input-lname"><input name="lastname" class="mailchimp-lastname" type="text" placeholder="'.__('Last Name', 'kt_mailchimp').'"/></div>';
                }

                if($layout == 2){
                    $text_mailchimp = '%1$s %2$s %3$s';
                }else{
                    $text_mailchimp = '%1$s <div class="mailchimp-email-button">%2$s %3$s</div>';
                }


                $output .= '<form class="mailchimp-form clearfix mailchimp-layout-'.esc_attr($layout).'" action="#" method="post">';
                $output .= sprintf( $text_mailchimp, $name, $email, $button );
                $output .= '<input type="hidden" name="action" value="signup"/>';
                $output .= '<input type="hidden" name="list_id" value="'.esc_attr($list).'"/>';
                $output .= '<input type="hidden" name="opt_in" value="'.esc_attr($opt_in).'"/>';
                $output .= '<div class="mailchimp-success">'.$this->options['messages_successfully'].'</div>';
                $output .= '<div class="mailchimp-error"></div>';
                $output .= '</form>';




            }else{
                $output .= sprintf(
                    "Please enter your mailchimp API key in <a href='%s'>here</a>",
                    admin_url( 'options-general.php?page=kt-mailchimp-settings')
                );
            }

            $output .= '</div><!-- .mailchimp-wrapper -->';

            return $output;
        }




    }


    /**
     * Mailchimp callback AJAX request
     *
     * @since 1.0
     * @return json
     */

    function frontend_mailchimp_callback() {
        check_ajax_referer( 'ajax_frontend', 'security' );

        $output = array( 'error'=> 1, 'msg' => '');
        $error = '';
        $merge_vars = array();

        $email = strip_tags($_POST['email']);
        $firstname = isset($_POST['firstname']) ? strip_tags($_POST['firstname']) : '';
        $lastname = isset($_POST['lastname']) ? strip_tags($_POST['lastname']) : '';

        if(strlen(trim($lastname)) <= 0) {
            $lastname = '';
        }

        $merge_vars['FNAME'] = $firstname;
        $merge_vars['LNAME'] = $lastname;

        if (!$email) {
            $error = __('Email address is required field.', 'kt_mailchimp');
        }elseif(!is_email($email)){
            $error = __('Email address seems invalid.', 'kt_mailchimp');
        }

        if($error){
            $output['msg'] = $error;
        }else{
            if ( isset ( $this->options['api_key'] ) && !empty ( $this->options['api_key'] ) ) {
                $mcapi = new MCAPI($this->options['api_key']);
                $opt_in = in_array($_POST['opt_in'], array('1', 'true', 'y', 'yes', 'on'));

                $mcapi->listSubscribe($_POST['list_id'], $email, $merge_vars, 'html', $opt_in);
                $output['mcapi'] = $mcapi;

                if($mcapi->errorCode) {
                    $output['msg'] = $mcapi->errorMessage;
                }else{
                    $output['error'] = 0;
                }
            }
        }


        echo json_encode($output);
        die();
    }



}



$kt_mailchimp = new KT_MailChimp();


