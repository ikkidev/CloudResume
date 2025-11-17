<?php
/**
 * Mailchimp for WP plugin functions
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

require_once trailingslashit( get_parent_theme_file_path() ) . 'includes/plugins/mailchimp-for-wp/customizer.php';

if ( bimber_can_use_plugin( 'js_composer/js_composer.php' ) ) {
	require_once trailingslashit( get_parent_theme_file_path() ) . 'includes/plugins/mailchimp-for-wp/visual-composer.php';
}

// Shortcode.
add_shortcode( 'bimber_mc4wp_form', 'bimber_mc4wp_form_shortcode' );

// Widget.
//add_filter( 'mc4wp_form_before_fields', 'bimber_mc4wp_avatar_before_form', 10, 2 );
add_filter( 'mc4wp_form_before_fields', 'bimber_mc4wp_title_before_form', 10, 2 );

// Privacy text.
add_filter( 'mc4wp_form_after_fields', 'bimber_mc4wp_privacy_text_after_form', 10, 2 );

// After demo data import.
add_action( 'bimber_after_import_content', 'bimber_mc4wp_reset_form_id_if_invalid' );

/**
 * Render title before the newsletter sign-up form fields
 *
 * @param string $html  HTML markup.
 *
 * @return string
 */
function bimber_mc4wp_title_before_form( $html ) {
	$html .= '<p class="g1-alpha g1-alpha-1st">' . esc_html( bimber_get_theme_option( 'newsletter', 'other_title' ) ) . '</p>';

	return $html;
}

/**
 * Render avatar before the newsletter sign-up form fields
 *
 * @param string $html  HTML markup.
 *
 * @return string
 */
function bimber_mc4wp_avatar_before_form( $html ) {
	$newsletter_avatar = bimber_get_theme_option( 'newsletter', 'other_avatar' );
	$html .= '<div class="g1-newsletter-avatar">';
	if ( ! empty( $newsletter_avatar ) ) {
		$avatar_id = bimber_get_attachment_id_by_url( $newsletter_avatar );
		if ( $avatar_id ) {
			$html .= wp_get_attachment_image( $avatar_id, 'thumbnail', false, array( 'class' => 'g1-no-lazyload' ) );
		}
	}
	$html .= '</div>';
	return $html;
}

/**
 * Render the privacy text after the newsletter sign-up form fields
 *
 * @param string $html      HTML markup.
 *
 * @return string
 */
function bimber_mc4wp_privacy_text_after_form( $html ) {
	$html .= '<p class="g1-meta g1-newsletter-privacy">' . wp_kses_post( bimber_get_theme_option( 'newsletter', 'privacy' ) ) . '</p>';

	return $html;
}

/**
 * Set up default newsletter sign-up form id
 */
function bimber_mc4wp_reset_form_id_if_invalid() {
	// Validate form.
	$form_id = (int) get_option( 'mc4wp_default_form_id', 0 );
	$form = get_post( $form_id );

	$valid_form = true;

	// Invalid form.
	if( ! is_object( $form ) || ! isset( $form->post_type ) || $form->post_type !== 'mc4wp-form' ) {
		$valid_form = false;
	}

	if ( $valid_form ) {
		return;
	}

	// Find first form.
	$query_args = array(
		'posts_per_page'        => 1,
		'post_type'             => 'mc4wp-form',
		'post_status'           => 'publish',
		'ignore_sticky_posts'   => true,
	);

	$query = new WP_Query();
	$forms = $query->query( $query_args );

	// If form exists, set its id as default.
	if ( ! empty( $forms ) ) {
		$form = $forms[0];

		update_option( 'mc4wp_default_form_id', $form->ID );
	} else {
		// Form doesn't exist, create a new and set as default.
        $email_label       = esc_html__( 'Email address', 'mailchimp-for-wp' );
        $email_placeholder = esc_html__( 'Your email address', 'mailchimp-for-wp' );
        $signup_button     = esc_html__( 'Sign up', 'mailchimp-for-wp' );

        $form_content  = "<p>\n\t<label>{$email_label}:</label> \n";
        $form_content .= "\t\t<input type=\"email\" name=\"EMAIL\" placeholder=\"{$email_placeholder}\" required />\n</p>\n\n";
        $form_content .= "<p>\n\t<input type=\"submit\" value=\"{$signup_button}\" />\n</p>";

		// Fix for MultiSite stripping KSES for roles other than administrator/
		remove_all_filters( 'content_save_pre' );

		$form_id = wp_insert_post(
			array(
				'post_type'     => 'mc4wp-form',
				'post_status'   => 'publish',
				'post_title'    => 'Default sign-up form',
				'post_content'  => $form_content,
			)
		);

		update_option( 'mc4wp_default_form_id', $form_id );
	}
}

/**
 * Shortcode
 *
 * @param array $atts			Shortcode attributes.
 *
 * @return string				Shortcode output.
 */
