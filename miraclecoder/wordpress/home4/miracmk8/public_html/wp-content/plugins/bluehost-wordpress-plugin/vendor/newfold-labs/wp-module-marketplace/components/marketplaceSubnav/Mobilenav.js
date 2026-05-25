import { Select, Title } from '@newfold/ui-component-library';

export const Mobilenav = ( { categories, activePath } ) => {
	const setCategory = ( cat ) => {
		if ( categories.find( category => category.name === cat ) ) {
			window.location.hash = `#marketplace/${ cat }`;
		}
	}
	return (
		<aside className={ 'nfd-marketplace-sidebar' }>
			<Title
				as="h2"
				className="nfd-marketplace-categories-title nfd-text-base nfd-mb-4"
			>
				{ __( 'Categories', 'wp-module-marketplace' ) }
			</Title>
			<Select
				onChange={ setCategory }
				value={ activePath }
				id={ 'nfd-marketing-page-category-selector' }
				className={ 'nfd-marketing-page-category-selector' }
				selectedLabel={
					categories.find(
						( cat ) => cat.name === activePath
					)?.title
				}
			>
				{ categories.map( ( cat ) => (
					<Select.Option
						label={ cat.title +
							(cat?.products_count
								? ` (${ cat.products_count })`
								: '') }
						value={ cat.name }
						className={
							'nfd-solutions-category-selector__' + cat.id
						}
						key={ cat.name }
						href={ '/test' }
					/>

				) ) }
			</Select>
		</aside>
	);
};
