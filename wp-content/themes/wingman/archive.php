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
        <?php
        /**
         * @hooked
         */
        do_action( 'theme_before_main' ); ?>
        <div class="row">
            <div id="main" class="<?php echo apply_filters('kt_main_class', 'main-class', $sidebar['sidebar']); ?>" role="main">
                <?php do_action('before_blog_posts_loop'); ?>
                <?php if ( have_posts() ) : ?>
                    <?php global $wp_query; ?>
                    <?php
                        $page_animation = kt_option( 'page_animation' );

                        $animate_classic = ( $page_animation == 1 && $settings['blog_type'] == 'classic' ) ? 'animation-effect' : ' ';
                        $data_animate_classic = ( $page_animation == 1 && $settings['blog_type'] == 'classic' ) ? 'data-animation="fadeInUp" data-timeeffect="0"' : ' ';
                    ?>
                    <div class='blog-posts blog-posts-<?php echo esc_attr($settings['blog_type']); ?> <?php echo $animate_classic; ?>'
                         data-settings="<?php echo esc_attr( json_encode( $settings ) ); ?>"
                         data-type='<?php echo esc_attr($settings['blog_type']); ?>'
                         data-total='<?php echo esc_attr($wp_query->max_num_pages); ?>'
                         data-current='1' <?php echo $data_animate_classic; ?>
                         >

                        <?php
                        // Start the Loop.

                        if($settings['blog_type'] == 'grid' || $settings['blog_type'] == 'masonry'){
                            $elementClass[] = 'blog-posts-columns-'.$settings['blog_columns'];
                            $bootstrapColumn = round( 12 / $settings['blog_columns'] );
                            $bootstrapTabletColumn = round( 12 / $settings['blog_columns_tablet'] );
                            $classes = 'col-xs-12 col-sm-'.$bootstrapTabletColumn.' col-md-' . $bootstrapColumn;
                        }
                        $blog_atts_posts = array(
                            'image_size' => $settings['image_size'],
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
                            "class" => ''
                        );
                        
                        if( $settings['blog_type'] == 'classic' ){
                            $path = 'templates/blog/classic/content';
                        }elseif( $settings['blog_type'] == 'zigzag' ){
                            $path = 'templates/blog/zigzag/content';
                        }else{
                            $path = 'templates/blog/layout/content';
                        }
                        
                        $class_animation = ( $page_animation == 1 && $settings['blog_type'] == 'grid' ) ? 'animation-effect' : '';
                        $data_animation = ( $page_animation == 1 && $settings['blog_type'] == 'grid' ) ? 'data-animation="fadeInUp"' : '';
                        
                        echo "<div class='blog-posts-content clearfix' style='text-align: ".esc_attr($settings['align'])."'>";
                        if($settings['blog_type'] == 'grid' || $settings['blog_type'] == 'masonry'){
                            echo "<div class='row ".$class_animation."' ".$data_animation.">";
                        }

                        $i = 1;
                        while ( have_posts() ) : the_post();
                            $blog_atts = $blog_atts_posts;
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
                                
                            kt_get_template_part( $path, get_post_format(), $blog_atts);

                            if($settings['blog_type'] == 'grid' || $settings['blog_type'] == 'masonry' || ( $settings['blog_type'] == 'zigzag' && $i%2 == 0 )){
                                echo "</div><!-- .article-post-item -->";
                            }

                            $i++;
                            // End the loop.
                        endwhile;

                        if ($settings['blog_type'] == 'grid' || $settings['blog_type'] == 'masonry') {
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