function bimber_mc4wp_form_shortcode( $atts, $content ) {
	static $counter = 0;

	$default_atts = array(
		'title' 	            => esc_html__( 'Get the best viral stories straight into your inbox!', 'bimber' ),
		'subtitle'	            => '',
		'avatar_id'	            => '',
		'background_image_id'   => '',
		'template'	            => 'box-vertical',
		'id'	                => '',
		'class'	                => '',
	);

	$atts = shortcode_atts( $default_atts, $atts, 'bimber_mc4wp_form' );

	$final_id = strlen( $atts['id'] ) ? $atts['id'] : 'bimber-mc4wp-form-counter-' . ++$counter;

	$final_classes = array(
		// Add common classes here.
	);
	$final_classes = array_merge( $final_classes, explode( ' ', $atts['class'] ) );

	$out = '';

	if ( shortcode_exists( 'mc4wp_form' ) ) {
		bimber_mc4wp_reset_form_id_if_invalid();

		global $bimber_mc4wp_data;
		$bimber_mc4wp_data = array();

		$bimber_mc4wp_data['title']                 = $atts['title'];
		$bimber_mc4wp_data['subtitle']              = $atts['subtitle'];
		$bimber_mc4wp_data['avatar_id']             = $atts['avatar_id'];
		$bimber_mc4wp_data['background_image_id']   = $atts['background_image_id'];
		$bimber_mc4wp_data['id']                    = $final_id;
		$bimber_mc4wp_data['classes']               = $final_classes;

		//remove_filter( 'mc4wp_form_before_fields', 'bimber_mc4wp_avatar_before_form', 10 );
		remove_filter( 'mc4wp_form_before_fields', 'bimber_mc4wp_title_before_form', 10 );

		ob_start();
		get_template_part( 'template-parts/newsletter/templates/' . $atts['template'] );
		$out = ob_get_clean();

		//add_filter( 'mc4wp_form_before_fields', 'bimber_mc4wp_avatar_before_form', 10, 2 );
		add_filter( 'mc4wp_form_before_fields', 'bimber_mc4wp_title_before_form', 10, 2 );
	}

	return $out;
}

/**
 * Return newsletter config for the slot location
 *
 * @param string $slot           Slot location
 *
 * @return array|bool            False if not set
 */
function bimber_mc4wp_get_slot_config( $slot ) {
	if ( ! $slot ) {
		return false;
	}

	$title       = bimber_get_theme_option( 'newsletter', $slot . '_title' );
	$subtitle    = bimber_get_theme_option( 'newsletter', $slot . '_subtitle' );
	$avatar      = bimber_get_theme_option( 'newsletter', $slot . '_avatar' );
	$avatar_id   = ! empty( $avatar ) ? bimber_get_attachment_id_by_url( $avatar ) : 0;
	$bg_image    = bimber_get_theme_option( 'newsletter', $slot . '_background_image' );
	$bg_image_id = ! empty( $bg_image ) ? bimber_get_attachment_id_by_url( $bg_image ) : 0;
	$template    = bimber_get_theme_option( 'newsletter', $slot . '_template' );

	$config = array(
		'title'                 => $title,
		'subtitle'              => $subtitle,
		'avatar_id'             => $avatar_id,
		'background_image_id'   => $bg_image_id,
		'template'              => $template,


	);

	return apply_filters( 'bimber_mc4wp_slot_config', $config, $slot );
}

/**
 * Render some HTML markup before the newsletter sign-up form fields
 *
 * @param string $html  HTML markup.
 *
 * @since 6.2
 * @deprecated
 *
 *
 * @return string
 */
function bimber_mc4wp_avatar_before_form_in_collection( $html ) {
	_deprecated_function( __METHOD__, '6.2' );

	return $html;
}

/**
 * Render mailchimp shortcode
 *
 * @since 6.2
 * @deprecated
 *
 * @param string $position	Position.
 */
function bimber_mc4wp_render_shortcode() {
	_deprecated_function( __METHOD__, '6.2', __( 'Use the [bimber_mc4wp_form] shortcode.', 'bimber' ) );
}

/**
 * Render newsletter form header
 *
 * @since 6.2
 * @deprecated
 *
 * @param  string $title_class     Title header type.
 * @param  string $subtitle_class  Subtitle header type.
 * @param  string $position        Theme position.
 */
function bimber_mc4wp_newsletter_header( $title_class, $subtitle_class, $position ) {
	_deprecated_function( __METHOD__, '6.2' );
}

/**
 * Render avatar.
 *
 * @since 6.2
 * @deprecated
 *
 * @param string $url      	Url.
 * @param string $hdpi_url	HDPI url.
 */
function bimber_mc4wp_render_avatar( $url, $hdpi_url, $class = false ) {
	_deprecated_function( __METHOD__, '6.2' );
}



/**
 * Return list of newsletter templates
 *
 * @return array
 */
function bimber_mc4wp_customizer_get_template_choices() {
	return array(
		'box-vertical'          => _x( 'Box vertical',          'newsletter template', 'bimber' ),
		'box-horizontal'        => _x( 'Box horizontal',        'newsletter template', 'bimber' ),
		'box-horizontal-l'      => _x( 'Box horizontal large',  'newsletter template', 'bimber' ),
		'background-vertical'   => _x( 'Background vertical',   'newsletter template', 'bimber' ),
		'background-horizontal' => _x( 'Background horizontal', 'newsletter template', 'bimber' ),
	);
}