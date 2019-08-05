<?php
class UserController extends BaseController
{
    public function registerAction()
    {
        $mobile = trim(strip_tags($this->getParam("mobile")));
        Interceptor::ensureNotEmpty($mobile, ERROR_PARAM_IS_EMPTY, "mobile");

        $this->_registerBySource($mobile, 'mobile');
    }

    public function registerEmailAction()
    {
        $email = trim(strip_tags($this->getParam("email")));
        Interceptor::ensureNotFalse(filter_var($email, FILTER_VALIDATE_EMAIL), ERROR_PARAM_IS_EMPTY, "email");

        $this->_registerBySource($email, 'email');
    }

    private function _registerBySource($username, $source)
    {
        $password = trim(strip_tags($this->getParam("password")));
        $code     = trim(strip_tags($this->getParam("code")));
        $nickname = trim(strip_tags($this->getParam("nickname")));
        $gender   = trim(strip_tags($this->getParam("gender", "N")));
        $birth    = trim(strip_tags($this->getParam("birth", "0000-00-00")));

        Interceptor::ensureNotEmpty($password, ERROR_PARAM_IS_EMPTY, "password");
        Interceptor::ensureNotEmpty($code,     ERROR_PARAM_IS_EMPTY, "code");

        if(empty($nickname) && $source == "mobile") {
            $nickname = "手机用户" . substr(md5($username . $source . microtime(true)), 0, 6);
            $dao_user = new DAOUser();
            while ($dao_user->exists($nickname)) {
                $nickname =  "手机用户" . substr(md5($username . $source . microtime(true)), 0, 6);
            }
        }else{
            Interceptor::ensureFalse(mb_strwidth($nickname, "utf8") > 16, ERROR_USER_NAME_TOOLONG, $nickname);
            Interceptor::ensureFalse(mb_strwidth($nickname, "utf8") < 6, ERROR_USER_NAME_SHORT, $nickname);
            Interceptor::ensureFalse(empty($nickname) || is_numeric($nickname) || preg_match("/(" . RULE_DIRTY_WORDS . "|" . RULE_PROTECT_WORDS . ")/i", $nickname), ERROR_USER_NAME_DIRTY, $nickname);

            $dao_user = new DAOUser();
            Interceptor::ensureFalse($dao_user->exists($nickname), ERROR_USER_NAME_EXISTS, $nickname);
        }
        
        Interceptor::ensureNotFalse(Captcha::verify($username, $code, "reg"), ERROR_CODE_INVALID, $username. "($code)");

        $dao_user_bind = new DAOUserBind();
        $bindinfo = $dao_user_bind->getUserbindBySource($username, $source);
        Interceptor::ensureEmpty($bindinfo,    ERROR_USER_REGED, $username);

        $user = new User();
        $userinfo = $user->register($username, $source, $password, $nickname, $gender, $birth);

        $this->render($userinfo);
    }

    public function loginAction()
    {
        $username = trim($this->getParam("username"));
        $password = trim($this->getParam("password"));
        $captcha  = trim($this->getParam("captcha"));

        $deviceid     = trim(strip_tags($this->getParam("deviceid")));
        
        $anti_spam_forbidden = new AntiSpamForbidden();
        //检查是否设置封禁设备号
        if (!empty($deviceid) && $anti_spam_forbidden->checkForbiddenDevice($deviceid)) {
            Interceptor::ensureNotEmpty('',             ERROR_PARAM_IS_EMPTY, "deviceid被封禁");
        }
        
        
        Interceptor::ensureNotEmpty($username,  ERROR_PARAM_IS_EMPTY, "username");
        Interceptor::ensureNotEmpty($password,  ERROR_PARAM_IS_EMPTY, "password");

        $user = new User();
        $userinfo = $user->login($username, $password, $captcha);

        $option = array(
            'region' => Context::get('region'),
            'uid'    => $userinfo['uid'],
            'ip'     => Util::getIP(),
            'lng'    => $this->getParam("lng"),
            'lat'    => $this->getParam("lat")
        );
        include_once 'process_client/ProcessClient.php';
        ProcessClient::getInstance("dream")->addTask("user_login_speed", $option);

        //注释newsfeeds by yangqing
        //require_once "process_client/ProcessClient.php";
        //worker 拉取我关注人的feeds，插入我的newsfeed表
        //$bool = ProcessClient::getInstance("dream")->addTask("followings_increase_newsfeeds", array("uid" => $userinfo['uid'],"type"=>'login'));

        $this->render($userinfo);
    }


    public function miniAppActiveAction()
    {
        $code          = trim($this->getParam("code"));
        $ext           = $this->getParam("ext");
        $openid        = $this->getParam("openid");
        $session_key   = $this->getParam("session_key");

        Interceptor::ensureNotEmpty($code, ERROR_PARAM_IS_EMPTY, 'code');
        Interceptor::ensureNotEmpty($openid, ERROR_PARAM_IS_EMPTY, 'openid');
        Interceptor::ensureNotEmpty($session_key, ERROR_PARAM_IS_EMPTY, 'session_key');
        $ext=json_decode($ext, true);
        Interceptor::ensureNotFalse($ext, ERROR_CUSTOM, "");
        $ext['openid']=$openid;
        $ext['session_key']=$session_key;

        $user      = new User();
        $userinfo = $user->active('', 'wx', '', '', '', $code, $ext);
        $this->render($userinfo);
    }

