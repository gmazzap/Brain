<?php namespace Brain\Tests;

class ModuleStub implements \Brain\Module {

    public function boot( \Brain\Container $brain ) {
        $brain->get( 'brain.tests.stub' )->boot();
    }

    public function getBindings( \Brain\Container $brain ) {
        $brain['brain.tests.arrayobject'] = function() {
            return new \ArrayObject;
        };
        $brain['brain.tests.stub'] = function( $brain ) {
            return new ServiceStub( $brain['brain.tests.arrayobject'] );
        };
    }

    public function getPath() {
        return BRAINPATH;
    }

}