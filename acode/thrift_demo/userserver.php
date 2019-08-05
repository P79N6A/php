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

if (php_sapi_name() == 'cli') {
  ini_set("display_errors", "stderr");
}

use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TPhpStream;
use Thrift\Transport\TBufferedTransport;
use Thrift\TMultiplexedProcessor;
use \UserModule\User;
use \UserModule\InvalidException;

class LoginServiceHandler implements \UserModule\LoginServiceIf
{
    public function Login($username, $pwd)
    {

    }

    public function Register($username, $pwd)
    {
        $objUser = new User();
        if($username=='wangzhongwei') {
          throw new InvalidException(array('code'=>111,'message'=>'该用户已经注册！'));
        }else {
           $objUser->username = $username;
            $objUser->password = $pwd;
            $objUser->id = 1;
            $objUser->age = 33;
            $objUser->email = 'areyouok@163.com';
            $objUser->tel = '13716857451';
        }

        return $objUser;
    }

    public function getCheckCode($sessionid)
    {

    }

    public function verifyCheckCode($sessionid, $checkcode)
    {

    }
}

class UserServiceHandler implements \UserModule\UserServiceIf
{
    public function Detail($id)
    {
        $objUser = new User();
        if($id == 1) {
          $objUser->username = 'wangxiaoxiao';
          $objUser->password = 'howareyouok';
          $objUser->id = 1;
          $objUser->age = 34;
          $objUser->email = 'areyouok@163.com';
          $objUser->tel = '13716857451';
        }
        return $objUser;
    }
    public function Update(\UserModule\User $user)
    {
        $objUser = new User();
        if($user->id == 1) {
          $objUser->id = $user->id;
          $objUser->username = $user->username;
          $objUser->age = $user->age;
          $objUser->email = $user->email;
          $objUser->tel = $user->tel;
        }
        return $objUser;
    }
    public function search(\UserModule\InParamsUser $inParams)
    {
        $result = new \UserModule\OutputParamsUser();
        $result->page = $inParams->page;
        $result->pageSize = $inParams->pageSize;
        $result->totalNum = 1;
        $arrUsers = [];
        $objUser = new User();
        $objUser->username = 'wangxiaoxiao';
        $objUser->password = 'howareyouok';
        $objUser->id = 1;
        $objUser->age = 34;
        $objUser->email = 'areyouok@163.com';
        $objUser->tel = '13716857451';

        $arrUsers[] = $objUser;

        $objUser1 = clone $objUser;
        $objUser1->username = 'whatareyoudoing';
        $objUser1->tel = '13855254475';
        $arrUsers[] = $objUser1;

        $result->records = $arrUsers;

        return $result;
    }
    public function getAllStatus()
    {
        return [0=>'新创建',1=>'已认证',2=>'已删除'];
    }
}


header('Content-Type', 'application/x-thrift');
if (php_sapi_name() == 'cli') {
  echo "\n";
}

$transport = new TBufferedTransport(new TPhpStream(TPhpStream::MODE_R | TPhpStream::MODE_W));
$protocol = new TBinaryProtocol($transport, true, true);
$tMultiplexedProcessor = new TMultiplexedProcessor();

$loginService = new LoginServiceHandler();
$loginServiceProcessor = new \UserModule\LoginServiceProcessor($loginService);
$tMultiplexedProcessor->registerProcessor('loginService', $loginServiceProcessor);

$userService = new UserServiceHandler();
$userServiceProcessor = new \UserModule\UserServiceProcessor($userService);
$tMultiplexedProcessor->registerProcessor('userService', $userServiceProcessor);

$transport->open();

try{
  $tMultiplexedProcessor->process($protocol, $protocol);
} catch(TException $e) {
  $transport->close();
  throw $e;
}

$transport->close();
