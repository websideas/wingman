
<div class="content-404">
    <div class="page-not-found">

        <h1><img src="<?php echo THEME_IMG; ?>404.png" alt="404" class="img-responsive" /></h1>
        <h3><?php _e('OPPS! THIS PAGE COULD NOT BE FOUND!', THEME_LANG) ?></h3>
        <?php get_search_form(); ?>
        <p ><?php _e('Sorry bit the page you are looking for does not exist, <br />have been removed or name changed', THEME_LANG ); ?></p>
        <div class="buttons">
            <a title="<?php _e('Back to Home', THEME_LANG); ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>" class="button alt">
                <span>
                    <?php _e('Back to Home', THEME_LANG ); ?>
                </span>
            </a>
            <a title="<?php _e('BACK TO PREVIES PAGE', THEME_LANG); ?>" href="#" class="button alt" onclick="goBack()">
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