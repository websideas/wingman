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
				'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'wingman' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'wingman'  ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );
		?>
	</div><!-- .entry-content -->
    <?php
    if( kt_option( 'show_page_comment', 0 ) ){
        if ( comments_open() || get_comments_number() ) :
            comments_template();
        endif;
    }
    ?>
</div><!-- #post-## -->
