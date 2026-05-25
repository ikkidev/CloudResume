import { default as Header } from '../marketplaceHeader/';
import { default as Body } from '../marketplaceBody/';

const defaults = {
	eventendpoint: '/newfold-data/v1/events/',
	perPage: 12,
	appendCategoryToTitle: true,
	text: {
		title: __( 'Marketplace', 'wp-module-marketplace' ),
		subTitle: __(
			'Explore our featured collection of tools and services.',
			'wp-module-marketplace'
		),
		error: __(
			'Oops, there was an error loading the marketplace, please try again later.',
			'wp-module-marketplace'
		),
		noProducts: __(
			'Sorry, no marketplace items. Please, try again later.',
			'wp-module-marketplace'
		),
		loadMore: __( 'Load More', 'wp-module-marketplace' ),
        categories: __( 'Categories', 'wp-module-marketplace' ),

    },
};

/**
 * Marketplace Module
 * For use in brand app to display marketplace
 *
 * @param {*} props
 * @return
 */
const Marketplace = ( { methods, constants, ...props } ) => {
	// set defaults if not provided
	constants = Object.assign( defaults, constants );

	return (<>
		<div className="nfd-page-content nfd-flex nfd-flex-col nfd-relative nfd-gap-6 nfd-max-w-full nfd-my-0">
			<Header title={ constants.text.title } description={ constants.text.subTitle }/>
		</div>
		<Body constants={ constants } methods={ methods }/>
	</>);
};

export default Marketplace;
