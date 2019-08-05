<?php
class User
{
    public function login($username, $password, $captcha)
    {
        $is_login_failed = Restrict::check("login_failed", $username);

        if(Util::isMobile($username)) {
            $dao_user_bind = new DAOUserBind();
            $bindinfo = $dao_user_bind->getUserBindBySource($username, "mobile");

            $uid = $bindinfo["uid"];
        } else if(filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $dao_user_bind = new DAOUserBind();
            $bindinfo = $dao_user_bind->getUserBindBySource($username, "email");

            $uid = $bindinfo["uid"];
        } else {
            $uid = $username;
            if(!empty($real_uid = UserPersist::getRealUidByUid($username))) {
                $uid = $real_uid;
            }
        }

        $forbidden = new Forbidden();
        if($forbidden->isForbidden($uid)) {
            $dao_forbidden = new DAOForbidden();
            $forbidden_info = $dao_forbidden->getForbidden($uid);
            $reason = $forbidden_info["reason"];
            $expire = date("Y年m月d日", $forbidden_info["expire"]);
            $userinfo = $this->getUserInfo($uid);

            Interceptor::ensureNotFalse(false, ERROR_USER_ERR_BLACK, [$userinfo['nickname'], $uid, $expire]);
        }

        $dao_user = new DAOUser();
        $loginInfo = $dao_user->getUserInfo($uid);

        if(md5($password.$loginInfo["salt"]) != $loginInfo["password"]) {
            Restrict::add("login_failed", $username);
            $dao_error_log = new DAOErrorLog();
            $dao_error_log->addLog(null, $username, ERROR_USER_PASSWORD_WRONG);

            Interceptor::ensureNotFalse(false, ERROR_USER_PASSWORD_WRONG);
        }

        Restrict::delete("login_failed", $username);

        $userinfo = $this->getUserInfo($uid);
        $userinfo["token"] = Session::getToken($uid);

        //channel
        $userinfo['channel'] =  (string) $this->getUserChannel($uid);

        $this->addLoginLog($uid);

        //用户召回 拉新20180303
        if(Util::isMobile($username)) {
            $recall['uid']      = $uid;
            $recall['rid']      = $username;
            $recall['deviceid'] = Context::get("deviceid");
            ProcessClient::getInstance("dream")->addTask("passport_sync_user_recall", $recall);
        }

        return $userinfo;
    }

    public function register($mobile, $source, $password, $nickname, $gender, $birth)
    {
        $user_persist = new UserPersist();
        $uid = $user_persist->getUserId();
        $addtime = date("Y-m-d H:i:s");
        $modtime = date("Y-m-d H:i:s");

        try {
            $dao_user = new DAOUser();
            $dao_user->startTrans();

            $dao_user->addUser($uid, $nickname, "", "", $gender, "", $birth, Context::get('region'), $addtime, $modtime);

            $dao_user_bind = new DAOUserBind();
            $dao_user_bind->addUserbind($uid, $mobile, $source, $nickname, '', '');

            $this->setPassword($uid, $password);

            $dao_user_bind->commit();
        } catch (Exception $e) {
            Logger::log("register_error", null, array("source" => $source, "errno" => $e->getCode(), "errmsg" => $e->getMessage()));
            $dao_user->rollback();

            throw $e;
        }

        if($source == 'mobile') {
            $dAOWithdrawMobile = new DAOWithdrawMobile();
            $dAOWithdrawMobile->add($uid, $mobile);
        }

        $this->addLoginLog($uid);

        $userinfo = $this->getUserInfo($uid);

        include_once 'process_client/ProcessClient.php';
        $sync_data = $userinfo;
        $sync_data['addtime'] = $addtime;
        $sync_data['modtime'] = $modtime;
        ProcessClient::getInstance("dream")->addTask("passport_sync_data", $sync_data);
        
        $option = array('nickname' => $nickname,'uid' => $uid, "signature" => $signature);
        ProcessClient::getInstance("dream")->addTask("filter_nickname_worker", $option);
        
        $userinfo["token"] = Session::getToken($uid);
        //channel
        $userinfo['channel'] =  (string) $this->getUserChannel($uid);
        //通过答题注册用户送10元现金
        ProcessClient::getInstance("dream")->addTask("login_bonus_worker", array('uid'=>$uid,'deviceid'=>Context::get("deviceid"),'channel'=>$userinfo['channel']));

        include_once 'libs/antispam_client/AntiSpamClient.php';
        $anti_spam = new AntiSpamClient();
        $anti_spam->isDirty('nickname', $nickname, 0, $uid, 0);
        
        //用户召回 拉新20180303
        $recall['uid']      = $uid;
        $recall['rid']      = $mobile;
        $recall['deviceid'] = Context::get("deviceid");
        ProcessClient::getInstance("dream")->addTask("passport_sync_user_recall", $recall);

        return $userinfo;
    }
    

