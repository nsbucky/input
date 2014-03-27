<?php namespace Input;

use \IteratorAggregate;
use \ArrayAccess;
use \Countable;
use \ArrayIterator;


class Input implements InputInterface, IteratorAggregate, ArrayAccess, Countable {

    const INPUT_ARRAY = 6;

    protected $inputType;

    protected $parameters = [];

    /**
     * @param integer $inputType
     * @param array $inputArray
     * @throws \RuntimeException
     */
    protected function __construct($inputType, array $inputArray = [])
    {
        $allowedInputs = [INPUT_GET, INPUT_POST, INPUT_COOKIE, INPUT_SERVER, INPUT_ENV, self::INPUT_ARRAY];

        if( ! in_array( $inputType, $allowedInputs) ) {
            throw new \RuntimeException('You must specify a valid PHP Input type: INPUT_GET, INPUT_POST, INPUT_COOKIE,
            INPUT_SERVER, or INPUT_ENV. You can also set an array with seLf::INPUT_ARRAY');
        }

        $this->inputType = $inputType;

        switch( $inputType ) {
            case INPUT_GET:
                $this->parameters = $_GET;
                break;
            case INPUT_POST:
                $this->parameters = $_POST;
                break;
            case INPUT_COOKIE:
                $this->parameters = $_COOKIE;
                break;
            case INPUT_SERVER:
                $this->parameters = $_SERVER;
                break;
            case INPUT_ENV:
                $this->parameters = $_ENV;
                break;
            case self::INPUT_ARRAY:
                $this->parameters = $inputArray;
                break;
        }
    }

    /**
     * @param $name
     * @param null $defaultValue
     * @return mixed
     */
    public function value($name, $defaultValue = null)
    {
        // if it has the variable, but the variable is actually empty
        // its safe to assume that if the defaultValue is set, then
        // we at least want that, not an empty value.
        if( $this->has( $name ) && ! is_null( $defaultValue) ) {
            return empty( $this->parameters[ $name ] ) ? $defaultValue : $this->parameters[ $name ];
        }

        // if the variable doesn't even exist in the request, well then just return
        // the defaultValue.
        if( ! $this->has( $name ) ) {
            return $defaultValue;
        }

        // nothing.
        return null;
    }

    /**
     * @param $name
     * @return bool
     */
    public function has( $name )
    {
        return filter_has_var( $this->inputType, $name );
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->parameters;
    }

    /**
     * @param mixed $wanted
     * @return array
     */
    public function only( $wanted )
    {
        $args = func_get_args();

        if( is_array( $args[0] ) ) {
            return array_intersect_key( $this->parameters, array_flip( $args[0] ) );
        }

        return array_intersect_key( $this->parameters, array_flip( $args ) );
    }

    /**
     * @param mixed $notWanted
     * @return array
     */
    public function except( $notWanted )
    {
        $args = func_get_args();

        if( is_array( $args[0] ) ) {
            return array_diff_key( $this->parameters, array_flip( $args[0] ) );
        }

        return array_diff_key( $this->parameters, array_flip( $args ) );
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists( $offset )
    {
        return $this->has( $offset );
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet( $offset )
    {
        return $this->value( $offset );
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet( $offset, $value )
    {
        // do not overwrite request vars.
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset( $offset )
    {
        // do not unset request vars.
    }

    /**
     * @return ArrayIterator|\Traversable
     */
    public function getIterator()
    {
        return new ArrayIterator( $this->parameters );
    }

    /**
     * @return int
     */
    public function count()
    {
        return count( $this->parameters );
    }

    /**
     * call magic sanitize or validation stuff
     * @param $method
     * @param $args
     * @return int
     */
    public function __call($method, $args)
    {
        // looking for is / no / as
        $sub    = substr( $method, 0, 2);

        if( $sub === 'as' ) {
            $key = $args[0];
            $funcName = substr( $method, 2);
            $sanitize = new Sanitize( $this->value($key) );
            return $sanitize->$funcName();
        }

        $negate          = false;
        $validatorMethod = null;

        if( $sub === 'is' ) {
            $validatorMethod = substr( $method, 2);
        }

        if( $sub === 'no' ) {
            $validatorMethod = substr( $method, 3); // account for 'not'
            $negate = true;
        }

        $key              = $args[0];
        $validator        = new Validate( $this->value($key) );

        switch( count($args) ) {
            case 2:
                $validationResult = $validator->$validatorMethod($args[1], $args[2]);
                break;
            case 3:
                $validationResult = $validator->$validatorMethod($args[1], $args[2], $args[3]);
                break;
            case 4:
                $validationResult = $validator->$validatorMethod($args[1], $args[2], $args[3], $args[4]);
                break;
            default:
                $validationResult = $validator->$validatorMethod();
                break;
        }

        // bitwise is cool but I'm dumb and needed a cheat sheet
        // true ^ false  = true
        // true ^ true   = false
        // false ^ false = false
        // false ^ true  = true
        return (bool) $validationResult ^ $negate;
    }

}