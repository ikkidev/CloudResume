<div class="snax-gdpr-consent-form snax-gdpr-consent-form-hidden">
	<h4 class="g1-epsilon g1-epsilon-1st"><?php esc_html_e( 'Privacy Policy', 'snax' ); ?></h4>

	<p><?php
		if ( function_exists( 'snax_gdpr_render_consent_text' ) ) {
			snax_gdpr_render_consent_text();
		}
		?>
	</p>

	<p>
		<button class="snax-slog-gdpr-cancel g1-button g1-button-subtle g1-button-m"><?php esc_html_e( 'Cancel', 'snax' ); ?></button>
		<button class="snax-slog-gdpr-accept g1-button g1-button-solid g1-button-m"><?php esc_html_e( 'Accept', 'snax' ); ?></button>
	</p>
</div>