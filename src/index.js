/**
 * WordPress dependencies
 */
import { addFilter } from '@wordpress/hooks';
import domReady from '@wordpress/dom-ready';

/**
 * Internal dependencies.
 */
import PrimaryCategory from './components/PrimaryCategory';

const loadPrimaryCategory = ( OriginalComponent ) => {
	return ( props ) => {
		/**
		 * Only render primary category block on category taxonomy(and not post tag).
		 */
		if ( props.slug === 'category' ) {
			return (
				<PrimaryCategory
					OriginalComponent={ OriginalComponent }
					{ ...props }
				/>
			);
		}
		return <OriginalComponent { ...props } />;
	};
};

domReady( () => {
	addFilter(
		'editor.PostTaxonomyType',
		'kishanjasani/tenup-primary-category',
		loadPrimaryCategory
	);
} );
