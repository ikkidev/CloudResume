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
<?php if ( is_sorting_enabled() ) : ?>
    <div class="cace-comments-filter">
        <label>
            <?php esc_html_e( 'Sort by', 'cace' ); ?>

            <?php $sort_types = get_enabled_sort_types(); ?>
            <select id="cace-comment-order" data-tpl-url="<?php echo esc_url( add_query_arg( array( 'comment-order' => '%ORDER%' ), get_permalink() ) . '#comments' ); ?>">
                <?php foreach ( $sort_types as $type_id => $type_data ): ?>
                    <option value="<?php echo esc_attr( $type_id ); ?>" <?php selected( $type_id, $args['cace_order'] ); ?>><?php echo esc_html( $type_data['label'] ) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
    </div>
<?php endif; ?>