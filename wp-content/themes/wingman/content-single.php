<?php $post_layout = kt_post_option(null, '_kt_blog_post_layout', 'blog_layout', 1); ?>

<div id="post-<?php the_ID(); ?>" <?php post_class('post-single post-layout-'.$post_layout ); ?>>
    <?php
        $show_post_format = kt_post_option(null, '_kt_post_format', 'blog_post_format', 1);
        $imagesize = kt_post_option(null, '_kt_blog_image_size', 'blog_image_size', 'blog_post');
        $title_meta_center = kt_post_option(null,'_kt_title_and_meta_center','title_meta_center',1);
    ?>
    <?php
        if( ! post_password_required( ) && $show_post_format && $post_layout == 1 ){
            kt_post_thumbnail( $imagesize, 'img-responsive', false , false);
        }
    ?>
    <header class="entry-header<?php if( $title_meta_center == 1 ){ echo ' text-center'; } ?>">
        <?php if(kt_post_option(null, '_kt_meta_info', 'blog_meta', 1)){ ?>
            <div class="entry-meta-data">
                <?php
                if(kt_option('blog_meta_author', 1)){
                    kt_entry_meta_author();
                }
                if(kt_option('blog_meta_categories', 1)) {
                    kt_entry_meta_categories();
                }
                if(kt_option('blog_meta_date', 1)) {
                    kt_entry_meta_time();
                }
                if(kt_option('blog_meta_comments', 1)){
     			    kt_entry_meta_comments();
                }
                if(kt_option('blog_view_number', 0)){
                    echo kt_get_post_views( get_the_ID() );
                }
                if(kt_option('blog_like_post', 1)){
                    kt_like_post();
                }
                ?>
            </div>
        <?php } ?>
        <h2 class="entry-title"><?php the_title(); ?></h2>
    </header><!-- .entry-header -->
    <div class="entry-content-outer">
        <?php
            if( ! post_password_required( ) && $show_post_format && $post_layout == 3 ){
                kt_post_thumbnail( $imagesize, 'img-responsive', false );
            }
        ?>

        <div class="entry-content clearfix">
            <?php the_content(); ?>
            <?php
            if( ! post_password_required( ) ):
                wp_link_pages( array(
                    'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', THEME_LANG ) . '</span>',
                    'after'       => '</div>',
                    'link_before' => '<span>',
                    'link_after'  => '</span>',
                    'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', THEME_LANG ) . ' </span>%',
                    'separator'   => '<span class="screen-reader-text">, </span>',
                ) );
            endif;
            ?>
        </div><!-- .entry-content -->

        <?php
            $show_sharebox = kt_post_option(null, '_kt_social_sharing', 'blog_share_box', 1);
            if(get_the_tag_list() || $show_sharebox){
                echo '<div class="entry-tool clearfix">';
                kt_entry_meta_tags('<div class="tags-container pull-left">', '</div>');
                if($show_sharebox){
                    kt_share_box( null, '', 'pull-right');
                }
                echo "</div>";

            }

        ?>
    </div>


    <?php

        if(kt_post_option(null, '_kt_prev_next', 'blog_next_prev', 1)){
            kt_post_nav();
        }

        if(kt_post_option(null, '_kt_author_info', 'blog_author', 1)){
            kt_author_box();
        }

    ?>

</div><!-- #post-## -->

<?php
    if(kt_post_option(null, '_kt_related_acticles', 'blog_related', 1)){
        kt_related_article(null, kt_option('blog_related_type', 'categories'));
    }

    // If comments are open or we have at least one comment, load up the comment template.
    if ( shortcode_exists( 'fbcomments' ) ) {
        echo '<div class="kt_facebook_comment">'.do_shortcode('[fbcomments]').'</div>';
    }else{
        if ( comments_open() || get_comments_number() ) :
            comments_template();
        endif;
    }

?>

