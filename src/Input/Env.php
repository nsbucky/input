<?php namespace Input;

class Env extends Input {

    public function __construct()
    {
        parent::__construct( INPUT_ENV );
    }

}