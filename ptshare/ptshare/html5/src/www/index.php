<?php
ini_set("display_errors", "On");
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
set_include_path(get_include_path() . PATH_SEPARATOR . dirname(dirname(dirname(__FILE__))) . "/config");

require 'server_conf.php';

$LOAD_PATH = array($ROOT_PATH."/config", $ROOT_PATH."/src/application/controllers", $ROOT_PATH."/src/application/models", $ROOT_PATH."/src/application/models/libs", $ROOT_PATH."/src/application/models/libs/payment_client", $ROOT_PATH."/src/application/models/dao", $ROOT_PATH."/src/application/models/libs/oauth_client");
set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $LOAD_PATH));

function __autoload($classname)
{
    $classname = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
    include_once "$classname.php";
}

$router = new Router();

$router->execute();
?>
