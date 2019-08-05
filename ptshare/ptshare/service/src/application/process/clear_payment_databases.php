<?php
// crontab 初始化机器人
// php /home/dream/codebase/service/src/application/process/clean_relict_live_worker.php
// insert into robots(uid,addtime) select uid,addtime from user limit 200000 ;
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

$dao = new DAOAccount();
$sql = "show tables;";
$all = $dao->getAll($sql);

foreach ($all as $key => $info) {
	$table = $info['Tables_in_grape_payment'];
	$clear_data = "truncate table {$table} ";
	
	$bool = $dao->execute($clear_data);
	var_dump($bool);
}
$datetime = date("Y-m-d H:i:s");
$insert_sql = "insert into account_0 (uid,currency,balance,addtime) values (10000,1,100000000, '$datetime'),(20000,3,5000, '$datetime')";
$bool = $dao->execute($insert_sql, false,false);

$insert_sql = "insert into account_1 (uid,currency,balance,addtime) values (10001,1,1000000, '$datetime')";
$bool = $dao->execute($insert_sql, false,false);
$insert_sql = "insert into account_2 (uid,currency,balance,addtime) values (10002,1,0, '$datetime')";
$bool = $dao->execute($insert_sql, false,false);
$insert_sql = "insert into account_3 (uid,currency,balance,addtime) values (10003,1,4000000, '$datetime')";
$bool = $dao->execute($insert_sql, false,false);

$insert_sql = "insert into account_1 (uid,currency,balance,addtime) values (20001,3,1000, '$datetime')";
$bool = $dao->execute($insert_sql, false,false);
var_dump(count($all) . " => cleared");