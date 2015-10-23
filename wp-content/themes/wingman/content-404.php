
<div class="content-404">
    <div class="page-not-found">



        <h1><?php _e('404', THEME_LANG) ?></h1>
        <h3><?php _e('SORRY, PAGE NOT FOUND', THEME_LANG) ?></h3>
        <p ><?php _e('We\'re sorry, but the Web address you\'ve entered is no longer available.', THEME_LANG ); ?></p>
        <?php get_search_form(); ?>
        <div class="buttons">
            <a title="<?php _e('Home', THEME_LANG); ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-default">
                <span>
                    <?php _e('Home page', THEME_LANG ); ?>
                    <i class="icon-home button-icon-right"></i>
                </span>
            </a>
        </div>

    </div><!-- .page-not-found -->
</div><!-- .content-404 -->