    public function miniAppSessionAction()
    {
        $code          = trim($this->getParam("code"));
        Interceptor::ensureNotEmpty($code, ERROR_PARAM_IS_EMPTY, 'code');
        $t=WxMiniProgram::getOpenId($code);
        $t['code']=$code;
        $this->render($t);
    }

    /**
     * 小程序用户订阅开播通知
     */
    public function miniAppSubNoticeAction()
    {
        $openid = $this->getParam("openid");
        $formid = $this->getParam("formid");
        $uid = Context::get("userid");

        Interceptor::ensureNotEmpty($openid, ERROR_PARAM_IS_EMPTY, 'openid');
        Interceptor::ensureNotEmpty($formid, ERROR_PARAM_IS_EMPTY, 'formid');

        $wxNotice = new WxNotice();
        $userinfo = $wxNotice->subNotice($uid, $openid, $formid);
        $this->render($userinfo);
    }

    public function activeAction()
    {
        $rid          = trim($this->getParam("rid"));
        $source       = trim($this->getParam("source"));
        $access_token = trim($this->getParam("access_token"));
        $token_secret = trim($this->getParam("token_secret"));
        $isweb        = trim($this->getParam("isweb", ''));
        $deviceid     = trim(strip_tags($this->getParam("deviceid")));
        
        $anti_spam_forbidden = new AntiSpamForbidden();
        //检查是否设置封禁设备号
        if (!empty($deviceid) && $anti_spam_forbidden->checkForbiddenDevice($deviceid)) {
            Interceptor::ensureNotEmpty('',             ERROR_PARAM_IS_EMPTY, "deviceid被封禁");
        }
        
        
        
        Interceptor::ensureNotEmpty($rid,             ERROR_PARAM_IS_EMPTY, "rid");
        Interceptor::ensureNotEmpty($source,          ERROR_PARAM_IS_EMPTY, "source");
        Interceptor::ensureNotEmpty($access_token,    ERROR_PARAM_IS_EMPTY, "access_token");

        $user     = new User();
        $userinfo = $user->active($rid, $source, $access_token, $token_secret, $isweb);

        $this->render($userinfo);
    }

    public function fastLoginAction()
    {
        $uid = Context::get("userid");

        $user = new User();
        $userinfo = $user->getUserInfo($uid);
        Interceptor::ensureNotEmpty($userinfo, ERROR_LOGINUSER_NOT_EXIST);

        $userinfo["token"] = Session::getToken($uid);
        $userinfo['channel'] = $user->getUserChannel($uid);

        $deviceid     = trim(strip_tags($this->getParam("deviceid")));
        
        $anti_spam_forbidden = new AntiSpamForbidden();
        //检查是否设置封禁设备号
        if (!empty($deviceid) && $anti_spam_forbidden->checkForbiddenDevice($deviceid)) {
            Interceptor::ensureNotEmpty('',             ERROR_PARAM_IS_EMPTY, "deviceid被封禁");
        }
        
        
        $user->addLoginLog($uid);

        $option = array(
            'region' => Context::get('region'),
            'uid'    => $userinfo['uid'],
            'ip'     => Util::getIP(),
            'lng'    => $this->getParam("lng"),
            'lat'    => $this->getParam("lat")
        );
        include_once 'process_client/ProcessClient.php';
        ProcessClient::getInstance("dream")->addTask("user_login_speed", $option);

        //注释newsfeeds by yangqing
        //require_once "process_client/ProcessClient.php";
        // worker 拉取我关注人的feeds，插入我的newsfeed表
        //$bool = ProcessClient::getInstance("dream")->addTask("followings_increase_newsfeeds", array("uid" => $userinfo['uid'],"type"=>'login'));

        $this->render($userinfo);
    }

    public function resetPasswordAction()
    {
        $username = trim($this->getParam("username"));
        $password = trim($this->getParam("password"));
        $code     = trim($this->getParam("code"));

        Interceptor::ensureNotEmpty($username,     ERROR_PARAM_IS_EMPTY, "username");
        Interceptor::ensureNotEmpty($password,     ERROR_PARAM_IS_EMPTY, "password");
        Interceptor::ensureNotEmpty($code,         ERROR_PARAM_IS_EMPTY, "code");

        Interceptor::ensureNotFalse(Captcha::verify($username, $code, "forgot"), ERROR_CODE_INVALID, $username. "($code)");

        $user = new User();
        $token = $user->resetPassword($username, $password);

        $this->render(array("token"=>$token));
    }

    public function setPasswordAction()
    {
        $password = trim($this->getParam("password"));

        Interceptor::ensureNotEmpty($password,  ERROR_PARAM_IS_EMPTY, "password");

        $user = new User();
        $user->setPassword(Context::get("userid"), $password);

        $this->render();
    }

    public function bindAction()
    {
        $rid         = trim($this->getParam("rid"));
        $source       = trim($this->getParam("source"));
        $access_token = trim($this->getParam("access_token"));

        Interceptor::ensureNotEmpty($rid,             ERROR_PARAM_IS_EMPTY, "rid");
        Interceptor::ensureNotEmpty($source,          ERROR_PARAM_IS_EMPTY, "source");
        Interceptor::ensureNotEmpty($access_token,    ERROR_PARAM_IS_EMPTY, "access_token");

        $user = new User();
        $user->bind($rid, $source, $access_token);

        $this->render();
    }

