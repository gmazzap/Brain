<?php
namespace Brain;

class Container extends \Pimple {

    private static $brain;

    private $brain_modules;


    public static function boot( \Pimple $container, $with_modules = TRUE ) {
        if ( is_null( static::$brain ) ) {
            static::$brain = $container;
            static::$brain[ 'embedded' ] = function () {
                return new static;
            };
            $instance = static::$brain[ 'embedded' ];
            $instance->brain_modules = new \SplObjectStorage;
            if ( $with_modules !== FALSE ) static::bootModules( $instance );
        }
        return static::$brain[ 'embedded' ];
    }

    public static function flush() {
        static::$brain = NULL;
    }

    public static function instance() {
        if ( is_null( static::$brain ) || ! isset( static::$brain[ 'embedded' ] ) ) {
            throw new \DomainException;
        }
        return static::$brain[ 'embedded' ];
    }

    public function addModule( Module $module ) {
        if ( ! $this->brain_modules instanceof \SplObjectStorage ) {
            throw new \DomainException;
        }
        $this->brain_modules->attach( $module );
    }

    public function get( $id = '' ) {
        if ( ! is_string( $id ) ) throw new \DomainException;
        return $this[ $id ];
    }

    protected static function bootModules( Container $instance ) {
        // use this hook to register modules via Brain\Container::addModule()
        // or to add params/services to container thanks to the instance passed as action argument
        do_action( 'brain_init', $instance );
        $instance->brain_modules->rewind();
        while ( $instance->brain_modules->valid() ) {
            $module = $instance->brain_modules->current();
            $module->getBindings( $instance );
            $module->boot( $instance );
            $instance->brain_modules->next();
        }
        do_action( 'brain_loaded', $instance );
    }
}