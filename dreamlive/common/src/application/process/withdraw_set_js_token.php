<?php
// crontab 每两个小时设置一次
ini_set("display_errors", "On");
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

$ROOT_PATH = dirname(dirname(dirname(dirname(__FILE__))));
$LOAD_PATH = array(
    $ROOT_PATH."/src/www",
    $ROOT_PATH."/config",
    $ROOT_PATH."/src/application/controllers",
    $ROOT_PATH."/src/application/models",
    $ROOT_PATH."/src/application/models/libs",
    $ROOT_PATH."/src/application/models/dao"
);
set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $LOAD_PATH));

function __autoload($classname)
{
    $classname = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
    include_once "$classname.php";
}
require $ROOT_PATH . "/config/server_conf.php";
$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wxd42f1708840ee63d&secret=0f7758d71e900ec9387d689b8890ba8b";
$result = getCurlData($url, []);
$token  = json_decode($result, true);

$config = new Config();
$result = $config->setConfig(325, 'china', "payment_token", $token['access_token'], 7200, 'server', '1.0.0.0', '1000000');

print "<pre>";
print_r("设置access_token结果:");
print "</pre>";
print "<pre>";
print_r($result);
print "</pre>";
sleep(30);

$config = new Config();
$access_token = $config->getConfig('china', "payment_token", "server", '1.0.0.0');

$jsurl = "https://api.weixin.qq.com/cgi-bin/ticket/getticket";
$url   = $jsurl . "?type=jsapi&access_token=" . $access_token['value'];
$result = getCurlData($url, []);
$json = json_decode($result, true);

$config->setConfig(326, 'china', "payment_js_token", $json['ticket'], 7200, 'server', '1.0.0.0', '1000000');

print "<pre>";
print_r("设置js_token结果:");
print "</pre>";
print "<pre>";
print_r($json);
print "</pre>";
print "<pre>";
print_r($result);
print "</pre>";


function getCurlData($url, $xml, $useCert = false, $second = 30)
{
    $ch = curl_init();
    //设置超时
    curl_setopt($ch, CURLOPT_TIMEOUT, $second);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);//严格校验
    //设置header
    curl_setopt($ch, CURLOPT_HEADER, false);
    //要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
    //运行curl
    $data = curl_exec($ch);
    if ($data) {
        curl_close($ch);
        return $data;
    } else {
        $error = curl_errno($ch);
        curl_close($ch);
    }
}
?>
