<form method="get" class="searchform search-post clearfix" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<input type="text" placeholder="<?php esc_html_e('Search', 'wingman'); ?>"  value="<?php echo esc_attr(get_search_query()); ?>" name="s" />
    <button class="submit"><i class="fa fa-search"></i></button>
</form>