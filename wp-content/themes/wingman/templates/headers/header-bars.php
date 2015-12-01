<div class="menu-bars-outer">

    <a data-side="left" class="menu-bars-link" href="#">
        <i class="fa fa-bars"></i>
    </a>

    <div class="menu-bars-items">

        <?php if ( kt_is_wc()){ ?>

            <div class="menu-bars-item menu-bars-account">
                <ul>
                    <?php if(class_exists('YITH_WCWL_UI')){ ?>
                        <?php
                        global $yith_wcwl;
                        $count = YITH_WCWL()->count_products();
                        ?>
                        <li><a href="<?php echo esc_url($yith_wcwl->get_wishlist_url('')); ?>"><?php _e('My Wishlist', THEME_LANG) ?></a></li>
                    <?php } ?>
                    <?php if(defined( 'YITH_WOOCOMPARE' )){ ?>
                        <li><a href="#" class="yith-woocompare-open"><?php _e('Compare', THEME_LANG) ?></a></li>
                    <?php } ?>
                    <li><a href="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>"><?php _e('My Cart', THEME_LANG) ?></a></li>
                    <li><a href="<?php echo esc_url( WC()->cart->get_checkout_url() ); ?>"><?php _e('Check out', THEME_LANG) ?></a></li>
                </ul>
            </div><!-- .menu-bars-item -->

            <?php if(class_exists('WOOCS')){ ?>
                <div class="menu-bars-item menu-bars-currency">
                    <h4>Currency</h4>
                    <?php
                    global $WOOCS;
                    $currencies=$WOOCS->get_currencies();
                    echo '<ul>';
                    foreach($currencies as $key => $currency){
                        $selected = ($WOOCS->current_currency == $key) ? 'active' : '';
                        printf(
                            '<li class="%s"><a href="#" data-currency="%s" title="%s"><span></span>%s</a>',
                            $selected,
                            $currency['name'],
                            $currency['description'],
                            $currency['name']
                        );
                    }
                    echo '</ul>';
                    ?>
                </div><!-- .menu-bars-item -->
            <?php } ?>

        <?php } ?>

        <?php
            kt_custom_wpml('<div class="menu-bars-item menu-bars-language">', '</div>', __('Language', THEME_LANG));
        ?>

        <?php
        /**
         * @hooked
         */
        do_action( 'menu_bars_tool' ); ?>

    </div><!-- .menu-bars-items -->

</div><!-- .menu-bars-outer -->