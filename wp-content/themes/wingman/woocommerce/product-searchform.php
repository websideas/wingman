<form method="get" class="woocommerce-product-search searchform" action="<?php echo esc_url( home_url( '/'  ) ); ?>">
	<?php kt_get_categories_product(); ?>
    <label class="screen-reader-text"><?php _e( 'Search for:', 'woocommerce' ); ?></label>
    <input type="text" class="search-field" placeholder="<?php echo esc_attr_x( 'Search Products&hellip;', 'placeholder', 'woocommerce' ); ?>" value="<?php echo get_search_query(); ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label', 'woocommerce' ); ?>" />
    <input type="hidden" name="post_type" value="product" />
    <button><i class="fa fa-search"></i></button>
</form>

