<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * KT_Facebook widget class
 *
 * @since 1.0
 */
class Widget_KT_Mailchimp extends WP_Widget {

    var $api_key;

    public function __construct() {
        $widget_ops = array('classname' => 'widget_kt_mailchimp', 'description' => __( "Subscribe to mailing list.", THEME_LANG) );
        parent::__construct('kt_mailchimp', __('KT: Mailchimp', THEME_LANG), $widget_ops);
        $this->alt_option_name = 'widget_kt_mailchimp';

        $this->api_key = kt_option('mailchimp_api_key' );

        // Add ajax for frontend
        add_action( 'wp_ajax_frontend_mailchimp', array( $this, 'frontend_mailchimp_callback') );
        add_action( 'wp_ajax_nopriv_frontend_mailchimp', array( $this, 'frontend_mailchimp_callback') );

    }


    public function widget($args, $instance) {
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        
        if($instance['list']){
            echo $args['before_widget'];
            if ( $title ) {
                echo $args['before_title'] . $title . $args['after_title'];
            }

            if ( isset ( $this->api_key ) && !empty ( $this->api_key ) ) {

                if(!$instance['message'])
                    $instance['message'] = __('Success!  Check your inbox or spam folder for a message containing a confirmation link.', THEME_LANG);

                $output = $name = '';

                $output .= ($instance['text_before']) ? '<div class="mailchimp-before">'.$instance['text_before'].'</div>' : '';

                $email = '<div class="mailchimp-input-email"><input name="email" class="mailchimp-email" type="text" placeholder="'.__('E-mail address*', THEME_LANG).'"/></div>';
                $button = '<div class="mailchimp-input-button"><button class="btn btn-default mailchimp-submit" type="submit">'.__('Subscribe', THEME_LANG).'</button></div>';

                if(!$instance['disable_names']){
                    $name .= '<div class="mailchimp-input-name clearfix">';
                    $name .= '<div class="mailchimp-col"><input name="firstname" class="mailchimp-firstname" type="text" placeholder="'.__('First Name*', THEME_LANG).'"/></div>';
                    $name .= '<div class="mailchimp-col"><input name="lastname" class="mailchimp-lastname" type="text" placeholder="'.__('Last Name', THEME_LANG).'"/></div>';
                    $name .= '</div>';
                }

                $text_mailchimp = '%1$s %2$s <div class="mailchimp-input-button">%3$s</div>';

                $output .= '<form class="mailchimp-form clearfix mailchimp-layout-'.esc_attr($instance['layout']).'" action="#" method="post">';
                $output .= sprintf( $text_mailchimp, $name, $email, $button );
                $output .= '<input type="hidden" name="action" value="signup"/>';
                $output .= '<input type="hidden" name="list_id" value="'.esc_attr($instance['list']).'"/>';
                $output .= '<input type="hidden" name="opt_in" value="'.esc_attr($instance['opt_in']).'"/>';
                $output .= '<div class="mailchimp-success">'.$instance['message'].'</div>';
                $output .= '<div class="mailchimp-error"></div>';
                $output .= '</form>';

                echo $output;

            }else{
                echo '<p>'.__("Please enter your mailchimp API key in theme option", THEME_LANG).'</p>';
            }
            
            echo $args['after_widget'];
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
        $errors = array();

        $email = strip_tags($_POST['email']);
        $firstname = isset($_POST['firstname']) ? strip_tags($_POST['firstname']) : '';
        $lastname = isset($_POST['lastname']) ? strip_tags($_POST['lastname']) : '';

        if(strlen(trim($lastname)) <= 0) {
            $lastname = '';
        }

        if(isset($_POST['firstname']) && strlen(trim($firstname)) <= 0) {
            $errors[] = __('your name is required field.', THEME_LANG);
        }
        if (!$email) {
            $errors[] = __('Email address is required field.', THEME_LANG);
        }elseif(!is_email($email)){
            $errors[] = __('Email address seems invalid.', THEME_LANG);
        }

        if(count($errors)){
            $output['msg'] = '<ul>';
            foreach($errors as $error){
                $output['msg'] .= '<li>'.$error.'</li>';
            }
            $output['msg'] .= '</ul>';
        }else{
            if ( isset ( $this->api_key ) && !empty ( $this->api_key ) ) {
                $mcapi = new MCAPI($this->api_key);
                $opt_in = in_array($_POST['opt_in'], array('1', 'true', 'y', 'on'));

                $mcapi->listSubscribe($_POST['list_id'], $email, null, 'html', $opt_in);
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
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['list'] = strip_tags($new_instance['list']);


        if ( current_user_can('unfiltered_html') ){
            $instance['text_before'] =  $new_instance['text_before'];
            $instance['message'] =  $new_instance['message'];
        }else{
            $instance['text_before'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text_before']) ) );
            $instance['message'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['message']) ) );
        }

        $instance['opt_in'] = isset( $new_instance['opt_in'] ) ? (bool) $new_instance['opt_in'] : false;
        $instance['disable_names'] = isset( $new_instance['disable_names'] ) ? (bool) $new_instance['disable_names'] : false;
        $instance['layout'] = (int) $new_instance['layout'];

        return $instance;
    }

    public function form( $instance ) {

        $defaults = array( 'title' => __( 'Newsletter' , THEME_LANG), 'text_before' => '', 'message' => __('Success!  Check your inbox or spam folder for a message containing a confirmation link.', THEME_LANG), 'list' => '', 'opt_in' => false, 'disable_names' => false, 'layout' => 1);
        $instance = wp_parse_args( (array) $instance, $defaults );

        $title = strip_tags($instance['title']);
        $lists_arr = array(0 => __('Select option', THEME_LANG));



        if ( isset ( $this->api_key ) && !empty ( $this->api_key ) ) {
            $mcapi = new MCAPI($this->api_key);
            $lists = $mcapi->lists();
            if($lists['data']){
                foreach ($lists['data'] as $item) {
                    $lists_arr[$item['id']] = $item['name'];
                }
            }
        }

        ?>

        <p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
        <p>
            <label for="<?php echo $this->get_field_id( 'text_before' ); ?>"><?php _e( 'Text before:' ); ?></label>
            <textarea class="widefat" rows="5" cols="20" id="<?php echo $this->get_field_id('text_before'); ?>" name="<?php echo $this->get_field_name('text_before'); ?>"><?php echo $instance['text_before'] ?></textarea></p>

        <p><label for="<?php echo $this->get_field_id('list'); ?>"><?php _e('Email Lists:',THEME_LANG); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('list'); ?>" name="<?php echo $this->get_field_name('list'); ?>">
                <?php foreach($lists_arr as $key => $val){ ?>
                <option <?php selected( $instance['list'], $key ); ?> value="<?php echo $key ?>"><?php echo $val ?></option>
                <?php } ?>
            </select>
        </p>

        <p><input class="checkbox" type="checkbox" <?php checked( $instance['opt_in'] ); ?> id="<?php echo $this->get_field_id( 'opt_in' ); ?>" name="<?php echo $this->get_field_name( 'opt_in' ); ?>" />
            <label for="<?php echo $this->get_field_id( 'opt_in' ); ?>"><?php _e( 'Double Opt In:', THEME_LANG ); ?></label>
        <br><small><?php _e('Require that users confirm their subscriptions?', THEME_LANG) ?></small></p>

        <p><input class="checkbox" type="checkbox" <?php checked( $instance['disable_names'] ); ?> id="<?php echo $this->get_field_id( 'disable_names' ); ?>" name="<?php echo $this->get_field_name( 'disable_names' ); ?>" />
            <label for="<?php echo $this->get_field_id( 'disable_names' ); ?>"><?php _e( 'Disable Names:', THEME_LANG ); ?></label>
            <br><small><?php _e('Disable the First and Last Name fields?', THEME_LANG) ?></small></p>

        <p>
            <label for="<?php echo $this->get_field_id( 'message' ); ?>"><?php _e( 'Success message:', THEME_LANG ); ?></label>
            <textarea class="widefat" rows="5" cols="20" id="<?php echo $this->get_field_id('message'); ?>" name="<?php echo $this->get_field_name('message'); ?>"><?php echo $instance['message'] ?></textarea></p>

        <p><label for="<?php echo $this->get_field_id('layout'); ?>"><?php _e('Layout:',THEME_LANG); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('layout'); ?>" name="<?php echo $this->get_field_name('layout'); ?>">
                <option <?php selected( $instance['layout'], '1' ); ?> value="1"><?php _e('Layout 1',THEME_LANG); ?></option>
                <option <?php selected( $instance['layout'], '2' ); ?> value="2"><?php _e('Layout 2',THEME_LANG); ?></option>
            </select>
        </p>

        <p class="description"><?php _e('For use widget, You need config Mail Chimp API Key in Theme options', THEME_LANG); ?></p>

    <?php
    }
}

/**
 * Register KT_Facebook widget
 *
 *
 */

register_widget('Widget_KT_Mailchimp');
