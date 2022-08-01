import { SelectControl } from '@wordpress/components';
import { useSelect, useDispatch } from '@wordpress/data';
import { store as editorStore } from '@wordpress/editor';
import { store as coreStore } from '@wordpress/core-data';
import { useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

const TENUP_PRIMARY_CATEGORY_META_KEY = '_tenup_primary_category';

const DEFAULT_QUERY = {
	context: 'view',
	_fields: 'id,name,parent,slug',
	per_page: -1,
	orderby: 'name',
	order: 'asc',
};

const PrimaryCategory = ( props ) => {
	const { OriginalComponent } = props;

	const { allCategories, postMeta, postCategories, loading } = useSelect(
		( select ) => {
			return {
				allCategories: select( coreStore ).getEntityRecords(
					'taxonomy',
					'category',
					DEFAULT_QUERY
				),
				postMeta:
					select( editorStore ).getEditedPostAttribute( 'meta' ),
				postCategories:
					select( editorStore ).getEditedPostAttribute(
						'categories'
					),
				loading: select( coreStore ).isResolving( 'getEntityRecords', [
					'taxonomy',
					'category',
					DEFAULT_QUERY,
				] ),
			};
		},
		[]
	);

	/**
	 * Transform post categories into label, value pair to render into SelectControl.
	 */
	const primaryCategoriesOptions =
		allCategories?.length > 0
			? postCategories?.map( ( categoryId ) => {
					const category = allCategories.find(
						( singleCategory ) => singleCategory.id === categoryId
					);
					if ( ! category ) {
						return [];
					}
					return { label: category?.name, value: category?.slug };
			  } )
			: [];

	const { editPost } = useDispatch( editorStore );
	const setPostMeta = ( newMeta ) => editPost( { meta: newMeta } );

	/**
	 * If user removes a category from the post which is primary category,
	 * Update the primary category to empty string.
	 */
	useEffect( () => {
		if (
			primaryCategoriesOptions?.length > 0 &&
			! primaryCategoriesOptions.find(
				( category ) =>
					category.value ===
					postMeta[ TENUP_PRIMARY_CATEGORY_META_KEY ]
			)
		) {
			setPostMeta( { [ TENUP_PRIMARY_CATEGORY_META_KEY ]: '' } );
		}
	}, [ postCategories ] );

	return (
		<>
			<OriginalComponent { ...props } />
			<div style={ { marginTop: '12px' } }>
				{ ! loading && postCategories?.length > 1 && (
					<SelectControl
						label={ __(
							'Select the primary category',
							'tenup-primary-category'
						) }
						value={ postMeta[ TENUP_PRIMARY_CATEGORY_META_KEY ] }
						options={ primaryCategoriesOptions }
						onChange={ ( primaryCategory ) => {
							setPostMeta( {
								[ TENUP_PRIMARY_CATEGORY_META_KEY ]:
									primaryCategory,
							} );
						} }
					/>
				) }
			</div>
		</>
	);
};

export default PrimaryCategory;
