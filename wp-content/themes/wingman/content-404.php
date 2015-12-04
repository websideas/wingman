
<div class="content-404">
    <div class="page-not-found">
        <?php $image_404 = kt_option( '404_image' ); ?>
        <?php if( $image_404['url'] ){ ?>
            <h1><img src="<?php echo $image_404['url']; ?>" alt="404" class="img-responsive" /></h1>
        <?php } ?>
        <h3><?php _e('OPPS! THIS PAGE COULD NOT BE FOUND!', THEME_LANG) ?></h3>
        <?php get_search_form(); ?>
        <p ><?php _e('Sorry bit the page you are looking for does not exist, <br />have been removed or name changed', THEME_LANG ); ?></p>
        <div class="buttons">
            <a title="<?php _e('Back to Home', THEME_LANG); ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-default">
                <span>
                    <?php _e('Back to Homepage', THEME_LANG ); ?>
                </span>
            </a>
            <a title="<?php _e('BACK TO PREVIES PAGE', THEME_LANG); ?>" href="#" class="btn btn-default" onclick="goBack()">
                <span>
                    <?php _e('BACK TO PREVIES PAGE', THEME_LANG ); ?>
                </span>
            </a>
        </div>
        
        
        <script>
            function goBack() {
                window.history.back()
            }
        </script>
    </div><!-- .page-not-found -->
</div><!-- .content-404 -->