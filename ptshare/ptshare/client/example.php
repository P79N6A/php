<?php
$params = array(
    "userid"=>1192213,
    "deviceid"=>'1289721',
    "platform"=>'ios',
    "network"=>'wifii',
    "version"=>'1.0.0',
    "rand"=>2834,
    "netspeed"=>26432,
    "time"=>2347324
);

ksort($params);

print_r($params);
exit;

require_once (dirname(__FILE__) . '/ShareClient.php');
$share_client = ShareClient::getInstance();

$share_client->addFile("file", "d:/cecq1mwUyT39A.jpg", "d:/cecq1mwUyT39A.jpg");
$result = $share_client->uploadImage("cecq1mwUyT39A.jpg", "avatar");

var_dump($result);
exit;
function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

$time_start = microtime_float();

//单个接口请求
try {
    $share_client->setUserId(1000);
    $result = $share_client->getMessageToken(1000);
} catch (Exception $e) {
    print $e->getMessage();
}

print "1000\n";
print_r($result);

try {
    $share_client->setUserId(1001);
    $result = $share_client->getMessageToken(1001);
} catch (Exception $e) {
    print $e->getMessage();
}

print "1001\n";
print_r($result);

try {
    $share_client->setUserId(1002);
    $result = $share_client->getMessageToken(1002);
} catch (Exception $e) {
    print $e->getMessage();
}

print "1002\n";
print_r($result);

try {
    $share_client->setUserId(1003);
    $result = $share_client->getMessageToken(1003);
} catch (Exception $e) {
    var_dump($e);
    print $e->getMessage();
}

print "1003\n";
print_r($result);

$time_end = microtime_float();
$time = $time_end - $time_start;

echo "Did nothing in $time seconds\n";

//批量接口请求
$time_start = microtime_float();

$user_token_1000 = $user_token_1001 = $user_token_1002 = $user_token_1003 = $user_token_1007 = array();

$share_client->start();
$share_client->setUserId(1000);
$share_client->getMessageToken(1000)->bind($user_token_1000);
$share_client->setUserId(1001);
$share_client->getMessageToken(1001)->bind($user_token_1001);
$share_client->setUserId(1002);
$share_client->getMessageToken(1002)->bind($user_token_1002);
$share_client->setUserId(1003);
$share_client->getMessageToken(1003)->bind($user_token_1003);
$share_client->commit();

print_r($user_token_1000);
print_r($user_token_1001);
print_r($user_token_1002);
print_r($user_token_1003);

$time_end = microtime_float();
$time = $time_end - $time_start;

echo "Did nothing in $time seconds\n";
