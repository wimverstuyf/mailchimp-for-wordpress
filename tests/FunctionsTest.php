<?php
use PHPUnit\Framework\TestCase;

/**
 * Class Functions_Test
 * @ignore
 */
class Functions_Test extends TestCase {

    public $tests = array(
        array(
            'input' => array(),
            'output' => array(),
        ),
        array(
            'input' => array(
                'SOME_FIELD' => 'Some value',
                'SOME_OTHER_FIELD' => 'Some other value'
            ),
            'output' => array(
                'SOME_FIELD' => 'Some value',
                'SOME_OTHER_FIELD' => 'Some other value'
            ),
        ),
        array(
            'input' => array(
                'NAME' => 'Danny van Kooten'
            ),
            'output' => array(
                'NAME' => 'Danny van Kooten',
                'FNAME' => 'Danny',
                'LNAME' => 'van Kooten'
            ),
        ),
        array(
            'input' => array(
                'NAME' => 'Danny'
            ),
            'output' => array(
                'NAME' => 'Danny',
                'FNAME' => 'Danny',
            ),
        ),
    );


    /**
     * @covers pl4wp_obfuscate_email_addresses()
     */
    public function test_pl4wp_obfuscate_email_addresses() {

        // by no means should the two strings be similar
        $string = 'PhpList API error: Recipient "johnnydoe@gmail.com" has too many recent signup requests';
        $obfuscated = pl4wp_obfuscate_email_addresses( $string );
        self::assertNotEquals( $string, $obfuscated );

        // less than 70% of the string should be similar
        $string = 'johnnydoe@gmail.com';
        $obfuscated = pl4wp_obfuscate_email_addresses( $string );
        similar_text( $string, $obfuscated, $percentage );
        self::assertTrue( $percentage <= 70 );
    }

    /**
     * @covers pl4wp_obfuscate_string
     */
    public function test_pl4wp_obfuscate_string() {

        // by no means should the two strings be similar
        $string = 'super-secret-string';
        $obfuscated = pl4wp_obfuscate_string( $string );
        self::assertNotEquals( $string, $obfuscated );

        // less than 50% of the string should be similar
        similar_text( $string, $obfuscated, $percentage );
        self::assertTrue( $percentage <= 50 );
    }

    /**
     * @covers pl4wp_add_name_data
     */
    public function test_pl4wp_add_name_data() {
        foreach( $this->tests as $test ) {
            self::assertEquals( pl4wp_add_name_data( $test['input'] ), $test['output'] );
        }
    }

    /**
     * @covers pl4wp_guess_merge_vars
     */
    public function test_pl4wp_guess_merge_vars() {

       foreach( $this->tests as $test ) {
           self::assertEquals( pl4wp_guess_merge_vars( $test['input'] ), $test['output'] );
       }
    }

    /**
     * @covers pl4wp_array_get
     */
    public function test_pl4wp_array_get() {
        self::assertEquals( pl4wp_array_get( array( 'foo' => 'bar' ), 'foo' ), 'bar' );
        self::assertEquals( pl4wp_array_get( array( 'foo' => 'bar' ), 'foofoo', 'default' ), 'default' );
        self::assertEquals( pl4wp_array_get( array( 'foo' => array( 'bar' => 'foobar' ) ), 'foo.bar' ), 'foobar' );
        self::assertEquals( pl4wp_array_get( array( 'foo' => array( 'bar' => 'foobar' ) ), 'foo.foo', 'default' ), 'default' );     
    }
}
