import { default as MarketplaceItem } from '../marketplaceItem/';
import { Button } from '@newfold/ui-component-library';

/**
 * MarketplaceList Component
 * For use in Marketplace to display a list of marketplace items
 *
 * @param {*} props
 * @return
 */
const MarketplaceList = ( {
							  marketplaceItems,
							  currentCount,
							  category,
							  methods,
							  constants,
						  } ) => {
	const [ itemsCount, setItemsCount ] = methods.useState( currentCount );
	const [ currentItems, setCurrentItems ] = methods.useState( [] );
	const [ activeItems, setActiveItems ] = methods.useState( [] );

	/**
	 * Filter Products By Category - this ensures only this category products is listed here, it gets us current items
	 * @param Array    items - the products
	 * @param string   category - the category to filter by
	 * @param items
	 * @param category
	 * @return
	 */
	const filterProductsByCategory = ( items, category ) => {
		if ( 'all' === category.name ) return items;

		return items.filter( ( item ) => {
			return item.categories.includes( category.title );
		} );
	};

	/**
	 * Set Product List Length - this controls how many products are displayed in the list, it gets us active current items
	 * @param Array      items
	 * @param Number     itemsCount
	 * @param items
	 * @param itemsCount
	 * @return
	 */
	const setProductListCount = ( items, itemsCount ) => {
		return [ ...items ].slice( 0, itemsCount );
	};

	/**
	 * increment itemCount by perPage amount
	 */
	const loadMoreClick = () => {
		setItemsCount( itemsCount + constants.perPage );
	};

	/**
	 * init method - filter products
	 */
	methods.useEffect( () => {
		setCurrentItems(
			filterProductsByCategory( marketplaceItems, category )
		);
	}, [ marketplaceItems ] );

	/**
	 * recalculate activeItems if currentItems or itemsCount changes
	 */
	methods.useEffect( () => {
		setActiveItems( setProductListCount( currentItems, itemsCount ) );
	}, [ currentItems, itemsCount ] );

	return (
		<>
			<div
				className={ `marketplace-list marketplace-list-${ category.name } wppbh-app-marketplace-list nfd-grid nfd-gap-6 nfd-grid-cols-1 min-[575px]:nfd-grid-cols-2 md:nfd-grid-cols-1 min-[1060px]:nfd-grid-cols-2 min-[1360px]:nfd-grid-cols-3` }
			>
				{ activeItems.length > 0 &&
					activeItems.map( ( item ) => (
						<MarketplaceItem
							key={ item.id }
							item={ item }
							methods={ methods }
							constants={ constants }
						/>
					) ) }
				{ ! activeItems.length && <p>{ constants.text.noProducts }</p> }
				{ currentItems && currentItems.length > itemsCount && (
					<div className="nfd-flex nfd-mx-4 nfd-col-span-full">
						<Button
							onClick={ loadMoreClick }
							variant="secondary"
							className="align-center nfd-bg-transparent nfd-transition-all nfd-duration-200 nfd-text-[#196bde] hover:nfd-bg-[#ccdcf4]"
							style={ { margin: 'auto' } }
						>
							{ constants.text.loadMore }
						</Button>
					</div>
				) }
			</div>
		</>
	);
};

export default MarketplaceList;
