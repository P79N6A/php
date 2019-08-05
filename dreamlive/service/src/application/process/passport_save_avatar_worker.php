<?php
class SaveAvatar
{
    public static function execute($value)
    {
        $uid = $value["params"]["uid"];
        User::reload($uid);
        $user_info = User::getUserInfo($uid);
        if (! $user_info) {
            sleep(3);
            $user_info = User::getUserInfo($uid);
        }
        //微信 /0 头像
        if($user_info["avatar"] == '/0') {
            $dao_user = new DAOUser();
            $dao_user->update("user", array('avatar'=>''), "uid=?", $uid);

            return true;
        }

        if (substr($user_info["avatar"], 0, 26) == 'http://static.dreamlive.com') {
            return true;
        }
        if (substr($user_info["avatar"], 0, 7) != 'http://') {
            return true;
        }

        $data = self::getUrlContents($user_info["avatar"], 5);
        if (! $data) {
            sleep(3);
            $data = self::getUrlContents($user_info["avatar"], 10);
            if (! $data) {
                return false;
            }
        }

        $url = self::uploadAvatar($data);
        if (! $url) {
            sleep(1);
            $url = self::uploadAvatar($data);
            if (! $url) {
                return false;
            }
        }

        $temp = parse_url($url);
        $url = $temp['path'];

        $dao_user = new DAOUser();
        $dao_user->setUserInfo($uid, "", "", $url);
        User::reload($uid);

        return true;
    }
    public static function getUrlContents($url, $timeout = 30)
    {
        /*{{{*/
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.109 Safari/537.36');

        $output = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($http_code != 200) {
            $output = false;
        }

        return $output;
    }/*}}}*/
    public static function uploadAvatar($photo_data)
    {
        /*{{{*/
        $dream_client = DreamClient::getInstance();

        try{
            $result = $dream_client->uploadImage(urlencode($photo_data), 'avatar');
        } catch(Exception $e){
            return false;
        }

        return $result['url'];
    }/*}}}*/
}
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
require_once "process_client/ProcessClient.php";
require_once "dream_client/DreamClient.php";

try{
    $process = new ProcessClient("dream");

    $process->addWorker("passport_save_avatar",  array("SaveAvatar", "execute"),  10, 2000);

    $process->run();
} catch (Exception $e) {
    ;
}
?>
