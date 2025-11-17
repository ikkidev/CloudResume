import { default as MarketplaceIsLoading } from '../marketplaceIsLoading/';
import { default as MarketplaceItem } from '../marketplaceItem/';

/**
 * Marketplace Module
 * For use in brand app to display marketplace products filtered by category and/or vendor values.
 * This is a lite version of the marketplace, in that it doesn't display full list of products or
 * navigation, it is only a list of speficied products.
 * 
 * @param {*} props 
 * @returns 
 */
 const MarketplaceLite = ({methods, constants, Components, ...props}) => {
	const [ isLoading, setIsLoading ] = methods.useState( true );
	const [ isError, setIsError ] = methods.useState( false );
	const [ marketplaceCategories, setMarketplaceCategories ] = methods.useState( [] );
	const [ marketplaceItems, setMarketplaceItems ] = methods.useState( [] );
	const limit = constants.perPage ? constants.perPage : 12;
	/**
	 * on mount load all marketplace data from module api
	 */
	methods.useEffect(() => {
		methods.apiFetch( {
			url: `${constants.resturl}/newfold-marketplace/v1/marketplace`
		}).then( ( response ) => {
			// check response for data
			if ( ! response.hasOwnProperty('categories') || ! response.hasOwnProperty('products') ) {
				setIsError( true );
			} else {
				setMarketplaceItems( validateProducts( response.products.data, props.vendor ) );
				setMarketplaceCategories( validateCategories( response.categories.data ) );
			}
		});
	}, [] );

	/**
	 * When marketplaceItems changes
	 * verify that there are products
	 */
	 methods.useEffect(() => {
		// only after a response
		if ( !isLoading ) {
			// if no marketplace items, display error
			if ( marketplaceItems.length < 1 ) {
				setIsError( true );
			} else {
				setIsError( false );
			}
		}
	}, [ marketplaceItems ] );

	/**
	 * When marketplaceCategories changes
	 * verify that the tab is a category
	 */
	methods.useEffect(() => {
		// only before rendered, but after categories are populated
		if ( isLoading && marketplaceCategories.length >= 1 ) {
			// read initial tab from path
			setIsLoading( false );
		}
	}, [ marketplaceCategories ] );

	/**
	 * Validate provided category data
	 * @param Array categories 
	 * @returns 
	 */
	const validateProducts = ( products, vendor ) => {
		
		if ( vendor ) {
			return setProductListCount( filterProductsByVendor(products, vendor), limit );
		} else {
			return setProductListCount( products, limit )
		}
	};

	/**
	 * Set Product List Length - this controls how many products are displayed in the list, it gets us active current items
	 * @param Array items 
	 * @param Number itemsCount 
	 * @returns 
	 */
	const setProductListCount = (items, itemsCount) => {
		let count = 0;
		return items.filter((item) => {
			count++;
			return count <= itemsCount;
		});
	};

	/**
	 * Filter Products By Vendor - this ensures only this vendor's products are listed
	 * @param Array items - the products
	 * @param string vendor - the vendor to filter by 
	 * @returns 
	 */
	const filterProductsByVendor = (items, vendorname) => {
		return items.filter((item) => {
			return item.vendor !== null && item.vendor.name === vendorname;
		});
	};

	/**
	 * Validate provided category data
	 * @param Array categories 
	 * @returns 
	 */
	const validateCategories = ( categories ) => {
		
		if ( ! categories.length ) {
			return [];
		}
		
		let thecategories = [];
		categories.forEach((cat)=>{
			cat.className = 'newfold-marketplace-tab-'+cat.name;

			if ( cat.products_count > 0 &&
				( 
					cat.title === props.category ||
					cat.name === props.category 
				)
			) {
				thecategories.push(cat);
			}
		});
		
		return thecategories;
	};

	/**
	 * render marketplace preloader
	 * 
	 * @returns React Component
	 */
	 const renderSkeleton = () => {
		// render skeleton with no tabs and set the number from received perPage prop
		return <MarketplaceIsLoading 
			filter={false}
			items={limit}
		/>;
	}


	return (
		<div className={methods.classNames('newfold-marketplace-wrapper newfold-marketplaceLite-wrapper')}>
			{ isLoading && 
				renderSkeleton()
			}
			{ isError && 
				<h3>Oops, there was an error loading products, please try again later.</h3>
			}
			{ !isLoading && !isError &&
				<div className={ `marketplace-lite marketplace-lite-category-${ props.category } marketplace-lite-vendor-${ props.vendor }` }>
					<div className="grid col2">
						{ marketplaceItems.length > 0 && marketplaceItems.map((item) => (
								<MarketplaceItem
									key={item.hash} 
									item={item}
									Components={Components}
									methods={methods}
									constants={constants}
								/>
							))
						}
						{ !marketplaceItems.length &&
							<p>Sorry, there are no products available. Please, try again later.</p>
						}
					</div>
				</div>
			}
		</div>
	)

};

export default MarketplaceLite;