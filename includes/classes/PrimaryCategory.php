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
	}

}