    public function active($rid, $source, $access_token, $token_secret, $channel = '',$code='',$ext='')
    {
        Interceptor::ensureNotFalse(in_array($source, array("sina", "qq", "wx", "facebook", "twitter")), ERROR_PARAM_INVALID_FORMAT, "source:$source");
        $appName=Context::get('app_name');
        $t=null;
        if ($appName!='miniapp') {
            try{
                $oauth_user_info = OAuth::getUserInfo($source, $access_token, $rid, $token_secret, $channel);
            } catch (OAuthException $e) {
                Logger::log("oauth", null, array( "rid" => $rid, "source" => $source, "access token" => $access_token, "errno" => $e->getCode(), "errmsg" => $e->getMessage()));
            }
        }else{
            //$t=WxMiniProgram::getOpenId($code);
            $oauth_user_info=array();
            $t=array();
            //$oauth_user_info['rid']=$t['unionid']?$t['unionid']:$t['openid'];
            //$ext=json_decode($ext,true);
            //Interceptor::ensureNotFalse($ext,ERROR_CUSTOM,"" );
            //Util::log("kkk1",array($ext),'ddd6');
            if ($ext) {
                $miData=$ext['encryptedData'];
                $iv=$ext['iv'];
                $sessionKey=$ext['session_key'];
                $openid=$ext['openid'];
                $t['session_key']=$sessionKey;
                $t['openid']=$openid;

                if ($miData&&$iv) {
                    $s=WxMiniProgram::decryptData($miData, $iv, $sessionKey);
                    Interceptor::ensureNotNull($s, ERROR_CUSTOM, 'decrypt err');
                    //Util::log("kkk2",array($s),'ddd6');
                    if ($s) {
                        $oauth_user_info['unionid']=isset($s['unionId'])?$s['unionId']:"";
                        $oauth_user_info['rid']=$oauth_user_info['unionid'];
                        //$oauth_user_info['rid']=$oauth_user_info['unionid']?$oauth_user_info['unionid']:$openid;
                        $t['unionid2']=$oauth_user_info['unionid'];
                    }
                }

                $oauth_user_info['nickname']=isset($ext['userInfo']['nickName'])?$ext['userInfo']['nickName']:"";
                $oauth_user_info['avatar']= isset($ext['userInfo']['avatar']) ?$ext['userInfo']['avatar'] : "";
                $oauth_user_info['signature']= isset($ext['userInfo']['signature']) ?$ext['userInfo']['signature'] : "";
                $oauth_user_info['gender']= isset($ext['userInfo']['gender']) ?$ext['userInfo']['gender'] : "N";

            }else{
                $oauth_user_info['nickname']='wx'.rand(10000000, 99999999);
            }

        }
        Interceptor::ensureNotEmpty($oauth_user_info, ERROR_USER_NOT_LOGIN);

        $dao_userbind = new DAOUserBind();
        $bindinfo = $dao_userbind->getUserbindBySource($oauth_user_info["rid"], $source);

        if(!empty($bindinfo)) {
            $uid = $bindinfo["uid"];
            $userinfo = $this->getUserInfo($uid);

            $forbidden = new Forbidden();
            if($forbidden->isForbidden($uid)) {
                $dao_forbidden = new DAOForbidden();
                $forbidden_info = $dao_forbidden->getForbidden($uid);
                $reason = $forbidden_info["reason"];
                $expire = date("Y年m月d日", $forbidden_info["expire"]);
            
                Interceptor::ensureNotFalse(false, ERROR_USER_ERR_BLACK, [$userinfo['nickname'], $uid, $expire]);
            }
            $userinfo["isnew"] = false;
        } else {
            $rid       = $oauth_user_info["rid"];
            $nickname  = $oauth_user_info["nickname"] ? $oauth_user_info["nickname"] : "";
            $avatar    = $oauth_user_info["avatar"] ? $oauth_user_info["avatar"] : "";
            $signature = $oauth_user_info["signature"] ? $oauth_user_info["signature"] : "";
            $gender    = $oauth_user_info["gender"] ? $oauth_user_info["gender"] : "N";
            $location  = ""; //不同步第三方位置信息
            $birth     = $oauth_user_info["birth"] ? $oauth_user_info["birth"] : "0000-00-00";
            $extend    = $oauth_user_info["extend"] ? $oauth_user_info["extend"] : array();
            $unionid   = $oauth_user_info["unionid"] ? $oauth_user_info["unionid"] : "";

            $dao_user = new DAOUser();
            while ($dao_user->exists($nickname)) {
                $nickname = $oauth_user_info["nickname"] . substr(md5($unionid . $source . microtime(true)), 0, 5);
            }

            $user_persist = new UserPersist();
            $uid = $user_persist->getUserId();
            $addtime = date("Y-m-d H:i:s");
            $modtime = date("Y-m-d H:i:s");

            try {
                $dao_user = new DAOUser();
                $dao_user->startTrans();

                $dao_user->addUser($uid, $nickname, $avatar, $signature, $gender, $location, $birth, Context::get('region'), $addtime, $modtime);

                $dao_user_bind = new DAOUserBind();
                $dao_user_bind->addUserbind($uid, $rid, $source, $nickname, $avatar, $access_token);

                $dao_user_bind->commit();
            } catch (Exception $e) {
                Logger::log("active_error", null, array("source" => $source, "access token" => $access_token, "rid" => $rid, "errno" => $e->getCode(), "errmsg" => $e->getMessage()));
                $dao_user->rollback();

                throw $e;
            }

            $userinfo = $this->getUserInfo($uid);

            include_once 'process_client/ProcessClient.php';
            $sync_data = $userinfo;
            $sync_data['addtime'] = $addtime;
            $sync_data['modtime'] = $modtime;
            ProcessClient::getInstance("dream")->addTask("passport_sync_data", $sync_data);
            ProcessClient::getInstance("dream")->addTask('passport_save_avatar', array("uid" => $uid));
            if($source == 'sina') {
                $syncSina['uid']           = $uid;
                $syncSina['rid']           = $rid;
                $syncSina['nickname']      = $oauth_user_info["nickname"];
                $syncSina['followers']     = $extend['weibo_info']['followers_count'];
                $syncSina['verified']      = $extend['weibo_info']['verified'];
                $syncSina['verified_type'] = $extend['weibo_info']['verified_type'];
                ProcessClient::getInstance("dream")->addTask("passport_sync_user_weibo", $syncSina);
            }
            
            $option = array('nickname' => $nickname,'uid' => $uid, "signature" => $signature);
            ProcessClient::getInstance("dream")->addTask("filter_nickname_worker", $option);
            
            include_once 'libs/antispam_client/AntiSpamClient.php';
            $anti_spam = new AntiSpamClient();
            $anti_spam->isDirty('nickname', $nickname, 0, $userinfo['uid'], 0);
            
            $userinfo["isnew"] = true;
        }

        $this->addLoginLog($uid);

        $userinfo["token"] = Session::getToken($uid);
        //channel
        $userinfo['channel'] =  (string) $this->getUserChannel($uid);

        /*//用户召回 拉新20180303
        if($source == 'wx'){
            $recall['uid']      = $uid;
            $recall['rid']      = 0;
            $recall['deviceid'] = Context::get("deviceid");
            ProcessClient::getInstance("dream")->addTask("passport_sync_user_recall", $recall);
        }*/
        if ($appName=='miniapp') {
            $userinfo['miniapp']=$t;
        }

        return $userinfo;
    }

