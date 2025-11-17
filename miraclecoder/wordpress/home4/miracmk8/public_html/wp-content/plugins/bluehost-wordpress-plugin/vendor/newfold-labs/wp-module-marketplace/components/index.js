import Marketplace from './marketplace';
import ProductPage from './productPage';

const NewfoldMarketplace = ( { methods, constants, ...props } ) => {
	const match = methods.useMatch( 'marketplace/product/:id' );
	if ( match ) {
		return (
			<ProductPage
				productPageId={ match.params.id }
				methods={ methods }
				constants={ constants }
			/>
		);
	}

	return (
		<Marketplace methods={ methods } constants={ constants } { ...props } />
	);
};

export default NewfoldMarketplace;
