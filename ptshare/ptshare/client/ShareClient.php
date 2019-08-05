<?php
define('SHARE_START_TIME', microtime(true) * 1000);
define('SHARE_CLIENT_PATH', dirname(__FILE__));

date_default_timezone_set('Asia/Shanghai');

require_once (SHARE_CLIENT_PATH . '/config/ShareConfig.php');
require_once (SHARE_CLIENT_PATH . '/lib/ShareLog.php');
require_once (SHARE_CLIENT_PATH . '/lib/ShareRequest.php');

$GLOBALS['SHARE_LOG'] = array(
    'level' => ShareLog::LOG_LEVEL_WARNING, // 日志级别为警告级别，同时业务日志（调试级别）将关闭
    'split' => ShareLog::LOG_SPLIT_DAY,
    'log_file' => SHARE_CLIENT_PATH . '/logs/share_client.log'
);

class ShareClient
{
    const ERROR_NOT_CONFIG = - 1;  // 没有配置
    const ERROR_NOT_COMMIT = - 12; // 并行调用没有提交

    private static $error_descs = array(
        self::ERROR_NOT_CONFIG => '方法[%s]没有配置',
        self::ERROR_NOT_COMMIT => '并行调用没有提交'
    );

    private $request;

    private $userid;

    private $token;

    private static $instance;

    private static $partner = "internal";

    private static $secret = "b95669d3840fa9c7358a8156d9743789";

    private $files = array();

    public static function getInstance()
    {
        if (! isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        $this->request = ShareRequest::getInstance();
    }

    public static function setPrivacy($partner, $secret)
    {
        self::$partner = $partner;
        self::$secret  = $secret;
    }

    public function __call($name, $args = array())
    {
        return $this->dispatch($name, $args);
    }

    public function addFile($field, $filename, $realname)
    {
        $this->files[] = array("field"=>$field, "filename"=>$filename, "realname"=>$realname);
    }

    public static function __callStatic($name, $args = array())
    {
        $client = ShareClient::getInstance();

        if ($client->request->isParallel()) {
            $errno = self::ERROR_NOT_COMMIT;
            $errmsg = sprintf(self::$error_descs[$errno], $name);
            throw new Exception($errmsg, $errno);
        }

        return $client->dispatch($name, $args);
    }

    private function dispatch($name, $args = array())
    {
        if (! isset(ShareConfig::$api_conf[$name])) {
            $errno = self::ERROR_NOT_CONFIG;
            $errmsg = sprintf(self::$error_descs[$errno], $name);
            throw new Exception($errmsg, $errno);
        }

        $api_conf = ShareConfig::$api_conf[$name];

        $host = isset($api_conf['host']) ? trim($api_conf['host']) : '';
        $ip   = isset($api_conf['ip']) ? trim($api_conf['ip']) : '';
        $url  = isset($api_conf['url']) ? trim($api_conf['url']) : '';

        $params = isset($api_conf['params']) ? (array) $api_conf['params'] : array();
        $method = isset($api_conf['method']) ? trim($api_conf['method']) : 'get';
        $secret = self::$secret;

        $connect_timeout = isset($api_conf['connect_timeout']) ? intval($api_conf['connect_timeout']) : null;
        $read_timeout    = isset($api_conf['read_timeout']) ? intval($api_conf['read_timeout']) : null;

        $options = array();
        if ($ip !== '') {
            $url = 'http://' . $ip . $url . '?' . $this->getExtras($secret);
            $options[CURLOPT_HTTPHEADER] = array(
                'Host: ' . $host
            );
        } else {
            $url = 'http://' . $host . $url . '?' . $this->getExtras($secret);
        }

        $options[CURLOPT_USERAGENT] = $_SERVER["HTTP_USER_AGENT"];

        $idx = 0;
        $data = array();
        foreach ($params as $param) {
            if (! isset($args[$idx])) {
                $idx ++;
                continue;
            }
            $data[$param] = $args[$idx];
            $idx ++;
        }

        if(!empty($this->files)) {
            foreach($this->files as $file) {
                $filename = $file["filename"];
                $realname = $file["realname"];
                $field    = $file["field"];

                if($filename != $realname) {
                    $suffix   = end(explode(".", $realname));
                    $realname = $filename.".".$suffix;
                    if(file_exists($filename)) {
                    	@rename($filename, $realname);
                    }
                }

                $data[$field] = curl_file_create($realname);
            }
        }

        if($this->token) {
            $options[CURLOPT_COOKIE] = "token={$this->token}";
        }

        if ($this->request->isParallel()) {
            $this->request->add($url, $method, $data, $options, $connect_timeout, $read_timeout);
        } else {
            return $this->request->execute($url, $method, $data, $options, $connect_timeout, $read_timeout);
        }

        return $this;
    }

    public function bind(&$value)
    {
        $this->request->bind($value);
    }

    public function start()
    {
        $this->request->start();
    }

    public function commit()
    {
        $this->request->commit();
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public static function setUserId($userid, $token = null)
    {
        $ins = self::getInstance();
        $ins->userid = $userid;
        if ($token) {
            $ins->token = $token;
        }
    }

    private function getExtras($secret)
    {
        $params = array(
            'userid' => $this->userid,
            'platform' => "server",
            'partner' => self::$partner,
            'rand' => substr(md5(microtime() . rand(1, 100000000)), 0, 12),
            'time' => time(),
            'remote_addr' => $_SERVER["REMOTE_ADDR"]
        );

        if (self::$partner) {
            $params["partner"] = self::$partner;
        }

        $params["guid"] = md5($params['rand'] . "_" . $params["time"] . $secret);

        return http_build_query($params);
    }
}
?>
