<?php

/**
 * Renders a select field.
 * @var array $args
 * @var array $choices
 * @var string $selected
 * @var string $name
 * @var string $id
 * @var string $class
 */

use WPGDPRC\Utils\AdminForm;
?>

<select name="<?php echo esc_attr($name) ?>" class="<?php echo esc_attr($class) ?>" id="<?php echo esc_attr($id) ?>" <?php echo AdminForm::buildAttributes( $args ); // PHPCS: XSS ok; ?> >

    <?php foreach ( $choices as $current_value => $text ): ?>
        <option value="<?php echo esc_attr($current_value) ?>" <?php echo AdminForm::isSelected( $selected, $current_value ) // PHPCS: XSS ok;  ?>>
            <?php echo esc_html($text); ?>
        </option>
    <?php endforeach; ?>

</select>
