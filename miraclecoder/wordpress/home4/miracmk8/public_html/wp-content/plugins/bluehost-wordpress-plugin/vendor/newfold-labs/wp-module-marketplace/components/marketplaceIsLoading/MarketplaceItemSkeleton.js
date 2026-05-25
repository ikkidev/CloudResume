import MarketplaceSkeleton from "../marketplaceSkeleton";

/**
 * MarketplaceItemSkeleton Component
 * For use in Marketplace to display Marketplace items content skeleton
 * 
 * @param {*} props 
 * @returns 
 */
const MarketplaceItemSkeleton = () => {
    return ( 
        <div className="marketplace-item-skeleton">
            
            <div className="skeleton-item-media">
                <MarketplaceSkeleton width="100%" height="auto" className="marketplace-item-img-skeleton" />
            </div>

            <div className="skeleton-item-body">
                <MarketplaceSkeleton width="170px" height="20px" className="marketplace-item-title-skeleton"/>

                <MarketplaceSkeleton width="100%" height="10px" className="marketplace-item-desc-skeleton"/>
                <MarketplaceSkeleton width="100%" height="10px" className="marketplace-item-desc-skeleton"/>
                <MarketplaceSkeleton width="70%" height="10px" className="marketplace-item-desc-skeleton"/>

                <MarketplaceSkeleton width="80px" height="36px" className="marketplace-item-button-skeleton"/>
            </div>

        </div>
     );
}
 
export default MarketplaceItemSkeleton;