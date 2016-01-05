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
                    $text = esc_html__('Hot Products', 'wingman');
                }elseif($tab == 'new'){
                    $text = esc_html__('New Arrivals', 'wingman');
                }elseif($tab == 'bestselling'){
                    $text = esc_html__('Best Sellers', 'wingman');
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
    "name" => esc_html__( "Products Carousel Tab", 'wingman'),
    "base" => "products_carousel_tab",
    "category" => esc_html__('by Theme', 'wingman' ),
    "params" => array(

        array(
            "type" => "dropdown",
            "heading" => esc_html__("Data source", 'wingman'),
            "param_name" => "source",
            "value" => array(
                esc_html__('Widgets', 'wingman') => 'widgets',
                esc_html__('Specific Categories', 'wingman') => 'categories',
            ),
            'std' => 'widgets',
            "admin_label" => true,
            "description" => esc_html__("Select content type for your posts.", 'wingman'),
        ),

        array(
            "type" => "kt_taxonomy",
            'taxonomy' => 'product_cat',
            'heading' => esc_html__( 'Categories', 'js_composer' ),
            'param_name' => 'categories',
            'multiple' => true,
            "admin_label" => true,
            "dependency" => array( "element" => "source","value" => 'categories' ),
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Per page', 'js_composer' ),
            'value' => 8,
            'param_name' => 'per_page',
            'description' => esc_html__( 'The "per_page" shortcode determines how many products to show on the page', 'js_composer' ),
        ),


        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Order by', 'js_composer' ),
            'param_name' => 'orderby',
            'value' => array(
                '',
                esc_html__( 'Date', 'js_composer' ) => 'date',
                esc_html__( 'ID', 'js_composer' ) => 'ID',
                esc_html__( 'Author', 'js_composer' ) => 'author',
                esc_html__( 'Title', 'js_composer' ) => 'title',
                esc_html__( 'Modified', 'js_composer' ) => 'modified',
                esc_html__( 'Random', 'js_composer' ) => 'rand',
                esc_html__( 'Comment count', 'js_composer' ) => 'comment_count',
                esc_html__( 'Menu order', 'js_composer' ) => 'menu_order',
            ),
            'save_always' => true,
            "dependency" => array( "element" => "source","value" => 'categories' ),
            'description' => sprintf( esc_html__( 'Select how to sort retrieved products. More at %s.', 'js_composer' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Sort order', 'js_composer' ),
            'param_name' => 'order',
            'value' => array(
                '',
                esc_html__( 'Descending', 'js_composer' ) => 'DESC',
                esc_html__( 'Ascending', 'js_composer' ) => 'ASC',
            ),
            'save_always' => true,
            "dependency" => array( "element" => "source","value" => 'categories' ),
            'description' => sprintf( esc_html__( 'Designates the ascending or descending order. More at %s.', 'js_composer' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
        ),

        array(
            "type" => "kt_heading",
            "heading" => esc_html__("Columns to Show?", 'wingman'),
            "edit_field_class" => "kt_sub_heading vc_column",
            "param_name" => "items_show",
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'on Desktop', 'wingman' ),
            'param_name' => 'product_columns',
            'value' => array(
                esc_html__( '1 column', 'js_composer' ) => '1',
                esc_html__( '2 columns', 'js_composer' ) => '2',
                esc_html__( '3 columns', 'js_composer' ) => '3',
                esc_html__( '4 columns', 'js_composer' ) => '4',
                esc_html__( '6 columns', 'js_composer' ) => '6',
            ),
            'std' => '4',
            "edit_field_class" => "vc_col-sm-4 vc_column",
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'on Tablets Landscape', 'wingman' ),
            'param_name' => 'product_columns_desktop',
            'value' => array(
                esc_html__( '1 column', 'js_composer' ) => '1',
                esc_html__( '2 columns', 'js_composer' ) => '2',
                esc_html__( '3 columns', 'js_composer' ) => '3',
                esc_html__( '4 columns', 'js_composer' ) => '4',
                esc_html__( '6 columns', 'js_composer' ) => '6',
            ),
            'std' => '3',
            "edit_field_class" => "vc_col-sm-4 vc_column",
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'on Tablet', 'wingman' ),
            'param_name' => 'product_columns_tablet',
            'value' => array(
                esc_html__( '1 column', 'js_composer' ) => '1',
                esc_html__( '2 columns', 'js_composer' ) => '2',
                esc_html__( '3 columns', 'js_composer' ) => '3',
                esc_html__( '4 columns', 'js_composer' ) => '4',
                esc_html__( '6 columns', 'js_composer' ) => '6',
            ),
            'std' => '2',
            "edit_field_class" => "vc_col-sm-4 vc_column",
        ),

        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Skin', 'wingman' ),
            'param_name' => 'skin',
            'value' => array(
                esc_html__( 'Default', 'js_composer' ) => 'dark',
                esc_html__( 'Light', 'js_composer' ) => 'light',
            ),
            'std' => 'dark',
            'description' => esc_html__( 'Select your skin.', 'wingman' )
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
            "heading" => esc_html__( "Extra class name", "js_composer" ),
            "param_name" => "el_class",
            "description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer" ),
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
