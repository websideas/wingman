<?php
/**
 * The template used for displaying page content
 *
 */
?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', THEME_LANG ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', THEME_LANG  ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );
		?>
        <?php //edit_post_link( __( 'Edit', THEME_LANG ), '<span class="edit-link">', '</span>' ); ?>
	</div><!-- .entry-content -->

    <?php
    if( kt_option( 'show_page_comment', 0 ) ){
        // If comments are open or we have at least one comment, load up the comment template.
        if ( shortcode_exists( 'fbcomments' ) ) {
            echo '<div class="kt_facebook_comment">'.do_shortcode('[fbcomments]').'</div>';
        }else{
            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;
        }
    }
    ?>
    
</div><!-- #post-## -->