    public function unbindAction()
    {
        $rid    = trim($this->getParam("rid"));
        $source = trim($this->getParam("source"));

        Interceptor::ensureNotEmpty($rid,     ERROR_PARAM_IS_EMPTY, "rid");
        Interceptor::ensureNotEmpty($source,  ERROR_PARAM_IS_EMPTY, "source");

        $user = new User();
        $user->unbind(Context::get("userid"), $rid, $source);

        $this->render();
    }

    public function getBindsAction()
    {
        $dao_userbind = new DAOUserBind();
        $bind_list = $dao_userbind->getUserbinds(Context::get("userid"));

        $binds = array();
        foreach($bind_list as $k => $v) {
            $binds[] = array(
               "source" => $v["source"],
               "rid" => (string)$v["rid"],
               "name" => (string)$v["nickname"],
            );
        }

        $this->render(array("binds" => $binds));
    }

    public function changeMobileAction()
    {
        /*{{{更换绑定手机号*/
        $mobile   = trim($this->getParam("mobile"));
        $code     = trim($this->getParam("code"));

        Interceptor::ensureNotEmpty($mobile,     ERROR_PARAM_IS_EMPTY, "mobile");
        Interceptor::ensureNotEmpty($code,       ERROR_PARAM_IS_EMPTY, "code");

        $dao_user_bind = new DAOUserBind();
        $bindinfo = $dao_user_bind->getUserBindBySource($mobile, "mobile");
        Interceptor::ensureEmpty($bindinfo,    ERROR_USER_REGED, $mobile);

        $uid = Context::get("userid");
        $bindinfo = $dao_user_bind->getUserBind($uid, "mobile");
        Interceptor::ensureNotEmpty($bindinfo,    ERROR_USER_NOTBINDED, $uid);

        $dao_user_bind->setUserBind($uid, "mobile", $mobile);

        $this->render();
    }/*}}}*/

    public function checkNicknameAction()
    {
        $nickname = trim($this->getParam("nickname"));
        Interceptor::ensureNotEmpty($nickname, ERROR_PARAM_IS_EMPTY, "nickname");

        $uid = Context::get("userid");
        $userinfo = User::getUserInfo($uid);
        if($userinfo["nickname"] != $nickname) {
            Interceptor::ensureFalse(mb_strwidth($nickname, "utf8") > 16, ERROR_USER_NAME_TOOLONG, $nickname);
            Interceptor::ensureFalse(mb_strwidth($nickname, "utf8") < 6, ERROR_USER_NAME_SHORT, $nickname);
            Interceptor::ensureFalse(empty($nickname) || is_numeric($nickname) || preg_match("/(" . RULE_DIRTY_WORDS . "|" . RULE_PROTECT_WORDS . ")/i", $nickname), ERROR_USER_NAME_DIRTY, $nickname);

            $dao_user = new DAOUser();
            Interceptor::ensureFalse($dao_user->exists($nickname), ERROR_USER_NAME_EXISTS, $nickname);
        }

        $this->render();
    }

    public function getCodeAction()
    {
        $mobile   = trim($this->getParam("mobile"));
        $type     = trim($this->getParam("type", "login"));

        Interceptor::ensureNotEmpty($mobile, ERROR_PARAM_IS_EMPTY, "mobile");
        Interceptor::ensureNotFalse(Captcha::checkIntervalTime($mobile, $type), ERROR_CODE_INTERVAL_TIME, $mobile);
        Interceptor::ensureNotFalse(Captcha::checkSendTimes($mobile, $type), ERROR_CODE_OVERTIMES, $mobile);

        $id = Captcha::send($mobile, $type);

        $this->render(array("id" => $id));
    }

