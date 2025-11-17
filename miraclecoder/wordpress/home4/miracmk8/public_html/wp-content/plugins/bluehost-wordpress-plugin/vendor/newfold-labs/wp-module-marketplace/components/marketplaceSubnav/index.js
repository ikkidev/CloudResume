import { NewfoldRuntime } from '@newfold/wp-module-runtime';
import apiFetch from '@wordpress/api-fetch';

/**
 * Marketplace Subnav Helper Method
 *
 * @return array of routes
 */
export const getMarketplaceSubnavRoutes = () => {
	return apiFetch( {
		url: NewfoldRuntime.createApiUrl(
			'/newfold-marketplace/v1/marketplace'
		),
	} ).then( ( response ) => {
		const marketplaceSubnav = [];
		if ( response.hasOwnProperty( 'categories' ) ) {
			const marketPlaceCategories = response.categories.data;
			marketPlaceCategories.forEach( ( cat ) => {
				// format categories for subnav routes
				marketplaceSubnav.push( {
					name: '/marketplace/' + cat.name,
					title: cat.title,
				} );
			} );
		}
		return marketplaceSubnav;
	} );
};
