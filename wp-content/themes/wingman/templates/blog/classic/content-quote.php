<?php
    $classes = array('post-item post-layout-classic', $blog_atts['class']);
?>
<article <?php post_class($classes); ?>>

    <?php
    if($blog_atts['thumbnail_type'] == 'image'){
        kt_post_thumbnail_image($blog_atts['image_size'], 'img-responsive');
    }else{
        kt_post_thumbnail($blog_atts['image_size'], 'img-responsive');
    }
    ?>
    <?php if($blog_atts['thumbnail_type'] == 'image'){ ?>
        <div class="entry-main-content">

            <div class="post-info">
                <div class="entry-ci">
                    <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <?php if($blog_atts['show_meta']){ ?>
                        <div class="entry-meta-data">
                            <?php
                            if($blog_atts['show_author']){
                                kt_entry_meta_author();
                            }
                            if($blog_atts['show_category']){
                                kt_entry_meta_categories();
                            }
                            if($blog_atts['show_comment']){
                                kt_entry_meta_comments();
                            }
                            if($blog_atts['show_date']){
                                kt_entry_meta_time($blog_atts['date_format']);
                            }
                            if($blog_atts['show_like_post']){
                                kt_like_post();
                            }
                            ?>
                        </div>
                    <?php } ?>
                    <?php if($blog_atts['show_excerpt']){ ?>
                        <div class="entry-excerpt">
                            <?php
                            the_content(sprintf(
                                __('Read more %s', THEME_LANG),
                                the_title('<span class="screen-reader-text">', '</span>', false)
                            ));
                            ?>
                        </div><!-- .entry-excerpt -->
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>
</article>