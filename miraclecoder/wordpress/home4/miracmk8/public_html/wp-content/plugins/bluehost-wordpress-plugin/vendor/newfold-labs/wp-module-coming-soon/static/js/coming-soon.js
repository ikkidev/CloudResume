{
	const API_ENDPOINT = window.NewfoldRuntime.restUrl + 'newfold-coming-soon/v1';
	const { __ } = wp.i18n;

	const attachToRuntime = () => {
		window.NewfoldRuntime.comingSoon = buildObject();
	};

	const buildObject = () => {
		return {
			isEnabled,
			enable,
			disable,
			lastChanged,
			toggleAdminBarSiteStatus,
		};
	};

	const isEnabled = async () => {
		let status;

		await window.wp
			.apiFetch( {
				url: `${ API_ENDPOINT }/status`,
				method: 'GET',
			} )
			.then( ( response ) => {
				if ( response.hasOwnProperty( 'comingSoon' ) ) {
					status = response.comingSoon;
				} else {
					status = null;
				}
			} )
			.catch( () => {
				status = null;
			} );

		return status;
	};

	const enable = async () => {
		const result = {};

		await window.wp
			.apiFetch( {
				url: `${ API_ENDPOINT }/enable`,
				method: 'POST',
			} )
			.then( ( response ) => {
				if ( response.hasOwnProperty( 'comingSoon' ) ) {
					result.success = true;
					result.comingSoon = response.comingSoon;
					toggleAdminBarSiteStatus( true );
				} else {
					result.success = false;
				}
			} )
			.catch( () => {
				result.success = false;
			} );

		return result;
	};

	const disable = async () => {
		const result = {};

		await window.wp
			.apiFetch( {
				url: `${ API_ENDPOINT }/disable`,
				method: 'POST',
			} )
			.then( ( response ) => {
				if ( response.hasOwnProperty( 'comingSoon' ) ) {
					result.success = true;
					result.comingSoon = response.comingSoon;
					toggleAdminBarSiteStatus( false );
				} else {
					result.success = false;
				}
			} )
			.catch( () => {
				result.success = false;
			} );

		return result;
	};

	const lastChanged = async () => {
		let value;

		await window.wp
			.apiFetch( {
				url: `${ API_ENDPOINT }/last-changed`,
				method: 'GET',
			} )
			.then( ( response ) => {
				if ( response.hasOwnProperty( 'lastChanged' ) ) {
					value = response.lastChanged;
				} else {
					value = null;
				}
			} )
			.catch( () => {
				value = null;
			} );

		return value;
	};

	/**
	 * Toggle the site status badge in the admin bar.
	 *
	 * @param {boolean} newState The new state of the site status.
	 */
	const toggleAdminBarSiteStatus = ( newState ) => {
		/**
		 * The badge elements for NFD and WooCommerce.
		 * Only one of them will be active at a time.
		 * When WooCommerce is active, the WooCommerce badge will be used.
		 * When WooCommerce is not active, the NFD badge will be used.
		 */
		const badge = {
			nfd: {
				selector: '#wp-toolbar #wp-admin-bar-nfd-site-visibility-badge',
				comingSoon: {
					text: __( 'Coming soon', 'wp-module-coming-soon' ),
					class: 'nfd-site-status-badge-coming-soon',
				},
				live: {
					text: __( 'Live', 'wp-module-coming-soon' ),
					class: 'nfd-site-status-badge-live',
				},
				hidden: {
					class: 'nfd-site-status-badge-hidden',
				},
			},
			woocommerce: {
				selector:
					'#wp-toolbar #wp-admin-bar-woocommerce-site-visibility-badge',
				comingSoon: {
					text: __( 'Coming soon', 'wp-module-coming-soon' ),
					class: 'woocommerce-site-status-badge-coming-soon',
				},
				live: {
					text: __( 'Live', 'wp-module-coming-soon' ),
					class: 'woocommerce-site-status-badge-live',
				},
				hidden: {
					class: 'woocommerce-site-status-badge-hidden',
				},
			},
		};

		const getActiveBadge = () => {
			// Return the WooCommerce badge if WooCommerce is active.
			if ( window.NewfoldRuntime.isWoocommerceActive ) {
				return badge.woocommerce;
			}
			return badge.nfd;
		};
		const activeBadge = getActiveBadge();

		const siteVisibilityBadge = document.querySelector(
			activeBadge.selector
		);

		if ( ! siteVisibilityBadge ) {
			return;
		}

		const toggle = ( newState ) => {
			if ( newState ) {
				// Coming soon
				siteVisibilityBadge.classList.remove(
					activeBadge.live.class,
					activeBadge.hidden.class
				);
				siteVisibilityBadge.classList.add( activeBadge.comingSoon.class );
				const textElement = siteVisibilityBadge.querySelector( 'a.ab-item' );
				if ( textElement ) {
					textElement.textContent = activeBadge.comingSoon.text;
				}
			} else {
				// Live
				siteVisibilityBadge.classList.remove(
					activeBadge.comingSoon.class,
					activeBadge.hidden.class
				);
				siteVisibilityBadge.classList.add( activeBadge.live.class );
				const textElement = siteVisibilityBadge.querySelector( 'a.ab-item' );
				if ( textElement ) {
					textElement.textContent = activeBadge.live.text;
				}
			}
		};
		toggle( newState );
	};

	window.addEventListener( 'DOMContentLoaded', () => {
		attachToRuntime();
	} );
}
