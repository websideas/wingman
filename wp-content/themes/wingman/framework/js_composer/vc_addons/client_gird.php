<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

class WPBakeryShortCode_Clients_Gird extends WPBakeryShortCode {
    var $excerpt_length;
    protected function content($atts, $content = null) {
        $atts = shortcode_atts( array(
            'client_style' => 'style1',
            'height_item' => '200',
            'target_link' => '_self',
            
            'image_size' => 'thumbnail',
            'source' => 'all',
            'categories' => '',
            'posts' => '',
            'orderby' => 'date',
            'meta_key' => '',
            'order' => 'DESC',
            'max_items' => 10,
            
            'border_width' => '',
            'border_type' => '',
            'border_color' => '#e5e5e5',

            'desktop' => 4,
            'tablet' => 3,
            'mobile' => 1,

            'css' => '',
            'css_animation' => '',
            'el_class' => '',
        ), $atts );

        extract($atts);
        
        $args = array(
                    'post_type' => 'kt_client',
                    'order' => $order,
                    'orderby' => $orderby,
                    'posts_per_page' => $max_items,
                    'ignore_sticky_posts' => true
                );
        
        if($orderby == 'meta_value' || $orderby == 'meta_value_num'){
            $args['meta_key'] = $meta_key;
        }
        if($source == 'categories'){
            if($categories){
                $categories_arr = array_filter(explode( ',', $categories));
                if(count($categories_arr)){
                    $args['tax_query'] = array(
                                    		array(
                                    			'taxonomy' => 'client-category',
                                    			'field' => 'id',
                                    			'terms' => $categories
                                    		)
                                    	);
                }
            }
        }elseif($source == 'posts'){
            if($posts){
                $posts_arr = array_filter(explode( ',', $posts));
                if(count($posts_arr)){
                    $args['post__in'] = $posts_arr;
                }
            }
        }

        $elementClass = array(
        	'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'kt_client-wrapper ', $this->settings['base'], $atts ),
        	'extra' => $this->getExtraClass( $el_class ),
        	'css_animation' => $this->getCSSAnimation( $css_animation ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' )
        );


        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );
        
        $col_desktop = 12/$desktop;
        $col_tab = 12/$tablet;
        $col_mobile = 12/$mobile;
        
        $output = $custom_css = '';
        
        $rand = rand();
        
        if( $border_width ){
            if( $client_style == 'style1' ){
                $custom_css .= '#kt_client_'.$rand.'.kt_client .style1{
                    border-width:'.$border_width.'px;
                }';   
            }
            $custom_css .= '#kt_client_'.$rand.'.kt_client .kt_client_col{
                border-width:'.$border_width.'px;
            }';
        }
        
        if($border_type ){
            if( $client_style == 'style1' ){
                $custom_css .= '#kt_client_'.$rand.'.kt_client .style1{border-style: '.$border_type.' none none '.$border_type.';}';
                $custom_css .= '#kt_client_'.$rand.'.kt_client .style1 .kt_client_col{border-style: none '.$border_type.' '.$border_type.' none;}';
            }elseif($client_style == 'style2'){
                $custom_css .= '#kt_client_'.$rand.'.kt_client .style2 .kt_client_col{border-style: none '.$border_type.' '.$border_type.' none;}';
            }
        }

        if( $client_style == 'style1' ){
            $custom_css .= '#kt_client_'.$rand.'.kt_client .style1{border-color: '.$border_color.';}';
        }elseif($client_style == 'style2'){
            $custom_css .= '#kt_client_'.$rand.'.kt_client .kt_client_col{border-color: '.$border_color.';}';
        }

        
        if($custom_css){
            $custom_css = '<div class="kt_custom_css" data-css="'.esc_attr($custom_css).'"></div>';
        }
        
        $output .= '<div class="'.esc_attr( $elementClass ).'">';
            $query = new WP_Query( $args );
            if ( $query->have_posts() ) :
                $output .= '<div id="kt_client_'.$rand.'" class="kt_client '.$border_color.'"><div class="row '.$client_style.'" data-desktop="'.$desktop.'" data-tablet="'.$tablet.'" data-mobile="'.$mobile.'">';
                    while ( $query->have_posts() ) : $query->the_post();
                        $thumbnail = get_thumbnail_attachment(get_post_thumbnail_id(),$image_size);
                        $output .= '<div class="kt_client_col col-xs-'.$col_mobile.' col-sm-'.$col_tab.' col-md-'.$col_desktop.'">';
                            $output .= '<div class="client-logo" style="background-image: url('.$thumbnail['url'].');height:'.$height_item.'px;">';
                                $link = rwmb_meta('_kt_link_client');
                                if($link){ $output .= '<a target="'.$target_link.'" href="'.$link.'"></a>'; }
                            $output .= '</div>';
                        $output .= '</div>';
                    endwhile; wp_reset_postdata();
                $output .= '</div></div>';
            endif;
        $output .= $custom_css.'</div>';

    	return $output;
    }
}