    public function getEmailCodeAction()
    {
        $email    = trim($this->getParam("email"));
        $type     = trim($this->getParam("type", "reg"));
  
        Interceptor::ensureNotFalse(filter_var($email, FILTER_VALIDATE_EMAIL), ERROR_PARAM_IS_EMPTY, "email");
        Interceptor::ensureNotFalse(Captcha::checkIntervalTime($email, $type), ERROR_CODE_INTERVAL_TIME, $email);
        Interceptor::ensureNotFalse(Captcha::checkSendTimes($email, $type), ERROR_CODE_OVERTIMES, $email);

        $id = Captcha::sendEmail($email, $type);

        $this->render(array("id" => $id));
    }
    //获取用户信息
    public function getGameUserInfoAction()
    {
        $uid = Context::get("userid");
        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid($uid)");

        $user = new User();
        $userinfo = $user->getUserInfo($uid);
        Interceptor::ensureNotFalse($userinfo && !Forbidden::isForbidden($uid), ERROR_USER_NOT_EXIST);

        //获取用户星钻余额
        $balance        = Account::getBalance($uid, 2);
        $userinfos['balance']        = (int)$balance;
        $userinfos['uid']            = $userinfo['uid'];
        $userinfos['nickname']       = $userinfo['nickname'];
        $userinfos['avatar']         = $userinfo['avatar'];
        $userinfos['gender']         = $userinfo['gender'];

        $this->render($userinfos);
    }
    public function getNewUserInfoAction()
    {
        $uid        = $this->getParam("uid",  0)?$this->getParam("uid",  0):Context::get("userid");
        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid($uid)");

        $user       = new User();
        $live       = new Live();
        $liveid     = (int)$live->isUserLive($uid);
        $cache      = Cache::getInstance("REDIS_CONF_CACHE");
        $userInfo   = $user->getUserInfo($uid);
        $blance     = Account::getBalance($uid, 2);
        $info       = array(
            'blance'    => (int)$blance,
            'vip'       => (int)$userInfo['vip'],
            "praise"    => (int)Counter::get(Counter::COUNTER_TYPE_LIVE_PRAISES, $liveid),
            'gift'      => (int)Counter::get(Counter::COUNTER_TYPE_PAYMENT_RECEIVE_GIFT, $uid),
            'sharid'    => (int)Counter::get(Counter::COUNTER_TYPE_SHARE_USER, $uid),
        );
        $this -> render($info);
    }
    public function getUserInfoAction()
    {
        $loginid = Context::get("userid");
        $uid     = (int)$this->getParam("uid",  0);

        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid($uid)");

        $user = new User();
        $userinfo = $user->getUserInfo($uid);
        Interceptor::ensureNotFalse($userinfo && !Forbidden::isForbidden($uid), ERROR_USER_NOT_EXIST);

        $counters['live'] = Counter::mixed(
            array(
               Counter::COUNTER_TYPE_FOLLOWERS,
               Counter::COUNTER_TYPE_FOLLOWINGS,
               Counter::COUNTER_TYPE_LIVE_PRAISES
            ),
            array($uid)
        );
        $counters['payment'] = Counter::mixed(
            array(
               Counter::COUNTER_TYPE_PAYMENT_SEND_GIFT,
               Counter::COUNTER_TYPE_PAYMENT_RECEIVE_GIFT,
            ),
            array($uid)
        );


        $userinfo["followers"]       = (int) $counters['live'][$uid][Counter::COUNTER_TYPE_FOLLOWERS];
        $userinfo["followings"]      = (int) $counters['live'][$uid][Counter::COUNTER_TYPE_FOLLOWINGS];
        $userinfo["praises"]        = (int) $counters['live'][$uid][Counter::COUNTER_TYPE_LIVE_PRAISES];
        $userinfo["send_gift"]       = (int) $counters['payment'][$uid][Counter::COUNTER_TYPE_PAYMENT_SEND_GIFT];
        $userinfo["receive_gift"]    = (int) $counters['payment'][$uid][Counter::COUNTER_TYPE_PAYMENT_RECEIVE_GIFT];

        $userinfo["followed"] = !! current(Follow::isFollowed($loginid, $uid));

        $this->render($userinfo);
    }

    public function getMultiUserInfoAction()
    {
        $uids = trim($this->getParam("uids"), ",");

        Interceptor::ensureNotFalse(preg_match("/^(\d+,?)+$/", $uids) != 0 && substr_count($uids, ",") < 100, ERROR_PARAM_INVALID_FORMAT, "uid($uids)");

        $_UIDList = explode(",", $uids);
        $result = array();

        $forbidden_users = Forbidden::isForbiddenUsers($_UIDList);

        foreach ($_UIDList as $uid) {
            $user = new User();
            $userinfo = $user->getUserInfo($uid);

            if (! $userinfo || in_array($uid, $forbidden_users)) {
                $result[$uid] = array();
                continue;
            }

            $result[$uid] = $userinfo;

        }

        $this->render(
            array(
            'users'=>$result
            )
        );
    }

    public function getMyUserInfoAction()
    {
        $uid = Context::get("userid");

        $user = new User();
        $userinfo = $user->getUserInfo($uid);

        $counters = Counter::mixed(
            array(
                  Counter::COUNTER_TYPE_FOLLOWERS,
                  Counter::COUNTER_TYPE_FOLLOWINGS,
                  Counter::COUNTER_TYPE_LIVE_PRAISES
               ),
            array($uid)
        );
        $userinfo["followers"]  = (int) $counters[$uid][Counter::COUNTER_TYPE_FOLLOWERS];
        $userinfo["followings"] = (int) $counters[$uid][Counter::COUNTER_TYPE_FOLLOWINGS];
        $userinfo["praises"]    = (int) $counters[$uid][Counter::COUNTER_TYPE_LIVE_PRAISES];

        $account = new Account();
        $accountinfo = $account->getAccountList($uid);

        $userinfo["ticket"]  = (int) $accountinfo["ticket"];
        $userinfo["diamond"] = (int) $accountinfo["diamond"];
        $userinfo["coin"]    = (int) $accountinfo["coin"];
        $userinfo["star"]    = (int) $accountinfo["star"];

        $dao_verifiedStudent = new DAOVerifiedStudent();
        $school = $dao_verifiedStudent->getVerify($uid);
        $userinfo['vs_status'] = $school ? ($school['status'] == 3 ? 1 : $school['status']) : 0;

        $dao_verifiedArtist = new DAOVerifiedArtist();
        $artist = $dao_verifiedArtist->getVerify($uid);
        $userinfo['va_status'] = $artist ? ($artist['status'] == 3 ? 1 : $artist['status']) : 0;

        //座驾逻辑
        $ride=Bag::getRideByUid($uid);
        $userinfo['rideurl'] = $ride;

        $userinfo['kick']    = $userinfo['vip']>0?(bool) Vip::getLevelConfig($userinfo['vip'], Vip::TYPE_KICK_NUM):false;
        $userinfo['silence'] = $userinfo['vip']>0?(bool) Vip::getLevelConfig($userinfo['vip'], Vip::TYPE_SILENCE_NUM):false;

        $this->render($userinfo);
    }

