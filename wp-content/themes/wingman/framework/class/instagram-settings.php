<?php

// Prevent loading this file directly
if ( !defined('ABSPATH')) exit;

require_once ( FW_CLASS . 'instagram-api.php' );

class KT_Instagram_Settings
{
    
    /**
     * Holds the values to be used in the fields callbacks
     */
     
    private $client_id;
    private $access_token;
    private $username;
    private $userid;

    /**
     * Start up
     */
    public function __construct()
    {
        $this->updatev();
        
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }
    
    function updatev(){
        $this->client_id = get_option( 'kt_instagram_client_id' );
        $this->access_token = get_option('kt_instagram_access');
        $this->username = get_option('kt_instagram_username');
        $this->userid = get_option('kt_instagram_userid');
    }
    
    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        if ( isset($_POST['authorize-instagram']) ) {
            
            $client_id = sanitize_text_field($_POST['client_id']);
            $username = sanitize_text_field($_POST['username']);
            
            if($client_id && $username){
                
                update_option('kt_instagram_client_id', $client_id);
                update_option('kt_instagram_username', $username);
                $this->updatev();
                
                $redirect_uri = admin_url( 'options-general.php?page=kt-instagram-settings');
                wp_redirect('https://instagram.com/oauth/authorize/?client_id='.$this->client_id.'&redirect_uri='.urlencode($redirect_uri).'&response_type=token');
                die(); 
                   
            }else{
                
                update_option('kt_instagram_client_id', $client_id);
                update_option('kt_instagram_username', $username);
                
                update_option('kt_instagram_access', '');
                update_option('kt_instagram_userid', '');
                
                if ( function_exists( 'add_settings_error' ) )
					add_settings_error( 'kt_instagram_client_id', 'invalid_instagram_value', __( 'Please insert to all field' ) );

            }
        }elseif(isset($_POST['logout-instagram'])){
            
            update_option('kt_instagram_client_id', '');
            update_option('kt_instagram_username', '');
            update_option('kt_instagram_access', '');
            update_option('kt_instagram_userid', '');
            
        }
        
        // This page will be under "Settings"
        add_options_page(
            __('KT Instagram Settings', THEME_LANG), 
            __('KT Instagram', THEME_LANG), 
            'manage_options', 
            'kt-instagram-settings', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        /*
        if($this->access_token && $this->username ){

            $kt_instagram = new KT_Instagram();
            $data = $kt_instagram->getUserMedia( array('count' => 9 ));
            
            if(!empty($data)){ 
                echo $kt_instagram->showInstagram($data); 
            }else{
                _e('Empty username or access token', THEME_LANG);
            }
        }
        */
        
        ?>
        <div class="wrap">
            <h2><?php _e('Instagram Settings', THEME_LANG ); ?></h2> 
            
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'kt_instagram_group' );   
                do_settings_sections( 'instagram-settings' );
                
