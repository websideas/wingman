<?php
/*
Plugin Name:  KT Insgagram
Plugin URI:   http://kitethemes.com/
Description:  Theme Cephenus Insgagram
Version:      1.1
Author:       KiteThemes
Author URI:   http://kitethemes.com/

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


    }

    public function enqueue_scripts(){
        wp_enqueue_style( 'kt_mailchimp', KTM_PLUGIN_URL . '/assets/styles.css', array());
        wp_enqueue_script( 'kt_mailchimp-script', KTM_PLUGIN_URL . '/assets/functions.js', array( 'jquery' ), null, true );
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
                $opt_in = in_array($_POST['opt_in'], array('1', 'true', 'y', 'on'));

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