    public function searchAction()
    {
        $keyword = trim(strip_tags($this->getParam("keyword")));
        Interceptor::ensureNotEmpty($keyword, ERROR_PARAM_IS_EMPTY, "keyword");
        $this->render(array("users"=>$this->newSearch($keyword)));

        /*        $user = new User();

        if(preg_match('/^[1-9]\d*$/', $keyword)){
            if(!empty($real_uid = UserPersist::getRealUidByUid($keyword))){
                $keyword = $real_uid;
            }
            $userinfo = $user->getUserInfo($keyword);
            if(!empty($userinfo)){
               $user_list[] = array(
                  "uid"      => $userinfo['uid'],
                  "vid"      => $userinfo['vid'],
                  "nickname" => $userinfo['nickname'],
                  "avatar"   => $userinfo['avatar'],
                  "gender"   => $userinfo['gender'],
                  "level"    => $userinfo['level'],
                  "medal"    => $userinfo['medal'],
               );
            }else{
                try{
                    $user_list = $user->searchUserByNickname($keyword);
                }catch(Exception $e){
                    $user_list = $user->getUserListByNickname($keyword);
                }
            }
        }else{
            try{
                $user_list = $user->searchUserByNickname($keyword);
            }catch(Exception $e){
                $user_list = $user->getUserListByNickname($keyword);
            }
        }

        $superAnchorList = $user->searchSuperUserByNicknameDb($keyword);

        if($superAnchorList){
            foreach($user_list as $v){
                $uidlist[] = $v['uid'];
            }
            foreach($superAnchorList as $k=>$v){
                if(!in_array($v['uid'],$uidlist)){
                    array_unshift($user_list, $v);
                }
            }
        }

        Interceptor::ensureNotEmpty($user_list, ERROR_BIZ_PASSPORT_SEARCH_NOT_FOUND);
        foreach($user_list as $v){
            $_UIDList[] = $v['uid'];
        }
        $forbidden_users = Forbidden::isForbiddenUsers($_UIDList);
        foreach ($user_list as $k=>$v) {
            if (in_array($v['uid'], $forbidden_users)) {
                unset($user_list[$k]);
            }
        }
        $user_list = array_merge($user_list);

        //大主播
        $redis = Cache::getInstance("REDIS_CONF_CACHE");
        $rediskeys = $redis->hkeys('dreamlive_super_popular_users');
        $superList = $redis->hmget('dreamlive_super_popular_users', $rediskeys);
        if($superList){
            foreach($superList as $k=>$v ){
                if (strstr($v, $keyword) !== false ){
                    if(in_array($k,$_UIDList)){
                        $tempList = array();
                        foreach ($user_list as $k2=>$v2) {
                            if ($v2['uid'] == $k) {
                                $tempList = $v2;
                                unset($user_list[$k2]);
                            }
                        }
                        array_unshift($user_list, $tempList);
                    }else{
                        $userinfo = $user->getUserInfo($k);
                        array_unshift($user_list, array(
                            "uid"      => $userinfo['uid'],
                            "vid"      => $userinfo['vid'],
                            "nickname" => $userinfo['nickname'],
                            "avatar"   => $userinfo['avatar'],
                            "gender"   => $userinfo['gender'],
                            "level"    => $userinfo['level'],
                            "medal"    => $userinfo['medal'],
                         ));
                    }
                }
            }
        }
        //大主播

        //北京清除大主播
        $isBeijing = false;
        $user_ip = Util::getIP();
        list($province, $city, $district) = Util::ip2Location($user_ip);
        if($city=='北京'){
            $isBeijing = true;
        }

        $lng = Context::get("lng");
        $lat = Context::get("lat");
        if(!empty($lng) && !empty($lat)){
            $dispatch = new DispatchStream();
            $distance = $dispatch->getDistance(39.9150483, 116.3908177, $lat, $lng);
            if($distance<=30){
                $isBeijing = true;
            }
        }

        if($isBeijing){
            $cache = Cache::getInstance("REDIS_CONF_CACHE");
            $big_liver_keys = $cache->get("big_liver_keys");
            $big_live_list = explode(',',$big_liver_keys);

            foreach($user_list as $key=>$user){
                if (in_array($user['uid'], $big_live_list)) {
                    unset($user_list[$key]);
                }
            }
            $user_list = array_values($user_list);
        }
        //不显示自己
        foreach($user_list as $key=>$user){
            if ($user['uid'] == $uid) {
                unset($user_list[$key]);
            }
        }
        $user_list = array_values($user_list);
        foreach($user_list as $key=>$user){
            $level[$key] = $user['level'];
        }
        array_multisort($level, SORT_DESC, SORT_NUMERIC, $user_list);

        //添加直播状态并且正在直播用户排前
        $left  = [];
        $right = [];
        if($user_list){
            foreach ($user_list as $k=>&$v){
                $v['is_live'] = Live::isUserLive($v['uid'])?true:false;
                if(Live::isUserLive($v['uid'])){
                    $left[$k] = $v;
                }else{
                    $right[$k]  = $v;
                }
            }
            $user_list = array_merge($left,$right);
        }

        $this->render(array("users"=>$user_list));*/
    }

