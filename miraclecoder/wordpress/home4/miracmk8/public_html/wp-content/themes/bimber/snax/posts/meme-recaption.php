<?php
/**
 * Recaption meme
 *
 * @package snax 1.11
 * @subpackage Theme
 */

$dev = defined( 'BTP_DEV' ) && BTP_DEV;
if ( ! $dev ) {
	return;
}
if ( snax_get_meme_template_post_type() === get_post_type() ) {
	$template = get_the_ID();
} else {
	$template = get_post_meta( get_the_ID(), '_snax_meme_template', true );
}

if ( ! $template || empty( $template ) ) {
	return;
}

$url = snax_get_frontend_submission_page_url();
if ( strpos( $url, '?' ) > 0 ) {
	$url = $url . '&' . snax_get_url_var_prefix() . '_format=meme&meme_template=' . (int) $template;
} else {
	$url = $url . '?' . snax_get_url_var_prefix() . '_format=meme&meme_template=' . (int) $template;
}

?>

<a href="<?php echo esc_url( $url ); ?>" class="snax-similar-memes g1-button g1-button-s g1-button-simple"><?php echo esc_html__( 'Recaption this meme', 'snax' ); ?></a>
