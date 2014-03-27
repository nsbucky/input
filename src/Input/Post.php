<?php namespace Input;

class Post extends Input {

    public function __construct()
    {
        parent::__construct( INPUT_POST );
    }

}