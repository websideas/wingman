<?php

/**
 * Shortcode attributes
 * @var $atts
 * @var $type
 * @var $icon_fontawesome
 * @var $icon_openiconic
 * @var $icon_typicons
 * @var $icon_entypo
 * @var $icon_linecons
 * @var $color
 * @var $custom_color
 * @var $background_style
 * @var $background_color
 * @var $custom_background_color
 * @var $size
 * @var $align
 * @var $el_class
 * @var $link
 * @var $css_animation
 * Shortcode class
 * @var $this WPBakeryShortCode_VC_Icon
 */
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$class = $this->getExtraClass( $el_class );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class, $this->settings['base'], $atts );
$css_class .= $this->getCSSAnimation( $css_animation );
// Enqueue needed icon font.
vc_icon_element_fonts_enqueue( $type );

$uniqid = 'vc_icon_element_'.uniqid();
$custom_css = '';


if($background_style == 'hexagonal'){
    if( $background_color == 'custom' && !$custom_background_color){
        $custom_background_color = '#ebebeb';
        $custom_css .= '#'.$uniqid.' .vc_icon_element-inner:before{border-bottom-color: '.$custom_background_color.';}';
        $custom_css .= '#'.$uniqid.' .vc_icon_element-inner:after{border-top-color: '.$custom_background_color.';}';
    }


    if($background_color_hover){
        if(isset($hover_div)){
            $custom_css .= '#'.$hover_div.':hover .vc_icon_element-inner:before{border-bottom-color: '.$background_color_hover.';}';
            $custom_css .= '#'.$hover_div.':hover .vc_icon_element-inner:after{border-top-color: '.$background_color_hover.';}';
        }else{
            $custom_css .= '#'.$uniqid.' .vc_icon_element-inner:hover:before{border-bottom-color: '.$background_color_hover.';}';
            $custom_css .= '#'.$uniqid.' .vc_icon_element-inner:hover:after{border-top-color: '.$background_color_hover.';}';

        }
    }
}

if($color_hover){
    if(isset($hover_div)) {
        $custom_css .= '#' . $hover_div . ':hover .vc_icon_element .vc_icon_element-icon{color:' . $color_hover . '!important;}';
    }else{
        $custom_css .= '#' . $uniqid . '.vc_icon_element .vc_icon_element-inner:hover .vc_icon_element-icon{color:' . $color_hover . '!important;}';
    }
}


if ( strlen( $background_style ) > 0 ) {
    if ( strpos( $background_style, 'outline' ) !== false ) {
        if($background_color_hover){
            if(isset($hover_div)){
                $custom_css .= '#'.$hover_div.':hover .vc_icon_element .vc_icon_element-inner{border-color:'.$background_color_hover.'!important;}';
            }else{
                $custom_css .= '#'.$uniqid.'.vc_icon_element .vc_icon_element-inner:hover{border-color:'.$background_color_hover.'!important;}';
            }
        }
    } else {
        if($background_color_hover){
            if(isset($hover_div)) {
                $custom_css .= '#' . $hover_div . ':hover .vc_icon_element .vc_icon_element-inner{background:' . $background_color_hover . '!important;}';
            }else{
                $custom_css .= '#' . $uniqid . '.vc_icon_element .vc_icon_element-inner:hover{background:' . $background_color_hover . '!important;}';
            }
        }
    }
}
if($custom_css){
    $custom_css = '<div class="kt_custom_css" data-css="'.esc_attr($custom_css).'"></div>';
}








$url = vc_build_link( $link );
$has_style = false;
if ( strlen( $background_style ) > 0 ) {
    $has_style = true;
    if ( strpos( $background_style, 'outline' ) !== false ) {
        $background_style .= ' vc_icon_element-outline'; // if we use outline style it is border in css
    } else {
        $background_style .= ' vc_icon_element-background';
    }
}

$iconClass = isset( ${"icon_" . $type} ) ? esc_attr( ${"icon_" . $type} ) : 'fa fa-adjust';

if(!$iconClass) return;


$iconClass = isset( ${"icon_" . $type} ) ? esc_attr( ${"icon_" . $type} ) : 'fa fa-adjust';

$style = '';
if ( 'custom' === $background_color ) {
    if ( false !== strpos( $background_style, 'outline' ) ) {
        $style = 'border-color:' . $custom_background_color;
    } else {
        $style = 'background-color:' . $custom_background_color;
    }
}
$style = $style ? ' style="' . esc_attr( $style ) . '"' : '';


?>
<div id="<?php echo $uniqid; ?>"
    class="vc_icon_element vc_icon_element-outer<?php echo strlen( $css_class ) > 0 ? ' ' . trim( esc_attr( $css_class ) ) : ''; ?> vc_icon_element-align-<?php echo esc_attr( $align ); ?><?php if ( $has_style ): echo ' vc_icon_element-have-style'; else: echo ' vc_icon_element-no-style'; endif; ?>">
    <div
        class="vc_icon_element-inner vc_icon_element-color-<?php echo esc_attr( $color ); ?><?php if ( $has_style ): echo ' vc_icon_element-have-style-inner'; endif; ?> vc_icon_element-size-<?php echo esc_attr( $size ); ?> vc_icon_element-style-<?php echo esc_attr( $background_style ); ?> vc_icon_element-background-color-<?php echo esc_attr( $background_color ); ?>"<?php echo $style ?>><span
            class="vc_icon_element-icon <?php echo $iconClass; ?>" <?php echo( $color === 'custom' ? 'style="color:' . esc_attr( $custom_color ) . '"' : '' ); ?>></span><?php
        if ( strlen( $link ) > 0 && strlen( $url['url'] ) > 0 ) {
            echo '<a class="vc_icon_element-link" href="' . esc_attr( $url['url'] ) . '" title="' . esc_attr( $url['title'] ) . '" target="' . ( strlen( $url['target'] ) > 0 ? esc_attr( $url['target'] ) : '_self' ) . '"></a>';
        }
        ?></div>
    <?php echo $custom_css; ?>
</div><?php echo $this->endBlockComment( $this->getShortcode() ); ?>