<?php
if ( ! class_exists( 'Brain\Container' ) ) require_once __DIR__ . '/vendor/autoload.php';

if ( function_exists( 'add_action' ) ) {
    add_action( 'after_setup_theme',
        function() {
        Brain\Container::boot( new \Pimple );
    }, 0 );
}
