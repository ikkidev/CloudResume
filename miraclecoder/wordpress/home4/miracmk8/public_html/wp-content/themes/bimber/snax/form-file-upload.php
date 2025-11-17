<?php
/**
 * Snax File Upload Form
 *
 * @package snax
 * @subpackage Form
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
global $snax_form_file_upload_args;

$snax_media_type        = $snax_form_file_upload_args['media_type'];
$snax_upload_by_url = $snax_form_file_upload_args['upload_by_url'];
$snax_max_upload_size    = $snax_form_file_upload_args['max_upload_size'];
$featured_image = apply_filters( 'snax_form_file_upload_featured_image', false );
$no_media = apply_filters( 'snax_form_file_upload_no_media', false );
$snax_upload_methods = apply_filters( 'snax_form_file_upload_methods', array(), $snax_media_type );

if ( ! $snax_max_upload_size ) {
	$snax_max_upload_size = 0;
}

if ( $no_media ) {
	$snax_button_class = 'g1-button-m  g1-button-solid';
	$snax_header_class = 'g1-beta g1-beta-1st';
} else {
	$snax_button_class = 'g1-button-s  g1-button-simple';
	$snax_header_class = 'g1-gamma g1-gamma-1st';
}
$snax_format = snax()->get_current_format();
if ( in_array( $snax_format, array( 'text', 'trivia_quiz', 'personality_quiz' ), true ) ) {
		$snax_button_class = 'g1-button-s g1-button-simple';
}
?>
<div class="snax-plupload-upload-ui" class="hide-if-no-js">
	<div class="snax-drag-drop-area">
		<div class="snax-drag-drop-inside">
			<div class="snax-drag-drop-info-area <?php echo $snax_header_class?>">
				<p class="snax-drag-drop-info"><?php
				if ( $featured_image ) {
					echo esc_html__( 'Drop thumbnail here', 'snax' );
				} else {
					switch ( $snax_media_type ) {
						case 'image':
							echo esc_html__( 'Drop Images Here', 'snax' );
							break;
						case 'audio':
							echo esc_html__( 'Drop Audio Here', 'snax' );
							break;
						case 'video':
							echo esc_html__( 'Drop Video Here', 'snax' );
							break;
						default:
							echo esc_html__( 'Drop Files Here', 'snax' );
							break;
					}
				} ?></p>
			</div>
			<p><?php _ex( 'or', 'Uploader: Drop files here - or - Select Files', 'snax' ); ?></p>

			<p class="snax-drag-drop-buttons">
				<input
					type="button"
					value="<?php echo esc_attr( __( 'Browse files', 'snax' ) ); ?>"
					class="snax-plupload-browse-button g1-button <?php echo $snax_button_class?>" />
				<?php if( $snax_upload_by_url ): ?>
				<input
					type="button"
					value="<?php echo esc_attr( __( 'Get image from URL', 'snax' ) ); ?>"
					class="snax-load-form-button g1-button <?php echo $snax_button_class?>"
					data-snax-rel-class="snax-load-image-from-url-area" />
				<?php endif; ?>
				<?php foreach( $snax_upload_methods as $snax_upload_method ): ?>
				<input
					type="button"
					value="<?php echo esc_attr( $snax_upload_method['name'] ); ?>"
					class="snax-load-form-button g1-button <?php echo $snax_button_class?>"
					data-snax-rel-class="<?php echo esc_attr( $snax_upload_method['form_class'] ); ?>" />
				<?php endforeach; ?>
			</p>
		</div>
	</div>
</div>

<p class="hide-if-js">
	<?php esc_html_e( 'You don\'t have javascript enabled. Media upload is not possible.', 'snax' ); ?>
</p>

<?php if( $snax_upload_by_url ): ?>
<div class="snax-load-image-from-url-area">
	<?php esc_html_e( 'Get image from URL', 'snax' ); ?>
	<input type="text" class="snax-load-image-from-url" size="255" placeholder="<?php esc_html_e( 'http://', 'snax' ); ?>" />
		<input
			type="button"
			value="<?php echo esc_attr( __( 'Back', 'snax' ) ); ?>"
			class="snax-load-form-button g1-button <?php echo $snax_button_class?>"
			data-snax-rel-class="snax-load-image-from-url-area" />
</div>
<?php endif; ?>
<?php foreach( $snax_upload_methods as $snax_upload_method ): ?>
	<div class="<?php echo sanitize_html_class( $snax_upload_method['form_class'] ); ?>">
		<?php call_user_func( $snax_upload_method['form_callback'] ); ?>
		<input
			type="button"
			value="<?php echo esc_attr( __( 'Back', 'snax' ) ); ?>"
			class="snax-load-form-button g1-button <?php echo $snax_button_class?>"
			data-snax-rel-class="<?php echo esc_attr( $snax_upload_method['form_class'] ); ?>" />
	</div>
<?php endforeach; ?>

<?php if ( $snax_max_upload_size > 0 ) : ?>
<p class="snax-max-upload-size">
	<?php printf( __( 'Maximum upload file size: %s.', 'snax' ), esc_html( size_format( $snax_max_upload_size ) ) ); ?>
</p>
<?php endif; ?>