    public function addLoginLog($uid)
    {
        $dao_login_log = new DAOLoginLog();

        $extend = json_encode(
            array(
            "method"=>Context::get("method"),
            "source"=>trim(strip_tags($_REQUEST["source"]))
            )
        );
        //发送背包礼物
        include_once 'process_client/ProcessClient.php';
        ProcessClient::getInstance("dream")->addTask("login_gift_log_worker", $uid);

        ProcessClient::getInstance("dream")->addTask("passport_user_login_position", array('uid'=>$uid, 'ip'=>Util::getIP(), 'platform'=>Context::get("platform"), 'lng'=>Context::get("lng"), 'lat'=>Context::get("lat")));

        return $dao_login_log->addLoginLog($uid, Util::getIP(), Context::get("deviceid"), Context::get("platform"), Context::get("version"), Context::get("brand"), Context::get("model"), Context::get("network"), Context::get("netspeed"), $extend);
    }

    public function setPassword($uid, $password)
    {
        $salt = rand(100000, 999999);
        $password = md5($password.$salt);

        $dao_user = new DAOUser();
        return $dao_user->setPassword($uid, $password, $salt);
    }

    public function resetPassword($username, $password)
    {
        if(Util::isMobile($username)) {
            $dao_user_bind = new DAOUserBind();
            $bindinfo = $dao_user_bind->getUserBindBySource($username, "mobile");
        } else if(filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $dao_user_bind = new DAOUserBind();
            $bindinfo = $dao_user_bind->getUserBindBySource($username, "email");
        }

        Interceptor::ensureNotEmpty($bindinfo, ERROR_USER_NOTBINDED, $username);

        $uid = $bindinfo["uid"];

        $this->setPassword($uid, $password);

        $token = Session::getToken($uid);
        return $token;
    }

