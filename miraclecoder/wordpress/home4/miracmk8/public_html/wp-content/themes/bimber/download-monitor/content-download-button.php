<?php
/**
 * Download button
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/** @var DLM_Download $dlm_download */
?>
<p class="g1-dm-button">
	<a class="g1-button g1-button-solid g1-button-l" href="<?php $dlm_download->the_download_link(); ?>" rel="nofollow">
		<span class="g1-button-icon"></span>
		<?php printf( __( 'Download &ldquo;%s&rdquo;', 'download-monitor' ), $dlm_download->get_title() ); ?>
	</a>
	<span class="g1-meta"><?php echo $dlm_download->get_version()->get_filename(); ?> &ndash; <?php printf( _n( 'Downloaded 1 time', 'Downloaded %d times', $dlm_download->get_download_count(), 'download-monitor' ), $dlm_download->get_download_count() ) ?> &ndash; <?php echo $dlm_download->get_version()->get_filesize_formatted(); ?></span>
</p>