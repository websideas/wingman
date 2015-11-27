<?php $classes = array('post-item post-layout-zigzag clearfix equal_height row', $blog_atts['class']);  ?>

<?php
    if( $blog_atts['blog_number'] %3 == 2 ){
        $class_offset = 'col-md-offset-2';
    }elseif( $blog_atts['blog_number'] %3 == 0 ){
        $class_offset = 'col-md-offset-4';
    }else{
        $class_offset = '';
    }
?>

<div class="col-md-8 <?php echo $class_offset; ?>">
    <article <?php post_class($classes); ?>>
        <div class="col-md-6 col-sm-6">
            <?php kt_post_thumbnail_image($blog_atts['image_size'], 'img-responsive'); ?>
        </div>
        <div class="col-md-6 col-sm-6">
            <?php kt_post_thumbnail($blog_atts['image_size'], 'img-responsive'); ?>
        </div>
    </article>
</div>