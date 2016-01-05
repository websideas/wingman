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

            'autoheight' => true,
            'autoplay' => false,
            'mousedrag' => true,
            'autoplayspeed' => 5000,
            'slidespeed' => 200,
            'desktop' => 4,
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
            'woocommerce' => 'woocommerce columns-' . $desktop ,
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' )
        );

        $meta_query = WC()->query->get_meta_query();

        if( $show == 'best-sellers' ){
            $meta_key = 'total_sales';
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

    }
}



vc_map( array(
    "name" => esc_html__( "Products Carousel", 'wingman'),
    "base" => "products_carousel",
    "category" => esc_html__('by Theme', 'wingman' ),
    "params" => array(
        array(
			'type' => 'textfield',
			'heading' => esc_html__( 'Per page', 'js_composer' ),
			'value' => 10,
			'param_name' => 'per_page',
			'description' => esc_html__( 'The "per_page" shortcode determines how many products to show on the page', 'js_composer' ),
		),
        "admin_label" => true,
        
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Show', 'wingman' ),
            'param_name' => 'show',
            'value' => array(
                esc_html__( 'All Product', 'woocommerce' ) => 'all',
                esc_html__( 'Featured Products', 'js_composer' ) => 'featured',
                esc_html__( 'On-sale Products', 'js_composer' ) => 'onsale',
                esc_html__( 'Best Sellers', 'js_composer' ) => 'best-sellers',
            ),
            'std' => '',
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
            "dependency" => array( "element" => "show","value" => 'all' ),
            'description' => esc_html__( 'Select order type. If "Meta value" or "Meta value Number" is chosen then meta key is required.', 'js_composer' ),
            'param_holder_class' => 'vc_grid-data-type-not-ids',
            "admin_label" => true,
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Meta key', 'js_composer' ),
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
            'heading' => esc_html__( 'Sorting', 'js_composer' ),
            'param_name' => 'order',
            'value' => array(
                esc_html__( 'Descending', 'js_composer' ) => 'DESC',
                esc_html__( 'Ascending', 'js_composer' ) => 'ASC',
            ),
            "dependency" => array( "element" => "show","value" => '' ),
            'param_holder_class' => 'vc_grid-data-type-not-ids',
            'description' => esc_html__( 'Select sorting order.', 'js_composer' ),
            "admin_label" => true,
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



        // Carousel
        array(
            'type' => 'kt_switch',
            'heading' => esc_html__( 'Auto Height', 'wingman' ),
            'param_name' => 'autoheight',
            'value' => 'true',
            "edit_field_class" => "vc_col-sm-4 kt_margin_bottom",
            "description" => esc_html__("Enable auto height.", 'wingman'),
            'group' => esc_html__( 'Carousel', 'wingman' )
        ),
        array(
            'type' => 'kt_switch',
            'heading' => esc_html__( 'Mouse Drag', 'wingman' ),
            'param_name' => 'mousedrag',
            'value' => 'true',
            "edit_field_class" => "vc_col-sm-4 kt_margin_bottom",
            "description" => esc_html__("Mouse drag enabled.", 'wingman'),
            'group' => esc_html__( 'Carousel', 'wingman' )
        ),
        array(
            'type' => 'kt_switch',
            'heading' => esc_html__( 'AutoPlay', 'wingman' ),
            'param_name' => 'autoplay',
            'value' => 'false',
            "description" => esc_html__("Enable auto play.", 'wingman'),
            "edit_field_class" => "vc_col-sm-4 kt_margin_bottom",
            'group' => esc_html__( 'Carousel', 'wingman' )
        ),
        array(
            "type" => "kt_number",
            "heading" => esc_html__("AutoPlay Speed", 'wingman'),
            "param_name" => "autoplayspeed",
            "value" => "5000",
            "suffix" => esc_html__("milliseconds", 'wingman'),
            'group' => esc_html__( 'Carousel', 'wingman' ),
            "dependency" => array("element" => "autoplay","value" => array('true')),
        ),
        array(
            "type" => "kt_number",
            "heading" => esc_html__("Slide Speed", 'wingman'),
            "param_name" => "slidespeed",
            "value" => "200",
            "suffix" => esc_html__("milliseconds", 'wingman'),
            'group' => esc_html__( 'Carousel', 'wingman' )
        ),
        array(
            "type" => "kt_heading",
            "heading" => esc_html__("Navigation settings", 'wingman'),
            "param_name" => "navigation_settings",
            'group' => esc_html__( 'Carousel', 'wingman' )
        ),
        array(
            'type' => 'kt_switch',
            'heading' => esc_html__( 'Navigation', 'wingman' ),
            'param_name' => 'navigation',
            'value' => 'true',
            "description" => esc_html__("Show navigation in carousel", 'wingman'),
            'group' => esc_html__( 'Carousel', 'wingman' )
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Navigation position', 'wingman' ),
            'param_name' => 'navigation_position',
            'group' => esc_html__( 'Carousel', 'wingman' ),
            'value' => array(
                esc_html__( 'Center outside', 'wingman') => 'center_outside',
                esc_html__( 'Center inside', 'wingman') => 'center',
                esc_html__( 'Top', 'wingman') => 'top',
                esc_html__( 'Bottom', 'wingman') => 'bottom',
            ),
            "dependency" => array("element" => "navigation","value" => array('true')),
        ),
        array(
            'type' => 'kt_switch',
            'heading' => esc_html__( 'Always Show Navigation', 'wingman' ),
            'param_name' => 'navigation_always_on',
            'value' => 'false',
            "description" => esc_html__("Always show the navigation.", 'wingman'),
            'group' => esc_html__( 'Carousel', 'wingman' ),
            "dependency" => array("element" => "navigation_position","value" => array('center', 'center_outside')),
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Navigation style', 'js_composer' ),
            'param_name' => 'navigation_style',
            'group' => esc_html__( 'Carousel', 'wingman' ),
            'value' => array(
                esc_html__( 'Normal', 'wingman' ) => '',
                esc_html__( 'Circle Background', 'wingman' ) => 'circle_background',
                esc_html__( 'Square Background', 'wingman' ) => 'square_background',
                esc_html__( 'Round Background', 'wingman' ) => 'round_background',
                esc_html__( 'Circle Border', 'wingman' ) => 'circle_border',
                esc_html__( 'Square Border', 'wingman' ) => 'square_border',
                esc_html__( 'Round Border', 'wingman' ) => 'round_border',
            ),
            'std' => 'square_border',
            "dependency" => array("element" => "navigation","value" => array('true')),
        ),

        array(
            'type' => 'kt_radio',
            'heading' => esc_html__( 'Navigation Icon', 'js_composer' ),
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
            'description' => esc_html__( 'Select your style for navigation.', 'wingman' ),
            "dependency" => array("element" => "navigation","value" => array('true')),
            'group' => esc_html__( 'Carousel', 'wingman' )
        ),

        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Carousel Skin', 'js_composer' ),
            'param_name' => 'carousel_skin',
            'group' => esc_html__( 'Carousel', 'wingman' ),
            'value' => array(
                esc_html__( 'Black', 'wingman' ) => 'black',
                esc_html__( 'White', 'wingman' ) => 'white',
                esc_html__( 'Accent', 'wingman' ) => 'accent'
            ),
            'std' => 'black',
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
