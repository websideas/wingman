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

    $menus_arr = array('' => __('Default', THEME_LANG));
    foreach ( $menus as $menu ) {
        $menus_arr[$menu->term_id] = esc_html( $menu->name );
    }

    /**
     * For Post Audio
     *
     */

    $meta_boxes[] = array(
        'title'  => __('Audio Settings',THEME_LANG),
        'pages'  => array( 'post' ),
        'show'   => array(
            'post_format' => array( 'Audio'),
        ),

        'fields' => array(
            array(
                'name' => __('Audio Type', THEME_LANG),
                'id' => $prefix . 'audio_type',
                'type'     => 'select',
                'options'  => array(
                    '' => __('Select Option', THEME_LANG),
                    'upload' => __('Upload', THEME_LANG),
                    'soundcloud' => __('Soundcloud', THEME_LANG),
                ),
            ),
            array(
                'name'             => __( 'Upload MP3 File', THEME_LANG ),
                'id'               => "{$prefix}audio_mp3",
                'type'             => 'file_advanced',
                'max_file_uploads' => 1,
                'mime_type'        => 'audio', // Leave blank for all file types
                'required' => array($prefix . 'audio_type','=', 'upload' ),
            ),
            array(
                'name' => __( 'Soundcloud', THEME_LANG ),
                'desc' => __( 'Paste embed iframe or Wordpress shortcode.', THEME_LANG ),
                'id'   => "{$prefix}audio_soundcloud",
                'type' => 'textarea',
                'cols' => 20,
                'rows' => 3,
                'required' => array($prefix . 'audio_type','=', 'soundcloud' ),
            ),
        ),
    );

    /**
     * For Video
     *
     */

    $meta_boxes[] = array(
        'title'  => __('Video Settings',THEME_LANG),
        'pages'  => array( 'post' ),
        'show'   => array(
            'post_format' => array( 'Video'),
        ),

        'fields' => array(
            array(
                'name' => __('Video Type', THEME_LANG),
                'id' => $prefix . 'video_type',
                'type'     => 'select',
                'options'  => array(
                    '' => __('Select Option', THEME_LANG),
                    'external' => __('External url', THEME_LANG),
                ),
            ),
            array(
                'name' => __('Choose Video', THEME_LANG),
                'id' => $prefix . 'choose_video',
                'type'     => 'select',
                'options'  => array(
                    'youtube' => __('Youtube', THEME_LANG),
                    'vimeo' => __('Vimeo', THEME_LANG),
                ),
                'required' => array($prefix . 'video_type','=', 'external' ),
            ),
            array(
                'name' => __( 'Video id', THEME_LANG ),
                'id' => $prefix . 'video_id',
                'desc' => sprintf( __( 'Enter id of video .Example: <br />- Link video youtube: https://www.youtube.com/watch?v=nPOO1Coe2DI id of video: nPOO1Coe2DI <br /> -Link vimeo: https://vimeo.com/70296428 id video: 70296428.', THEME_LANG ) ),
                'type'  => 'text',
                'required' => array($prefix . 'video_type','=', 'external' ),
            ),
        ),
    );

    /**
     * For Gallery
     *
     */

    $meta_boxes[] = array(
        'title'  => __('Gallery Settings',THEME_LANG),
        'pages'  => array( 'post' ),
        'show'   => array(
            'post_format' => array( 'Gallery'),
        ),

        'fields' => array(
            array(
                'name' => __('Gallery Type', THEME_LANG),
                'id' => $prefix . 'gallery_type',
                'type'     => 'select',
                'options'  => array(
                    '' => __('Default', THEME_LANG),
                    'rev' => __('Revolution Slider', THEME_LANG),
                    'layer' => __('Layer Slider', THEME_LANG)
                ),
            ),

            array(
                'name' => __('Select Revolution Slider', THEME_LANG),
                'id' => $prefix . 'gallery_rev_slider',
                'default' => true,
                'type' => 'revSlider',
                'required' => array($prefix . 'gallery_type','=', 'rev' ),
            ),
            array(
                'name' => __('Select Layer Slider', THEME_LANG),
                'id' => $prefix . 'gallery_layerslider',
                'default' => true,
                'type' => 'layerslider',
                'required' => array($prefix . 'gallery_type','=', 'layer' ),
            ),
            array(
                'name' => __( 'Gallery images', 'your-prefix' ),
                'id'  => "{$prefix}gallery_images",
                'type' => 'image_advanced',
                'desc' => __( "You can drag and drop for change order image", THEME_LANG ),
                'required' => array($prefix . 'gallery_type','=', '' ),
            ),
        ),
    );



    /**
     * For Link
     *
     */

    $meta_boxes[] = array(
        'title'  => __('Link Settings',THEME_LANG),
        'pages'  => array( 'post' ),
        'show'   => array(
            'post_format' => array( 'Link'),
        ),

        'fields' => array(
            array(
                'name' => __( 'External URL', THEME_LANG ),
                'id' => $prefix . 'external_url',
                'desc' => __( "Input your link in here", THEME_LANG ),
                'type'  => 'text',
            ),

        ),
    );

    /**
     * For Quote
     *
     */

    $meta_boxes[] = array(
        'title'  => __('Quote Settings',THEME_LANG),
        'pages'  => array( 'post' ),
        'show'   => array(
            'post_format' => array( 'Quote'),
        ),
        'fields' => array(
            array(
                'name' => __( 'Quote Content', THEME_LANG ),
                'desc' => __( 'Please type the text for your quote here.', THEME_LANG ),
                'id'   => "{$prefix}quote_content",
                'type' => 'textarea',
                'cols' => 20,
                'rows' => 3,
            ),
            array(
                'name' => __( 'Author', THEME_LANG ),
                'id' => $prefix . 'quote_author',
                'desc' => __( "Please type the text for author quote here.", THEME_LANG ),
                'type'  => 'text',
            ),

        ),
    );
    
    /**
     * For Testimonial
     *
     */

    $meta_boxes[] = array(
        'title'  => __('Testimonial Settings',THEME_LANG),
        'pages'  => array( 'kt_testimonial' ),
        'fields' => array(
            array(
                'name' => __( 'Company Name / Job Title', THEME_LANG ),
                'id' => $prefix . 'testimonial_company',
                'desc' => __( "Please type the text for Company Name / Job Title here.", THEME_LANG ),
                'type'  => 'text',
            ),
            array(
                'name' => __( "URL to Author's Website", THEME_LANG ),
                'id' => $prefix . 'testimonial_link',
                'desc' => __( "Please type the text for link here.", THEME_LANG ),
                'type'  => 'text',
            ),
            array(
                'name' => __('Rate', THEME_LANG),
                'id'   => "{$prefix}rate",
                'type' => 'select',
                'options' => array(
                    '0'    => __('Choose star', THEME_LANG),
                    '1'   => __('1', THEME_LANG),
                    '2'   => __('2', THEME_LANG),
                    '3'   => __('3', THEME_LANG),
                    '4'   => __('4', THEME_LANG),
                    '5'   => __('5', THEME_LANG),
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
                'name' => __('Show Post format', THEME_LANG),
                'id'   => "{$prefix}post_format",
                'type' => 'select',
                'options' => array(
                    -1    => __('Default', THEME_LANG),
                    0		=> __('Hidden', THEME_LANG),
                    1		=> __('Show', THEME_LANG),
                ),
                'std'  => -1
            ),
            array(
                'name' => __('Title and meta center', THEME_LANG),
                'id'   => "{$prefix}title_and_meta_center",
                'type' => 'select',
                'options' => array(
                    -1    => __('Default', THEME_LANG),
                    0		=> __('No', THEME_LANG),
                    1		=> __('Yes', THEME_LANG),
                ),
                'std'  => -1
            ),
            array(
                'type' => 'select',
                'name' => __('Post layouts', THEME_LANG),
                'desc' => __('Select the your post layout.', THEME_LANG),
                'id'   => "{$prefix}blog_post_layout",
                'options' => array(
                    ''    => __('Default', THEME_LANG),
                    '1' => __( 'Layout 1', THEME_LANG ),
                    '2' => __( 'layout 2', THEME_LANG ),
                ),
                'std' => ''
            ),

            array(
                'type' => 'select',
                'name' => __('Post image size', THEME_LANG),
                'desc' => __('Select the format position.', THEME_LANG),
                'id'   => "{$prefix}blog_image_size",
                'options' => array_merge(array('' => __('Default', THEME_LANG)), $image_sizes),
                'std' => ''
            ),

            array(
                'name' => __('Meta info', THEME_LANG),
                'id'   => "{$prefix}meta_info",
                'type' => 'select',
                'options' => array(
                    -1    => __('Default', THEME_LANG),
                    0		=> __('Hidden', THEME_LANG),
                    1		=> __('Show', THEME_LANG),
                ),
                'std'  => -1
            ),
            array(
                'name' => __('Previous & next buttons', THEME_LANG),
                'id'   => "{$prefix}prev_next",
                'type' => 'select',
                'options' => array(
                    -1    => __('Default', THEME_LANG),
                    0		=> __('Hidden', THEME_LANG),
                    1		=> __('Show', THEME_LANG),
                ),
                'std'  => -1
            ),
            array(
                'name' => __('Author info', THEME_LANG),
                'id'   => "{$prefix}author_info",
                'type' => 'select',
                'options' => array(
                    -1    => __('Default', THEME_LANG),
                    0		=> __('Hidden', THEME_LANG),
                    1		=> __('Show', THEME_LANG),
                ),
                'std'  => -1
            ),
            array(
                'name' => __('Social sharing', THEME_LANG),
                'id'   => "{$prefix}social_sharing",
                'type' => 'select',
                'options' => array(
                    -1    => __('Default', THEME_LANG),
                    0		=> __('Hidden', THEME_LANG),
                    1		=> __('Show', THEME_LANG),
                ),
                'std'  => -1
            ),
            array(
                'name' => __('Related articles', THEME_LANG),
                'id'   => "{$prefix}related_acticles",
                'type' => 'select',
                'options' => array(
                    -1    => __('Default', THEME_LANG),
                    0		=> __('Hidden', THEME_LANG),
                    1		=> __('Show', THEME_LANG),
                ),
                'std'  => -1
            ),

            array(
                'name' => __('Select Thumbnail size for Random Layout Packery', THEME_LANG),
                'id'   => "{$prefix}thumbnail_packery",
                'type' => 'select',
                'options' => array(
                    'small_squared' => __('Small Squared', THEME_LANG),
                    'big_squared'	=> __('Big Squared', THEME_LANG),
                    'landscape'		=> __('Landscape', THEME_LANG),
                    'portrait'		=> __('Portrait', THEME_LANG),
                ),
                'std'  => 'small_squared'
            ),
        )
    );




    /**
     * For Team
     *
     */

    $meta_boxes[] = array(
        'title'  => __('Team Settings',THEME_LANG),
        'pages'  => array( 'kt_team' ),
        'fields' => array(
            array(
                'name' => __( 'Regency', THEME_LANG ),
                'id' => $prefix . 'team_regency',
                'desc' => __( "Regency.", THEME_LANG ),
                'type'  => 'text',
            ),
            array(
                'name' => __( 'Twitter', THEME_LANG ),
                'id' => $prefix . 'team_twitter',
                'desc' => __( "Link Twitter.", THEME_LANG ),
                'type'  => 'text',
            ),
            array(
                'name' => __( 'Facebook', THEME_LANG ),
                'id' => $prefix . 'team_facebook',
                'desc' => __( "Link Facebook.", THEME_LANG ),
                'type'  => 'text',
            ),
            array(
                'name' => __( 'Google+', THEME_LANG ),
                'id' => $prefix . 'team_googleplus',
                'desc' => __( "Link Google+.", THEME_LANG ),
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
                'name' => __( 'Link Client', THEME_LANG ),
                'id' => $prefix . 'link_client',
                'desc' => __( "Link Client.", THEME_LANG ),
                'type'  => 'text',
            ),

        )
    );
    
    
    /**
     * For Portfolio
     * 
     */
    
    $meta_boxes[] = array(
        'id' => 'portfolio_meta_boxes',
        'title' => 'Portfolio Options',
        'pages' => array( 'portfolio' ),
        'context' => 'normal',
        'priority' => 'default',
        'fields' => array(
            array(
                'name' => __('Layout configuration', THEME_LANG),
                'id' => $prefix . 'sidebar',
                'desc' => __("Choose the sidebar configuration for the detail page.", THEME_LANG),
                'type' => 'select',
                'options' => array(
                    'full' => __('Full Width', THEME_LANG),
                    'left' => __('Left Sidebar', THEME_LANG),
                    'right' => __('Right Sidebar', THEME_LANG)
                ),
                'std' => 'default'
            ),
            array(
                'name' => __('Left sidebar', THEME_LANG),
                'id' => $prefix . 'left_sidebar',
                'default' => true,
                'type' => 'sidebars',
            ),
            array(
                'name' => __('Right sidebar', THEME_LANG),
                'id' => $prefix . 'right_sidebar',
                'default' => true,
                'type' => 'sidebars'
            ),
            
            array(
                'name' => __('Video Type', THEME_LANG),
                'id' => $prefix . 'video_type',
                'type'     => 'select',
                'options'  => array(
                    '' => __('Select Option', THEME_LANG),
                    //'upload' => __('Upload', THEME_LANG),
                    'youtube' => __('Youtube', THEME_LANG),
                    'vimeo' => __('Vimeo', THEME_LANG),
                    'dailymotion' => __('Daily Motion', THEME_LANG)
                ),
            ),
            array(
                'name' => __( 'Video Id', THEME_LANG ),
                'id' => $prefix . 'video_id',
                'desc' => __( "Please fill this option with the required ID.", THEME_LANG ),
                'type'  => 'text',
            ),
            
            array(
                'name' => __('Select Image', THEME_LANG),
                'id' => $prefix . 'list_image',
                'type' => 'image_advanced'
            ),
            
            array(
                'name' => __( 'Client', THEME_LANG ),
                'id' => $prefix . 'client',
                'desc' => __( "Please enter your client.", THEME_LANG ),
                'type'  => 'text',
            ),
            array(
                'name' => __( 'Project Date', THEME_LANG ),
                'id' => $prefix . 'project_date',
                'desc' => __( "Please enter your date of project.", THEME_LANG ),
                'type'  => 'date',
            ),
            array(
                'name' => __( 'Link Project', THEME_LANG ),
                'id' => $prefix . 'link_project',
                'desc' => __( "Please enter your link project.", THEME_LANG ),
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
        'pages' => array( 'page', 'post', 'portfolio','product' ),
        'context' => 'normal',
        'priority' => 'high',
        'tabs'      => array(
            'header'  => array(
                'label' => __( 'Header', THEME_LANG ),
                'icon'  => 'fa fa-desktop',
            ),
            'page_header' => array(
                'label' => __( 'Page Header', THEME_LANG ),
                'icon'  => 'fa fa-bars',
            ),

            'page_layout' => array(
                'label' => __( 'Page layout', THEME_LANG ),
                'icon'  => 'fa fa-columns',
            ),
            'page_background' => array(
                'label' => __( 'Background', THEME_LANG ),
                'icon'  => 'fa fa-picture-o',
            ),
            /*
            'extra' => array(
                'label' => __( 'Extra', THEME_LANG ),
                'icon'  => 'fa fa-asterisk',
            ),
            */
        ),
        'fields' => array(
            // Page Header
            array(

                'name' => __( 'Page Header', THEME_LANG ),
                'id' => $prefix . 'page_header',
                'desc' => __( "Show Page Header.", THEME_LANG ),
                'type' => 'select',
                'options' => array(
                    -1    => __('Default', THEME_LANG),
                    0		=> __('Hidden', THEME_LANG),
                    1		=> __('Show', THEME_LANG),
                ),
                'std'  => -1,
                'tab'  => 'page_header',
            ),
            array(
                'name' => __( 'Page Header Custom Text', THEME_LANG ),
                'id' => $prefix . 'page_header_custom',
                'desc' => __( "Enter cstom Text for page header.", THEME_LANG ),
                'type'  => 'text',
                'tab'  => 'page_header',
                'required' => array($prefix . 'page_header','!=', '0' ),
            ),

            array(
                'name' => __( 'Page header subtitle', THEME_LANG ),
                'id' => $prefix . 'page_header_subtitle',
                'desc' => __( "Enter subtitle for page.", THEME_LANG ),
                'type'  => 'text',
                'tab'  => 'page_header',
                'required' => array($prefix . 'page_header','!=', '0' ),
            ),

            array(
                'id'       => "{$prefix}page_header_align",
                'type'     => 'select',
                'name'    => __( 'Page Header align', THEME_LANG ),
                'desc'     => __( 'Please select Page Header align', THEME_LANG ),
                'options'  => array(
                    ''    => __('Default', THEME_LANG),
                    'left' => __('Left', THEME_LANG ),
                    'center' => __('Center', THEME_LANG),
                    'right' => __('Right', THEME_LANG)
                ),
                'std'  => '',
                'tab'  => 'page_header',
                'required' => array($prefix . 'page_header','!=', '0' ),
            ),

            array(
                'name' => __('Page breadcrumb', THEME_LANG),
                'id'   => "{$prefix}show_breadcrumb",
                'type' => 'select',
                'options' => array(
                    -1    => __('Default', THEME_LANG),
                    0		=> __('Hidden', THEME_LANG),
                    1		=> __('Show', THEME_LANG),
                ),
                'std'  => -1,
                'desc' => __( "Show page breadcrumb.", THEME_LANG ),
                'tab'  => 'page_header',
                'required' => array($prefix . 'page_header','!=', '0' ),
            ),

            array(
                'name' => __('Separator bettwen title and subtitle', THEME_LANG),
                'id'   => "{$prefix}page_header_separator",
                'type' => 'select',
                'options' => array(
                    -1    => __('Default', THEME_LANG),
                    0		=> __('Hidden', THEME_LANG),
                    1		=> __('Show', THEME_LANG),
                ),
                'std'  => -1,
                'desc' => __( "Show separator bettwen title and subtitle.", THEME_LANG ),
                'tab'  => 'page_header',
                'required' => array($prefix . 'page_header','!=', '0' ),
            ),

            array(
                'name' => __('Page header top spacing', THEME_LANG),
                'id' => $prefix . 'page_header_top',
                'desc' => __("(Example: 60px). Emtpy for use default", THEME_LANG ),
                'type'  => 'text',
                'tab'  => 'page_header',
                'required' => array($prefix . 'page_header','!=', '0' ),
            ),
            array(
                'name' => __('Page header bottom spacing', THEME_LANG),
                'id' => $prefix . 'page_header_bottom',
                'desc' => __("(Example: 60px). Emtpy for use default", THEME_LANG ),
                'type'  => 'text',
                'tab'  => 'page_header',
                'required' => array($prefix . 'page_header','!=', '0' ),
            ),
            array(
                'name' => __( 'Separator custom color', THEME_LANG ),
                'id'   => "{$prefix}page_header_separator_color",
                'type' => 'color',
                'tab'  => 'page_header',
                'desc' => __( "Choose custom color for separator.", THEME_LANG ),
                'required' => array($prefix . 'page_header','!=', '0' ),
            ),
            array(
                'name' => __( 'Typography title custom color', THEME_LANG ),
                'id'   => "{$prefix}page_header_title_color",
                'type' => 'color',
                'tab'  => 'page_header',
                'desc' => __( "Choose custom color for title.", THEME_LANG ),
                'required' => array($prefix . 'page_header','!=', '0' ),
            ),
            array(
                'name' => __( 'Typography sub title custom color', THEME_LANG ),
                'id'   => "{$prefix}page_header_subtitle_color",
                'type' => 'color',
                'tab'  => 'page_header',
                'desc' => __( "Choose custom color for sub title.", THEME_LANG ),
                'required' => array($prefix . 'page_header','!=', '0' ),
            ),
            array(
                'name' => __( 'Typography breadcrumbs custom color', THEME_LANG ),
                'id'   => "{$prefix}page_header_breadcrumbs_color",
                'type' => 'color',
                'tab'  => 'page_header',
                'desc' => __( "Choose custom color for breadcrumbs.", THEME_LANG ),
                'required' => array($prefix . 'page_header','!=', '0' ),
            ),
            array(
                'name' => __('Background Options for page header', THEME_LANG),
                'id' => $prefix.'page_header_bg',
                'type'  => 'background',
                'tab'  => 'page_header',
                'required' => array($prefix . 'page_header','!=', '0' ),
            ),

            //Header
            array(
                'name'    => __( 'Header position', THEME_LANG ),
                'type'     => 'select',
                'id'       => $prefix.'header_position',
                'desc'     => __( "Please choose header position", THEME_LANG ),
                'options'  => array(
                    'default' => __('Default', THEME_LANG),
                    'transparent' => __('Transparent header', THEME_LANG),
                    'below' => __('Below Slideshow', THEME_LANG),
                ),
                'std'  => 'default',
                'tab'  => 'header',
            ),

            array(
                'name' => __('Transparent header Color Scheme', THEME_LANG),
                'id'   => "{$prefix}header_scheme",
                'type' => 'select',
                'options' => array(
                    'light'		=> __('Light', THEME_LANG),
                    'dark'		=> __('Dark', THEME_LANG),
                ),
                'std'  => 'light',
                'tab'  => 'header',
                'required' => array($prefix . 'header_position','=', 'transparent' ),
            ),

            array(
                'name' => __('Select Your Slideshow Type', THEME_LANG),
                'id' => $prefix . 'slideshow_source',
                'desc' => __("You can select the slideshow type using this option.", THEME_LANG),
                'type' => 'select',
                'options' => array(
                    '' => __('Select Option', THEME_LANG),
                    'revslider' => __('Revolution Slider', THEME_LANG),
                    'layerslider' => __('Layer Slider', THEME_LANG),
                ),
                'tab'  => 'header',
            ),
            array(
                'name' => __('Select Revolution Slider', THEME_LANG),
                'id' => $prefix . 'rev_slider',
                'default' => true,
                'type' => 'revSlider',
                'tab'  => 'header',
                'desc' => __('Select the Revolution Slider.', THEME_LANG),
                'required' => array($prefix . 'slideshow_source','=', 'revslider' ),
            ),
            array(
                'name' => __('Select Layer Slider', THEME_LANG),
                'id' => $prefix . 'layerslider',
                'default' => true,
                'type' => 'layerslider',
                'tab'  => 'header',
                'desc' => __('Select the Layer Slider.', THEME_LANG),
                'required' => array($prefix . 'slideshow_source','=', 'layerslider' ),
            ),

            /*
            array(
                'name' => __('Main Navigation Menu', THEME_LANG),
                'id'   => "{$prefix}header_main_menu",
                'type' => 'select',
                'options' => $menus_arr,
                'std'  => '',
                'tab'  => 'header',
            ),
            */

            //Page layout
            array(
                'name' => __('Page layout', THEME_LANG),
                'id' => $prefix . 'layout',
                'desc' => __("Please choose this page's layout.", THEME_LANG),
                'type' => 'select',
                'options' => array(
                    'default' => __('Default', THEME_LANG),
                    'full' => __('Full width Layout', THEME_LANG),
                    'boxed' => __('Boxed Layout', THEME_LANG),
                ),
                'std' => 'default',
                'tab'  => 'page_layout',
            ),
            array(
                'name' => __('Sidebar configuration', THEME_LANG),
                'id' => $prefix . 'sidebar',
                'desc' => __("Choose the sidebar configuration for the detail page.<br/><b>Note: Cart and checkout, My account page always use no sidebars.</b>", THEME_LANG),
                'type' => 'select',
                'options' => array(
                    'default' => __('Default', THEME_LANG),
                    'full' => __('No sidebars', THEME_LANG),
                    'left' => __('Left Sidebar', THEME_LANG),
                    'right' => __('Right Sidebar', THEME_LANG)
                ),
                'std' => 'default',
                'tab'  => 'page_layout',
            ),
            array(
                'name' => __('Left sidebar', THEME_LANG),
                'id' => $prefix . 'left_sidebar',
                'default' => false,
                'type' => 'sidebars',
                'tab'  => 'page_layout',
                'desc' => __("Select your sidebar.", THEME_LANG),
                'required' => array($prefix . 'sidebar','=', 'left' ),
            ),
            array(
                'name' => __('Right sidebar', THEME_LANG),
                'id' => $prefix . 'right_sidebar',
                'default' => false,
                'type' => 'sidebars',
                'tab'  => 'page_layout',
                'desc' => __("Select your sidebar.", THEME_LANG),
                'required' => array($prefix . 'sidebar','=', 'right' ),
            ),
            array(
                'name' => __('Page top spacing', THEME_LANG),
                'id' => $prefix . 'page_top_spacing',
                'desc' => __("Enter your page top spacing (Example: 100px).", THEME_LANG ),
                'type'  => 'text',
                'tab'  => 'page_layout',
            ),
            array(
                'name' => __('Page bottom spacing', THEME_LANG),
                'id' => $prefix . 'page_bottom_spacing',
                'desc' => __("Enter your page bottom spacing (Example: 100px).", THEME_LANG ),
                'type'  => 'text',
                'tab'  => 'page_layout',
            ),
            array(
                'name' => __('Extra page class', THEME_LANG),
                'id' => $prefix . 'extra_page_class',
                'desc' => __('If you wish to add extra classes to the body class of the page (for custom css use), then please add the class(es) here.', THEME_LANG ),
                'type'  => 'text',
                'tab'  => 'page_layout',
            ),
            array(
                'name' => __('Background Options for Boxed & Wide Mode', THEME_LANG),
                'id' => $prefix.'body_background',
                'type'  => 'background',
                'tab'  => 'page_background',
                'desc' => '&nbsp;',
            ),
            array(
                'name' => __('Background Options for Boxed mod', THEME_LANG),
                'id' => $prefix.'boxed_background',
                'type'  => 'background',
                'tab'  => 'page_background',
                'desc' => __('', THEME_LANG ),
            )

        )
    );


    return $meta_boxes;
}




