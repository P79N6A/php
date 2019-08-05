<?php
/**
 * 游戏脚本（星钻场）
 */

if (substr(php_sapi_name(), 0, 3) !== 'cli') { die("cli mode only");
}

set_time_limit(0);
ini_set('memory_limit', '2G');
ini_set("display_errors", "On");
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

$ROOT_PATH = dirname(dirname(dirname(dirname(__FILE__))));

$LOAD_PATH = array(
    $ROOT_PATH . "/src/www",
    $ROOT_PATH . "/config",
    $ROOT_PATH . "/src/application/controllers",
    $ROOT_PATH . "/src/application/models",
    $ROOT_PATH . "/src/application/models/libs",
    $ROOT_PATH . "/src/application/models/dao",
    $ROOT_PATH . "/../",
);
set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $LOAD_PATH));

function __autoload($classname)
{
    $classname = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
    include_once "$classname.php";
}

require $ROOT_PATH . "/config/server_conf.php";



$FLAG = false;

function killSigHandler($signo)
{
    switch ($signo) {
    case SIGTERM:
        $GLOBALS['FLAG'] = true;
        break;
    default:
        break;
    }
}

class HandbookingerServer
{
    const LOCK_FILE = "./runhorse.lock";
    const PID_FILE = "./runhorse.pid";

    private $engine = null;
    private $stop = false;

    private static $instance = null;

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new HandbookingerServer();
        }
        return self::$instance;
    }

    private function __construct()
    {
        if (!$this->engine) {
            $this->engine = new HandbookingerEngine();
        }
    }


    public function run()
    {
        pcntl_signal(SIGTERM, "killSigHandler");
        while (true) {
            try{
                $this->engine->init();
                $this->engine->startGame();
                $this->engine->stakeStart();
                $this->engine->stakeEnd();
                $this->engine->runStart();
                $this->engine->runEnd();
            } catch (Exception $e) {
                throw $e;
            } finally {
                //print('server exit');
            }
            pcntl_signal_dispatch();
            if ($this->stop || $GLOBALS['FLAG']) {
                break;
            }
        }
    }

    private function wait($starttime, $after)
    {
        while (time() >= $starttime + $after) {
            return;
        }
    }

    public function start()
    {
        $this->run();
    }

    public function stop()
    {
        $this->stop = true;
    }

    public function restart()
    {
        $this->stop();
        sleep(3);
        $this->start();
    }
}

//
HandbookingerServer::getInstance()->start();


