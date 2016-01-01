<?php
/**
 * The template for displaying comments
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
    return;
}
?>

<div id="comments" class="comments-area">

    <?php if ( have_comments() ) : ?>
        <h2 class="comments-title">
            <?php
            printf( _nx( 'One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'wingman' ),
                number_format_i18n( get_comments_number() ), get_the_title() );
            ?>
        </h2>

        <ol class="comment-list">
            <?php
            wp_list_comments( array(
                'style'       => 'ul',
                'short_ping'  => true,
                'avatar_size' => 60,
                'callback' => 'kt_comments'
            ) );
            ?>
        </ol><!-- .comment-list -->

        <?php kt_comment_nav(); ?>

    <?php endif; // have_comments() ?>

    <?php
    // If comments are closed and there are comments, let's leave a little note, shall we?
    if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
        ?>
        <p class="no-comments"><?php _e( 'Comments are closed.', 'wingman' ); ?></p>
    <?php endif; ?>
    
    <?php


    $commenter = wp_get_current_commenter();
    $req = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );
    $html_req = ( $req ? " required='required'" : '' );

    $required = ' '.__('(required)', 'wingman');

    $new_fields = array(
        'author' => '<p class="comment_field-column">' .
            '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '"  placeholder="'.__('Name', 'wingman').'"' . $aria_req . $html_req . ' /></p>',
        'email'  => '<p class="comment_field-column">' .
            '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" placeholder="'.__('Email', 'wingman').'"' . $aria_req . $html_req . ' /></p>',
        'url'    => '<p class="comment_field-column">' .
            '<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" placeholder="'.__('Website', 'wingman').'" /></p>',
    );



    $comments_args = array(
        'label_submit'      => __( 'send messages', 'wingman' ),
        'fields' => apply_filters( 'comment_form_default_fields', $new_fields ),
        //'comment_form_before_fields' => '<div>',
        //'comment_form_after_fields' => '</div>',
        'comment_field' => '<p><textarea id="comment" name="comment" placeholder="'.__('Your Comment', 'wingman').'"  aria-required="true" rows="6"></textarea></p>',
        'class_submit'      => 'btn btn-default',
    );

    ?>
    
    <?php comment_form($comments_args); ?>

</div><!-- .comments-area -->
