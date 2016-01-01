<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * KT_Twitter widget class
 *
 * @since 1.0
 */
class Widget_KT_Twitter extends WP_Widget {
    
    private $consumer_key;
    private $consumer_secret;
    private $access_key;
    private $access_secret;
    private $username;
    
    public function __construct() {
        $widget_ops = array('classname' => 'widget_kt_twitter', 'description' => __( "Display recent tweets.", KT_THEME_LANG) );
        parent::__construct('kt_twitter', __('KT: Twitter', KT_THEME_LANG), $widget_ops);
        $this->alt_option_name = 'widget_kt_twitter';
        
        $this->consumer_key = kt_option( 'twitter_consumer_key' );
        $this->consumer_secret = kt_option('twitter_consumer_secret');
        $this->access_key = kt_option('twitter_access_key');
        $this->access_secret = kt_option('twitter_access_secret');
        $this->username = kt_option('twitter');
        
        
    }

    public function widget($args, $instance) {
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

        echo $args['before_widget'];
        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        if($this->consumer_key && $this->consumer_secret && $this->access_key && $this->access_secret && $this->username){
            if(!class_exists('TwitterOAuth')){
                require_once ( KT_FW_CLASS . 'twitteroauth.php' );
            }
            
            $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
            if ( ! $number )
                $number = 5;
            
            
            
            $connection = new TwitterOAuth($this->consumer_key, $this->consumer_secret, $this->access_key, $this->access_secret);
            $query = 'https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name='.$this->username.'&count='.$number;
            $content = $connection->get($query);
            
            if(!empty($content->errors)){
                //Couldn't retrieve tweets! Wrong username?
                printf( '<strong>%s</strong>',$content->errors[0]->message );
            }else{

                $tag = ($instance['layout'] =='list') ? 'li' : 'div';
                $content_twitter = '';
                if(is_array($content)){
                    foreach($content as $tweet){ ?>
                        <?php ob_start(); ?>
                        <<?php echo $tag ?> class="kt-twitter-wrapper">
                            <div class="kt-twitter-content">
                                <div class="kt-twitter-status"><?php echo $tweet->text ?></div>
                                <div class="timestamp tw_timestamp"><?php echo date('d M / H:i',strtotime($tweet->created_at)); ?></div>
                            </div>
                            <div class="kt-twitter-tool">
                                <span><a target="_blank" href="https://twitter.com/intent/tweet?in_reply_to=<?php echo $tweet->id; ?>">
                                    <?php _e('Reply', KT_THEME_LANG); ?></a></span>
                                <span><a target="_blank" href="https://twitter.com/intent/retweet?tweet_id=<?php echo $tweet->id; ?>">
                                    <?php _e('Retweet', KT_THEME_LANG); ?></a></span>
                                <span><a target="_blank" href="https://twitter.com/intent/favorite?tweet_id=<?php echo $tweet->id; ?>">
                                    <?php _e('Favorite', KT_THEME_LANG); ?></a></span>
                            </div>
                        </<?php echo $tag ?>>
                        <?php
                            $content_twitter .= ob_get_contents();
                            ob_end_clean();
                        ?>
                    <?php }
                }
                if(($instance['layout'] =='list')){
                    echo '<ul class="kt-twitter-'.$instance['layout'].'">'.$content_twitter.'</ul>';    
                }else{
                    $atts = array( 'desktop' => 1, 'tablet' => 1, 'mobile' => 1, 'navigation' => "false", 'margin' => 0, 'pagination' => 'true');
                    $carousel_ouput = kt_render_carousel($atts);
                    echo str_replace('%carousel_html%', $content_twitter, $carousel_ouput);    
                }
            }
        }else{
            printf(
                '<strong>%s</strong>',
                __('Please config twitter settings in theme option', KT_THEME_LANG) 
            );
        }
        
        echo $args['after_widget'];
    }


    /*
     * Transform Tweet plain text into clickable text
     */
    function parseTweet($text) {
        $text = preg_replace('#http://[a-z0-9._/-]+#i', '<a  target="_blank" href="$0">$0</a>', $text); //Link
        $text = preg_replace('#@([a-z0-9_]+)#i', '@<a  target="_blank" href="http://twitter.com/$1">$1</a>', $text); //usernames
        $text = preg_replace('# \#([a-z0-9_-]+)#i', ' #<a target="_blank" href="http://search.twitter.com/search?q=%23$1">$1</a>', $text); //Hashtags
        $text = preg_replace('#https://[a-z0-9._/-]+#i', '<a  target="_blank" href="$0">$0</a>', $text); //Links
        return $text;
    }

    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        
        $instance['number'] = (int) $new_instance['number'];
        if(!$new_instance['number']){
            $instance['number'] = 5;
        }
        
        if ( in_array( $new_instance['layout'], array( 'list', 'carousel' ) ) ) {
            $instance['layout'] = $new_instance['layout'];
        } else {
            $instance['layout'] = 'list';
        }
        return $instance;
    }


    public function form( $instance ) {

        $defaults = array( 'title' => __( 'Tweets' , KT_THEME_LANG), 'number' => 5, 'layout' => 'list');
        $instance = wp_parse_args( (array) $instance, $defaults );

        $title = strip_tags($instance['title']);
        
        
        ?>

        <p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
        <?php if($this->consumer_key && $this->consumer_secret && $this->access_key && $this->access_secret){ ?>
            <p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of tweets to show:', KT_THEME_LANG ); ?></label>
                <input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $instance['number']; ?>" class="widefat" /></p>
            
            <p><label for="<?php echo $this->get_field_id('layout'); ?>"><?php _e('Layout:',KT_THEME_LANG); ?></label>
                <select class="widefat" id="<?php echo $this->get_field_id('layout'); ?>" name="<?php echo $this->get_field_name('layout'); ?>">
                    <option <?php selected( $instance['layout'], 'list' ); ?> value="list"><?php _e('List',KT_THEME_LANG); ?></option>
                    <option <?php selected( $instance['layout'], 'carousel' ); ?> value="carousel"><?php _e('Carousel',KT_THEME_LANG); ?></option>
                </select>
            </p>
            
            
        <?php }else{ 
            printf(
                '<p>Please config twitter in %shere%s for use widget</p>',
                '<a href="'.admin_url( 'admin.php?page=theme_options').'">',
                '</a>'
            );    
        } 
        ?>
    <?php
    }
}

/**
 * Register Widget_KT_Twitter widget
 *
 *
 */

register_widget('Widget_KT_Twitter');
