<?php
/**
 * The template part for a newsletter sign-up form after the post content.
 *
 * @package Bimber_Theme 5.4
 */

?>

<?php if ( bimber_can_use_plugin( 'mailchimp-for-wp/mailchimp-for-wp.php' ) ) {
	$newsletter_config = bimber_mc4wp_get_slot_config( 'after_post_content' );

	if ( ! empty( $newsletter_config ) ) {
		$newsletter_classes = apply_filters( 'bimber_newsletter_after_content_class', array(
			'g1-box',
			'g1-newsletter',
			'g1-newsletter-after-post-content',
			'g1-newsletter-horizontal',
		) );

		echo do_shortcode( sprintf(
			'[bimber_mc4wp_form title="%s" subtitle="%s" avatar_id="%d" background_image_id="%d" template="%s" class="%s"]',
			$newsletter_config['title'],
			$newsletter_config['subtitle'],
			$newsletter_config['avatar_id'],
			$newsletter_config['background_image_id'],
			$newsletter_config['template'],
			implode( ' ', $newsletter_classes )
		));
	}
} else {
	get_template_part( 'template-parts/newsletter/notice-plugin-required' );
}
