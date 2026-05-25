<?php
/**
 * Image editor template part
 *
 * @package photomix
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

if ( ! current_user_can('upload_files') ) {
	wp_die(__('Sorry, you are not allowed to upload files.'));
}

$photomix_editor = photomix_get_editor_config();
?>

<div class="photomix-editor photomix-tpl-4-01">
	<canvas id="photomix-canvas" width="<?php echo absint( $photomix_editor['width'] ) ?>" height="<?php echo absint( $photomix_editor['height'] ); ?>"></canvas>

	<div class="photomix-actions-wrapper">
		<div class="photomix-image-actions photomix-image-actions-disabled photomix-image-first">

			<?php photomix_get_template_part( 'image-editor/image-actions' ) ?>

		</div>

		<div class="photomix-image-actions photomix-image-actions-disabled photomix-image-second">

			<?php photomix_get_template_part( 'image-editor/image-actions' ) ?>

		</div>

		<div class="photomix-image-actions photomix-image-actions-disabled photomix-image-third">

			<?php photomix_get_template_part( 'image-editor/image-actions' ) ?>

		</div>
		<div class="photomix-image-actions photomix-image-actions-disabled photomix-image-fourth">

			<?php photomix_get_template_part( 'image-editor/image-actions' ) ?>

		</div>
	</div>
</div>

<script type="text/javascript">
	(function(ctx) {
		var canvasWidth  = parseInt(<?php echo absint( $photomix_editor['width'] ); ?>, 10);
		var canvasHeight = parseInt(<?php echo absint( $photomix_editor['height'] ); ?>, 10);
		var gapWidth 	 = parseInt(<?php echo absint( $photomix_editor['gutter_on'] ? $photomix_editor['width'] / 100 : 0 ); ?>, 10);
		var maskWidth 	 = Math.round( ( canvasWidth - 3 * gapWidth ) / 4 );

		ctx.photomixEditorConfig = {
			canvas: {
				backgroundColor: '<?php echo sanitize_hex_color( $photomix_editor['gutter_on'] ? $photomix_editor['gutter_color'] : '' ); ?>',
				objects:         <?php echo photomix_get_objects_js_config(); ?>,
				shapes:          {
					'color': '<?php echo sanitize_hex_color( $photomix_editor['shape_color'] ? $photomix_editor['shape_color'] : '' ); ?>'
				},
				icons: {
					'noPhoto':   '<?php echo trailingslashit( photomix_get_plugin_url() ) . 'modules/image-editor/assets/images/no-photo.svg'; ?>'
				}
			},
			masks: {
				first: {
					width: 		maskWidth,
					height: 	canvasHeight,
					left:		0,
					top:		0,
					fill:		'<?php echo sanitize_hex_color( $photomix_editor['background_color'] ) ?>',
					image:      <?php echo photomix_get_mask_js_config( 'first' ); ?>
				},
				second: {
					width: 		maskWidth,
					height: 	canvasHeight,
					left:		maskWidth + gapWidth,
					top:		0,
					fill:		'<?php echo sanitize_hex_color( $photomix_editor['background_color'] ) ?>',
					image:      <?php echo photomix_get_mask_js_config( 'second' ); ?>
				},
				third: {
					width: 		maskWidth,
					height: 	canvasHeight,
					left:		2 * (maskWidth + gapWidth),
					top:		0,
					fill:		'<?php echo sanitize_hex_color( $photomix_editor['background_color'] ) ?>',
					image:      <?php echo photomix_get_mask_js_config( 'third' ); ?>
				},
				fourth: {
					width: 		maskWidth,
					height: 	canvasHeight,
					left:		3 * (maskWidth + gapWidth),
					top:		0,
					fill:		'<?php echo sanitize_hex_color( $photomix_editor['background_color'] ) ?>',
					image:      <?php echo photomix_get_mask_js_config( 'fourth' ); ?>
				}
			}
		};
	})(window);
</script>
