<?php
/**
 * Template list template part
 *
 * @package photomix
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

$photomix_icons_uri = trailingslashit( photomix_get_plugin_url() ) . 'icon/';

?>

<div class="photomix-workspace">
	<div class="photomix-templates">
		<ul class="photomix-template-items">
			<li class="photomix-template-item">
				<a class="photomix-template" href="<?php echo esc_url( add_query_arg( array( 'photomix-template' => 'one-image' ) ) ); ?>">
					<img width="60" height="60" src="<?php echo esc_url( $photomix_icons_uri . 'tpl-1-01.svg' ); ?>" alt="" />
				</a>
			</li>
			<li class="photomix-template-item">
				<a class="photomix-template" href="<?php echo esc_url( add_query_arg( array( 'photomix-template' => 'two-equal-images' ) ) ); ?>">
					<img width="60" height="60" src="<?php echo esc_url( $photomix_icons_uri . 'tpl-2-01.svg' ); ?>" alt="" />
				</a>
			</li>
			<li class="photomix-template-item">
				<a class="photomix-template" href="<?php echo esc_url( add_query_arg( array( 'photomix-template' => 'three-equal-images' ) ) ); ?>">
					<img width="60" height="60" src="<?php echo esc_url( $photomix_icons_uri . 'tpl-3-01.svg' ); ?>" alt="" />
				</a>
			</li>
			<li class="photomix-template-item">
				<a class="photomix-template" href="<?php echo esc_url( add_query_arg( array( 'photomix-template' => 'four-equal-images' ) ) ); ?>">
					<img width="60" height="60" src="<?php echo esc_url( $photomix_icons_uri . 'tpl-4-01.svg' ); ?>" alt="" />
				</a>
			</li>

			<li class="photomix-template-item">
				<a class="photomix-template" href="<?php echo esc_url( add_query_arg( array( 'photomix-template' => 'square-window' ) ) ); ?>">
					<img width="60" height="60" src="<?php echo esc_url( $photomix_icons_uri . 'tpl-2-04.svg' ); ?>" alt="" />
				</a>
			</li>
			<li class="photomix-template-item">
				<a class="photomix-template" href="<?php echo esc_url( add_query_arg( array( 'photomix-template' => 'blurred-image' ) ) ); ?>">
					<img width="60" height="60" src="<?php echo esc_url( $photomix_icons_uri . 'tpl-1-02.svg' ); ?>" alt="" />
				</a>
			</li>

			<li class="photomix-template-item">
				<a class="photomix-template" href="<?php echo esc_url( add_query_arg( array( 'photomix-template' => 'two-slash-divided' ) ) ); ?>">
					<img width="60" height="60" src="<?php echo esc_url( $photomix_icons_uri . 'tpl-2-02.svg' ); ?>" alt="" />
				</a>
			</li>
			<li class="photomix-template-item">
				<a class="photomix-template" href="<?php echo esc_url( add_query_arg( array( 'photomix-template' => 'two-zigzag-divided' ) ) ); ?>">
					<img width="60" height="60" src="<?php echo esc_url( $photomix_icons_uri . 'tpl-2-03.svg' ); ?>" alt="" />
				</a>
			</li>
			<li class="photomix-template-item">
				<a class="photomix-template" href="<?php echo esc_url( add_query_arg( array( 'photomix-template' => 'two-lightning-divided' ) ) ); ?>">
					<img width="60" height="60" src="<?php echo esc_url( $photomix_icons_uri . 'tpl-2-05.svg' ); ?>" alt="" />
				</a>
			</li>
		</ul>
	</div>
</div>

