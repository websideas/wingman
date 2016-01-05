<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-custom-heading.php' );

class WPBakeryShortCode_Testimonial_Carousel extends WPBakeryShortCode_VC_Custom_heading {
    protected function content($atts, $content = null) {
        $atts = shortcode_atts( array(
            'title' => '',
            'layout' => 1,

            'font_container' => '',
            'use_theme_fonts' => 'yes',
            'google_fonts' => '',
            'letter_spacing' => '0',
            'skin' => 'dark',


            'font_container_company' => '',
            'use_theme_fonts_company' => 'yes',
            'google_fonts_company' => '',
            'letter_spacing_company' => '0',

            'source' => 'all',
            'categories' => '',
            'posts' => '',
            'orderby' => 'date',
            'meta_key' => '',
            'order' => 'DESC',
            'max_items' => 10,

            'gutters' => false,
            'autoheight' => true,
            'autoplay' => false,
            'mousedrag' => true,
            'autoplayspeed' => 5000,
            'slidespeed' => 200,
            'desktop' => 1,
            'tablet' => 1,
            'mobile' => 1,

            'navigation' => true,
            'navigation_always_on' => true,
            'navigation_position' => 'center',
            'navigation_style' => '',
            'navigation_icon' => 'fa fa-long-arrow-left|fa fa-long-arrow-right',
            'callback' => 'kt_testimonial_thumbnail',

            'pagination' => true,

            'css_animation' => '',
            'el_class' => '',
            'css' => '',
        ), $atts);

        $atts['carousel_skin'] = $atts['skin'];
        extract($atts);

        $args = array(
            'order' => $order,
            'orderby' => $orderby,
            'posts_per_page' => $max_items,
            'ignore_sticky_posts' => true,
            'post_type' => 'kt_testimonial'
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
                            'taxonomy' => 'testimonial-category',
                            'field' => 'id',
                            'terms' => $categories_arr
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
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'testimonial-carousel-wrapper ', $this->settings['base'], $atts ),
            'extra' => $this->getExtraClass( $el_class ),
            'css_animation' => $this->getCSSAnimation( $css_animation ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
            'layout' => 'testimonial-carousel-layout-'.$layout,
        );

        if($skin){
            $elementClass['skin'] = 'testimonial-carousel-skin-'.$skin;
        }


        $output = $style_title = $style_company = '';

        if($title){
            $output .= '<div class="block-heading">';
            $output .= '<h3>'.$title.'</h3>';
            $output .= '</div>';
        }


        $styles = array();
        extract( $this->getAttributes( $atts ) );
        unset($font_container_data['values']['text_align']);
        extract( $this->getStyles( $el_class, $css, $google_fonts_data, $font_container_data, $atts ) );
        $settings = get_option( 'wpb_js_google_fonts_subsets' );
        $subsets = '';
        if ( is_array( $settings ) && ! empty( $settings ) ) {
            $subsets = '&subset=' . implode( ',', $settings );
        }
        if ( ! empty( $google_fonts_data ) && isset( $google_fonts_data['values']['font_family'] ) ) {
            wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_fonts_data['values']['font_family'] ), '//fonts.googleapis.com/css?family=' . $google_fonts_data['values']['font_family'] . $subsets );
        }

        if($letter_spacing){
            $styles[] = 'letter-spacing: '.$letter_spacing.'px;';
        }
        if ( ! empty( $styles ) ) {
            $style_title = 'style="' . esc_attr( implode( ';', $styles ) ) . '"';
        }




        $atts['font_container'] = $font_container_company;
        $atts['google_fonts'] = $google_fonts_company;
        $atts['use_theme_fonts'] = $use_theme_fonts_company;
        $styles = array();
        extract( $this->getAttributes( $atts ) );
        unset($font_container_data['values']['text_align']);
        extract( $this->getStyles( $el_class, $css, $google_fonts_data, $font_container_data, $atts ) );
        $settings = get_option( 'wpb_js_google_fonts_subsets' );
        $subsets = '';
        if ( is_array( $settings ) && ! empty( $settings ) ) {
            $subsets = '&subset=' . implode( ',', $settings );
        }
        if ( ! empty( $google_fonts_data ) && isset( $google_fonts_data['values']['font_family'] ) ) {
            wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_fonts_data['values']['font_family'] ), '//fonts.googleapis.com/css?family=' . $google_fonts_data['values']['font_family'] . $subsets );
        }

        if($letter_spacing_company){
            $styles[] = 'letter-spacing: '.$letter_spacing_company.'px;';
        }
        if ( ! empty( $styles ) ) {
            $style_company = 'style="' . esc_attr( implode( ';', $styles ) ) . '"';
        }


        $query = new WP_Query( $args );

        if ( $query->have_posts() ) :

            $carousel_ouput = kt_render_carousel(apply_filters( 'kt_render_args', $atts), 'testimonial-layout-'.esc_attr($layout));

            $carousel_html = '';

            while ( $query->have_posts() ) : $query->the_post();

                $thumbnail = get_thumbnail_attachment(get_post_thumbnail_id(get_the_ID()), 'small');
                $carousel_html .= '<div class="testimonial-item testimonial-layout-'.esc_attr($layout).'" data-thumbnail="'.$thumbnail['url'].'">';
                $testimonial_content = '<div class="testimonial-content">'.do_shortcode(get_the_content()).'</div>';
                $link = rwmb_meta('_kt_testimonial_link');
                if( $link ){
                    $testimonial_author = '<h4 class="testimonial-author"><a target="_blank" href="'.$link.'" '.$style_title.'>'.get_the_title().'</a></h4>';
                }else{
                    $testimonial_author = '<h4 class="testimonial-author" '.$style_title.'>'.get_the_title().'</h4>';
                }
                $testimonial_author .= '<div class="testimonial-info" '.$style_company.'>'.rwmb_meta('_kt_testimonial_company').'</div>';
                $testimonial_rate = '<div class="testimonial-rate rate-'.rwmb_meta('_kt_rate').'"><span class="star-active"></span></div>';

                $carousel_html .= sprintf('%s <div class="testimonial-author-infos"> %s %s</div>', $testimonial_content, $testimonial_author, $testimonial_rate );

                $carousel_html .= '</div><!-- .testimonial-posts-item -->';

            endwhile; wp_reset_postdata();

            $output .= str_replace('%carousel_html%', $carousel_html, $carousel_ouput);

        endif;

        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );
        return '<div class="'.esc_attr( $elementClass ).'">'.$output.'</div>';

    }
}