    public function modPassword($uid, $newPass, $oldPass)
    {
        $dao_user = new DAOUser();
        $loginInfo = $dao_user->getUserInfo($uid);

        if(md5($oldPass.$loginInfo["salt"]) != $loginInfo["password"]) {
            Interceptor::ensureNotFalse(false, ERROR_USER_PASSWORD_WRONG);
        }

        return $this->setPassword($uid, $newPass);
    }

    public static function reload($uid)
    {
        $super_popular_users = Context::getConfig("SUPER_POPULAR_USERS");
        if(in_array($uid, $super_popular_users)) {
            LocalCache::delete("user_cache:$uid");
        }

        $cache = Cache::getInstance("REDIS_CONF_USER", $uid);
        $cache->delete("USER_CACHE_$uid");

        self::getUserInfo($uid);

        return true;
    }

    public static function getUserInfo($uid)
    {
        /*$super_popular_users = Context::getConfig("SUPER_POPULAR_USERS");

        if(in_array($uid, $super_popular_users)) {
            $userinfo = LocalCache::get("user_cache:$uid");

            if($userinfo) {
                $userinfo["L1_cached"] = true;

                return $userinfo;
            }
        }*/

        $cache = Cache::getInstance("REDIS_CONF_USER", $uid);
        $key = "USER_CACHE_$uid";
        if (!($userinfo = $cache->get($key))) {
            $dao_user = new DAOUser();
            $userinfo = $dao_user->getUserInfo($uid);

            if ($userinfo) {
                if("Y" == $userinfo["verified"]) {
                    $dao_verifield = new DAOVerified();
                    $userinfo["verifiedinfo"] = $dao_verifield->getVerifiedInfo($uid);
                }

                $userinfo["exp"] = UserExp::getUserExp($uid);
                $userinfo["medal"] = UserMedal::getUserMedals($uid);
                $userinfo["L2_cached_time"] = $_SERVER["REQUEST_TIME"];

                $founder = false;
                foreach ($userinfo['medal'] AS $medal_value) {
                    if ($medal_value['kind'] == 'founder') {
                        $founder = true;
                    }
                }
                $config = new Config();
                $default_avatar = $config->getConfig(Context::get("region")?Context::get("region"):"china", "default_avatar", "server", '1.0.0.0');

                $dao_verifiedStudent = new DAOVerifiedStudent();
                $school = $dao_verifiedStudent->getVerifiedSchoolByUid($uid);
                $dao_verifiedArtist = new DAOVerifiedArtist();
                $artist = $dao_verifiedArtist->getVerifiedReasonByUid($uid);

                $userinfo = array(
                    "uid"        => (int)$userinfo["uid"],
                    "vid"    => (int)UserPersist::getUidByRealUid($uid),
                    "nickname"   => (string)$userinfo["nickname"],
                    "avatar"     => (string)$userinfo["avatar"]?$userinfo["avatar"] : $default_avatar['value'],
                    "signature"  => (string)$userinfo["signature"],
                    "founder"     => $founder,//创始主播
                    "gender"     => (string)$userinfo["gender"] == "F"?"F":"M",//20180103 默认男
                    "birth"      => (string)$userinfo["birth"],
                    "location"   => (string)$userinfo["location"],
                    "region"      => (string)$userinfo["region"],
                    "verified"     => $userinfo["verified"] == "Y",
                    "verifiedinfo"   => array(
                        "credentials" => (string)$userinfo["verifiedinfo"]["credentials"],
                        "type"        => (int)$userinfo["verifiedinfo"]["type"],
                        "realname"    => (string)$userinfo["verifiedinfo"]["realname"],
                    ),
                    "exp"        => (int)$userinfo["exp"],
                    "level"      => (int)UserExp::getLevelByExp($userinfo["exp"]),
                    "medal"      => (array)$userinfo["medal"],
                    "vs_school" => (string)$school,
                    "vip" => (int)Vip::getUserVipLevel($uid),
                    "va_reason" => (string)$artist,
                    "addtime" => (int)strtotime($userinfo["addtime"]),
                );

                $cache->set($key, json_encode($userinfo));
            }
        } else {
            $userinfo = json_decode($userinfo, true);
            $userinfo["L2_cached"] = true;
        }

        /*if(in_array($uid, $super_popular_users)) {
            $userinfo["L1_cached_time"] = $_SERVER["REQUEST_TIME"];
            LocalCache::set("user_cache:$uid", $userinfo, 60);
        }*/
        if($userinfo['avatar']) {
            $userinfo['avatar'] = str_replace("dreamlive.com", "tongyigg.com", $userinfo['avatar']);
            if(stripos($userinfo['avatar'], 'http') !== 0) {
                $userinfo['avatar'] = Context::getConfig("STATIC_DOMAIN") .$userinfo['avatar'];
            }
        }
        $userinfo["gender"] = $userinfo["gender"] == "F"?"F":"M";//20180103 默认男

        Bag::updateUserInfo($userinfo);
        return $userinfo;
    }

