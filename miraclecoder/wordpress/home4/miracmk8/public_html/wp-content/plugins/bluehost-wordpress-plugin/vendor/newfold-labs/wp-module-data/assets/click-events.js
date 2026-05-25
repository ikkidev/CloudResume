{
	/**
	 * When a user clicks on something that has a `data-nfd-click` attribute, send an event to the API endpoint.
	 *
	 * @param {Event} e The event object.
	 */
	const processEvent = ( e ) => {
		const isLink = e.target.tagName === 'A';
		// If it's a link, prevent the default behavior until the event is sent.
		if ( isLink ) {
			e.preventDefault();
		}

		const target = e.target;
		const eventId = target.getAttribute( 'data-nfd-click' );
		const eventKey = target.getAttribute( 'data-nfd-event-key' ) || eventId;
		const eventCategory =
			target.getAttribute( 'data-nfd-event-category' ) || 'plugin';
		const brand =
			target.getAttribute( 'data-nfd-brand' ) ||
			window.nfdHiiveEvents.brand;
		const queue = target.getAttribute( 'data-nfd-queue' ) || false;

		const eventData = {
			action: eventId,
			category: eventCategory,
			queue,
			data: {
				label_key: eventKey,
				brand,
				page: window.location.href,
			},
		};

		// To handle marketplace items.
		if ( target.getAttribute( 'data-nfd-product-id' ) ) {
			eventData.data.product_id = target.getAttribute(
				'data-nfd-product-id'
			);
		}

		// Send event to the API
		window.wp
			.apiFetch( {
				url: window.nfdHiiveEvents.eventEndpoint,
				method: 'POST',
				data: eventData,
			} )
			.catch( ( error ) => {
				if ( error === 'This site is not connected to the hiive.' ) {
					console.warn(
						'Site not connected to Hiive, event not sent.'
					);
				} else {
					console.error( 'Error sending event to API', error );
				}
			} );

		// Maybe send user to link manually
		if ( isLink ) {
			const isCTB = target.getAttribute( 'data-ctb-id' );
			const hasPDAttr = target.hasAttribute( 'data-nfd-prevent-default' );
			// except with a CTB link since the CTB script manages that as a fallback
			// or if it has a data-nfd-prevent-default attribute to prevent default behavior
			if ( isCTB || hasPDAttr ) {
				return;
			} else {
				const linkTarget = target.getAttribute( 'target' );
				// respect target="_blank" settings in links
				if ( linkTarget === '_blank' ) {
					window.open( e.target.href );
				} else {
					window.open( e.target.href, '_self' );
				}
			}
		}
	};

	window.addEventListener( 'load', () => {
		document
			.querySelector( '#wpwrap' )
			.addEventListener( 'click', ( e ) => {
				if ( e.target.hasAttribute( 'data-nfd-click' ) ) {
					processEvent( e );
				}
			} );
	} );
}
