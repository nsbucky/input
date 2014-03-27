<?php namespace Input;

class Validate {

    protected $value;

    /**
     * add closure validators: function($value){...}
     * @var array
     */
    public static $validators = [];

    public function __construct( $value )
    {
        $this->value = $value;
    }


    public function boolean()
    {
        return filter_var( $this->value, FILTER_VALIDATE_BOOLEAN );
    }

    public function email()
    {
        return filter_var( $this->value, FILTER_VALIDATE_EMAIL);
    }

    public function float()
    {
        return filter_var( $this->value, FILTER_VALIDATE_FLOAT);
    }

    public function ip()
    {
        return filter_var( $this->value, FILTER_VALIDATE_IP );
    }

    public function url()
    {
        return filter_var( $this->value, FILTER_VALIDATE_URL );
    }

    public function phone()
    {
        //
    }

    public function maxlength( $length )
    {

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

}