    public function bind($rid, $source, $access_token)
    {
        if($source == "mobile") {
            Interceptor::ensureNotFalse(Captcha::verify($rid, $access_token, "bind"), ERROR_CODE_INVALID, $rid . "($access_token)");
        } else {
            try{
                $oauth_user_info = OAuth::getUserInfo($source, $access_token, $rid);
            } catch (OAuthException $e) {
                Logger::log("oauth", null, array("source" => $source, "access token" => $access_token, "rid" => $rid, "errno" => $e->getCode(), "errmsg" => $e->getMessage()));
            }

            Interceptor::ensureNotEmpty($oauth_user_info, ERROR_USER_OAUTH_FAILD);
        }

        $uid = Context::get("userid");

        $dao_user_bind = new DAOUserBind();
        $bindinfo = $dao_user_bind->getUserBind($uid, $source);
        Interceptor::ensureEmpty($bindinfo, BIZ_PASSPORT_ERROR_BINDED, $source);

        $bindinfo = $dao_user_bind->getUserBindBySource($source == "wx" ? $oauth_user_info["unionid"] : $rid, $source);

        if($source == 'mobile') {
            $sourceName = '手机';
        }elseif($source == 'wx') {
            $sourceName = '微信';
        }else{
            $sourceName = $source;
        }

        Interceptor::ensureEmpty($bindinfo, BIZ_PASSPORT_ERROR_BINDED_OTHER, $sourceName);

        $rid = $source == "wx" ? $oauth_user_info["unionid"] : $rid;
        $nickname = (string)$oauth_user_info["nickname"];
        $avatar = (string)$oauth_user_info["avatar"];

        if($source == "mobile") {
            $dAOWithdrawMobile = new DAOWithdrawMobile();
            if(!$dAOWithdrawMobile->exists($uid)) {
                $dAOWithdrawMobile->add($uid, $rid);
            }
        }

        return $dao_user_bind->addUserBind($uid, $rid, $source, $nickname, $avatar, $access_token);
    }

    public function unbind($uid, $rid, $source)
    {
        $dao_user_bind = new DAOUserBind();
        $bindinfo = $dao_user_bind->getUserBind($uid, $source);
        Interceptor::ensureNotEmpty($bindinfo, ERROR_USER_NOTBINDED, $rid);

        $dao_user = new DAOUser();
        $userinfo = $dao_user->getUserInfo($uid);

        $binds = $dao_user_bind->getUserBinds($uid);
        Interceptor::ensureNotFalse((count($binds) > 1 || $userinfo["password"] != ""), ERROR_USER_BIND_LIMIT, $uid);

        return $dao_user_bind->delete($uid, $source);
    }

    public function setUserInfo($uid, $nickname, $signature, $avatar, $gender = "", $location = "", $birth = "")
    {
        if($avatar) {
            $temp = parse_url($avatar);
            $avatar = $temp['path'];
        }
        $dao_user = new DAOUser();
        $dao_user->setUserInfo($uid, $nickname, $signature, $avatar, $gender, $location, $birth);

        $this->reload($uid);

        $userinfo = $this->getUserInfo($uid);
        include_once 'process_client/ProcessClient.php';
        $sync_data = $userinfo;
        ProcessClient::getInstance("dream")->addTask("passport_sync_data", $sync_data);
        
        $option = array('nickname' => $nickname,'uid' => $uid, "signature" => $signature);
        ProcessClient::getInstance("dream")->addTask("filter_nickname_worker", $option);
        
        include_once 'libs/antispam_client/AntiSpamClient.php';
        $anti_spam = new AntiSpamClient();
        $anti_spam->isDirty('nickname', $nickname, 0, $uid, 0);
    }

    public function isActive($uid)
    {
        $key = "dream_active_users_".date("Ymd");
        $cache = Cache::getInstance("REDIS_CONF_USER");

        return $cache->sIsMember($key, $uid);
    }

    public function getUserListByNickname($nickname)
    {
        $user_list = array();

        $dao_user = new DAOUser();
        $uid_list = $dao_user->getUidListByNickname($nickname);

        if(count($uid_list)) {
            foreach($uid_list as $v){
                $userinfo = $this->getUserInfo($v['uid']);

                array_push(
                    $user_list, array(
                    "uid"      => $userinfo['uid'],
                    "vid"      => $userinfo['vid'],
                    "nickname" => $userinfo['nickname'],
                    "avatar"   => $userinfo['avatar'],
                    "gender"   => $userinfo['gender'],
                    "level"    => $userinfo['level'],
                    "medal"    => $userinfo['medal'],
                    )
                );
            }
        }

        return $user_list;
    }

