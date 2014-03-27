<?php

use \Input\Validate;

class ValidateTest extends PHPUnit_Framework_TestCase {

    public function testValidateBoolean()
    {
        $v = new Validate( 'yes' );
        $this->assertTrue( $v->boolean() );

        $v = new Validate( 1 );
        $this->assertTrue( $v->boolean() );

        $v = new Validate( 'off' );
        $this->assertFalse( $v->boolean() );
    }

    public function testEmail()
    {
        $v = new Validate('test@test.com');
        $this->assertTrue( $v->email() );
    }

    public function testFloat()
    {
        $v = new Validate(12.02);
        $this->assertTrue( $v->float() );
    }

    public function testIp()
    {
        $v = new Validate('192.168.1.1');
        $this->assertTrue( $v->ip() );
    }

    public function testUrl()
    {
        $v = new Validate('http://www.mybigballs.com');
        $this->assertTrue( $v->url() );
    }

    public function testMaxLength()
    {
        $v = new Validate('abcderfefd');
        $this->assertTrue( $v->maxlength( 10 ));
        $v = new Validate('abcderfefdasdf');
        $this->assertFalse( $v->maxlength( 10 ));

        $v = new Validate(range(0,4));
        $this->assertTrue( $v->maxlength( 10 ));
    }

    public function testMinLength()
    {
        $v = new Validate('abcderfefd');
        $this->assertTrue( $v->minlength( 4 ));
        $v = new Validate('ab');
        $this->assertFalse( $v->minlength( 4 ));

        $v = new Validate(range(0,4));
        $this->assertTrue( $v->minlength( 2 ));
    }

    public function testMax()
    {
        $v = new Validate(2);
        $this->assertTrue( $v->max( 10 ));
        $v = new Validate( 23);
        $this->assertFalse( $v->max( 10 ));
    }

    public function testMin()
    {
        $v = new Validate(4);
        $this->assertTrue( $v->min( 3 ));
        $v = new Validate(5);
        $this->assertFalse( $v->min( 10 ));
    }

    public function testBetween()
    {
        $v = new Validate(5);
        $this->assertTrue( $v->between(1,10));
        $this->assertFalse( $v->between(10,20));

        $v = new Validate(range(0,4));
        $this->assertTrue( $v->between(1,10));
    }

    public function testAddValidator()
    {
        Validate::addValidator('test', function($value){
            return $value === 'test';
        });

        $v = new Validate('test');
        $this->assertTrue( $v->test() );
    }

    public function testAlpha()
    {
        $v = new Validate('abcd');
        $this->assertTrue( (bool) $v->alpha() );

        $v = new Validate('abcd23');
        $this->assertFalse( (bool) $v->alpha() );
    }

    public function testAlphaNumDash()
    {
        $v = new Validate('ab-cd');
        $this->assertTrue( (bool) $v->alphanumdash() );

        $v = new Validate('abcd@');
        $this->assertFalse( (bool) $v->alphanumdash() );
    }

    public function testAlphaNum()
    {
        $v = new Validate('abcd23');
        $this->assertTrue( (bool) $v->alphanum() );

        $v = new Validate('abcd#');
        $this->assertFalse( (bool) $v->alphanum() );
    }

    public function testIn()
    {
        $v = new Validate(5);
        $this->assertTrue( $v->in( range(1,10)));

        $v = new Validate(8);
        $this->assertFalse( $v->in( range(3,7)));
    }

    public function testRequired()
    {
        $v = new Validate('');
        $this->assertFalse( $v->required() );

        $v = new Validate('a');
        $this->assertTrue( $v->required() );
    }
}