<?php

/**
 * @var $level string
 * @var $attr array
 * @var $text string
 */

?>

<h<?php echo esc_html($level) ?>
    <?php foreach ( $attr as $name => $value ): ?>
        <?php echo esc_html($name) ?>="<?php echo esc_html($value) ?>"
    <?php endforeach; ?>
>
    <?php echo wp_kses_post($text) ?>
</h<?php echo esc_html($level) ?> >
