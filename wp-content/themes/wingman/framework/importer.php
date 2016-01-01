<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;


/************************************************************************
* Extended Example:
* Way to set menu, import revolution slider, and set home page.
*************************************************************************/

if ( !function_exists( 'kt_extended_imported' ) ) {
    /**
     *
     *
     * @param $demo_active_import
     * @param $demo_directory_path
     */
    function kt_extended_imported( $demo_active_import , $demo_directory_path ) {
        reset( $demo_active_import );
        $current_key = key( $demo_active_import );
        /************************************************************************
        * Import slider(s) for the current demo being imported
        *************************************************************************/

        if ( class_exists( 'RevSlider' ) ) {

            $wbc_sliders_array = array(
                'demo1' => 'slider1.zip',
                'demo2' => 'slider2.zip',
                'demo3' => 'slider3.zip',
            );
            if ( isset( $demo_active_import[$current_key]['directory'] ) && !empty( $demo_active_import[$current_key]['directory'] ) && array_key_exists( $demo_active_import[$current_key]['directory'], $wbc_sliders_array ) ) {
                $wbc_slider_import = $wbc_sliders_array[$demo_active_import[$current_key]['directory']];

                $slider_import = KT_THEME_DIR.'dummy-data/revslider/'.$wbc_slider_import;
                if ( file_exists( $slider_import ) ) {
                    $slider = new RevSlider();
                    $slider->importSliderFromPost( true, true, $slider_import );
                }
            }

        }

        /************************************************************************
         * Setting Menus
         *************************************************************************/

        $main_menu = get_term_by( 'name', __('Main menu', 'wingman'), 'nav_menu' );
        set_theme_mod( 'nav_menu_locations', array(
                'primary' => $main_menu->term_id
            )
        );

        /************************************************************************
         * Set HomePage
         *************************************************************************/

        // array of demos/homepages to check/select from
        $wbc_home_pages = array(
            'demo1' => 'Home',
            'demo2' => 'Home',
            'demo3' => 'Home',
        );

        if ( isset( $demo_active_import[$current_key]['directory'] ) && !empty( $demo_active_import[$current_key]['directory'] ) && array_key_exists( $demo_active_import[$current_key]['directory'], $wbc_home_pages ) ) {
            $page = get_page_by_title( $wbc_home_pages[$demo_active_import[$current_key]['directory']] );
            if ( isset( $page->ID ) ) {
                update_option( 'page_on_front', $page->ID );
                update_option( 'show_on_front', 'page' );
            }
        }

    }
    add_action( 'wbc_importer_after_content_import', 'kt_extended_imported', 10, 2 );
}




if(!function_exists('kt_change_demo_directory_path')){
    /**
     * Change the path to the directory that contains demo data folders.
     *
     * @param [string] $demo_directory_path
     *
     * @return [string]
     */

    function kt_change_demo_directory_path( ) {
        $demo_directory_path = KT_THEME_DIR.'dummy-data/';
        return $demo_directory_path;
    }
    add_filter('wbc_importer_dir_path', 'kt_change_demo_directory_path' );
}
