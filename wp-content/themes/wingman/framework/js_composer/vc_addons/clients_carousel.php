<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

class WPBakeryShortCode_Clients_Carousel extends WPBakeryShortCode {
    protected function content($atts, $content = null) {
        $atts = shortcode_atts( array(
            'title' => '',
            'img_size' => 'thumbnail',
            'source' => 'all',
            'categories' => '',
            'posts' => '',
            'orderby' => 'date',
            'meta_key' => '',
            'order' => 'DESC',
            'target_link' => '_self',

            'gutters' => false,
            'autoheight' => true,
            'autoplay' => false,
            'mousedrag' => true,
            'autoplayspeed' => 5000,
            'slidespeed' => 200,
            'desktop' => 5,
            'desktopsmall' => 4,
            'tablet' => 3,
            'mobile' => 1,

            'navigation' => true,
            'navigation_always_on' => false,
            'navigation_position' => 'center',
            'navigation_style' => '',
            'navigation_icon' => 'fa fa-angle-left|fa fa-angle-right',
            'pagination' => false,

            'css_animation' => '',
            'el_class' => '',
            'css' => '',

        ), $atts );

        extract($atts);

        $args = array(
            'post_type' => 'kt_client',
            'order' => $order,
            'orderby' => $orderby,
            'posts_per_page' => -1,
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

        $client_carousel_html = $post_thumbnail = '';
        $query = new WP_Query( $args );
        if ( $query->have_posts() ) :
            while ( $query->have_posts() ) : $query->the_post();
                $link = rwmb_meta('_kt_link_client');
                if( $link ){
                    $post_thumbnail = '<a target="'.$target_link.'" href="'.$link.'">'.get_the_post_thumbnail(get_the_ID(),$img_size).'</a>';
                }else{
                    $post_thumbnail = get_the_post_thumbnail(get_the_ID(),$img_size);
                }

                $client_carousel_html .= sprintf(
                    '<div class="%s">%s</div>',
                    'clients-carousel-item',
                    $post_thumbnail
                );
            endwhile; wp_reset_postdata();
        endif;

        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'clients-carousel-wrapper ', $this->settings['base'], $atts ),
            'extra' => $this->getExtraClass( $el_class ),
            'css_animation' => $this->getCSSAnimation( $css_animation ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' )
        );

        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );

        $output = '';
        $output .= '<div class="'.esc_attr( $elementClass ).'">';
        if($title){
            $output .= sprintf('<div class="block-heading"><h3>%s</h3></div>', $title );
        }

        $carousel_ouput = kt_render_carousel(apply_filters( 'kt_render_args', $atts));
        $output .= str_replace('%carousel_html%', $client_carousel_html, $carousel_ouput);

        $output .= '</div>';

        return $output;
    }
}

