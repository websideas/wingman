<form method="get" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<input type="text" placeholder="<?php _e('Search this site', THEME_LANG); ?>"  value="<?php echo get_search_query(); ?>" name="s" />
    <button class="submit"><i class="icon-magnifier"></i></button>
</form>