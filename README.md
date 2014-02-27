Brain
=====

Brain is a simple [Pimple](http://pimple.sensiolabs.org/) wrapper for WordPress.

To register params and services in the container you should create a **module**, that is a class implementing `Brain\Module` inteface.

This interface is made by 2 metdods:

1. `getBindings` that take the current container as argument, it is used to register services and params
2. `boot` to boot the module, i.e. to run something that should be done once. Method takes as argument the current container, so it can be used to do something on boot.


To register the module, should be used the `addModule` method of container, but **only using `'brain_init'` hook**.

The `'brain_loaded'` hook is fired when all modules has been loaded.


##Usage Example##

###First define a service###

    class FooService {
    
      function foo( $foo = '' ) {
        echo '<p>Foo is ' . $foo . '</p>';
      }
      
    }

###Then define a Brain module###

    class FooModule implements Brain\Module {
	
      function getBindings( Brain\Container $brain ) {
        $brain['foo'] = 'bar';
        $brain['foo_service'] = function() { return new FooService; };
      }

      function boot( Brain\Container $brain ) {
        add_action( 'loop_start', function() {
          $brain['foo_service']->foo( $brain['foo'] );
        });
      }
    }

###Finally add the module to Brain###

    add_action( 'brain_init', function( $brain ) {
      $brain->addModule( new FooModule );
    });
    
    
Note that Brain init itself and its modules on `'after_setup_theme'` with priority 0, a reasonably early hook that can be used in plugin and themes.

See [Pimple docs](http://pimple.sensiolabs.org/) for more info.
	
