<?php

use \Input\Input,
    \Input\Container;

class InputTest extends PHPUnit_Framework_TestCase {

    public $sampleInput = [
        'first_name' => 'John',
        'last_name'  => 'Stamos',
        'email'      => 'fullhouse@hotmale.com',
        'phone'      => '8587896541',
    ];

    /** @var  Container */
    public $container;

    public function __construct()
    {
        $this->container = new Container( $this->sampleInput );
        return parent::__construct();
    }

    public function testAll()
    {
        $this->assertEquals( $this->sampleInput, $this->container->all() );
    }

    public function testValue()
    {
        $this->assertEquals( $this->sampleInput['first_name'], $this->container->value('first_name'));
    }

    public function testDefaultValue()
    {
        $this->assertEquals( 'balls', $this->container->value('dick','balls'));
    }

    public function testHas()
    {
        $this->assertTrue( $this->container->has('first_name') );
    }

    public function testOnly()
    {
        $input = $this->sampleInput;
        unset($input['email'], $input['phone']);
        $this->assertEquals( $input, $this->container->only('first_name','last_name') );
    }

    public function testExcept()
    {
        $input = $this->sampleInput;
        unset($input['last_name']);
        $this->assertEquals( $input, $this->container->except('last_name') );
    }

    public function testAsArray()
    {
        $this->assertEquals( $this->sampleInput['first_name'], $this->container['first_name']);

        $this->assertEquals( count($this->sampleInput), count($this->container));
    }

    public function testGetArrayValue()
    {
        $this->sampleInput['list'] = ['balls'];
        $container = new Container( $this->sampleInput );

        $this->assertEquals( $this->sampleInput['list'], $container['list'] );
        $this->assertTrue( is_array($container['list']) );
        $this->assertTrue( is_array($container->value('list')) );
    }

    public function testMagicCallAs()
    {
        $phone = (int) $this->sampleInput['phone'];

        $this->assertEquals( $phone, $this->container->asInt('phone'));
    }

    public function testMagicCallIs()
    {
        $this->assertTrue( (bool) $this->container->isEmail('email') );
    }

    public function testMagicCallNot()
    {
        $this->assertTrue( (bool) $this->container->notEmail('phone') );
    }
}