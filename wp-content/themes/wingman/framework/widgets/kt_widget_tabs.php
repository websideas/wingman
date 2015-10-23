<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * KT tabs widget class
 *
 * @since 1.0
 */
class WP_Widget_KT_Tabs extends WP_Widget {

	public function __construct() {

        $widget_ops = array('classname' => 'widget_kt_post_tabs', 'description' => __( "Display popular posts, recent posts and comments in tabbed format.") );
        parent::__construct('kt_post_tabs', __('KT: Post Tabs', THEME_LANG), $widget_ops);
        $this->alt_option_name = 'widget_kt_post_tabs';

        add_action( 'save_post', array($this, 'flush_widget_cache') );
        add_action( 'deleted_post', array($this, 'flush_widget_cache') );
        add_action( 'switch_theme', array($this, 'flush_widget_cache') );

	}

	public function widget( $args, $instance ) {
        $cache = array();
        if ( ! $this->is_preview() ) {
            $cache = wp_cache_get( 'widget_kt_post_tabs', 'widget' );
        }

        if ( ! is_array( $cache ) ) {
            $cache = array();
        }

        if ( ! isset( $args['widget_id'] ) ) {
            $args['widget_id'] = $this->id;
        }

        if ( isset( $cache[ $args['widget_id'] ] ) ) {
            echo $cache[ $args['widget_id'] ];
            return;
        }

        ob_start();

        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        
        $select_rand = isset( $instance['select_rand'] ) ? $instance['select_rand'] : true;
        $select_recent = isset( $instance['select_recent'] ) ? $instance['select_recent'] : true;
        $select_comments = isset( $instance['select_comments'] ) ? $instance['select_comments'] : true;
        
        $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
        if ( ! $number )
            $number = 5;
        $show_thumbnail = isset( $instance['show_thumbnail'] ) ? $instance['show_thumbnail'] : true;
            
        echo $args['before_widget'];
        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }



        if( $select_rand || $select_recent || $select_comments ){

            $rand = rand();

            $tabs = array();
            if($select_rand)  $tabs[] = 'rand';
            if($select_recent)  $tabs[] = 'recent';
            //if($select_popular)  $tabs[] = 'popular';


            ?>
            <div class="kt_widget_tabs">
                <ul class="clearfix kt-tabs-nav">
                    <?php if( $select_rand ){ ?><li><a href="#kt_tab_rand<?php echo $rand; ?>"><?php _e( 'Random', THEME_LANG ); ?></a></li><?php } ?>
                    <?php if( $select_recent ){ ?><li><a href="#kt_tab_recent<?php echo $rand; ?>"><?php _e( 'Recent', THEME_LANG ); ?></a></li><?php } ?>
                    <?php if( $select_comments ){ ?><li><a href="#kt_tab_comments<?php echo $rand; ?>"><i class="icon_chat_alt"></i> </a></li><?php } ?>
                </ul>
                <div class="tabs-container">
                    <?php
                    if(count($tabs)){
                        $argsp =  array(
                            'posts_per_page'      => $number,
                            'ignore_sticky_posts' => true,
                            'order'               => 'DESC',
                        );
                        foreach($tabs as $tab){
                            $argsn = $argsp;
                            if($tab == 'rand'){
                                $argsn['orderby'] = 'rand';
                            }elseif($tab == 'recent'){
                                $argsn['orderby'] = 'date';
                            }elseif($tab == 'popular'){

                            }

                            $query = new WP_Query( apply_filters( 'widget_posts_args', $argsn ) );

                            ?>
                            <div id="kt_tab_<?php echo $tab ?><?php echo $rand; ?>" class="kt_tabs_content">
                                <?php if ($query->have_posts()){ ?>
                                    <ul>
                                        <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                                            <li <?php post_class('article-widget clearfix'); ?>>
                                                <?php if($show_thumbnail){ kt_post_thumbnail_image( 'small', 'img-responsive' ); } ?>
                                                <div class="article-attr">
                                                    <h3 class="title"><a href="<?php the_permalink(); ?>"><?php get_the_title() ? the_title() : the_ID(); ?></a></h3>
                                                    <div class="entry-meta-data">
                                                        <?php  kt_entry_meta_time();  ?>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php endwhile; wp_reset_postdata(); ?>
                                    </ul>
                                <?php } ?>
                            </div>
                            <?php
                        }
                    }
                    ?>




                    <?php if( $select_comments ){ ?>
                        <div id="kt_tab_comments<?php echo $rand; ?>" class="kt_tabs_content">
                        <?php
                            $args_comments = array(
                                'orderby' => 'date',
                                'number' => $number,
                                'status' => 'approve'
                            );
                            $comments_query = new WP_Comment_Query;
                            $comments = $comments_query->query( $args_comments );

                            if ( $comments ) { ?>
                                <ul>
                                    <?php foreach ( $comments as $comment ) { ?>
                                        <li class="clearfix">
                                            <?php if($show_thumbnail){ ?>
                                                <a class="entry-thumb" href="<?php echo get_comment_link($comment->comment_ID); ?>">
                                                    <?php echo get_avatar( $comment->comment_author_email, 70 ); ?>
                                                </a>
                                            <?php } ?>
                                            <div class="article-attr">
                                                <h3 class="title">
                                                    <a href="<?php echo get_comment_link($comment->comment_ID); ?>">
                                                        <span class="comment_author"><?php echo get_comment_author( $comment->comment_ID ); ?> </span>
                                                    </a>
                                                </h3>
                                                <div class="kt-comment-content">
                                                    <?php
                                                        $str = $comment->comment_content;
                                                        if (mb_strlen($str) > 40) {
                                                            echo mb_substr($str, 0, 40).'...';
                                                        } else {
                                                            echo $str;
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                        </li>
                                    <?php } ?>
                                </ul>
                            <?php }else{
                                printf(
                                    '<strong>%s</strong>',
                                    __('No comments found.', THEME_LANG)
                                );
                            } ?>
                        </div>
                    <?php } ?>


                </div>
            </div>
        <?php }
        
        echo $args['after_widget'];

        if ( ! $this->is_preview() ) {
            $cache[ $args['widget_id'] ] = ob_get_flush();
            wp_cache_set( 'widget_kt_post_tabs', $cache, 'widget' );
        } else {
            ob_end_flush();
        }

	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        
        $instance['select_rand'] = isset( $new_instance['select_rand'] ) ? (bool) $new_instance['select_rand'] : false;
        $instance['select_recent'] = isset( $new_instance['select_recent'] ) ? (bool) $new_instance['select_recent'] : false;
        $instance['select_comments'] = isset( $new_instance['select_comments'] ) ? (bool) $new_instance['select_comments'] : false;
        
        $instance['number'] = (int) $new_instance['number'];
        $instance['show_thumbnail'] = isset( $new_instance['show_thumbnail'] ) ? (bool) $new_instance['show_thumbnail'] : false;

        $this->flush_widget_cache();

        $alloptions = wp_cache_get( 'alloptions', 'options' );
        if ( isset($alloptions['widget_kt_post_tabs']) )
            delete_option('widget_kt_post_tabs');

        return $instance;
	}

