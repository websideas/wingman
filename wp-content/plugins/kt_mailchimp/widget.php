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

    var $options;

    public function __construct() {
        $widget_ops = array('classname' => 'widget_kt_mailchimp', 'description' => __( "Subscribe to mailing list.", 'kt_mailchimp') );
        parent::__construct('kt_mailchimp', __('KT: Mailchimp', 'kt_mailchimp'), $widget_ops);
        $this->alt_option_name = 'widget_kt_mailchimp';

        $this->options = get_option( 'kt_mailchimp_option' );

    }


    public function widget($args, $instance) {
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

        if($instance['list']){
            echo $args['before_widget'];
            if ( $title ) {
                echo $args['before_title'] . $title . $args['after_title'];
            }

            if ( isset ( $this->options['api_key'] ) && !empty ( $this->options['api_key'] ) ) {

                if(!$this->options['messages_successfully'])
                    $this->options['messages_successfully'] = __('Thank you, your sign-up request was successful! Please check your email inbox to confirm.', 'kt_mailchimp');

                $output = $name = '';

                $output .= ($instance['text_before']) ? '<div class="mailchimp-before">'.$instance['text_before'].'</div>' : '';

                $email = '<div class="mailchimp-input-email"><input name="email" class="mailchimp-email" type="text" placeholder="'.__('E-mail address*', 'kt_mailchimp').'"/></div>';
                $button = '<div class="mailchimp-input-button"><button class="mailchimp-submit" type="submit">'.__('Subscribe', 'kt_mailchimp').'</button></div>';

                if(!$instance['disable_names']){
                    $name .= '<div class="mailchimp-input-name clearfix">';
                    $name .= '<div class="mailchimp-col"><input name="firstname" class="mailchimp-firstname" type="text" placeholder="'.__('First Name*', 'kt_mailchimp').'"/></div>';
                    $name .= '<div class="mailchimp-col"><input name="lastname" class="mailchimp-lastname" type="text" placeholder="'.__('Last Name', 'kt_mailchimp').'"/></div>';
                    $name .= '</div>';
                }

                if($instance['layout'] = 2){
                    $text_mailchimp = '%1$s %2$s %3$s';
                }else{
                    $text_mailchimp = '%1$s <div class="mailchimp-email-button">%2$s %3$s</div>';
                }


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
                echo '<p>'.__("Please enter your mailchimp API key in theme option", 'kt_mailchimp').'</p>';
            }

            echo $args['after_widget'];
        }
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

        $defaults = array( 'title' => __( 'Newsletter' , 'kt_mailchimp'), 'text_before' => '', 'message' => __('Success!  Check your inbox or spam folder for a message containing a confirmation link.', 'kt_mailchimp'), 'list' => '', 'opt_in' => false, 'disable_names' => false, 'layout' => 1);
        $instance = wp_parse_args( (array) $instance, $defaults );

        $title = strip_tags($instance['title']);
        $lists_arr = array(0 => __('Select option', 'kt_mailchimp'));



        if ( isset ( $this->options['api_key'] ) && !empty ( $this->options['api_key'] ) ) {
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

        <p><label for="<?php echo $this->get_field_id('list'); ?>"><?php _e('Email Lists:','kt_mailchimp'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('list'); ?>" name="<?php echo $this->get_field_name('list'); ?>">
                <?php foreach($lists_arr as $key => $val){ ?>
                <option <?php selected( $instance['list'], $key ); ?> value="<?php echo $key ?>"><?php echo $val ?></option>
                <?php } ?>
            </select>
        </p>

        <p><input class="checkbox" type="checkbox" <?php checked( $instance['opt_in'] ); ?> id="<?php echo $this->get_field_id( 'opt_in' ); ?>" name="<?php echo $this->get_field_name( 'opt_in' ); ?>" />
            <label for="<?php echo $this->get_field_id( 'opt_in' ); ?>"><?php _e( 'Double Opt In:', 'kt_mailchimp' ); ?></label>
        <br><small><?php _e('Require that users confirm their subscriptions?', 'kt_mailchimp') ?></small></p>

        <p><input class="checkbox" type="checkbox" <?php checked( $instance['disable_names'] ); ?> id="<?php echo $this->get_field_id( 'disable_names' ); ?>" name="<?php echo $this->get_field_name( 'disable_names' ); ?>" />
            <label for="<?php echo $this->get_field_id( 'disable_names' ); ?>"><?php _e( 'Disable Names:', 'kt_mailchimp' ); ?></label>
            <br><small><?php _e('Disable the First and Last Name fields?', 'kt_mailchimp') ?></small></p>

        <p><label for="<?php echo $this->get_field_id('layout'); ?>"><?php _e('Layout:','kt_mailchimp'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('layout'); ?>" name="<?php echo $this->get_field_name('layout'); ?>">
                <option <?php selected( $instance['layout'], '1' ); ?> value="1"><?php _e('Layout 1','kt_mailchimp'); ?></option>
                <option <?php selected( $instance['layout'], '2' ); ?> value="2"><?php _e('Layout 2','kt_mailchimp'); ?></option>
            </select>
        </p>

        <p class="description"><?php _e('For use widget, You need config Mail Chimp API Key', 'kt_mailchimp'); ?></p>

    <?php
    }
}

/**
 * Register KT_Facebook widget
 *
 *
 */

// register Foo_Widget widget
function register_KT_Mailchimp_widget() {
    register_widget( 'Widget_KT_Mailchimp' );
}
add_action( 'widgets_init', 'register_KT_Mailchimp_widget' );

