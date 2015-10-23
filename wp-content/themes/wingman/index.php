<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * e.g., it puts together the home page when no home.php file exists.
 *
 * Learn more: {@link https://codex.wordpress.org/Template_Hierarchy}
 *
 */


if( is_singular() ){
    if( is_page( ) ){
        include_once(get_template_directory().'/page.php' ) ;
    }else{
        include_once (get_template_directory().'/single.php');
    }
}else{
    include get_template_directory().'/archive.php';
}
