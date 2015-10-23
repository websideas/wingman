<?php /* Template Name: Blank */ ?>

<?php get_header( 'blank' ); ?>
<div class="container">
    <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="entry-content">
                <?php the_content(); ?>
            </div>
        </article>
    <?php endwhile; // end of the loop. ?>
</div>
<?php get_footer( 'blank' ); ?>