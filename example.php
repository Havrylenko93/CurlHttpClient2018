<?php

require_once "vendor/autoload.php";

$x = new CurlHttpClient\CurlHttpClient('https://dou.ua/');

echo "<pre>";
var_dump($x);
echo "</pre>";
exit();