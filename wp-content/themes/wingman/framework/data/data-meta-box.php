<?php
/**
 * All helpers for theme
 *
 */
 
 
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;




add_filter( 'rwmb_meta_boxes', 'kt_register_meta_boxes' );
function kt_register_meta_boxes( $meta_boxes )
{
    $prefix = '_kt_';
    $image_sizes = kt_get_image_sizes();
    $menus = wp_get_nav_menus();

    $menus_arr = array('' => __('Default', KT_THEME_LANG));
    foreach ( $menus as $menu ) {
        $menus_arr[$menu->term_id] = esc_html( $menu->name );
    }

    /**
     * For Post Audio
     *
     */

    $meta_boxes[] = array(
        'title'  => __('Audio Settings',KT_THEME_LANG),
        'pages'  => array( 'post' ),
        'show'   => array(
            'post_format' => array( 'Audio'),
        ),

        'fields' => array(
            array(
                'name' => __('Audio Type', KT_THEME_LANG),
                'id' => $prefix . 'audio_type',
                'type'     => 'select',
                'options'  => array(
                    '' => __('Select Option', KT_THEME_LANG),
                    'upload' => __('Upload', KT_THEME_LANG),
                    'soundcloud' => __('Soundcloud', KT_THEME_LANG),
                ),
            ),
            array(
                'name'             => __( 'Upload MP3 File', KT_THEME_LANG ),
                'id'               => "{$prefix}audio_mp3",
                'type'             => 'file_advanced',
                'max_file_uploads' => 1,
                'mime_type'        => 'audio', // Leave blank for all file types
                'compare' => array($prefix . 'audio_type','=', 'upload' ),
            ),
            array(
                'name' => __( 'Soundcloud', KT_THEME_LANG ),
                'desc' => __( 'Paste embed iframe or Wordpress shortcode.', KT_THEME_LANG ),
                'id'   => "{$prefix}audio_soundcloud",
                'type' => 'textarea',
                'cols' => 20,
                'rows' => 3,
                'compare' => array($prefix . 'audio_type','=', 'soundcloud' ),
            ),
        ),
    );

    /**
     * For Video
     *
     */

    $meta_boxes[] = array(
        'title'  => __('Video Settings',KT_THEME_LANG),
        'pages'  => array( 'post' ),
        'show'   => array(
            'post_format' => array( 'Video'),
        ),

        'fields' => array(
            array(
                'name' => __('Video Type', KT_THEME_LANG),
                'id' => $prefix . 'video_type',
                'type'     => 'select',
                'options'  => array(
                    '' => __('Select Option', KT_THEME_LANG),
                    'external' => __('External url', KT_THEME_LANG),
                ),
            ),
            array(
                'name' => __('Choose Video', KT_THEME_LANG),
                'id' => $prefix . 'choose_video',
                'type'     => 'select',
                'options'  => array(
                    'youtube' => __('Youtube', KT_THEME_LANG),
                    'vimeo' => __('Vimeo', KT_THEME_LANG),
                ),
                'compare' => array($prefix . 'video_type','=', 'external' ),
            ),
            array(
                'name' => __( 'Video id', KT_THEME_LANG ),
                'id' => $prefix . 'video_id',
                'desc' => sprintf( __( 'Enter id of video .Example: <br />- Link video youtube: https://www.youtube.com/watch?v=nPOO1Coe2DI id of video: nPOO1Coe2DI <br /> -Link vimeo: https://vimeo.com/70296428 id video: 70296428.', KT_THEME_LANG ) ),
                'type'  => 'text',
                'compare' => array($prefix . 'video_type','=', 'external' ),
            ),
        ),
    );

    /**
     * For Gallery
     *
     */

    $meta_boxes[] = array(
        'title'  => __('Gallery Settings',KT_THEME_LANG),
        'pages'  => array( 'post' ),
        'show'   => array(
            'post_format' => array( 'Gallery'),
        ),

        'fields' => array(
            array(
                'name' => __('Gallery Type', KT_THEME_LANG),
                'id' => $prefix . 'gallery_type',
                'type'     => 'select',
                'options'  => array(
                    '' => __('Default', KT_THEME_LANG),
                    'rev' => __('Revolution Slider', KT_THEME_LANG),
                    'layer' => __('Layer Slider', KT_THEME_LANG)
                ),
            ),

            array(
                'name' => __('Select Revolution Slider', KT_THEME_LANG),
                'id' => $prefix . 'gallery_rev_slider',
                'default' => true,
                'type' => 'revSlider',
                'compare' => array($prefix . 'gallery_type','=', 'rev' ),
            ),
            array(
                'name' => __('Select Layer Slider', KT_THEME_LANG),
                'id' => $prefix . 'gallery_layerslider',
                'default' => true,
                'type' => 'layerslider',
                'compare' => array($prefix . 'gallery_type','=', 'layer' ),
            ),
            array(
                'name' => __( 'Gallery images', 'your-prefix' ),
                'id'  => "{$prefix}gallery_images",
                'type' => 'image_advanced',
                'desc' => __( "You can drag and drop for change order image", KT_THEME_LANG ),
                'compare' => array($prefix . 'gallery_type','=', '' ),
            ),
        ),
    );



    /**
     * For Link
     *
     */

    $meta_boxes[] = array(
        'title'  => __('Link Settings',KT_THEME_LANG),
        'pages'  => array( 'post' ),
        'show'   => array(
            'post_format' => array( 'Link'),
        ),
        'fields' => array(
            array(
                'name' => __( 'External URL', KT_THEME_LANG ),
                'id' => $prefix . 'external_url',
                'desc' => __( "Input your link in here", KT_THEME_LANG ),
                'type'  => 'text',
            ),

        ),
    );

    /**
     * For Quote
     *
     */

    $meta_boxes[] = array(
        'title'  => __('Quote Settings',KT_THEME_LANG),
        'pages'  => array( 'post' ),
        'show'   => array(
            'post_format' => array( 'Quote'),
        ),
        'fields' => array(
            array(
                'name' => __( 'Quote Content', KT_THEME_LANG ),
                'desc' => __( 'Please type the text for your quote here.', KT_THEME_LANG ),
                'id'   => "{$prefix}quote_content",
                'type' => 'textarea',
                'cols' => 20,
                'rows' => 3,
            ),
            array(
                'name' => __( 'Author', KT_THEME_LANG ),
                'id' => $prefix . 'quote_author',
                'desc' => __( "Please type the text for author quote here.", KT_THEME_LANG ),
                'type'  => 'text',
            ),

        ),
    );
    
    /**
     * For Testimonial
     *
     */

    $meta_boxes[] = array(
        'title'  => __('Testimonial Settings',KT_THEME_LANG),
        'pages'  => array( 'kt_testimonial' ),
        'fields' => array(
            array(
                'name' => __( 'Company Name / Job Title', KT_THEME_LANG ),
                'id' => $prefix . 'testimonial_company',
                'desc' => __( "Please type the text for Company Name / Job Title here.", KT_THEME_LANG ),
                'type'  => 'text',
            ),
            array(
                'name' => __( "URL to Author's Website", KT_THEME_LANG ),
                'id' => $prefix . 'testimonial_link',
                'desc' => __( "Please type the text for link here.", KT_THEME_LANG ),
                'type'  => 'text',
            ),
            array(
                'name' => __('Rate', KT_THEME_LANG),
                'id'   => "{$prefix}rate",
                'type' => 'select',
                'options' => array(
                    '0'    => __('Choose star', KT_THEME_LANG),
                    '1'   => __('1', KT_THEME_LANG),
                    '2'   => __('2', KT_THEME_LANG),
                    '3'   => __('3', KT_THEME_LANG),
                    '4'   => __('4', KT_THEME_LANG),
                    '5'   => __('5', KT_THEME_LANG),
                ),
                'std'  => '0'
            ),
        ),
    );

    /**
     * For Layout option for post
     *
     */
    $meta_boxes[] = array(
        'id' => 'post_meta_boxes',
        'title' => 'Post Options',
        'pages' => array('post'),
        'context' => 'normal',
        'priority' => 'high',
        'fields' => array(
            array(
                'name' => __('Show Post format', KT_THEME_LANG),
                'id'   => "{$prefix}post_format",
                'type' => 'select',
                'options' => array(
                    -1    => __('Default', KT_THEME_LANG),
                    0		=> __('Hidden', KT_THEME_LANG),
                    1		=> __('Show', KT_THEME_LANG),
                ),
                'std'  => -1
            ),
            array(
                'name' => __('Title and meta center', KT_THEME_LANG),
                'id'   => "{$prefix}title_and_meta_center",
                'type' => 'select',
                'options' => array(
                    -1    => __('Default', KT_THEME_LANG),
                    0		=> __('No', KT_THEME_LANG),
                    1		=> __('Yes', KT_THEME_LANG),
                ),
                'std'  => -1
            ),

            array(
                'type' => 'select',
                'name' => __('Post image size', KT_THEME_LANG),
                'desc' => __('Select the format position.', KT_THEME_LANG),
                'id'   => "{$prefix}blog_image_size",
                'options' => array_merge(array('' => __('Default', KT_THEME_LANG)), $image_sizes),
                'std' => ''
            ),

            array(
                'name' => __('Meta info', KT_THEME_LANG),
                'id'   => "{$prefix}meta_info",
                'type' => 'select',
                'options' => array(
                    -1    => __('Default', KT_THEME_LANG),
                    0		=> __('Hidden', KT_THEME_LANG),
                    1		=> __('Show', KT_THEME_LANG),
                ),
                'std'  => -1
            ),
            array(
                'name' => __('Previous & next buttons', KT_THEME_LANG),
                'id'   => "{$prefix}prev_next",
                'type' => 'select',
                'options' => array(
                    -1    => __('Default', KT_THEME_LANG),
                    0		=> __('Hidden', KT_THEME_LANG),
                    1		=> __('Show', KT_THEME_LANG),
                ),
                'std'  => -1
            ),
            array(
                'name' => __('Author info', KT_THEME_LANG),
                'id'   => "{$prefix}author_info",
                'type' => 'select',
                'options' => array(
                    -1    => __('Default', KT_THEME_LANG),
                    0		=> __('Hidden', KT_THEME_LANG),
                    1		=> __('Show', KT_THEME_LANG),
                ),
                'std'  => -1
            ),
            array(
                'name' => __('Social sharing', KT_THEME_LANG),
                'id'   => "{$prefix}social_sharing",
                'type' => 'select',
                'options' => array(
                    -1    => __('Default', KT_THEME_LANG),
                    0		=> __('Hidden', KT_THEME_LANG),
                    1		=> __('Show', KT_THEME_LANG),
                ),
                'std'  => -1
            ),
            array(
                'name' => __('Related articles', KT_THEME_LANG),
                'id'   => "{$prefix}related_acticles",
                'type' => 'select',
                'options' => array(
                    -1    => __('Default', KT_THEME_LANG),
                    0		=> __('Hidden', KT_THEME_LANG),
                    1		=> __('Show', KT_THEME_LANG),
                ),
                'std'  => -1
            ),
        )
    );




    /**
     * For Team
     *
     */

    $meta_boxes[] = array(
        'title'  => __('Team Settings',KT_THEME_LANG),
        'pages'  => array( 'kt_team' ),
        'fields' => array(
            array(
                'name' => __( 'Regency', KT_THEME_LANG ),
                'id' => $prefix . 'team_regency',
                'desc' => __( "Regency.", KT_THEME_LANG ),
                'type'  => 'text',
            ),
            array(
                'name' => __( 'Twitter', KT_THEME_LANG ),
                'id' => $prefix . 'team_twitter',
                'desc' => __( "Link Twitter.", KT_THEME_LANG ),
                'type'  => 'text',
            ),
            array(
                'name' => __( 'Facebook', KT_THEME_LANG ),
                'id' => $prefix . 'team_facebook',
                'desc' => __( "Link Facebook.", KT_THEME_LANG ),
                'type'  => 'text',
            ),
            array(
                'name' => __( 'Google+', KT_THEME_LANG ),
                'id' => $prefix . 'team_googleplus',
                'desc' => __( "Link Google+.", KT_THEME_LANG ),
                'type'  => 'text',
            ),
        ),
    );


    /**
     * For Client
     * 
     */
    
    $meta_boxes[] = array(
        'id' => 'client_meta_boxes',
        'title' => 'Client Options',
        'pages' => array( 'kt_client' ),
        'context' => 'normal',
        'priority' => 'default',
        'fields' => array(
            
            array(
                'name' => __( 'Link Client', KT_THEME_LANG ),
                'id' => $prefix . 'link_client',
                'desc' => __( "Link Client.", KT_THEME_LANG ),
                'type'  => 'text',
            ),

        )
    );

    /**
     * For Layout option
     *
     */
    $meta_boxes[] = array(
        'id' => 'page_meta_boxes',
        'title' => 'Page Options',
        'pages' => array( 'page', 'post', 'product' ),
        'context' => 'normal',
        'priority' => 'high',
        'tabs'      => array(
            'header'  => array(
                'label' => __( 'Header', KT_THEME_LANG ),
                'icon'  => 'fa fa-desktop',
            ),
            'page_header' => array(
                'label' => __( 'Page Header', KT_THEME_LANG ),
                'icon'  => 'fa fa-bars',
            ),
            'page_layout' => array(
                'label' => __( 'Page layout', KT_THEME_LANG ),
                'icon'  => 'fa fa-columns',
            )
        ),
        'fields' => array(


            //Header
            array(
                'name'    => __( 'Header position', KT_THEME_LANG ),
                'type'     => 'select',
                'id'       => $prefix.'header_position',
                'desc'     => __( "Please choose header position", KT_THEME_LANG ),
                'options'  => array(
                    'default' => __('Default', KT_THEME_LANG),
                    'transparent' => __('Transparent header', KT_THEME_LANG),
                    'below' => __('Below Slideshow', KT_THEME_LANG),
                ),
                'std'  => 'default',
                'tab'  => 'header',
            ),

            array(
                'name' => __('Select Your Slideshow Type', KT_THEME_LANG),
                'id' => $prefix . 'slideshow_source',
                'desc' => __("You can select the slideshow type using this option.", KT_THEME_LANG),
                'type' => 'select',
                'options' => array(
                    '' => __('Select Option', KT_THEME_LANG),
                    'revslider' => __('Revolution Slider', KT_THEME_LANG),
                    'layerslider' => __('Layer Slider', KT_THEME_LANG),
                ),
                'tab'  => 'header',
            ),
            array(
                'name' => __('Select Revolution Slider', KT_THEME_LANG),
                'id' => $prefix . 'rev_slider',
                'default' => true,
                'type' => 'revSlider',
                'tab'  => 'header',
                'desc' => __('Select the Revolution Slider.', KT_THEME_LANG),
                'compare' => array($prefix . 'slideshow_source','=', 'revslider' ),
            ),
            array(
                'name' => __('Select Layer Slider', KT_THEME_LANG),
                'id' => $prefix . 'layerslider',
                'default' => true,
                'type' => 'layerslider',
                'tab'  => 'header',
                'desc' => __('Select the Layer Slider.', KT_THEME_LANG),
                'compare' => array($prefix . 'slideshow_source','=', 'layerslider' ),
            ),

            /*
            array(
                'name' => __('Main Navigation Menu', KT_THEME_LANG),
                'id'   => "{$prefix}header_main_menu",
                'type' => 'select',
                'options' => $menus_arr,
                'std'  => '',
                'tab'  => 'header',
            ),
            */

            // Page Header
            array(

                'name' => __( 'Page Header', KT_THEME_LANG ),
                'id' => $prefix . 'page_header',
                'desc' => __( "Show Page Header.", KT_THEME_LANG ),
                'type' => 'select',
                'options' => array(
                    -1    => __('Default', KT_THEME_LANG),
                    0		=> __('Hidden', KT_THEME_LANG),
                    1		=> __('Show', KT_THEME_LANG),
                ),
                'std'  => -1,
                'tab'  => 'page_header',
            ),
            array(
                'name' => __( 'Page Header Custom Text', KT_THEME_LANG ),
                'id' => $prefix . 'page_header_custom',
                'desc' => __( "Enter cstom Text for page header.", KT_THEME_LANG ),
                'type'  => 'text',
                'tab'  => 'page_header',
                'compare' => array($prefix . 'page_header','!=', '0' ),
            ),

            array(
                'name' => __( 'Page header subtitle', KT_THEME_LANG ),
                'id' => $prefix . 'page_header_subtitle',
                'desc' => __( "Enter subtitle for page.", KT_THEME_LANG ),
                'type'  => 'text',
                'tab'  => 'page_header',
                'compare' => array($prefix . 'page_header','!=', '0' ),
            ),

            array(
                'id'       => "{$prefix}page_header_align",
                'type'     => 'select',
                'name'    => __( 'Page Header align', KT_THEME_LANG ),
                'desc'     => __( 'Please select Page Header align', KT_THEME_LANG ),
                'options'  => array(
                    ''    => __('Default', KT_THEME_LANG),
                    'left' => __('Left', KT_THEME_LANG ),
                    'center' => __('Center', KT_THEME_LANG),
                    'right' => __('Right', KT_THEME_LANG)
                ),
                'std'  => '',
                'tab'  => 'page_header',
                'compare' => array($prefix . 'page_header','!=', '0' ),
            ),

            array(
                'name' => __('Page breadcrumb', KT_THEME_LANG),
                'id'   => "{$prefix}show_breadcrumb",
                'type' => 'select',
                'options' => array(
                    -1    => __('Default', KT_THEME_LANG),
                    0		=> __('Hidden', KT_THEME_LANG),
                    1		=> __('Show', KT_THEME_LANG),
                ),
                'std'  => -1,
                'desc' => __( "Show page breadcrumb.", KT_THEME_LANG ),
                'tab'  => 'page_header',
                'compare' => array($prefix . 'page_header','!=', '0' ),
            ),

            array(
                'name' => __('Page header top spacing', KT_THEME_LANG),
                'id' => $prefix . 'page_header_top',
                'desc' => __("(Example: 60px). Emtpy for use default", KT_THEME_LANG ),
                'type'  => 'text',
                'tab'  => 'page_header',
                'compare' => array($prefix . 'page_header','!=', '0' ),
            ),
            array(
                'name' => __('Page header bottom spacing', KT_THEME_LANG),
                'id' => $prefix . 'page_header_bottom',
                'desc' => __("(Example: 60px). Emtpy for use default", KT_THEME_LANG ),
                'type'  => 'text',
                'tab'  => 'page_header',
                'compare' => array($prefix . 'page_header','!=', '0' ),
            ),
            array(
                'name' => __( 'Typography title custom color', KT_THEME_LANG ),
                'id'   => "{$prefix}page_header_title_color",
                'type' => 'color',
                'tab'  => 'page_header',
                'desc' => __( "Choose custom color for title.", KT_THEME_LANG ),
                'compare' => array($prefix . 'page_header','!=', '0' ),
            ),
            array(
                'name' => __( 'Typography sub title custom color', KT_THEME_LANG ),
                'id'   => "{$prefix}page_header_subtitle_color",
                'type' => 'color',
                'tab'  => 'page_header',
                'desc' => __( "Choose custom color for sub title.", KT_THEME_LANG ),
                'compare' => array($prefix . 'page_header','!=', '0' ),
            ),
            array(
                'name' => __( 'Typography breadcrumbs custom color', KT_THEME_LANG ),
                'id'   => "{$prefix}page_header_breadcrumbs_color",
                'type' => 'color',
                'tab'  => 'page_header',
                'desc' => __( "Choose custom color for breadcrumbs.", KT_THEME_LANG ),
                'compare' => array($prefix . 'page_header','!=', '0' ),
            ),


            //Page layout
            array(
                'name' => __('Sidebar configuration', KT_THEME_LANG),
                'id' => $prefix . 'sidebar',
                'desc' => __("Choose the sidebar configuration for the detail page.<br/><b>Note: Cart and checkout, My account page always use no sidebars.</b>", KT_THEME_LANG),
                'type' => 'select',
                'options' => array(
                    'default' => __('Default', KT_THEME_LANG),
                    'full' => __('No sidebars', KT_THEME_LANG),
                    'left' => __('Left Sidebar', KT_THEME_LANG),
                    'right' => __('Right Sidebar', KT_THEME_LANG)
                ),
                'std' => 'default',
                'tab'  => 'page_layout',
            ),
            array(
                'name' => __('Left sidebar', KT_THEME_LANG),
                'id' => $prefix . 'left_sidebar',
                'default' => false,
                'type' => 'sidebars',
                'tab'  => 'page_layout',
                'desc' => __("Select your sidebar.", KT_THEME_LANG),
                'compare' => array($prefix . 'sidebar','=', 'left' ),
            ),
            array(
                'name' => __('Right sidebar', KT_THEME_LANG),
                'id' => $prefix . 'right_sidebar',
                'default' => false,
                'type' => 'sidebars',
                'tab'  => 'page_layout',
                'desc' => __("Select your sidebar.", KT_THEME_LANG),
                'compare' => array($prefix . 'sidebar','=', 'right' ),
            ),
            array(
                'name' => __('Page top spacing', KT_THEME_LANG),
                'id' => $prefix . 'page_top_spacing',
                'desc' => __("Enter your page top spacing (Example: 100px).", KT_THEME_LANG ),
                'type'  => 'text',
                'tab'  => 'page_layout',
            ),
            array(
                'name' => __('Page bottom spacing', KT_THEME_LANG),
                'id' => $prefix . 'page_bottom_spacing',
                'desc' => __("Enter your page bottom spacing (Example: 100px).", KT_THEME_LANG ),
                'type'  => 'text',
                'tab'  => 'page_layout',
            ),
            array(
                'name' => __('Extra page class', KT_THEME_LANG),
                'id' => $prefix . 'extra_page_class',
                'desc' => __('If you wish to add extra classes to the body class of the page (for custom css use), then please add the class(es) here.', KT_THEME_LANG ),
                'type'  => 'text',
                'tab'  => 'page_layout',
            ),

        )
    );


    return $meta_boxes;
}