    private function newSearch($keyword)
    {
        $uid = Context::get("userid");
        $keyword = trim(strip_tags($this->getParam("keyword")));
        Interceptor::ensureNotEmpty($keyword, ERROR_PARAM_IS_EMPTY, "keyword");

        $user_list=array();
        $user = new User();
        if(preg_match('/^[1-9]\d*$/', $keyword)) {
            if(!empty($real_uid = UserPersist::getRealUidByUid($keyword))) {
                $keyword = $real_uid;
            }
            $userinfo = $user->getUserInfo($keyword);
            if(!empty($userinfo)) {
                $user_list[] = array(
                    "uid"      => $userinfo['uid'],
                    "vid"      => $userinfo['vid'],
                    "nickname" => $userinfo['nickname'],
                    "avatar"   => $userinfo['avatar'],
                    "gender"   => $userinfo['gender'],
                    "level"    => $userinfo['level'],
                    "medal"    => $userinfo['medal'],
                );
            }
        }
        if (empty($user_list)) {
            try{
                $user_list = $user->searchUserByNickname($keyword);
            }catch(Exception $e){
            }
            if (empty($user_list)) {
                $user_list = $user->getUserListByNickname($keyword);
            }
        }


        Interceptor::ensureNotEmpty($user_list, ERROR_BIZ_PASSPORT_SEARCH_NOT_FOUND);
        foreach($user_list as $v){
            $_UIDList[] = $v['uid'];
        }
        $forbidden_users = Forbidden::isForbiddenUsers($_UIDList);

        foreach ($user_list as $k=>$v) {
            if (in_array($v['uid'], $forbidden_users)) {
                unset($user_list[$k]);
            }
            if ($v['uid'] == $uid) {
                unset($user_list[$k]);
            }
        }

        //添加直播状态并且正在直播用户排前
        $left  = [];
        $right = [];
        if($user_list) {
            foreach ($user_list as $k=>&$v){
                $v['is_live'] = Live::isUserLive($v['uid'])?true:false;
                if(Live::isUserLive($v['uid'])) {
                    $left[$k] = $v;
                }else{
                    $right[$k]  = $v;
                }
            }
            $user_list = array_merge($left, $right);
        }

        return $user_list;
    }

    public function expRankingAction()
    {
        include 'server_conf.php';
        include_once $ROOT_PATH."/src/application/models".DIRECTORY_SEPARATOR. "task". DIRECTORY_SEPARATOR. "AwardTask.php";

        $uid = Context::get("userid");

        $exp = UserExp::getUserExp($uid);
        $level = UserExp::getLevelByExp($exp);
        $levelexp = UserExp::getExpByLevel($level+1);
        $prelevelexp = UserExp::getExpByLevel($level);

        $dao_exp_map = new DAOUserLevelMap();
        $task        = new Task();

        $total      = $dao_exp_map->getTotalCount();
        $eqlevel    = $dao_exp_map->getEqLevelCount($level);
        $ltlevel    = $dao_exp_map->getLtLevelCount($level);
        $totalnum   = $total['count'] ? $total['count'] : 0;
        $eqlevelnum = $eqlevel['count'] ? $eqlevel['count'] : 0;
        $ltlevelnum = $ltlevel['count'] ? $ltlevel['count'] : 0;
        $percent = floor((($exp - $prelevelexp)/($levelexp - $prelevelexp) * $eqlevelnum + $ltlevelnum) / $totalnum * 100);

        $userTask     = new UserTask();
        $taskInfo     = $task->getTaskInfo(Task::TASK_ID_LIVE_WATCH);
        $award = AwardTask::getTaskAward($uid, Task::TASK_ID_LIVE_WATCH, $taskInfo['type'], 1, json_decode($taskInfo['extend'], true));
        $watch = $award['exp'];

        $taskInfo     = $task->getTaskInfo(Task::TASK_ID_SHARE);
        $award = AwardTask::getTaskAward($uid, Task::TASK_ID_SHARE, $taskInfo['type'], 1, json_decode($taskInfo['extend'], true));
        $share = $award['exp'];

        $taskInfo     = $task->getTaskInfo(Task::TASK_ID_LIVE_START);
        $award = AwardTask::getTaskAward($uid, Task::TASK_ID_LIVE_START, $taskInfo['type'], 1, json_decode($taskInfo['extend'], true));
        $live = $award['exp'];

        $taskInfo     = $task->getTaskInfo(Task::TASK_ID_LIVE_START);
        $award = AwardTask::getTaskAward($uid, Task::TASK_ID_LIVE_START, $taskInfo['type'], 1, json_decode($taskInfo['extend'], true));
        $chat  = $award['exp'];

        $this->render(
            array(
            'percent'    => $percent,
            'level'      => $level,
            'exp'        => $exp,
            'levelexp'   => $levelexp,
            /**
            'watch'     => $task->getTaskProfit($uid, Task::TASK_ID_LIVE_WATCH, 1),
            'share'     => $task->getTaskProfit($uid, Task::TASK_ID_SHARE, 1),
            'live'      => $task->getTaskProfit($uid, Task::TASK_ID_LIVE_START, 1),
            'chat'      => $task->getTaskProfit($uid, Task::TASK_ID_COMMENT, 1),
            */
            'watch'     => $watch,
            'share'     => $share,
            'live'      => $live,
            'chat'      => $chat,
            )
        );
    }

    public function getOldUserInfoAction()
    {
        $uid     = Context::get("userid");
        $rid     = trim($this->getParam("rid"));
        $token   = trim($this->getParam("access_token"));

        Interceptor::ensureNotEmpty($rid,     ERROR_PARAM_IS_EMPTY, "rid");
        Interceptor::ensureNotEmpty($token,    ERROR_PARAM_IS_EMPTY, "access_token");

        $user = new User();
        $url = $user->getOldUser($uid, $rid, $token);

        $this->render(array('url'=>$url));
    }

