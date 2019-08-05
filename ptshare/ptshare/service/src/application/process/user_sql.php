<?php

set_time_limit(0);
ini_set('memory_limit', '2G');
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

$dao_user = new DAOUser();
$sql = "select avatar,id from user_ex where id > ?";
$all_user_ex = $dao_user->getAll($sql,array(40606));
$storage = new Storage();
foreach ($all_user_ex as $ex) {
	$image_url = "http://static.tongyigg.com".$ex['avatar'];
	$pathinfo = pathinfo($image_url);
	$image_data = BookStore::getImageUrl($image_url);
	$url = $storage->addImage($pathinfo['extension'], $image_data, 'default');
	$url = str_replace('https://static.putaofenxiang.com', '', $url);
	$info = ['tp' => $url];
	$bool = $dao_user->update("user_ex", $info, " id=? ", array($ex['id']));

	var_dump($ex['id']."====>" . $bool);

}