<?php namespace Input;

class Container extends Input {

    public function __construct( array $inputArray )
    {
        parent::__construct( self::INPUT_ARRAY, $inputArray );
    }

    /**
     * need to overwrite the parent method as it calls filter_has_var, which
     * will only work when a PHP input type is present. It will not work for
     * user created arrays.
     *
     * @param $name
     * @return bool
     */
    public function has( $name )
    {
        return array_key_exists( $name, $this->parameters );
    }

}