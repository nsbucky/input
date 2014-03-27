<?php

use \Input\Sanitize;

class SanitizeTest extends PHPUnit_Framework_TestCase {

    public function testSanitizeInt()
    {
        $int = '4d56';
        $s = new Sanitize( $int );
        $newInt = $s->int();
        $this->assertEquals(456, $newInt);
    }

    public function testEmail()
    {
        $email = '(test)@test.com';
        $s = new Sanitize( $email );
        $newEmail = $s->email();
        $this->assertEquals('test@test.com', $newEmail);
    }

    public function testEncoded()
    {
        $url = 'my butt';
        $s = new Sanitize( $url );
        $newUrl = $s->encoded();
        $this->assertEquals('my%20butt', $newUrl);
    }

    public function testFloat()
    {
        $s = new Sanitize('23.f56');
        $this->assertEquals(23.56, $s->float());
    }

    public function testEscape()
    {
        $s = new Sanitize('<asdf>"');
        $this->assertEquals('&#60;asdf&#62;&#34;', $s->escape());
    }

    public function testChars()
    {
        $s = new Sanitize('<asdf>');
        $this->assertEquals('&#60;asdf&#62;', $s->chars());
    }

    public function testStripped()
    {
        $s = new Sanitize('<asdf>bob');
        $this->assertEquals('bob', $s->stripped());
    }

    public function testUrl()
    {
        $s = new Sanitize('http://www.balls.com'.PHP_EOL);
        $this->assertEquals('http://www.balls.com', $s->url());
    }

}