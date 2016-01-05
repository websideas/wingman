<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;



class WPBakeryShortCode_Category_Products_Tab extends WPBakeryShortCode {
    protected function content($atts, $content = null) {
        $atts = shortcode_atts( array(
            'categories' => '',
            'per_page' => 4,
            'product_columns' => 4,
            'product_columns_desktop' => 3,
            'product_columns_tablet' => 2,
            'orderby' => 'date',
            'meta_key' => '',
            'order' => 'DESC',
            'show' => '',
            'css_animation' => '',
            'el_class' => '',
            'css' => '',   
        ), $atts );
        extract($atts);

        global $woocommerce_loop;
        
        $elementClass = array(
        	'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'woocommerce-category-products-tab ', $this->settings['base'], $atts ),
        	'extra' => $this->getExtraClass( $el_class ),
        	'css_animation' => $this->getCSSAnimation( $css_animation ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' )
        );
        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );
        
        $output = '';
        
        $uniqeID = uniqid();
        
        $categories = explode(',',$categories);
        if( count($categories) > 0 ){

            $output .= '<div class="'.esc_attr( $elementClass ).'">';
                $output .= "<ul class='block-heading-tabs'>";
                    foreach( $categories as $id ){
                        $term = get_term( $id, 'product_cat' );
                        $output .= "<li><a href='#tab-".$id.'-'.$uniqeID."'>".$term->name."</a></li>";
                    }
                $output .= "</ul>";
                
                $meta_query = WC()->query->get_meta_query();
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
                
                $output .= "<div class='category-products-tabs'>";
                    foreach($categories as $value){
                        
                        $args['tax_query'] = array(
                                        		array(
                                        			'taxonomy' => 'product_cat',
                                        			'field'    => 'id',
                                        			'terms'    => $value,
                                        		),
                                        	);
                        
                        $output .= "<div id='tab-".$value.'-'.$uniqeID."' class='category-products-tab'>";
                            ob_start();
                            $products = new WP_Query( apply_filters( 'woocommerce_shortcode_products_query', $args, $atts ) );
                            $woocommerce_loop['columns'] = $atts['product_columns'];
                            $woocommerce_loop['columns_tablet'] = $atts['product_columns_tablet'];
                            if ( $products->have_posts() ) :
                                woocommerce_product_loop_start();
                                while ( $products->have_posts() ) : $products->the_post();
                                    wc_get_template_part( 'content', 'product' );
                                endwhile; // end of the loop.
                                woocommerce_product_loop_end();
                            endif;
                            wp_reset_postdata();
                            $output .= '<div class="woocommerce  columns-' . $atts['product_columns'] . '">' . ob_get_clean() . '</div>';
                        $output .= "</div><!-- .category-products-tab -->";
                    }
                $output .= "</div><!-- .category-products-tabs -->";
            $output .= "</div><!-- .category-products-tab-wrapper -->";

        }
        
        return $output;
    }
}



vc_map( array(
    "name" => esc_html__( "Category Products Tab", 'wingman'),
    "base" => "category_products_tab",
    "category" => esc_html__('by Theme', 'wingman' ),
    "params" => array(
        array(
			"type" => "kt_taxonomy",
            'taxonomy' => 'product_cat',
			'heading' => esc_html__( 'Categories', 'js_composer' ),
			'param_name' => 'categories',
            'multiple' => true,
            "admin_label" => true,
		),
        array(
			'type' => 'textfield',
			'heading' => esc_html__( 'Per page', 'js_composer' ),
			'value' => 4,
			'param_name' => 'per_page',
			'description' => esc_html__( 'The "per_page" shortcode determines how many products to show on the page', 'js_composer' ),
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
            'heading' => esc_html__( 'Show', 'wingman' ),
            'param_name' => 'show',
            'value' => array(
                esc_html__( 'All Product', 'woocommerce' ) => 'all',
                esc_html__( 'Featured Products', 'js_composer' ) => 'featured',
                esc_html__( 'On-sale Products', 'js_composer' ) => 'onsale',
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
        array(
			'type' => 'css_editor',
			'heading' => esc_html__( 'Css', 'js_composer' ),
			'param_name' => 'css',
			// 'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'js_composer' ),
			'group' => esc_html__( 'Design options', 'js_composer' )
		),
    ),
));
