<form method="get" class="searchform search-post clearfix" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<input type="text" placeholder="<?php _e('Search', KT_THEME_LANG); ?>"  value="<?php echo get_search_query(); ?>" name="s" />
    <button class="submit"><i class="fa fa-search"></i></button>
</form>