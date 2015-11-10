<?php
/**
 * The template for displaying Search Results pages
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

$sidebar = kt_get_search_sidebar();
$settings = kt_get_settings_search();


get_header(); ?>
    <div class="container">
        <?php
        /**
         * @hooked
         */
        do_action( 'theme_before_main' ); ?>
        <div class="row">
            <div id="main" class="<?php echo apply_filters('kt_main_class', 'main-class', $sidebar['sidebar']); ?>">
                <?php if( have_posts()){ ?>
                    <div class="list-blog-posts">
                       <h3 class="search-heading"><?php printf( __( "Search Results for: <span class='search-keyword'>'%s'</span>", THEME_LANG ), get_search_query() ); ?></h3>

                        <?php global $wp_query; ?>
                        <?php
                            $page_animation = kt_option( 'page_animation' );
                            $animate_classic = ( $page_animation == 1 && ($settings['blog_type'] == 'classic' || $settings['blog_type'] == 'list') ) ? 'animation-effect' : ' ';
                            $data_animate_classic = ( $page_animation == 1 && ($settings['blog_type'] == 'classic' || $settings['blog_type'] == 'list') ) ? 'data-animation="fadeInUp" data-timeeffect="0"' : ' ';
                        ?>
                        <div class='blog-posts blog-posts-<?php echo esc_attr($settings['blog_type']); ?> <?php echo $animate_classic; ?>' data-settings="<?php echo esc_attr( json_encode( $settings ) ); ?>" data-type='<?php echo esc_attr($settings['blog_type']) ?>' data-total='<?php echo esc_attr($wp_query->max_num_pages); ?>' data-current='1' <?php echo $data_animate_classic; ?>>

                            <?php
                            // Start the Loop.

                            if($settings['blog_type'] == 'grid' || $settings['blog_type'] == 'masonry'){
                                $elementClass[] = 'blog-posts-columns-'.$settings['blog_columns'];
                                $bootstrapColumn = round( 12 / $settings['blog_columns'] );
                                $bootstrapTabletColumn = round( 12 / $settings['blog_columns_tablet'] );
                                $classes = 'col-xs-12 col-sm-'.$bootstrapTabletColumn.' col-md-' . $bootstrapColumn;
                            }

                            $blog_atts_s = array(
                                'image_size' => $settings['image_size'],
                                'readmore' => $settings['readmore'],
                                'show_meta' =>  apply_filters('sanitize_boolean', $settings['show_meta']),
                                "show_author" => apply_filters('sanitize_boolean', $settings['show_author']),
                                "show_category" => apply_filters('sanitize_boolean', $settings['show_category']),
                                "show_comment" => apply_filters('sanitize_boolean', $settings['show_comment']),
                                "show_date" => apply_filters('sanitize_boolean', $settings['show_date']),
                                "date_format" => $settings['date_format'],
                                "show_like_post" => apply_filters('sanitize_boolean', $settings['show_like_post']),
                                "show_view_number" => apply_filters('sanitize_boolean', $settings['show_view_number']),
                                'thumbnail_type' => $settings['thumbnail_type'],
                                'sharebox' => apply_filters('sanitize_boolean', $settings['sharebox']),
                                "show_excerpt" => apply_filters('sanitize_boolean', $settings['show_excerpt']),
                                "class" => ''
                            );

                            if( $settings['blog_type'] == 'classic' ){
                                $path = 'templates/blog/classic/content';
                            }elseif( $settings['blog_type'] == 'zigzag' ){
                                $path = 'templates/blog/zigzag/content';
                            }elseif( $settings['blog_type'] == 'list' ){
                                $path = 'templates/blog/list/content';
                            }else{
                                $path = 'templates/blog/layout/content';
                            }
                            
                            $class_animation = ( $page_animation == 1 && $settings['blog_type'] == 'grid' ) ? 'animation-effect' : '';
                            $data_animation = ( $page_animation == 1 && $settings['blog_type'] == 'grid' ) ? 'data-animation="fadeInUp"' : '';
                            
                            echo "<div class='blog-posts-content clearfix' style='text-align: ".$settings['align']."'>";
                            if($settings['blog_type'] == 'grid' || $settings['blog_type'] == 'masonry' || $settings['blog_type'] == 'list'){
                                echo "<div class='row ".$class_animation."' ".$data_animation.">";
                            }

                            $i = 1;
                            while ( have_posts() ) : the_post();

                                $blog_atts = $blog_atts_s;

                                if($settings['blog_type'] == 'grid' || $settings['blog_type'] == 'masonry'){
                                    $classes_extra = '';
                                    if($settings['blog_type'] == 'grid'){
                                        if (  ( $i - 1 ) % $settings['blog_columns'] == 0 || 1 == $settings['blog_columns'] )
                                            $classes_extra .= ' col-clearfix-md col-clearfix-lg first ';

                                        if ( ( $i - 1 ) % $settings['blog_columns_tablet'] == 0 || 1 == $settings['blog_columns_tablet'] )
                                            $classes_extra .= ' col-clearfix-sm';
                                    }
                                    echo "<div class='article-post-item ".$classes." ".$classes_extra." ".$i."'>";
                                }elseif( $settings['blog_type'] == 'zigzag' && $i%2 == 0 ){
                                    echo "<div class='article-post-item box-even'>";
                                }

                                kt_get_template_part( $path, get_post_format(), $blog_atts );

                                if($settings['blog_type'] == 'grid' || $settings['blog_type'] == 'masonry' || ( $settings['blog_type'] == 'zigzag' && $i%2 == 0 )){
                                    echo "</div><!-- .article-post-item -->";
                                }

                                $i++;
                                // End the loop.
                            endwhile;

                            if ($settings['blog_type'] == 'grid' || $settings['blog_type'] == 'masonry' || $settings['blog_type'] == 'list') {
                                echo "</div><!-- .row -->";
                            }
                            echo "</div><!-- .blog-posts-content -->";


                            // Previous/next page navigation.
                            kt_paging_nav($settings['blog_pagination']);


                            echo "</div><!-- .blog-posts -->";
                            ?>
                    </div>
                <?php }else { ?>
                    <div class="search-content-error">
                        <h1><?php _e('Nothing Found', THEME_LANG) ?></h1>
                        <p>
                            <?php printf( __( "Sorry ! but nothing was found by <span class='search-keyword'>'%s'</span>.", THEME_LANG ), get_search_query() ); ?>
                            <?php _e('Please try again with some different keywords.', THEME_LANG); ?>
                        </p>
                        <?php get_search_form(); ?>
                    </div>
                <?php } ?>
            </div>
            <?php if($sidebar['sidebar'] != 'full'){ ?>
                <div class="<?php echo apply_filters('kt_sidebar_class', 'sidebar', $sidebar['sidebar']); ?>">
                    <?php dynamic_sidebar($sidebar['sidebar_area']); ?>
                </div><!-- .sidebar -->
            <?php } ?>
        </div><!-- .row -->
        <?php
        /**
         * @hooked
         */
        do_action( 'theme_after_main' ); ?>
    </div><!-- .container -->
<?php get_footer(); ?>