    public function mergeOldUserAction()
    {
        $uid     = Context::get("userid");
        $mergeid     = trim($this->getParam("mergeid"));

        Interceptor::ensureNotEmpty($mergeid,     ERROR_PARAM_IS_EMPTY, "id");

        $user = new User();
        $token = $user->mergeOldUser($uid, $mergeid);

        $this->render(array('token'=>$token));
    }
    public function getMergeOldUserStatusAction()
    {
        $uid     = Context::get("userid");

        $user = new User();
        $status = $user->getOldUserStatus($uid);

        $this->render($status);
    }
    public function delUserMergeOldAction()
    {
        $id     = Context::get("mergeid");

        $dao_user_merge = new DAOUserMergeOld();
        $dao_user_merge->delUserMergeOldById($id);

        $this->render();
    }

    public function getPwdStatusAction()
    {
        $uid  = Context::get("userid");

        $user = new User();
        $status = $user->issetPwd($uid);

        $this->render(array("status"=>$status));
    }

    public function changePasswordAction()
    {
        $uid  = Context::get("userid");

        $old_pass     = trim($this->getParam("oldpassword"));
        $new_pass     = trim($this->getParam("newpassword"));

        Interceptor::ensureNotEmpty($old_pass,     ERROR_PARAM_IS_EMPTY, "oldpassword");
        Interceptor::ensureNotEmpty($new_pass,     ERROR_PARAM_IS_EMPTY, "newpassword");

        Interceptor::ensureFalse($old_pass == $new_pass,  ERROR_BIZ_PASSPORT_USER_PASSWORD_OLD_EQ_NEW);

        $user = new User();
        $user->modPassword($uid, $new_pass, $old_pass);

        $this->render();
    }

    public function getUidByForgotCodeAction()
    {
        $mobile = trim($this->getParam("mobile"));
        $code     = trim($this->getParam("code"));

        Interceptor::ensureNotEmpty($mobile,     ERROR_PARAM_IS_EMPTY, "mobile");
        Interceptor::ensureNotEmpty($code,    ERROR_PARAM_IS_EMPTY, "code");

        Interceptor::ensureNotFalse(Captcha::VerifyForgotNotDel($mobile, $code), ERROR_CODE_INVALID, $mobile. "($code)");

        $dao_user_bind = new DAOUserBind();
        $bindinfo = $dao_user_bind->getUserBindBySource($mobile, "mobile");

        Interceptor::ensureNotEmpty($bindinfo, BIZ_PASSPORT_ERROR_NOT_BIND);

        $uid = $bindinfo["uid"];

        $this->render(array("uid"=>$uid));
    }
    public function getVipInfoAction()
    {
        $uid  = Context::get("userid");

        $user = new User();
        $userinfo = $user->getUserInfo($uid);

        $vipinfo = Vip::getUserVipInfo($uid);

        $level = $userinfo['vip'];
        if(strtotime($vipinfo['uptime'])>strtotime(date('Y-m'))) {
            $vipinfo['consume_keep'] = 0;
        }

        $data = array(
            'level'           => $level,
            'next_level'      => min($level+1, Vip::getMaxLevel()),
            'current_consume' => (int)$vipinfo['consume_current'],
            'keep_consume'    => (int)$vipinfo['consume_keep'],
        );

        $data['next_consume'] = $level == Vip::getMaxLevel()? 0 : Vip::getLevelCurrentConsume($data['next_level']);
        $config = Vip::getLevelConfigs($level);

        $data['exclusive']   = (bool)$config['exclusive'];
        $data['ride']        = (bool)$config['ride'];
        $data['horn_num']    = (bool)$config['horn_num'];
        $data['prop_num']    = (bool)$config['prop_num'];
        $data['font_color']  = (bool)$config['font_color'];
        $data['silence']     = (bool)$config['silence'];
        $data['silence_num'] = (bool)$config['silence_num'];
        $data['kick']        = (bool)$config['kick'];
        $data['kick_num']    = (bool)$config['kick_num'];

        $this->render($data);
    }

    //------------------------------ 后台  --------------------------------
    public function addUserPeristAction()
    {
        $uid = intval($this->getParam("uid"));

        Interceptor::ensureNotEmpty($uid, ERROR_PARAM_IS_EMPTY, "uid");

        $user_persist = new UserPersist();
        $user_persist->addPersist($uid);

        $this->render();
    }

    public function setUserInfoAction()
    {
        $uid       = trim($this->getParam("uid"));
        $nickname  = trim($this->getParam("nickname"));
        $signature = trim($this->getParam("signature"));
        $avatar    = trim($this->getParam("avatar"));

        Interceptor::ensureNotFalse(is_numeric($uid), ERROR_PARAM_INVALID_FORMAT, "uid");

        $user_info = User::getUserInfo($uid);
        if($nickname != $user_info['nickname']) {
            $dao_user = new DAOUser();
            Interceptor::ensureFalse($dao_user->exists($nickname), ERROR_USER_NAME_EXISTS, $nickname);
        }

        $user = new User();
        $user->setUserInfo($uid, $nickname, $signature, $avatar);

        $this->render();
    }


