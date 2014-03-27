<?php namespace Input;

class Server extends Input {

    public function __construct()
    {
        parent::__construct( INPUT_SERVER );
    }

    public function getRequestMethod()
    {
        return $this->value( 'REQUEST_METHOD' );
    }

    public function getReferrer()
    {
        return $this->value( 'HTTP_REFERER' );
    }

    public function getUserAgent()
    {
        return $this->value( 'HTTP_USER_AGENT' );
    }

    public function isSecure()
    {
        return (bool) $this->value( 'HTTPS' );
    }

    public function getIp()
    {
        return $this->value( 'REMOTE_ADDR' );
    }

    public function getRequestUri()
    {
        return $this->value( 'REQUEST_URI' );
    }

}