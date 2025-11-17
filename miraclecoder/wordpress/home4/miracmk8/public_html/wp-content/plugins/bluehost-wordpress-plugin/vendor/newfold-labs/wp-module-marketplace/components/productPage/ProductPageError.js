import { Title } from '@newfold/ui-component-library';
import errorVector from '../../includes/assets/img/dog-walking.svg';

const ProductPageError = ( { constants } ) => {
	return (
		<div aria-live="assertive">
			<div
				role="alert"
				className="nfd-flex nfd-flex-col nfd-items-center nfd-justify-center nfd-gap-6 nfd-p-8 nfd-min-h-[calc(100dvh-220px)]"
			>
				<img
					src={ errorVector }
					role="presentation"
					alt="Dog walking with a leash"
					width="300"
					height="278.5"
				/>
				<div className="nfd-text-center">
					<Title as="h2" size="1">
						{ constants.text.productPage.error.title }
					</Title>
					<p className="nfd-mt-2 nfd-text-base">
						{ constants.text.productPage.error.description }
					</p>
				</div>
			</div>
		</div>
	);
};

export default ProductPageError;
