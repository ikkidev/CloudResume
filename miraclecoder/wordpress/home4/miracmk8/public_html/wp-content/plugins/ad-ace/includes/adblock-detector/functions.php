<?php
/**
 * Common Functions
 *
 * @package AdAce.
 * @subpackage Functions
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

add_action( 'wp_enqueue_scripts', 'adace_adblock_enqueue_scripts' );
add_action( 'wp_footer', 'adace_adblock_detector_check' );

function adace_adblock_detector_enabled() {
	$detector = get_option( 'adace_adblock_detector_enabled', adace_options_get_defaults( 'adace_adblock_detector_enabled' ) );

	return ( 'standard' === $detector );
}

function adace_adblock_enqueue_scripts() {
	if ( ! adace_adblock_detector_enabled() ) {
		return;
	}

	$ver = adace_get_plugin_version();

	wp_enqueue_script( 'adace-adijs-pot', adace_get_plugin_url() . '/includes/adblock-detector/advertisement.js', array(), $ver );
}

function adace_adblock_detector_check() {
	$trigger_alert  = apply_filters( 'adace_adblock_trigger_alert', false );

	if ( ! adace_adblock_detector_enabled() && ! $trigger_alert ) {
		return;
	}

	adace_get_template_part( 'adblock-detector' );
	$page 			= get_option( 'adace_adblock_detector_page', adace_options_get_defaults( 'adace_adblock_detector_page' ) );
	if ( '-1' !== $page && is_page( $page ) ) {
		return;
	}
	?>
	<script>
 		!function(t){"use strict";var e;t.adi=function(n){var i=t.extend({},e.defaults,n);return new e(i)},(e=function(e){t.extend(this,e),this._check()&&(this._init(),this.active()),this._check()||this.inactive()}).prototype._check=function(){return void 0===t.adblock},e.prototype._init=function(){this._append()},e.prototype._setTemplate=function(t,e){return'<div class="jquery-adi"><div class="jquery-adi_content"><button class="jquery-adi_close"></button><h2>'+t+"</h2><p>"+e+"</p></div></div>"},e.prototype._append=function(e){this.$el=t(this._setTemplate(this.title,this.content)).appendTo(t(document.body)).addClass(this.theme),this._show()},e.prototype._show=function(){this.$el.show(),this._center(),this._controls(),this.onOpen(this.$el)},e.prototype._controls=function(){var e=this;function n(){e.$el.hide(),e.onClose(e.$el)}this.$el.on("click",".jquery-adi_close",n),t(document).on("keyup",function(t){27==t.keyCode&&n()})},e.prototype._center=function(){var t=this.$el.find(".jquery-adi_content");t.css("margin-top",-Math.abs(t.outerHeight()/2))},e.defaults={title:"Adblock detected!",content:"We noticed that you may have an Ad Blocker turned on. Please be aware that our site is best experienced with Ad Blockers turned off.",theme:"light",onOpen:function(){},onClose:function(){},active:function(){},inactive:function(){}}}(jQuery);

		(function ($) {
			"use strict";

			<?php if ( $trigger_alert ) : ?>
			var triggerAlert = true;
			<?php else: ?>
			var triggerAlert = false;
			<?php endif; ?>

			var adblockDetectedAlert = function() {
				$('html').addClass('adace-show-popup-detector');
				$('.adace-detector-button-refresh').on('click', function(){
					window.location.reload();
				});
				$('.adace-popup-close').on('click', function(){
					console.log('e');
					$('html').removeClass('adace-show-popup-detector');
				});
			};

			$(document).ready(function () {
				// Only when the AdBlocker is enabled this script will be available.
				if ( typeof $.adi === 'function' ) {
					$.adi({
						onOpen: function (el) {
							adblockDetectedAlert();
						}
					});
				}

				// Show alert on demand. AdBlocker can be disabled.
				if (triggerAlert) {
					adblockDetectedAlert();
				}
			});
		})(jQuery);
	</script>
	<?php
}

/**
 * Add a post display state for special AdBlocker pages in the page list table
 *
 * @param array   $post_states  An array of post display states.
 * @param WP_Post $post         The current post object.
 *
 * @return array
 */
function adace_add_display_post_states( $post_states, $post ) {
    // Link landing page.
    if ( $post->ID === (int) get_option( 'adace_adblock_detector_page' ) ) {
        $post_states['adace_how_to_disable_adblockers_page'] = _x( 'AdAce, How to Disable Adblockers Page', 'Admin page label', 'adace' );
    }

    return $post_states;
}
