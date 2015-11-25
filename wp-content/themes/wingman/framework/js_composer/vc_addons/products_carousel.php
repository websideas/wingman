<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;



class WPBakeryShortCode_Products_Carousel extends WPBakeryShortCode {
    protected function content($atts, $content = null) {
        $atts = shortcode_atts( array(
            'per_page' => 10,
            'orderby' => 'date',
            'meta_key' => '',
            'order' => 'DESC',
            'show' => '',

            'margin' => 10,
            'autoheight' => true,
            'autoplay' => false,
            'mousedrag' => true,
            'autoplayspeed' => 5000,
            'slidespeed' => 200,
            'desktop' => 1,
            'tablet' => 1,
            'mobile' => 1,
            'gutters' => false,

            'navigation' => true,
            'navigation_always_on' => true,
            'navigation_position' => 'top',
            'navigation_style' => 'square_border',
            'carousel_skin' => 'black',
            'navigation_icon' => 'fa fa-angle-left|fa fa-angle-right',
            
            'css_animation' => '',
            'el_class' => '',
            'css' => '',   
        ), $atts );
        extract($atts);



        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'products-carousel ', $this->settings['base'], $atts ),
            'extra' => $this->getExtraClass( $el_class ),
            'css_animation' => $this->getCSSAnimation( $css_animation ),
            'woocommerce' => 'woocommerce',
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' )
        );





        $meta_query = WC()->query->get_meta_query();

        if( $show == 'best-sellers' ){
            $meta_query = 'total_sales';
            $orderby    = 'meta_value_num';
        }

        $args = array(
            'post_type'				=> 'product',
            'post_status'			=> 'publish',
            'ignore_sticky_posts'	=> 1,
            'posts_per_page' 		=> $atts['per_page'],
            'meta_query' 			=> $meta_query,
            'order'                 => $order,
            'orderby'               => $orderby,
            'meta_key'              => $meta_key
        );
        if( $show == 'onsale' ){
            $product_ids_on_sale = wc_get_product_ids_on_sale();
            $args['post__in'] = array_merge( array( 0 ), $product_ids_on_sale );
        }elseif( $show == 'featured' ){
            $args['meta_query'][] = array(
                'key'   => '_featured',
                'value' => 'yes'
            );
        }

        $carousel_ouput = kt_render_carousel(apply_filters( 'kt_render_args', $atts), '', 'woocommerce-carousel-wrapper');
        $output = $carousel_html ='';

        ob_start();
        global $woocommerce_loop;
        $products = new WP_Query( apply_filters( 'woocommerce_shortcode_products_query', $args, $atts ) );
        $woocommerce_loop['columns'] = $desktop;
        $woocommerce_loop['columns_tablet'] = $tablet;
        if ( $products->have_posts() ) :
            woocommerce_product_loop_start();
            while ( $products->have_posts() ) : $products->the_post();
                wc_get_template_part( 'content', 'product' );
            endwhile; // end of the loop.
            woocommerce_product_loop_end();
        endif;
        wp_reset_postdata();
        $carousel_html .= ob_get_clean();
        if($carousel_html){

            $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );
            $output = '<div class="'.esc_attr( $elementClass ).'">'.str_replace('%carousel_html%', $carousel_html, $carousel_ouput).'</div>';

        }

        return $output;

        /*




        
        $data_carousel = array(
            'pagination' => false,
            'desktop' => $desktop,
            'tablet' => $tablet,
            'mobile' => $mobile,
            'autoheight' => true,
            'autoplay' => apply_filters('sanitize_boolean', $autoplay),
            'navigation' => apply_filters('sanitize_boolean', $navigation),
            'navigation_always_on' => true,
            'slidespeed' => apply_filters('sanitize_boolean', $slidespeed),
            'navigation_pos' => $navigation_position
        );


        $output .= '<div class="'.esc_attr( $elementClass ).'" '.render_data_carousel($data_carousel).'>';
            
            $meta_query = WC()->query->get_meta_query();
            
            if( $show == 'best-sellers' ){ 
                $meta_query = 'total_sales';
                $orderby    = 'meta_value_num';
            }
            
            $args = array(
    			'post_type'				=> 'product',
    			'post_status'			=> 'publish',
    			'ignore_sticky_posts'	=> 1,
    			'posts_per_page' 		=> $atts['per_page'],
    			'meta_query' 			=> $meta_query,
                'order'                 => $order,
                'orderby'               => $orderby,
                'meta_key'              => $meta_key
    		);
            if( $show == 'onsale' ){
                $product_ids_on_sale = wc_get_product_ids_on_sale();
                $args['post__in'] = array_merge( array( 0 ), $product_ids_on_sale );
            }elseif( $show == 'featured' ){
                $args['meta_query'][] = array(
					'key'   => '_featured',
					'value' => 'yes'
				);
            }


            ob_start();
            $products = new WP_Query( apply_filters( 'woocommerce_shortcode_products_query', $args, $atts ) );
            $woocommerce_loop['columns'] = $desktop;
            $woocommerce_loop['columns_tablet'] = $tablet;
            if ( $products->have_posts() ) :
                woocommerce_product_loop_start();
                while ( $products->have_posts() ) : $products->the_post();
                    wc_get_template_part( 'content', 'product' );
                endwhile; // end of the loop.
                woocommerce_product_loop_end();
            endif;
            wp_reset_postdata();
            $output .= '<div class="woocommerce columns-1">' . ob_get_clean() . '</div>';
            
        $output .= "</div><!-- .woocommerce-carousel-wrapper -->";

        return $output;

        */
    }
}



