import { Title } from '@newfold/ui-component-library';

export const Header = ( { title, description } ) => {
	return <div className="nfd-marketplace-page__header nfd-flex nfd-flex-col nfd-gap-y-4 min-[782px]:nfd-mb-6">
		{
			!! title &&
			<Title>
				{ title }
			</Title>
		}
		{
			!! description &&
			<span>
				{ description }
			</span>
		}
	</div>
};
