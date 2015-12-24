<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;



class WPBakeryShortCode_Products_Carousel_Tab extends WPBakeryShortCode {
    protected function content($atts, $content = null) {
        $atts = shortcode_atts( array(
            'source' => 'widgets',

            'categories' => '',
            'per_page' => 8,
            'product_columns' => 4,
            'product_columns_desktop' => 3,
            'product_columns_tablet' => 2,
            'orderby' => 'date',
            'order' => 'DESC',
            'show' => '',
            'skin' => 'dark',

            'css_animation' => '',
            'el_class' => '',
            'css' => '',
        ), $atts );
        extract($atts);

        global $woocommerce_loop;

        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'woocommerce-products-carousel-tab', $this->settings['base'], $atts ),
            'extra' => $this->getExtraClass( $el_class ),
            'css_animation' => $this->getCSSAnimation( $css_animation ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
            'skin' => 'skin-'.$skin
        );

        $output = '';
        $uniqeID = uniqid();

        $meta_query = WC()->query->get_meta_query();
        $args = array(
            'post_type'				=> 'product',
            'post_status'			=> 'publish',
            'ignore_sticky_posts'	=> 1,
            'posts_per_page' 		=> $atts['per_page'],
            'meta_query' 			=> $meta_query
        );

        $tabs = array();
        if($source == 'categories'){
            $tabs = explode(',', $categories);
            $args['order'] = $order;
            $args['orderby'] = $orderby;

        }else{
            $tabs = array('featured', 'new', 'bestselling');
        }


        $output .= "<ul class='block-heading-tabs' data-count='".count($tabs)."'>";

        foreach($tabs as $tab){
            if($source == 'categories'){
                $term = get_term( $tab, 'product_cat' );
                $output .= "<li><a href='#tab-".$tab.'-'.$uniqeID."'>".$term->name."</a></li>";
            }else{
                if($tab == 'featured'){
                    $text = __('Hot Products', THEME_LANG);
                }elseif($tab == 'new'){
                    $text = __('New Arrivals', THEME_LANG);
                }elseif($tab == 'bestselling'){
                    $text = __('Best Sellers', THEME_LANG);
                }
                $output .= "<li><a href='#tab-".$tab.'-'.$uniqeID."'>".$text."</a></li>";
            }
        }

        $output .= "</ul>";


        $carousel_args = apply_filters('woocommerce_carousel_args', array(
            'autoheight' => false,
            'autoplay' => false,
            'mousedrag' => true,
            'autoplayspeed' => 5000,
            'slidespeed' => 200,
            'desktop' => $product_columns,
            'desktopsmall' => $product_columns_desktop,
            'tablet' => $product_columns_tablet,
            'mobile' => 1,
            'gutters' => false,

            'navigation' => true,
            'navigation_always_on' => true,
            'navigation_position' => 'top',
            'navigation_style' => 'square_border',
            'carousel_skin' => $skin,
            'navigation_icon' => 'fa fa-angle-left|fa fa-angle-right',
        ));


        $carousel_ouput = kt_render_carousel(apply_filters( 'kt_render_args', $carousel_args), '', 'woocommerce-carousel-wrapper');


        $output .= "<div class='category-products-tabs'>";
        foreach($tabs as $tab){
            $new_args = $args;

            if( $tab == 'bestselling' ){
                $new_args['meta_key'] = 'total_sales';
                $new_args['orderby'] = 'meta_value_num';

            }elseif( $tab == 'featured' ){
                $new_args['meta_query'][] = array(
                    'key'   => '_featured',
                    'value' => 'yes'
                );
            }


            $products = new WP_Query( apply_filters( 'woocommerce_shortcode_products_query', $new_args, $atts ) );
            $woocommerce_loop['columns'] = $atts['product_columns'];
            $woocommerce_loop['columns_tablet'] = $atts['product_columns_tablet'];

            $output .= "<div id='tab-".$tab.'-'.$uniqeID."' class='category-products-tab'>";
            $carousel_html ='';
            ob_start();

            if ( $products->have_posts() ) :
                do_action( "woocommerce_shortcode_before_products_carousel_tab_loop" );
                woocommerce_product_loop_start();
                while ( $products->have_posts() ) : $products->the_post();
                    wc_get_template_part( 'content', 'product' );
                endwhile; // end of the loop.
                woocommerce_product_loop_end();
                do_action( "woocommerce_shortcode_after_products_carousel_tab_loop" );
            endif;

            woocommerce_reset_loop();
            wp_reset_postdata();
            $carousel_html .= ob_get_clean();
            if($carousel_html){
                $output .= str_replace('%carousel_html%', $carousel_html, $carousel_ouput);
            }

            $output .= "</div><!-- .category-products-tab -->";
        }
        $output .= "</div><!-- .category-products-tabs -->";

        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );
        $output = '<div class="'.esc_attr( $elementClass ).'"><div class="woocommerce  columns-' . $atts['product_columns'] . '">'.$output.'</div></div>';

        return $output;
    }
}



