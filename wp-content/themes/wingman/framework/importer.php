<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;



add_filter( 'kt_import_demo', 'kt_import_demo_wingman' );
function kt_import_demo_wingman( $demos ){
    $demos['demo1'] = array(
        'title' => 'Classic',
        'previewlink' => 'http://wingman.kitethemes.com/',
        'xml_count' => 2,
        'status' => sprintf(
            '<span class="%s">%s</span>',
            'demo-main',
            __('Main', 'wingman')
        )
    );

    $demos['demo2'] = array(
        'title' => 'Fashion',
        'previewlink' => 'http://wingman.kitethemes.com/demo2/',
        'xml_count' => 2,
        'status' => sprintf(
            '<span class="%s">%s</span>',
            'demo-hot',
            __('Hot', 'wingman')
        )
    );

    $demos['demo3'] = array(
        'title' => 'Cosmetic',
        'previewlink' => 'http://wingman.kitethemes.com/demo3/',
        'xml_count' => 2,
    );

    $demos['demo4'] = array(
        'title' => 'Coming soon',
        'previewlink' => '#',
        'coming' => true
    );


    return $demos;
}


if ( !function_exists( 'kt_extended_imported' ) ) {


    function kt_extended_imported( $demoid ) {

        /************************************************************************
        * Import slider(s) for the current demo being imported
        *************************************************************************/

        if ( class_exists( 'RevSlider' ) ) {

            $wbc_sliders_array = array(
                'demo1' => 'slider1.zip',
                'demo2' => 'slider2.zip',
                'demo3' => 'slider3.zip',
            );
            if ( isset( $wbc_sliders_array[$demoid]  ) ) {
                $wbc_slider_import = $wbc_sliders_array[$demoid];

                $slider_import = KT_THEME_DIR.'/dummy-data/revslider/'.$wbc_slider_import;

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
        $kt_home_pages = array(
            'demo1' => 'Home',
            'demo2' => 'Home',
            'demo3' => 'Home',
        );

        if ( isset( $wbc_sliders_array[$demoid]  ) ) {
            $page = get_page_by_title( $kt_home_pages[$demoid] );
            if ( isset( $page->ID ) ) {
                update_option( 'page_on_front', $page->ID );
                update_option( 'show_on_front', 'page' );
            }
        }

        /************************************************************************
         * Set Posts page
         *************************************************************************/

        // array of demos/Posts page to check/select from
        $kt_posts_pages = array(
            'demo1' => 'Blog',
            'demo2' => 'Blog',
            'demo3' => 'Blog',
        );

        if ( isset( $kt_posts_pages[$demoid]  ) ) {
            $page = get_page_by_title( $kt_posts_pages[$demoid] );
            if ( isset( $page->ID ) ) {
                update_option( 'page_for_posts', $page->ID );
            }
        }


    }
    add_action( 'kt_importer_after_content_import', 'kt_extended_imported');
}




function kt_importer_dir_wingman( ) {
    return KT_THEME_DATA_DIR.'/';
}
add_filter('kt_importer_dir', 'kt_importer_dir_wingman' );

function kt_importer_url_wingman( ) {

    return KT_THEME_DATA.'/';
}
add_filter('kt_importer_url', 'kt_importer_url_wingman' );

function kt_importer_opt_name_wingman(  ) {
    return KT_THEME_OPTIONS;
}
add_filter('kt_importer_opt_name', 'kt_importer_opt_name_wingman' );





