<?php
/**
 * 当线上反馈接收礼物数有问题时候
 * 更新redis主播接收礼物获得的票数。
 */
set_time_limit(0);
ini_set('memory_limit', '1G');
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

require $ROOT_PATH."/config/server_conf.php";


$dao_deposit = new DAODeposit();
//每天运行一次， 下午执行， 12小时前到前一天的时间
$start_date = date("Y-m-d H:i:s", time() - 36 * 60*60);
$end_date = date("Y-m-d H:i:s", time()- 12 * 60*60);
$sql = "select * from deposit where status='P' and (addtime BETWEEN '$start_date' and '$end_date')";
$data = $dao_deposit->getAll($sql,  null, false);
$date = date("Y-m-d");
$file = fopen("./$date.txt", "a+");            
if(is_array($data)) {
    foreach ($data as $key => $value) {
        $url = "http://api.dreamlive.tv/deposit/verify?orderid={$value['orderid']}&platform=server&partner=internal&rand=643e6a45c057&time=1484296665&remote_addr=192.168.1.31&guid=71c58c4d3f9e994c20f832f52032c0d0";
        $data = getCurlData($url);
        fputs($file, $value['orderid'] . ":" . json_encode($data) . "\n");
    }
}
fclose($file);
print_r("over .. \n");
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