vc_map( array(
    "name" => esc_html__( "Clients Carousel", 'wingman'),
    "base" => "clients_carousel",
    "category" => esc_html__('by Theme', 'wingman' ),
    "description" => esc_html__( "Recent Posts Carousel", 'wingman'),
    "wrapper_class" => "clearfix",
    "params" => array(
        array(
            "type" => "textfield",
            "heading" => esc_html__( "Title", 'wingman' ),
            "param_name" => "title",
            "description" => esc_html__( "title", 'wingman' ),
            "admin_label" => true,
        ),
        array(
            "type" => "kt_image_sizes",
            "heading" => esc_html__( "Select image sizes", 'wingman' ),
            "param_name" => "img_size",
            'description' => esc_html__( 'Select size of image', 'wingman')
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Target Link', 'wingman' ),
            'param_name' => 'target_link',
            'value' => array(
                esc_html__( 'Self', 'wingman' ) => '_self',
                esc_html__( 'Blank', 'wingman' ) => '_blank',
                esc_html__( 'Parent', 'wingman' ) => '_parent',
                esc_html__( 'Top', 'wingman' ) => '_top',
            ),
            'description' => esc_html__( 'Select target link.', 'wingman' ),
        ),

        // Data settings
        array(
            "type" => "dropdown",
            "heading" => esc_html__("Data source", 'wingman'),
            "param_name" => "source",
            "value" => array(
                esc_html__('All', 'wingman') => '',
                esc_html__('Specific Categories', 'wingman') => 'categories',
                esc_html__('Specific Client', 'wingman') => 'posts',
            ),
            "admin_label" => true,
            'std' => 'all',
            "description" => esc_html__("Select content type for your posts.", 'wingman'),
            'group' => esc_html__( 'Data settings', 'js_composer' ),
        ),
        array(
            "type" => "kt_taxonomy",
            'taxonomy' => 'client-category',
            'heading' => esc_html__( 'Categories', 'wingman' ),
            'param_name' => 'categories',
            'placeholder' => esc_html__( 'Select your categories', 'wingman' ),
            "dependency" => array("element" => "source","value" => array('categories')),
            'multiple' => true,
            'group' => esc_html__( 'Data settings', 'js_composer' ),
        ),
        array(
            "type" => "kt_posts",
            'args' => array('post_type' => 'kt_client', 'posts_per_page' => -1),
            'heading' => esc_html__( 'Specific Client', 'js_composer' ),
            'param_name' => 'posts',
            'placeholder' => esc_html__( 'Select your posts', 'js_composer' ),
            "dependency" => array("element" => "source","value" => array('posts')),
            'multiple' => true,
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
            'description' => esc_html__( 'Input meta key for grid ordering.', 'js_composer' ),
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
            "heading" => esc_html__("Items to Show?", 'wingman'),
            "param_name" => "items_show",
            'group' => esc_html__( 'Carousel', 'wingman' )
        ),
        array(
            "type" => "kt_number",
            "class" => "",
            "edit_field_class" => "vc_col-sm-6 kt_margin_bottom",
            "heading" => esc_html__("On Desktop", 'wingman'),
            "param_name" => "desktop",
            "value" => "5",
            "min" => "1",
            "max" => "25",
            "step" => "1",
            'group' => esc_html__( 'Carousel', 'wingman' )
        ),

        array(
            'type' => 'kt_number',
            'heading' => esc_html__( 'on Tablets Landscape', 'wingman' ),
            'param_name' => 'desktopsmall',
            "value" => "5",
            "min" => "1",
            "max" => "25",
            "step" => "1",
            'std' => '4',
            "edit_field_class" => "vc_col-sm-6 kt_margin_bottom",
            'group' => esc_html__( 'Carousel', 'wingman' )
        ),
        array(
            "type" => "kt_number",
            "class" => "",
            "edit_field_class" => "vc_col-sm-6 kt_margin_bottom",
            "heading" => esc_html__("On Tablet", 'wingman'),
            "param_name" => "tablet",
            "value" => "3",
            "min" => "1",
            "max" => "25",
            "step" => "1",
            'group' => esc_html__( 'Carousel', 'wingman' )
        ),
        array(
            "type" => "kt_number",
            "class" => "",
            "edit_field_class" => "vc_col-sm-6 kt_margin_bottom",
            "heading" => esc_html__("On Mobile", 'wingman'),
            "param_name" => "mobile",
            "value" => "1",
            "min" => "1",
            "max" => "25",
            "step" => "1",
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
            'std' => 'center',
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
                esc_html__( 'Circle Border', 'wingman' ) => 'circle_border',
                esc_html__( 'Square Border', 'wingman' ) => 'square_border',
                esc_html__( 'Round Border', 'wingman' ) => 'round_border',
            ),
            'std' => '',
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
            "type" => "kt_heading",
            "heading" => esc_html__("Pagination settings", 'wingman'),
            "param_name" => "pagination_settings",
            'group' => esc_html__( 'Carousel', 'wingman' )
        ),
        array(
            'type' => 'kt_switch',
            'heading' => esc_html__( 'Pagination', 'wingman' ),
            'param_name' => 'pagination',
            'value' => 'false',
            "description" => esc_html__("Show pagination in carousel", 'wingman'),
            'group' => esc_html__( 'Carousel', 'wingman' )
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