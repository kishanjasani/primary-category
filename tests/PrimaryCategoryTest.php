<?php
/**
 *
 * Class PrimaryCategoryTest
 * @package TenUp_Primary_Category
 */

namespace TenUp_Primary_Category\Tests;

use TenUp_Primary_Category\PrimaryCategory;

/**
 * PrimaryCategory test case.
 */
class PrimaryCategoryTest extends \WP_UnitTestCase {

	private $instance;
	private $categories;
	private $post;
	private $primary_category;

	public function setUp(): void {
		parent::setUp();

		global $wp_rewrite;

		$wp_rewrite->init();
		$wp_rewrite->set_permalink_structure( '/%category%/%postname%/' );

		/**
		 * This was needed as set_category_base does not work in PHPUnit.
		 */
		$wp_rewrite->add_permastruct(
			'primary_category',
			'category/%category%',
			array(
				'with_front'   => true,
				'hierarchical' => true,
				'ep_mask'      => 256,
				'slug'         => 'category',
			)
		);

		$wp_rewrite->flush_rules();

		$this->categories = self::factory()->term->create_many(
			5,
			array(
				'taxonomy' => 'category',
			)
		);

		$this->post = self::factory()->post->create_and_get(
			array(
				'post_title'  => 'Test Post',
				'post_status' => 'publish',
			)
		);

		$this->instance         = new PrimaryCategory();
		$this->primary_category = get_category( $this->categories[1] );

		wp_set_post_categories( $this->post->ID, $this->categories );
		update_post_meta( $this->post->ID, TENUP_PRIMARY_CATEGORY_META_KEY, $this->primary_category->slug );
	}

	/**
	 * Call protected/private method of a class.
	 *
	 * @param object &$object     Instantiated object that we will run method on.
	 * @param string $method_name Method name to call
	 * @param array  $parameters  Array of parameters to pass into method.
	 *
	 * @throws \ReflectionException
	 *
	 * @return mixed Method return.
	 */
	public function invoke_hidden_method( &$object, $method_name, array $parameters = array() ) {

		$reflection = new \ReflectionClass( get_class( $object ) );

		$method = $reflection->getMethod( $method_name );

		$method->setAccessible( true );

		return $method->invokeArgs( $object, $parameters );
	}

	public function test_it_permalink_contains_primary_category() {
		$post_permalink = get_permalink( $this->post );
		$this->assertStringContainsString( $this->primary_category->slug, $post_permalink );
	}

	public function test_it_fetch_primary_category_from_post() {
		update_post_meta( $this->post->ID, TENUP_PRIMARY_CATEGORY_META_KEY, '' );

		$actual = $this->invoke_hidden_method( $this->instance, 'get_primary_category', [ $this->post ] );
		$this->assertFalse( $actual );

		$primary_category = get_category( $this->categories[3] );
		update_post_meta( $this->post->ID, TENUP_PRIMARY_CATEGORY_META_KEY, $primary_category->slug );

		$actual = $this->invoke_hidden_method( $this->instance, 'get_primary_category', [ $this->post ] );
		$this->assertEquals( $primary_category->slug, $actual );
	}
}
