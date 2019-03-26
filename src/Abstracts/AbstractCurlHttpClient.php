<?php

namespace CurlHttpClient\Abstracts;

use CurlHttpClient\Interfaces\CurlInterface,
    CurlHttpClient\Response;

abstract class AbstractCurlHttpClient implements CurlInterface
{
    /** @var array */
    protected $options = [];
    /** @var */
    protected $countOfAttempts;
    /** @var */
    protected $timeBetweenAttempts;
    /** @var */
    protected $curl;
    /** @var */
    protected $curlInfo;
    /** @var */
    protected $response;

    /**
     * @param $url
     * @return CurlInterface
     */
    abstract public function init($url): CurlInterface;

    /**
     * @param int $optionKey
     * @param $optionValue
     * @return CurlInterface
     */
    abstract public function setOption(int $optionKey, $optionValue): CurlInterface;

    public function __destruct()
    {
        if (is_resource($this->curl)) {
            curl_close($this->curl);
        }
    }

    /**
     * AbstractCurlHttpClient constructor.
     * @param null $url
     * @throws \Exception
     */
    public function __construct($url = null)
    {
        if (!extension_loaded('curl')) {
            throw new \Exception('CURL library is not loaded');
        }

        $this->init($url);
    }

    /**
     * @throws \Exception
     */
    protected function checkCurlDescriptor(): void
    {
        if ($this->curl === false) {
            throw new \Exception('CURL init error');
        }
    }

    /**
     * @param int $count - count of retrying request
     * @return CurlInterface
     */
    public function setCountOfAttempts(int $count): CurlInterface
    {
        $this->countOfAttempts = $count;

        return $this;
    }

    /**
     * @param int $seconds - time between attempt of retry request in seconds
     * @return CurlInterface
     */
    public function setTimeBetweenAttempts(int $seconds): CurlInterface
    {
        $this->timeBetweenAttempts = $seconds;

        return $this;
    }

    /**
     *  set curl info
     */
    protected function setCurlInfo(): void
    {
        $this->curlInfo = curl_getinfo($this->curl);
    }

    /**
     */
    public function responseStatusIsOk(): bool
    {
        return !empty($this->curlInfo['http_code']) && $this->curlInfo['http_code'] === Response::HTTP_CODE_OK;
    }

    /**
     * @return int
     */
    public function responseStatusCode(): int
    {
        if (!isset($this->curlInfo['http_code'])) {
            return 0;
        }

        $status = (int)$this->curlInfo['http_code'];

        return $status;
    }

    /**
     * @throws \Exception
     */
    protected function checkResponse(): void
    {
        if (!$this->responseStatusIsOk()) {
            $statusCode = $this->responseStatusCode();

            if ($statusCode === 0) {
                throw new \Exception('Error of curl_exec() function', $statusCode);
            }

            throw new \Exception(Response::$responseStatusesToMessages[$statusCode], $statusCode);
        }
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}