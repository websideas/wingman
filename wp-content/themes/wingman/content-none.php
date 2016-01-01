<?php
/**
 * The template part for displaying a message that posts cannot be found
 *
 * Learn more: {@link https://codex.wordpress.org/Template_Hierarchy}
 *
 * @package WordPress
 */
?>

<div class="no-results not-found">
    <div class="page-content">

        <?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

            <p><?php printf( esc_html__( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'wingman' ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>

        <?php elseif ( is_search() ) : ?>

            <p>
                <?php printf( esc_html__( "Sorry ! No post was found by <span class='search-keyword'>'%s'</span>.", 'wingman' ), get_search_query() ); ?>
                <?php esc_html_e('Try searching for something else', 'wingman'); ?>
            </p>
            <?php get_search_form(); ?>

        <?php else : ?>

            <p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'wingman' ); ?></p>
            <?php get_search_form(); ?>

        <?php endif; ?>

    </div><!-- .page-content -->
</div><!-- .no-results -->
