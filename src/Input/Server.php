<?php namespace Input;

class Server extends Input {

    public function __construct()
    {
        parent::__construct( INPUT_SERVER );
    }

}