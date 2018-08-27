<?php

use PHPUnit\Framework\TestCase;

use Brain\Monkey;
use Brain\Monkey\Functions;

/**
 * Class DynamicContentTagTest
 * @ignore
 */
class DynamicContentTagTest extends TestCase {

	/**
	 * @var PL4WP_Dynamic_Content_Tags
	 */
	protected $instance;

	/**
	 * Runs before all tests
	 */
	public function setUp() {
		parent::setUp();
		Monkey\setUp();
		$this->instance = new PL4WP_Dynamic_Content_Tags( 'context' );
	}

	/**
	 * Runs after all tests
	 */
	protected function tearDown() {
		Monkey\tearDown();
		parent::tearDown();
	}

	/**
	 * @covers PL4WP_Dynamic_Content_Tags::__construct
	 */
	public function test_constructor() {
		$context = 'something';
		self::assertAttributeEquals( $context, 'context', new PL4WP_Dynamic_Content_Tags( $context ) );
	}

	/**
	 * @covers PL4WP_Dynamic_Content_Tags::escape_value_url
	 */
	public function test_escape_value_url() {

		$reflectionOfUser = new ReflectionClass('PL4WP_Dynamic_Content_Tags');
		$method = $reflectionOfUser->getMethod('escape_value_url');
		$method->setAccessible(true);

		$value = 'john@email.com';
		self::assertEquals( $method->invoke( $this->instance, $value ), urlencode( $value ) );
	}

	/**
	 * @covers PL4WP_Dynamic_Content_Tags::escape_value_html
	 */
	public function test_escape_value_html() {

		$reflection = new ReflectionClass('PL4WP_Dynamic_Content_Tags');
		$method = $reflection->getMethod('escape_value_html');
		$method->setAccessible(true);

		$value = '<script>alert("hi");</script>';

		// just test if "esc_html" is called
		Functions\when('esc_html')->justReturn('called');
		self::assertEquals( $method->invoke( $this->instance, $value ), 'called' );
	}

	/**
	 * @covers PL4WP_Dynamic_Content_Tags::escape_value_url
	 */
	public function test_escape_attributes() {
		$reflection = new ReflectionClass('PL4WP_Dynamic_Content_Tags');
		$method = $reflection->getMethod('escape_value_attributes');
		$method->setAccessible(true);

		$value = 'an-invalid="attribute string"';

		// just test if "esc_html" is called
		Functions\when('esc_attr')->justReturn('called');
		self::assertEquals( $method->invoke( $this->instance, $value ), 'called' );
	}

	/**
	 * @covers PL4WP_Dynamic_Content_Tags::replace
	 */
	public function test_replace() {

		$tags = array(
			'sample_tag' => array (
				'replacement' => 'sample replacement'
			)
		);
		$instance = new PL4WP_Dynamic_Content_Tags( 'context', $tags );

		// default
		$string = "String with {sample_tag} in it.";
		self::assertEquals( "String with sample replacement in it.", $instance->replace( $string ) );

		// with double-quoted attribute
		$string = "String with {sample_tag attribute=\"value with space\"} in it.";
		self::assertEquals( "String with sample replacement in it.", $instance->replace( $string ) );

		// with unquoted attribute
		$string = "String with {sample_tag attribute=value with spaces} in it.";
		self::assertEquals( "String with sample replacement in it.", $instance->replace( $string ) );

		// with single-quoted attribute
		$string = "String with {sample_tag attribute='value with spaces'} in it.";
		self::assertEquals( "String with sample replacement in it.", $instance->replace( $string ) );

		// space after opening tag, do notihing
		$string = "String with { sample_tag attribute=\"value\"} in it.";
		self::assertEquals( $string, $instance->replace( $string ) );
	}

	/**
	 * @covers PL4WP_Dynamic_Content_Tags::replace
	 */
	public function test_replace_with_callback() {
		$tags = array(
			'sample_tag' => array (
				'callback' => function( $attributes ) {

					if( ! empty( $attributes['return'] ) ) {
						return $attributes['return'];
					}

					return 'sample replacement';
				}
			)
		);
		$instance = new PL4WP_Dynamic_Content_Tags( 'context', $tags );

		// normal
		$string = "String with {sample_tag} in it.";
		self::assertEquals( "String with sample replacement in it.", $instance->replace( $string ) );

		// attribute
		$string = "String with {sample_tag attribute=value} in it.";
		self::assertEquals( "String with sample replacement in it.", $instance->replace( $string ) );

		// "default" attribute
		$string = "String with {sample_tag return=value} in it.";
		self::assertEquals( "String with value in it.", $instance->replace( $string ) );

	}


}
