<?php
/**
 * MyCred import functions
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme
 */

add_filter( 'bimber_mycred_modules_list', 'bimber_mycred_default_module_list', 10, 1 );

/**
 * List of available modules to import
 *
 * @param array $list  List of module names.
 * @return array
 */
function bimber_mycred_default_module_list( $list ) {
	$list[] = 'core';
	$list[] = 'bbpress';
	$list[] = 'snax';
	$list[] = 'wyr';
	return $list;
}


add_action( 'bimber_mycred_display_import_packages', 'bimber_mycred_display_import_package_core' );

/**
 * Render import package checkbox
 */
function bimber_mycred_display_import_package_core() { ?>
	<input name="import_mycred_settings[core]" id="import_mycred_core" type="checkbox" value="import_mycred_core" checked/>
	<label for="import_mycred_core">Bimber</label>
	<br />
	<?php
}

add_action( 'bimber_mycred_display_import_packages', 'bimber_mycred_display_import_package_bbpress' );

/**
 * Render import package checkbox
 */
function bimber_mycred_display_import_package_bbpress() { ?>
	<input name="import_mycred_settings[bbpress]" id="import_mycred_bbpress" type="checkbox" value="import_mycred_bbpress"
	<?php disabled( bimber_can_use_plugin( 'bbpress/bbpress.php' ), false )?>
	<?php checked( bimber_can_use_plugin( 'bbpress/bbpress.php' ), true )?>
	/>
	<label for="import_mycred_bbpress">bbPress</label>
	<?php if ( ! bimber_can_use_plugin( 'bbpress/bbpress.php' ) ) :?>
		- <span class="description"><?php esc_html_e( 'Activate plugin to enable this option', 'bimber' ); ?></span>
	<?php endif;?>
	<br />
	<?php
}

add_action( 'bimber_mycred_display_import_packages', 'bimber_mycred_display_import_package_snax' );

/**
 * Render import package checkbox
 */
function bimber_mycred_display_import_package_snax() { ?>
	<input name="import_mycred_settings[snax]" id="import_mycred_snax" type="checkbox" value="import_mycred_snax"
	<?php disabled( bimber_can_use_plugin( 'snax/snax.php' ), false )?>
	<?php checked( bimber_can_use_plugin( 'snax/snax.php' ), true )?>
	/>
	<label for="import_mycred_snax">Snax</label>
	<?php if ( ! bimber_can_use_plugin( 'snax/snax.php' ) ) :?>
		- <span class="description"><?php esc_html_e( 'Activate plugin to enable this option', 'bimber' ); ?></span>
	<?php endif;?>
	<br />
	<?php
}

add_action( 'bimber_mycred_display_import_packages', 'bimber_mycred_display_import_package_wyr' );

/**
 * Render import package checkbox
 */
function bimber_mycred_display_import_package_wyr() { ?>
	<input name="import_mycred_settings[wyr]" id="import_mycred_wyr" type="checkbox" value="import_mycred_wyr"
	<?php disabled( bimber_can_use_plugin( 'whats-your-reaction/whats-your-reaction.php' ), false )?>
	<?php checked( bimber_can_use_plugin( 'whats-your-reaction/whats-your-reaction.php' ), true )?>
	/>
	<label for="import_mycred_wyr"><?php esc_html_e( 'What\'s Your Reaction?', 'wyr' ); ?></label>
	<?php if ( ! bimber_can_use_plugin( 'whats-your-reaction/whats-your-reaction.php' ) ) :?>
		- <span class="description"><?php esc_html_e( 'Activate plugin to enable this option', 'bimber' ); ?></span>
	<?php endif;?>
	<br />
	<?php
}
