<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;


class WPBakeryShortCode_List_Blog_Posts extends WPBakeryShortCode {

    protected function content($atts, $content = null) {
        $atts = shortcode_atts( array(
            'image_size' => '',
            'readmore' => '',
            'blog_pagination' => 'classic',
            'sharebox' => 'true',
            'blog_type' => 'classic',
            'blog_columns' => 3,
            'blog_columns_tablet' => 2,
            
            'packery_columns' => 3,
            'packery_style' => 'style1',
            
            'row_height' => 250,
            'margin' => 10, 
            
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
            $class_animation = ( $page_animation == 1 && ( $blog_type == 'grid' || $blog_type == 'list' || $blog_type == 'zigzag' ) ) ? 'animation-effect' : '';
            $data_animation = ( $page_animation == 1 && ( $blog_type == 'grid' || $blog_type == 'list' || $blog_type == 'zigzag' ) ) ? 'data-animation="fadeInUp"' : '';
            
            $align = '';
            if( $blog_type == 'packery' || $blog_type == 'justified' ){
                $align = 'style="text-align:'.$blog_align.'"';
            }
            $class_packery = '';
            if( $blog_type == 'packery' ){
                $class_packery = 'column'.$packery_columns.' '.$packery_style;
            }
            
            $data_justified = '';
            if( $blog_type == 'justified' ){
                $data_justified = 'data-height="'.$row_height.'" data-margin="'.$margin.'"';
            }
                        
            echo "<div class='blog-posts blog-posts-".esc_attr($blog_type)." blog-posts-".esc_attr($thumbnail_type)."' data-queryvars='".esc_attr(json_encode($args))."' data-settings='".$settings."' data-type='".$blog_type."' data-total='".$wp_query->max_num_pages."' data-current='1'>";
            echo "<div class='blog-posts-content clearfix ".$animate_classic." ".$class_packery."' ".$data_justified." ".$data_animate_classic." ".$align.">";

            do_action('before_blog_posts_loop');

            if( $blog_type == 'grid' || $blog_type == 'masonry' || $blog_type == 'list' ||  $blog_type == 'zigzag' ){
                echo "<div class='row ".$class_animation."' ".$data_animation." style='text-align: ".$blog_align.";'>";
            }

            if($blog_type == 'grid' || $blog_type == 'masonry'){
                $elementClass[] = 'blog-posts-columns-'.$blog_columns;
                $bootstrapColumn = round( 12 / $blog_columns );
                $bootstrapTabletColumn = round( 12 / $blog_columns_tablet );
                $classes = 'col-xs-12 col-sm-'.$bootstrapTabletColumn.' col-md-' . $bootstrapColumn;
            }
            $i = 1 ;
            $blog_atts_posts = array(
                'image_size' => $image_size,
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
            );

            if( $blog_type == 'classic' ){
                $path = 'templates/blog/classic/content';
            }elseif( $blog_type == 'zigzag' ){
                $path = 'templates/blog/zigzag/content';
            }elseif( $blog_type == 'packery' ){
                $path = 'templates/blog/packery/content';
            }elseif( $blog_type == 'justified' ){
                $path = 'templates/blog/justified/content';
            }elseif( $blog_type =='list' ){
                $path = 'templates/blog/list/content';
            }else{
                $path = 'templates/blog/layout/content';
            }

            while ( have_posts() ) : the_post();
                $blog_atts = $blog_atts_posts;
                $blog_atts['blog_number'] = $i;
                if($blog_type == 'grid' || $blog_type == 'masonry'){
                    $classes_extra = '';
                    if($blog_type == 'grid'){
                        if (  ( $i - 1 ) % $blog_columns == 0 || 1 == $blog_columns )
                            $classes_extra .= ' col-clearfix-md col-clearfix-lg first ';

                        if ( ( $i - 1 ) % $blog_columns_tablet == 0 || 1 == $blog_columns_tablet )
                            $classes_extra .= ' col-clearfix-sm';
                    }
                    echo "<div class='article-post-item ".$classes." ".$classes_extra."'>";
                }

                kt_get_template_part( $path, get_post_format(), $blog_atts);

                if($blog_type == 'grid' || $blog_type == 'masonry'){
                    echo "</div><!-- .article-post-item -->";
                }
                $i++;
            endwhile;

            if ($blog_type == 'grid' || $blog_type == 'masonry' || $blog_type == 'list' || $blog_type == 'zigzag' ) {
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
    "name" => __( "Blog Posts", THEME_LANG),
    "base" => "list_blog_posts",
    "category" => __('by Theme', THEME_LANG ),
    "description" => __( "Display blog posts", THEME_LANG),
    "params" => array(
        // Layout setting
        array(
            "type" => "kt_heading",
            "heading" => __("Layout setting", THEME_LANG),
            "param_name" => "layout_settings",
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Loop Style', THEME_LANG ),
            'param_name' => 'blog_type',
            'value' => array(
                __( 'Standand', 'js_composer' ) => 'classic',
                __( 'Grid', 'js_composer' ) => 'grid',
                __( 'List', 'js_composer' ) => 'list',
                __( 'Masonry', 'js_composer' ) => 'masonry',
                __( 'Zig Zag', 'js_composer' ) => 'zigzag',
                __( 'Packery', 'js_composer' ) => 'packery',
                __( 'Justified', 'js_composer' ) => 'justified',
            ),
            'description' => '',
        ),
        array(
            'type' => 'kt_switch',
            'heading' => __( 'Share box', THEME_LANG ),
            'param_name' => 'sharebox',
            'value' => 'true',
            "description" => __("Show or hide the share box.", THEME_LANG),
            'dependency' => array(
                'element' => 'blog_type',
                'value' => array( 'classic' )
            ),
        ),
        array(
            "type" => "kt_heading",
            "heading" => __("Columns to Show?", THEME_LANG),
            "edit_field_class" => "kt_sub_heading vc_column",
            "param_name" => "items_show",
            'dependency' => array(
                'element' => 'blog_type',
                'value' => array( 'grid', 'masonry' )
            ),
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'on Desktop', THEME_LANG ),
            'param_name' => 'blog_columns',
            'value' => array(
                __( '1 column', 'js_composer' ) => '1',
                __( '2 columns', 'js_composer' ) => '2',
                __( '3 columns', 'js_composer' ) => '3',
                __( '4 columns', 'js_composer' ) => '4',
                __( '6 columns', 'js_composer' ) => '6',
            ),
            'std' => '3',
            "edit_field_class" => "vc_col-sm-6 vc_column",
            'dependency' => array(
                'element' => 'blog_type',
                'value' => array( 'grid', 'masonry' )
            ),
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'on Tablet', THEME_LANG ),
            'param_name' => 'blog_columns_tablet',
            'value' => array(
                __( '1 column', 'js_composer' ) => '1',
                __( '2 columns', 'js_composer' ) => '2',
                __( '3 columns', 'js_composer' ) => '3',
                __( '4 columns', 'js_composer' ) => '4',
                __( '6 columns', 'js_composer' ) => '6',
            ),
            'std' => '2',
            "edit_field_class" => "vc_col-sm-6 vc_column",
            'dependency' => array(
                'element' => 'blog_type',
                'value' => array( 'grid', 'masonry' )
            ),
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Style Packery', THEME_LANG ),
            'param_name' => 'packery_style',
            'value' => array(
                __( 'Style 1', 'js_composer' ) => 'style1',
                __( 'Style 2', 'js_composer' ) => 'style2',
            ),
            'std' => 'style1',
            'dependency' => array(
                'element' => 'blog_type',
                'value' => array( 'packery' )
            ),
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'on Desktop Packery', THEME_LANG ),
            'param_name' => 'packery_columns',
            'value' => array(
                __( '3 columns', 'js_composer' ) => '3',
                __( '4 columns', 'js_composer' ) => '4',
                __( '5 columns', 'js_composer' ) => '5',
            ),
            'std' => '3',
            "edit_field_class" => "vc_col-sm-6 vc_column",
            'dependency' => array(
                'element' => 'blog_type',
                'value' => array( 'packery' )
            ),
        ),
        
        array(
            "type" => "kt_number",
            "heading" => __("Row Height", THEME_LANG),
            "param_name" => "row_height",
            "value" => 250,
            "min" => 0,
            "max" => 500,
            "suffix" => "px",
            'dependency' => array(
                'element' => 'blog_type',
                'value' => array( 'justified' )
            ),
        ),
        array(
            "type" => "kt_number",
            "heading" => __("Margin", THEME_LANG),
            "param_name" => "margin",
            "value" => 10,
            "min" => 0,
            "max" => 30,
            "suffix" => "px",
            'dependency' => array(
                'element' => 'blog_type',
                'value' => array( 'justified' )
            ),
        ),
        
        
        array(
            "type" => "kt_heading",
            "heading" => __("Extra setting", THEME_LANG),
            "param_name" => "extra_settings",
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Text align', 'js_composer' ),
            'param_name' => 'blog_align',
            'value' => array(
                __( 'Left', THEME_LANG ) => 'left',
                __( 'Center', THEME_LANG ) => 'center'
            ),
            'description' => __( 'Not working for archive style classic', 'js_composer' ),
            'dependency' => array(
                'element' => 'blog_type',
                'value_not_equal_to' => array( 'classic' )
            ),
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Readmore button', THEME_LANG ),
            'param_name' => 'readmore',
            'value' => array(
                __('None', THEME_LANG) => '',
                __( 'Link', 'js_composer' ) => 'link',
                __( 'Button Accent', 'js_composer' ) => 'btn-default',
                __( 'Button Light', 'js_composer' ) => 'btn-light',
                __( 'Button Dark', 'js_composer' ) => 'btn-dark',
                __( 'Button Gray', 'js_composer' ) => 'btn-gray',
                __( 'Button Accent Border', 'js_composer' ) => 'btn-default-b',
                __( 'Button Light Border', 'js_composer' ) => 'btn-light-b',
                __( 'Button Dark Border', 'js_composer' ) => 'btn-dark-b',
            ),
            "description" => __("Show or hide the readmore button.", THEME_LANG),
        ),
        array(
            "type" => "kt_image_sizes",
            "heading" => __( "Select image sizes", THEME_LANG ),
            "param_name" => "image_size",
            'dependency' => array(
                'element' => 'blog_type',
                'value_not_equal_to' => array( 'packery', 'justified' )
            ),
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Thumbnail type', THEME_LANG ),
            'param_name' => 'thumbnail_type',
            'value' => array(
                __( 'Post format', 'js_composer' ) => 'format',
                __( 'Featured Image', 'js_composer' ) => 'image',
            ),
            'description' => __( 'Select thumbnail type for article.', THEME_LANG ),
            'dependency' => array(
                'element' => 'blog_type',
                'value_not_equal_to' => array( 'justified', 'packery' )
            ),
        ),
        array(
            'type' => 'kt_switch',
            'heading' => __( 'Show Excerpt?', THEME_LANG ),
            'param_name' => 'show_excerpt',
            'value' => 'true',
            "description" => __("Show or hide the Excerpt.", THEME_LANG),
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

        /*
        array(
            "type" => "textfield",
            "heading" => __( "Image size custom", THEME_LANG ),
            "param_name" => "img_size_custom",
            'description' => __('Default: 300x200 (Width x Height)', THEME_LANG),
            "dependency" => array("element" => "image_size","value" => array('custom')),
        ),
        */


        array(
            'type' => 'dropdown',
            'heading' => __( 'Navigation type', 'js_composer' ),
            'param_name' => 'blog_pagination',
            'admin_label' => true,
            'value' => array(
                __( 'Classic pagination', THEME_LANG ) => 'classic',
                __( 'Load More button', THEME_LANG ) => 'loadmore',
                __( 'Normal pagination', THEME_LANG ) => 'normal',
                __( 'None', THEME_LANG ) => 'none',
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
            "heading" => __("Data source", THEME_LANG),
            "param_name" => "source",
            "value" => array(
                __('All', THEME_LANG) => '',
                __('Specific Categories', THEME_LANG) => 'categories',
                __('Specific Posts', THEME_LANG) => 'posts',
                __('Specific Authors', THEME_LANG) => 'authors'
            ),
            "admin_label" => true,
            'std' => '',
            "description" => __("Select content type for your posts.", THEME_LANG),
            'group' => __( 'Data settings', 'js_composer' ),
        ),
        array(
            "type" => "kt_taxonomy",
            'taxonomy' => 'category',
            'heading' => __( 'Categories', THEME_LANG ),
            'param_name' => 'categories',
            'placeholder' => __( 'Select your categories', THEME_LANG ),
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
            'heading' => __( 'Show Meta', THEME_LANG ),
            'param_name' => 'show_meta',
            'value' => 'true',
            "description" => __("Show or hide the meta.", THEME_LANG),
            'group' => __( 'Meta', 'js_composer' ),
        ),
        array(
            'type' => 'kt_switch',
            'heading' => __( 'Show Author', THEME_LANG ),
            'param_name' => 'show_author',
            'value' => 'false',
            "description" => __("Show or hide the post author.", THEME_LANG),
            'group' => __( 'Meta', 'js_composer' ),
            "dependency" => array("element" => "show_meta","value" => array('true')),
        ),
        array(
            'type' => 'kt_switch',
            'heading' => __( 'Show Category', THEME_LANG ),
            'param_name' => 'show_category',
            'value' => 'false',
            "description" => __("Show or hide the post category.", THEME_LANG),
            'group' => __( 'Meta', 'js_composer' ),
            "dependency" => array("element" => "show_meta","value" => array('true')),
        ),
        array(
            'type' => 'kt_switch',
            'heading' => __( 'Show Comment', THEME_LANG ),
            'param_name' => 'show_comment',
            'value' => 'false',
            "description" => __("Show or hide the post comment.", THEME_LANG),
            'group' => __( 'Meta', 'js_composer' ),
            "dependency" => array("element" => "show_meta","value" => array('true')),
        ),
        array(
            'type' => 'kt_switch',
            'heading' => __( 'Show Date', THEME_LANG ),
            'param_name' => 'show_date',
            'value' => 'true',
            "description" => __("Show or hide the post date.", THEME_LANG),
            'group' => __( 'Meta', 'js_composer' ),
            "dependency" => array("element" => "show_meta","value" => array('true')),
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Date format', 'js_composer' ),
            'param_name' => 'date_format',
            'value' => array(
                __( '05 December 2014', THEME_LANG ) => 'd F Y',
                __( 'December 13th 2014', THEME_LANG ) => 'F jS Y',
                __( '13th December 2014', THEME_LANG ) => 'jS F Y',
                __( '05 Dec 2014', THEME_LANG ) => 'd M Y',
                __( 'Dec 05 2014', THEME_LANG ) => 'M d Y',
                __( 'Time ago', THEME_LANG ) => 'time',
            ),
            'description' => __( 'Select your date format', THEME_LANG ),
            'group' => __( 'Meta', 'js_composer' ),
            'dependency' => array(
                'element' => 'show_date',
                'value' => array( 'true'),
            ),
        ),
        array(
            'type' => 'kt_switch',
            'heading' => __( 'Show Like Post', THEME_LANG ),
            'param_name' => 'show_like_post',
            'value' => 'false',
            "description" => __("Show or hide the like post.", THEME_LANG),
            'group' => __( 'Meta', 'js_composer' ),
            "dependency" => array("element" => "show_meta","value" => array('true')),
        ),
        array(
            'param_name' => 'show_view_number',
            'type' => 'kt_switch',
            'heading' => __('Show View Number', THEME_LANG),
            'description' => __('Show view number in blog posts.', THEME_LANG),
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


