<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;


class WPBakeryShortCode_List_Blog_Posts extends WPBakeryShortCode {

    protected function content($atts, $content = null) {
        $atts = shortcode_atts( array(
            'readmore' => '',
            'blog_pagination' => 'classic',
            'sharebox' => 'true',
            'blog_type' => 'classic',
            'blog_columns' => 3,
            'thumbnail_type' => 'format',
            'show_excerpt' => 'true',
            'blog_align' => 'left',

            'source' => 'all',
            'categories' => '',
            'posts' => '',
            'authors' => '',
            'orderby' => 'date',
            'meta_key' => '',
            'order' => 'DESC',
            'max_items' => 10,
            "excerpt_length" => 20,


            "show_meta" => 'true',
            "show_author" => 'false',
            "show_category" => 'false',
            'show_comment' => 'false',
            "show_date" => 'true',
            "date_format" => 'd F Y',
            "show_like_post" => 'false',
            'show_view_number' => 'false',

            'css' => '',
            'css_animation' => '',
            'el_class' => '',

        ), $atts );

        extract($atts);

        $output = $settings = '';

        $excerpt_length =  intval( $excerpt_length );
        $exl_function = create_function('$n', 'return '.$excerpt_length.';');
        add_filter( 'excerpt_length', $exl_function , 999 );

        $args = array(
            'order' => $order,
            'orderby' => $orderby,
            'posts_per_page' => $max_items,
            'ignore_sticky_posts' => true
        );

        if($blog_pagination != 'none'){
            if ( get_query_var('paged') ) { $paged = get_query_var('paged'); }
            elseif ( get_query_var('page') ) { $paged = get_query_var('page'); }
            else { $paged = 1; }

            $args['paged'] = $paged;
        }

        if($orderby == 'meta_value' || $orderby == 'meta_value_num'){
            $args['meta_key'] = $meta_key;
        }
        if($source == 'categories'){
            if($categories){
                $categories_arr = array_filter(explode( ',', $categories));
                if(count($categories_arr)){
                    $args['category__in'] = $categories_arr;
                }
            }
        }elseif($source == 'posts'){
            if($posts){
                $posts_arr = array_filter(explode( ',', $posts));
                if(count($posts_arr)){
                    $args['post__in'] = $posts_arr;
                }
            }
        }elseif($source == 'authors'){
            if($authors){
                $authors_arr = array_filter(explode( ',', $authors));
                if(count($authors_arr)){
                    $args['author__in'] = $authors_arr;
                }
            }
        }

        ob_start();

        query_posts($args);
        if ( have_posts() ) :

            if($blog_pagination == 'loadmore'){
                unset($atts['el_class'], $atts['css'], $atts['css_animation'], $atts['title']);
                $settings = esc_attr( json_encode( $atts ) );
            }

            global $wp_query;
            
            $page_animation = kt_option( 'page_animation' );
            $class_animation = ( $page_animation == 1 && ( $blog_type == 'grid' || $blog_type == 'zigzag' ) ) ? 'animation-effect' : '';
            $data_animation = ( $page_animation == 1 && ( $blog_type == 'grid' || $blog_type == 'zigzag' ) ) ? 'data-animation="fadeInUp"' : '';
            
            $animate_classic = ( $page_animation == 1 && ($blog_type == 'classic' || $blog_type == 'zigzag') ) ? 'animation-effect' : ' ';
            $data_animate_classic = ( $page_animation == 1 && ($blog_type == 'classic' || $blog_type == 'zigzag') ) ? 'data-animation="fadeInUp" data-timeeffect="0"' : ' ';

            $align = '';


                        
            echo "<div class='blog-posts blog-posts-".esc_attr($blog_type)." blog-posts-".esc_attr($thumbnail_type)."' data-queryvars='".esc_attr(json_encode($args))."' data-settings='".$settings."' data-type='".$blog_type."' data-total='".$wp_query->max_num_pages."' data-current='1'>";
            echo "<div class='blog-posts-content clearfix' ".$animate_classic." ".$data_animate_classic." ".$align.">";

            do_action('before_blog_posts_loop');

            if($blog_type == 'grid' || $blog_type == 'masonry'){
                $elementClass[] = 'blog-posts-columns-'.$blog_columns;
                $bootstrapColumn = round( 12 / $blog_columns );
                $classes = 'col-xs-12 col-sm-6 col-md-' . $bootstrapColumn.' col-lg-' . $bootstrapColumn;
            }

            if( $blog_type == 'grid' || $blog_type == 'masonry' ||  $blog_type == 'zigzag' ){
                echo "<div class='row multi-columns-row ".$class_animation."' ".$data_animation." style='text-align: ".$blog_align.";'>";
                if($blog_type == 'masonry'){
                    echo '<div class="blog-posts-sizer '.$classes.'"></div>';
                }
            }

            $i = 1 ;
            $blog_atts_posts = array(
                'readmore' => $readmore,
                'show_excerpt' =>  apply_filters('sanitize_boolean', $show_excerpt),
                'show_meta' =>  apply_filters('sanitize_boolean', $show_meta),
                "show_author" => apply_filters('sanitize_boolean', $show_author),
                "show_category" => apply_filters('sanitize_boolean', $show_category),
                "show_comment" => apply_filters('sanitize_boolean', $show_comment),
                "show_date" => apply_filters('sanitize_boolean', $show_date),
                "date_format" => $date_format,
                "show_like_post" => apply_filters('sanitize_boolean', $show_like_post),
                "show_view_number" => apply_filters('sanitize_boolean', $show_view_number),
                'thumbnail_type' => $thumbnail_type,
                'sharebox' => apply_filters('sanitize_boolean', $sharebox),
                "class" => '',
                "type" => $blog_type,
            );

            if( $blog_type == 'classic' ){
                $path = 'templates/blog/classic/content';
            }elseif( $blog_type == 'zigzag' ){
                $path = 'templates/blog/zigzag/content';
            }elseif( $blog_type =='list' ){
                $path = 'templates/blog/list/content';
            }else{
                $path = 'templates/blog/layout/content';
            }

            while ( have_posts() ) : the_post();
                $blog_atts = $blog_atts_posts;
                $blog_atts['blog_number'] = $i;
                if($blog_type == 'grid' || $blog_type == 'masonry'){
                    echo "<div class='article-post-item ".$classes."'>";
                }

                kt_get_template_part( $path, get_post_format(), $blog_atts);

                if($blog_type == 'grid' || $blog_type == 'masonry'){
                    echo "</div><!-- .article-post-item -->";
                }
                $i++;
            endwhile;

            if ($blog_type == 'grid' || $blog_type == 'masonry' || $blog_type == 'zigzag' ) {
                echo "</div><!-- .row -->";
            }
            echo "</div><!-- .blog-posts-content -->";

            kt_paging_nav($blog_pagination);

            echo "</div><!-- .blog-posts -->";
            do_action('after_blog_posts_loop');

        endif;
        wp_reset_query();

        remove_filter('excerpt_length', $exl_function, 999 );

        $output .= ob_get_clean();

        $elementClass = array(
            'base' => apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'blog-posts-wrapper ', $this->settings['base'], $atts),
            'extra' => $this->getExtraClass($el_class),
            'css_animation' => $this->getCSSAnimation($css_animation),
            'shortcode_custom' => vc_shortcode_custom_css_class($css, ' ')
        );
        $elementClass = preg_replace(array('/\s+/', '/^\s|\s$/'), array(' ', ''), implode(' ', $elementClass));

