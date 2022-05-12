<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\Test\Transport;

use Phue\Transport\Http;

/**
 * Tests for Phue\Transport\Http
 */
class HttpTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Set up
     */
    public function setUp()
    {
        // Mock client
        $this->mockClient = $this->createMock('\Phue\Client', 
            // ['getTransport'],
            // ['127.0.0.1']
            array(
                'getTransport'
            ), array(
                '127.0.0.1'
            ));
        
        // Mock transport adapter
        $this->mockAdapter = $this->createMock(
            '\Phue\Transport\Adapter\AdapterInterface');
        
        // Set transport
        $this->transport = new Http($this->mockClient);
    }

    /**
     * Test: Client property
     *
     * @covers Phue\Transport\Http::__construct
     */
    public function testClientProperty()
    {
        // Ensure property is set properly
        $this->assertAttributeEquals($this->mockClient, 'client', $this->transport);
    }

    /**
     * Test: Get default adapter
     *
     * @covers Phue\Transport\Http::getAdapter
     */
    public function testGetDefaultAdapter()
    {
        $this->assertInstanceOf('\Phue\Transport\Adapter\AdapterInterface', 
            $this->transport->getAdapter());
    }

    /**
     * Test: Custom adapter
     *
     * @covers Phue\Transport\Http::getAdapter
     * @covers Phue\Transport\Http::setAdapter
     */
    public function testCustomAdapter()
    {
        $this->transport->setAdapter($this->mockAdapter);
        
        $this->assertEquals($this->mockAdapter, $this->transport->getAdapter());
    }

    /**
     * Test: Send request with bad status code
     *
     * @covers Phue\Transport\Http::sendRequest
     *
     * @expectedException \Phue\Transport\Exception\ConnectionException
     */
    public function testSendRequestBadStatusCode()
    {
        // Stub adapter methods
        $this->stubMockAdapterResponseMethods(null, 500, 'application/json');
        
        // Set mock adapter
        $this->transport->setAdapter($this->mockAdapter);
        
        // Send request
        $this->transport->sendRequest('dummy', 'GET');
    }

    /**
     * Test: Send request with bad content type
     *
     * @covers Phue\Transport\Http::sendRequest
     * @covers Phue\Transport\Http::getJsonResponse
     *
     * @expectedException \Phue\Transport\Exception\ConnectionException
     */
    public function testSendRequestBadContentType()
    {
        // Stub adapter methods
        $this->stubMockAdapterResponseMethods(null, 200, 'unknown');
        
        // Set mock adapter
        $this->transport->setAdapter($this->mockAdapter);
        
        // Send request
        $this->transport->sendRequest('dummy', 'GET');
    }

    /**
     * Test: Send request with error response
     *
     * @covers Phue\Transport\Http::sendRequest
     * @covers Phue\Transport\Http::getJsonResponse
     *
     * @expectedException \Phue\Transport\Exception\UnauthorizedUserException
     */
    public function testSendRequestErrorResponse()
    {
        // Mock response
        $mockResponse = array(
            'error' => array(
                'type' => 1,
                'description' => 'Some kind of error'
            )
        );
        
        // Stub adapter methods
        $this->stubMockAdapterResponseMethods($mockResponse, 200, 'application/json');
        
        // Set mock adapter
        $this->transport->setAdapter($this->mockAdapter);
        
        // Send request
        $this->transport->sendRequest('dummy', 'GET');
    }

    /**
     * Test: Send request with array response
     *
     * @covers Phue\Transport\Http::sendRequest
     * @covers Phue\Transport\Http::getJsonResponse
     */
    public function testSendRequestArray()
    {
        // Mock response
        // $mockResponse = [
        // 'value 1', 'value 2'
        // ];
        $mockResponse = array(
            'value 1',
            'value 2'
        );
        
        // Stub adapter methods
        $this->stubMockAdapterResponseMethods($mockResponse, 200, 'application/json');
        
        // Set mock adapter
        $this->transport->setAdapter($this->mockAdapter);
        
        // Send request
        $this->assertEquals($mockResponse[0], 
            $this->transport->sendRequest('dummy', 'GET'));
    }

    /**
     * Test: Send request with success resposne
     *
     * @covers Phue\Transport\Http::sendRequest
     * @covers Phue\Transport\Http::getJsonResponse
     */
    public function testSendRequestSuccess()
    {
        // Mock response
        $mockResponse = array(
            'success' => '123'
        );
        
        // Stub adapter methods
        $this->stubMockAdapterResponseMethods($mockResponse, 200, 'application/json');
        
        // Set mock adapter
        $this->transport->setAdapter($this->mockAdapter);
        
        // Send request
        $this->assertEquals($mockResponse['success'], 
            $this->transport->sendRequest('dummy', 'GET'));
    }

    /**
     * Test: Throw exception by type
     *
     * @dataProvider providerErrorTypes
     *
     * @covers Phue\Transport\Http::getExceptionByType
     */
    public function testThrowExceptionByType($type, $exceptionName)
    {
        $this->assertInstanceOf($exceptionName, 
            $this->transport->getExceptionByType($type, null));
    }

    /**
     * Provider: Error types
     *
     * @return array
     */
    public function providerErrorTypes()
    {
        $errorTypes = array(
            array(
                - 1,
                'Phue\Transport\Exception\BridgeException'
            )
        );
        
        foreach (Http::$exceptionMap as $errorId => $errorClass) {
            $errorTypes[] = array(
                $errorId,
                $errorClass
            );
        }
        
        return $errorTypes;
    }

    /**
     * Stub adapter response methods
     *
     * @param string $response
     *            Response body
     * @param string $httpStatusCode
     *            Http status code
     * @param string $contentType
     *            Content type
     */
    protected function stubMockAdapterResponseMethods($response, $httpStatusCode, 
        $contentType)
    {
        // Stub send method on transport adapter
        $this->mockAdapter->expects($this->once())
            ->method('send')
            ->will($this->returnValue(json_encode($response)));
        
        // Stub getHttpStatusCode on transport adapter
        $this->mockAdapter->expects($this->once())
            ->method('getHttpStatusCode')
            ->will($this->returnValue($httpStatusCode));
        
        // Stub getContentType on transport adapter
        $this->mockAdapter->expects($this->once())
            ->method('getContentType')
            ->will($this->returnValue($contentType));
    }
}
