Brain
=====

Brain is a simple [Pimple](http://pimple.sensiolabs.org/) wrapper for WordPress.

It's the base package of the [Brain Project](http://giuseppe-mazzapica.github.io/Brain).

To register params and services in the container you should create a **module**, that is a class implementing `Brain\Module` inteface.

This interface is made by 3 methods:

1. `getBindings` that take the current container as argument, it is used to register services and params
2. `boot` to boot the module, i.e. to run something that should be done once. Method takes as argument the current container, so it can be used to do something on boot.
2. `getPath` that should return the absolute path of module folder


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
        add_action( 'loop_start', function() use( $brain ) {
          $brain['foo_service']->foo( $brain['foo'] );
        });
      }

      function getPath() {
        return dirname( __FILE__ );
      }
    }

###Finally add the module to Brain###

    add_action( 'brain_init', function( $brain ) {
      $brain->addModule( new FooModule );
    });

See [Pimple docs](http://pimple.sensiolabs.org/) for more info.


###Get data from Brain###

To get services registered is possible to use the `Brain::instance()` static method and use the array access method of Pimple, something like:

    $brain = Brain::instance();
    $foo_service = $brain['foo_service'];

or is possible to use the Brain `get` method, in chaining it with the `instance` method, just like:

    $foo_service = Brain::instance()->get('foo_service');


##Installation##

The package should be installed via [Composer](https://getcomposer.org/).
Brain is available through [packagist](https://packagist.org/), so you only need to add in your `composer.json` the require settings.
Something like so:

    "require": {
        "php": ">=5.4",
        "brain/brain": "dev-master"
    }

There is no need to explicitly require Pimple, because Brain will require it for you.
after that just

    $ composer install

and you are done. See [composer docs](https://getcomposer.org/doc/) for further details.


###Note on PHP version###

Pimple supports PHP 5.3+, however I don't want to support anymore that version, so even if (probably) the current version on Brain works with PHP 5.3, is possible that a nearly future release will not, I'll never test it.

##Related WordPress hooks##

Brain init itself and its modules on `'after_setup_theme'` with priority 0, a reasonably early hook that can be used in plugin and themes.
The Brain-related hooks are:

* `'brain_init'` to register modules (see above)
* `'brain_loaded'` is fired when all modules are loaded
* `'after_setup_theme'` with priority >= 1 (or any later hook) to get data from the container
