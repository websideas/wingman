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

    $menus_arr = array('' => esc_html__('Default', 'wingman'));
    foreach ( $menus as $menu ) {
        $menus_arr[$menu->term_id] = esc_html( $menu->name );
    }

    /**
     * For Post Audio
     *
     */

    $meta_boxes[] = array(
        'title'  => esc_html__('Audio Settings','wingman'),
        'pages'  => array( 'post' ),
        'show'   => array(
            'post_format' => array( 'Audio'),
        ),

        'fields' => array(
            array(
                'name' => esc_html__('Audio Type', 'wingman'),
                'id' => $prefix . 'audio_type',
                'type'     => 'select',
                'options'  => array(
                    '' => esc_html__('Select Option', 'wingman'),
                    'upload' => esc_html__('Upload', 'wingman'),
                    'soundcloud' => esc_html__('Soundcloud', 'wingman'),
                ),
            ),
            array(
                'name'             => esc_html__( 'Upload MP3 File', 'wingman' ),
                'id'               => "{$prefix}audio_mp3",
                'type'             => 'file_advanced',
                'max_file_uploads' => 1,
                'mime_type'        => 'audio', // Leave blank for all file types
                'visible' => array($prefix . 'audio_type','=', 'upload' ),
            ),
            array(
                'name' => esc_html__( 'Soundcloud', 'wingman' ),
                'desc' => esc_html__( 'Paste embed iframe or Wordpress shortcode.', 'wingman' ),
                'id'   => "{$prefix}audio_soundcloud",
                'type' => 'textarea',
                'cols' => 20,
                'rows' => 3,
                'visible' => array($prefix . 'audio_type','=', 'soundcloud' ),
            ),
        ),
    );

    /**
     * For Video
     *
     */

    $meta_boxes[] = array(
        'title'  => esc_html__('Video Settings','wingman'),
        'pages'  => array( 'post' ),
        'show'   => array(
            'post_format' => array( 'Video'),
        ),

        'fields' => array(
            array(
                'name' => esc_html__('Video Type', 'wingman'),
                'id' => $prefix . 'video_type',
                'type'     => 'select',
                'options'  => array(
                    '' => esc_html__('Select Option', 'wingman'),
                    'external' => esc_html__('External url', 'wingman'),
                ),
            ),
            array(
                'name' => esc_html__('Choose Video', 'wingman'),
                'id' => $prefix . 'choose_video',
                'type'     => 'select',
                'options'  => array(
                    'youtube' => esc_html__('Youtube', 'wingman'),
                    'vimeo' => esc_html__('Vimeo', 'wingman'),
                ),
                'visible' => array($prefix . 'video_type','=', 'external' ),
            ),
            array(
                'name' => esc_html__( 'Video id', 'wingman' ),
                'id' => $prefix . 'video_id',
                'desc' => sprintf( esc_html__( 'Enter id of video .Example: <br />- Link video youtube: https://www.youtube.com/watch?v=nPOO1Coe2DI id of video: nPOO1Coe2DI <br /> -Link vimeo: https://vimeo.com/70296428 id video: 70296428.', 'wingman' ) ),
                'type'  => 'text',
                'visible' => array($prefix . 'video_type','=', 'external' ),
            ),
        ),
    );

    /**
     * For Gallery
     *
     */

    $meta_boxes[] = array(
        'title'  => esc_html__('Gallery Settings','wingman'),
        'pages'  => array( 'post' ),
        'show'   => array(
            'post_format' => array( 'Gallery'),
        ),

        'fields' => array(
            array(
                'name' => esc_html__('Gallery Type', 'wingman'),
                'id' => $prefix . 'gallery_type',
                'type'     => 'select',
                'options'  => array(
                    '' => esc_html__('Default', 'wingman'),
                    'rev' => esc_html__('Revolution Slider', 'wingman'),
                    'layer' => esc_html__('Layer Slider', 'wingman')
                ),
            ),

            array(
                'name' => esc_html__('Select Revolution Slider', 'wingman'),
                'id' => $prefix . 'gallery_rev_slider',
                'default' => true,
                'type' => 'revSlider',
                'visible' => array($prefix . 'gallery_type','=', 'rev' ),
            ),
            array(
                'name' => esc_html__('Select Layer Slider', 'wingman'),
                'id' => $prefix . 'gallery_layerslider',
                'default' => true,
                'type' => 'layerslider',
                'visible' => array($prefix . 'gallery_type','=', 'layer' ),
            ),
            array(
                'name' => esc_html__( 'Gallery images', 'wingman' ),
                'id'  => "{$prefix}gallery_images",
                'type' => 'image_advanced',
                'desc' => esc_html__( "You can drag and drop for change order image", 'wingman' ),
                'visible' => array($prefix . 'gallery_type','=', '' ),
            ),
        ),
    );



    /**
     * For Link
     *
     */

    $meta_boxes[] = array(
        'title'  => esc_html__('Link Settings','wingman'),
        'pages'  => array( 'post' ),
        'show'   => array(
            'post_format' => array( 'Link'),
        ),
        'fields' => array(
            array(
                'name' => esc_html__( 'External URL', 'wingman' ),
                'id' => $prefix . 'external_url',
                'desc' => esc_html__( "Input your link in here", 'wingman' ),
                'type'  => 'text',
            ),

        ),
    );

    /**
     * For Quote
     *
     */

    $meta_boxes[] = array(
        'title'  => esc_html__('Quote Settings','wingman'),
        'pages'  => array( 'post' ),
        'show'   => array(
            'post_format' => array( 'Quote'),
        ),
        'fields' => array(
            array(
                'name' => esc_html__( 'Quote Content', 'wingman' ),
                'desc' => esc_html__( 'Please type the text for your quote here.', 'wingman' ),
                'id'   => "{$prefix}quote_content",
                'type' => 'textarea',
                'cols' => 20,
                'rows' => 3,
            ),
            array(
                'name' => esc_html__( 'Author', 'wingman' ),
                'id' => $prefix . 'quote_author',
                'desc' => esc_html__( "Please type the text for author quote here.", 'wingman' ),
                'type'  => 'text',
            ),

        ),
    );
    
    /**
     * For Testimonial
     *
     */

    $meta_boxes[] = array(
        'title'  => esc_html__('Testimonial Settings','wingman'),
        'pages'  => array( 'kt_testimonial' ),
        'fields' => array(
            array(
                'name' => esc_html__( 'Company Name / Job Title', 'wingman' ),
                'id' => $prefix . 'testimonial_company',
                'desc' => esc_html__( "Please type the text for Company Name / Job Title here.", 'wingman' ),
                'type'  => 'text',
            ),
            array(
                'name' => esc_html__( "URL to Author's Website", 'wingman' ),
                'id' => $prefix . 'testimonial_link',
                'desc' => esc_html__( "Please type the text for link here.", 'wingman' ),
                'type'  => 'text',
            ),
            array(
                'name' => esc_html__('Rate', 'wingman'),
                'id'   => "{$prefix}rate",
                'type' => 'select',
                'options' => array(
                    '0'    => esc_html__('Choose star', 'wingman'),
                    '1'   => esc_html__('1', 'wingman'),
                    '2'   => esc_html__('2', 'wingman'),
                    '3'   => esc_html__('3', 'wingman'),
                    '4'   => esc_html__('4', 'wingman'),
                    '5'   => esc_html__('5', 'wingman'),
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
                'name' => esc_html__('Show Post format', 'wingman'),
                'id'   => "{$prefix}post_format",
                'type' => 'select',
                'options' => array(
                    -1    => esc_html__('Default', 'wingman'),
                    0		=> esc_html__('Hidden', 'wingman'),
                    1		=> esc_html__('Show', 'wingman'),
                ),
                'std'  => -1
            ),
            array(
                'name' => esc_html__('Title and meta center', 'wingman'),
                'id'   => "{$prefix}title_and_meta_center",
                'type' => 'select',
                'options' => array(
                    -1    => esc_html__('Default', 'wingman'),
                    0		=> esc_html__('No', 'wingman'),
                    1		=> esc_html__('Yes', 'wingman'),
                ),
                'std'  => -1
            ),

            array(
                'type' => 'select',
                'name' => esc_html__('Post image size', 'wingman'),
                'desc' => esc_html__('Select the format position.', 'wingman'),
                'id'   => "{$prefix}blog_image_size",
                'options' => array_merge(array('' => esc_html__('Default', 'wingman')), $image_sizes),
                'std' => ''
            ),

            array(
                'name' => esc_html__('Meta info', 'wingman'),
                'id'   => "{$prefix}meta_info",
                'type' => 'select',
                'options' => array(
                    -1    => esc_html__('Default', 'wingman'),
                    0		=> esc_html__('Hidden', 'wingman'),
                    1		=> esc_html__('Show', 'wingman'),
                ),
                'std'  => -1
            ),
            array(
                'name' => esc_html__('Previous & next buttons', 'wingman'),
                'id'   => "{$prefix}prev_next",
                'type' => 'select',
                'options' => array(
                    -1    => esc_html__('Default', 'wingman'),
                    0		=> esc_html__('Hidden', 'wingman'),
                    1		=> esc_html__('Show', 'wingman'),
                ),
                'std'  => -1
            ),
            array(
                'name' => esc_html__('Author info', 'wingman'),
                'id'   => "{$prefix}author_info",
                'type' => 'select',
                'options' => array(
                    -1    => esc_html__('Default', 'wingman'),
                    0		=> esc_html__('Hidden', 'wingman'),
                    1		=> esc_html__('Show', 'wingman'),
                ),
                'std'  => -1
            ),
            array(
                'name' => esc_html__('Social sharing', 'wingman'),
                'id'   => "{$prefix}social_sharing",
                'type' => 'select',
                'options' => array(
                    -1    => esc_html__('Default', 'wingman'),
                    0		=> esc_html__('Hidden', 'wingman'),
                    1		=> esc_html__('Show', 'wingman'),
                ),
                'std'  => -1
            ),
            array(
                'name' => esc_html__('Related articles', 'wingman'),
                'id'   => "{$prefix}related_acticles",
                'type' => 'select',
                'options' => array(
                    -1    => esc_html__('Default', 'wingman'),
                    0		=> esc_html__('Hidden', 'wingman'),
                    1		=> esc_html__('Show', 'wingman'),
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
        'title'  => esc_html__('Team Settings','wingman'),
        'pages'  => array( 'kt_team' ),
        'fields' => array(
            array(
                'name' => esc_html__( 'Regency', 'wingman' ),
                'id' => $prefix . 'team_regency',
                'desc' => esc_html__( "Regency.", 'wingman' ),
                'type'  => 'text',
            ),
            array(
                'name' => esc_html__( 'Twitter', 'wingman' ),
                'id' => $prefix . 'team_twitter',
                'desc' => esc_html__( "Link Twitter.", 'wingman' ),
                'type'  => 'text',
            ),
            array(
                'name' => esc_html__( 'Facebook', 'wingman' ),
                'id' => $prefix . 'team_facebook',
                'desc' => esc_html__( "Link Facebook.", 'wingman' ),
                'type'  => 'text',
            ),
            array(
                'name' => esc_html__( 'Google+', 'wingman' ),
                'id' => $prefix . 'team_googleplus',
                'desc' => esc_html__( "Link Google+.", 'wingman' ),
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
                'name' => esc_html__( 'Link Client', 'wingman' ),
                'id' => $prefix . 'link_client',
                'desc' => esc_html__( "Link Client.", 'wingman' ),
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
                'label' => esc_html__( 'Header', 'wingman' ),
                'icon'  => 'fa fa-desktop',
            ),
            'page_header' => array(
                'label' => esc_html__( 'Page Header', 'wingman' ),
                'icon'  => 'fa fa-bars',
            ),
            'page_layout' => array(
                'label' => esc_html__( 'Page layout', 'wingman' ),
                'icon'  => 'fa fa-columns',
            )
        ),
        'fields' => array(


            //Header
            array(
                'name'    => esc_html__( 'Header position', 'wingman' ),
                'type'     => 'select',
                'id'       => $prefix.'header_position',
                'desc'     => esc_html__( "Please choose header position", 'wingman' ),
                'options'  => array(
                    'default' => esc_html__('Default', 'wingman'),
                    'transparent' => esc_html__('Transparent header', 'wingman'),
                    'below' => esc_html__('Below Slideshow', 'wingman'),
                ),
                'std'  => 'default',
                'tab'  => 'header',
            ),

            array(
                'name' => esc_html__('Select Your Slideshow Type', 'wingman'),
                'id' => $prefix . 'slideshow_source',
                'desc' => esc_html__("You can select the slideshow type using this option.", 'wingman'),
                'type' => 'select',
                'options' => array(
                    '' => esc_html__('Select Option', 'wingman'),
                    'revslider' => esc_html__('Revolution Slider', 'wingman'),
                    'layerslider' => esc_html__('Layer Slider', 'wingman'),
                ),
                'tab'  => 'header',
            ),
            array(
                'name' => esc_html__('Select Revolution Slider', 'wingman'),
                'id' => $prefix . 'rev_slider',
                'default' => true,
                'type' => 'revSlider',
                'tab'  => 'header',
                'desc' => esc_html__('Select the Revolution Slider.', 'wingman'),
                'visible' => array($prefix . 'slideshow_source','=', 'revslider' ),
            ),
            array(
                'name' => esc_html__('Select Layer Slider', 'wingman'),
                'id' => $prefix . 'layerslider',
                'default' => true,
                'type' => 'layerslider',
                'tab'  => 'header',
                'desc' => esc_html__('Select the Layer Slider.', 'wingman'),
                'visible' => array($prefix . 'slideshow_source','=', 'layerslider' ),
            ),

            /*
            array(
                'name' => esc_html__('Main Navigation Menu', 'wingman'),
                'id'   => "{$prefix}header_main_menu",
                'type' => 'select',
                'options' => $menus_arr,
                'std'  => '',
                'tab'  => 'header',
            ),
            */

            // Page Header
            array(

                'name' => esc_html__( 'Page Header', 'wingman' ),
                'id' => $prefix . 'page_header',
                'desc' => esc_html__( "Show Page Header.", 'wingman' ),
                'type' => 'select',
                'options' => array(
                    -1    => esc_html__('Default', 'wingman'),
                    0		=> esc_html__('Hidden', 'wingman'),
                    1		=> esc_html__('Show', 'wingman'),
                ),
                'std'  => -1,
                'tab'  => 'page_header',
            ),
            array(
                'name' => esc_html__( 'Page Header Custom Text', 'wingman' ),
                'id' => $prefix . 'page_header_custom',
                'desc' => esc_html__( "Enter cstom Text for page header.", 'wingman' ),
                'type'  => 'text',
                'tab'  => 'page_header',
                'visible' => array($prefix . 'page_header','!=', '0' ),
            ),

            array(
                'name' => esc_html__( 'Page header subtitle', 'wingman' ),
                'id' => $prefix . 'page_header_subtitle',
                'desc' => esc_html__( "Enter subtitle for page.", 'wingman' ),
                'type'  => 'text',
                'tab'  => 'page_header',
                'visible' => array($prefix . 'page_header','!=', '0' ),
            ),

            array(
                'id'       => "{$prefix}page_header_align",
                'type'     => 'select',
                'name'    => esc_html__( 'Page Header align', 'wingman' ),
                'desc'     => esc_html__( 'Please select Page Header align', 'wingman' ),
                'options'  => array(
                    ''    => esc_html__('Default', 'wingman'),
                    'left' => esc_html__('Left', 'wingman' ),
                    'center' => esc_html__('Center', 'wingman'),
                    'right' => esc_html__('Right', 'wingman')
                ),
                'std'  => '',
                'tab'  => 'page_header',
                'visible' => array($prefix . 'page_header','!=', '0' ),
            ),

            array(
                'name' => esc_html__('Page breadcrumb', 'wingman'),
                'id'   => "{$prefix}show_breadcrumb",
                'type' => 'select',
                'options' => array(
                    -1    => esc_html__('Default', 'wingman'),
                    0		=> esc_html__('Hidden', 'wingman'),
                    1		=> esc_html__('Show', 'wingman'),
                ),
                'std'  => -1,
                'desc' => esc_html__( "Show page breadcrumb.", 'wingman' ),
                'tab'  => 'page_header',
                'visible' => array($prefix . 'page_header','!=', '0' ),
            ),

            array(
                'name' => esc_html__('Page header top spacing', 'wingman'),
                'id' => $prefix . 'page_header_top',
                'desc' => esc_html__("(Example: 60px). Emtpy for use default", 'wingman' ),
                'type'  => 'text',
                'tab'  => 'page_header',
                'visible' => array($prefix . 'page_header','!=', '0' ),
            ),
            array(
                'name' => esc_html__('Page header bottom spacing', 'wingman'),
                'id' => $prefix . 'page_header_bottom',
                'desc' => esc_html__("(Example: 60px). Emtpy for use default", 'wingman' ),
                'type'  => 'text',
                'tab'  => 'page_header',
                'visible' => array($prefix . 'page_header','!=', '0' ),
            ),
            array(
                'name' => esc_html__( 'Typography title custom color', 'wingman' ),
                'id'   => "{$prefix}page_header_title_color",
                'type' => 'color',
                'tab'  => 'page_header',
                'desc' => esc_html__( "Choose custom color for title.", 'wingman' ),
                'visible' => array($prefix . 'page_header','!=', '0' ),
            ),
            array(
                'name' => esc_html__( 'Typography sub title custom color', 'wingman' ),
                'id'   => "{$prefix}page_header_subtitle_color",
                'type' => 'color',
                'tab'  => 'page_header',
                'desc' => esc_html__( "Choose custom color for sub title.", 'wingman' ),
                'visible' => array($prefix . 'page_header','!=', '0' ),
            ),
            array(
                'name' => esc_html__( 'Typography breadcrumbs custom color', 'wingman' ),
                'id'   => "{$prefix}page_header_breadcrumbs_color",
                'type' => 'color',
                'tab'  => 'page_header',
                'desc' => esc_html__( "Choose custom color for breadcrumbs.", 'wingman' ),
                'visible' => array($prefix . 'page_header','!=', '0' ),
            ),


            //Page layout
            array(
                'name' => esc_html__('Sidebar configuration', 'wingman'),
                'id' => $prefix . 'sidebar',
                'desc' => esc_html__("Choose the sidebar configuration for the detail page.<br/><b>Note: Cart and checkout, My account page always use no sidebars.</b>", 'wingman'),
                'type' => 'select',
                'options' => array(
                    'default' => esc_html__('Default', 'wingman'),
                    'full' => esc_html__('No sidebars', 'wingman'),
                    'left' => esc_html__('Left Sidebar', 'wingman'),
                    'right' => esc_html__('Right Sidebar', 'wingman')
                ),
                'std' => 'default',
                'tab'  => 'page_layout',
            ),
            array(
                'name' => esc_html__('Left sidebar', 'wingman'),
                'id' => $prefix . 'left_sidebar',
                'default' => false,
                'type' => 'sidebars',
                'tab'  => 'page_layout',
                'desc' => esc_html__("Select your sidebar.", 'wingman'),
                'visible' => array($prefix . 'sidebar','=', 'left' ),
            ),
            array(
                'name' => esc_html__('Right sidebar', 'wingman'),
                'id' => $prefix . 'right_sidebar',
                'default' => false,
                'type' => 'sidebars',
                'tab'  => 'page_layout',
                'desc' => esc_html__("Select your sidebar.", 'wingman'),
                'visible' => array($prefix . 'sidebar','=', 'right' ),
            ),
            array(
                'name' => esc_html__('Page top spacing', 'wingman'),
                'id' => $prefix . 'page_top_spacing',
                'desc' => esc_html__("Enter your page top spacing (Example: 100px).", 'wingman' ),
                'type'  => 'text',
                'tab'  => 'page_layout',
            ),
            array(
                'name' => esc_html__('Page bottom spacing', 'wingman'),
                'id' => $prefix . 'page_bottom_spacing',
                'desc' => esc_html__("Enter your page bottom spacing (Example: 100px).", 'wingman' ),
                'type'  => 'text',
                'tab'  => 'page_layout',
            ),
            array(
                'name' => esc_html__('Extra page class', 'wingman'),
                'id' => $prefix . 'extra_page_class',
                'desc' => esc_html__('If you wish to add extra classes to the body class of the page (for custom css use), then please add the class(es) here.', 'wingman' ),
                'type'  => 'text',
                'tab'  => 'page_layout',
            ),

        )
    );


    return $meta_boxes;
}