    public function activeInternal($rid, $source, $nickname, $avatar ,$gender)
    {
        Interceptor::ensureNotFalse(in_array($source, array("sina", "qq", "wx", "facebook", "twitter")), ERROR_PARAM_INVALID_FORMAT, "source:$source");

        $dao_userbind = new DAOUserBind();
        $bindinfo = $dao_userbind->getUserbindBySource($rid, $source);

        if(!empty($bindinfo)) {
            $uid = $bindinfo["uid"];

            $forbidden = new Forbidden();
            if($forbidden->isForbidden($uid)) {
                $dao_forbidden = new DAOForbidden();
                $forbidden_info = $dao_forbidden->getForbidden($uid);
                $reason = $forbidden_info["reason"];
                $expire = date("Y年m月d日", $forbidden_info["expire"]);

                Interceptor::ensureFalse(false, ERROR_USER_ERR_BLACK, $reason);
            }
        } else {
            $user_persist = new UserPersist();
            $uid = $user_persist->getUserId();
            $addtime = date("Y-m-d H:i:s");
            $modtime = date("Y-m-d H:i:s");

            try {
                $dao_user = new DAOUser();
                $dao_user->startTrans();

                $dao_user->addUser($uid, $nickname, $avatar, '', $gender, '', '', Context::get('region'), $addtime, $modtime);

                $dao_user_bind = new DAOUserBind();
                $dao_user_bind->addUserbind($uid, $rid, $source, $nickname, $avatar, '');

                $dao_user_bind->commit();
            } catch (Exception $e) {
                Logger::log("activeInternal_error", null, array("source" => "mobile", "errno" => $e->getCode(), "errmsg" => $e->getMessage()));
                $dao_user->rollback();

                throw $e;
            }

            $userinfo = $this->getUserInfo($uid);
            include_once 'process_client/ProcessClient.php';
            $sync_data = $userinfo;
            $sync_data['addtime'] = $addtime;
            $sync_data['modtime'] = $modtime;
            ProcessClient::getInstance("dream")->addTask("passport_sync_data", $sync_data);
            ProcessClient::getInstance("dream")->addTask('passport_save_avatar', array("uid" => $uid));
        }

        $this->addLoginLog($uid);

        $userinfo = $this->getUserInfo($uid);
        $userinfo["token"] = Session::getToken($uid);

        return $userinfo;
    }

    public function setVerified($uid, $verified)
    {
        $dao_user = new DAOUser();
        $dao_user->setVerified($uid, $verified);

        $this->reload($uid);
    }

