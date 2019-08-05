<?php

use Hprose\Http\Server;

function helloworlddd($name) {
    return "Hello $name!";
}

$server = new Server();
$server->addFunction('helloworlddd');
$server->start();