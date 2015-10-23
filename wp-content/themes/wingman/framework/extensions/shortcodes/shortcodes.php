<?php
/* TOOLTIP SHORTCODE
================================================= */
function tooltip_shortcode($atts, $content = null) {
	extract(shortcode_atts(array(
		'title' => '',
        'tag' => 'a',
		'link' => '#',
		'direction' => 'top'
	), $atts));
    
    if( $tag == 'a' ){ $href = 'href="'.$link.'"'; }else{ $href = ''; }
			
	$output = '<'.$tag.' '.$href.' data-toggle="tooltip" title="'.$title.'" data-placement="'.$direction.'">'.do_shortcode($content).'</'.$tag.'>';

	return $output;
}

add_shortcode('kt_tooltip', 'tooltip_shortcode');

/* HIGHTLIGHT SHORTCODE
================================================= */
function highlight_shortcode($atts, $content = null) {
    extract(shortcode_atts(array(
		'background' => '#ecb848',
        'color' => '#fff',
	), $atts));
    return '<span class="highlight" style="background:'.$background.';color:'.$color.'">'. do_shortcode($content) .'</span>';
}
add_shortcode("kt_highlight", "highlight_shortcode");

/* BLOCKQUOTE SHORTCODE
================================================= */
/*
function blockquote_shortcode($atts, $content = null) {
    extract(shortcode_atts(array(
		'style' => '',
        'reverse' => 'false'
	), $atts));
    if( $reverse == 'true' ){ $class = ' blockquote-reverse'; }else{ $class = ''; }
    
	return '<blockquote class="'.$style.$class.'">'. do_shortcode($content) .'</blockquote>';
}
add_shortcode("kt_blockquote", "blockquote_shortcode");*/