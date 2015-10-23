<?php $classes = array('post-item post-layout-zigzag', $blog_atts['class']);  ?>

<article <?php post_class($classes); ?>>
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12 col-first">
            <?php kt_post_thumbnail_image($blog_atts['image_size'], 'img-responsive'); ?>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <?php kt_post_thumbnail($blog_atts['image_size'], 'img-responsive'); ?>
        </div>
    </div>
</article>