<?php
/**
 * Main class to intereact with Primary Category of the plugin.
 *
 * @package TenUp_Primary_Category
 */

namespace TenUp_Primary_Category;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( ! class_exists( 'PrimaryCategory' ) ) {

	/**
	 * Main class to intereact with Primary Category.
	 *
	 * @since 0.1.0
	 */
	class PrimaryCategory {

		/**
		 * The instance of the class PrimaryCategory.
		 *
		 * @since 0.1.0
		 * @access protected
		 * @static
		 *
		 * @var PrimaryCategory
		 */
		protected static $instance = null;

		/**
		 * Calls the register_hooks function and require_files function.
		 *
		 * @since 0.1.0
		 * @access public
		 *
		 * @return void
		 */
		public function __construct() {
			Assets::get_instance();

			$this->register_hooks();
		}

		/**
		 * Returns the current instance of the class.
		 *
		 * @since 0.1.0
		 * @access public
		 * @static
		 *
		 * @return PrimaryCategory Returns the current instance of the class.
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Registers the actions and filters for the plugin.
		 *
		 * @since 0.1.0
		 * @access public
		 *
		 * @return void
		 */
		public function register_hooks() {
			// Register action.
			add_action( 'init', [ $this, 'primary_category_register_metabox' ] );
			add_action( 'pre_get_posts', [ $this, 'list_post_with_primary_category' ] );

			// Register filters.
			add_filter( 'post_link_category', [ $this, 'register_primary_category_permalinks' ], 10, 3 );
		}

		/**
		 * Register Primary category metabox.
		 *
		 * @since 0.1.0
		 * @access public
		 *
		 * @return void
		 */
		public function primary_category_register_metabox() {
			register_post_meta(
				'',
				TENUP_PRIMARY_CATEGORY_META_KEY,
				[
					'show_in_rest'      => true,
					'single'            => true,
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_text_field',
					'auth_callback'     => function() {
						return current_user_can( 'edit_posts' );
					},
				]
			);
		}

		/**
		 * Filters the category that gets used in the %category% permalink token.
		 *
		 * @since 0.1.0
		 * @access public
		 *
		 * @param WP_Term $category The category to use in the permalink.
		 * @param array   $cats     Array of all categories (WP_Term objects) associated with the post.
		 * @param WP_Post $post     The post in question.
		 *
		 * @return WP_Term
		 */
		public function register_primary_category_permalinks( $category, $cats, $post ) {

			$primary_category_slug = $this->get_primary_category( $post );

			if ( ! empty( $primary_category_slug ) ) {
				return get_category_by_slug( $primary_category_slug );
			}

			return $category;
		}

		/**
		 * Get the id of the primary category.
		 *
		 * @since 0.1.0
		 * @access protected
		 *
		 * @param WP_Post $post The post object.
		 *
		 * @return int $primary_term Primary category id
		 */
		protected function get_primary_category( $post = null ) {
			if ( empty( $post ) ) {
				return false;
			}

			$primary_term = get_post_meta( $post->ID, TENUP_PRIMARY_CATEGORY_META_KEY, true );

			return ! empty( $primary_term ) ? $primary_term : false;
		}

		/**
		 * List all post with primary category.
		 *
		 * @param object $query Main Query object.
		 *
		 * @since 0.1.0
		 * @access public
		 *
		 * @return void
		 */
		public function list_post_with_primary_category( $query ) {
			global $wp, $wp_rewrite;

			$category_name        = $query->get( 'category_name' );
			$category_permastruct = $wp_rewrite->get_category_permastruct();

			if ( ! $category_name ) {
				return;
			}

			$category_permalink = str_replace( '/%category%', '', $category_permastruct );
			$current_url        = home_url( $wp->request );

			/**
			 * We don't want to alter the behaviour of the main category archives page.
			 * However, for other pages, we would like to filter the posts to only show posts with the primary category.
			 */
			if ( $current_url &&
				$category_permalink &&
				false === strpos( $current_url, $category_permalink ) &&
				$query->is_main_query()
			) {
				$query->set(
					'meta_query',
					array(
						'relation' => 'AND', // Use AND for taking result on both condition true.
						array(
							'key'   => TENUP_PRIMARY_CATEGORY_META_KEY,
							'value' => $category_name,
						),
					)
				);
			}
		}
	}
}
