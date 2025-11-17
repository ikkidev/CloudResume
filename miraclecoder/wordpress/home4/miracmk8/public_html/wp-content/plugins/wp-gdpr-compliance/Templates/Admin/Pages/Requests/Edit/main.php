<?php

use WPGDPRC\Objects\RequestAccess;
use WPGDPRC\Utils\Elements;
use WPGDPRC\Utils\Request;
use WPGDPRC\Utils\Template;
use WPGDPRC\WordPress\Plugin;

/**
 * @var string $admin_url
 * @var int    $object_id
 * @var string $email
 * @var string $created
 * @var string $offset
 * @var array  $results
 */
$request = new RequestAccess( $object_id );

$intro = [
	/* translators: %1$1s: email %2$2s: date */
	sprintf( _x( 'This request was submitted by %1$1s on %2$2s.', 'admin', 'wp-gdpr-compliance' ), '<strong>' . $email . '</strong>', '<strong>' . $created . '</strong>' ),
];

$total = 0;
$tiles = [];
foreach ( $results as $data ) {
	$total += ( $data[ Request::KEY_REQUESTED ] - $data[ Request::KEY_PROCESSED ] );
}

if ( $total ) {
	/* translators: %1s: date offset */
	$intro[] = sprintf( _x( 'The data wil be anonymized in %1s when no action is taken.', 'admin', 'wp-gdpr-compliance' ), '<strong>' . $offset . '</strong>' );

	$tiles = [
		[
			'title'  => _x( 'Cancel request', 'admin', 'wp-gdpr-compliance' ),
			'text'   => _x( 'If you cancel this request, the user can not choose anymore which data he/she/they would like to be anonymized. The user can submit a new request though.', 'admin', 'wp-gdpr-compliance' ),
			'footer' => $request->isProcessed() ?
				__( 'All requests have been processed.', 'wp-gdpr-compliance' ) :
                function() {
                    Elements::button(_x( 'Cancel request', 'admin', 'wp-gdpr-compliance' ), [
                        'class' => 'wpgdprc-button wpgdprc-button--alert wpgdprc-button--small',
                        'name'  => 'delete',
                        'type'  => 'submit',
                    ]);
                }
				// '<input type="submit" class="wpgdprc-button wpgdprc-button--alert wpgdprc-button--small" name="delete" value="' . _x( 'Cancel request', 'admin', 'wp-gdpr-compliance' ) . '">',
		],
		[
			'title'  => _x( 'Anonymize all', 'admin', 'wp-gdpr-compliance' ),
			'text'   => _x( 'Anonymize all data that is found for this request.', 'admin', 'wp-gdpr-compliance' ),
			'footer' => $request->isProcessed() ?
				__( 'All requests have been processed.', 'wp-gdpr-compliance' ) :
                function() {
                    Elements::button(_x( 'Anonymize all', 'admin', 'wp-gdpr-compliance' ), [
                        'class' => 'wpgdprc-button wpgdprc-button--white wpgdprc-button--small',
                        'name'  => 'update',
                        'type'  => 'submit',
                    ]);
                }
				// '<input type="submit" class="wpgdprc-button wpgdprc-button--white wpgdprc-button--small" name="update" value="' . _x( 'Anonymize all', 'admin', 'wp-gdpr-compliance' ) . '">',
		],
	];
}

$default = [
	'class'       => 'wpgdprc-tile--text',
	'heading'     => 4,
	'title_class' => 'h6',
];

?>

<article class="wpgdprc-container">
	<div class="wpgdprc-content">
		<header class="wpgdprc-content__header">
			<h2 class="wpgdprc-content__title">
				<?php
					/* translators: %1s: email */
					esc_html( sprintf( _x( 'Request by %1s', 'admin', 'wp-gdpr-compliance' ), $email ) );
				?>
			</h2>
			<p class="wpgdprc-content__text">
				<?php echo wp_kses( implode( ' ', $intro ), \WPGDPRC\Utils\AdminHelper::getAllowedHTMLTags() ); ?>
			</p>
		</header>

		<form method="post" action="">
			<input type="hidden" name="request_id" value="<?php echo esc_attr( $object_id ); ?>">
			<div class="wpgdprc-content__container">
				<section class="wpgdprc-section">
					<div class="wpgdprc-section__header">
						<h3 class="wpgdprc-section__title h3">
							<?php echo wp_kses( _x( 'Found data', 'admin', 'wp-gdpr-compliance' ), \WPGDPRC\Utils\AdminHelper::getAllowedHTMLTags() ); ?>
						</h3>
					</div>
					<div class="wpgdprc-section__container">
						<div class="grid-x grid-margin-x grid-margin-y">
							<?php foreach ( $results as $type => $data ) : ?>
								<div class="cell large-6 xlarge-4">
									<div class="wpgdprc-badge wpgdprc-badge--<?php echo esc_attr( $type ); ?>">
										<div class="wpgdprc-badge__content">
											<h4 class="wpgdprc-badge__title"><?php echo esc_html($data['title']); ?></h4>
											<p class="wpgdprc-badge__text">
												<?php
													/* translators: %1$1s: number of items requested %2$2s: number of items processed */
													echo esc_html(sprintf( _x( '%1$1s entries requested, %2$2s anonymized', 'admin', 'wp-gdpr-compliance' ), $data[ Request::KEY_REQUESTED ], $data[ Request::KEY_PROCESSED ] ));
												?>
											</p>
										</div>
										<div class="wpgdprc-badge__icon">
											<?php Template::renderSvg( $data['icon'] ); ?>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</section>
				<?php if ( ! empty( $tiles ) ) : ?>
				<section class="wpgdprc-tiles">
					<div class="wpgdprc-tiles__header">
						<h3 class="wpgdprc-tiles__title h3">
							<?php echo esc_html( _x( 'Actions', 'admin', 'wp-gdpr-compliance' ) ); ?>
						</h3>
					</div>
					<div class="wpgdprc-tiles__container">
						<div class="grid-x grid-margin-x grid-margin-y">
							<?php foreach ( $tiles as $tile ) : ?>
								<div class="cell large-6 xlarge-3">
									<?php Template::render( 'Admin/tile', array_merge( $default, $tile ) ); ?>
								</div>
							<?php endforeach ?>
						</div>
					</div>
				</section>
				<?php endif; ?>
			</div>
		</form>
	</div>
</article>
