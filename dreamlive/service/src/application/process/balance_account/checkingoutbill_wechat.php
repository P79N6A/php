<?php
set_time_limit(0);
ini_set('memory_limit', '1G');
ini_set("display_errors", "On");
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
$ROOT_PATH = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
$LOAD_PATH = [
    $ROOT_PATH . "/src/www",
    $ROOT_PATH . "/config",
    $ROOT_PATH . "/src/application/controllers",
    $ROOT_PATH . "/src/application/models",
    $ROOT_PATH . "/src/application/models/libs",
    $ROOT_PATH . "/src/application/models/dao",
    $ROOT_PATH . "/src/application/models/libs/payment_client",
];
set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $LOAD_PATH));

function __autoload($classname)
{
    $classname = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
    include_once "$classname.php";
}

require $ROOT_PATH . "/config/server_conf.php";

$bill_date = date("Ymd", strtotime("-1 day"));
$file_path = DepositHelper::bill('wechat', $bill_date);

$dao_outbill_wechat = new DAOCheckingOutbillWechat();
$row = 1;
if (($handle = fopen($file_path, "r")) !== false) {
    while (($data = fgetcsv($handle, 0, ",")) !== false) {
        $num = count($data);

        if (strpos($data[0], '`20') === 0) {
            if ($num != 24) {
                continue;
            }
        } else {
            continue;
        }

        $data = array_map('csv_clean', $data);

        $addtime = date('Y-m-d', strtotime($bill_date));
        list($tradetime, $ghid, $mchid, $submch, $deviceid, $wxorder, $bzorder, $openid, $tradetype, $tradestatus, $bank, $currency, $totalmoney, $redpacketmoney,
            $wxrefund, $bzrefund, $refundmoney, $redpacketrefund, $refundtype, $refundstatus, $productname, $bzdatapacket, $fee, $rate) = $data;

        $dao_outbill_wechat->addBill(
            $tradetime, $ghid, $mchid, $submch, $deviceid, $wxorder, $bzorder, $openid, $tradetype, $tradestatus, $bank, $currency, $totalmoney, $redpacketmoney,
            $wxrefund, $bzrefund, $refundmoney, $redpacketrefund, $refundtype, $refundstatus, $productname, $bzdatapacket, $fee, $rate, $addtime
        );

    }
    fclose($handle);
}

function csv_clean($str)
{
    return ltrim($str, '`');
}