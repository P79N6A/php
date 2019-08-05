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
require_once $ROOT_PATH . "/../client/ShareClient.php";

date_default_timezone_set('Asia/Shanghai');

class SaveAvatar
{
    const KEY  = "PTSHARE_WORK_SAVE_AVATAR";
    const STEP = 3000;

    public static function prepare()
    {
        $startime = date('Y-m-d H:00:00', strtotime("-15 minute"));
        $endtime = date('Y-m-d H:00:00', time());

        $sql = "select count(*) from user where modtime between '{$startime}' and  '{$endtime }'  ";
        $dao = new DAOUser();
        $total = $dao->getOne($sql,null,false);

        if($total > 0 ){
            $step = self::STEP;
            for($i = 0; $i < $total; $i += $step){
                $sql = "select avatar,uid from user where modtime between '{$startime}' and  '{$endtime}'  LIMIT {$i},{$step}";
// echo $sql, "\n";
                $dao = new DAOUser();
                $uids = $dao->getAll($sql,null,false);
                if(!empty($uids)){
                    $redis = Cache::getInstance('REDIS_CONF_USER');
                    foreach($uids as $k=>$v){
                        $redis->rPush(self::KEY, $v['uid'].','.$v['avatar']);
                    }
                }
            
            }
        }
    }

    public static function save()
    {
        $redis = Cache::getInstance('REDIS_CONF_USER');
        while($data = $redis->lPop(self::KEY)){
            list($uid,$avatar) = explode(',',$data);
           
            if($avatar){
                if (substr($avatar, 0, 4) != 'http') {
                    continue;
                }
                // // 132 换成 0 
                // if(substr($avatar,-3) == 132){
                //     $avatar = substr($avatar,0, -3).'0';
                // }

                $content = self::getUrlContents($avatar);

                if (! $content) {
                    sleep(3);
                    $content = self::getUrlContents($avatar);
                }
                if (! $content) {
                    Logger::log('avatar_save', 'non_content', [$uid, $avatar]);
                    continue;
                }

                $url = self::uploadAvatar($content);
                if (! $url) {
                    sleep(1);
                    $url = self::uploadAvatar($content);
                }
                if (! $url) {
                    Logger::log('avatar_save', 'non_url', [$uid, $avatar]);
                    continue;
                }

                $url = Util::getURLPath($url);

                try{
                    $dao_user = new DAOUser();
    
                    $dao_user->setUserInfo($uid, "", $url);
        
                    User::reload($uid);

                } catch(Exception $e){
                    Logger::log('avatar_save', 'non_dao', [$uid, $avatar]);
                    continue;
                }

                echo $uid, "\n";
            }

            //小程序码
            try{
                $xcx = new WxProgram();
                $xcx->getUserInviteWxacodeunlimit($uid);
            }catch(Exception $e){
    
            }
        
        }
    }

    public static function getUrlContents($url, $timeout = 30)
    {
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

        if ($http_code != 200){
            $output = false;
        }

        return $output;
    }
    public static function uploadAvatar($photo_data)
    {
        $share_client = ShareClient::getInstance();

        try{
            $result = $share_client->uploadImage(urlencode($photo_data));
        } catch(Exception $e){
            return false;
        }

        return $result['url'];
    }


}
echo "start:" . date("Y-m-d H:i:s") . "\n";
if($argv[1]){
    if($argv[1] == 'prepare'){

        SaveAvatar::prepare();
    }else if($argv[1] == 'save'){

        SaveAvatar::save();
    }
}
echo "end:" . date("Y-m-d H:i:s") . "\n\n";
die;
