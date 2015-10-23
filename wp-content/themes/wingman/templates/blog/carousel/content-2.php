<?php

$classes = array('post-item-carousel post-layout-carousel2', $blog_atts['class']);
//var_dump($blog_atts);
?>
<article <?php post_class($classes); ?>>
    <div class="entry-main-content">
        <?php kt_post_thumbnail_image($blog_atts['image_size'], 'img-responsive'); ?>
        <div class="post-item-carousel-outer">
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
        </div>
    </div>
</article>