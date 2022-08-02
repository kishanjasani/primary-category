<?php
/**
 * Class AssetsTest
 *
 * @package TenUp_Primary_Category
 */

namespace TenUp_Primary_Category\Tests;

use TenUp_Primary_Category\Assets;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

/**
 * Asstes test case.
 */
class AssetsTest extends \WP_UnitTestCase {

	public $assets;

	protected function setUp(): void {
		parent::setUp();

		$this->assets = new Assets();
	}

	public function test_it_will_be_registered_on_admin_screens(): void {
		$mock = $this->getMockBuilder( stdObject::class )
			->setMethods( [ 'in_admin' ] )
			->getMock();

		$GLOBALS['current_screen'] = $mock;

		$mock->expects( $this->once() )
			->method( 'in_admin' )
			->will( $this->returnValue( true ) );

		$actual = is_admin();
		$this->assertTrue( $actual );

		$GLOBALS['current_screen'] = null;
	}

	public function test_it_will_not_be_registered_on_frontend(): void {
		$actual = is_admin();
		$this->assertFalse( $actual );
	}

	public function test_it_registers_script_on_registration(): void {
		$this->assets->register_scripts();
		$actual = wp_script_is( 'tenup-primary-category-meta-block-script', 'registered' );
		$this->assertTrue( $actual );
	}

	public function test_it_register_on_admin_enqueue_hook(): void {
		$this->assets->register_scripts();
		$actual = wp_script_is( 'tenup-primary-category-meta-block-script', 'queue' );

		$this->assertFalse( $actual );

		$this->assets->enqueue_scripts();
		$actual = wp_script_is( 'tenup-primary-category-meta-block-script', 'queue' );
		$this->assertTrue( $actual );
	}
}
