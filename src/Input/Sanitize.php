<?php namespace Input;

class Sanitize
{

    protected $value;

    /**
     * add closure filters: function($value){...}
     * @var array
     */
    protected static $filters = [];

    /**
     * @param $value
     */
    public function __construct( $value )
    {
        $this->value = $value;
    }

    /**
     * @return integer
     */
    public function int()
    {
        $value = filter_var( $this->value, FILTER_SANITIZE_NUMBER_INT );

        return (int) $value;
    }

    /**
     * @return mixed
     */
    public function email()
    {
        return filter_var( $this->value, FILTER_SANITIZE_EMAIL );
    }

    /**
     * @return mixed
     */
    public function encoded()
    {
        return filter_var( $this->value, FILTER_SANITIZE_ENCODED );
    }

    /**
     * @return mixed
     */
    public function float()
    {
        return filter_var( $this->value, FILTER_SANITIZE_NUMBER_FLOAT, [
            'flags'  => FILTER_FLAG_ALLOW_FRACTION
        ] );
    }

    /**
     * @return mixed
     */
    public function escape()
    {
        return filter_var( $this->value, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
    }

    /**
     * @return mixed
     */
    public function chars()
    {
        return filter_var( $this->value, FILTER_SANITIZE_SPECIAL_CHARS );
    }

    /**
     * @return mixed
     */
    public function stripped()
    {
        return filter_var( $this->value, FILTER_SANITIZE_STRING );
    }

    /**
     * @return mixed
     */
    public function url()
    {
        return filter_var( $this->value, FILTER_SANITIZE_URL );
    }

    /**
     * @param $method
     * @param $args
     * @return mixed
     */
    public function __call( $method, $args )
    {
        if( array_key_exists( $method, self::$filters ) ) {
            $func = self::$filters[$method];

            return $func( $this->value );
        }
    }

    /**
     * @param $name
     * @param $filter
     */
    public static function addFilter( $name, $filter )
    {
        if( is_callable( $filter )) {
            self::$filters[ $name ] = $filter;
        }
    }

}