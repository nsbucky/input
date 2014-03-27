<?php namespace Input;

class Get extends Input {

    public function __construct()
    {
        parent::__construct( INPUT_GET );
    }

}