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

    $menus_arr = array('' => __('Default', 'wingman'));
    foreach ( $menus as $menu ) {
        $menus_arr[$menu->term_id] = esc_html( $menu->name );
    }

    /**
     * For Post Audio
     *
     */

    $meta_boxes[] = array(
        'title'  => __('Audio Settings','wingman'),
        'pages'  => array( 'post' ),
        'show'   => array(
            'post_format' => array( 'Audio'),
        ),

        'fields' => array(
            array(
                'name' => __('Audio Type', 'wingman'),
                'id' => $prefix . 'audio_type',
                'type'     => 'select',
                'options'  => array(
                    '' => __('Select Option', 'wingman'),
                    'upload' => __('Upload', 'wingman'),
                    'soundcloud' => __('Soundcloud', 'wingman'),
                ),
            ),
            array(
                'name'             => __( 'Upload MP3 File', 'wingman' ),
                'id'               => "{$prefix}audio_mp3",
                'type'             => 'file_advanced',
                'max_file_uploads' => 1,
                'mime_type'        => 'audio', // Leave blank for all file types
                'compare' => array($prefix . 'audio_type','=', 'upload' ),
            ),
            array(
                'name' => __( 'Soundcloud', 'wingman' ),
                'desc' => __( 'Paste embed iframe or Wordpress shortcode.', 'wingman' ),
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
        'title'  => __('Video Settings','wingman'),
        'pages'  => array( 'post' ),
        'show'   => array(
            'post_format' => array( 'Video'),
        ),

        'fields' => array(
            array(
                'name' => __('Video Type', 'wingman'),
                'id' => $prefix . 'video_type',
                'type'     => 'select',
                'options'  => array(
                    '' => __('Select Option', 'wingman'),
                    'external' => __('External url', 'wingman'),
                ),
            ),
            array(
                'name' => __('Choose Video', 'wingman'),
                'id' => $prefix . 'choose_video',
                'type'     => 'select',
                'options'  => array(
                    'youtube' => __('Youtube', 'wingman'),
                    'vimeo' => __('Vimeo', 'wingman'),
                ),
                'compare' => array($prefix . 'video_type','=', 'external' ),
            ),
            array(
                'name' => __( 'Video id', 'wingman' ),
                'id' => $prefix . 'video_id',
                'desc' => sprintf( __( 'Enter id of video .Example: <br />- Link video youtube: https://www.youtube.com/watch?v=nPOO1Coe2DI id of video: nPOO1Coe2DI <br /> -Link vimeo: https://vimeo.com/70296428 id video: 70296428.', 'wingman' ) ),
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
        'title'  => __('Gallery Settings','wingman'),
        'pages'  => array( 'post' ),
        'show'   => array(
            'post_format' => array( 'Gallery'),
        ),

        'fields' => array(
            array(
                'name' => __('Gallery Type', 'wingman'),
                'id' => $prefix . 'gallery_type',
                'type'     => 'select',
                'options'  => array(
                    '' => __('Default', 'wingman'),
                    'rev' => __('Revolution Slider', 'wingman'),
                    'layer' => __('Layer Slider', 'wingman')
                ),
            ),

            array(
                'name' => __('Select Revolution Slider', 'wingman'),
                'id' => $prefix . 'gallery_rev_slider',
                'default' => true,
                'type' => 'revSlider',
                'compare' => array($prefix . 'gallery_type','=', 'rev' ),
            ),
            array(
                'name' => __('Select Layer Slider', 'wingman'),
                'id' => $prefix . 'gallery_layerslider',
                'default' => true,
                'type' => 'layerslider',
                'compare' => array($prefix . 'gallery_type','=', 'layer' ),
            ),
            array(
                'name' => __( 'Gallery images', 'your-prefix' ),
                'id'  => "{$prefix}gallery_images",
                'type' => 'image_advanced',
                'desc' => __( "You can drag and drop for change order image", 'wingman' ),
                'compare' => array($prefix . 'gallery_type','=', '' ),
            ),
        ),
    );



    /**
     * For Link
     *
     */

    $meta_boxes[] = array(
        'title'  => __('Link Settings','wingman'),
        'pages'  => array( 'post' ),
        'show'   => array(
            'post_format' => array( 'Link'),
        ),
        'fields' => array(
            array(
                'name' => __( 'External URL', 'wingman' ),
                'id' => $prefix . 'external_url',
                'desc' => __( "Input your link in here", 'wingman' ),
                'type'  => 'text',
            ),

        ),
    );

    /**
     * For Quote
     *
     */

    $meta_boxes[] = array(
        'title'  => __('Quote Settings','wingman'),
        'pages'  => array( 'post' ),
        'show'   => array(
            'post_format' => array( 'Quote'),
        ),
        'fields' => array(
            array(
                'name' => __( 'Quote Content', 'wingman' ),
                'desc' => __( 'Please type the text for your quote here.', 'wingman' ),
                'id'   => "{$prefix}quote_content",
                'type' => 'textarea',
                'cols' => 20,
                'rows' => 3,
            ),
            array(
                'name' => __( 'Author', 'wingman' ),
                'id' => $prefix . 'quote_author',
                'desc' => __( "Please type the text for author quote here.", 'wingman' ),
                'type'  => 'text',
            ),

        ),
    );
    
    /**
     * For Testimonial
     *
     */

    $meta_boxes[] = array(
        'title'  => __('Testimonial Settings','wingman'),
        'pages'  => array( 'kt_testimonial' ),
        'fields' => array(
            array(
                'name' => __( 'Company Name / Job Title', 'wingman' ),
                'id' => $prefix . 'testimonial_company',
                'desc' => __( "Please type the text for Company Name / Job Title here.", 'wingman' ),
                'type'  => 'text',
            ),
            array(
                'name' => __( "URL to Author's Website", 'wingman' ),
                'id' => $prefix . 'testimonial_link',
                'desc' => __( "Please type the text for link here.", 'wingman' ),
                'type'  => 'text',
            ),
            array(
                'name' => __('Rate', 'wingman'),
                'id'   => "{$prefix}rate",
                'type' => 'select',
                'options' => array(
                    '0'    => __('Choose star', 'wingman'),
                    '1'   => __('1', 'wingman'),
                    '2'   => __('2', 'wingman'),
                    '3'   => __('3', 'wingman'),
                    '4'   => __('4', 'wingman'),
                    '5'   => __('5', 'wingman'),
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
                'name' => __('Show Post format', 'wingman'),
                'id'   => "{$prefix}post_format",
                'type' => 'select',
                'options' => array(
                    -1    => __('Default', 'wingman'),
                    0		=> __('Hidden', 'wingman'),
                    1		=> __('Show', 'wingman'),
                ),
                'std'  => -1
            ),
            array(
                'name' => __('Title and meta center', 'wingman'),
                'id'   => "{$prefix}title_and_meta_center",
                'type' => 'select',
                'options' => array(
                    -1    => __('Default', 'wingman'),
                    0		=> __('No', 'wingman'),
                    1		=> __('Yes', 'wingman'),
                ),
                'std'  => -1
            ),

            array(
                'type' => 'select',
                'name' => __('Post image size', 'wingman'),
                'desc' => __('Select the format position.', 'wingman'),
                'id'   => "{$prefix}blog_image_size",
                'options' => array_merge(array('' => __('Default', 'wingman')), $image_sizes),
                'std' => ''
            ),

            array(
                'name' => __('Meta info', 'wingman'),
                'id'   => "{$prefix}meta_info",
                'type' => 'select',
                'options' => array(
                    -1    => __('Default', 'wingman'),
                    0		=> __('Hidden', 'wingman'),
                    1		=> __('Show', 'wingman'),
                ),
                'std'  => -1
            ),
            array(
                'name' => __('Previous & next buttons', 'wingman'),
                'id'   => "{$prefix}prev_next",
                'type' => 'select',
                'options' => array(
                    -1    => __('Default', 'wingman'),
                    0		=> __('Hidden', 'wingman'),
                    1		=> __('Show', 'wingman'),
                ),
                'std'  => -1
            ),
            array(
                'name' => __('Author info', 'wingman'),
                'id'   => "{$prefix}author_info",
                'type' => 'select',
                'options' => array(
                    -1    => __('Default', 'wingman'),
                    0		=> __('Hidden', 'wingman'),
                    1		=> __('Show', 'wingman'),
                ),
                'std'  => -1
            ),
            array(
                'name' => __('Social sharing', 'wingman'),
                'id'   => "{$prefix}social_sharing",
                'type' => 'select',
                'options' => array(
                    -1    => __('Default', 'wingman'),
                    0		=> __('Hidden', 'wingman'),
                    1		=> __('Show', 'wingman'),
                ),
                'std'  => -1
            ),
            array(
                'name' => __('Related articles', 'wingman'),
                'id'   => "{$prefix}related_acticles",
                'type' => 'select',
                'options' => array(
                    -1    => __('Default', 'wingman'),
                    0		=> __('Hidden', 'wingman'),
                    1		=> __('Show', 'wingman'),
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
        'title'  => __('Team Settings','wingman'),
        'pages'  => array( 'kt_team' ),
        'fields' => array(
            array(
                'name' => __( 'Regency', 'wingman' ),
                'id' => $prefix . 'team_regency',
                'desc' => __( "Regency.", 'wingman' ),
                'type'  => 'text',
            ),
            array(
                'name' => __( 'Twitter', 'wingman' ),
                'id' => $prefix . 'team_twitter',
                'desc' => __( "Link Twitter.", 'wingman' ),
                'type'  => 'text',
            ),
            array(
                'name' => __( 'Facebook', 'wingman' ),
                'id' => $prefix . 'team_facebook',
                'desc' => __( "Link Facebook.", 'wingman' ),
                'type'  => 'text',
            ),
            array(
                'name' => __( 'Google+', 'wingman' ),
                'id' => $prefix . 'team_googleplus',
                'desc' => __( "Link Google+.", 'wingman' ),
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
                'name' => __( 'Link Client', 'wingman' ),
                'id' => $prefix . 'link_client',
                'desc' => __( "Link Client.", 'wingman' ),
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
                'label' => __( 'Header', 'wingman' ),
                'icon'  => 'fa fa-desktop',
            ),
            'page_header' => array(
                'label' => __( 'Page Header', 'wingman' ),
                'icon'  => 'fa fa-bars',
            ),
            'page_layout' => array(
                'label' => __( 'Page layout', 'wingman' ),
                'icon'  => 'fa fa-columns',
            )
        ),
        'fields' => array(


            //Header
            array(
                'name'    => __( 'Header position', 'wingman' ),
                'type'     => 'select',
                'id'       => $prefix.'header_position',
                'desc'     => __( "Please choose header position", 'wingman' ),
                'options'  => array(
                    'default' => __('Default', 'wingman'),
                    'transparent' => __('Transparent header', 'wingman'),
                    'below' => __('Below Slideshow', 'wingman'),
                ),
                'std'  => 'default',
                'tab'  => 'header',
            ),

            array(
                'name' => __('Select Your Slideshow Type', 'wingman'),
                'id' => $prefix . 'slideshow_source',
                'desc' => __("You can select the slideshow type using this option.", 'wingman'),
                'type' => 'select',
                'options' => array(
                    '' => __('Select Option', 'wingman'),
                    'revslider' => __('Revolution Slider', 'wingman'),
                    'layerslider' => __('Layer Slider', 'wingman'),
                ),
                'tab'  => 'header',
            ),
            array(
                'name' => __('Select Revolution Slider', 'wingman'),
                'id' => $prefix . 'rev_slider',
                'default' => true,
                'type' => 'revSlider',
                'tab'  => 'header',
                'desc' => __('Select the Revolution Slider.', 'wingman'),
                'compare' => array($prefix . 'slideshow_source','=', 'revslider' ),
            ),
            array(
                'name' => __('Select Layer Slider', 'wingman'),
                'id' => $prefix . 'layerslider',
                'default' => true,
                'type' => 'layerslider',
                'tab'  => 'header',
                'desc' => __('Select the Layer Slider.', 'wingman'),
                'compare' => array($prefix . 'slideshow_source','=', 'layerslider' ),
            ),

            /*
            array(
                'name' => __('Main Navigation Menu', 'wingman'),
                'id'   => "{$prefix}header_main_menu",
                'type' => 'select',
                'options' => $menus_arr,
                'std'  => '',
                'tab'  => 'header',
            ),
            */

            // Page Header
            array(

                'name' => __( 'Page Header', 'wingman' ),
                'id' => $prefix . 'page_header',
                'desc' => __( "Show Page Header.", 'wingman' ),
                'type' => 'select',
                'options' => array(
                    -1    => __('Default', 'wingman'),
                    0		=> __('Hidden', 'wingman'),
                    1		=> __('Show', 'wingman'),
                ),
                'std'  => -1,
                'tab'  => 'page_header',
            ),
            array(
                'name' => __( 'Page Header Custom Text', 'wingman' ),
                'id' => $prefix . 'page_header_custom',
                'desc' => __( "Enter cstom Text for page header.", 'wingman' ),
                'type'  => 'text',
                'tab'  => 'page_header',
                'compare' => array($prefix . 'page_header','!=', '0' ),
            ),

            array(
                'name' => __( 'Page header subtitle', 'wingman' ),
                'id' => $prefix . 'page_header_subtitle',
                'desc' => __( "Enter subtitle for page.", 'wingman' ),
                'type'  => 'text',
                'tab'  => 'page_header',
                'compare' => array($prefix . 'page_header','!=', '0' ),
            ),

            array(
                'id'       => "{$prefix}page_header_align",
                'type'     => 'select',
                'name'    => __( 'Page Header align', 'wingman' ),
                'desc'     => __( 'Please select Page Header align', 'wingman' ),
                'options'  => array(
                    ''    => __('Default', 'wingman'),
                    'left' => __('Left', 'wingman' ),
                    'center' => __('Center', 'wingman'),
                    'right' => __('Right', 'wingman')
                ),
                'std'  => '',
                'tab'  => 'page_header',
                'compare' => array($prefix . 'page_header','!=', '0' ),
            ),

            array(
                'name' => __('Page breadcrumb', 'wingman'),
                'id'   => "{$prefix}show_breadcrumb",
                'type' => 'select',
                'options' => array(
                    -1    => __('Default', 'wingman'),
                    0		=> __('Hidden', 'wingman'),
                    1		=> __('Show', 'wingman'),
                ),
                'std'  => -1,
                'desc' => __( "Show page breadcrumb.", 'wingman' ),
                'tab'  => 'page_header',
                'compare' => array($prefix . 'page_header','!=', '0' ),
            ),

            array(
                'name' => __('Page header top spacing', 'wingman'),
                'id' => $prefix . 'page_header_top',
                'desc' => __("(Example: 60px). Emtpy for use default", 'wingman' ),
                'type'  => 'text',
                'tab'  => 'page_header',
                'compare' => array($prefix . 'page_header','!=', '0' ),
            ),
            array(
                'name' => __('Page header bottom spacing', 'wingman'),
                'id' => $prefix . 'page_header_bottom',
                'desc' => __("(Example: 60px). Emtpy for use default", 'wingman' ),
                'type'  => 'text',
                'tab'  => 'page_header',
                'compare' => array($prefix . 'page_header','!=', '0' ),
            ),
            array(
                'name' => __( 'Typography title custom color', 'wingman' ),
                'id'   => "{$prefix}page_header_title_color",
                'type' => 'color',
                'tab'  => 'page_header',
                'desc' => __( "Choose custom color for title.", 'wingman' ),
                'compare' => array($prefix . 'page_header','!=', '0' ),
            ),
            array(
                'name' => __( 'Typography sub title custom color', 'wingman' ),
                'id'   => "{$prefix}page_header_subtitle_color",
                'type' => 'color',
                'tab'  => 'page_header',
                'desc' => __( "Choose custom color for sub title.", 'wingman' ),
                'compare' => array($prefix . 'page_header','!=', '0' ),
            ),
            array(
                'name' => __( 'Typography breadcrumbs custom color', 'wingman' ),
                'id'   => "{$prefix}page_header_breadcrumbs_color",
                'type' => 'color',
                'tab'  => 'page_header',
                'desc' => __( "Choose custom color for breadcrumbs.", 'wingman' ),
                'compare' => array($prefix . 'page_header','!=', '0' ),
            ),


            //Page layout
            array(
                'name' => __('Sidebar configuration', 'wingman'),
                'id' => $prefix . 'sidebar',
                'desc' => __("Choose the sidebar configuration for the detail page.<br/><b>Note: Cart and checkout, My account page always use no sidebars.</b>", 'wingman'),
                'type' => 'select',
                'options' => array(
                    'default' => __('Default', 'wingman'),
                    'full' => __('No sidebars', 'wingman'),
                    'left' => __('Left Sidebar', 'wingman'),
                    'right' => __('Right Sidebar', 'wingman')
                ),
                'std' => 'default',
                'tab'  => 'page_layout',
            ),
            array(
                'name' => __('Left sidebar', 'wingman'),
                'id' => $prefix . 'left_sidebar',
                'default' => false,
                'type' => 'sidebars',
                'tab'  => 'page_layout',
                'desc' => __("Select your sidebar.", 'wingman'),
                'compare' => array($prefix . 'sidebar','=', 'left' ),
            ),
            array(
                'name' => __('Right sidebar', 'wingman'),
                'id' => $prefix . 'right_sidebar',
                'default' => false,
                'type' => 'sidebars',
                'tab'  => 'page_layout',
                'desc' => __("Select your sidebar.", 'wingman'),
                'compare' => array($prefix . 'sidebar','=', 'right' ),
            ),
            array(
                'name' => __('Page top spacing', 'wingman'),
                'id' => $prefix . 'page_top_spacing',
                'desc' => __("Enter your page top spacing (Example: 100px).", 'wingman' ),
                'type'  => 'text',
                'tab'  => 'page_layout',
            ),
            array(
                'name' => __('Page bottom spacing', 'wingman'),
                'id' => $prefix . 'page_bottom_spacing',
                'desc' => __("Enter your page bottom spacing (Example: 100px).", 'wingman' ),
                'type'  => 'text',
                'tab'  => 'page_layout',
            ),
            array(
                'name' => __('Extra page class', 'wingman'),
                'id' => $prefix . 'extra_page_class',
                'desc' => __('If you wish to add extra classes to the body class of the page (for custom css use), then please add the class(es) here.', 'wingman' ),
                'type'  => 'text',
                'tab'  => 'page_layout',
            ),

        )
    );


    return $meta_boxes;
}




