<?php
/**
 * The Template Part for displaying "About Author" box.
 *
 * For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme 5.3.2
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}


?>
<?php
	$author_bio = apply_filters( 'bimber_author_info_box_bio', get_the_author_meta( 'description' ), get_the_author_meta( 'ID' ) );
	$classes = apply_filters( 'bimber_author_info_box_class', array( 'g1-row', 'author-info' ) );
	if ( post_type_supports( get_post_type(), 'author' ) ) : ?>
	<section class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $classes ) ); ?>" itemscope="" itemtype="http://schema.org/Person">
		<div class="g1-row-inner author-info-inner">
			<div class="g1-column author-overview">

				<figure class="author-avatar">
					<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
						<?php
							echo get_avatar( get_the_author_meta( 'email' ), 80 );
							do_action( 'bimber_author_box_after_avatar', get_the_author_meta( 'ID' ) );
						?>

					</a>
				</figure>

				<header>
					<?php
						global $allowedposttags;
						$bimber_allowedposttags = $allowedposttags;
						$bimber_allowedposttags['span']['itemprop'] = true;
						$bimber_written_by_tpl = apply_filters( 'bimber_written_by_author_tpl', __( 'Written by <a href="%s"><span itemprop="name" >%s</span></a>', 'bimber' ) );
					?>
					<h2 class="g1-gamma g1-gamma-1st author-title"><?php printf( wp_kses( $bimber_written_by_tpl, $bimber_allowedposttags ), esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ), get_the_author() ); ?></h2>

					<?php
						if ( function_exists( 'bp_follow_add_follow_button' ) ) {
							if ( bimber_can_use_plugin( 'amp/amp.php' ) && is_amp_endpoint() ) {
								// Don't render anything on AMP pages.
							} else {
								// The button won't show up if a user is logged out.
								// That's why need fake (hopefully) user ID.
								$bimber_follower_id = bp_loggedin_user_id();
								$bimber_follower_id = $bimber_follower_id ? $bimber_follower_id : 999999999;

								bp_follow_add_follow_button( array(
									'leader_id'   => get_the_author_meta( 'ID' ),
									'follower_id' => $bimber_follower_id,
									'link_class'  => 'g1-button g1-button-simple g1-button-xs g1-bp-action',
									'wrapper'     => '',
								) );
							}
						}
					?>
				</header>

				<?php if ( ! empty( $author_bio ) ): ?>
				<div itemprop="description" class="author-bio">
					<?php echo wp_kses_post( wpautop( $author_bio ) ); ?>
				</div>
				<?php endif; ?>

				<div class="author-extras">
					<?php
						do_action( 'bimber_author_after_name' );

						if ( bimber_can_use_plugin( 'g1-socials/g1-socials.php' ) && apply_filters( 'bimber_display_g1_socials_in_author_box', true ) ) {
							echo do_shortcode( '[g1_socials_user user="' . get_the_author_meta( 'ID' ) . '" icon_size="28" icon_color="text"]' );
						}
					?>
				</div>
			</div>
		</div>
	</section>
<?php endif;
