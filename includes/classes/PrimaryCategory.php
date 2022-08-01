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
		 * @since 0.1
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
		 * @since 0.1
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
		 * @since 0.1
		 * @access public
		 *
		 * @return void
		 */
		public function register_hooks() {
			// Register action and filters.
			add_action( 'init', [ $this, 'primary_category_register_metabox' ] );
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
	}

}
