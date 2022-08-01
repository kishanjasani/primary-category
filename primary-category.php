<?php

/**
 * Plugin Name:       Ten Up Primary Category
 * Description:       Allows you to choose primary category from categories to posts
 * Requires at least: 5.9
 * Requires PHP:      7.4
 * Version:           0.1.0
 * Author:            Kishan Jasani
 * License:           GPL-3.0
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Domain Path:       /languages
 * Text Domain:       tenup-primary-category
 *
 * @package           TenUp_Primary_Category
 */

use TenUp_Primary_Category\PrimaryCategory;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( ! defined( 'TENUP_PRIMARY_CATEGORY_PLUGIN_PATH' ) ) {
	/**
	 * Path to the plugin folder.
	 *
	 * @since 0.1.0
	 */
	define( 'TENUP_PRIMARY_CATEGORY_PLUGIN_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
}

if ( ! defined( 'TENUP_PRIMARY_CATEGORY_PLUGIN_URL' ) ) {
	/**
	 * URL to the plugin folder.
	 *
	 * @since 0.1.0
	 */
	define( 'TENUP_PRIMARY_CATEGORY_PLUGIN_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
}

/**
 * Primary category meta key.
 *
 * @since 0.1.0
 */
const TENUP_PRIMARY_CATEGORY_META_KEY = '_tenup_primary_category';

// Require Composer autoloader if it exists.
if ( file_exists( TENUP_PRIMARY_CATEGORY_PLUGIN_PATH . '/vendor/autoload.php' ) ) {
	require_once TENUP_PRIMARY_CATEGORY_PLUGIN_PATH . '/vendor/autoload.php';
}

PrimaryCategory::get_instance();
