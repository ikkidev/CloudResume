<?php
/**
 * New image page
 *
 * @package photomix
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

$photomix_svg_base_url = trailingslashit( photomix_get_plugin_url() ) . 'modules/image-editor/assets/images/';
?>
<span class="photomix-header-label"><?php esc_html_e( 'Sticker', 'photomix' ); ?></span>
<div class="photomix-library">

	<div class="photomix-library-menu-button photomix-header-button">
		<span class="photomix-libray-toggle photomix-icon">
		</span>

			<div class="photomix-library-dropdown">
				<div class="photomix-library-tabs">
					<a class="photomix-library-tab photomix-library-tab-current" href=""><?php esc_html_e( 'Markers', 'photomix' ); ?></a>
					<?php /* <a class="photomix-library-tab" href=""><?php esc_html_e( 'Emoticons', 'photomix' ); ?></a> */ ?>
					<a class="photomix-library-tab" href=""><?php esc_html_e( 'Shapes', 'photomix' ); ?></a>
					<a class="photomix-library-tab" href=""><?php esc_html_e( 'Texts', 'photomix' ); ?></a>
				</div>

				<div class="photomix-library-panels">
					<div class="photomix-library-panel photomix-library-panel-current">
						<ul class="photomix-items">
							<li class="photomix-item">
								<a href="#" data-photomix-object-id="arrow">
									<img width="40" height="40" src="<?php echo esc_url( $photomix_svg_base_url . 'left-arrow.svg' ); ?>" alt="" />
									<span><?php esc_html_e( 'Arrow', 'mace' ); ?></span>
								</a>
							</li>
							<li class="photomix-item">
								<a href="#" data-photomix-object-id="circle">
									<img width="40" height="40" src="<?php echo esc_url( $photomix_svg_base_url . 'circle.svg' ); ?>" alt="" />
									<span><?php esc_html_e( 'Circle', 'mace' ); ?></span>
								</a>
							</li>
						</ul>
					</div>
					<?php
					/*
                    <div class="photomix-library-panel">
						<ul class="photomix-items">
							<li class="photomix-item">
								<a href="#" data-photomix-object-id="omg">
									<img width="60" height="60" src="<?php echo esc_url( $photomix_svg_base_url . 'left-arrow.svg' ); ?>" alt="" />
									<span><?php esc_html_e( 'OMG', 'mace' ); ?></span>
								</a>
							</li>
							<li class="photomix-item">
								<a href="#" data-photomix-object-id="wtf">
									<img width="60" height="60" src="<?php echo esc_url( $photomix_svg_base_url . 'left-arrow.svg' ); ?>" alt="" />
									<span><?php esc_html_e( 'WTF', 'mace' ); ?></span>
								</a>
							</li>
							<li class="photomix-item">
								<a href="#" data-photomix-object-id="lol">
									<img width="60" height="60" src="<?php echo esc_url( $photomix_svg_base_url . 'left-arrow.svg' ); ?>" alt="" />
									<span><?php esc_html_e( 'LOL', 'mace' ); ?></span>
								</a>
							</li>
						</ul>
					</div>
                    */ ?>
					<div class="photomix-library-panel">
						<ul class="photomix-items">
							<li class="photomix-item">
								<a href="" data-photomix-object-id="heart">
									<img width="40" height="40" src="<?php echo esc_url( $photomix_svg_base_url . 'heart.svg' ); ?>" alt="" />
									<span><?php esc_html_e( 'Heart', 'mace' ); ?></span>
								</a>
							</li>
							<li class="photomix-item">
								<a href="" data-photomix-object-id="heart-broken">
									<img width="40" height="40" src="<?php echo esc_url( $photomix_svg_base_url . 'heart-broken.svg' ); ?>" alt="" />
									<span><?php esc_html_e( 'Broken Heart', 'mace' ); ?></span>
								</a>
							</li>
							<li class="photomix-item">
								<a href="" data-photomix-object-id="star">
									<img width="40" height="40" src="<?php echo esc_url( $photomix_svg_base_url . 'star.svg' ); ?>" alt="" />
									<span><?php esc_html_e( 'Star', 'mace' ); ?></span>
								</a>
							</li>
							<li class="photomix-item">
								<a href="" data-photomix-object-id="thunder">
									<img width="40" height="40" src="<?php echo esc_url( $photomix_svg_base_url . 'thunder.svg' ); ?>" alt="" />
									<span><?php esc_html_e( 'Thunder', 'mace' ); ?></span>
								</a>
							</li>
						</ul>
					</div>
					<div class="photomix-library-panel">
						<ul class="photomix-items">
							<li class="photomix-item">
								<a href="" data-photomix-object-id="versus">
									<img width="40" height="40" src="<?php echo esc_url( $photomix_svg_base_url . 'vs-v01.svg' ); ?>" alt="" />
									<span><?php esc_html_e( 'Versus', 'mace' ); ?></span>
								</a>
							</li>
							<li class="photomix-item">
								<a href="" data-photomix-object-id="question-mark">
									<img width="40" height="40" src="<?php echo esc_url( $photomix_svg_base_url . 'question-mark-v01.svg' ); ?>" alt="" />
									<span><?php esc_html_e( 'Question Mark', 'mace' ); ?></span>
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>

	</div>

	<?php $photomix_lib_texts_dir_url = trailingslashit( photomix_get_plugin_url() ) . 'modules/image-editor/assets/images/'; ?>
	<div class="photomix-library-active-object photomix-object-not-selected photomix-header-button">
		<span class="photomix-icon photomix-remove-image photomix-remove-object" title="<?php esc_attr_e( 'Remove', 'photomix' ); ?>"><?php esc_html_e( 'Remove', 'mace' ); ?></span>
		<span class="photomix-active-object-id"></span>
	</div>
</div>

