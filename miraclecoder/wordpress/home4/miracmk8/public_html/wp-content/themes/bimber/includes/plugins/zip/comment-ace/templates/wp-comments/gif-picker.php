<?php
/**
 * WordPress comments template.
 *
 * @package Commentace
 */

namespace Commentace;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
?>
<div class="cace-drop cace-drop-the-gifpicker">
    <button class="cace-drop-toggle" type="button" title="<?php esc_attr_e( 'Reply with GIF', 'cace' ); ?>">
        <?php
        echo wp_kses(
            __( 'Reply with <abbr>GIF</abbr>', 'cace' ),
            array(
                'abbr' => array(),
            )
        );
        ?>
    </button>
    <div class="cace-drop-content">
        <?php if ( ! get_giphy_app_key() ): ?>
            <p class="cace-giphy-app-key-not-set">
                <?php printf(
                    esc_html__( 'GIPHY App Key not set. Please check %s', 'cace' ),
                    '<a href="'. esc_url( admin_url( 'admin.php?page=cace-settings-wp-comment-form' ) ) .'" target="_blank">'. esc_html__( 'settings', 'cace' ) .'</a>' ); ?>
            </p>
        <?php else: ?>

        <div class="cace-gifpicker">
            <div class="cace-gifpicker-header">
                <div class="cace-gifpicker-filters">
                    <div class="cace-gifpicker-filter-search">
                        <input type="text" class="cace-gifpicker-search-field" placeholder="<?php _e( 'Search GIFs&hellip;', 'cace' ) ?>" />
                    </div>
                </div>
            </div>
            <div class="cace-gifpicker-body">
                <ul class="cace-gif-items">
                </ul>
            </div>
        </div>

        <?php endif; ?>
    </div>
</div>
