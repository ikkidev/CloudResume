import MarketplaceSkeleton from "../marketplaceSkeleton";

/**
 * MarketplaceFilterBarSkeleton Component
 * For use in Marketplace to display filter bar content skeleton
 * 
 * @param {*} props 
 * @returns 
 */
const MarketplaceFilterBarSkeleton = ({ width, height }) => {
    return ( 
        <MarketplaceSkeleton width={ width || "500px" } height={ height || "45px" } className="filterbar-skeleton"/>
     );
}
 
export default MarketplaceFilterBarSkeleton;