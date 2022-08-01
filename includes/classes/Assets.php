<?php
/**
 * Load Assets for the primary category.
 *
 * @package TenUp_Primary_Category
 */

namespace TenUp_Primary_Category;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( ! class_exists( 'Assets' ) ) {

	/**
	 * Assets.
	 *
	 * @since 0.1.0
	 */
	class Assets {

		/**
		 * The instance of the class Assets.
		 *
		 * @since 0.1.0
		 * @access protected
		 * @static
		 *
		 * @var Assets
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
			$this->register_hooks();
		}

		/**
		 * Returns the current instance of the class.
		 *
		 * @since 0.1.0
		 * @access public
		 * @static
		 *
		 * @return Assets Returns the current instance of the class.
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

			/**
			 * Enqueue scripts for the admin pages.
			 */
			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		}

		/**
		 * Admin Scripts.
		 *
		 * @since 0.1.0
		 * @access public
		 * @return void
		 */
		public function enqueue_scripts() {
			$asset_file_path = TENUP_PRIMARY_CATEGORY_PLUGIN_PATH . '/build/index.asset.php';
			$asset           = is_readable( $asset_file_path ) ? require $asset_file_path : array();

			wp_register_script(
				'tenup-primary-category-meta-block-script',
				TENUP_PRIMARY_CATEGORY_PLUGIN_URL . '/build/index.js',
				array_merge( $asset['dependencies'] ),
				filemtime( TENUP_PRIMARY_CATEGORY_PLUGIN_PATH . '/build/index.js' ),
				true
			);

			wp_enqueue_script( 'tenup-primary-category-meta-block-script' );
		}
	}
}
