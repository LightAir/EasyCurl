<?php

namespace tests;

use LightAir\Utils\EasyCurl;
use PHPUnit\Framework\TestCase;

class EasyCurlTest extends TestCase
{
    const URL = 'http://localhost:3351';

    public function setUp(): void
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

        $this->assertTrue($ecu->isHttpError());
        $this->assertEquals(500, $ecu->getHttpStatusCode());
        $this->assertEquals(500, $ecu->getHttpErrorCode());
        $this->assertEquals('Failure', $result);
        $this->assertEquals('HTTP/1.1 500 Internal Server Error', $ecu->getHttpErrorMessage());
        $this->assertNotNull($ecu->getRawResponseHeaders());
    }

    /** @test */
    public function queryRequestBody()
    {
        $ecu = new EasyCurl(static::URL . '/echo.php');

        $result = $ecu->query('post', ['foo' => 'bar']);

        $this->assertMatchesRegularExpression('/foo=bar/', $result);
    }

    /** @test */
    public function queryRequestBodyWithUri()
    {
        $ecu = new EasyCurl(static::URL . '/echo.php');

        $result = $ecu->query('post', ['foo' => 'bar'], static::URL . '/echo.php');

        $this->assertMatchesRegularExpression('/foo=bar/', $result);
    }

    /** @test */
    public function curlError()
    {
        $ecu = new EasyCurl('httpg://0.0.0.0.0.0');
        $ecu->get();

        $this->assertTrue($ecu->isCurlError());
        $this->assertTrue($ecu->isError());
        $this->assertEquals(1, $ecu->getCurlErrorCode());
        $this->assertMatchesRegularExpression('/not supported or disabled in libcurl/', $ecu->getCurlErrorMessage());

    }

    /** @test */
    public function checkUserAgent()
    {

        $ecu = new EasyCurl(static::URL . '/echo.php');

        $ecu->setUserAgent('unit test');
        $result = $ecu->post();
        $this->assertMatchesRegularExpression('/User-Agent: unit test/', $result);

    }

    /** @test */
    public function checkBasicAuth()
    {

        $ecu = new EasyCurl(static::URL . '/echo.php');

        $ecu->setPassword('123');
        $ecu->setLogin('234');
        $result = $ecu->get();
        $this->assertMatchesRegularExpression('/Authorization: Basic MjM0OjEyMw==/', $result);
    }

    /** @test */
    public function checkContentType()
    {
        $ecu = new EasyCurl(static::URL . '/echo.php');

        $ecu->changeContentType('json');
        $result = $ecu->get();
        $this->assertMatchesRegularExpression('/Content-Type: application\/json/', $result);
    }

    /** @test */
    public function checkPut()
    {
        $ecu = new EasyCurl(static::URL . '/echo.php');

        $result = $ecu->put(['foo' => 'put']);
        $this->assertMatchesRegularExpression('/PUT/', $result);
        $this->assertMatchesRegularExpression('/foo=put/', $result);
    }

    /** @test */
    public function checkPost()
    {
        $ecu = new EasyCurl(static::URL . '/echo.php');

        $result = $ecu->put(['foo' => 'put']);
        $this->assertMatchesRegularExpression('/PUT/', $result);
        $this->assertMatchesRegularExpression('/foo=put/', $result);
    }

    /** @test */
    public function checkDelete()
    {
        $ecu = new EasyCurl(static::URL . '/echo.php');

        $result = $ecu->delete(['foo' => 'delete']);
        $this->assertMatchesRegularExpression('/DELETE/', $result);
        $this->assertMatchesRegularExpression('/foo=delete/', $result);
    }

    /** @test */
    public function checkDoesNotExistMethod()
    {
        $ecu = new EasyCurl(static::URL . '/echo.php');
        $ecu->query('donotexistmethod', ['foo' => 'dne']);

        $this->assertEquals(501, $ecu->getHttpErrorCode());
        $this->assertEquals('HTTP/1.1 501 Not Implemented', $ecu->getHttpErrorMessage());
    }

    /** @test */
    public function checkProxy()
    {
        $ecu = new EasyCurl(static::URL . '/echo.php');

        $ecu->setProxy(self::URL);
        $result = $ecu->get();

        $this->assertMatchesRegularExpression('/GET/', $result);
        $this->assertMatchesRegularExpression('/Proxy-Connection: Keep-Alive/', $result);
    }

    /** @test */
    public function checkTimeOut()
    {
        $ecu = new EasyCurl('10.255.255.1');

        $ecu->setTimeOut(1);
        $startTime = time();

        $ecu->get(['timeout']);

        $this->assertEquals(1, time() - $startTime);
        $this->assertTrue($ecu->isError());
    }

    /** @test */
    public function checkJsonDecode()
    {
        $ecu = new EasyCurl(static::URL . '/json.php');
        $ecu->setAutoJSON(true);

        $this->assertTrue($ecu->isAutoJSON());

        $result = $ecu->query();

        $this->assertEquals([
            "test" => "json"
        ],
            $result
        );
    }

    /** @test */
    public function checkJsonEncode()
    {
        $ecu = new EasyCurl(static::URL . '/echo.php');
        $ecu->setAutoJSON(true);

        $this->assertTrue($ecu->isAutoJSON());

        $result = $ecu->query('POST', ['hello' => 'world']);

        $this->assertMatchesRegularExpression('{"hello":"world"}', $result);
    }
}