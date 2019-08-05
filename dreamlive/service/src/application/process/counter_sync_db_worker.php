<?php
class DbSyncWorker
{

    private static $cached = array();
    
    private static $syncInterval = 30;
    
    /**
     * 计数器发生变化
     * params：product、type、relateid、value、microtime
     */
    public static function sync($data)
    {
        
        if (empty($data)) {
            return true;
        }
        
        $product     = 'dreamlive';
        $type         = $data['params']['type'];
        $relateid     = $data['params']['relateid'];
        $value         = $data['params']['value'];
        $microtime     = $data['params']['microtime'];
        
        try {
            $key = "{$product}_{$type}_{$relateid}";
            if (isset(self::$cached[$key])) {
                if (self::$cached[$key]['microtime'] < $microtime) {
                    self::$cached[$key]['value'] = $value;
                    self::$cached[$key]['dirty'] = true;
                }
            }else{
                self::$cached[$key]['microtime'] = $microtime;
                self::$cached[$key]['value'] = $value;
                self::$cached[$key]['last_sync'] = time();
                self::$cached[$key]['dirty'] = true;
            }
            
            if (self::$cached[$key]['last_sync'] < time() - self::$syncInterval) {
                self::$cached[$key]['last_sync'] = time();
                self::$cached[$key]['dirty'] = false;
                Counter::sync2db($product, $type, $relateid, $value, $microtime);
            }else{
                $result = true;
            }
        }catch (Exception $e){
            Logger::log("process_error", "counter_change1", array("errorcode" => $e->getCode(), "errormsg" => json_encode($e->getMessage())));
        }
        
        return $result;
    }
    
    public static function syncAll()
    {
        $synced = 0;
        $success = 0;
        foreach (self::$cached as $k => $v){
            if ($v['dirty']) {
                ++$synced;
                list($product, $type, $relateid) = explode('_', $k);
                Counter::sync2db($product, $type, $relateid, $v['value'], $v['microtime']);
            }
        }
        file_put_contents('/tmp/sync.log', date('Y-m-d H:i:s') . ' sync before restart. cached:' . count(self::$cached) . ' synced:' . $synced . PHP_EOL, FILE_APPEND);
    }
}

register_shutdown_function(array('DbSyncWorker', 'syncAll'));

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
require_once 'process_client/ProcessClient.php';

try{
    $process = new ProcessClient("dream");
    $process->addWorker("counter_change", array("DbSyncWorker", "sync"), 1, 200000); // 44(RS) * 1 = 44(WORKER)
    $process->run();
}catch (Exception $e){
    Logger::fatal("计数器worker启动失败: " . $e->getMessage());
    Logger::log("process_error", "counter_change", array("errorcode" => $e->getCode(), "errormsg" => json_encode($e->getMessage())));
}
