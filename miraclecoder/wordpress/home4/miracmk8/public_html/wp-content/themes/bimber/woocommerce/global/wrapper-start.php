<?php
/**
 * Content wrappers
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/wrapper-start.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version 	3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php 
if ( is_product() ) :
	$bimber_class = array(
				'g1-row',
				'g1-row-layout-page',
			);
	if ( bimber_show_breadcrumbs() ) {
		$bimber_class[] = 'g1-row-padding-m';
	}
?>
	<div class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $bimber_class ) ); ?>">
		<div class="g1-row-background">
		</div>
		<div class="g1-row-inner">
			<?php if ( 'show' === bimber_get_theme_option( 'woocommerce', 'single_product_sidebar' ) ) : ?>
				<div class="g1-column g1-column-2of3" id="primary">
			<?php else : ?>
				<div class="g1-column" id="primary">
			<?php  endif; ?>
				<div id="content" role="main">
<?php else : ?>
	<div class="g1-row g1-row-layout-page g1-row-padding-m archive-body">
		<div class="g1-row-inner">

			<?php if ( bimber_woocommerce_page_has_sidebar() ) : ?>
				<div class="g1-column g1-column-2of3">
			<?php else : ?>
				<div class="g1-column">
			<?php  endif; ?>
<?php endif;
