<?php
if ( ! defined( 'ABSPATH' ) ) exit();
if ( ! class_exists( 'Brain\Container' ) ) require_once __DIR__ . '/vendor/autoload.php';

add_action( 'after_setup_theme', function() {
    Brain\Container::boot( new \Pimple );
}, 0 );