        return '<div class="' . esc_attr($elementClass) . '">' . $output . '</div>';

    }

}

// Add your Visual Composer logic here
vc_map( array(
    "name" => __( "Blog Posts", 'wingman'),
    "base" => "list_blog_posts",
    "category" => __('by Theme', 'wingman' ),
    "description" => __( "Display blog posts", 'wingman'),
    "params" => array(
        // Layout setting
        array(
            "type" => "kt_heading",
            "heading" => __("Layout setting", 'wingman'),
            "param_name" => "layout_settings",
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Loop Style', 'wingman' ),
            'param_name' => 'blog_type',
            'value' => array(
                __( 'Standard', 'js_composer' ) => 'classic',
                __( 'Grid', 'js_composer' ) => 'grid',
                __( 'List', 'js_composer' ) => 'list',
                __( 'Masonry', 'js_composer' ) => 'masonry',
                __( 'Zig Zag', 'js_composer' ) => 'zigzag',
            ),
            'description' => '',
        ),
        array(
            "type" => "kt_heading",
            "heading" => __("Columns to Show?", 'wingman'),
            "edit_field_class" => "kt_sub_heading vc_column",
            "param_name" => "items_show",
            'dependency' => array(
                'element' => 'blog_type',
                'value' => array( 'grid', 'masonry' )
            ),
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'on Desktop', 'wingman' ),
            'param_name' => 'blog_columns',
            'value' => array(
                __( '2 columns', 'js_composer' ) => '2',
                __( '3 columns', 'js_composer' ) => '3',
                __( '4 columns', 'js_composer' ) => '4',
            ),
            'std' => '3',
            'dependency' => array(
                'element' => 'blog_type',
                'value' => array( 'grid', 'masonry' )
            ),
        ),
        
        array(
            "type" => "kt_heading",
            "heading" => __("Extra setting", 'wingman'),
            "param_name" => "extra_settings",
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Text align', 'js_composer' ),
            'param_name' => 'blog_align',
            'value' => array(
                __( 'Left', 'wingman' ) => 'left',
                __( 'Center', 'wingman' ) => 'center'
            ),
            'description' => __( 'Not working for archive style classic', 'js_composer' ),
            'dependency' => array(
                'element' => 'blog_type',
                'value_not_equal_to' => array( 'classic' )
            ),
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Readmore button', 'wingman' ),
            'param_name' => 'readmore',
            'value' => array(
                __('None', 'wingman') => '',
                __( 'Link', 'js_composer' ) => 'link',
            ),
            "description" => __("Show or hide the readmore button.", 'wingman'),
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Thumbnail type', 'wingman' ),
            'param_name' => 'thumbnail_type',
            'value' => array(
                __( 'Post format', 'js_composer' ) => 'format',
                __( 'Featured Image', 'js_composer' ) => 'image',
            ),
            'description' => __( 'Select thumbnail type for article.', 'wingman' )
        ),
        array(
            'type' => 'kt_switch',
            'heading' => __( 'Show Excerpt?', 'wingman' ),
            'param_name' => 'show_excerpt',
            'value' => 'true',
            "description" => __("Show or hide the Excerpt.", 'wingman'),
        ),

        array(
            'type' => 'textfield',
            'heading' => __( 'Excerpt length', 'js_composer' ),
            'value' => 20,
            'param_name' => 'excerpt_length',
            'dependency' => array(
                'element' => 'show_excerpt',
                'value' => 'true'
            ),
        ),


        array(
            'type' => 'dropdown',
            'heading' => __( 'Navigation type', 'js_composer' ),
            'param_name' => 'blog_pagination',
            'admin_label' => true,
            'value' => array(
                __( 'Classic pagination', 'wingman' ) => 'classic',
                __( 'Load More button', 'wingman' ) => 'loadmore',
                __( 'Normal pagination', 'wingman' ) => 'normal',
                __( 'None', 'wingman' ) => 'none',
            ),
            'description' => __( 'Select the navigation type', 'js_composer' )
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'CSS Animation', 'js_composer' ),
            'param_name' => 'css_animation',
            'admin_label' => true,
            'value' => array(
                __( 'No', 'js_composer' ) => '',
                __( 'Top to bottom', 'js_composer' ) => 'top-to-bottom',
                __( 'Bottom to top', 'js_composer' ) => 'bottom-to-top',
                __( 'Left to right', 'js_composer' ) => 'left-to-right',
                __( 'Right to left', 'js_composer' ) => 'right-to-left',
                __( 'Appear from center', 'js_composer' ) => "appear"
            ),
            'description' => __( 'Select type of animation if you want this element to be animated when it enters into the browsers viewport. Note: Works only in modern browsers.', 'js_composer' )
        ),
        array(
            "type" => "textfield",
            "heading" => __( "Extra class name", "js_composer"),
            "param_name" => "el_class",
            "description" => __( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer" ),
        ),


        // Data settings
        array(
            "type" => "dropdown",
            "heading" => __("Data source", 'wingman'),
            "param_name" => "source",
            "value" => array(
                __('All', 'wingman') => '',
                __('Specific Categories', 'wingman') => 'categories',
                __('Specific Posts', 'wingman') => 'posts',
                __('Specific Authors', 'wingman') => 'authors'
            ),
            "admin_label" => true,
            'std' => '',
            "description" => __("Select content type for your posts.", 'wingman'),
            'group' => __( 'Data settings', 'js_composer' ),
        ),
        array(
            "type" => "kt_taxonomy",
            'taxonomy' => 'category',
            'heading' => __( 'Categories', 'wingman' ),
            'param_name' => 'categories',
            'placeholder' => __( 'Select your categories', 'wingman' ),
            "dependency" => array("element" => "source","value" => array('categories')),
            'multiple' => true,
            'group' => __( 'Data settings', 'js_composer' ),
        ),
        array(
            "type" => "kt_posts",
            'args' => array('post_type' => 'post', 'posts_per_page' => -1),
            'heading' => __( 'Specific Posts', 'js_composer' ),
            'param_name' => 'posts',
            'size' => '5',
            'placeholder' => __( 'Select your posts', 'js_composer' ),
            "dependency" => array("element" => "source","value" => array('posts')),
            'multiple' => true,
            'group' => __( 'Data settings', 'js_composer' ),
        ),
        array(
            "type" => "kt_authors",
            'post_type' => 'post',
            'heading' => __( 'Specific Authors', 'js_composer' ),
            'param_name' => 'authors',
            'size' => '5',
            'placeholder' => __( 'Select your authors', 'js_composer' ),
            "dependency" => array("element" => "source","value" => array('authors')),
            'multiple' => true,
            'group' => __( 'Data settings', 'js_composer' ),
        ),
        array(
            'type' => 'textfield',
            'heading' => __( 'Total items', 'js_composer' ),
            'param_name' => 'max_items',
            'value' => 10, // default value
            'param_holder_class' => 'vc_not-for-custom',
            'description' => __( 'Set max limit for items in grid or enter -1 to display all (limited to 1000).', 'js_composer' ),
            'group' => __( 'Data settings', 'js_composer' ),
        ),

        array(
            'type' => 'dropdown',
            'heading' => __( 'Order by', 'js_composer' ),
            'param_name' => 'orderby',
            'value' => array(
                __( 'Date', 'js_composer' ) => 'date',
                __( 'Order by post ID', 'js_composer' ) => 'ID',
                __( 'Author', 'js_composer' ) => 'author',
                __( 'Title', 'js_composer' ) => 'title',
                __( 'Last modified date', 'js_composer' ) => 'modified',
                __( 'Post/page parent ID', 'js_composer' ) => 'parent',
                __( 'Number of comments', 'js_composer' ) => 'comment_count',
                __( 'Menu order/Page Order', 'js_composer' ) => 'menu_order',
                __( 'Meta value', 'js_composer' ) => 'meta_value',
                __( 'Meta value number', 'js_composer' ) => 'meta_value_num',
                __( 'Random order', 'js_composer' ) => 'rand',
            ),
            'description' => __( 'Select order type. If "Meta value" or "Meta value Number" is chosen then meta key is required.', 'js_composer' ),
            'group' => __( 'Data settings', 'js_composer' ),
            'param_holder_class' => 'vc_grid-data-type-not-ids',
            "admin_label" => true,
        ),
        array(
            'type' => 'textfield',
            'heading' => __( 'Meta key', 'js_composer' ),
            'param_name' => 'meta_key',
            'group' => __( 'Data settings', 'js_composer' ),
            'param_holder_class' => 'vc_grid-data-type-not-ids',
            'dependency' => array(
                'element' => 'orderby',
                'value' => array( 'meta_value', 'meta_value_num' ),
            ),
            "admin_label" => true,
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Sorting', 'js_composer' ),
            'param_name' => 'order',
            'group' => __( 'Data settings', 'js_composer' ),
            'value' => array(
                __( 'Descending', 'js_composer' ) => 'DESC',
                __( 'Ascending', 'js_composer' ) => 'ASC',
            ),
            'param_holder_class' => 'vc_grid-data-type-not-ids',
            'description' => __( 'Select sorting order.', 'js_composer' ),
            "admin_label" => true,
        ),








        // Meta setting
        array(
            'type' => 'kt_switch',
            'heading' => __( 'Show Meta', 'wingman' ),
            'param_name' => 'show_meta',
            'value' => 'true',
            "description" => __("Show or hide the meta.", 'wingman'),
            'group' => __( 'Meta', 'js_composer' ),
        ),
        array(
            'type' => 'kt_switch',
            'heading' => __( 'Show Author', 'wingman' ),
            'param_name' => 'show_author',
            'value' => 'false',
            "description" => __("Show or hide the post author.", 'wingman'),
            'group' => __( 'Meta', 'js_composer' ),
            "dependency" => array("element" => "show_meta","value" => array('true')),
        ),
        array(
            'type' => 'kt_switch',
            'heading' => __( 'Show Category', 'wingman' ),
            'param_name' => 'show_category',
            'value' => 'false',
            "description" => __("Show or hide the post category.", 'wingman'),
            'group' => __( 'Meta', 'js_composer' ),
            "dependency" => array("element" => "show_meta","value" => array('true')),
        ),
        array(
            'type' => 'kt_switch',
            'heading' => __( 'Show Comment', 'wingman' ),
            'param_name' => 'show_comment',
            'value' => 'false',
            "description" => __("Show or hide the post comment.", 'wingman'),
            'group' => __( 'Meta', 'js_composer' ),
            "dependency" => array("element" => "show_meta","value" => array('true')),
        ),
        array(
            'type' => 'kt_switch',
            'heading' => __( 'Show Date', 'wingman' ),
            'param_name' => 'show_date',
            'value' => 'true',
            "description" => __("Show or hide the post date.", 'wingman'),
            'group' => __( 'Meta', 'js_composer' ),
            "dependency" => array("element" => "show_meta","value" => array('true')),
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Date format', 'js_composer' ),
            'param_name' => 'date_format',
            'value' => array(
                __( '05 December 2014', 'wingman' ) => 'd F Y',
                __( 'December 13th 2014', 'wingman' ) => 'F jS Y',
                __( '13th December 2014', 'wingman' ) => 'jS F Y',
                __( '05 Dec 2014', 'wingman' ) => 'd M Y',
                __( 'Dec 05 2014', 'wingman' ) => 'M d Y',
                __( 'Time ago', 'wingman' ) => 'time',
            ),
            'description' => __( 'Select your date format', 'wingman' ),
            'group' => __( 'Meta', 'js_composer' ),
            'dependency' => array(
                'element' => 'show_date',
                'value' => array( 'true'),
            ),
        ),
        array(
            'type' => 'kt_switch',
            'heading' => __( 'Show Like Post', 'wingman' ),
            'param_name' => 'show_like_post',
            'value' => 'false',
            "description" => __("Show or hide the like post.", 'wingman'),
            'group' => __( 'Meta', 'js_composer' ),
            "dependency" => array("element" => "show_meta","value" => array('true')),
        ),
        array(
            'param_name' => 'show_view_number',
            'type' => 'kt_switch',
            'heading' => __('Show View Number', 'wingman'),
            'description' => __('Show view number in blog posts.', 'wingman'),
            'value' => 'false',
            'group' => __( 'Meta', 'js_composer' ),
            "dependency" => array("element" => "show_meta","value" => array('true')),
        ),
        array(
            'type' => 'css_editor',
            'heading' => __( 'Css', 'js_composer' ),
            'param_name' => 'css',
            // 'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'js_composer' ),
            'group' => __( 'Design options', 'js_composer' )
        ),
    ),
));


