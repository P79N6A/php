<?php

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

class GuardDetail extends DAOProxy
{
    public function __construct()
    {
        
        $this->setDBConf("MYSQL_CONF_MESSAGE");
        $this->setTableName("user_guard_detail");
    }
    
    public function getList()
    {
        $sql = "select * from ".$this->getTableName();
        return $this->getAll($sql);
    }
}


echo  "脚本执行开始:".date("Y-m-d H:i:s")."\r\n";

$guardDetail = new GuardDetail();
$list = $guardDetail->getList();


if($list) {
    $c1 = $c2 = 0;
    try{
        foreach($list as $items){
            $uid = $items['uid'];
            $relateid = $items['relateid'];
            $price = $items['price'];
            $orderid = $items['orderid'];

            $ret = AccountInterface::getBackGuard($uid, $relateid, $price, $orderid);
            if($ret) {
                $c1 = $c1+1;
            }else{
                $c2 = $c2+1;
            }
            echo var_dump($ret)."\r\n";
        }
    }catch(exception $e){
        print_r($e);
        exit(0);
    }
}

echo  "成功：".$c1,"失败：".$c2."\r\n";
echo  "脚本执行结束:".date("Y-m-d H:i:s")."\r\n";


