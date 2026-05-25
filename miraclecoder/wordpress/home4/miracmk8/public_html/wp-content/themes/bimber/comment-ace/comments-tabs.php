<?php
/**
 * Comments tabs template.
 *
 * @package Commentace
 */

namespace Commentace;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
?>

<div class="cace-comments-tabs cace-tabs">
    <ul class="cace-tab-items g1-tab-items">
        <?php $cace_tab_index = 0; ?>
        <?php foreach ( plugin()->comments()->get_types() as $cace_type ) : ?>
            <?php
            $cace_tab_classes = array(
                'cace-tab-item',
	            'cace-tab-item-' . $cace_type->get_id(),
                'g1-tab-item',
                'g1-tab-item-' . $cace_type->get_id(),
            );

            $cace_comment_count = $cace_type->get_comment_count();

            if ( 0 === $cace_tab_index++ ) {
                $cace_tab_classes[] = 'cace-tab-item-current';
                $cace_tab_classes[] = 'g1-tab-item-current';
            }
            ?>

            <li class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $cace_tab_classes ) ); ?>" data-comment-type="<?php echo esc_attr( $cace_type->get_id() ); ?>">
                <a class="cace-tab g1-tab">
                    <?php echo wp_kses_post( $cace_type->get_name() ); ?>
                    <?php if ( $cace_comment_count ) : ?>
                        <span class="count"><?php echo number_format_i18n( $cace_comment_count ); ?></span>
                    <?php endif; ?>
                </a>
            </li>

        <?php endforeach; ?>
    </ul>
</div>


