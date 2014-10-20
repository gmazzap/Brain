<?php namespace Brain\Tests\Unit;

use Brain\Tests\TestCase;
use Brain\Container;
use Brain\Tests\ModuleStub;

class ContainerTest extends TestCase {

    function testBootReturnContainer() {
        $boot = Container::boot( new \Pimple\Container, FALSE, FALSE );
        assertInstanceOf( 'Brain\Container', $boot );
    }

    function testIntanceReturnContainer() {
        $boot = Container::boot( new \Pimple\Container, FALSE, FALSE );
        assertTrue( $boot === Container::instance() );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    function testGetFailsIfBadId() {
        $boot = Container::boot( new \Pimple\Container, FALSE, FALSE );
        $boot->get( TRUE );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    function testGetFailsIfNonExistentId() {
        $boot = Container::boot( new \Pimple\Container, FALSE, FALSE );
        $boot->get( 'foo' );
    }

    function testGet() {
        $boot = Container::boot( new \Pimple\Container, FALSE, FALSE );
        assertInstanceOf( 'Pimple\Container', $boot );
        $boot[ 'test' ] = function() {
            return (object) [ 'foo' => 'bar' ];
        };
        assertEquals( 'bar', $boot->get( 'test' )->foo );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    function testSetFailsIfBadId() {
        $boot = Container::boot( new \Pimple\Container, FALSE, FALSE );
        $boot->set( TRUE );
    }

    function testSet() {
        $boot = Container::boot( new \Pimple\Container, FALSE, FALSE );
        $test = (object) [ 'foo' => 'bar' ];
        $boot->set( 'test', $test );
        assertTrue( $test === $boot[ 'test' ] );
    }

    /**
     * @expectedException \DomainException
     */
    function testAddModuleFailsIdNotBooted() {
        $container = new Container;
        $container->addModule( new ModuleStub );
    }

    function testAddModule() {
        $boot = Container::boot( new \Pimple\Container, FALSE, FALSE );
        $boot->addModule( new ModuleStub );
        Container::bootModules( $boot, FALSE );
        assertInstanceOf( 'Brain\Tests\ServiceStub', $boot->get( 'brain.tests.stub' ) );
    }

    function testBootModules() {
        $boot = Container::boot( new \Pimple\Container, FALSE, FALSE );
        $boot->addModule( new ModuleStub );
        Container::bootModules( $boot, FALSE );
        $service = $boot->get( 'brain.tests.stub' )->set();
        assertEquals( [ 'booted' => TRUE, 'setted' => TRUE ], $service->get() );
    }

}