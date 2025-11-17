<?php

use WPGDPRC\Utils\Elements;
use WPGDPRC\Utils\Template;

/**
 * @var string $class
 * @var string $title_class
 * @var string $title
 * @var string $text
 * @var string $link_title
 * @var string $link_url
 * @var string $link_attributes
 */

$link_class = strpos( $class, '--primary' ) ? 'wpgdprc-link--white' : '';
if ( ! empty( $link_title ) && ! empty( $link_url ) ) {

	if ( ! isset( $link_attributes['class'] ) ) {
		$link_attributes['class'] = '';
	}

	$link_attributes['class'] .= ' ' . implode( ' ', [ 'wpgdprc-tile__link', 'wpgdprc-link', esc_attr( $link_class ) ] );
}

Template::render(
	'Admin/tile',
	[
		'class'       => $class,
		'heading'     => 3,
		'title_class' => $title_class,
		'title'       => $title,
		'text'        => $text,
		'footer'      => function() use ($link_attributes, $link_url, $link_title) {
            Elements::link(
                $link_url,
                Template::getIcon( ! empty( $link_class ) ? 'arrow-right' : 'cog' ) . $link_title,
                $link_attributes,
                true
            );
        },
	]
);