vc_map( array(
    "name" => __( "Clients Gird", THEME_LANG),
    "base" => "clients_gird",
    "category" => __('by Theme', THEME_LANG ),
    "wrapper_class" => "clearfix",
    "params" => array(
        array(
            "type" => "kt_image_sizes",
            "heading" => __( "Select image sizes", THEME_LANG ),
            "param_name" => "image_size",
        ),
        array(
            "type" => "kt_number",
            "heading" => __("Height Item", THEME_LANG),
            "param_name" => "height_item",
            "value" => "200",
            "min" => "1",
            "max" => "500",
            "step" => "1",
            "suffix" => "px",
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Target Link', THEME_LANG ),
            'param_name' => 'target_link',
            'value' => array(
                __( 'Self', THEME_LANG ) => '_self',
                __( 'Blank', THEME_LANG ) => '_blank',
                __( 'Parent', THEME_LANG ) => '_parent',
            ),
            'description' => __( 'Select target link.', THEME_LANG ),
        ),
        
        //Layout settings
        array(
            'type' => 'dropdown',
            'heading' => __( 'Style', THEME_LANG ),
            'param_name' => 'client_style',
            'value' => array(
                __( 'Style 1', THEME_LANG ) => 'style1',
                __( 'Style 2', THEME_LANG ) => 'style2',
            ),
            'admin_label' => true,
            'description' => __( 'Select your style.', THEME_LANG ),
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
                __('Specific Client', THEME_LANG) => 'posts',
            ),
            "admin_label" => true,
            'std' => 'all',
            "description" => __("Select content type for your posts.", THEME_LANG),
            'group' => __( 'Data settings', 'js_composer' ),
        ),
        array(
            "type" => "kt_taxonomy",
            'taxonomy' => 'client-category',
            'heading' => __( 'Categories', THEME_LANG ),
            'param_name' => 'categories',
            'placeholder' => __( 'Select your categories', THEME_LANG ),
            "dependency" => array("element" => "source","value" => array('categories')),
            'multiple' => true,
            'group' => __( 'Data settings', 'js_composer' ),
        ),
        array(
            "type" => "kt_posts",
            'args' => array('post_type' => 'kt_client', 'posts_per_page' => -1),
            'heading' => __( 'Specific Client', 'js_composer' ),
            'param_name' => 'posts',
            'placeholder' => __( 'Select your posts', 'js_composer' ),
            "dependency" => array("element" => "source","value" => array('posts')),
            'multiple' => true,
            'group' => __( 'Data settings', 'js_composer' ),
        ),
        array(
            'type' => 'textfield',
            'heading' => __( 'Total items', 'js_composer' ),
            'param_name' => 'max_items',
            'value' => 8, // default value
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
    		'description' => __( 'Input meta key for grid ordering.', 'js_composer' ),
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
        
        array(
            "type" => "dropdown",
            "class" => "",
            "edit_field_class" => "vc_col-sm-4 kt_margin_bottom",
            "heading" => __("On Desktop", THEME_LANG),
            "param_name" => "desktop",
            "value" => array(
                __( '6 Column', 'js_composer' ) => '6',
                __( '4 Column', 'js_composer' ) => '4',
    			__( '3 Column', 'js_composer' ) => '3',
    			__( '2 Column', 'js_composer' ) => '2',
                __( '1 Column', 'js_composer' ) => '1',
    		),
            'std' => '3',
            'group' => __( 'Column settings', THEME_LANG ),
        ),
        array(
            "type" => "dropdown",
            "class" => "",
            "edit_field_class" => "vc_col-sm-4 kt_margin_bottom",
            "heading" => __("On Tablet", THEME_LANG),
            "param_name" => "tablet",
            "value" => array(
                __( '6 Column', 'js_composer' ) => '6',
                __( '4 Column', 'js_composer' ) => '4',
    			__( '3 Column', 'js_composer' ) => '3',
    			__( '2 Column', 'js_composer' ) => '2',
                __( '1 Column', 'js_composer' ) => '1',
    		),
            'std' => '2',
            'group' => __( 'Column settings', THEME_LANG ),
        ),
        array(
            "type" => "dropdown",
            "class" => "",
            "edit_field_class" => "vc_col-sm-4 kt_margin_bottom",
            "heading" => __("On Mobile", THEME_LANG),
            "param_name" => "mobile",
            "value" => array(
                __( '6 Column', 'js_composer' ) => '6',
                __( '4 Column', 'js_composer' ) => '4',
    			__( '3 Column', 'js_composer' ) => '3',
    			__( '2 Column', 'js_composer' ) => '2',
                __( '1 Column', 'js_composer' ) => '1',
    		),
            'std' => '1',
            'group' => __( 'Column settings', THEME_LANG ),
        ),
        //border
        array(
            "type" => "kt_number",
            "heading" => __("Border Width", THEME_LANG),
            "param_name" => "border_width",
            "value" => 1,
            "min" => 1,
            "max" => 5,
            "suffix" => "px",
            "description" => "",
            'group' => __( 'Border', THEME_LANG ),
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Border Style', 'js_composer' ),
            'param_name' => 'border_type',
            'value' => array(
                __( 'Dashed', 'js_composer' ) => 'dashed',
                __( 'Solid', 'js_composer' ) => 'solid',
                __( 'Dotted', 'js_composer' ) => 'dotted',
                __( 'Double', 'js_composer' ) => 'double',
                __( 'Groove', 'js_composer' ) => 'groove',
                __( 'Ridge', 'js_composer' ) => 'ridge',
                __( 'Inset', 'js_composer' ) => 'inset',
                __( 'Outset', 'js_composer' ) => 'outset'
            ),
            'std' => 'solid',
            'description' => __( 'Select type border.', 'js_composer' ),
            'group' => __( 'Border', THEME_LANG )
        ),
        array(
            'type' => 'colorpicker',
            'heading' => __( 'Border Color', 'js_composer' ),
            'param_name' => 'border_color',
            'description' => __( 'Select custom border color.', 'js_composer' ),
            'value' => '#e5e5e5',
            'group' => __( 'Border', THEME_LANG )
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

