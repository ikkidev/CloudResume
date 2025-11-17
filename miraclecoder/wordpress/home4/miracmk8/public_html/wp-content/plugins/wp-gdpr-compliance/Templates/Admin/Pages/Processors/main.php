<?php

use WPGDPRC\Objects\DataProcessor;
use WPGDPRC\Utils\Elements;
use WPGDPRC\Utils\Template;
use WPGDPRC\WordPress\Admin\Pages\PageDashboard;
use WPGDPRC\WordPress\Plugin;

/**
 * @var string $current
 * @var string $title
 * @var string $intro
 */

$prefix = ! empty( $current ) ? $current : PageDashboard::TAB_PROCESSORS;

if ( isset( $_GET['new'] ) || isset( $_GET['edit'] ) ) {
	$id = ! empty( $_GET['edit'] ) ? (int) $_GET['edit'] : 0;
	Template::render(
		'Admin/Pages/Processors/Edit/main',
		[
			'current' => $prefix,
			'id'      => $id,
			'object'  => new DataProcessor( $id ),
		]
	);
	return;
}

$list = DataProcessor::listObjects();
$tabs = [
	'active'   => [
		/* translators: %1s: number of active data processor */
		'title'    => sprintf( _x( 'Active data processors (%1$s)', 'admin', 'wp-gdpr-compliance' ), count( $list['active'] ) ),
		'selected' => 'true',
		'hidden'   => 'false',
		'list'     => $list['active'],
	],
	'disabled' => [
		/* translators: %1s: number of disabled data processor */
		'title'    => sprintf( _x( 'Disabled data processors (%1$s)', 'admin', 'wp-gdpr-compliance' ), count( $list['disabled'] ) ),
		'selected' => 'false',
		'hidden'   => 'true',
		'list'     => $list['disabled'],
	],
];

?>

<form method="post" action="<?php echo esc_url( PageDashboard::getFormAction( $current ) ); ?>">
	<input type="hidden" name="tab" value="<?php echo esc_attr( $current ); ?>"/>
	<header class="wpgdprc-content__header">
		<h2 class="wpgdprc-content__title">
			<?php echo esc_html( $title ); ?>
		</h2>
		<p class="wpgdprc-content__text">
			<?php echo wp_kses_post( $intro );?>
		</p>
	</header>

	<div class="wpgdprc-content__container">
		<section class="wpgdprc-tabs wpgdprc-tabs--consents" data-tabs>
			<div class="wpgdprc-tabs__header">
				<div class="wpgdprc-tabs__title-container">
					<h3 class="wpgdprc-tabs__title h3"><?php echo esc_html_x( 'Data processors', 'admin', 'wp-gdpr-compliance' ); ?></h3>
					<?php
					Elements::link(
						add_query_arg( [ 'new' => '' ], PageDashboard::getTabUrl( PageDashboard::TAB_PROCESSORS ) ),
						_x( 'Add data processor', 'admin', 'wp-gdpr-compliance' ),
						[
							'class' => 'wpgdprc-button wpgdprc-button--white wpgdprc-button--small wpgdprc-button--border',
							'role'  => 'button',
						]
					);
					?>
				</div>
				<ul class="wpgdprc-tabs__list" role="tablist">
					<?php foreach ( $tabs as $type => $data ) : ?>
						<li class="wpgdprc-tabs__item" role="presentation">
							<button class="wpgdprc-button wpgdprc-button--transparent wpgdprc-button--small wpgdprc-tabs__anchor"
									data-title="<?php echo esc_attr( $data['title'] ); ?>"
									id="tab-<?php echo esc_attr( $type ); ?>-processors"
									role="tab"
									aria-controls="<?php echo esc_attr( $type ); ?>-processors"
									aria-selected="<?php echo esc_attr( $data['selected'] ); ?>" tabindex="0">
								<?php echo esc_html( $data['title'] ); ?>
							</button>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>

			<div class="wpgdprc-tabs__container">
				<?php foreach ( $tabs as $type => $data ) : ?>
					<div class="wpgdprc-tabs__block wpgdprc-tabs__block--<?php esc_attr( $type ); ?>" id="<?php echo esc_attr( $type ); ?>-processors" role="tabpanel" aria-labelledby="tab-<?php echo esc_attr( $type ); ?>-processors" aria-hidden="<?php echo esc_attr( $data['hidden'] ); ?>">
						<?php
						foreach ( $data['list'] as $id => $object ) {
							Template::render(
								'Admin/Pages/Processors/item',
								[
									'object' => $object,
								]
							);
						}
						?>
					</div>
				<?php endforeach; ?>
			</div>
		</section>

		<?php
		Template::render(
			'Admin/Pages/Processors/footer',
			[
				'current' => $current,
			]
		);
		?>

	</div>
</form>
