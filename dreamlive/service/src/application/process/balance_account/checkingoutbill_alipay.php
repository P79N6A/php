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

$bill_date = date("Y-m-d", strtotime("-1 day"));
$file_path = DepositHelper::bill('alipay', $bill_date);

$dir = dirname($file_path);
$zip_filename = 'alipay' . $bill_date;

$zip = new ZipArchive();
$res = $zip->open($file_path);
if ($res === true) {
    $zip->renameIndex(0, $zip_filename . '_0.csv');
    $zip->renameIndex(1, $zip_filename . '_1.csv');
    $zip->close();

    $res = $zip->open($file_path);
    $zip->extractTo($dir);
} else {
    echo 'failed, code:' . $res;
}
$zip->close();

$dir_iterator = new DirectoryIterator($dir);
$dao_outbill_alipay = new DAOCheckingOutbillAlipay();

foreach ($dir_iterator as $file_info) {
    if ($file_info->isfile()) {
        $file_name = $file_info->getFilename();
        if (strpos($file_name, $zip_filename) === 0) {
            $row = 0;
            if (($handle = fopen($dir . '/' . $file_name, "r")) !== false) {
                while (($data = fgetcsv($handle, 0, ",")) !== false) {
                    $row++;

                    $num = count($data);
                    if ($row == 1) {
                        $title = iconv("GB2312", "UTF-8", $data[0]);
                        if ($title != '#支付宝业务明细查询') {
                            break;
                        }
                    }

                    $data = array_map('csv_clean', $data);
                    if (preg_match('/^[1-9]\d*$/', $data[0]) > 0) {
                        $dao_outbill_alipay->addBill(
                            $data[0],
                            $data[1],
                            $data[2],
                            $data[3],
                            $data[4],
                            $data[5],
                            $data[6],
                            $data[7],
                            // 
                            $data[8],
                            $data[9],
                            $data[10],
                            $data[11],
                            $data[12],
                            $data[13],
                            $data[14],
                            $data[15],
                            $data[16],
                            // 
                            $data[17],
                            $data[18],
                            $data[19],
                            $data[20],
                            $data[21],
                            $data[22],
                            $data[23],
                            $data[24],
                            $bill_date
                        );
                    }

                }
                fclose($handle);
            }
        }
    }
}

function csv_clean($str)
{
    $str = iconv("GB2312", "UTF-8", $str);
    return trim($str);
}