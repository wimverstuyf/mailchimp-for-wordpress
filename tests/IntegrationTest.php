<?php
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Assert;

/**
 * Class IntegrationTest
 * @ignore
 */
class IntegrationTest extends TestCase {

	/**
	 * @covers PL4WP_Integration::__construct
	 */
	public function test_constructor() {
		$slug = 'my-integration';

		$instance = $this->getMockForAbstractClass('PL4WP_Integration', array(
			$slug,
			array()
		));

		self::assertAttributeEquals( $slug, 'slug', $instance );
	}

	/**
	 * @covers PL4WP_Integration::checkbox_was_checked
	 */
	public function test_checkbox_was_checked() {
		$slug = 'my-integration';
		$instance = $this->getMockForAbstractClass('PL4WP_Integration', array(
			$slug,
			array()
		));
		self::assertFalse( $instance->checkbox_was_checked() );

		// copy of request data is stored in constructor so we should create a new instance to replicate
		$_POST[ Assert::readAttribute( $instance, 'checkbox_name' ) ] = 1;
		self::assertTrue( $instance->checkbox_was_checked() );
	}

}
