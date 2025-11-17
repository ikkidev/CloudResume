import ProductPageError from './ProductPageError';
import ProductPageLoading from './ProductPageLoading';

const initialState = {
	html: null,
	loading: true,
	error: false,
};

const defaults = {
	text: {
		productPage: {
			error: {
				title: __(
					'Oops! Something Went Wrong',
					'wp-module-marketplace'
				),
				description: __(
					'An error occurred while loading the content. Please try again later.',
					'wp-module-marketplace'
				),
			},
		},
	},
};

const ProductPage = ( { productPageId, methods, constants } ) => {
	const [ data, setData ] = methods.useState( {
		...initialState,
	} );

	// set defaults if not provided
	constants = Object.assign( defaults, constants );

	methods.useEffect( () => {
		// Reset the state
		setData( {
			...initialState,
		} );

		methods
			.apiFetch( {
				url: methods.NewfoldRuntime.createApiUrl(
					`/newfold-marketplace/v1/products/page`,
					{ id: productPageId }
				),
			} )
			.then( ( response ) => {
				if ( response.hasOwnProperty( 'html' ) ) {
					// Set the html content
					setData( {
						html: response.html,
						loading: false,
						error: false,
					} );
				} else {
					// Invoke error state
					setData( {
						html: null,
						loading: false,
						error: true,
					} );
				}
			} )
			.catch( () => {
				// Invoke error state
				setData( {
					html: null,
					loading: false,
					error: true,
				} );
			} );
	}, [ productPageId ] );

	return (
		<div>
			{ data.loading && <ProductPageLoading /> }
			{ data.error && <ProductPageError constants={ constants } /> }
			{ data.html && (
				<div
					dangerouslySetInnerHTML={ {
						__html: data.html,
					} }
				/>
			) }
		</div>
	);
};

export default ProductPage;
