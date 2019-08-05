<?php
ini_set("display_errors", "On");
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);// 

set_include_path(get_include_path() . PATH_SEPARATOR . dirname(dirname(dirname(__FILE__))) . "/config");

require 'server_conf.php';

$LOAD_PATH = array($ROOT_PATH."/config", $ROOT_PATH."/src/application/controllers", $ROOT_PATH."/src/application/models", $ROOT_PATH."/src/application/models/libs");

set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $LOAD_PATH));

function __autoload($classname)
{
    include_once "$classname.php";
    
}

$url = $_SERVER["REQUEST_URI"];

preg_match("/([^\/]+)\/([^\/?]+)?/", $url, $matches);

list($method, $controller, $action) = $matches;

$controller = $controller ? $controller : 'index';
$action = $action ? $action : 'index';

$controllerName = ucfirst($controller).'Controller';
$actionName = $action.'Action';

Context::add("controllerName", $controllerName);
Context::add("actionName", $actionName);
Context::add("method", $method);

try {
    $controller = new $controllerName();

    if (!method_exists($controller, $actionName)){
        throw new Exception("$actionName method is not exists\n", -1000);
    }

    $controller->$actionName();
} catch (Exception $e) {
    header('Content-Type: application/json; charset=utf-8');
    header("Server: nginx/1.2.3");

    $result = array(
        "errno"     => $e->getCode(),
        "werrmsg"    => $e->getMessage(),
    	"errmsg"    => $e->getMessage(),
        "consume"   => Consume::getTime(),
        "time"      => time()
    );

    if (! $result['errno']) {
        $result['errno']    = ERROR_SYS_UNKNOWN;
        $result['errmsg']   = Util::getError(ERROR_SYS_UNKNOWN) . 'w';
    }

    $content = json_encode($result);

    Logger::warning($content, array(), $result["errno"]);

    print $content;
    exit();
}
?>