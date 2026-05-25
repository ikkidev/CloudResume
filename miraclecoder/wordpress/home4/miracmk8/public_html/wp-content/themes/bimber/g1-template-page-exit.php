<?php
/**
 * Template Name: Page: Intermediate
 *
 * For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme 6.1
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}


?><!DOCTYPE html>
<!--[if IE 8]>
<html class="no-js lt-ie10 lt-ie9" id="ie8" <?php language_attributes(); ?>><![endif]-->
<!--[if IE 9]>
<html class="no-js lt-ie10" id="ie9" <?php language_attributes(); ?>><![endif]-->
<!--[if !IE]><!-->
<html class="no-js" <?php language_attributes(); ?>><!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>"/>
	<link rel="profile" href="http://gmpg.org/xfn/11"/>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>"/>

	<?php wp_head(); ?>
</head>

<body <?php body_class( 'g1-interpage' ); ?> itemscope itemtype="http://schema.org/WebPage">
	<?php do_action( 'bimber_body_start' ); ?>

	<?php
	if ( apply_filters( 'bimber_show_ad_before_header_theme_area', true ) ) :
		get_template_part( 'template-parts/ads/ad-before-header-theme-area' );
	endif;
	?>

<div class="g1-body-inner">

	<div class="g1-header g1-header-simplified g1-row g1-row-layout-page">
		<div class="g1-row-inner">
			<div class="g1-column">
				<?php
				get_template_part(
					'template-parts/header-builder/elements/mobile_logo',
					null,
					array(
						'bimber_header' => 'all',
					)
				);
				?>
			</div>
		</div>
		<div class="g1-row-background">
		</div>
	</div>

	<div id="page">
		<div id="primary" class="g1-primary-max">
			<div id="content" role="main">

				<?php while ( have_posts() ) : the_post();?>

					<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-tpl-interpage' ); ?> itemscope=""
					itemtype="<?php echo esc_attr( bimber_get_entry_microdata_itemtype() ); ?>">
						<div class="g1-row g1-row-layout-page g1-row-padding-m">
							<div class="g1-row-inner">
								<div class="g1-column">
									<div class="entry-content" itemprop="text">
										<?php the_content(); ?>
									</div>
								</div>
							</div>
						</div>
					</article>

				<?php endwhile;?>

			</div><!-- #content -->
		</div><!-- #primary -->
	</div>
</div>
<?php wp_footer(); ?>
</body>
</html>

