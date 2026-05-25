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

<div class="photomix-editor">
	<canvas id="photomix-canvas" width="<?php echo absint( $photomix_editor['width'] ) ?>" height="<?php echo absint( $photomix_editor['height'] ); ?>"></canvas>

	<div class="photomix-actions-wrapper">
		<div class="photomix-image-actions photomix-image-actions-disabled photomix-image-front">

			<?php photomix_get_template_part( 'image-editor/image-actions', 'blurred' ) ?>

		</div>
	</div>
</div>

<script type="text/javascript">
	(function(ctx) {
		var canvasWidth     = parseInt(<?php echo absint( $photomix_editor['width'] ); ?>, 10);
		var canvasHeight    = parseInt(<?php echo absint( $photomix_editor['height'] ); ?>, 10);

		var imageConfig  = <?php echo photomix_get_mask_js_config( 'front' ); ?>;

		// Custom config.
		// --------------

		imageConfig.size = 'contain';

		imageConfig.fabricConfig = {
			lockMovementX: true,
			lockMovementY: true
		};

		imageConfig.onAdded = function(fabricImage, obj) {
			var fabricCanvas = obj.getFabricCanvas();

			fabricCanvas.centerObject(fabricImage);

			var blurredImage = fabric.util.object.clone(fabricImage);
			var scale = obj.getImageScaleFactor(fabricImage, obj.getFabricMask(), 'cover');

			var filter = new fabric.Image.filters.GaussianBlur(6);
			blurredImage.filters.push(filter);
			blurredImage.applyFilters(fabricCanvas.renderAll.bind(fabricCanvas));

			blurredImage.set({
				left:   0,
				top:    0,
				scaleX: scale,
				scaleY: scale
			});

			fabricCanvas.add(blurredImage);
			fabricCanvas.sendBackwards(blurredImage);

			obj.blurredImage = blurredImage;
		};

		imageConfig.onRemove = function(fabricImage, obj) {
			var fabricCanvas = obj.getFabricCanvas();

			fabricCanvas.remove(obj.blurredImage);
			obj.blurredImage = null;
		};

		imageConfig.onFlipped = function(fabricImage, obj) {
			var fabricCanvas = obj.getFabricCanvas();

			obj.blurredImage.flipX = fabricImage.flipX;
		};

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
				front: {
					width: 		canvasWidth,
					height: 	canvasHeight,
					left:		0,
					top:		0,
					fill:		'<?php echo sanitize_hex_color( $photomix_editor['background_color'] ) ?>',
					image:      imageConfig
				}
			}
		};
	})(window);
</script>
