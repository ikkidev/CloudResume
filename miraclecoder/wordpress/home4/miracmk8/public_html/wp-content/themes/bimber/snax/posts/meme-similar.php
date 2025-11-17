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

$url = is_wp_error( get_term_link( 'meme', snax_get_snax_format_taxonomy_slug() ) ) ? '#' : get_term_link( 'meme', snax_get_snax_format_taxonomy_slug() );
if ( strpos( $url, '?' ) > 0 ) {
	$url = $url . '&' . snax_meme_get_archive_filter_by_template_query_var() . '=' . (int) $template;
} else {
	$url = $url . '?' . snax_meme_get_archive_filter_by_template_query_var() . '=' . (int) $template;
}
$count = snax_count_memes_by_template( $template );
if ( 1 === $count ) :?>
	<a href="<?php echo esc_url( $url ); ?>" class="snax-similar-memes g1-link"><?php echo sprintf( esc_html__( 'Show %s meme using this template', 'snax' ), $count ); ?></a>
<?php endif;
if ( $count > 1 ) :?>
	<a href="<?php echo esc_url( $url ); ?>" class="snax-similar-memes g1-link"><?php echo sprintf( esc_html__( 'Show %s memes using this template', 'snax' ), $count ); ?></a>
<?php endif;?>
