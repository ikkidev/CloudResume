<?php
/**
 * Theme options "Logs" section
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


$section_id = 'g1ui-settings-section-tracking';

add_settings_section(
	$section_id,                        // ID used to identify this section and with which to register options.
	null,                               // Title to be displayed on the administration page.
	'bimber_render_tracking_description',
	$this->get_page()                   // Page on which to add this section of options.
);

$bimber_amp_tracking_code_placeholder = <<<EOF
<amp-auto-ads
    type="adsense"
    data-ad-client="ca-pub-XXX">
</amp-auto-ads>
EOF;
$bimber_tracking_code_placeholder = <<<EOF
<!-- This is only an example to show what kind of code you can add here -->
<!-- This code WILL NOT be added to your site -->

<!-- Google Analytics -->
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-XXXXX-Y', 'auto');
ga('send', 'pageview');
</script>
<!-- End Google Analytics -->
EOF;

// Section fields.
add_settings_field(
	'tracking_code_head',
	esc_html__( 'Tracking code (in &lsaquo;head&rsaquo;)', 'bimber' ),
	array(
		$this,
		'render_textarea',
	),
	$this->get_page(),
	$section_id,
	array(
		'field_name'    => 'tracking_code_head',
		'default_value' => $bimber_theme_options_defaults['tracking_code_head'],
		'rows'          => 20,
		'cols'          => 80,
		'placeholder'   => $bimber_tracking_code_placeholder,
	)
);

add_settings_field(
	'tracking_code_footer',
	esc_html__( 'Tracking code (after <body>)', 'bimber' ),
	array(
		$this,
		'render_textarea',
	),
	$this->get_page(),
	$section_id,
	array(
		'field_name'    => 'tracking_code_footer',
		'default_value' => $bimber_theme_options_defaults['tracking_code_footer'],
		'rows'          => 20,
		'cols'          => 80,
		'placeholder'   => $bimber_tracking_code_placeholder,
	)
);

if ( bimber_can_use_plugin( 'amp/amp.php' ) ) {
	add_settings_field(
		'tracking_code_amp_head',
		esc_html__( 'AMP Tracking code (in <head>)', 'bimber' ),
		array(
			$this,
			'render_textarea',
		),
		$this->get_page(),
		$section_id,
		array(
			'field_name'    => 'tracking_code_amp_head',
			'default_value' => $bimber_theme_options_defaults['tracking_code_amp_head'],
			'rows'          => 20,
			'cols'          => 80,
			'placeholder'   => $bimber_amp_tracking_code_placeholder,
		)
	);
}

if ( bimber_can_use_plugin( 'amp/amp.php' ) ) {
	add_settings_field(
		'tracking_code_amp',
		esc_html__( 'AMP Tracking code (after <body>)', 'bimber' ),
		array(
			$this,
			'render_textarea',
		),
		$this->get_page(),
		$section_id,
		array(
			'field_name'    => 'tracking_code_amp',
			'default_value' => $bimber_theme_options_defaults['tracking_code_amp'],
			'rows'          => 20,
			'cols'          => 80,
			'placeholder'   => $bimber_amp_tracking_code_placeholder,
		)
	);
}

/**
 * Render section description
 */
function bimber_render_tracking_description() {
	?>
	<h3><?php esc_html_e( 'Tracking', 'bimber' ); ?></h3>
	<?php
}
