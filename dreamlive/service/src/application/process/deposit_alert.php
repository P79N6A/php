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
$dao_deposit = new DAODeposit();
$start_date = date("Y-m-d H:i:s", time() - 60*60);
$end_date = date("Y-m-d H:i:s", time());
$sql = "select * from deposit where status='Y' and amount>=1000 and  (addtime BETWEEN '$start_date' and '$end_date')";
$data = $dao_deposit->getAll($sql,  null, false);
//18001378477李世斌,‭18618181550‬陈雷, 18179185055方总
$mobiles = array('13426374937', '13691544388', '18179185055',  '18001378477', '‭18618181550‬');
$templateid = 3055351;
//| 得到今天的超过1000的发送警送短信. 
if(is_array($data)) {
    foreach ($data as $key => $value) {
        $params = array($value['uid'], $value['orderid'], $value['amount'], $value['addtime']);
        send($mobiles, $params, $templateid);
    }
}

function send($mobiles, $params, $templateid)
{
    /* {{{ 网易云信 发送模板短信*/

    $url = 'https://api.netease.im/';
    $APP_KEY = 'd8f7866e5f3d8177d9d083b757399fd1';
    $APP_SECRET = 'd33b409612d0';
    $YUNPIAN_APP_KEY = 'd071488cb16016591c4b32b08cd3e4ba';
    $start_time = microtime(true);

    $actionUrl = $url. 'sms/sendtemplate.action';
    $Nonce = mt_rand();
    $CurTime = time();
    $head_arr = array(
    'Content-Type:application/x-www-form-urlencoded; charset=utf-8',
    'AppKey:'. $APP_KEY,
    'Nonce:'. $Nonce,
    'CurTime:'. $CurTime,
    'CheckSum:'. strtolower(sha1($APP_SECRET.$Nonce.$CurTime))
    );

    $data = array(
    'templateid' => $templateid,
    'params' => json_encode($params),
    'mobiles' => json_encode($mobiles),
    );

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, true);
    curl_setopt($ch, CURLOPT_URL, $actionUrl);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $head_arr);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

    $content = curl_exec($ch);
    $errno = curl_errno($ch);
    $error = curl_error($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);


    if((false === $content) || (0 !== $errno)) {
        throw new Exception($error, -1);
    }

    if($status != 200) {
        throw new Exception("http request fail, status:$status", $status);
    }

    $result = json_decode($content, true);

    if($result["code"] != 200) {
        throw new Exception($result["msg"], $result["code"]);
    }
    return true;
} /* }}} */
