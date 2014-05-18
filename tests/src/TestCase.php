<?php namespace Brain\Tests;

class TestCase extends \PHPUnit_Framework_TestCase {

    function setUp() {
        parent::setUp();
        \Brain\Container::flush();
    }

}