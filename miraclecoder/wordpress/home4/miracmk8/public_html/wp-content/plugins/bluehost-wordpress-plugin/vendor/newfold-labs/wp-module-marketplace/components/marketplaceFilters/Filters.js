import { Select } from '@newfold/ui-component-library';

export const Filters = ( { categories, activeCategoryIndex } ) => {

	const activePath = activeCategoryIndex
		? categories[ activeCategoryIndex ].name
		: 'all';
	const setCategory = ( cat ) => {
		if ( categories.find( category => category.name === cat ) ) {
			window.location.hash = `#marketplace/${ cat }`;
		}
	}

	return (

		<div className={ 'nfd-marketplace-filters-section' }>
			<div className={ 'nfd-marketplace-categories nfd-flex nfd-gap-4 nfd-items-center' }>
			<span className="nfd-marketplace-categories-title nfd-text-base">
				{ __( 'Filter by type', 'wp-module-marketplace' ) }
			</span>
				<Select
					onChange={ setCategory }
					value={ activePath || 'all' }
					id={ 'nfd-marketing-page-category-selector' }
					className={ 'nfd-marketing-page-category-selector nfd-min-w-[200px]' }
					selectedLabel={
						categories.find(
							( cat ) => cat.name === (activePath || 'all')
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
						/>
					) ) }
				</Select>
			</div>
			<div className={ 'nfd-marketplace-search' }>
			</div>
		</div>
	);
};
