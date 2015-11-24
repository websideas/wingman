<form method="get" class="searchform clearfix" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <?php wp_dropdown_categories('show_option_none=All Categories'); ?>
	<input type="text" placeholder="<?php _e('Search', THEME_LANG); ?>"  value="<?php echo get_search_query(); ?>" name="s" />
    <button class="submit"><i class="fa fa-search"></i></button>
</form>