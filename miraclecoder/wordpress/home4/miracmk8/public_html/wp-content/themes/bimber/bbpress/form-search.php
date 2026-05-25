<?php

/**
 * Search 
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<form class="search-form" role="search" method="get" id="bbp-search-form" action="<?php bbp_search_url(); ?>">
	<label class="screen-reader-text hidden" for="bbp_search"><?php _e( 'Search for:', 'bbpress' ); ?></label>
	<input type="hidden" name="action" value="bbp-search-request" />
	<input class="search-field" placeholder="<?php esc_attr_e( 'Search for:', 'bbpress' ); ?>" tabindex="<?php bbp_tab_index(); ?>" type="text" value="<?php echo esc_attr( bbp_get_search_terms() ); ?>" name="bbp_search" id="bbp_search" />
	<button tabindex="<?php bbp_tab_index(); ?>" class="button search-submit" id="bbp_search_submit"><?php esc_html_e( 'Search', 'bbpress' ); ?></button>
</form>
