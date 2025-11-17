<?php

/**
 * @var string $status
 * @var string $text
 * @var string $class
 */
$class = ! empty( $class ) ? $class : '';
?>

<span class="wpgdprc-label wpgdprc-label--<?php echo esc_attr( $status ); ?> <?php echo esc_attr( $class ); ?>"><?php echo esc_html( $text ); ?></span>
