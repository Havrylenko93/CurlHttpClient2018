How to install?
``composer require havrylenko93/curl-http-client``

How to use?

Case 1. Simple.
```
$httpClient = new \CurlHttpClient\CurlHttpClient('https://www.php.net/manual/ru/function.curl-exec.php');
$result = $httpClient->execute();
```
Case 2. Additional parameters.
```
$httpClient = new \CurlHttpClient\CurlHttpClient('http://127.0.0.1/api/companies/');

$result = $httpClient
    ->setPort(8000)
    ->setConnectTimeout(1)
    ->acceptJson()
    ->execute();
```
Case 3. Re-sending requests with pauses.
If resource is unavailable then will be sent 3 request (setCountOfAttempts) 
with a gap between them in 4 seconds (setTimeBetweenAttempts). 
If any request is not finished by success status then will be throw an exception
```
$httpClient = new \CurlHttpClient\CurlHttpClient('http://127.0.0.1/api/companies/');

$result = $httpClient
    ->setPort(8000)
    ->setCountOfAttempts(3)
    ->setTimeBetweenAttempts(1)
    ->acceptJson()
    ->execute();
```
Case 4. Response to file.

```
$httpClient = new \CurlHttpClient\CurlHttpClient('http://127.0.0.1/api/companies/');

$result = $httpClient
    ->setPort(8000)
    ->responseToFile(fopen('/var/www/html/test/777.txt', 'w+'))
    ->execute();
```



All methods:

    public function setCountOfAttempts(int $count): CurlInterface

    public function setTimeBetweenAttempts(int $seconds): CurlInterface

    public function responseStatusIsOk(): bool

    public function responseStatusCode(): int

    public function getOptions(): array
    
    public function setOption(int $optionKey, $optionValue): CurlInterface

    public function setOptions(array $options): CurlInterface

    public function init($url): CurlInterface

    public function execute()

    public function setUrl(string $url)

    public function responseToFile($fileDescriptor)

    public function setPort(int $port)

    public function setConnectTimeout(int $seconds)

    public function setTimeout(int $seconds)

    public function setUserAgent(string $userAgent)

    public function setPostFields(string $postFields)

    public function acceptJson()

    public function acceptXml()

    public function setBasicAuthentication(string $username, string $password = '')

    public function setHeaders(array $headers)

    public function setProgressCallback($callback)

    public function setContentType($contentType)
