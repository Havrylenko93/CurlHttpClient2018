<?php

namespace CurlHttpClient;

class Response
{
    public const HTTP_CODE_OK = 200;
    public const HTTP_CODE_FOUND = 302;
    public const HTTP_CODE_BAD_REQUEST = 400;
    public const HTTP_CODE_UNAUTHORIZED = 401;
    public const HTTP_CODE_FORBIDDEN = 403;
    public const HTTP_CODE_NOT_FOUND = 404;
    public const HTTP_CODE_REQUEST_TIMEOUT = 408;
    public const HTTP_CODE_INTERNAL_SERVER_ERROR = 500;
    public const HTTP_CODE_SERVICE_UNAVAILABLE = 503;

    public static $responseStatusesToMessages = [
        self::HTTP_CODE_OK => 'OK',
        self::HTTP_CODE_FOUND => 'Found, moved temporarily',
        self::HTTP_CODE_BAD_REQUEST => 'Bad Request',
        self::HTTP_CODE_UNAUTHORIZED => 'Unauthorized',
        self::HTTP_CODE_FORBIDDEN => 'Forbidden',
        self::HTTP_CODE_NOT_FOUND => 'Not found',
        self::HTTP_CODE_REQUEST_TIMEOUT => 'Request Timeout',
        self::HTTP_CODE_INTERNAL_SERVER_ERROR => 'Internal server error',
        self::HTTP_CODE_SERVICE_UNAVAILABLE => 'Service unavailable',
    ];
}
