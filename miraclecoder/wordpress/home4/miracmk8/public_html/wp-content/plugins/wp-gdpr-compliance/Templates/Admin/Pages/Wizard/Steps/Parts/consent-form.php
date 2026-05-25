<?php

use WPGDPRC\Objects\DataProcessor;
use WPGDPRC\Utils\Template;
use WPGDPRC\WordPress\Admin\Pages\PageDashboard;

$id = isset( $id ) && is_numeric( $id ) ? intval( $id ) : 0;
Template::render(
	'Admin/Pages/Processors/Edit/main',
	[
		'current' => PageDashboard::TAB_PROCESSORS,
		'id'      => $id,
		'object'  => new DataProcessor( $id ),
	]
);
