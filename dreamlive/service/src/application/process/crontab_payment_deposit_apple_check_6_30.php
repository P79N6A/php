<?php
// 黑卡充值报警
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

$data = array();
$today     = date('Y-m-d', time());
$yesterday = date('Y-m-d', time()-24*3600);

$deposit = new DAODeposit();


$sql = "SELECT count(id) as num,amount, uid from deposit where source='apple' and `status`='Y' and addtime>'$yesterday' and addtime<'$today' and amount in(6, 30) GROUP BY amount, uid HAVING num>3" ;
$data = $deposit->getAll($sql, null, false);
$str = '';
if(is_array($data)) {
    foreach($data as $key=>$value){
        $str .= "{$value['uid']}, {$value['amount']}, {$value['num']};";
    }
}


if($str) {
    //发送机器人警告
    $url    = 'https://oapi.dingtalk.com/robot/send?access_token=37ccf087279cd9f53af3e2578494f467cfc8910035ce3ac9c190f3b6450a88f3';
    $post_data = array(
    'msgtype'   => 'text',
    'text'      => array(
    'content'   => "apple6.30黑卡充值报警[$yesterday]: $str"
    )
    );
    setPost($url, json_encode($post_data));
}


function setPost($url,$post_string)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array ('Content-Type: application/json;charset=utf-8'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}


