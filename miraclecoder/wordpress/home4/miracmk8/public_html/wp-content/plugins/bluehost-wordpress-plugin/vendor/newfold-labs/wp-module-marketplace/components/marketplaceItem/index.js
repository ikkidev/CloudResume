import { validate as isUuid } from 'uuid';
import { Button, Card, Link, Title } from '@newfold/ui-component-library';
import { ArrowRightIcon } from '@heroicons/react/24/outline';

/**
 * MarketplaceItem Component
 * For use in Marketplace to display marketplace items
 *
 * @param {*} props
 * @return
 */
const MarketplaceItem = ( { item, methods, constants } ) => {
	/**
	 * Send events to the WP REST API
	 *
	 * @param {Object} event The event data to be tracked.
	 */
	const sendEvent = ( event ) => {
		event.data = event.data || {};
		event.data.page = window.location.href;
		methods.apiFetch( {
			url: methods.NewfoldRuntime.createApiUrl( constants.eventendpoint ),
			method: 'POST',
			data: event,
		} );
	};

	/**
	 * Handle button clicks
	 * @param Event event
	 * @param event
	 * @return
	 */
	const onButtonNavigate = ( event ) => {
		if ( event.keycode && ENTER !== event.keycode ) {
			return;
		}
		sendEvent( {
			action: 'newfold-marketplaceitem-click',
			data: {
				element: 'button',
				label: event.target.innerText,
				productId: item.id,
			},
		} );
	};

	/**
	 * Handle link clicks
	 * @param Event event
	 * @param event
	 * @return
	 */
	const onAnchorNavigate = ( event ) => {
		if ( event.keycode && ENTER !== event.keycode ) {
			return;
		}
		sendEvent( {
			action: 'newfold-marketplaceitem-click',
			data: {
				element: 'a',
				href: event.target.getAttribute( 'href' ),
				label: event.target.innerText,
				productId: item.id,
			},
		} );
	};

	/**
	 * initial set up - adding event listeners
	 */
	methods.useEffect( () => {
		const itemContainer = document.getElementById(
			`marketplace-item-${ item.id }`
		);
		const itemButtons = Array.from(
			itemContainer.querySelectorAll( 'button' )
		);
		const itemAnchors = Array.from( itemContainer.querySelectorAll( 'a' ) );

		if ( itemButtons.length ) {
			itemButtons.forEach( ( button ) => {
				if ( button.getAttribute( 'data-action' ) !== 'close' ) {
					button.addEventListener( 'click', onButtonNavigate );
					button.addEventListener( 'onkeydown', onButtonNavigate );
				}
			} );
		}

		if ( itemAnchors.length ) {
			itemAnchors.forEach( ( link ) => {
				if ( link.getAttribute( 'data-action' ) !== 'close' ) {
					link.addEventListener( 'click', onAnchorNavigate );
					link.addEventListener( 'onkeydown', onAnchorNavigate );
				}
			} );
		}

		// unmount remove event listeners
		return () => {
			if ( itemButtons.length ) {
				itemButtons.forEach( ( button ) => {
					if ( button.getAttribute( 'data-action' ) !== 'close' ) {
						button.removeEventListener( 'click', onButtonNavigate );
						button.removeEventListener(
							'onkeydown',
							onButtonNavigate
						);
					}
				} );
			}
			if ( itemAnchors.length ) {
				itemAnchors.forEach( ( link ) => {
					if ( link.getAttribute( 'data-action' ) !== 'close' ) {
						link.removeEventListener( 'click', onAnchorNavigate );
						link.removeEventListener(
							'onkeydown',
							onAnchorNavigate
						);
					}
				} );
			}
		};
	}, [] );

	const renderPrimaryCTA = ( item ) => {
		let primaryCTA = '';

		if ( item.primaryCallToAction && item.primaryUrl ) {
			if ( item.clickToBuyId ) {
				primaryCTA = (
					<Button
						as="a"
						href={ window.NewfoldRuntime?.linkTracker?.addUtmParams( item.primaryUrl ) || item.primaryUrl }
						target="_blank"
						data-action="load-nfd-ctb"
						data-ctb-id={ item.clickToBuyId }
						data-nfd-click="newfold-marketplaceitem-click"
						data-nfd-event-key={ item.primaryCallToAction }
						data-nfd-product-id={ item.clickToBuyId }
					>
						{ item.primaryCallToAction }
					</Button>
				);
			} else {
				primaryCTA = (
					<Button as="a" href={ window.NewfoldRuntime?.linkTracker?.addUtmParams( item.primaryUrl ) || item.primaryUrl } target="_blank">
						{ item.primaryCallToAction }
					</Button>
				);
			}
		}

		return primaryCTA;
	};

	const renderSecondaryCTA = ( item ) => {
		let secondaryCTA = '';
		// If value is UUID, it is an internal link to a prodct page
		const isInternal = isUuid( item.secondaryUrl );

		const getLinkAttributes = () => {
			const attributes = {
				as: 'a',
				className:
					'nfd-inline-flex nfd-items-center nfd-gap-1.5 nfd-w-max nfd-no-underline',
				href: window.NewfoldRuntime?.linkTracker?.addUtmParams( generateSecondaryUrl() ) || generateSecondaryUrl(),
				target: isInternal ? '_self' : '_blank',
				onClick: handleNavigate,
			};

			return attributes;
		};

		const handleNavigate = ( e ) => {
			const navigate = methods.useNavigate();
			if ( isInternal ) {
				e.preventDefault();
				navigate( `/marketplace/product/${ item.secondaryUrl }` );
			}
		};

		const generateSecondaryUrl = () => {
			if ( isInternal ) {
				return `${ window.NewfoldRuntime.admin_url }admin.php?page=${ window.NewfoldRuntime.plugin.brand }#/marketplace/product/${ item.secondaryUrl }`;
			}

			return item.secondaryUrl;
		};

		if ( item.secondaryCallToAction && item.secondaryUrl ) {
			secondaryCTA = (
				<Link
					{ ...getLinkAttributes() }
					data-nfd-click="newfold-marketplaceitem-click"
					data-nfd-event-key={ item.secondaryCallToAction }
					data-nfd-product-id={ item.id }
				>
					<span className="nfd-text-primary">
						{ item.secondaryCallToAction }
					</span>
					<ArrowRightIcon className="nfd-text-[#18181B] nfd-w-3" />
				</Link>
			);
		}

		return secondaryCTA;
	};

	const renderPrice = ( item ) => {
		let pricewrap,
			price,
			fullprice = '';
		if ( item.price > 0 && item.price_formatted ) {
			price = (
				<span className="marketplace-item-price nfd-bg-[#E2E8F0] nfd-py-1 nfd-px-3 nfd-rounded-full">
					{ item.price_formatted }
				</span>
			);
			if ( item.full_price > 0 && item.full_price_formatted ) {
				fullprice = (
					<span className="marketplace-item-fullprice nfd-line-through">
						{ item.full_price_formatted }
					</span>
				);
			}
			pricewrap = (
				<div className="marketplace-item-price-wrap has-full-price nfd-flex nfd-flex-col nfd-items-center nfd-gap-2 nfd-text-[#1E293B] nfd-font-medium">
					{ fullprice }
					{ price }
				</div>
			);
		}
		return pricewrap;
	};

	return (
		<Card
			className={ `marketplace-item marketplace-item-${ item.id } ${
				item.full_price ? 'product-has-full-price' : ''
			}` }
			id={ `marketplace-item-${ item.id }` }
		>
			<Card.Header className="nfd-h-auto nfd-p-0">
				{ item.productThumbnailUrl && (
					<img
						src={ item.productThumbnailUrl }
						alt={ item.name + ' thumbnail' }
						className="nfd-w-full nfd-aspect-video marketplace-item-image"
					/>
				) }
			</Card.Header>
			<Card.Content className="nfd-flex nfd-flex-col nfd-gap-3">
				<Title as="h2" size="4" className="marketplace-item-title">
					{ item.name }
				</Title>
				<p>{ item.description }</p>
				{ renderSecondaryCTA( item ) }
			</Card.Content>
			<Card.Footer className="nfd-flex nfd-justify-between nfd-items-center nfd-flex-wrap nfd-gap-2 marketplace-item-footer">
				{ renderPrice( item ) }
				{ renderPrimaryCTA( item ) }
			</Card.Footer>
		</Card>
	);
};

export default MarketplaceItem;
