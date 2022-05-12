<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\Test;

use Phue\Client;
use Phue\Scene;

/**
 * Tests for Phue\Scene
 */
class SceneTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Set up
     *
     * @covers \Phue\Scene::__construct
     */
    public function setUp()
    {
        // Mock client
        $this->mockClient = $this->createMock('\Phue\Client', 
            array(
                'sendCommand'
            ), array(
                '127.0.0.1'
            ));
        
        // Build stub attributes
        $this->attributes = (object) array(
            'name' => 'Dummy scene',
            'lights' => array(
                2,
                3,
                5
            )
        );
        
        // Create scene object
        $this->scene = new Scene('custom-id', $this->attributes, $this->mockClient);
    }

    /**
     * Test: Getting Id
     *
     * @covers \Phue\Scene::getId
     */
    public function testGetId()
    {
        $this->assertEquals('custom-id', $this->scene->getId());
    }

    /**
     * Test: Getting name
     *
     * @covers \Phue\Scene::getName
     */
    public function testGetName()
    {
        $this->assertEquals($this->attributes->name, $this->scene->getName());
    }

    /**
     * Test: Get light ids
     *
     * @covers \Phue\Scene::getLightIds
     */
    public function testGetLightIds()
    {
        $this->assertEquals($this->attributes->lights, $this->scene->getLightIds());
    }

    /**
     * Test: toString
     *
     * @covers \Phue\Scene::__toString
     */
    public function testToString()
    {
        $this->assertEquals($this->scene->getId(), (string) $this->scene);
    }
}
