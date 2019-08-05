<?php
/**
 * 游戏脚本（星光场）
 */

if (substr(php_sapi_name(), 0, 3) !== 'cli') { die("cli mode only");
}

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
    $ROOT_PATH."/src/application/models/dao",
    $ROOT_PATH."/../",
);
set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $LOAD_PATH));

function __autoload($classname)
{
    $classname = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
    include_once "$classname.php";
}

require $ROOT_PATH."/config/server_conf.php";


$debug=isset($argv[1])&&$argv[1]=='debug'?true:false;

$FLAG=false;

function killSigHandler($signo)
{
    switch ($signo){
    case SIGTERM:
        $GLOBALS['FLAG']=true;
        break;
    default:
        break;
    }
}

class RunHorseServer
{
    const LOCK_FILE="./runhorse.lock";
    const PID_FILE="./runhorse.pid";

    private $engine=null;
    private $stop=false;

    private static  $instance=null;

    public static function getInstance($debug=false)
    {
        if (!self::$instance) {
            self::$instance=new RunHorseServer($debug);
        }
        return self::$instance;
    }

    private function __construct($debug=false)
    {
        if (!$this->engine) {
            $this->engine=new HorseracingEngineStar($debug);
        }
    }


    public function run()
    {
        pcntl_signal(SIGTERM, "killSigHandler");
        while (true){

            try{
                $timeline=null;
                try{
                    $this->engine->init();
                    $timeline=$this->engine->getTimeline();
                    $this->engine->startGame();
                    $this->engine->startBanker();
                    sleep($timeline['banker_time_span_robot']-1);//提前1s开始机器人抢庄
                }catch (Exception $e1){
                    sleep(
                        $timeline['banker_time_span']
                        +$timeline['banker_to_stake_span']
                        +$timeline['stake_time_span']
                        +$timeline['stake_to_run_span']
                        +$timeline['run_prepare_span']
                        +$timeline['run_time_span']
                        +$timeline['result_winner_span']
                        +$timeline['result_reward_top3_span']
                    );
                    throw $e1;
                }

                try{
                    $this->engine->robotBanker();
                    sleep($timeline['banker_time_span']-$timeline['banker_time_span_robot']+$timeline['banker_to_stake_span']);
                }catch (Exception $e2){
                    sleep(
                        $timeline['banker_time_span']
                        -$timeline['banker_time_span_robot']
                        +$timeline['banker_to_stake_span']
                        +$timeline['stake_time_span']
                        +$timeline['stake_to_run_span']
                        +$timeline['run_prepare_span']
                        +$timeline['run_time_span']
                        +$timeline['result_winner_span']
                        +$timeline['result_reward_top3_span']
                    );
                    throw $e2;
                }

                try{
                    $this->engine->startStake();
                    sleep($timeline['stake_time_span']+$timeline['stake_to_run_span']);
                }catch (Exception $e3){
                    sleep(
                        $timeline['stake_time_span']
                        +$timeline['stake_to_run_span']
                        +$timeline['run_prepare_span']
                        +$timeline['run_time_span']
                        +$timeline['result_winner_span']
                        +$timeline['result_reward_top3_span']
                    );
                    throw $e3;
                }

                try{
                    $this->engine->startRun();
                    sleep($timeline['run_prepare_span']-1);
                    $this->engine->settle();
                    sleep($timeline['run_time_span']-2+1);
                    $this->engine->gameOver();
                    if ($this->engine->checkStakeNum()) {
                        sleep($timeline['result_winner_span']+$timeline['result_reward_top3_span']+2);
                    }else{
                        sleep($timeline['result_winner_span']+ 1);
                    }
                } catch (Exception $e4){
                    sleep(
                        $timeline['run_prepare_span']
                        +$timeline['run_time_span']
                        +$timeline['result_winner_span']
                        +$timeline['result_reward_top3_span']
                    );
                    throw $e4;
                }

            }catch (Exception $e){
                print_r($e);
                print_r("\n");
                sleep(1);//
            }finally{
                sleep(HorseracingEngine::NEXT_ROUND_TIME);
            }
            pcntl_signal_dispatch();
            if ($this->stop||$GLOBALS['FLAG']) {
                break;
            }
        }
    }

    private function wait($starttime,$after)
    {
        while (time()>=$starttime+$after){
            return;
        }
    }
    public function start()
    {
        $this->run();
    }

    public function stop()
    {
        $this->stop=true;
    }

    public function restart()
    {
        $this->stop();
        sleep(3);
        $this->start();
    }
}

//
RunHorseServer::getInstance(true)->start();


