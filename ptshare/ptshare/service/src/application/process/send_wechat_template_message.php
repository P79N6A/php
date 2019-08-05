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

// 推送捐赠成功消息
$wxProgram = new WxProgram();

$languageConfig = Context::getConfig("SYSTERM_COMMON_LANGUAGE");
$remark = $languageConfig['wx_program_template_msg'][WxProgram::TYPE_SELL_ADD_SUCCESS]['remark'];

$contact = json_decode($contact, true);

$data = [
		Order::getOrderId(),
		"么么哒",
		999,
		DAOSell::getStatusData(DAOSell::STATUS_WAIT_ONLINE_AUDIT),
		"北京昌平回龙观",
		$remark,
];
$userid = "20000031";
$wxProgramFormId = "812a9647e54c57cdcea4d248b22d9c87";
if ($wxProgramFormId) {
	Context::add("wx_program_form_id", $wxProgramFormId);
}
$wxProgram->sendTemplateMessage($userid, WxProgram::TYPE_SELL_ADD_SUCCESS, $data);