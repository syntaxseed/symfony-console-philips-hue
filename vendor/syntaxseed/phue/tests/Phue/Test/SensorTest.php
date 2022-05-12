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
use Phue\Sensor;

/**
 * Tests for Phue\Sensor
 */
class SensorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Set up
     *
     * @covers \Phue\Sensor::__construct
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
            'state' => array(
                'daylight' => false,
                'lastupdated' => '2014-06-27T07:38:51'
            ),
            'config' => array(
                'on' => true,
                'long' => 'none',
                'lat' => 'none',
                'sunriseoffset' => 50,
                'sunsetoffset' => 50
            ),
            'name' => 'Daylight',
            'type' => 'Daylight',
            'modelid' => 'PHDL00',
            'manufacturername' => 'Philips',
            'swversion' => '1.0',
            'uniqueid' => '00:00:00:00:00:40:03:50-f2'
        );
        
        // Create sensor object
        $this->sensor = new Sensor(7, $this->attributes, $this->mockClient);
    }

    /**
     * Test: Getting Id
     *
     * @covers \Phue\Sensor::getId
     */
    public function testGetId()
    {
        $this->assertEquals(7, $this->sensor->getId());
    }

    /**
     * Test: Getting name
     *
     * @covers \Phue\Sensor::getName
     */
    public function testGetName()
    {
        $this->assertEquals($this->attributes->name, $this->sensor->getName());
    }

    /**
     * Test: Get type
     *
     * @covers \Phue\Sensor::getType
     */
    public function testGetType()
    {
        $this->assertEquals($this->attributes->type, $this->sensor->getType());
    }

    /**
     * Test: Get model id
     *
     * @covers \Phue\Sensor::getModelId
     */
    public function testGetModelId()
    {
        $this->assertEquals($this->attributes->modelid, $this->sensor->getModelId());
    }

    /**
     * Test: Get model
     *
     * @covers \Phue\Sensor::getModel
     */
    public function testGetModel()
    {
        $this->assertInstanceOf('\Phue\SensorModel\AbstractSensorModel', 
            $this->sensor->getModel());
    }

    /**
     * Test: Get manufacturer name
     *
     * @covers \Phue\Sensor::getManufacturerName
     */
    public function testGetManufacturerName()
    {
        $this->assertEquals($this->attributes->manufacturername, 
            $this->sensor->getManufacturerName());
    }

    /**
     * Test: Get software version
     *
     * @covers \Phue\Sensor::getSoftwareVersion
     */
    public function testGetSoftwareVersion()
    {
        $this->assertEquals($this->attributes->swversion, 
            $this->sensor->getSoftwareVersion());
    }

    /**
     * Test: Get null software version
     *
     * @covers \Phue\Sensor::getSoftwareVersion
     */
    public function testGetNullSoftwareVersion()
    {
        unset($this->attributes->swversion);
        
        $this->assertNull($this->sensor->getSoftwareVersion());
    }

    /**
     * Test: Get unique id
     *
     * @covers \Phue\Sensor::getUniqueId
     */
    public function testGetUniqueId()
    {
        $this->assertEquals($this->attributes->uniqueid, 
            $this->sensor->getUniqueId());
    }

    /**
     * Test: Get null unique id
     *
     * @covers \Phue\Sensor::getUniqueId
     */
    public function testGetNullUniqueId()
    {
        unset($this->attributes->uniqueid);
        
        $this->assertNull($this->sensor->getUniqueId());
    }

    /**
     * Test: Get state
     *
     * @covers \Phue\Sensor::getState
     */
    public function testGetState()
    {
        $this->assertInstanceOf('\stdClass', $this->sensor->getState());
    }

    /**
     * Test: Get config
     *
     * @covers \Phue\Sensor::getConfig
     */
    public function testGetConfig()
    {
        $this->assertInstanceOf('\stdClass', $this->sensor->getConfig());
    }

    /**
     * Test: toString
     *
     * @covers \Phue\Sensor::__toString
     */
    public function testToString()
    {
        $this->assertEquals($this->sensor->getId(), (string) $this->sensor);
    }
}
