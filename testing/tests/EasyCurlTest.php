<?php

use LightAir\EasyCurl\EasyCurl;

class EasyCurlTest extends PHPUnit_Framework_TestCase
{
	const URL = 'http://localhost:3351';

	public function setUp()
	{
	    parent::setUp();

		if (!getenv('CURL_TEST_SERVER_RUNNING')) {
			$this->markTestSkipped('The web server is not running.');
		}

		if (!extension_loaded('curl')) {
			$this->markTestSkipped('The curl extension is not installed.');
		}
	}

    /** @test */
	public function successfulResponse()
	{
        $ecu = new EasyCurl(static::URL . '/success.php');

        $result = $ecu->get();

		$this->assertEquals(200, $ecu->getHttpStatusCode());
		$this->assertEquals('OK', $result);
		$this->assertNotNull($ecu->getRawResponseHeaders());
		$this->assertNotNull($ecu->getResponseHeaders());
	}

    /** @test */
	public function failedResponse()
	{
        $ecu = new EasyCurl(static::URL . '/failure.php');

        $result = $ecu->get();

        $this->assertEquals(500, $ecu->getHttpStatusCode());
        $this->assertEquals('Failure', $result);
        $this->assertEquals('HTTP/1.1 500 Internal Server Error', $ecu->getHttpErrorMessage());
        $this->assertNotNull($ecu->getRawResponseHeaders());
	}

    /** @test */
	public function queryRequestBody()
	{
        $ecu = new EasyCurl(static::URL . '/echo.php');

        $result = $ecu->post(['foo' => 'bar']);

        $this->assertEquals('foo=bar', $result);
	}

	/** @test */
	public function curlError()
	{
        $ecu = new EasyCurl('httpg://0.0.0.0.0.0');
        $ecu->get();

        $this->assertTrue($ecu->isCurlError());
        $this->assertTrue($ecu->isError());
        $this->assertEquals(1, $ecu->getCurlErrorCode());

	}


    /** @test */
    //    public function te()
    //    {
    //        $ec = new EasyCurl(static::URL . '/echo.php');
    //        $ec->setHeader('foo', 'bar');
    //        $ec->get();
    //    }
}