vc_map( array(
    "name" => esc_html__( "Testimonial Carousel", 'wingman'),
    "base" => "testimonial_carousel",
    "category" => esc_html__('by Theme', 'wingman' ),
    "wrapper_class" => "clearfix",
    "params" => array(
        array(
            "type" => "textfield",
            "heading" => esc_html__( "Title", 'wingman' ),
            "param_name" => "title",
            "admin_label" => true,
            'description' => '',
        ),
        array(
            'type' => 'hidden',
            'heading' => esc_html__( 'URL (Link)', 'js_composer' ),
            'param_name' => 'link',
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Layout', 'wingman' ),
            'param_name' => 'layout',
            'value' => array(
                esc_html__( 'Layout 1', 'wingman' ) => '1',
                esc_html__( 'Layout 2', 'wingman' ) => '2',
            ),
            'description' => esc_html__( 'Select your layout.', 'wingman' ),
            "admin_label" => true,
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Skin', 'wingman' ),
            'param_name' => 'skin',
            'value' => array(
                esc_html__( 'Default', 'wingman' ) => '',
                esc_html__( 'Light', 'wingman' ) => 'light',
            ),
            'description' => esc_html__( 'Select your layout.', 'wingman' ),
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
        // Data settings
        array(
            "type" => "dropdown",
            "heading" => esc_html__("Data source", 'wingman'),
            "param_name" => "source",
            "value" => array(
                esc_html__('All', 'wingman') => '',
                esc_html__('Specific Categories', 'wingman') => 'categories',
                esc_html__('Specific Posts', 'wingman') => 'posts',
            ),
            "admin_label" => true,
            'std' => 'all',
            "description" => esc_html__("Select content type for your posts.", 'wingman'),
            'group' => esc_html__( 'Data settings', 'js_composer' ),
        ),
        array(
            "type" => "kt_taxonomy",
            'taxonomy' => 'testimonial-category',
            'heading' => esc_html__( 'Categories', 'wingman' ),
            'param_name' => 'categories',
            'placeholder' => esc_html__( 'Select your categories', 'wingman' ),
            "dependency" => array("element" => "source","value" => array('categories')),
            'multiple' => true,
            'group' => esc_html__( 'Data settings', 'js_composer' ),
        ),
        array(
            "type" => "kt_posts",
            'args' => array('post_type' => 'kt_testimonial', 'posts_per_page' => -1),
            'heading' => esc_html__( 'Specific Posts', 'js_composer' ),
            'param_name' => 'posts',
            'size' => '5',
            'placeholder' => esc_html__( 'Select your posts', 'js_composer' ),
            "dependency" => array("element" => "source","value" => array('posts')),
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
            'std' => 'center',
            "dependency" => array("element" => "navigation","value" => array('true')),
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
            'value' => 'true',
            "description" => esc_html__("Show pagination in carousel", 'wingman'),
            'group' => esc_html__( 'Carousel', 'wingman' )
        ),

        //Typography settings
        array(
            "type" => "kt_heading",
            "heading" => esc_html__("Author typography", 'wingman'),
            "param_name" => "author_typography",
            'group' => esc_html__( 'Typography', 'wingman' )
        ),
        array(
            'type' => 'font_container',
            'param_name' => 'font_container',
            'value' => '',
            'settings' => array(
                'fields' => array(
                    //'tag' => 'h2', // default value h2
                    'font_size',
                    'line_height',
                    'color',
                    'tag_description' => esc_html__( 'Select element tag.', 'js_composer' ),
                    'text_align_description' => esc_html__( 'Select text alignment.', 'js_composer' ),
                    'font_size_description' => esc_html__( 'Enter font size.', 'js_composer' ),
                    'line_height_description' => esc_html__( 'Enter line height.', 'js_composer' ),
                    'color_description' => esc_html__( 'Select heading color.', 'js_composer' ),
                ),
            ),
            'group' => esc_html__( 'Typography', 'wingman' )
        ),
        array(
            "type" => "kt_number",
            "heading" => esc_html__("Letter spacing", 'wingman'),
            "param_name" => "letter_spacing",
            "value" => 0,
            "min" => 0,
            "max" => 10,
            "suffix" => "px",
            "description" => "",
            'group' => esc_html__( 'Typography', 'wingman' ),
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__( 'Use theme default font family?', 'js_composer' ),
            'param_name' => 'use_theme_fonts',
            'value' => array( esc_html__( 'Yes', 'js_composer' ) => 'yes' ),
            'description' => esc_html__( 'Use font family from the theme.', 'js_composer' ),
            'group' => esc_html__( 'Typography', 'wingman' ),
            'std' => 'yes'
        ),
        array(
            'type' => 'google_fonts',
            'param_name' => 'google_fonts',
            'value' => '',
            'settings' => array(
                'fields' => array(
                    'font_family_description' => esc_html__( 'Select font family.', 'js_composer' ),
                    'font_style_description' => esc_html__( 'Select font styling.', 'js_composer' )
                )
            ),
            'group' => esc_html__( 'Typography', 'wingman' ),
            'dependency' => array(
                'element' => 'use_theme_fonts',
                'value_not_equal_to' => 'yes',
            ),
        ),
        array(
            "type" => "kt_heading",
            "heading" => esc_html__("Company typography", 'wingman'),
            "param_name" => "company_typography",
            'group' => esc_html__( 'Typography', 'wingman' )
        ),
        array(
            'type' => 'font_container',
            'param_name' => 'font_container_company',
            'value' => '',
            'settings' => array(
                'fields' => array(
                    //'tag' => 'h2', // default value h2
                    'font_size',
                    'line_height',
                    'color',
                    'tag_description' => esc_html__( 'Select element tag.', 'js_composer' ),
                    'text_align_description' => esc_html__( 'Select text alignment.', 'js_composer' ),
                    'font_size_description' => esc_html__( 'Enter font size.', 'js_composer' ),
                    'line_height_description' => esc_html__( 'Enter line height.', 'js_composer' ),
                    'color_description' => esc_html__( 'Select heading color.', 'js_composer' ),
                ),
            ),
            'group' => esc_html__( 'Typography', 'wingman' )
        ),
        array(
            "type" => "kt_number",
            "heading" => esc_html__("Letter spacing", 'wingman'),
            "param_name" => "letter_spacing_company",
            "value" => 0,
            "min" => 0,
            "max" => 10,
            "suffix" => "px",
            "description" => "",
            'group' => esc_html__( 'Typography', 'wingman' ),
        ),

        array(
            'type' => 'checkbox',
            'heading' => esc_html__( 'Use theme default font family?', 'js_composer' ),
            'param_name' => 'use_theme_fonts_company',
            'value' => array( esc_html__( 'Yes', 'js_composer' ) => 'yes' ),
            'description' => esc_html__( 'Use font family from the theme.', 'js_composer' ),
            'group' => esc_html__( 'Typography', 'wingman' ),
            'std' => 'yes'
        ),
        array(
            'type' => 'google_fonts',
            'param_name' => 'google_fonts_company',
            'value' => '',
            'settings' => array(
                'fields' => array(
                    'font_family_description' => esc_html__( 'Select font family.', 'js_composer' ),
                    'font_style_description' => esc_html__( 'Select font styling.', 'js_composer' )
                )
            ),
            'group' => esc_html__( 'Typography', 'wingman' ),
            'dependency' => array(
                'element' => 'use_theme_fonts_company',
                'value_not_equal_to' => 'yes',
            ),
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

