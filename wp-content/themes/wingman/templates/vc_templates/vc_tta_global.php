<?php
/**
 * Shortcode attributes
 * @var $atts
 * @var $content - shortcode content
 * @var $this
 *     WPBakeryShortCode_VC_Tta_Accordion|WPBakeryShortCode_VC_Tta_Tabs|WPBakeryShortCode_VC_Tta_Tour|WPBakeryShortCode_VC_Tta_Pageable
 */
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
$this->resetVariables( $atts, $content );

extract( $atts );

$this->setGlobalTtaInfo();

$this->enqueueTtaScript();

// It is required to be before tabs-list-top/left/bottom/right for tabs/tours
$prepareContent = $this->getTemplateVariable( 'content' );

$class_to_filter = $this->getTtaGeneralClasses();
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

$uniqid = 'vc_tta-'.uniqid();
$custom_css = '';

$output = '<div ' . $this->getWrapperAttributes() . ' id="'.$uniqid.'">';
$output .= $this->getTemplateVariable( 'title' );
$output .= '<div class="' . esc_attr( $css_class ) . '">';
$output .= $this->getTemplateVariable( 'tabs-list-top' );
$output .= $this->getTemplateVariable( 'tabs-list-left' );
$output .= '<div class="vc_tta-panels-container">';
$output .= $this->getTemplateVariable( 'pagination-top' );
$output .= '<div class="vc_tta-panels">';
$output .= $prepareContent;
$output .= '</div>';
$output .= $this->getTemplateVariable( 'pagination-bottom' );
$output .= '</div>';
$output .= $this->getTemplateVariable( 'tabs-list-bottom' );
$output .= $this->getTemplateVariable( 'tabs-list-right' );
$output .= '</div>';


$google_fonts_obj = new Vc_Google_Fonts();
$google_fonts_data = strlen( $google_fonts ) > 0 ? $google_fonts_obj->_vc_google_fonts_parse_attributes( array(), $google_fonts ) : '';



$styles = array();
if ( ! empty( $font_container_data ) && isset( $font_container_data['values'] ) ) {
    foreach ( $font_container_data['values'] as $key => $value ) {
        if ( $key !== 'tag' && strlen( $value ) > 0 ) {
            if ( preg_match( '/description/', $key ) ) {
                continue;
            }
            if ( $key === 'font_size' || $key === 'line_height' ) {
                $value = preg_replace( '/\s+/', '', $value );
            }
            if ( $key === 'font_size' ) {
                $pattern = '/^(\d*(?:\.\d+)?)\s*(px|\%|in|cm|mm|em|rem|ex|pt|pc|vw|vh|vmin|vmax)?$/';
                // allowed metrics: http://www.w3schools.com/cssref/css_units.asp
                $regexr = preg_match( $pattern, $value, $matches );
                $value = isset( $matches[1] ) ? (float) $matches[1] : (float) $value;
                $unit = isset( $matches[2] ) ? $matches[2] : 'px';
                $value = $value . $unit;
            }
            if ( strlen( $value ) > 0 ) {
                $styles[] = str_replace( '_', '-', $key ) . ': ' . $value;
            }
        }
    }
}
if ( ( ! isset( $atts['use_theme_fonts'] ) || 'yes' !== $atts['use_theme_fonts'] ) && ! empty( $google_fonts_data ) && isset( $google_fonts_data['values'], $google_fonts_data['values']['font_family'], $google_fonts_data['values']['font_style'] ) ) {
    $google_fonts_family = explode( ':', $google_fonts_data['values']['font_family'] );
    $styles[] = "font-family:" . $google_fonts_family[0];
    $google_fonts_styles = explode( ':', $google_fonts_data['values']['font_style'] );
    $styles[] = "font-weight:" . $google_fonts_styles[1];
    $styles[] = "font-style:" . $google_fonts_styles[2];
}

if(count($styles)){
    $settings = get_option( 'wpb_js_google_fonts_subsets' );
    $subsets = '';
    if ( is_array( $settings ) && ! empty( $settings ) ) {
        $subsets = '&subset=' . implode( ',', $settings );
    }
    if ( ! empty( $google_fonts_data ) && isset( $google_fonts_data['values']['font_family'] ) ) {
        wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_fonts_data['values']['font_family'] ), '//fonts.googleapis.com/css?family=' . $google_fonts_data['values']['font_family'] . $subsets );
    }

    $custom_css = '#'.$uniqid.' .vc_tta-tab > a{'.implode( ';', $styles ).'}';
    $custom_css .= '#'.$uniqid.' .vc_tta-panel-title > a{'.implode( ';', $styles ).'}';

    $custom_css = '<div class="kt_custom_css" data-css="'.esc_attr($custom_css).'"></div>';

    $output .= $custom_css;
}


$output .= '</div>';


echo $output;