    public function flush_widget_cache() {
        wp_cache_delete('widget_kt_post_tabs', 'widget');
    }

	public function form( $instance ) {
		$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : __( 'Widget Tabs' , THEME_LANG);
        
        $select_rand = isset( $instance['select_rand'] ) ? (bool) $instance['select_rand'] : true;
        $select_recent = isset( $instance['select_recent'] ) ? (bool) $instance['select_recent'] : true;
        $select_comments = isset( $instance['select_comments'] ) ? (bool) $instance['select_comments'] : true;
        
        $number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        $show_thumbnail = isset( $instance['show_thumbnail'] ) ? (bool) $instance['show_thumbnail'] : true;
		
	?>
    <p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
    
    <h4><?php _e('Select Tabs', THEME_LANG); ?></h4>
    <p>
        <input class="checkbox" type="checkbox" <?php checked( $select_rand ); ?> id="<?php echo $this->get_field_id( 'select_rand' ); ?>" name="<?php echo $this->get_field_name( 'select_rand' ); ?>" />
        <label for="<?php echo $this->get_field_id( 'select_rand' ); ?>"><?php _e( 'Display Random Posts',THEME_LANG ); ?></label>
    </p>
    <p>
        <input class="checkbox" type="checkbox" <?php checked( $select_recent ); ?> id="<?php echo $this->get_field_id( 'select_recent' ); ?>" name="<?php echo $this->get_field_name( 'select_recent' ); ?>" />
        <label for="<?php echo $this->get_field_id( 'select_recent' ); ?>"><?php _e( 'Display Recent Posts',THEME_LANG ); ?></label>
    </p>
    <p>
        <input class="checkbox" type="checkbox" <?php checked( $select_comments ); ?> id="<?php echo $this->get_field_id( 'select_comments' ); ?>" name="<?php echo $this->get_field_name( 'select_comments' ); ?>" />
        <label for="<?php echo $this->get_field_id( 'select_comments' ); ?>"><?php _e( 'Display Comments',THEME_LANG ); ?></label>
    </p>
    
    <h4><?php _e('Options Tabs', THEME_LANG); ?></h4>
    
    <p>
        <label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
        <input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" class="widefat" />
    </p>
    <p>
        <input class="checkbox" type="checkbox" <?php checked( $show_thumbnail ); ?> id="<?php echo $this->get_field_id( 'show_thumbnail' ); ?>" name="<?php echo $this->get_field_name( 'show_thumbnail' ); ?>" />
        <label for="<?php echo $this->get_field_id( 'show_thumbnail' ); ?>"><?php _e( 'Show Thumbnail (Avatar Comments)',THEME_LANG ); ?></label>
    </p>
<?php
	}
}



/**
 * Register KT_Tabs widget
 *
 *
 */

register_widget( 'WP_Widget_KT_Tabs' );