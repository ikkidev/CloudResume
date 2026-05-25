<?php
/**
 * Visual Composer element
 *
 * @package AdAce
 * @subpackage Plugins
 */

add_action( 'init', 'adace_vc_register_ad_element' );

/**
 * Register Ad element
 */
function adace_vc_register_ad_element() {
	$ads        = adace_get_all_ads();
	$ad_choices = array();

	foreach( $ads as $ad ) {
		$ad_choices[ $ad->post_title ] = $ad->ID;
	}

	vc_map( array(
		'name' 		=> __( 'Ad', 'adace' ),
		'base'	 	=> 'adace-ad',
		'category' 	=> __( 'AdAce', 'adace' ),
		'params' 	=> apply_filters( 'adace_vc_ad_params', array(
			array(
				'type' 			=> 'dropdown',
				'heading' 		=> __( 'Ad name', 'adace' ),
				'param_name'	=> 'id',
				'value' 		=> $ad_choices,
			),
			array(
				'type' 			=> 'dropdown',
				'heading' 		=> __( 'Align', 'adace' ),
				'param_name'	=> 'align',
				'value' 		=> array(
					_x('none',      'align', 'adace' )  => 'none',
					_x('center',    'align', 'adace' )  => 'center',
					_x('left',      'align', 'adace' )  => 'left',
					_x('right',     'align', 'adace' )  => 'right',
				),
				'std' 		=> 'center',
			),
		) ),
	) );
}
