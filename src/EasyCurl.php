<?php

namespace LightAir\EasyCurl;

use CurlHandle;

/**
 * A basic CURL wrapper for PHP
 *
 * @package LightAir\EasyCurl
 */
class EasyCurl
{

    /** @var array */
    private array $headers = [];

    /** @var string */
    private string $uri;

    /** @var string|null */
    private ?string $proxy = null;

    /** @var string */
    private string $userAgent = 'Curl PHP';

    /** @var string|null */
    private ?string $login = null;

    /** @var string|null */
    private ?string $password;

    /** @var int */
    private int $timeOut = 10;

    /** @var CurlHandle|false|resource */
    private $curl;

    /** @var array|null */
    private ?array $responseHeaders;

    /** @var string|null */
    private ?string $rawResponseHeaders = null;

    /** @var bool */
    private bool $error = false;

    /** @var bool */
    private bool $curlError = false;

    /** @var int */
    private int $curlErrorCode = 0;

    /** @var string|null */
    private ?string $curlErrorMessage;

    /** @var bool */
    private bool $httpError = false;

    /** @var int */
    private int $httpErrorCode = 0;

    /** @var int */
    private int $httpStatusCode = 0;

    /** @var string|null */
    private ?string $httpErrorMessage;

    /** @var bool */
    private bool $autoJSON = false;

    /**
     * Request constructor.
     *
     * @param string $uri
     */
    public function __construct($uri = null)
    {
        if (null !== $uri) {
            $this->uri = $uri;
            $this->curl = curl_init($uri);
        }
    }

    /**
     * @return bool
     */
    public function isAutoJSON(): bool
    {
        return $this->autoJSON;
    }

    /**
     * @param $autoJSONDecode
     *
     * @return $this
     */
    public function setAutoJSON(bool $autoJSONDecode): EasyCurl
    {
        $this->autoJSON = $autoJSONDecode;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getResponseHeaders(): ?array
    {
        return $this->responseHeaders;
    }

    /**
     * @return string|null
     */
    public function getRawResponseHeaders(): ?string
    {
        return $this->rawResponseHeaders;
    }

    /**
     * @return bool
     */
    public function isError(): bool
    {
        return $this->error;
    }

    /**
     * @return bool
     */
    public function isCurlError(): bool
    {
        return $this->curlError;
    }

    /**
     * @return int
     */
    public function getCurlErrorCode(): int
    {
        return $this->curlErrorCode;
    }

    /**
     * @return string|null
     */
    public function getCurlErrorMessage(): ?string
    {
        return $this->curlErrorMessage;
    }

    /**
     * @return bool
     */
    public function isHttpError(): bool
    {
        return $this->httpError;
    }

    /**
     * @return int
     */
    public function getHttpErrorCode(): int
    {
        return $this->httpErrorCode;
    }

    /**
     * @return int
     */
    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }

    /**
     * @return string|null
     */
    public function getHttpErrorMessage(): ?string
    {
        return $this->httpErrorMessage;
    }

    /**
     * Setter proxy
     *
     * @param string $proxy
     *
     * @return EasyCurl
     */
    public function setProxy(string $proxy): EasyCurl
    {
        $this->proxy = $proxy;

        return $this;
    }

    /**
     * Setter User Agent
     *
     * @param string $userAgent
     *
     * @return EasyCurl
     */
    public function setUserAgent(string $userAgent): EasyCurl
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    /**
     * Login setter for Basic Authorization
     *
     * @param string $login
     *
     * @return EasyCurl
     */
    public function setLogin(string $login): EasyCurl
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Password setter for Basic Authorization
     *
     * @param string $password
     *
     * @return EasyCurl
     */
    public function setPassword(string $password): EasyCurl
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Timeout setter
     *
     * @param int $timeOut
     *
     * @return EasyCurl
     */
    public function setTimeOut(int $timeOut): EasyCurl
    {
        $this->timeOut = $timeOut;

        return $this;
    }

    /**
     * Change header content type
     *
     * @param string $type
     *
     * @return EasyCurl
     */
    public function changeContentType(string $type): EasyCurl
    {

        $mime = [
            'json' => 'application/json',
            'javascript' => 'application/javascript',
            'js' => 'application/javascript',
            'ogg' => 'audio/ogg',
            'mpeg' => 'audio/mpeg',
            'html' => 'text/html',
            'plain' => 'text/plain',
            'urlencoded' => 'application/x-www-form-urlencoded'
        ];

        if (array_key_exists($type, $mime)) {
            $this->unsetHeader('Content-Type');
            $this->setHeader('Content-Type', $mime[$type]);
        }

        return $this;
    }

    /**
     * Unset header
     *
     * @param string $headerName
     *
     * @return EasyCurl
     */
    public function unsetHeader(string $headerName): EasyCurl
    {
        $this->setHeader($headerName, '');
        unset($this->headers[$headerName]);

        return $this;
    }

    /**
     * Set header
     *
     * @param string $headerName
     * @param string $headerValue
     *
     * @return EasyCurl
     */
    public function setHeader(string $headerName, string $headerValue): EasyCurl
    {
        $this->headers[$headerName] = $headerValue;

        $headers = [];

        foreach ($this->headers as $key => $value) {
            $headers[$key] = $value;
        }

        $finishedHeaders = array_map(
            fn($aval, $akey) => $akey . ': ' . $aval,
            $headers,
            array_keys($headers)
        );

        $this->setOpt(CURLOPT_HTTPHEADER, $finishedHeaders);

        return $this;
    }

