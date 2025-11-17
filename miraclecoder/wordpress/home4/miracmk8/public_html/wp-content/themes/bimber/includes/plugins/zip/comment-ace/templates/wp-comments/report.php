<?php
/**
 * "View Replies" button template.
 *
 * @package Commentace
 */

namespace Commentace;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
?>
<script id="cace-report-tpl" type="text/template">
    <form id="cace-report-form">
        <p class="cace-report-form-info">
            <?php echo esc_html_x( 'Please explain the reason why this comment should be removed', 'Reporting', 'cace' ); ?>
        </p>

        <p class="cace-report-form-text">
            <textarea cols="40" rows="4" id="cace-report-text" maxlength="<?php echo absint( get_report_maxlength() ); ?>"></textarea>
        </p>

        <p class="cace-report-form-actions">
            <button id="cace-report-cancel" class="g1-button g1-button-s g1-button-subtle" type="button"><?php echo esc_html_x( 'Cancel', 'Reporting', 'cace' ); ?></button>
            <button id="cace-report-submit" class="g1-button g1-button-s g1-button-solid" type="submit"><?php echo esc_html_x( 'Report', 'Reporting', 'cace' ); ?></button>
        </p>
    </form>
</script>
