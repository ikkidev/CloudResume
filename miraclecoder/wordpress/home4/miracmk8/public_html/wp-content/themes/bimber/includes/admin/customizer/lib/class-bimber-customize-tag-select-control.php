<?php
/**
 * WP Customizer custom control to use tag selection box
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

/**
 * Class Bimber_Customize_Tag_Select_Control
 */
class Bimber_Customize_Tag_Select_Control extends WP_Customize_Control {

	/**
	 * Type of the control
	 *
	 * @var string
	 */
	public $type = 'textarea';

	/**
	 * Render control HTML output
	 */
	public function render_content() {
		?>
		<label>
			<?php if ( ! empty( $this->label ) ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php endif; ?>
			<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
			<?php endif; ?>
			<?php $this->post_tags_meta_box(); ?>
		</label>
		<?php
	}

	private function post_tags_meta_box( $args = array() ) {
		$defaults = array( 'taxonomy' => 'post_tag' );
		$r = wp_parse_args( $args, $defaults );
		$tax_name = esc_attr( $r['taxonomy'] );
		$taxonomy = get_taxonomy( $r['taxonomy'] );
		$user_can_assign_terms = true;
		$comma = _x( ',', 'tag delimiter', 'bimber' );
		$terms_to_edit= $this->value();
		if ( is_array( $terms_to_edit ) ) {
			$terms_to_edit = implode( ',', $terms_to_edit );
		}
		?>
		<div id="tagsdiv-post_tag">
			<div class="inside">
				<div class="tagsdiv" id="<?php echo $tax_name; ?>">
					<div class="jaxtag">
					<div class="nojs-tags hide-if-js">
						<label for="tax-input-<?php echo $tax_name; ?>"><?php echo $taxonomy->labels->add_or_remove_items; ?></label>
						<p><textarea <?php $this->link(); ?> name="<?php echo "tax_input[$tax_name]"; ?>" rows="3" cols="20" class="the-tags" id="tax-input-<?php echo $tax_name; ?>" <?php disabled( ! $user_can_assign_terms ); ?> aria-describedby="new-tag-<?php echo $tax_name; ?>-desc"><?php echo str_replace( ',', $comma . ' ', $terms_to_edit ); // textarea_escaped by esc_attr() ?></textarea></p>
					</div>
					<?php if ( $user_can_assign_terms ) : ?>
					<div class="ui-front ajaxtag hide-if-no-js">
						<label class="screen-reader-text" for="new-tag-<?php echo $tax_name; ?>"><?php echo $taxonomy->labels->add_new_item; ?></label>
						<p><input data-wp-taxonomy="<?php echo $tax_name; ?>" type="text" id="new-tag-<?php echo $tax_name; ?>" name="newtag[<?php echo $tax_name; ?>]" class="g1-newtag newtag form-input-tip" size="16" autocomplete="off" aria-describedby="new-tag-<?php echo $tax_name; ?>-desc" value="" />
						<input type="button" class="button tagadd" value="<?php esc_attr_e( 'Add', 'bimber' ); ?>" /></p>
					</div>
					<?php elseif ( empty( $terms_to_edit ) ): ?>
						<p><?php echo $taxonomy->labels->no_terms; ?></p>
					<?php endif; ?>
					</div>
					<div class="tagchecklist"></div>
				</div>
			</div>
		</div>
		<?php
	}
}