    public function getUserAvatarAction()
    {
        $uid = intval($this->getParam("uid"));

        Interceptor::ensureNotEmpty($uid, ERROR_PARAM_IS_EMPTY, "uid");

        $user = new User();
        $userinfo = $user->getUserInfo($uid);

        Interceptor::ensureNotEmpty($userinfo, ERROR_USER_NOT_EXIST);

        $this->render(
            array(
                'avatar'=> (string)empty($userinfo)? "" : $userinfo['avatar'],
                'nickname'=> (string)empty($userinfo)? "" : $userinfo['nickname'],
                'balance' => (int)Account::getBalance($uid, 2),
            )
        );
    }

    public function getUidAction()
    {
        $uid = intval($this->getParam("uid"));
        Interceptor::ensureNotEmpty($uid, ERROR_PARAM_IS_EMPTY, "uid");

        $real_uid = UserPersist::getRealUidByUid($uid);
        $this->render(array("uid"=>$real_uid));
    }

    public function getMergeUidFromOldUidAction()
    {
        $uid = intval($this->getParam("uid"));
        Interceptor::ensureNotEmpty($uid, ERROR_PARAM_IS_EMPTY, "olduid");

        $dao = new DAOUserMergeOld();
        $result = $dao->getOldUserByOlduid($uid);
        Interceptor::ensureNotFalse($result && $result['status'] == "SUCCESS", ERROR_BIZ_PASSPORT_SEARCH_NOT_FOUND);

        $this->render(array("uid"=>$result['uid'],"time"=>$result['modtime']));
    }

    public function adminSetVipConfigAction()
    {
        $vip         = intval($this->getParam("vip"));
        $incr_amount = trim($this->getParam("incr_amount", ''));
        $decr_amount = trim($this->getParam("decr_amount", ''));
        $logo        = trim($this->getParam("logo", ''));
        $exclusive   = intval($this->getParam("exclusive"));
        $ride        = intval($this->getParam("ride"));
        $ride_expire = intval($this->getParam("ride_expire"));
        $horn_num    = intval($this->getParam("horn_num"));
        $prop_num    = intval($this->getParam("prop_num"));
        $font_color  = trim($this->getParam("font_color"));
        $silence     = intval($this->getParam("silence"));
        $kick        = intval($this->getParam("kick"));
        $silence_num = intval($this->getParam("silence_num"));
        $kick_num    = intval($this->getParam("kick_num"));

        Interceptor::ensureNotFalse($vip > 0, ERROR_PARAM_INVALID_FORMAT, "vip");

        $key = Vip::getKeyConfig($vip);

        $redis = Cache::getInstance("REDIS_CONF_USER");
        $redis->hmset(
            $key, array(
            'vip'         => $vip,
            'incr_amount' => $incr_amount,
            'decr_amount' => $decr_amount,
            'logo'        => $logo,
            'exclusive'   => $exclusive,
            'ride'        => $ride,
            'ride_expire' => $ride_expire,
            'horn_num'    => $horn_num,
            'prop_num'    => $prop_num,
            'font_color'  => $font_color,
            'silence'     => $silence,
            'kick'        => $kick,
            'silence_num' => $silence_num,
            'kick_num'    => $kick_num,
            )
        );

        $this->render();
    }

    public function adminImportAnchorsAction()
    {
        $data = $this->getParam('data');
        $data = json_decode($data, true);
        Interceptor::ensureNotFalse(json_last_error() == JSON_ERROR_NONE && $data, ERROR_PARAM_INVALID_FORMAT, 'data');

        $dao = new DAOSuperAnchors();
        $dao->importAnchors($data);

        $this->render();
    }
    
    public function setUserLiveAuthAction()
    {
        $uid = intval($this->getParam("uid", 0));
        $flag=$this->getParam("flag", "");

        Interceptor::ensureNotFalse($uid>0, ERROR_PARAM_INVALID_FORMAT, "uid");
        Interceptor::ensureNotFalse(!empty($flag), ERROR_PARAM_INVALID_FORMAT, 'flag');// on ,off

        if ($flag=='on') {
            UserAuth::openUserLiveAuth($uid);
        }elseif ($flag=='off') {
            UserAuth::closeUserLiveAuth($uid);
        }

        $this->render();
    }

    public function getUserLiveAuthAction()
    {
        $uid = intval($this->getParam("uid", 0));
        $result=array(
            'uid'=>$uid,
            'liveauth'=>UserAuth::hasLiveAuth($uid),
        );
        $this->render($result);
    }

    public function resetQqUserPwdAction()
    {
        $uid      = intval($this->getParam("uid", 0));
        $password = $this->getParam("pwd", "");

        Interceptor::ensureNotFalse($uid>10000000, ERROR_PARAM_INVALID_FORMAT, "uid");
        Interceptor::ensureNotFalse(!empty($password), ERROR_PARAM_INVALID_FORMAT, 'password');

        $salt = rand(100000, 999999);
        $password = md5($password.$salt);

        $dao_user = new DAOUser();
        $dao_user->setPassword($uid, $password, $salt);

        $this->render();
    }

    public function adminSetVipLevelAction()
    {
        $uid   = intval($this->getParam("uid", 0));
        $newlevel = intval($this->getParam("newlevel", 0)); 

        Interceptor::ensureNotFalse($uid>10000000, ERROR_PARAM_INVALID_FORMAT, "uid");
        Interceptor::ensureNotFalse($newlevel>0 && $newlevel<11, ERROR_PARAM_INVALID_FORMAT, "newlevel");
        
        Vip::setVipLevel($uid, $newlevel);

        User::reload($uid);
        
        $this->render();
    }
}
?>
