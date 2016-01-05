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


                        
            echo "<div class='blog-posts blog-posts-".esc_attr($blog_type)." blog-posts-".esc_attr($thumbnail_type)."' data-queryvars='".esc_attr(json_encode($args))."' data-settings='".esc_attr($settings)."' data-type='".esc_attr($blog_type)."' data-total='".$wp_query->max_num_pages."' data-current='1'>";
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
                    echo '<div class="blog-posts-sizer '.esc_attr($classes).'"></div>';
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
                    echo "<div class='article-post-item ".esc_attr($classes)."'>";
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
    "name" => esc_html__( "Blog Posts", 'wingman'),
    "base" => "list_blog_posts",
    "category" => esc_html__('by Theme', 'wingman' ),
    "description" => esc_html__( "Display blog posts", 'wingman'),
    "params" => array(
        // Layout setting
        array(
            "type" => "kt_heading",
            "heading" => esc_html__("Layout setting", 'wingman'),
            "param_name" => "layout_settings",
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Loop Style', 'wingman' ),
            'param_name' => 'blog_type',
            'value' => array(
                esc_html__( 'Standard', 'js_composer' ) => 'classic',
                esc_html__( 'Grid', 'js_composer' ) => 'grid',
                esc_html__( 'List', 'js_composer' ) => 'list',
                esc_html__( 'Masonry', 'js_composer' ) => 'masonry',
                esc_html__( 'Zig Zag', 'js_composer' ) => 'zigzag',
            ),
            'description' => '',
        ),
        array(
            "type" => "kt_heading",
            "heading" => esc_html__("Columns to Show?", 'wingman'),
            "edit_field_class" => "kt_sub_heading vc_column",
            "param_name" => "items_show",
            'dependency' => array(
                'element' => 'blog_type',
                'value' => array( 'grid', 'masonry' )
            ),
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'on Desktop', 'wingman' ),
            'param_name' => 'blog_columns',
            'value' => array(
                esc_html__( '2 columns', 'js_composer' ) => '2',
                esc_html__( '3 columns', 'js_composer' ) => '3',
                esc_html__( '4 columns', 'js_composer' ) => '4',
            ),
            'std' => '3',
            'dependency' => array(
                'element' => 'blog_type',
                'value' => array( 'grid', 'masonry' )
            ),
        ),
        
        array(
            "type" => "kt_heading",
            "heading" => esc_html__("Extra setting", 'wingman'),
            "param_name" => "extra_settings",
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Text align', 'js_composer' ),
            'param_name' => 'blog_align',
            'value' => array(
                esc_html__( 'Left', 'wingman' ) => 'left',
                esc_html__( 'Center', 'wingman' ) => 'center'
            ),
            'description' => esc_html__( 'Not working for archive style classic', 'js_composer' ),
            'dependency' => array(
                'element' => 'blog_type',
                'value_not_equal_to' => array( 'classic' )
            ),
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Readmore button', 'wingman' ),
            'param_name' => 'readmore',
            'value' => array(
                esc_html__('None', 'wingman') => '',
                esc_html__( 'Link', 'js_composer' ) => 'link',
            ),
            "description" => esc_html__("Show or hide the readmore button.", 'wingman'),
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Thumbnail type', 'wingman' ),
            'param_name' => 'thumbnail_type',
            'value' => array(
                esc_html__( 'Post format', 'js_composer' ) => 'format',
                esc_html__( 'Featured Image', 'js_composer' ) => 'image',
            ),
            'description' => esc_html__( 'Select thumbnail type for article.', 'wingman' )
        ),
        array(
            'type' => 'kt_switch',
            'heading' => esc_html__( 'Show Excerpt?', 'wingman' ),
            'param_name' => 'show_excerpt',
            'value' => 'true',
            "description" => esc_html__("Show or hide the Excerpt.", 'wingman'),
        ),

        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Excerpt length', 'js_composer' ),
            'value' => 20,
            'param_name' => 'excerpt_length',
            'dependency' => array(
                'element' => 'show_excerpt',
                'value' => 'true'
            ),
        ),


        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Navigation type', 'js_composer' ),
            'param_name' => 'blog_pagination',
            'admin_label' => true,
            'value' => array(
                esc_html__( 'Classic pagination', 'wingman' ) => 'classic',
                esc_html__( 'Load More button', 'wingman' ) => 'loadmore',
                esc_html__( 'Normal pagination', 'wingman' ) => 'normal',
                esc_html__( 'None', 'wingman' ) => 'none',
            ),
            'description' => esc_html__( 'Select the navigation type', 'js_composer' )
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'CSS Animation', 'js_composer' ),
            'param_name' => 'css_animation',
            'admin_label' => true,
            'value' => array(
                esc_html__( 'No', 'js_composer' ) => '',
                esc_html__( 'Top to bottom', 'js_composer' ) => 'top-to-bottom',
                esc_html__( 'Bottom to top', 'js_composer' ) => 'bottom-to-top',
                esc_html__( 'Left to right', 'js_composer' ) => 'left-to-right',
                esc_html__( 'Right to left', 'js_composer' ) => 'right-to-left',
                esc_html__( 'Appear from center', 'js_composer' ) => "appear"
            ),
            'description' => esc_html__( 'Select type of animation if you want this element to be animated when it enters into the browsers viewport. Note: Works only in modern browsers.', 'js_composer' )
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__( "Extra class name", "js_composer"),
            "param_name" => "el_class",
            "description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer" ),
        ),


        // Data settings
        array(
            "type" => "dropdown",
            "heading" => esc_html__("Data source", 'wingman'),
            "param_name" => "source",
            "value" => array(
                esc_html__('All', 'wingman') => '',
                esc_html__('Specific Categories', 'wingman') => 'categories',
                esc_html__('Specific Posts', 'wingman') => 'posts',
                esc_html__('Specific Authors', 'wingman') => 'authors'
            ),
            "admin_label" => true,
            'std' => '',
            "description" => esc_html__("Select content type for your posts.", 'wingman'),
            'group' => esc_html__( 'Data settings', 'js_composer' ),
        ),
        array(
            "type" => "kt_taxonomy",
            'taxonomy' => 'category',
            'heading' => esc_html__( 'Categories', 'wingman' ),
            'param_name' => 'categories',
            'placeholder' => esc_html__( 'Select your categories', 'wingman' ),
            "dependency" => array("element" => "source","value" => array('categories')),
            'multiple' => true,
            'group' => esc_html__( 'Data settings', 'js_composer' ),
        ),
        array(
            "type" => "kt_posts",
            'args' => array('post_type' => 'post', 'posts_per_page' => -1),
            'heading' => esc_html__( 'Specific Posts', 'js_composer' ),
            'param_name' => 'posts',
            'size' => '5',
            'placeholder' => esc_html__( 'Select your posts', 'js_composer' ),
            "dependency" => array("element" => "source","value" => array('posts')),
            'multiple' => true,
            'group' => esc_html__( 'Data settings', 'js_composer' ),
        ),
        array(
            "type" => "kt_authors",
            'post_type' => 'post',
            'heading' => esc_html__( 'Specific Authors', 'js_composer' ),
            'param_name' => 'authors',
            'size' => '5',
            'placeholder' => esc_html__( 'Select your authors', 'js_composer' ),
            "dependency" => array("element" => "source","value" => array('authors')),
            'multiple' => true,
            'group' => esc_html__( 'Data settings', 'js_composer' ),
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Total items', 'js_composer' ),
            'param_name' => 'max_items',
            'value' => 10, // default value
            'param_holder_class' => 'vc_not-for-custom',
            'description' => esc_html__( 'Set max limit for items in grid or enter -1 to display all (limited to 1000).', 'js_composer' ),
            'group' => esc_html__( 'Data settings', 'js_composer' ),
        ),

        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Order by', 'js_composer' ),
            'param_name' => 'orderby',
            'value' => array(
                esc_html__( 'Date', 'js_composer' ) => 'date',
                esc_html__( 'Order by post ID', 'js_composer' ) => 'ID',
                esc_html__( 'Author', 'js_composer' ) => 'author',
                esc_html__( 'Title', 'js_composer' ) => 'title',
                esc_html__( 'Last modified date', 'js_composer' ) => 'modified',
                esc_html__( 'Post/page parent ID', 'js_composer' ) => 'parent',
                esc_html__( 'Number of comments', 'js_composer' ) => 'comment_count',
                esc_html__( 'Menu order/Page Order', 'js_composer' ) => 'menu_order',
                esc_html__( 'Meta value', 'js_composer' ) => 'meta_value',
                esc_html__( 'Meta value number', 'js_composer' ) => 'meta_value_num',
                esc_html__( 'Random order', 'js_composer' ) => 'rand',
            ),
            'description' => esc_html__( 'Select order type. If "Meta value" or "Meta value Number" is chosen then meta key is required.', 'js_composer' ),
            'group' => esc_html__( 'Data settings', 'js_composer' ),
            'param_holder_class' => 'vc_grid-data-type-not-ids',
            "admin_label" => true,
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Meta key', 'js_composer' ),
            'param_name' => 'meta_key',
            'group' => esc_html__( 'Data settings', 'js_composer' ),
            'param_holder_class' => 'vc_grid-data-type-not-ids',
            'dependency' => array(
                'element' => 'orderby',
                'value' => array( 'meta_value', 'meta_value_num' ),
            ),
            "admin_label" => true,
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Sorting', 'js_composer' ),
            'param_name' => 'order',
            'group' => esc_html__( 'Data settings', 'js_composer' ),
            'value' => array(
                esc_html__( 'Descending', 'js_composer' ) => 'DESC',
                esc_html__( 'Ascending', 'js_composer' ) => 'ASC',
            ),
            'param_holder_class' => 'vc_grid-data-type-not-ids',
            'description' => esc_html__( 'Select sorting order.', 'js_composer' ),
            "admin_label" => true,
        ),








        // Meta setting
        array(
            'type' => 'kt_switch',
            'heading' => esc_html__( 'Show Meta', 'wingman' ),
            'param_name' => 'show_meta',
            'value' => 'true',
            "description" => esc_html__("Show or hide the meta.", 'wingman'),
            'group' => esc_html__( 'Meta', 'js_composer' ),
        ),
        array(
            'type' => 'kt_switch',
            'heading' => esc_html__( 'Show Author', 'wingman' ),
            'param_name' => 'show_author',
            'value' => 'false',
            "description" => esc_html__("Show or hide the post author.", 'wingman'),
            'group' => esc_html__( 'Meta', 'js_composer' ),
            "dependency" => array("element" => "show_meta","value" => array('true')),
        ),
        array(
            'type' => 'kt_switch',
            'heading' => esc_html__( 'Show Category', 'wingman' ),
            'param_name' => 'show_category',
            'value' => 'false',
            "description" => esc_html__("Show or hide the post category.", 'wingman'),
            'group' => esc_html__( 'Meta', 'js_composer' ),
            "dependency" => array("element" => "show_meta","value" => array('true')),
        ),
        array(
            'type' => 'kt_switch',
            'heading' => esc_html__( 'Show Comment', 'wingman' ),
            'param_name' => 'show_comment',
            'value' => 'false',
            "description" => esc_html__("Show or hide the post comment.", 'wingman'),
            'group' => esc_html__( 'Meta', 'js_composer' ),
            "dependency" => array("element" => "show_meta","value" => array('true')),
        ),
        array(
            'type' => 'kt_switch',
            'heading' => esc_html__( 'Show Date', 'wingman' ),
            'param_name' => 'show_date',
            'value' => 'true',
            "description" => esc_html__("Show or hide the post date.", 'wingman'),
            'group' => esc_html__( 'Meta', 'js_composer' ),
            "dependency" => array("element" => "show_meta","value" => array('true')),
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Date format', 'js_composer' ),
            'param_name' => 'date_format',
            'value' => array(
                esc_html__( '05 December 2014', 'wingman' ) => 'd F Y',
                esc_html__( 'December 13th 2014', 'wingman' ) => 'F jS Y',
                esc_html__( '13th December 2014', 'wingman' ) => 'jS F Y',
                esc_html__( '05 Dec 2014', 'wingman' ) => 'd M Y',
                esc_html__( 'Dec 05 2014', 'wingman' ) => 'M d Y',
                esc_html__( 'Time ago', 'wingman' ) => 'time',
            ),
            'description' => esc_html__( 'Select your date format', 'wingman' ),
            'group' => esc_html__( 'Meta', 'js_composer' ),
            'dependency' => array(
                'element' => 'show_date',
                'value' => array( 'true'),
            ),
        ),
        array(
            'type' => 'kt_switch',
            'heading' => esc_html__( 'Show Like Post', 'wingman' ),
            'param_name' => 'show_like_post',
            'value' => 'false',
            "description" => esc_html__("Show or hide the like post.", 'wingman'),
            'group' => esc_html__( 'Meta', 'js_composer' ),
            "dependency" => array("element" => "show_meta","value" => array('true')),
        ),
        array(
            'param_name' => 'show_view_number',
            'type' => 'kt_switch',
            'heading' => esc_html__('Show View Number', 'wingman'),
            'description' => esc_html__('Show view number in blog posts.', 'wingman'),
            'value' => 'false',
            'group' => esc_html__( 'Meta', 'js_composer' ),
            "dependency" => array("element" => "show_meta","value" => array('true')),
        ),
        array(
            'type' => 'css_editor',
            'heading' => esc_html__( 'Css', 'js_composer' ),
            'param_name' => 'css',
            // 'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'js_composer' ),
            'group' => esc_html__( 'Design options', 'js_composer' )
        ),
    ),
));


