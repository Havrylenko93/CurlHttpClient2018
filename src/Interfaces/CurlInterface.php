<?php

namespace CurlHttpClient\Interfaces;

interface CurlInterface
{
    public const DEFAULT_TIMEOUT = 180;
    public const DEFAULT_CONNECT_TIMEOUT = 30;
    public const DEFAULT_COUNT_OF_ATTEMPTS = 3;
    public const DEFAULT_TIME_BETWEEN_ATTEMPTS = 5;

    public function init($url): CurlInterface;

    public function setOption(int $optionKey, $optionValue): CurlInterface;
}