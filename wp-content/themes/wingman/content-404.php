
<div class="content-404">
    <div class="page-not-found">
        <?php
            $image_404_url = KT_THEME_IMG.'/404.png';
            $image_404 = kt_option( '404_image' );
            if( $image_404['url'] ){
                $image_404_url = $image_404['url'];
            }
        ?>
        <h1><img src="<?php echo esc_url($image_404_url); ?>" alt="<?php esc_html_e('404', 'wingman'); ?>" class="img-responsive" /></h1>

        <h3><?php esc_html_e('Opps! This page could not be found!', 'wingman') ?></h3>

        <?php get_search_form(); ?>

        <p><?php echo wp_kses(__('Sorry bit the page you are looking for does not exist, <br />have been removed or name changed', 'wingman'), array( 'br' => array() ) ); ?></p>

        <div class="buttons">
            <a title="<?php esc_html_e('Back to Home', 'wingman'); ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-default">
                <span>
                    <?php esc_html_e('Back to Homepage', 'wingman' ); ?>
                </span>
            </a>
            <a title="<?php esc_html_e('Back to previous page', 'wingman'); ?>" href="#" class="btn btn-default" onclick="window.history.back();">
                <span>
                    <?php esc_html_e('Back to previous page', 'wingman' ); ?>
                </span>
            </a>
        </div>
    </div><!-- .page-not-found -->
</div><!-- .content-404 -->