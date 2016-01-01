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
    "name" => __( "Testimonial Carousel", KT_THEME_LANG),
    "base" => "testimonial_carousel",
    "category" => __('by Theme', KT_THEME_LANG ),
    "wrapper_class" => "clearfix",
    "params" => array(
        array(
            "type" => "textfield",
            "heading" => __( "Title", KT_THEME_LANG ),
            "param_name" => "title",
            "admin_label" => true,
            'description' => '',
        ),
        array(
            'type' => 'hidden',
            'heading' => __( 'URL (Link)', 'js_composer' ),
            'param_name' => 'link',
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Layout', KT_THEME_LANG ),
            'param_name' => 'layout',
            'value' => array(
                __( 'Layout 1', KT_THEME_LANG ) => '1',
                __( 'Layout 2', KT_THEME_LANG ) => '2',
            ),
            'description' => __( 'Select your layout.', KT_THEME_LANG ),
            "admin_label" => true,
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Skin', KT_THEME_LANG ),
            'param_name' => 'skin',
            'value' => array(
                __( 'Default', KT_THEME_LANG ) => '',
                __( 'Light', KT_THEME_LANG ) => 'light',
            ),
            'description' => __( 'Select your layout.', KT_THEME_LANG ),
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
            "heading" => __( "Extra class name", "js_composer"),
            "param_name" => "el_class",
            "description" => __( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer" ),
        ),
        // Data settings
        array(
            "type" => "dropdown",
            "heading" => __("Data source", KT_THEME_LANG),
            "param_name" => "source",
            "value" => array(
                __('All', KT_THEME_LANG) => '',
                __('Specific Categories', KT_THEME_LANG) => 'categories',
                __('Specific Posts', KT_THEME_LANG) => 'posts',
            ),
            "admin_label" => true,
            'std' => 'all',
            "description" => __("Select content type for your posts.", KT_THEME_LANG),
            'group' => __( 'Data settings', 'js_composer' ),
        ),
        array(
            "type" => "kt_taxonomy",
            'taxonomy' => 'testimonial-category',
            'heading' => __( 'Categories', KT_THEME_LANG ),
            'param_name' => 'categories',
            'placeholder' => __( 'Select your categories', KT_THEME_LANG ),
            "dependency" => array("element" => "source","value" => array('categories')),
            'multiple' => true,
            'group' => __( 'Data settings', 'js_composer' ),
        ),
        array(
            "type" => "kt_posts",
            'args' => array('post_type' => 'kt_testimonial', 'posts_per_page' => -1),
            'heading' => __( 'Specific Posts', 'js_composer' ),
            'param_name' => 'posts',
            'size' => '5',
            'placeholder' => __( 'Select your posts', 'js_composer' ),
            "dependency" => array("element" => "source","value" => array('posts')),
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
        // Carousel
        array(
            'type' => 'kt_switch',
            'heading' => __( 'Auto Height', KT_THEME_LANG ),
            'param_name' => 'autoheight',
            'value' => 'true',
            "edit_field_class" => "vc_col-sm-4 kt_margin_bottom",
            "description" => __("Enable auto height.", KT_THEME_LANG),
            'group' => __( 'Carousel', KT_THEME_LANG )
        ),
        array(
            'type' => 'kt_switch',
            'heading' => __( 'Mouse Drag', KT_THEME_LANG ),
            'param_name' => 'mousedrag',
            'value' => 'true',
            "edit_field_class" => "vc_col-sm-4 kt_margin_bottom",
            "description" => __("Mouse drag enabled.", KT_THEME_LANG),
            'group' => __( 'Carousel', KT_THEME_LANG )
        ),
        array(
            'type' => 'kt_switch',
            'heading' => __( 'AutoPlay', KT_THEME_LANG ),
            'param_name' => 'autoplay',
            'value' => 'false',
            "description" => __("Enable auto play.", KT_THEME_LANG),
            "edit_field_class" => "vc_col-sm-4 kt_margin_bottom",
            'group' => __( 'Carousel', KT_THEME_LANG )
        ),
        array(
            "type" => "kt_number",
            "heading" => __("AutoPlay Speed", KT_THEME_LANG),
            "param_name" => "autoplayspeed",
            "value" => "5000",
            "suffix" => __("milliseconds", KT_THEME_LANG),
            'group' => __( 'Carousel', KT_THEME_LANG ),
            "dependency" => array("element" => "autoplay","value" => array('true')),
        ),
        array(
            "type" => "kt_number",
            "heading" => __("Slide Speed", KT_THEME_LANG),
            "param_name" => "slidespeed",
            "value" => "200",
            "suffix" => __("milliseconds", KT_THEME_LANG),
            'group' => __( 'Carousel', KT_THEME_LANG )
        ),

        array(
            "type" => "kt_heading",
            "heading" => __("Navigation settings", KT_THEME_LANG),
            "param_name" => "navigation_settings",
            'group' => __( 'Carousel', KT_THEME_LANG )
        ),
        array(
            'type' => 'kt_switch',
            'heading' => __( 'Navigation', KT_THEME_LANG ),
            'param_name' => 'navigation',
            'value' => 'true',
            "description" => __("Show navigation in carousel", KT_THEME_LANG),
            'group' => __( 'Carousel', KT_THEME_LANG )
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Navigation position', KT_THEME_LANG ),
            'param_name' => 'navigation_position',
            'group' => __( 'Carousel', KT_THEME_LANG ),
            'value' => array(
                __( 'Center outside', KT_THEME_LANG) => 'center_outside',
                __( 'Center inside', KT_THEME_LANG) => 'center',
                __( 'Top', KT_THEME_LANG) => 'top',
                __( 'Bottom', KT_THEME_LANG) => 'bottom',
            ),
            'std' => 'center',
            "dependency" => array("element" => "navigation","value" => array('true')),
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Navigation style', 'js_composer' ),
            'param_name' => 'navigation_style',
            'group' => __( 'Carousel', KT_THEME_LANG ),
            'value' => array(
                __( 'Normal', KT_THEME_LANG ) => '',
                __( 'Circle Border', KT_THEME_LANG ) => 'circle_border',
                __( 'Square Border', KT_THEME_LANG ) => 'square_border',
                __( 'Round Border', KT_THEME_LANG ) => 'round_border',
            ),
            'std' => '',
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
            'description' => __( 'Select your style for navigation.', KT_THEME_LANG ),
            "dependency" => array("element" => "navigation","value" => array('true')),
            'group' => __( 'Carousel', KT_THEME_LANG )
        ),

        array(
            "type" => "kt_heading",
            "heading" => __("Pagination settings", KT_THEME_LANG),
            "param_name" => "pagination_settings",
            'group' => __( 'Carousel', KT_THEME_LANG )
        ),
        array(
            'type' => 'kt_switch',
            'heading' => __( 'Pagination', KT_THEME_LANG ),
            'param_name' => 'pagination',
            'value' => 'true',
            "description" => __("Show pagination in carousel", KT_THEME_LANG),
            'group' => __( 'Carousel', KT_THEME_LANG )
        ),

        //Typography settings
        array(
            "type" => "kt_heading",
            "heading" => __("Author typography", KT_THEME_LANG),
            "param_name" => "author_typography",
            'group' => __( 'Typography', KT_THEME_LANG )
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
                    'tag_description' => __( 'Select element tag.', 'js_composer' ),
                    'text_align_description' => __( 'Select text alignment.', 'js_composer' ),
                    'font_size_description' => __( 'Enter font size.', 'js_composer' ),
                    'line_height_description' => __( 'Enter line height.', 'js_composer' ),
                    'color_description' => __( 'Select heading color.', 'js_composer' ),
                ),
            ),
            'group' => __( 'Typography', KT_THEME_LANG )
        ),
        array(
            "type" => "kt_number",
            "heading" => __("Letter spacing", KT_THEME_LANG),
            "param_name" => "letter_spacing",
            "value" => 0,
            "min" => 0,
            "max" => 10,
            "suffix" => "px",
            "description" => "",
            'group' => __( 'Typography', KT_THEME_LANG ),
        ),
        array(
            'type' => 'checkbox',
            'heading' => __( 'Use theme default font family?', 'js_composer' ),
            'param_name' => 'use_theme_fonts',
            'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
            'description' => __( 'Use font family from the theme.', 'js_composer' ),
            'group' => __( 'Typography', KT_THEME_LANG ),
            'std' => 'yes'
        ),
        array(
            'type' => 'google_fonts',
            'param_name' => 'google_fonts',
            'value' => 'font_family:Montserrat|font_style:400%20regular%3A400%3Anormal',
            'settings' => array(
                'fields' => array(
                    'font_family_description' => __( 'Select font family.', 'js_composer' ),
                    'font_style_description' => __( 'Select font styling.', 'js_composer' )
                )
            ),
            'group' => __( 'Typography', KT_THEME_LANG ),
            'dependency' => array(
                'element' => 'use_theme_fonts',
                'value_not_equal_to' => 'yes',
            ),
        ),
        array(
            "type" => "kt_heading",
            "heading" => __("Company typography", KT_THEME_LANG),
            "param_name" => "company_typography",
            'group' => __( 'Typography', KT_THEME_LANG )
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
                    'tag_description' => __( 'Select element tag.', 'js_composer' ),
                    'text_align_description' => __( 'Select text alignment.', 'js_composer' ),
                    'font_size_description' => __( 'Enter font size.', 'js_composer' ),
                    'line_height_description' => __( 'Enter line height.', 'js_composer' ),
                    'color_description' => __( 'Select heading color.', 'js_composer' ),
                ),
            ),
            'group' => __( 'Typography', KT_THEME_LANG )
        ),
        array(
            "type" => "kt_number",
            "heading" => __("Letter spacing", KT_THEME_LANG),
            "param_name" => "letter_spacing_company",
            "value" => 0,
            "min" => 0,
            "max" => 10,
            "suffix" => "px",
            "description" => "",
            'group' => __( 'Typography', KT_THEME_LANG ),
        ),

        array(
            'type' => 'checkbox',
            'heading' => __( 'Use theme default font family?', 'js_composer' ),
            'param_name' => 'use_theme_fonts_company',
            'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
            'description' => __( 'Use font family from the theme.', 'js_composer' ),
            'group' => __( 'Typography', KT_THEME_LANG ),
            'std' => 'yes'
        ),
        array(
            'type' => 'google_fonts',
            'param_name' => 'google_fonts_company',
            'value' => 'font_family:Montserrat|font_style:400%20regular%3A400%3Anormal',
            'settings' => array(
                'fields' => array(
                    'font_family_description' => __( 'Select font family.', 'js_composer' ),
                    'font_style_description' => __( 'Select font styling.', 'js_composer' )
                )
            ),
            'group' => __( 'Typography', KT_THEME_LANG ),
            'dependency' => array(
                'element' => 'use_theme_fonts_company',
                'value_not_equal_to' => 'yes',
            ),
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