    public function getOldUser($uid, $rid, $access_token)
    {
        $dao_user_merge = new DAOUserMergeOld();
        $old = $dao_user_merge->getOldUserByUid($uid);

        if(empty($old)) {
            $mergeid = $dao_user_merge->addUserMerge($uid);
        }else{
            $mergeid = $old['id'];
        }

        $oauth_user_info = OAuth::getUserInfo("wx", $access_token, $rid);

        if (empty($oauth_user_info)) {
            $dao_user_merge->setUserMergeError($uid, ERROR_USER_NOT_LOGIN, Context::get("platform").','.ERROR_USER_NOT_LOGIN.','.$rid);
            throw new BizException(ERROR_USER_NOT_LOGIN, array());
        }

        $dao_userbind = new DAOUserBind();
        $newinfo = $dao_userbind->getUserBind($uid,  "wx");
        $dao_userbind_old = new DAOUserBindOld();
        $bindinfo = $dao_userbind_old->getUserbindBySource($oauth_user_info["rid"], "wx");

        if (empty($newinfo) || empty($bindinfo) ||$uid == $bindinfo['uid']) {
            $dao_user_merge->setUserMergeError($uid, ERROR_BIZ_PASSPORT_OLD_USER_NOT_FOUND, Context::get("platform").','.ERROR_BIZ_PASSPORT_OLD_USER_NOT_FOUND.','.$rid);
            throw new BizException(ERROR_BIZ_PASSPORT_OLD_USER_NOT_FOUND, array());
        }

        $old_user = $dao_user_merge->getOldUserByOlduid($bindinfo['uid']);

        if($old_user) {
            Interceptor::ensureFalse($old_user['status'] == "SUCCESS", ERROR_BIZ_PASSPORT_OLD_USER_SUCESSED);
        }else{
            $dao_user_merge->setUserMergeAuzd($uid, $bindinfo['uid'], $newinfo['rid'], $bindinfo['rid']);
        }

        $account = new Account();
        $curinfo = $account->getAccountList($uid);
        $oldinfo = $account->getAccountList($bindinfo['uid']);

        $userinfo = self::getUserInfo($uid);

        return sprintf(
            "http://html5.dreamlive.tv/find_old_user1.php?cur_uid=%s&cur_nickname=%s&cur_photo=%s&cur_diamond=%s&cur_ticket=%s&old_uid=%s&old_nickname=%s&old_photo=%s&old_diamond=%s&old_ticket=%s&mergeid=%s",
            $userinfo['uid'],
            $userinfo["nickname"],
            $userinfo["avatar"],
            (string) $curinfo["diamond"],
            (string) $curinfo["ticket"],
            $bindinfo['uid'],
            $bindinfo["nickname"],
            $bindinfo["avatar"],
            (string) $oldinfo["diamond"],
            (string) $oldinfo["ticket"],
            (string) $mergeid
        );
    }
    public function mergeOldUser($uid, $mergeid)
    {
        $dao_user_merge = new DAOUserMergeOld();

        $old_user =  $dao_user_merge->getOldUser($uid, $mergeid);

        if (empty($old_user)) {
            throw new BizException(ERROR_BIZ_PASSPORT_OLD_USER_NOT_FOUND, array());
        }
        Interceptor::ensureFalse($old_user['status'] == "SUCCESS", ERROR_BIZ_PASSPORT_OLD_USER_SUCESSED);
        Interceptor::ensureFalse($old_user['status'] == "FAIL", ERROR_BIZ_PASSPORT_OLD_USER_NOT_FOUND);

        $old_uid = $old_user['olduid'];

        try {
            $dao_user = new DAOUser();
            $dao_userbind = new DAOUserBind();

            $dao_user->startTrans();

            $dao_userbind->delete($uid, 'wx');
            if(!$dao_userbind->setUserBind($old_uid, 'wx', $old_user['rid'])) {
                $dao_userbind_old = new DAOUserBindOld();
                $userbind_old_info = $dao_userbind_old->getUserbindBySource($old_user['oldrid'], "wx");

                $dao_userbind->addUserBind($old_uid, $old_user['rid'], 'wx', $userbind_old_info['nickname'], $userbind_old_info['avatar'], "");
            }

            $account = new Account();
            $accountinfo = $account->getAccountList($uid);

            $dao_user_merge->setUserMergeSucess($uid, $accountinfo["diamond"], $accountinfo["ticket"]);

            $token = Session::getToken($old_uid);

            $dao_user->commit();
        } catch (Exception $e) {
            $dao_user->rollback();

            $dao_user_merge->setUserMergeError($uid, ERROR_SYS_DB_SQL, Context::get("platform").','.ERROR_SYS_DB_SQL.','.$mergeid);
            $dao_user_merge->setUserMergeFail($uid);
            Logger::log("merge_olduser", $e->getMessage(), array("id"=>$mergeid,"uid"=>$uid,"olduid"=>$old_uid));

            throw $e;
        }

        Account::increase($old_uid, Account::TRADE_TYPE_ACCOUNT_MERGE, 0, $accountinfo["diamond"], Account::CURRENCY_DIAMOND, "账户合并 钻",  array("from"=>$uid,"to"=>$old_uid));
        Account::decrease($uid,     Account::TRADE_TYPE_ACCOUNT_MERGE, 0, $accountinfo["diamond"], Account::CURRENCY_DIAMOND, "账户合并 钻",  array("from"=>$uid,"to"=>$old_uid));

        Account::increase($old_uid, Account::TRADE_TYPE_ACCOUNT_MERGE, 0, $accountinfo["ticket"], Account::CURRENCY_TICKET, "账户合并 票",  array("from"=>$uid,"to"=>$old_uid));
        Account::decrease($uid,     Account::TRADE_TYPE_ACCOUNT_MERGE, 0, $accountinfo["ticket"], Account::CURRENCY_TICKET, "账户合并 票",  array("from"=>$uid,"to"=>$old_uid));

        include_once 'process_client/ProcessClient.php';
        ProcessClient::getInstance("dream")->addTask("passport_merge_user", array("newuid" => $uid, "olduid" => $old_uid));

        return $token ? $token : '';
    }

