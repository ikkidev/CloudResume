import MarketplaceSkeleton from '../marketplaceSkeleton';

const ProductPageLoading = () => {
	const Hero = () => (
		<div className="nfd-min-h-[35vh] nfd-flex nfd-flex-col nfd-items-center nfd-justify-center nfd-bg-[#f0f3f9] nfd-m-3 nfd-rounded-lg">
			<MarketplaceSkeleton
				width="35%"
				height="35px"
				className="nfd-mb-4"
			/>
			<div className="nfd-flex nfd-flex-col nfd-items-center nfd-gap-2 nfd-w-full">
				<MarketplaceSkeleton width="31%" height="15px" />
				<MarketplaceSkeleton width="40%" height="15px" />
				<MarketplaceSkeleton width="20%" height="15px" />
			</div>
		</div>
	);

	const Features = () => (
		<div className="nfd-py-10 nfd-flex nfd-flex-col nfd-items-center">
			<MarketplaceSkeleton
				width="28%"
				height="25px"
				className="nfd-mb-3"
			/>
			<MarketplaceSkeleton
				width="20%"
				height="15px"
				className="nfd-mb-1.5"
			/>
			<MarketplaceSkeleton width="12%" height="15px" />
			<div className="nfd-mt-8 nfd-flex nfd-justify-between nfd-w-10/12">
				<Feature />
				<Feature />
				<Feature />
			</div>
		</div>
	);

	const Feature = () => (
		<div className="nfd-w-1/4 nfd-flex nfd-flex-col nfd-items-center">
			<MarketplaceSkeleton
				width="65px"
				height="65px"
				className="nfd-mb-4 nfd-rounded-full"
			/>
			<MarketplaceSkeleton
				width="100%"
				height="24px"
				className="nfd-mb-2.5"
			/>
			<div className="nfd-flex nfd-flex-col nfd-items-center nfd-gap-1.5 nfd-w-full">
				<MarketplaceSkeleton width="90%" height="15px" />
				<MarketplaceSkeleton width="88%" height="15px" />
				<MarketplaceSkeleton width="60%" height="15px" />
			</div>
		</div>
	);

	const Pricing = () => (
		<>
			<div className="nfd-w-full nfd-mt-3 nfd-flex nfd-flex-col nfd-items-center">
				<div className="nfd-w-10/12 nfd-min-h-0.5 nfd-bg-[#f0f3f9]"></div>
			</div>
			<div className="nfd-py-10 nfd-flex nfd-flex-col nfd-items-center">
				<MarketplaceSkeleton width="22%" height="27px" />
				<div className="nfd-mt-10 nfd-flex nfd-justify-between nfd-w-10/12">
					<PricingItem />
					<PricingItem />
					<PricingItem />
				</div>
			</div>
		</>
	);

	const PricingItem = () => (
		<div className="nfd-w-1/4 nfd-flex nfd-flex-col nfd-gap-7 nfd-bg-[#f0f3f9] nfd-rounded-lg nfd-p-6">
			<MarketplaceSkeleton width="90%" height="28px" />
			<div className="nfd-flex nfd-flex-col nfd-gap-4">
				<MarketplaceSkeleton width="80%" height="14px" />
				<MarketplaceSkeleton width="55%" height="14px" />
				<MarketplaceSkeleton width="100%" height="14px" />
				<MarketplaceSkeleton width="72%" height="14px" />
				<MarketplaceSkeleton width="47%" height="14px" />
				<MarketplaceSkeleton width="80%" height="14px" />
			</div>
			<MarketplaceSkeleton width="55%" height="34px" />
		</div>
	);

	const FAQ = () => (
		<div className="nfd-flex nfd-flex-col nfd-items-center nfd-gap-8 nfd-py-12 nfd-mx-auto nfd-w-7/12">
			<FAQitem width="96%">
				<div className="nfd-flex nfd-flex-col nfd-gap-2.5 nfd-mt-4">
					<MarketplaceSkeleton width="100%" height="14px" />
					<MarketplaceSkeleton width="91%" height="14px" />
					<MarketplaceSkeleton width="95%" height="14px" />
					<MarketplaceSkeleton width="54%" height="14px" />
				</div>
			</FAQitem>
			<FAQitem />
			<FAQitem />
			<FAQitem />
			<FAQitem />
		</div>
	);

	const FAQitem = ( { width = '100%', children } ) => (
		<div className="nfd-w-full nfd-bg-[#f0f3f9] nfd-rounded-lg nfd-p-4">
			<MarketplaceSkeleton width={ width } height="26px" />
			{ children }
		</div>
	);

	return (
		<div
			aria-busy="true"
			aria-live="polite"
			aria-label="Fetching product details"
		>
			<Hero />
			<Features />
			<Pricing />
			<FAQ />
		</div>
	);
};

export default ProductPageLoading;