vc_map( array(
    "name" => __( "Products Carousel Tab", THEME_LANG),
    "base" => "products_carousel_tab",
    "category" => __('by Theme', THEME_LANG ),
    "params" => array(

        array(
            "type" => "dropdown",
            "heading" => __("Data source", THEME_LANG),
            "param_name" => "source",
            "value" => array(
                __('Widgets', THEME_LANG) => 'widgets',
                __('Specific Categories', THEME_LANG) => 'categories',
            ),
            'std' => 'widgets',
            "admin_label" => true,
            "description" => __("Select content type for your posts.", THEME_LANG),
        ),

        array(
            "type" => "kt_taxonomy",
            'taxonomy' => 'product_cat',
            'heading' => __( 'Categories', 'js_composer' ),
            'param_name' => 'categories',
            'multiple' => true,
            "admin_label" => true,
            "dependency" => array( "element" => "source","value" => 'categories' ),
        ),
        array(
            'type' => 'textfield',
            'heading' => __( 'Per page', 'js_composer' ),
            'value' => 8,
            'param_name' => 'per_page',
            'description' => __( 'The "per_page" shortcode determines how many products to show on the page', 'js_composer' ),
        ),


        array(
            'type' => 'dropdown',
            'heading' => __( 'Order by', 'js_composer' ),
            'param_name' => 'orderby',
            'value' => array(
                '',
                __( 'Date', 'js_composer' ) => 'date',
                __( 'ID', 'js_composer' ) => 'ID',
                __( 'Author', 'js_composer' ) => 'author',
                __( 'Title', 'js_composer' ) => 'title',
                __( 'Modified', 'js_composer' ) => 'modified',
                __( 'Random', 'js_composer' ) => 'rand',
                __( 'Comment count', 'js_composer' ) => 'comment_count',
                __( 'Menu order', 'js_composer' ) => 'menu_order',
            ),
            'save_always' => true,
            "dependency" => array( "element" => "source","value" => 'categories' ),
            'description' => sprintf( __( 'Select how to sort retrieved products. More at %s.', 'js_composer' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Sort order', 'js_composer' ),
            'param_name' => 'order',
            'value' => array(
                '',
                __( 'Descending', 'js_composer' ) => 'DESC',
                __( 'Ascending', 'js_composer' ) => 'ASC',
            ),
            'save_always' => true,
            "dependency" => array( "element" => "source","value" => 'categories' ),
            'description' => sprintf( __( 'Designates the ascending or descending order. More at %s.', 'js_composer' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
        ),

        array(
            "type" => "kt_heading",
            "heading" => __("Columns to Show?", THEME_LANG),
            "edit_field_class" => "kt_sub_heading vc_column",
            "param_name" => "items_show",
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'on Desktop', THEME_LANG ),
            'param_name' => 'product_columns',
            'value' => array(
                __( '1 column', 'js_composer' ) => '1',
                __( '2 columns', 'js_composer' ) => '2',
                __( '3 columns', 'js_composer' ) => '3',
                __( '4 columns', 'js_composer' ) => '4',
                __( '6 columns', 'js_composer' ) => '6',
            ),
            'std' => '4',
            "edit_field_class" => "vc_col-sm-4 vc_column",
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'on Tablets Landscape', THEME_LANG ),
            'param_name' => 'product_columns_desktop',
            'value' => array(
                __( '1 column', 'js_composer' ) => '1',
                __( '2 columns', 'js_composer' ) => '2',
                __( '3 columns', 'js_composer' ) => '3',
                __( '4 columns', 'js_composer' ) => '4',
                __( '6 columns', 'js_composer' ) => '6',
            ),
            'std' => '3',
            "edit_field_class" => "vc_col-sm-4 vc_column",
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'on Tablet', THEME_LANG ),
            'param_name' => 'product_columns_tablet',
            'value' => array(
                __( '1 column', 'js_composer' ) => '1',
                __( '2 columns', 'js_composer' ) => '2',
                __( '3 columns', 'js_composer' ) => '3',
                __( '4 columns', 'js_composer' ) => '4',
                __( '6 columns', 'js_composer' ) => '6',
            ),
            'std' => '2',
            "edit_field_class" => "vc_col-sm-4 vc_column",
        ),

        array(
            'type' => 'dropdown',
            'heading' => __( 'Skin', THEME_LANG ),
            'param_name' => 'skin',
            'value' => array(
                __( 'Default', 'js_composer' ) => 'dark',
                __( 'Light', 'js_composer' ) => 'light',
            ),
            'std' => 'dark',
            'description' => __( 'Select your skin.', THEME_LANG )
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
            "heading" => __( "Extra class name", "js_composer" ),
            "param_name" => "el_class",
            "description" => __( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer" ),
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