vc_map( array(
    "name" => __( "Products Carousel", THEME_LANG),
    "base" => "products_carousel",
    "category" => __('by Theme', THEME_LANG ),
    "params" => array(
        array(
			'type' => 'textfield',
			'heading' => __( 'Per page', 'js_composer' ),
			'value' => 10,
			'param_name' => 'per_page',
			'description' => __( 'The "per_page" shortcode determines how many products to show on the page', 'js_composer' ),
		),
        "admin_label" => true,
        
        array(
            'type' => 'dropdown',
            'heading' => __( 'Show', THEME_LANG ),
            'param_name' => 'show',
            'value' => array(
                __( 'All Product', 'woocommerce' ) => 'all',
                __( 'Featured Products', 'js_composer' ) => 'featured',
                __( 'On-sale Products', 'js_composer' ) => 'onsale',
                __( 'Best Sellers', 'js_composer' ) => 'best-sellers',
            ),
            'std' => '',
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
            "dependency" => array( "element" => "show","value" => 'all' ),
            'description' => __( 'Select order type. If "Meta value" or "Meta value Number" is chosen then meta key is required.', 'js_composer' ),
            'param_holder_class' => 'vc_grid-data-type-not-ids',
            "admin_label" => true,
        ),
        array(
            'type' => 'textfield',
            'heading' => __( 'Meta key', 'js_composer' ),
            'param_name' => 'meta_key',
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
            'value' => array(
                __( 'Descending', 'js_composer' ) => 'DESC',
                __( 'Ascending', 'js_composer' ) => 'ASC',
            ),
            "dependency" => array( "element" => "show","value" => '' ),
            'param_holder_class' => 'vc_grid-data-type-not-ids',
            'description' => __( 'Select sorting order.', 'js_composer' ),
            "admin_label" => true,
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



        // Carousel
        array(
            'type' => 'kt_switch',
            'heading' => __( 'Auto Height', THEME_LANG ),
            'param_name' => 'autoheight',
            'value' => 'true',
            "edit_field_class" => "vc_col-sm-4 kt_margin_bottom",
            "description" => __("Enable auto height.", THEME_LANG),
            'group' => __( 'Carousel', THEME_LANG )
        ),
        array(
            'type' => 'kt_switch',
            'heading' => __( 'Mouse Drag', THEME_LANG ),
            'param_name' => 'mousedrag',
            'value' => 'true',
            "edit_field_class" => "vc_col-sm-4 kt_margin_bottom",
            "description" => __("Mouse drag enabled.", THEME_LANG),
            'group' => __( 'Carousel', THEME_LANG )
        ),
        array(
            'type' => 'kt_switch',
            'heading' => __( 'AutoPlay', THEME_LANG ),
            'param_name' => 'autoplay',
            'value' => 'false',
            "description" => __("Enable auto play.", THEME_LANG),
            "edit_field_class" => "vc_col-sm-4 kt_margin_bottom",
            'group' => __( 'Carousel', THEME_LANG )
        ),
        array(
            "type" => "kt_number",
            "heading" => __("AutoPlay Speed", THEME_LANG),
            "param_name" => "autoplayspeed",
            "value" => "5000",
            "suffix" => __("milliseconds", THEME_LANG),
            'group' => __( 'Carousel', THEME_LANG ),
            "dependency" => array("element" => "autoplay","value" => array('true')),
        ),
        array(
            "type" => "kt_number",
            "heading" => __("Slide Speed", THEME_LANG),
            "param_name" => "slidespeed",
            "value" => "200",
            "suffix" => __("milliseconds", THEME_LANG),
            'group' => __( 'Carousel', THEME_LANG )
        ),
        array(
            "type" => "kt_heading",
            "heading" => __("Navigation settings", THEME_LANG),
            "param_name" => "navigation_settings",
            'group' => __( 'Carousel', THEME_LANG )
        ),
        array(
            'type' => 'kt_switch',
            'heading' => __( 'Navigation', THEME_LANG ),
            'param_name' => 'navigation',
            'value' => 'true',
            "description" => __("Show navigation in carousel", THEME_LANG),
            'group' => __( 'Carousel', THEME_LANG )
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Navigation position', THEME_LANG ),
            'param_name' => 'navigation_position',
            'group' => __( 'Carousel', THEME_LANG ),
            'value' => array(
                __( 'Center outside', THEME_LANG) => 'center_outside',
                __( 'Center inside', THEME_LANG) => 'center',
                __( 'Top', THEME_LANG) => 'top',
                __( 'Bottom', THEME_LANG) => 'bottom',
            ),
            "dependency" => array("element" => "navigation","value" => array('true')),
        ),
        array(
            'type' => 'kt_switch',
            'heading' => __( 'Always Show Navigation', THEME_LANG ),
            'param_name' => 'navigation_always_on',
            'value' => 'false',
            "description" => __("Always show the navigation.", THEME_LANG),
            'group' => __( 'Carousel', THEME_LANG ),
            "dependency" => array("element" => "navigation_position","value" => array('center', 'center_outside')),
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Navigation style', 'js_composer' ),
            'param_name' => 'navigation_style',
            'group' => __( 'Carousel', THEME_LANG ),
            'value' => array(
                __( 'Normal', THEME_LANG ) => '',
                __( 'Circle Background', THEME_LANG ) => 'circle_background',
                __( 'Square Background', THEME_LANG ) => 'square_background',
                __( 'Round Background', THEME_LANG ) => 'round_background',
                __( 'Circle Border', THEME_LANG ) => 'circle_border',
                __( 'Square Border', THEME_LANG ) => 'square_border',
                __( 'Round Border', THEME_LANG ) => 'round_border',
            ),
            'std' => 'square_border',
            "dependency" => array("element" => "navigation","value" => array('true')),
        ),

        array(
            'type' => 'kt_radio',
            'heading' => __( 'Navigation Icon', 'js_composer' ),
            'param_name' => 'navigation_icon',
            'class_input' => "radio-wrapper-group",
            'value' => array(
                '<span><i class="fa fa-angle-left"></i><i class="fa fa-angle-right"></i></span>' => 'fa fa-angle-left|fa fa-angle-right',
                '<span><i class="fa fa-chevron-left"></i><i class="fa fa-chevron-right"></i></span>' => 'fa fa-chevron-left|fa fa-chevron-right',
                '<span><i class="fa fa-angle-double-left"></i><i class="fa fa-angle-double-right"></i></span>' => 'fa fa-angle-double-left|fa fa-angle-double-right',
                '<span><i class="fa fa-long-arrow-left"></i><i class="fa fa-long-arrow-right"></i></span>' => 'fa fa-long-arrow-left|fa fa-long-arrow-right',
                '<span><i class="fa fa-chevron-circle-left"></i><i class="fa fa-chevron-circle-right"></i></span>' =>'fa fa-chevron-circle-left|fa fa-chevron-circle-right',
                '<span><i class="fa fa-arrow-circle-o-left"></i><i class="fa fa-arrow-circle-o-right"></i></span>' => 'fa fa-arrow-circle-o-left|fa fa-arrow-circle-o-right',
            ),
            'description' => __( 'Select your style for navigation.', THEME_LANG ),
            "dependency" => array("element" => "navigation","value" => array('true')),
            'group' => __( 'Carousel', THEME_LANG )
        ),

        array(
            'type' => 'dropdown',
            'heading' => __( 'Carousel Skin', 'js_composer' ),
            'param_name' => 'carousel_skin',
            'group' => __( 'Carousel', THEME_LANG ),
            'value' => array(
                __( 'Black', THEME_LANG ) => 'black',
                __( 'White', THEME_LANG ) => 'white',
                __( 'Accent', THEME_LANG ) => 'accent'
            ),
            'std' => 'black',
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
