<?php

namespace NewfoldLabs\WP\Module\LinkTracker\Functions;

/**
 * Builds a URL with query parameters.
 *
 * @param string $url The URL to which parameters will be appended.
 * @param array  $params An associative array of query parameters.
 * @return string The complete URL with query parameters.
 */
function build_link( string $url, $params = array() ) {
	return apply_filters(
		'nfd_build_url',
		$url,
		$params
	);
}
