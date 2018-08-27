<?php
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Assert;


/**
 * Class IntegrationManagerTest
 *
 * @ignore
 */
class IntegrationManagerTest extends TestCase {

	public function test_constructor() {
		$instance = new PL4WP_Integration_Manager();
		$property = Assert::readAttribute( $instance, 'tags' );
		self::assertInstanceOf( 'PL4WP_Integration_Tags', $property );
	}

	/**
	 * @covers PL4WP_Integration_Manager::register_integration
	 * @covers PL4WP_Integration_Manager::get_enabled_integrations
	 * @covers PL4WP_Integration_Manager::get_all
	 */
	public function test_register_integration() {
		$instance = new PL4WP_Integration_Manager();
		$instance->register_integration( 'slug', 'PL4WP_Sample_Integration', false );

		self::assertNotEmpty( $instance->get_all() );
		self::assertEmpty( $instance->get_enabled_integrations() );

		$instance->register_integration( 'another-slug', 'PL4WP_Sample_Integration', true );
		self::assertNotEmpty( $instance->get_enabled_integrations() );

		// if we register same slug twice, former should be overwritten so count should still be 2 here
		$instance->register_integration( 'slug', 'PL4WP_Sample_Integration', false );
		self::assertCount( 2, $instance->get_all() );
	}

	/**
	 * @covers PL4WP_Integration_Manager::deregister_integration
	 * @covers PL4WP_Integration_Manager::get_enabled_integrations
	 * @covers PL4WP_Integration_Manager::get_all
	 */
	public function test_deregister_integration() {
		$instance = new PL4WP_Integration_Manager();
		$instance->register_integration( 'slug', 'ClassName', true );
		$instance->deregister_integration('slug');

		self::assertEmpty( $instance->get_all() );
		self::assertEmpty( $instance->get_enabled_integrations() );
	}

	/**
	 * @covers PL4WP_Integration_Manager::get
	 */
	public function test_get() {
		$instance = new PL4WP_Integration_Manager();
		self::expectException( 'Exception' );
		$instance->get('non-existing-slug');


		$instance->register_integration( 'slug', 'PL4WP_Sample_Integration', true );
		self::expectException(null);
		self::assertInstanceOf( 'PL4WP_Sample_Integration', $instance->get('slug') );
	}




}

class PL4WP_Sample_Integration extends PL4WP_Integration {
	public function add_hooks() {}
	public function is_installed() {
		return true;
	}
}
