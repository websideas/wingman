<?php

if ( !function_exists( 'kt_admin_enqueue_scripts' ) ) {

    /**
     * Add stylesheet and script for admin
     *
     * @since       1.0
     * @return      void
     * @access      public
     */
    function kt_admin_enqueue_scripts(){
        wp_enqueue_style( 'kt-font-awesome', THEME_FONTS.'font-awesome/css/font-awesome.min.css');
        wp_enqueue_style( 'icomoon-theme', THEME_FONTS . 'Lineicons/style.css', array());
        wp_enqueue_style( 'framework-core', FW_CSS.'framework-core.css');
        wp_enqueue_style( 'chosen', FW_LIBS.'chosen/chosen.min.css');
        wp_enqueue_style('admin-style', FW_CSS.'theme-admin.css');

        wp_enqueue_script( 'kt_image', FW_JS.'kt_image.js', array('jquery'), FW_VER, true);
        wp_enqueue_script( 'chosen', FW_LIBS.'chosen/chosen.jquery.min.js', array('jquery'), FW_VER, true);
        wp_enqueue_script( 'cookie', FW_JS.'jquery.cookie.js', array('jquery'), FW_VER, true);
        wp_enqueue_script( 'showhide_metabox', FW_JS.'kt_showhide_metabox.js', array('jquery'), FW_VER, true);
        wp_enqueue_script( 'kt_icons', FW_JS.'kt_icons.js', array('jquery'), FW_VER, true);


        wp_localize_script( 'kt_image', 'kt_image_lange', array(
            'frameTitle' => __('Select your image', THEME_LANG )
        ));

        wp_register_script( 'framework-core', FW_JS.'framework-core.js', array('jquery', 'jquery-ui-tabs'), FW_VER, true);
        wp_enqueue_script('framework-core');

        $accent = kt_option('styling_accent', '#d0a852');

        if( $accent !='' ) {
            $accent_darker = kt_colour_brightness($accent, -0.8);
            $css = '
                .vc_btn3.vc_btn3-color-accent,
                .vc_btn3.vc_btn3-color-accent.vc_btn3-style-flat,
                .vc_btn3.vc_btn3-color-accent.vc_btn3-style-modern,
                .vc_btn3.vc_btn3-color-accent.vc_btn3-style-3d {background: %1$s !important;}
                .vc_btn3.vc_btn3-color-accent.vc_btn3-style-outline,
                .vc_btn3.vc_btn3-color-accent.vc_btn3-style-outline:hover,
                .vc_btn3.vc_btn3-color-accent.vc_btn3-style-outline:focus {border-color: %1$s !important;color: %1$s!important;background: transparent !important;}
                .vc_btn3.vc_btn3-color-accent.vc_btn3-style-3d.vc_btn3-size-sm {box-shadow: 0 4px 0 %2$s !important;}
                .vc_colored-dropdown .accent {background-color: %1$s !important;}
                ';

            wp_add_inline_style('admin-style', sprintf($css, $accent, $accent_darker));
        }

    } // End kt_admin_enqueue_scripts.
    add_action( 'admin_enqueue_scripts', 'kt_admin_enqueue_scripts' );
}

if ( !function_exists( 'kt_taxonomy_add_icon' ) ) {
    /**
     * Add icon picker to Taxonomy
     *
     */
    function kt_taxonomy_add_icon()
    {
        ?>
        <div class="form-field">
            <label for="category_icon"><?php _e('Category icon', THEME_LANG); ?></label>
            <?php kt_iconpicker('category_icon', ''); ?>
        </div>
        <?php
    }

    add_action('portfolio-category_add_form_fields', 'kt_taxonomy_add_icon', 10, 2);
}


if ( !function_exists( 'kt_taxonomy_edit_form_icon' ) ) {
    /**
     * Add icon picker to Taxonomy
     *
     * @param $tag
     */
    function kt_taxonomy_edit_form_icon($tag)
    {
        $value = get_metadata('taxonomy', $tag->term_id, 'category_icon', TRUE);
        ?>
        <tr class="form-field ">
            <th scope="row" valign="top"><label for="tag_widget"><?php _e('Category icon', THEME_LANG) ?></label></th>
            <td>
                <?php kt_iconpicker('category_icon', $value); ?>
            </td>
        </tr>
        <?php
    }

    add_action('portfolio-category_edit_form_fields', 'kt_taxonomy_edit_form_icon');
}

if ( !function_exists( 'save_icon_data' ) ) {
    /**
     * Save icon when submit
     *
     * @param $term_id
     */
    function save_icon_data($term_id)
    {
        if (isset($_POST['category_icon'])) {
            $tag_metadata = esc_attr($_POST['category_icon']);
            update_metadata('taxonomy', $term_id, 'category_icon', $tag_metadata);
        }
    }

    add_action('edited_terms', 'save_icon_data');
    add_action('create_portfolio-category', 'save_icon_data');
}





function kt_attachment_fields( $fields, $post ) {
    $meta = get_post_meta($post->ID, '_kt_image_layout', true);
    $fields['meta_link'] = array(
        'label' => __('Image layout', THEME_LANG),
        'input' => 'html',
        'show_in_edit' => true,
    );

    $html = '';
    $options = array(
        'contain' => __('Contain', THEME_LANG),
        'cover' => __('Cover', THEME_LANG)
    );

    foreach($options as $k => $v){
        $html .= sprintf(
            '<option value="%s" %s>%s</option>',
            $k,
            selected($k, $meta, false),
            $v
        );
    }


    $fields["meta_link"]["html"] = sprintf(
        '<select name="attachments[%1$s][meta_link]" id="attachments[%1$s][meta_link]">%2$s</select>',
        $post->ID,
        $html
    );

    return $fields;
}
add_filter( 'attachment_fields_to_edit', 'kt_attachment_fields', 10, 2 );



function kt_update_attachment_meta($attachment){
    global $post;
    update_post_meta($post->ID, '_kt_image_layout', $attachment['attachments'][$post->ID]['meta_link']);
}
add_filter( 'attachment_fields_to_save', 'kt_update_attachment_meta', 4);

function kt_media_xtra_fields() {
    $post_id = $_POST['id'];
    $meta = $_POST['attachments'][$post_id ]['meta_link'];
    update_post_meta($post_id , '_kt_image_layout', $meta);
    clean_post_cache($post_id);
}
add_action('wp_ajax_save-attachment-compat', 'kt_media_xtra_fields', 0, 1);