    /**
     * Set Options
     *
     * @param  mixed $option
     * @param  mixed $value
     *
     * @return bool
     */
    public function setOpt($option, $value): bool
    {
        $options[$option] = $value;
        return curl_setopt($this->curl, $option, $value);
    }

    /**
     * Performs query
     *
     * @param string $method
     * @param array $data
     * @param string|null $uri
     *
     * @return bool|string
     *
     * @throws EasyCurlException
     */
    public function query(string $method = 'get', array $data = [], ?string $uri = null)
    {

        if (null !== $uri) {
            $this->uri = $uri;

            if ($this->curl !== null) {
                curl_close($this->curl);
            }

            $this->curl = curl_init($uri);
        }

        if ($this->uri === null) {
            throw new EasyCurlException();
        }

        $method = strtoupper($method);

        if (is_array($data) && empty($data)) {
            $this->unsetHeader('Content-Length');
        }

        $this->setOpt(CURLOPT_CUSTOMREQUEST, $method);

        if ($method === 'GET') {
            $this->setOpt(CURLOPT_HTTPGET, true);
            $this->setOpt(CURLOPT_URL, $this->uri . '?' . http_build_query($data));
            return $this->exec();
        }

        $this->setOpt(CURLOPT_POST, true);

        if ($this->isAutoJSON()) {
            $dataJson = json_encode($data);

            $this->setOpt(
                CURLOPT_HTTPHEADER,
                [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($dataJson)
                ]);

            $this->setOpt(CURLOPT_POSTFIELDS, $dataJson);

            return $this->exec();
        }

        $this->setOpt(CURLOPT_POSTFIELDS, http_build_query($data));

        return $this->exec();
    }

    /**
     * Execution request
     *
     * @return bool|string
     */
    private function exec()
    {
        $this->buildQuery();

        $response = curl_exec($this->curl);

        // curl
        $this->curlErrorCode = curl_errno($this->curl);
        $this->curlErrorMessage = curl_error($this->curl);
        $this->curlError = !($this->curlErrorCode === 0);

        // http
        $this->httpStatusCode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
        $this->httpError = in_array(floor($this->httpStatusCode / 100), array(4, 5));
        $this->httpErrorCode = $this->httpError ? $this->httpStatusCode : 0;

        // base
        $this->error = $this->curlError || $this->httpError;

        if (!$this->curlError) {
            $this->responseHeaders = $this->headerParse($this->rawResponseHeaders);
        }

        if ($this->httpError) {
            $this->httpErrorMessage = $this->responseHeaders['Status-Line'];
        }

        if ($this->autoJSON && $response !== false && $this->isJSON($response)) {
            return json_decode($response, true);
        }

        return $response;
    }

    /**
     * @uses  headerCallback()
     *
     * Build query
     */
    public function buildQuery(): void
    {
        if ($this->proxy) {
            $this->setOpt(CURLOPT_PROXY, $this->proxy);
        }

        $this->setOpt(CURLOPT_RETURNTRANSFER, true);
        $this->setOpt(CURLOPT_USERAGENT, $this->userAgent);
        $this->setOpt(CURLOPT_CONNECTTIMEOUT, $this->timeOut);
        $this->setOpt(CURLINFO_HEADER_OUT, true);
        $this->setOpt(CURLOPT_HEADERFUNCTION, array($this, 'headerCallback'));

        if (null !== $this->login && null !== $this->password) {
            $this->setOpt(CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            $this->setOpt(CURLOPT_USERPWD, $this->login . ':' . $this->password);
        }
    }

    /**
     * Parser for headers
     *
     * @param string $rawHeader
     *
     * @return array
     */
    private function headerParse(string $rawHeader): array
    {

        $headers = array();

        $headerLine = substr($rawHeader, 0, strpos($rawHeader, "\r\n\r\n"));

        foreach (explode("\r\n", $headerLine) as $i => $line) {
            if ($i === 0) {
                $headers['Status-Line'] = $line;
                continue;
            }

            list ($key, $value) = explode(': ', $line);
            $headers[$key] = $value;
        }

        return $headers;
    }

    /**
     * Performs get request
     *
     * @param array $data
     * @param string|null $uri
     *
     * @return bool|string
     *
     * @throws EasyCurlException
     */

    public function get(array $data = [], string $uri = null)
    {
        return $this->query('get', $data, $uri);
    }

    /**
     * Performs post request
     *
     * @param array $data
     * @param string $uri
     *
     * @return bool|string
     *
     * @throws EasyCurlException
     */
    public function post(array $data = [], $uri = null)
    {
        return $this->query('post', $data, $uri);
    }


    /**
     * Performs put request
     *
     * @param array $data
     * @param string $uri
     *
     * @return bool|string
     *
     * @throws EasyCurlException
     */

    public function put(array $data = [], $uri = null)
    {
        return $this->query('put', $data, $uri);
    }

    /**
     * Performs delete request
     *
     * @param array $data
     * @param string $uri
     *
     * @return bool|string
     *
     * @throws EasyCurlException
     */

    public function delete(array $data = [], $uri = null)
    {
        return $this->query('delete', $data, $uri);
    }

    /**
     * Header Callback
     *
     * @param  $ch
     * @param  $header
     *
     * @return int
     */
    private function headerCallback(/** @noinspection PhpUnusedParameterInspection */
        $ch, $header)
    {
        $this->rawResponseHeaders .= $header;
        return strlen($header);
    }

    /**
     * Check string is JSON
     *
     * @param string $string
     *
     * @return bool
     */
    public function isJSON(string $string): bool
    {
        return is_array(json_decode($string, true)) && (json_last_error() === JSON_ERROR_NONE);
    }
}