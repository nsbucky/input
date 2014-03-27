<?php namespace Input;

interface InputInterface {
    public function value( $name, $defaultValue = null);
    public function has( $name );
    public function only( $wanted );
    public function except( $notWanted );
    public function all();
}