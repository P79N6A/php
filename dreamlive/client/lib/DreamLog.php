<?php

class DreamLog
{

    const LOG_LEVEL_NONE = 0x00;

    const LOG_LEVEL_FATAL = 0x01;

    const LOG_LEVEL_WARNING = 0x02;

    const LOG_LEVEL_NOTICE = 0x04;

    const LOG_LEVEL_TRACE = 0x08;

    const LOG_LEVEL_DEBUG = 0x10;

    const LOG_LEVEL_ALL = 0xFF;

    const LOG_SPLIT_NONE = 0;

    const LOG_SPLIT_DAY = 1;

    const LOG_SPLIT_HOUR = 2;

    /**
     *
     * @var array 日志级别字典
     */
    public static $levels = array(
        self::LOG_LEVEL_NONE => 'NONE',
        self::LOG_LEVEL_FATAL => 'FATAL',
        self::LOG_LEVEL_WARNING => 'WARNING',
        self::LOG_LEVEL_NOTICE => 'NOTICE',
        self::LOG_LEVEL_TRACE => 'TRACE',
        self::LOG_LEVEL_DEBUG => 'DEBUG',
        self::LOG_LEVEL_ALL => 'ALL'
    );

    protected $level;

    protected $log_split;

    protected $log_file;

    protected $logid;

    protected $start_time;

    private static $instance = null;

    private function __construct($start_time, $log_config)
    {
        $this->logid = self::genLogId(); // 日志ID
        $this->start_time = $start_time; // 开始时间
        $this->level = intval($log_config['level']); // 日志级别
        $this->log_split = intval($log_config['split']); // 日志切分
        $this->log_file = $log_config['log_file']; // 日志文件
    }

    public static function getInstance()
    {
        if (! isset(self::$instance)) {
            $start_time = defined('DREAM_START_TIME') ? DREAM_START_TIME : microtime(true) * 1000;
            if (empty($GLOBALS['DREAM_LOG'])) {
                $log_config = array(
                    'level' => self::LOG_LEVEL_ALL,
                    'split' => self::LOG_SPLIT_DAY,
                    'log_file' => dirname(dirname(__FILE__)) . '/logs/dream.sdk.log'
                );
            } else {
                $log_config = $GLOBALS['DREAM_LOG'];
            }
            self::$instance = new self($start_time, $log_config);
        }
        
        return self::$instance;
    }

    public static function debug($errlog, $errorno = 0, $args = null, $depth = 0)
    {
        $instance = self::getInstance();
        return $instance->writeLog(self::LOG_LEVEL_DEBUG, $errlog, $errorno, $args, $depth + 1);
    }

    public static function trace($errlog, $errorno = 0, $args = null, $depth = 0)
    {
        $instance = self::getInstance();
        return $instance->writeLog(self::LOG_LEVEL_TRACE, $errlog, $errorno, $args, $depth + 1);
    }

    public static function notice($errlog, $errorno = 0, $args = null, $depth = 0)
    {
        $instance = self::getInstance();
        return $instance->writeLog(self::LOG_LEVEL_NOTICE, $errlog, $errorno, $args, $depth + 1);
    }

    public static function warning($errlog, $errorno = 0, $args = null, $depth = 0)
    {
        $instance = self::getInstance();
        return $instance->writeLog(self::LOG_LEVEL_WARNING, $errlog, $errorno, $args, $depth + 1);
    }

    public static function fatal($errlog, $errorno = 0, $args = null, $depth = 0)
    {
        $instance = self::getInstance();
        return $instance->writeLog(self::LOG_LEVEL_FATAL, $errlog, $errorno, $args, $depth + 1);
    }

    public static function logId()
    {
        return self::getInstance()->logid;
    }

    public static function getClientIP()
    {
        if (isset($_SERVER['HTTP_CLIENTIP'])) {
            $ip = $_SERVER['HTTP_CLIENTIP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '127.0.0.1') {
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $ip = $ips[0];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = '127.0.0.1';
        }
        $pos = strpos($ip, ',');
        if ($pos > 0) {
            $ip = substr($ip, 0, $pos);
        }
        return trim($ip);
    }

    public function writeLog($level, $errlog, $errorno = 0, $args = null, $depth = 0)
    {
        if ($level > $this->level || ! isset(self::$levels[$level])) {
            return;
        }
        
        $level_name = self::$levels[$level];
        $log_file = $this->getLogFile($level);
        
        $debug_trace = debug_backtrace();
        if ($depth >= count($debug_trace)) {
            $depth = count($debug_trace) - 1;
        }
        $filename = basename($debug_trace[$depth]['file']);
        $line = intval($debug_trace[$depth]['line']);
        
        $args_val = '';
        if (is_array($args) && count($args) > 0) {
            foreach ($args as $k => $v) {
                $args_val .= sprintf('%s[%s]', $k, $v);
            }
        }
        
        $time_used = microtime(true) * 1000 - $this->start_time;
        
        $errlog = sprintf("%s: %s [%s:%d] errno[%d] ip[%s] logId[%u] uri[%s] time_used[%d] %s%s\n", $level_name, date('m-d H:i:s:', time()), $filename, $line, $errorno, self::getClientIP(), $this->logid, isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '', $time_used, $args_val, $errlog);
        return file_put_contents($log_file, $errlog, FILE_APPEND);
    }

    private static function genLogId()
    {
        $arrTime = gettimeofday();
        $logid = ((($arrTime['sec'] * 100000 + $arrTime['usec'] / 10) & 0x7FFFFFFF) | 0x80000000);
        return $logid;
    }

    private function getLogFile($level)
    {
        $log_file = $this->log_file;
        if (($level & self::LOG_LEVEL_WARNING) || ($level & self::LOG_LEVEL_FATAL)) {
            $log_file .= '.wf';
        }
        if ($this->log_split == self::LOG_SPLIT_DAY) {
            $log_file .= '.' . date("Ymd");
        } elseif ($this->log_split == self::LOG_SPLIT_HOUR) {
            $log_file .= '.' . date("YmdH");
        }
        return $log_file;
    }
}