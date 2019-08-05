<?php
//nohup php /home/yangqing/work/dreamlive/service/src/application/process/import_sendgift_to_redis.php > import_task_log.log 2>&1 &

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

class replyurlDetails extends DAOProxy
{

    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_LIVE");
        $this->setTableName("replayurl_details");
    }

    public function getList($startime,$endtime)
    {
        $sql = "select * from ".$this->getTableName()." where status =? and stime>'".trim($startime)."' and etime<='".trim($endtime)."' ";
        return $this->getAll($sql, array('status'=>'N'));
    }

    public function updateDate($replayurl, $option)
    {
        return $this->update($this->getTableName(), $option, "replayurl=?", $replayurl);
    }
}

class LiveReplyurl extends DAOProxy
{

    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_LIVE");
        $this->setTableName("live");
    }

    public function getList($startime,$endtime)
    {
        $sql = "select * from ".$this->getTableName()." where deleted =? and replayurl !='' and endtime>'".trim($startime)."' and endtime<='".trim($endtime)."' ";
        return $this->getAll($sql, array('deleted'=>'Y'));
    }
}



//$addTime = '2017-04-15 00:00:00';
$startime = date('Y-m-d H:i:s', time()-86400*3);
$endtime  = date('Y-m-d H:i:s', time()-600);
echo  "脚本执行live开始:".date('Y-m-d H:i:s');echo "\n";

$replyurlDetails = new replyurlDetails();
$liveReplyurl    = new LiveReplyurl();
$wcs             = new Wcs();

$list = $liveReplyurl->getList($startime, $endtime);
foreach ($list as $key => $val) {
    $result = $wcs->deletem3u8($val['replayurl']);
    if ($result->code == 200) {
        $json = json_decode($result->respBody);
        $option = array(
            'persistentId'=>$json->persistentId,
            'status'=>'N'
        );
        $ret = $replyurlDetails->updateDate($val['replayurl'], $option);
        echo 'replayurl='.$val['replayurl'].'  persistentId='.$json->persistentId.'   ret='.print_r($ret, true);echo "\n";
    }
}

echo  "脚本执行live结束:".date('Y-m-d H:i:s');echo "\n";echo "\n";




echo  "脚本执行replayurl_details开始:".date('Y-m-d H:i:s');echo "\n";

$replyurlDetails = new replyurlDetails();
$list = $replyurlDetails->getList($startime, $endtime);

$wcs = new Wcs();
foreach ($list as $key => $val) {
    $result = $wcs->deletem3u8($val['replayurl']);
    if ($result->code == 200) {
        $json = json_decode($result->respBody);
        $option = array(
            'persistentId'=>$json->persistentId
        );
        $ret = $replyurlDetails->updateDate($val['replayurl'], $option);
        echo 'replayurl='.$val['replayurl'].'  persistentId='.$json->persistentId.'   ret='.print_r($ret, true);echo "\n";
    }
}

echo  "脚本执行replayurl_details结束:".date('Y-m-d H:i:s');echo "\n";echo "\n";echo "\n";echo "\n";echo "\n";echo "\n";echo "\n";



class Wcs
{
    const WCS_MGR_URL    = 'http://dianhuan.mgr20.v1.wcsapi.com';
    const WCS_ACCESS_KEY = '4492bdf57c52dcb1e9e499070694fd2dee50c833';
    const WCS_SECRET_KEY = '4e88feb304553b4e25d27457b42a6a181af51a3c';
    const BUCKET         = "dianhuan-video";

    public static function deletem3u8($key)
    {
        $url = self::WCS_MGR_URL . '/fmgr/deletem3u8';
        $notifyURL = "";
        $separate  = 0;
        $fops = 'fops=bucket/'.url_safe_base64_encode(self::BUCKET).'/key/'.url_safe_base64_encode($key).'/deletets/1&notifyURL='.url_safe_base64_encode($notifyURL).'&separate='.$separate;
        $token = get_token($url, $fops);
        $headers = array("Authorization:$token");
        $ret = http_post($url, $headers, $fops);
        return $ret;
    }
}

function get_token($url, $body=null)
{
    $path = parse_url($url, PHP_URL_PATH);
    $query = parse_url($url, PHP_URL_QUERY);
    if($query) {
        if ($body) {
            $arr = array($path,'?',$query,"\n",$body);
        }else {
            $arr = array($path,'?',$query,"\n");
        }
    }else {
        if ($body) {
            $arr = array($path,"\n",$body);
        }else {
            $arr = array($path,"\n");
        }
    }
    $sign = join("", $arr);
    $encodesign = hash_hmac('sha1', $sign, Wcs::WCS_SECRET_KEY, false);
    return Wcs::WCS_ACCESS_KEY . ':' . url_safe_base64_encode($encodesign);
}

function http_post($url, $headers, $fields, $opt = null)
{
    $opt = array(CURLOPT_CUSTOMREQUEST => 'POST',);
    return __http($url, $headers, $fields, $opt);
}


function __http($url, $headers, $fields = null, $opt = null)
{
    $ch = curl_init();
    $options = array(
        CURLOPT_USERAGENT => get_user_agent(),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_HEADER => true,
        CURLOPT_NOBODY => false,
        CURLOPT_URL => $url,
        CURLOPT_TIMEOUT => 5
    );

    if($opt) {
        foreach ($opt as $key => $value) {
            $options[$key] = $value;
        }
    }

    if (!empty($headers)) {
        $options[CURLOPT_HTTPHEADER] = $headers;
    }

    if (!empty($fields)) {
        $options[CURLOPT_POSTFIELDS] = $fields;
    }

    curl_setopt_array($ch, $options);

    try {
        $result = curl_exec($ch);
    } catch (\Exception $e) {
        throw new \Exception("Caught exception when send request:".$e->getMessage());
    }

    $ret = new Response();
    $errno = curl_errno($ch);
    //错误状态码
    if ($errno !== 0) {
        $ret->code = $errno;
        $ret->message = curl_error($ch);
        curl_close($ch);
        return $ret;
    }

    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    //分割响应头部和内容
    $responseArray = explode("\r\n\r\n", $result);
    $responseArraySize = sizeof($responseArray);
    if ($responseArraySize == 1) {
        $respBody = $responseArray[0];
    } else {
        $respHeader = $responseArray[0];
        $respBody = $responseArray[1];
        $ret->respHeader = $respHeader;
    }

    $ret->respBody = $respBody;
    $ret->code = $code;

    //超时判断
    if ($ret->code == 28) {
        $ret->respBody = "请求超时！";
    }
    return $ret;
}

function url_safe_base64_encode($str)
{
    $find = array('+', '/');
    $replace = array('-', '_');
    return str_replace($find, $replace, base64_encode($str));
}
function get_user_agent()
{
    $sdkInfo = "WCS PHP SDK /" . "2.0.4" . " (http://wcs.chinanetcenter.com/)";

    $systemInfo = php_uname("s");
    $machineInfo = php_uname("m");

    $envInfo = "($systemInfo/$machineInfo)";

    $phpVer = phpversion();

    $ua = "$sdkInfo $envInfo PHP/$phpVer";
    return $ua;
}

class Response
{

    public $code; //响应状态码

    public $message; //响应信息，当响应内容为二进制数据流时返回响应信息

    public $respHeader; //响应头部

    public $respBody; //响应内容

}


