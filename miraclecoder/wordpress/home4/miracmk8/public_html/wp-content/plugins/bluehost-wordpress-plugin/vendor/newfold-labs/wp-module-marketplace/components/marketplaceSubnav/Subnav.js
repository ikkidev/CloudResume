import './stylesheet.scss';

import { useViewportMatch } from '@wordpress/compose';
import { Sidebar } from './Sidebar';
import { Mobilenav } from './Mobilenav';

const Subnav = ( { categories, activeCategoryIndex } ) => {
	const isLargeViewport = useViewportMatch( 'medium' );
	const activePath = activeCategoryIndex
		? categories[ activeCategoryIndex ].name
		: 'all';

	return (
		categories &&
		<>
			{ isLargeViewport
				? <Sidebar { ...{ categories, activePath } }/>
				: <Mobilenav { ...{ categories, activePath } }/>
			}
		</>);
}

export default Subnav;