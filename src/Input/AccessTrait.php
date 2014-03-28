<?php namespace Input;

trait AccessTrait {

    protected $input = [];

    /**
     * @param $name
     * @param null $defaultValue
     * @return mixed
     */
    public function value($name, $defaultValue = null)
    {
        if( $this->has($name) ) {
            return $this->input[ $name ];
        }

        return $defaultValue;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function get($name)
    {
        return $this->value($name);
    }

    /**
     * @param $name
     * @return bool
     */
    public function has( $name )
    {
        if( count( func_get_args() ) > 1 ) {
            foreach( func_get_args() as $value ) {
                if( !$this->has( $value ) ) {
                    return false;
                }
            }

            return true;
        }

        if( is_bool( $this->input[ $name ] )
            || is_array( $this->input[ $name ] )
            || is_object( $this->input[$name] ) ) {
            return true;
        }

        return trim( (string) $this->input[ $name ] ) !== '';
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->input;
    }

    /**
     * @param mixed $wanted
     * @return array
     */
    public function only( $wanted )
    {
        $args = func_get_args();

        if( is_array( $args[0] ) ) {
            return array_intersect_key( $this->input, array_flip( $args[0] ) );
        }

        return array_intersect_key( $this->input, array_flip( $args ) );
    }

    /**
     * @param mixed $notWanted
     * @return array
     */
    public function except( $notWanted )
    {
        $args = func_get_args();

        if( is_array( $args[0] ) ) {
            return array_diff_key( $this->input, array_flip( $args[0] ) );
        }

        return array_diff_key( $this->input, array_flip( $args ) );
    }


}