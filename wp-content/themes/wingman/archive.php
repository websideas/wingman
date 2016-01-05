<?php
/**
 * The template for displaying archive
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 */

$sidebar = kt_get_archive_sidebar();
$settings = kt_get_settings_archive();

get_header(); ?>
    <div class="container">
        <?php do_action( 'kt_before_main' ); ?>

        <div class="row">
            <?php $main_class = apply_filters('kt_main_class', 'main-class', $sidebar['sidebar']); ?>
            <div id="main" class="<?php echo esc_attr($main_class); ?>" role="main">
                <?php do_action('before_blog_posts_loop'); ?>
                <?php if ( have_posts() ) : ?>
                    <?php global $wp_query; ?>

                    <div class='blog-posts blog-posts-<?php echo esc_attr($settings['blog_type']); ?>'
                         data-settings="<?php echo esc_attr( json_encode( $settings ) ); ?>"
                         data-type='<?php echo esc_attr($settings['blog_type']); ?>'
                         data-total='<?php echo esc_attr($wp_query->max_num_pages); ?>'
                         data-current='1'
                         >

                        <?php
                        // Start the Loop.

                        if($settings['blog_type'] == 'grid' || $settings['blog_type'] == 'masonry'){
                            $elementClass[] = 'blog-posts-columns-'.$settings['blog_columns'];
                            $bootstrapColumn = round( 12 / $settings['blog_columns'] );
                            $classes = 'col-xs-12 col-sm-6 col-md-' . $bootstrapColumn;
                        }
                        $blog_atts_posts = array(
                            'readmore' => $settings['readmore'],
                            'show_meta' =>  apply_filters('sanitize_boolean', $settings['show_meta']),
                            "show_author" => apply_filters('sanitize_boolean', $settings['show_author']),
                            "show_category" => apply_filters('sanitize_boolean', $settings['show_category']),
                            "show_comment" => apply_filters('sanitize_boolean', $settings['show_comment']),
                            "show_date" => apply_filters('sanitize_boolean', $settings['show_date']),
                            "show_excerpt" => apply_filters('sanitize_boolean', $settings['show_excerpt']),
                            "date_format" => $settings['date_format'],
                            "show_like_post" => apply_filters('sanitize_boolean', $settings['show_like_post']),
                            "show_view_number" => apply_filters('sanitize_boolean', $settings['show_view_number']),
                            'thumbnail_type' => $settings['thumbnail_type'],
                            'sharebox' => apply_filters('sanitize_boolean', $settings['sharebox']),
                            "class" => '',
                            "type" => $settings['blog_type'],
                        );
                        
                        if( $settings['blog_type'] == 'classic' ){
                            $path = 'templates/blog/classic/content';
                        }elseif( $settings['blog_type'] == 'zigzag' ){
                            $path = 'templates/blog/zigzag/content';
                        }elseif( $settings['blog_type'] == 'list' ){
                            $path =  'templates/blog/list/content';
                        }else{
                            $path = 'templates/blog/layout/content';
                        }

                        $page_animation = kt_option( 'page_animation' );
                        $class_animation = ( $page_animation == 1 && ( $settings['blog_type'] == 'grid' || $settings['blog_type'] == 'zigzag' ) ) ? 'animation-effect' : '';
                        $data_animation = ( $page_animation == 1 && ( $settings['blog_type'] == 'grid' || $settings['blog_type'] == 'zigzag' ) ) ? 'data-animation="fadeInUp"' : '';
                        
                        echo "<div class='blog-posts-content clearfix' style='text-align: ".esc_attr($settings['align'])."'>";
                        if($settings['blog_type'] == 'grid' || $settings['blog_type'] == 'masonry' || $settings['blog_type'] == 'zigzag'){
                            echo "<div class='row multi-columns-row ".$class_animation."' ".$data_animation.">";
                        }

                        $i = 1;
                        while ( have_posts() ) : the_post();
                            $blog_atts = $blog_atts_posts;
                            $blog_atts['blog_number'] = $i;
                            if($settings['blog_type'] == 'grid' || $settings['blog_type'] == 'masonry'){
                                echo "<div class='article-post-item ".$classes."'>";
                            }
                                
                            kt_get_template_part( $path, get_post_format(), $blog_atts);

                            if($settings['blog_type'] == 'grid' || $settings['blog_type'] == 'masonry'){
                                echo "</div><!-- .article-post-item -->";
                            }

                            $i++;
                            // End the loop.
                        endwhile;

                        if ($settings['blog_type'] == 'grid' || $settings['blog_type'] == 'masonry' || $settings['blog_type'] == 'zigzag') {
                            echo "</div><!-- .row -->";
                        }
                        echo "</div><!-- .blog-posts-content -->";


                        // Previous/next page navigation.
                        kt_paging_nav($settings['blog_pagination']);

                    echo "</div><!-- .blog-posts -->";


                // If no content, include the "No posts found" template.
                else :
                    get_template_part( 'content', 'none' );

                endif;
                ?>


            </div>
            <?php if($sidebar['sidebar'] != 'full'){ ?>
                <?php $sidebar_class = apply_filters('kt_sidebar_class', 'sidebar', $sidebar['sidebar']); ?>
                <div class="<?php echo esc_attr($sidebar_class); ?>">
                    <?php dynamic_sidebar($sidebar['sidebar_area']); ?>
                </div><!-- .sidebar -->
            <?php } ?>
        </div><!-- .row -->
        <?php do_action( 'kt_after_main' ); ?>
    </div><!-- .container -->
<?php get_footer(); ?>