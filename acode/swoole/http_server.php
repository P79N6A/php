<?php
/*$http = new swoole_http_server("127.0.0.1", 9501);

$http->on("start", function ($server) {
    echo "Swoole http server is started at http://127.0.0.1:9501\n";
});

$http->on("request", function ($request, $response) {
    $response->header("Content-Type", "text/plain");
    $response->end("Hello World\n");
});

$http->start();
*/
$http = new swoole_http_server("127.0.0.1", 9502);

function init($server) {
    echo "Swoole http server is started at http://127.0.0.1:9501\n";
}

function response($request, $response) {
    $response->header("Content-Type", "text/plain");
    $response->end("Hello World\n");
}

$http->on("start", "init");

$http->on("request", "response");

$http->start();