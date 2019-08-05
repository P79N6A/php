<?php
ini_set('date.timezone','Asia/Shanghai'); // 'Asia/Shanghai' 为上海时区
require("./../vendor/autoload.php");
$client = new \GuzzleHttp\Client();
$res = $client->request('GET', 'https://www.baidu.com/');
echo $res->getStatusCode();
// 200
echo $res->getHeaderLine('content-type');
// 'application/json; charset=utf8'
echo $res->getBody();
// '{"id": 1420053, "name": "guzzle", ...}'
exit;
// Send an asynchronous request.
$request = new \GuzzleHttp\Psr7\Request('GET', 'http://httpbin.org');
$promise = $client->sendAsync($request)->then(function ($response) {
	echo 'I completed! ' . $response->getBody();
});
$promise->wait();