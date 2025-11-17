<?php
/**
 * BP Nouveau Messages search form template.
 *
 * @since 3.2.0
 * @version 3.2.0
 */
?>
<form action="" method="get" id="user_messages_search_form" class="bp-messages-search-form" data-bp-search="messages">
	<label for="user_messages_search" class="bp-screen-reader-text">
		<?php _e( 'Search Messages', 'buddypress' ); ?>
	</label>
	<input type="search" id="user_messages_search" placeholder="<?php echo esc_attr_x( 'Search', 'search placeholder text', 'buddypress' ); ?>"/>
	<button type="submit" id="user_messages_search_submit" class="g1-button g1-button-m g1-button-simple">
		<span class="bp-screen-reader-text"><?php echo esc_html_x( 'Search', 'button', 'buddypress' ); ?></span>
	</button>
</form>
