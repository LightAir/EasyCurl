<?php

/**
 * A basic CURL wrapper for PHP
 *
 * @package EasyCurl
 *
 * @author LightAir
 */

namespace LightAir\EasyCurl;

class EasyCurl
{

    /**
     * Headers
     *
     * @var array
     */
    private $headers = [];

    /**
     * URI Request
     *
     * @var string
     */
    private $uri;

    /**
     * Proxy address
     *
     * @var string
     */
    private $proxy;

    /**
     * User Agent
     *
     * @var string
     */
    private $userAgent = 'Curl PHP';

    /**
     * Login for Basic Authorization
     *
     * @var string
     */
    private $login;

    /**
     * Password for Basic Authorization
     *
     * @var string
     */
    private $password;

    /**
     * Time Out
     *
     * @var int
     */
    private $timeOut = 10;

    private $curl;
    private $options;

    private $response;
    private $responseHeaders;
    private $rawResponseHeaders;

    private $error = false;

    private $curlError = false;
    private $curlErrorCode = 0;
    private $curlErrorMessage;

    private $httpError = false;
    private $httpErrorCode = 0;
    private $httpStatusCode = 0;
    private $httpErrorMessage;

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
     * @return mixed
     */
    public function getResponseHeaders()
    {
        return $this->responseHeaders;
    }

    /**
     * @return mixed
     */
    public function getRawResponseHeaders()
    {
        return $this->rawResponseHeaders;
    }

    /**
     * @return boolean
     */
    public function isError()
    {
        return $this->error;
    }

    /**
     * @return boolean
     */
    public function isCurlError()
    {
        return $this->curlError;
    }

    /**
     * @return int
     */
    public function getCurlErrorCode()
    {
        return $this->curlErrorCode;
    }

    /**
     * @return mixed
     */
    public function getCurlErrorMessage()
    {
        return $this->curlErrorMessage;
    }

    /**
     * @return boolean
     */
    public function isHttpError()
    {
        return $this->httpError;
    }

    /**
     * @return int
     */
    public function getHttpErrorCode()
    {
        return $this->httpErrorCode;
    }

    /**
     * @return int
     */
    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }

    /**
     * @return mixed
     */
    public function getHttpErrorMessage()
    {
        return $this->httpErrorMessage;
    }

    /**
     * Setter proxy
     *
     * @param string $proxy
     *
     * @return $this
     */
    public function setProxy($proxy)
    {
        $this->proxy = $proxy;

        return $this;
    }

    /**
     * Setter User Agent
     *
     * @param $userAgent
     *
     * @return $this
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    /**
     * Login setter for Basic Authorization
     *
     * @param $login
     *
     * @return $this
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Password setter for Basic Authorization
     *
     * @param $password
     *
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Timeout setter
     *
     * @param int $timeOut
     *
     * @return $this
     */
    public function setTimeOut($timeOut)
    {
        $this->timeOut = $timeOut;

        return $this;
    }

    /**
     * Change header content type
     *
     * @param string $type
     *
     * @return $this
     */
    public function changeContentType($type)
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
     * @param $headerName
     *
     * @return $this
     */
    public function unsetHeader($headerName)
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
     * @return $this
     */
    public function setHeader($headerName, $headerValue)
    {
        $this->headers[$headerName] = $headerValue;

        $headers = [];

        foreach ($this->headers as $key => $value) {
            $headers[$key] = $value;
        }

        $callback = function ($aval, $akey) {
            return $akey . ': ' . $aval;
        };

        $finishedHeaders = array_map($callback, $headers, array_keys($headers));

        $this->setOpt(CURLOPT_HTTPHEADER, $finishedHeaders);

        return $this;
    }

    /**
     * Set Options
     *
     * @param  mixed $option
     * @param  mixed $value
     *
     * @return boolean
     */
    public function setOpt($option, $value)
    {
        $this->options[$option] = $value;
        return curl_setopt($this->curl, $option, $value);
    }

    /**
     * Performs query
     *
     * @param string $method
     * @param array $data
     * @param string $uri
     *
     * @return mixed
     */
    public function query($method = 'get', array $data = [], $uri = null)
    {

        if (null !== $uri) {
            $this->uri = $uri;

            if ($this->curl !== null) {
                curl_close($this->curl);
            }

            $this->curl = curl_init($uri);
        }

        $method = strtoupper($method);

        if (!in_array($method, ['GET', 'POST', 'PUT', 'DELETE'], true)) {
            $method = 'GET';
        }

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
        $this->setOpt(CURLOPT_POSTFIELDS, http_build_query($data));

        return $this->exec();
    }

    /**
     * Execution request
     *
     * @return mixed
     */
    private function exec()
    {
        $this->buildQuery();

        $this->response = curl_exec($this->curl);

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

        return $this->response;
    }

    /**
     * @uses  headerCallback()
     *
     * Build query
     */
    public function buildQuery()
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
     * @param $rawHeader
     *
     * @return array
     */
    private function headerParse($rawHeader)
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
     * @param string $uri
     *
     * @return mixed
     */

    public function get(array $data = [], $uri = null)
    {
        return $this->query('get', $data, $uri);
    }

    /**
     * Performs post request
     *
     * @param array $data
     * @param string $uri
     *
     * @return mixed
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
     * @return mixed
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
     * @return mixed
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
     * @return integer
     */
    private function headerCallback(/** @noinspection PhpUnusedParameterInspection */
        $ch, $header)
    {
        $this->rawResponseHeaders .= $header;
        return strlen($header);
    }
}