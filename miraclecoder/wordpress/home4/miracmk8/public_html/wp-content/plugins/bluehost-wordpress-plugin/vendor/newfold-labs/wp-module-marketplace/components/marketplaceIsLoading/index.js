import './stylesheet.scss';

import MarketplaceFilterBarSkeleton from './MarketplaceFilterBarSkeleton';
import MarketplaceItemSkeleton from './MarketplaceItemSkeleton';

/**
 * MarketPlaceIsLoading Component
 * For use in Marketplace to display conent skeleton preloader
 * 
 * @param {*} props 
 * @returns 
 */
const MarketplaceIsLoading = ({ items = 6, filter = true, containerClassNames = "grid col2" }) => {

    const renderMarketplaceSkeletonItems = (items) => {
        const itemsCount = items;
        let marketplaceItems = [];

        for (let i = 0; i < itemsCount; i++) {
            marketplaceItems.push(i);
        }

        return marketplaceItems.map( (item) => <MarketplaceItemSkeleton key={item}/> );
    }

    return ( 
        <div className="newfold-marketplace-isloading">
            { filter && <MarketplaceFilterBarSkeleton /> }

            <div className={ containerClassNames }>
                { renderMarketplaceSkeletonItems(items) }
            </div>
            
        </div>
     );
}
 
export default MarketplaceIsLoading;