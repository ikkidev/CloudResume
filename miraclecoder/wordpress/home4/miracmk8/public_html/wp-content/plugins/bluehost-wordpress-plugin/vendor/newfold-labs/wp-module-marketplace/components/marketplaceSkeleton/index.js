import './stylesheet.scss';

/**
 * MarketplaceSkeleton Component
 * Use to generate content loading skeleton
 *
 * @param {*} props
 * @return {JSX.Element} MarketplaceSkeleton
 */
const MarketplaceSkeleton = ( { width, height, className = '' } ) => {
	return (
		<div
			// eslint-disable-next-line prettier/prettier
			className={ classNames(
				'newfold-marketplace-skeleton nfd-flex-1',
				className
			) }
			style={ {
				width: width || '100%',
				height: height || 'auto',
			} }
		></div>
	);
};

export default MarketplaceSkeleton;
