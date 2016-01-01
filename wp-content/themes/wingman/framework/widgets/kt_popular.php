<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * KT tabs widget class
 *
 * @since 1.0
 */
class WP_Widget_KT_Popular extends WP_Widget {

	public function __construct() {

        $widget_ops = array('classname' => 'widget_kt_post_popular', 'description' => __( "Display popular posts of week, month and year in tabbed format.") );
        parent::__construct('kt_post_popular', __('KT: Post Popular', KT_THEME_LANG), $widget_ops);
        $this->alt_option_name = 'widget_kt_post_popular';

        add_action( 'save_post', array($this, 'flush_widget_cache') );
        add_action( 'deleted_post', array($this, 'flush_widget_cache') );
        add_action( 'switch_theme', array($this, 'flush_widget_cache') );

	}

	public function widget( $args, $instance ) {
        $cache = array();
        if ( ! $this->is_preview() ) {
            $cache = wp_cache_get( 'widget_kt_post_popular', 'widget' );
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
        
        $select_week = isset( $instance['select_week'] ) ? $instance['select_week'] : true;
        $select_month = isset( $instance['select_month'] ) ? $instance['select_month'] : true;
        $select_year = isset( $instance['select_year'] ) ? $instance['select_year'] : true;
        
        $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
        if ( ! $number )
            $number = 5;
        $show_thumbnail = isset( $instance['show_thumbnail'] ) ? $instance['show_thumbnail'] : true;
            
        echo $args['before_widget'];
        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }



        if( $select_week || $select_month || $select_year ){

            $rand = rand();

            $tabs = array();
            if($select_week)  $tabs[] = 'week';
            if($select_month)  $tabs[] = 'month';
            if($select_year)  $tabs[] = 'year';


            ?>
            <div class="kt_widget_tabs">
                <ul class="clearfix kt-tabs-nav">
                    <?php if( $select_week ){ ?><li><a href="#kt_tab_week<?php echo $rand; ?>"><?php _e( '1 Week', KT_THEME_LANG ); ?></a></li><?php } ?>
                    <?php if( $select_month ){ ?><li><a href="#kt_tab_month<?php echo $rand; ?>"><?php _e( '1 Month', KT_THEME_LANG ); ?></a></li><?php } ?>
                    <?php if( $select_year ){ ?><li><a href="#kt_tab_year<?php echo $rand; ?>"><?php _e( '1 year', KT_THEME_LANG ); ?></a></li><?php } ?>
                </ul>
                <div class="tabs-container">
                    <?php
                    if(count($tabs)){
                        $argsp =  array(
                            'posts_per_page'      => $number,
                            'ignore_sticky_posts' => true,
                            'post_status' => 'publish',
                            'orderby' => 'meta_value_num',
                            'meta_key' => 'kt_post_views_count'
                        );
                        
                        foreach($tabs as $tab){
                            $argsn = $argsp;
                            if($tab == 'week'){
                                $argsn['date_query'] = array(
                        			array(
                            			'year' => date( 'Y' ),
                            			'week' => date( 'w' ),
                            		),
                            	);
                            }elseif($tab == 'month'){
                                $argsn['date_query'] = array(
                        			array(
                            			'after'     => array(
                                            'year'  => date('Y'),
                            				'month' => date('m')-1,
                            				'day'   => date('d'),
                                        ),
                            			'before'    => array(
                            				'year'  => date('Y'),
                            				'month' => date('m'),
                            				'day'   => date('d'),
                            			),
                            			'inclusive' => true,
                            		),
                            	);
                            }elseif($tab == 'year'){
                                $argsn['date_query'] = array(
                        			array(
                            			'after'     => array(
                                            'year'  => date('Y'),
                            				'month' => date('m'),
                            				'day'   => date('d'),
                                        ),
                            			'before'    => array(
                            				'year'  => date('Y'),
                            				'month' => date('m'),
                            				'day'   => date('d'),
                            			),
                            			'inclusive' => true,
                            		),
                            	);
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
                                                        <?php 
                                                            kt_entry_meta_time();
                                                            echo kt_get_post_views( get_the_ID() );
                                                        ?>
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
                </div>
            </div>
        <?php }
        
        echo $args['after_widget'];

        if ( ! $this->is_preview() ) {
            $cache[ $args['widget_id'] ] = ob_get_flush();
            wp_cache_set( 'widget_kt_post_popular', $cache, 'widget' );
        } else {
            ob_end_flush();
        }

	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        
        $instance['select_week'] = isset( $new_instance['select_week'] ) ? (bool) $new_instance['select_week'] : false;
        $instance['select_month'] = isset( $new_instance['select_month'] ) ? (bool) $new_instance['select_month'] : false;
        $instance['select_year'] = isset( $new_instance['select_year'] ) ? (bool) $new_instance['select_year'] : false;
        
        $instance['number'] = (int) $new_instance['number'];
        $instance['show_thumbnail'] = isset( $new_instance['show_thumbnail'] ) ? (bool) $new_instance['show_thumbnail'] : false;

        $this->flush_widget_cache();

        $alloptions = wp_cache_get( 'alloptions', 'options' );
        if ( isset($alloptions['widget_kt_post_popular']) )
            delete_option('widget_kt_post_popular');

        return $instance;
	}

    public function flush_widget_cache() {
        wp_cache_delete('widget_kt_post_popular', 'widget');
    }

	public function form( $instance ) {
		$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : __( 'Widget Tabs' , KT_THEME_LANG);
        
        $select_week = isset( $instance['select_week'] ) ? (bool) $instance['select_week'] : true;
        $select_month = isset( $instance['select_month'] ) ? (bool) $instance['select_month'] : true;
        $select_year = isset( $instance['select_year'] ) ? (bool) $instance['select_year'] : true;
        
        $number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        $show_thumbnail = isset( $instance['show_thumbnail'] ) ? (bool) $instance['show_thumbnail'] : true;
		
	?>
    <p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
    
    <h4><?php _e('Select Tabs', KT_THEME_LANG); ?></h4>
    <p>
        <input class="checkbox" type="checkbox" <?php checked( $select_week ); ?> id="<?php echo $this->get_field_id( 'select_week' ); ?>" name="<?php echo $this->get_field_name( 'select_week' ); ?>" />
        <label for="<?php echo $this->get_field_id( 'select_week' ); ?>"><?php _e( 'Display Popular Posts of week',KT_THEME_LANG ); ?></label>
    </p>
    <p>
        <input class="checkbox" type="checkbox" <?php checked( $select_month ); ?> id="<?php echo $this->get_field_id( 'select_month' ); ?>" name="<?php echo $this->get_field_name( 'select_month' ); ?>" />
        <label for="<?php echo $this->get_field_id( 'select_month' ); ?>"><?php _e( 'Display Popular Posts of Month',KT_THEME_LANG ); ?></label>
    </p>
    <p>
        <input class="checkbox" type="checkbox" <?php checked( $select_year ); ?> id="<?php echo $this->get_field_id( 'select_year' ); ?>" name="<?php echo $this->get_field_name( 'select_year' ); ?>" />
        <label for="<?php echo $this->get_field_id( 'select_year' ); ?>"><?php _e( 'Display Popular Posts of Year',KT_THEME_LANG ); ?></label>
    </p>
    
    <h4><?php _e('Options Tabs', KT_THEME_LANG); ?></h4>
    
    <p> 
        <label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
        <input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" class="widefat" />
    </p>
    <p>
        <input class="checkbox" type="checkbox" <?php checked( $show_thumbnail ); ?> id="<?php echo $this->get_field_id( 'show_thumbnail' ); ?>" name="<?php echo $this->get_field_name( 'show_thumbnail' ); ?>" />
        <label for="<?php echo $this->get_field_id( 'show_thumbnail' ); ?>"><?php _e( 'Show Thumbnail (Avatar Comments)',KT_THEME_LANG ); ?></label>
    </p>
<?php
	}
}



/**
 * Register KT_Popular widget
 *
 *
 */

register_widget( 'WP_Widget_KT_Popular' );