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
        $ec = new EasyCurl(static::URL . '/success.php');

        $result = $ec->get();

		$this->assertEquals(200, $ec->getHttpStatusCode());
		$this->assertEquals('OK', $result);
		$this->assertNotNull($ec->getRawResponseHeaders());
		$this->assertNotNull($ec->getResponseHeaders());
	}

    /** @test */
	public function failedResponse()
	{
        $ec = new EasyCurl(static::URL . '/failure.php');

        $result = $ec->get();

        $this->assertEquals(500, $ec->getHttpStatusCode());
        $this->assertEquals('Failure', $result);
        $this->assertEquals('HTTP/1.1 500 Internal Server Error', $ec->getHttpErrorMessage());
        $this->assertNotNull($ec->getRawResponseHeaders());
	}

    /** @test */
	public function queryRequestBody()
	{
        $ec = new EasyCurl(static::URL . '/echo.php');

        $result = $ec->post(['foo' => 'bar']);

        $this->assertEquals('foo=bar', $result);
	}

	/** @test */
	public function curlError()
	{
        $ec = new EasyCurl('httpg://0.0.0.0.0.0');
        $ec->get();

        $this->assertTrue($ec->isCurlError());
        $this->assertTrue($ec->isError());
        $this->assertEquals(1, $ec->getCurlErrorCode());

	}


    /** @test */
    //    public function te()
    //    {
    //        $ec = new EasyCurl(static::URL . '/echo.php');
    //        $ec->setHeader('foo', 'bar');
    //        $ec->get();
    //    }
}