<?php namespace Brain\Tests;

class ServiceStub {

    public $array_object;

    function __construct( \ArrayObject $array_object ) {
        $this->array_object = $array_object;
    }

    public function boot() {
        $this->array_object['booted'] = TRUE;
    }

    public function set() {
        $this->array_object['setted'] = TRUE;
        return $this;
    }

    public function get() {
        return $this->array_object->getArrayCopy();
    }

}