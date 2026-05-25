import { SidebarNavigation, Title } from '@newfold/ui-component-library';

export const Sidebar = ( { categories, activePath } ) => {
	return (
		<aside className={ 'nfd-marketplace-sidebar' }>
			<SidebarNavigation activePath={ activePath }>
				<SidebarNavigation.Sidebar className="nfd-min-w-60">
					<Title
						as="h2"
						className="nfd-marketplace-categories-title nfd-text-base nfd-mb-4"
					>
						{ __( 'Categories', 'wp-module-marketplace' ) }
					</Title>
					<SidebarNavigation.MenuItem
						label="Categories"
						defaultOpen={ true }
						className={ 'nfd-hidden' }
					>
						{ categories.map( ( cat ) => (
							<SidebarNavigation.SubmenuItem
								pathProp="id"
								id={ cat.name }
								label={
									cat.title +
									( cat?.products_count
										? ` (${ cat.products_count })`
										: '' )
								}
								key={ cat.name }
								href={ `#/marketplace/${ cat.name }` }
							/>
						) ) }
					</SidebarNavigation.MenuItem>
				</SidebarNavigation.Sidebar>
			</SidebarNavigation>
		</aside>
	);
};
