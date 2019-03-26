<?php

namespace CurlHttpClient;

use CurlHttpClient\Abstracts\AbstractCurlHttpClient,
    CurlHttpClient\Interfaces\CurlInterface;

class CurlHttpClient extends AbstractCurlHttpClient
{
    /**
     * @param int $optionKey
     * @param $optionValue
     * @return CurlInterface
     */
    public function setOption(int $optionKey, $optionValue): CurlInterface
    {
        $this->options[$optionKey] = $optionValue;

        return $this;
    }

    /**
     * @param array $options
     * @return CurlInterface
     */
    public function setOptions(array $options): CurlInterface
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }

    /**
     * @param $url
     * @return CurlInterface
     * @throws \Exception
     */
    public function init($url): CurlInterface
    {
        if ($url !== null) {
            $this->curl = curl_init($url);
        } else {
            $this->curl = curl_init();
        }

        $this->checkCurlDescriptor();

        $this->setDefaultSettings();

        return $this;
    }

    private function setDefaultSettings(): void
    {
        $this->setOption(CURLOPT_RETURNTRANSFER, true);
        $this->setOption(CURLOPT_TIMEOUT, self::DEFAULT_TIMEOUT);
        $this->setOption(CURLOPT_CONNECTTIMEOUT, self::DEFAULT_CONNECT_TIMEOUT);
        $this->setCountOfAttempts(self::DEFAULT_COUNT_OF_ATTEMPTS);
        $this->setTimeBetweenAttempts(self::DEFAULT_TIME_BETWEEN_ATTEMPTS);
    }

    /**
     * @return bool|string
     * @throws \Exception
     */
    public function execute()
    {
        $opts = $this->getOptions();

        curl_setopt_array($this->curl, $opts);

        $currentAttempt = 0;

        while ($currentAttempt++ < $this->countOfAttempts) {
            $this->response = curl_exec($this->curl);
            $this->setCurlInfo();

            if ($this->checkStatus()) {
                break;
            }

            if ($currentAttempt < $this->countOfAttempts) {
                sleep($this->timeBetweenAttempts);
            }
        }

        $this->checkResponse();

        return $this->response;
    }

    protected function checkStatus()
    {
        return $this->responseStatusIsOk();
    }

    /**
     * @param string $url
     * @return CurlInterface
     */
    public function setUrl(string $url)
    {
        return $this->setOption(CURLOPT_URL, $url);
    }

    /**
     * @param $fileDescriptor
     * @return CurlInterface
     * @throws \Exception
     */
    public function responseToFile($fileDescriptor)
    {
        if (!is_resource($fileDescriptor)) {
            throw new \Exception('Parameter is not a resource');
        }

        return $this->setOption(CURLOPT_FILE, $fileDescriptor);
    }

    /**
     * @param int $port
     * @return CurlInterface
     */
    public function setPort(int $port)
    {
        return $this->setOption(CURLOPT_PORT, $port);
    }

    /**
     * @param int $seconds
     * @return CurlInterface
     */
    public function setConnectTimeout(int $seconds)
    {
        return $this->setOption(CURLOPT_CONNECTTIMEOUT, $seconds);
    }

    /**
     * @param int $seconds
     * @return CurlInterface
     */
    public function setTimeout(int $seconds)
    {
        return $this->setOption(CURLOPT_TIMEOUT, $seconds);
    }

    /**
     * @param string $userAgent
     * @return CurlInterface
     */
    public function setUserAgent(string $userAgent)
    {
        return $this->setOption(CURLOPT_USERAGENT, $userAgent);
    }

    /**
     * @param string $postFields
     * @return CurlInterface
     */
    public function setPostFields(string $postFields)
    {
        return $this->setOption(CURLOPT_POSTFIELDS, $postFields);
    }

    /**
     * @return CurlInterface
     */
    public function acceptJson()
    {
        return $this->setHeaders([
            "Content-Type: application/json",
            "Accept: application/json",
            "Cache-Control: no-cache"
        ]);
    }

    /**
     * @return CurlInterface
     */
    public function acceptXml()
    {
        return $this->setHeaders(["Content-Type: text/xml; charset=UTF-8", 'accept: text/xml']);
    }

    /**
     * @param string $username
     * @param string $password
     * @return CurlInterface
     */
    public function setBasicAuthentication(string $username, string $password = '')
    {
        $this->setOption(CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

        return $this->setOption(CURLOPT_USERPWD, $username . ':' . $password);
    }

    /**
     * @param array $headers
     * @return CurlInterface
     */
    public function setHeaders(array $headers)
    {
        return $this->setOption(CURLOPT_HTTPHEADER, array_merge($headers, (array)$this->options[CURLOPT_HTTPHEADER]));
    }

    /**
     * @param $callback
     * @return CurlInterface
     */
    public function setProgressCallback($callback)
    {
        $this->setOption(CURLOPT_PROGRESSFUNCTION, $callback);

        return $this->setOption(CURLOPT_NOPROGRESS, false);
    }

    /**
     * @param $contentType
     */
    public function setContentType($contentType)
    {
        $this->setHeaders(["Content-Type: " . $contentType]);
    }
}