<?php namespace Input;

class Validate {

    protected $value;

    /**
     * add closure validators: function($value){...}
     * @var array
     */
    protected  static $validators = [];

    public function __construct( $value )
    {
        $this->value = $value;
    }


    public function boolean()
    {
        $r = filter_var( $this->value, FILTER_VALIDATE_BOOLEAN );
        if( $r === false ) {
            return false;
        }

        return true;
    }

    public function email()
    {
        $r = filter_var( $this->value, FILTER_VALIDATE_EMAIL);
        if( $r === false ) {
            return false;
        }

        return true;
    }

    public function float()
    {
        $r = filter_var( $this->value, FILTER_VALIDATE_FLOAT);
        if( $r === false ) {
            return false;
        }

        return true;
    }

    public function ip()
    {
        $r = filter_var( $this->value, FILTER_VALIDATE_IP );
        if( $r === false ) {
            return false;
        }

        return true;
    }

    public function url()
    {
        $r = filter_var( $this->value, FILTER_VALIDATE_URL );
        if( $r === false ) {
            return false;
        }

        return true;
    }

    public function maxlength( $length )
    {
        if( is_array( $this->value ) ) {
            return (bool) ( count($this->value) <= $length );
        }

        return (bool) ( strlen( (string) $this->value ) <= $length );
    }

    public function minlength( $length )
    {
        if( is_array( $this->value ) ) {
            return (bool) ( count($this->value) >= $length );
        }

        return (bool) (strlen( (string) $this->value ) >= $length );
    }

    public function between( $min, $max )
    {
        if( is_array( $this->value ) ) {
            return (bool) ( count($this->value) >= $min && count($this->value) <= $max );
        }

        return (bool) ( strlen( (string) $this->value ) >= $min && (string) $this->value  <= $max );
    }

    public function alpha()
    {
        return preg_match('/^([a-z])+$/i', $this->value);
    }

    public function alphanumdash()
    {
        return preg_match('/^([a-z0-9_-])+$/i', $this->value);
    }

    public function alphanum()
    {
        return preg_match('/^([a-z0-9])+$/i', $this->value);
    }

    public function in( array $list )
    {
        return in_array( $this->value, $list);
    }

    public function min( $min )
    {
        $val = filter_var($this->value, FILTER_VALIDATE_INT, ['options'=> ['min_range' => (int) $min] ] );

        if( $val === false ) {
            return false;
        }

        return true;
    }

    public function max( $max )
    {
        $val = filter_var($this->value, FILTER_VALIDATE_INT, ['options'=> ['max_range' => (int) $max] ] );

        if( $val === false ) {
            return false;
        }

        return true;
    }

    public function required()
    {
        $v = trim( $this->value );
        return $v != '';
    }

    /**
     * @param $method
     * @param $args
     * @return mixed
     */
    public function __call( $method, $args )
    {
        if( array_key_exists( $method, self::$validators ) ) {
            $func = self::$validators[$method];

            return $func( $this->value );
        }
    }

    /**
     * @param $name
     * @param $filter
     */
    public static function addValidator( $name, $filter )
    {
        if( is_callable( $filter )) {
            self::$validators[ $name ] = $filter;
        }
    }

}