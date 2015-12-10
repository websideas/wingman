<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

class KT_MailChimp_Settings
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        $this->options = get_option( 'kt_mailchimp_option' );

        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );

    }



    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            __('KT MailChimp Settings', 'kt_mailchimp'),
            __('KT MailChimp', 'kt_mailchimp'),
            'manage_options',
            'kt-mailchimp-settings',
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        ?>
        <div class="wrap">
            <h2><?php _e('Mail Chimp Settings', 'kt_mailchimp' ); ?></h2>
            <form method="post" action="options.php" style="max-width: 1000px;">
                <?php
                // This prints out all hidden setting fields
                settings_fields( 'kt_mailchimp_group' );
                do_settings_sections( 'mailchimp-settings' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {
        register_setting(
            'kt_mailchimp_group', // Option group
            'kt_mailchimp_option', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            __('Settings', 'kt_mailchimp'), // Title
            array( $this, 'print_section_info' ), // Callback
            'mailchimp-settings' // Page
        );

        add_settings_field(
            'api_key', // ID
            __('Mail Chimp API Key', 'kt_mailchimp'), // Title
            array( $this, 'api_key_callback' ), // Callback
            'mailchimp-settings', // Page
            'setting_section_id' // Section
        );

        $api_key = $this->options['api_key'];
        if ( isset ( $api_key ) && !empty ( $api_key ) ) {
            add_settings_field(
                'email_lists', // ID
                __('Email Lists', 'kt_mailchimp'), // Title
                array( $this, 'email_lists_callback' ), // Callback
                'mailchimp-settings', // Page
                'setting_section_id' // Section
            );

            add_settings_field(
                'other_option', // ID
                __('Other option', 'kt_mailchimp'), // Title
                array( $this, 'other_option_callback' ), // Callback
                'mailchimp-settings', // Page
                'setting_section_id' // Section
            );

            add_settings_section(
                'setting_section_message', // ID
                __('Messages', 'kt_mailchimp'), // Title
                array( $this, 'print_section_info' ), // Callback
                'mailchimp-settings' // Page
            );

            add_settings_field(
                'messages_successfully', // ID
                __('Successfully subscribed', 'kt_mailchimp'), // Title
                array( $this, 'messages_successfully_callback' ), // Callback
                'mailchimp-settings', // Page
                'setting_section_message' // Section
            );

        }




    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        if( isset( $input['api_key'] ) )
            $new_input['api_key'] = sanitize_text_field( $input['api_key'] );

        $new_input['messages_successfully'] = $input['messages_successfully'];


        return $new_input;
    }

    /**
     * Print the Section text
     */
    public function print_section_info(){}

    /**
     * Get the settings option array and print one of its values
     */
    public function api_key_callback()
    {
        printf(
            '<input type="text" id="api_key" class="large-text" size="40" name="kt_mailchimp_option[api_key]" value="%s" />',
            isset( $this->options['api_key'] ) ? esc_attr( $this->options['api_key']) : ''
        );
        printf(
            '<p class="description">%s</p>',
            __('Enter your mail Chimp API key to enable a newsletter signup option with the registration form.', 'kt_mailchimp')
        );
    }
    public function email_lists_callback(){
        $api_key = $this->options['api_key'];
        if ( isset ( $api_key ) && !empty ( $api_key ) ) {
            $mcapi = new MCAPI($api_key);
            $lists = $mcapi->lists();

            echo "<ul class='kt_mailchimp_lists'>";
            foreach ($lists['data'] as $key => $item) {
                printf(
                    '<li>%1$s (ID: %2$s, Subscribers: %3$s)<br/><input type="text" onclick="this.select()" class="large-text" style="font-weight:bold;text-align:left;" size="40" value=\'[kt_mailchimp list="%2$s"]\' readonly="readonly"></li>',
                    $item['name'],
                    $item['id'],
                    $item['stats']['member_count']

                );
            }
            echo "</ul>";

            printf(
                '<p class="description">%s</p>',
                __('Place the short code shown below any list in a post or page to display the signup form, or use the dedicated widget.', 'kt_mailchimp')
            );
        }
    }

    public function other_option_callback(){
        echo "<ul class='kt_mailchimp_option'>";
        echo '<li><strong>'.__('Double Opt In', 'kt_mailchimp').'</strong> : opt_in="yes". ( EX: yes or no)</li>';
        echo '<li><strong>'.__('Disable Names', 'kt_mailchimp').'</strong> : disable_names="yes". ( EX: yes or no)</li>';
        echo '<li><strong>'.__('Layout', 'kt_mailchimp').'</strong> : layout="1" (EX: 1, 2)</li>';
        echo "</ul>";
    }

    function messages_successfully_callback(){
        printf(
            '<input type="text" id="messages_successfully" class="large-text" size="40" name="kt_mailchimp_option[messages_successfully]" value="%s" />',
            isset( $this->options['messages_successfully'] ) ? esc_attr( $this->options['messages_successfully']) : __('Thank you, your sign-up request was successful! Please check your email inbox to confirm.', 'kt_mailchimp')
        );
        printf(
            '<p class="description">%s</p>',
            __('The text that shows when an email address is successfully subscribed to the selected list.', 'kt_mailchimp')
        );
    }


}


$kt_mailchimp_settings = new KT_MailChimp_Settings();