    public function getOldUserStatus($uid)
    {
        $dao_user_merge = new DAOUserMergeOld();

        $olduser = $dao_user_merge->getOldUserByOlduid($uid);
        if($olduser['status'] == "SUCCESS") {
            return array('status' => "SUCCESS");
        }

        $old = $dao_user_merge->getOldUserByUid($uid);

        if(empty($old)) {
            return array('status'=>"PREPARE");
        }else{
            if($old['status'] == "AUTHORIZED") {
                $userinfo = self::getUserInfo($uid);
                $bindinfo = self::getUserInfo($old['olduid']);

                $account = new Account();

                $curinfo = $account->getAccountList($uid);
                $oldinfo = $account->getAccountList($old['olduid']);

                $url = sprintf(
                    "http://html5.dreamlive.tv/find_old_user1.php?cur_uid=%s&cur_nickname=%s&cur_photo=%s&cur_diamond=%s&cur_ticket=%s&old_uid=%s&old_nickname=%s&old_photo=%s&old_diamond=%s&old_ticket=%s&mergeid=%s",
                    $userinfo['uid'],
                    $userinfo["nickname"],
                    $userinfo["avatar"],
                    (string) $curinfo["diamond"],
                    (string) $curinfo["ticket"],
                    $bindinfo['uid'],
                    $bindinfo["nickname"],
                    $bindinfo["avatar"],
                    (string) $oldinfo["diamond"],
                    (string) $oldinfo["ticket"],
                    (string) $old['id']
                );
                return array(
                    'status'=> $old['status'],
                    'url'=> $url
                );
            }elseif($old['status'] == "SUCCESS") {
                return array('status' => "SUCCESS");
            }else{
                if($old['errno']) {
                    if(Context::get("platform") == 'android') {
                        return array('status'=>"FAIL");
                    }else{
                        Interceptor::ensureNotFalse(false, $old['errno']);
                    }
                }
                return array('status'=>"PREPARE");
            }
        }
    }
    public function getUserChannel($uid)
    {
        $dao_user = new DAOUser();
        $loginInfo = $dao_user->getUserInfo($uid);

        if($loginInfo['channel'] === "" && Context::get("channel")) {
            $dao_user = new DAOUser();
            $dao_user->setUserChannel($uid, Context::get("channel"));

            return Context::get("channel");
        }

        return $loginInfo['channel'];
    }

    public function issetPwd($uid)
    {
        $dao_user = new DAOUser();
        $loginInfo = $dao_user->getUserInfo($uid);

        return empty($loginInfo['password']) ? false : true;
    }

    public function searchUserByNickname($nickname)
    {
        $user_list = array();

        $sphinx = new Sphinx();
        $spx_list = $sphinx->sphinx($nickname, 0, 200);

        if(count($spx_list)) {
            foreach($spx_list as $v){
                $userinfo = $this->getUserInfo($v);

                array_push(
                    $user_list, array(
                    "uid"      => $userinfo['uid'],
                    "vid"      => $userinfo['vid'],
                    "nickname" => $userinfo['nickname'],
                    "avatar"   => $userinfo['avatar'],
                    "gender"   => $userinfo['gender'],
                    "level"    => $userinfo['level'],
                    "medal"    => $userinfo['medal'],
                    )
                );
            }
        }

        return $user_list;
    }

    public function searchSuperUserByNicknameDb($nickname)
    {
        $user_list = array();
        $daosuper = new DAOSuperAnchors();
        $superlist = $daosuper->search($nickname);
        if($superlist) {
            foreach($superlist as $v){
                $userinfo = $this->getUserInfo($v['uid']);

                array_push(
                    $user_list, array(
                    "uid"      => $userinfo['uid'],
                    "vid"      => $userinfo['vid'],
                    "nickname" => $userinfo['nickname'],
                    "avatar"   => $userinfo['avatar'],
                    "gender"   => $userinfo['gender'],
                    "level"    => $userinfo['level'],
                    "medal"    => $userinfo['medal'],
                    )
                );
            }
        }

        return $user_list;
    }
    public static function getUserInfos($uids)
    {
        if (! $uids) {
            return array();
        }

        if (! is_array($uids)) {
            $uids = array(
                $uids
            );
        }

        $cache = Cache::getInstance("REDIS_CONF_USER", $uid);
        foreach ($uids as $uid) {
            $keys[] = "USER_CACHE_".$uid;
        }
        $results = $cache->mget($keys);

        foreach ($results as $row) {
            if ($row) {
                $userinfo = json_decode($row, true);
                $userinfo["L2_cached"] = true;
                if($userinfo['avatar']) {
                    $userinfo['avatar'] = str_replace("dreamlive.tv", "dreamlive.com", $userinfo['avatar']);
                    if(stripos($userinfo['avatar'], 'http') !== 0) {
                        $userinfo['avatar'] = Context::getConfig("STATIC_DOMAIN") .$userinfo['avatar'];
                    }
                }
                $userinfo["gender"] = $userinfo["gender"] == "F"?"F":"M";//20180103 默认男

                Bag::updateUserInfo($userinfo);

                $userinfos[$userinfo['uid']] = $userinfo;
            }
        }

        return $userinfos;
    }
}
?>