                if($this->access_token && $this->username ){
                    submit_button(__('Logout or try with other account'), 'primary', 'logout-instagram'); 
                }else{
                    submit_button(__('Authorize with Instagram', THEME_LANG), 'primary', 'authorize-instagram');
                }
            ?>
            </form>
            <script type="text/javascript">
                (function($){
                    $('document').ready(function() {
                        var hash = window.location.hash;
                        if(hash){
                            hash = hash.substring(hash.indexOf('#')+1);
                            var splitter = hash.split('=');
                            var data = {
                    			'action': 'instagram_access_token',
                    			'access':  splitter[1]
                    		};
                    		$.post(ajaxurl, data, function(response) {
                                location.href = "<?php echo admin_url( 'options-general.php?page=kt-instagram-settings'); ?>";
                    		});
                        }
                    });
                })(jQuery);
            </script>
            
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'kt_instagram_group', // Option group
            'kt_instagram_option', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            __('Settings', THEME_LANG), // Title
            array( $this, 'print_section_info' ), // Callback
            'instagram-settings' // Page
        );
        
        if($this->access_token && $this->username ){
            
            add_settings_field(
                'instagram_access_token', // ID
                __('Instagram access token', THEME_LANG), // Title 
                array( $this, 'instagram_access_token_callback' ), // Callback
                'instagram-settings', // Page
                'setting_section_id' // Section           
            );
            
            add_settings_field(
                'instagram_usernameinfo', // ID
                __('Instagram access token', THEME_LANG), // Title 
                array( $this, 'instagram_usernameinfo_callback' ), // Callback
                'instagram-settings', // Page
                'setting_section_id' // Section           
            );
            
            add_settings_field(
                'instagram_userid', // ID
                __('Instagram ID', THEME_LANG), // Title 
                array( $this, 'instagram_usernameid_callback' ), // Callback
                'instagram-settings', // Page
                'setting_section_id' // Section           
            );
            
        }else{
            
            add_settings_field(
                'instagram_setup', // ID
                __('Instagram Setup', THEME_LANG), // Title 
                array( $this, 'instagram_setup_callback' ), // Callback
                'instagram-settings', // Page
                'setting_section_id' // Section           
            );
            
            add_settings_field(
                'client_id', // ID
                __('Instagram Client ID', THEME_LANG), // Title 
                array( $this, 'client_id_callback' ), // Callback
                'instagram-settings', // Page
                'setting_section_id' // Section           
            );
            
            add_settings_field(
                'username', // ID
                __('Instagram Username ID', THEME_LANG), // Title 
                array( $this, 'username_callback' ), // Callback
                'instagram-settings', // Page
                'setting_section_id' // Section           
            );
        }
        
        
    }
    public function instagram_access_token_callback(){
        echo $this->access_token;
    }
    
    public function instagram_usernameinfo_callback(){
        echo $this->username;
    }
    
    public function instagram_usernameid_callback(){
        echo $this->userid;
    }
    
    public function instagram_setup_callback()
    {
        printf(
            '<p class="description"><b>%s:</b> %s</p>',
            __('Step 1', THEME_LANG),
            __('Head over to <a href="http://instagram.com/developer" target="_bank">http://instagram.com/developer</a>. Login to your account. Then click on Manage Clients, and finally on Register a New Client', THEME_LANG)
        );
        
        printf(
            '<p class="description"><b>%s:</b> %s</p>',
            __('Step 2', THEME_LANG),
            __('Fill out the required fields for registering a new client id making sure to set <b>Website URL</b> and <b>Redirect URI</b> in text below. Once all of the fields are filled out click submit.', THEME_LANG)
        );
        
        printf(
            '<p class="description"><b>%s:</b> %s</p>',
            __('Step 2', THEME_LANG),
            __('Add the client id you generated and your username to the form.', THEME_LANG)
        );
        
        
        echo "<hr/>";
        
        printf(
            '<p class="description"><b>%s:</b> %s</p>',
            __('Website URL', THEME_LANG),
            get_site_url()
        );
        printf(
            '<p class="description"><b>%s:</b> %s</p>',
            __('Redirect URI(s)', THEME_LANG),
            admin_url( 'options-general.php?page=kt-instagram-settings')
        );   
    }
    
    /** 
     * Get the settings option array and print one of its values
     */
    public function client_id_callback()
    {
        printf(
            '<input type="text" id="client_id" size="40" name="client_id" value="%s" />',
            isset( $this->client_id ) ? esc_attr( $this->client_id) : ''
        );
    }
    
    public function username_callback()
    {
        printf(
            '<input type="text" id="username" size="40" name="username" value="%s" />',
            isset( $this->username ) ? esc_attr( $this->username) : ''
        );
    }
    
    public function print_section_info(){ }
    
}

$kt_instagram_settings = new KT_Instagram_Settings();


add_action( 'wp_ajax_instagram_access_token', 'kt_instagram_access_token' );
function kt_instagram_access_token() {
    update_option('kt_instagram_access', sanitize_text_field($_POST['access']));
    
    $kt_instagram = new KT_Instagram();
    $userid = $kt_instagram->getUserMedia();
    
	wp_die();
}
