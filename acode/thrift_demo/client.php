<?php
namespace UserModule\php;
error_reporting(E_ALL);
require_once __DIR__.'/PHP/Thrift/ClassLoader/ThriftClassLoader.php';

use Thrift\ClassLoader\ThriftClassLoader;

$GEN_DIR = __DIR__.'/ThriftGen/gen-php';
$loader = new ThriftClassLoader();
$loader->registerNamespace('Thrift', __DIR__ . '/PHP');
$loader->registerDefinition('UserModule', $GEN_DIR);
$loader->register();

use Thrift\Protocol\TBinaryProtocol;
use Thrift\Protocol\TMultiplexedProtocol;
use Thrift\Transport\THttpClient;
use Thrift\Transport\TBufferedTransport;
use Thrift\Exception\TException;
use UserModule\InvalidException;
use UserModule\User;

try {
  $socket = new THttpClient('localhost', 8081, '/acode/thrift_demo/userserver.php');
  $transport = new TBufferedTransport($socket);
  $protocol = new TBinaryProtocol($transport);

  $loginProtocol = new TMultiplexedProtocol($protocol, 'loginService');
  $loginService = new \UserModule\LoginServiceClient($loginProtocol);
  $result = $loginService->Register('wangzhongwei3','areyouok');
  print_r($result);
  echo '<br/>';

  // $result = $loginService->login('wangzhongwei','areyouok');
  // var_dump($result);
  // echo '<br/>';

  $userProtocol = new TMultiplexedProtocol($protocol, 'userService');
  $userService = new \UserModule\UserServiceClient($userProtocol);
  $result = $userService->Detail(1);
  print_r($result);
  echo '<br/>';

  $result->age = $result->age+3;
  $result = $userService->Update($result);
  print_r($result);
  echo '<br/>';

  $params = new \UserModule\InParamsUser();
  $params->page = 1;
  $params->pageSize = 10;
  $result = $userService->search($params);
  print_r($result);
  echo '<br/>';

  $result = $userService->getAllStatus();
  //var_dump($resulthrift -out .. --gen go example.thriftt);
  echo '<br/>';

  $transport->close();

} catch (InvalidException $tx) {
  print 'TException: '.$tx->getCode().' '.$tx->getMessage()."\n";
}


