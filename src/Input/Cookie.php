<?php namespace Input;

class Cookie extends Input {

    public function __construct()
    {
        parent::__construct( INPUT_COOKIE );
    }

}