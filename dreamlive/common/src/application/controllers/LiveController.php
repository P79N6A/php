<?php
class LiveController extends BaseController
{
    public function getStreamAction()
    {
        $region = Context::get('region');
        $uid    = Context::get('userid');
        $result = DispatchStream::getDispatch($region, $uid);
        $this->render($result);
    }

    public function prepareAction()
    {
        if(Context::get("platform")=='obs' || Config::convertVersion(Context::get("version")) < Config::convertVersion('2.4.0.0')) {
            $config = new Config();
            $result = $config->getConfig("china", "obs_live_start_permissions_config", "server", '1000000000000');
            $list   = json_decode($result['value'], true);
            Interceptor::ensureNotFalse(in_array(Context::get("userid"), $list), ERROR_BIZ_PAYMENT_WITHDRAW_NO_POWER);
        }
        
        $stream = new Stream();
        list($sn, $partner) = $stream->getStream(Context::get("userid"));

        //判断是否具有录制的权限
        $live = new Live();
        $replay = $live->isPeplayPermissions(Context::get("userid"));
        
        //私密直播权限
        $privacy = Privacy::getPrivacy(Context::get("userid"));
        $privacyReplay  = Privacy::getPrivacyReplay(Context::get("userid"));
        if($replay || $privacyReplay) {
            $replay = true;
        }
        
        $rtmp = $stream->getRTMPUrl($sn, $partner, $replay);
        $streams = StreamDispatch::getStream($rtmp, $partner, Context::get("userid"));
        $liveid = Counter::increase(Counter::COUNTER_TYPE_FEEDS, "idgen");
        
        if(trim(Context::get("platform")) == 'ios' && Context::get("version") < '2.6.3') {
            $streams = array($rtmp);
        }
        
        $option = array(
            'region' => Context::get('region'),
            'uid'    => Context::get("userid"),
            'ip'     => Util::getIP(),
            'lng'    => $this->getParam("lng"),
            'lat'    => $this->getParam("lat")
            
        );
        include_once 'process_client/ProcessClient.php';
        ProcessClient::getInstance("dream")->addTask("user_login_speed", $option);

        //直播权限
        $liveAuth=UserAuth::hasLiveAuth(Context::get("userid"));
        $liveAuth=$liveAuth=="N"?false:true;
        
        $user = new User();
        $user_info = $user->getUserInfo(Context::get("userid"));
        
        
        $config = new Config();
        $result = $config->getConfig("china", "push_bitrate_adapt_ios", "ios", '1000000000000');
        $pushBitrateAdaptIos   = json_decode($result['value'], true);
        
        $this->render(array("sn"=>$sn, "medal"=> $user_info['medal'], "partner"=>$partner, "rtmp"=>$rtmp, "liveid" => $liveid,'replay'=>$replay, 'stream' => $streams, 'privacy'=>$privacy,'permissions'=>$liveAuth,'adaptEnable'=>$pushBitrateAdaptIos['adaptEnable']));
    }

    public function getLiveInfoAction()
    {
        $liveid = $this->getParam("liveid")     ? intval(trim($this->getParam("liveid")))     : 0;
        $platform = $this->getParam("platform") ? trim(strip_tags($this->getParam("platform"))) : "Android";
        $userid   = intval(Context::get("userid"));
        Interceptor::ensureNotFalse($liveid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "liveid");

        $live = new Live();
        $live_info = $live->getLiveInfo($liveid);
        $privacy = Privacy::getPrivacyInfoByLiveInfo($live_info['privacy']);
        //unset($live_info['privacy']);
        Interceptor::ensureNotEmpty($live_info, ERROR_BIZ_LIVE_NOT_EXIST);
        //Interceptor::ensureNotFalse(($live_info['status']==Live::ACTIVING || $live_info['status']==Live::PAUSED || ($live_info['replay']=='Y' && $live_info['replayurl']!='')), ERROR_LIVE_IS_OVER);

        //判断是否具有录制的权限
        /**
        $live = new Live();
        $replay = $live->isPeplayPermissions($live_info['uid']);
        */
        $replay = ($live_info['record'] == 'Y') ? true : false;

        $stream = new Stream();
        if($live_info['status']==Live::ACTIVING || $live_info['status']==Live::PAUSED) {
            $flv = $stream->getFLVUrl($live_info['sn'], $live_info['partner'], $live_info['region'], $replay);
            $hls = $stream->getHLSUrl($live_info['sn'], $live_info['partner'], $live_info['region'], $replay);
            if (Context::get("platform") == 'server') {
                $live_info['url']    = $hls;
                $live_info['flv']    = $flv;
            } else {
                $live_info['url']    = $flv;
            }
            
            //istudio合作
            if (!empty($live_info['pullurl'])) {
                $live_info['url']    = $live_info['pullurl'];
            }
            
            //$privacy   = Privacy::hasPrivacyLive($live_info["uid"], $live_info["addtime"], $live_info["endtime"], $liveid);
            if (!empty($privacy) && isset($privacy['privacyid']) && Context::get("version") >= '2.6.2') {
                
                $aes = new AES;
                $aes_iv = substr(md5($liveid.$userid), 0, 16);
                $aes_key = substr(md5($userid), 0, 16);
                $flv = $aes->aes128cbcEncrypt($flv, $aes_iv, $aes_key);
                
                $live_info['iv'] = $aes_iv;
                $live_info['k']  = $aes_key;
                $live_info['url'] = $flv;
            }
            if(!empty($live_info['privacy']) && Context::get("version") <= '2.6.3') {
                $live_info['status'] = Live::FINISHED;
            }
            unset($live_info['privacy']);
            if (!empty($privacy) && isset($privacy['privacyid'])) {
                $privacyInfo = Privacy::getPrivacyInfo($live_info['uid'], $live_info['addtime'], $live_info['endtime'], $liveid, $privacy);
                if ($platform == 'ios' && $privacyInfo['preview'] == false && Context::get("version") <= '2.6.3') {
                    $privacyInfo['preview'] = true;
                    $privacyInfo['freetime'] = 10;
                }

                $live_info['privacy'] = $privacyInfo;
                $live_privacy_ticket     = Counter::get(Counter::COUNTER_TYPE_LIVE_PRIVACY_TICKET, $privacyInfo['privacyid']);//付费收益
                    
                $live_info['watches'] = Counter::get(Counter::COUNTER_TYPE_LIVE_PRIVACY_WATCHES, $privacyInfo['privacyid']);
                $live_info['profit'] += $live_privacy_ticket;
            }

            
        }elseif($live_info['replay']=='Y' && $live_info['replayurl']!='') {
            $replayurl = $stream->getRepalyUrl($live_info['partner'], $live_info['region'], $live_info['replayurl']);
            $live_info['url']         = $replayurl;
            $subtitleurl              = Context::getConfig("STATIC_DOMAIN")."/" . $live_info['liveid']."/replay/index.txt";
            $live_info['subtitleUrl'] = $subtitleurl;
            
            //$privacy   = Privacy::hasPrivacyLive($live_info["uid"], $live_info["addtime"], $live_info["endtime"], $liveid);
            if (!empty($privacy) && isset($privacy['privacyid']) && Context::get("version") >= '2.6.2') {
                
                $aes = new AES;
                $aes_iv = substr(md5($liveid.$userid), 0, 16);
                $aes_key = substr(md5($userid), 0, 16);
                $flv = $aes->aes128cbcEncrypt($replayurl, $aes_iv, $aes_key);
                
                $live_info['iv'] = $aes_iv;
                $live_info['k']  = $aes_key;
                $live_info['url'] = $flv;
            }
            
            if(!empty($live_info['privacy'])) {
                   $privacy  = Privacy::getReplayPrivacyInfoByLiveInfo($live_info['privacy']);
                   unset($live_info['privacy']);
                $privacyInfo = Privacy::getPrivacyInfo($live_info['uid'], $live_info['addtime'], $live_info['endtime'], $liveid, $privacy);
                if ($platform == 'ios' && $privacyInfo['preview'] == false && Context::get("version") <= '2.6.3') {
                    $privacyInfo['preview'] = true;
                    $privacyInfo['freetime'] = 10;
                }
            
                $live_info['privacy'] = $privacyInfo;
                $live_privacy_ticket     = Counter::get(Counter::COUNTER_TYPE_LIVE_PRIVACY_TICKET, $privacyInfo['privacyid']);//付费收益
                 
                $live_info['watches'] = Counter::get(Counter::COUNTER_TYPE_LIVE_PRIVACY_WATCHES, $privacyInfo['privacyid']);
                $live_info['profit'] += $live_privacy_ticket;
            }
            $live_info['replayurls'] = $live->getReplayurlList($live_info);
        }else{
            unset($live_info['privacy']);
        }

        $live_info['ticket'] = Task::makeTicket(Context::get("userid"), Task::TASK_ID_LIVE_WATCH, time());

        if ($live_info['extends']['position']) {
            $live_info['position'] = $live_info['extends']['position'];
        }

        unset($live_info['extends']);

        if (Context::get("version") >= '2.9.0') {
            $patroller = new Patroller();
            $is_patroller = $patroller->isPatroller(Context::get("userid"), $live_info['uid'], $liveid);
            $live_info['isPatroller'] = (int) $is_patroller;
            $ret = UserGuard::getUserGuardRedis(Context::get("userid"), $live_info['uid'], true);
        
            $is_guard = intval($ret['type']);
            $live_info['isGuard'] = $is_guard;
        }
        $live_info['permit'] = Link::isUserPermit($live_info['uid']);

        $packet = new Packet();
        $live_info['packetid']  = (int) $packet->getLivePacket($liveid);

        $this->render(array("live" => $live_info));
    }

    public function startAction()
    {
        $liveid     = $this->getParam("liveid")   ? intval(trim($this->getParam("liveid")))         : 0;
        $sn         = $this->getParam("sn")       ? strip_tags(trim($this->getParam("sn")))         : "";
        $partner    = $this->getParam("partner")  ? strip_tags(trim($this->getParam("partner")))    : "";
        $title      = $this->getParam("title")    ? strip_tags(trim($this->getParam("title")))      : "";
        $width      = $this->getParam("width")    ? intval($this->getParam("width"))                : 0;
        $height     = $this->getParam("height")   ? intval($this->getParam("height"))               : 0;
        $point      = $this->getParam("point")    ? strip_tags(trim($this->getParam("point")))      : "0.0";
        $province   = $this->getParam("province") ? strip_tags(trim($this->getParam("province")))   : "";
        $city       = $this->getParam("city")     ? strip_tags(trim($this->getParam("city")))       : "";
        $district   = $this->getParam("district") ? strip_tags(trim($this->getParam("district")))   : "";
        $location   = $this->getParam("location") ? strip_tags(trim($this->getParam("location")))   : "";
        $virtual    = $this->getParam("virtual")  ? strip_tags(trim($this->getParam("virtual")))    : "N";
        $position   = $this->getParam("position") ? strip_tags(trim($this->getParam("position")))   : "Y";
        $replay     = $this->getParam("replay")   ? strip_tags(trim($this->getParam("replay")))     : "N";
        $cover      = $this->getParam("cover")    ? strip_tags(trim($this->getParam("cover")))      : "";
        $pullurl    = $this->getParam("pullurl")  ? strip_tags(trim($this->getParam("pullurl")))    : "";
        $province     = str_replace('市', '', $province);
        $city         = str_replace('市', '', $city);
        $district     = str_replace('市', '', $district);

        $extends    = array('position'=>$position);

        $uid    = Context::get("userid");

        //obs推流，强制保存
        $platform = Context::get("platform");
        if(in_array($platform, array("obs"))) {
            $replay = "Y";
        }

        //Interceptor::ensureNotFalse(preg_match("/-?[\d\.]+,-?[\d\.]+/", $point) > 0, ERROR_PARAM_INVALID_FORMAT, "point");
        Interceptor::ensureNotFalse(in_array($virtual, array("Y","N"), true), ERROR_PARAM_INVALID_FORMAT, "virtual");
        $live = new Live();
        $live_info = $live->getLiveInfo($liveid);

        if (!empty($live_info)) {
            $result = array(
            "live" => array(
                        "liveid" => $liveid
                    ),
            "ticket" => Task::makeTicket(Context::get("userid"), Task::TASK_ID_LIVE_START, strtotime($live_info['addtime'])),
            );

            $this->render($result);
        }

        //------------------客户端同时调多次解决办法--------------------
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $start_only_once_redis_key = "live_start_only_start_once_". $liveid;

        if ($cache->INCR($start_only_once_redis_key) > 1) {
            $result = array(
            "live" => array(
                            "liveid" => $liveid
            ),
            "ticket" => Task::makeTicket(Context::get("userid"), Task::TASK_ID_LIVE_START, strtotime($live_info['addtime'])),
            );
        }
        $cache->expire($start_only_once_redis_key, 36000);
        //------------------客户端同时调多次解决办法--------------------

        $result = $live->add($liveid, $uid, $sn, $partner, $title, $width, $height, $point, $province, $city, $district, $location, $virtual, $extends, $replay, $cover, $pullurl);
        $result['ticket'] = Task::makeTicket(Context::get("userid"), Task::TASK_ID_LIVE_START, time());

        $live_info = $live->getLiveInfo($result['live']['liveid']);
        

        $record  = 'N';
        if($live->isPeplayPermissions(Context::get("userid")) || Privacy::getPrivacyReplay(Context::get("userid"))) {
            $record = 'Y';
        }


        $live_info = array(
            'liveid'   => $live_info['liveid'],
        'uid'      => $live_info['uid'],
        'title'    => $live_info['title'],
        'duration' => $live_info['duration'],
        'sn'       => $live_info['sn'],
        'cover'    => $live_info['cover'],
        'point'    => $live_info['point'],
        'province' => $live_info['province'],
        'city'     => $live_info['city'],
        'district' => $live_info['district'],
        'location' => $live_info['location'],
        'startime' => $live_info['addtime'],
        'extends'  => $live_info['extends'],
        'replayurl'=> $live_info['replayurl'],
        'replay'   => $live_info['replay'],
        'region'   => $live_info['region'],
        'partner'  => $live_info['partner'],
        'width'    => $live_info['width'],
        'height'   => $live_info['height'],
        'record'   => $record
        );
        include_once 'process_client/ProcessClient.php';
        ProcessClient::getInstance("dream")->addTask("live_start", $live_info);

        $live->heartbeat($live_info['liveid']);
        
        
        $result['permit'] = Link::isUserPermit($live_info['uid']);
        $this->render($result);
    }

    public function stopAction()
    {
        $liveid = $this->getParam("liveid") ? intval($this->getParam("liveid")) : 0;

        Interceptor::ensureNotFalse($liveid >= 0, ERROR_PARAM_NOT_SMALL_ZERO, "liveid");

        $live = new Live();
        $live_info = $live->getLiveInfo($liveid);

        Interceptor::ensureNotEmpty($live_info, ERROR_BIZ_LIVE_NOT_EXIST);
        Interceptor::ensureNotFalse(Context::get("userid") == $live_info["uid"], ERROR_BIZ_LIVE_NOT_OWNER);
        Interceptor::ensureNotFalse(in_array($live_info["status"], array(Live::ACTIVING, Live::PAUSED)), ERROR_BIZ_LIVE_NOT_ACTIVE);

        $live->stop($liveid);

        $live_info = $live->getLiveInfo($liveid);


        include_once 'process_client/ProcessClient.php';
        ProcessClient::getInstance("dream")->addTask("live_stop", $live_info);
        ProcessClient::getInstance("dream")->addTask("delete_feeds_worker", array('relateid' => $live_info['liveid'], 'type' => Feeds::FEEDS_LIVE, 'uid' => Context::get("userid")));

        $this->render();
    }

    public function setCoverAction()
    {
        $liveid = $this->getParam("liveid") ? intval(trim($this->getParam("liveid"))) : 0;
        $cover = $this->getPost("cover") ? strip_tags(trim($this->getPost("cover"))) : "";

        Interceptor::ensureNotFalse($liveid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "liveid");
        Interceptor::ensureNotEmpty($cover, ERROR_PARAM_IS_EMPTY, "cover");
        Interceptor::ensureNotFalse(strlen($cover)<=300, ERROR_PARAM_INVALID_FORMAT, 'cover');

        $live = new Live();
        $live_info = $live->getLiveInfo($liveid);
        Interceptor::ensureNotEmpty($live_info, ERROR_BIZ_LIVE_NOT_EXIST);
        Interceptor::ensureNotFalse(Context::get("userid") == $live_info["uid"], ERROR_BIZ_LIVE_NOT_OWNER);

        try{
            $live = new Live();
            Interceptor::ensureNotFalse($live->setCover($liveid, $cover), ERROR_LIVE_SET_COVER);
        }catch(Exception $e){
            Interceptor::ensureNotFalse(false, ERROR_LIVE_SET_COVER);
        }

        include_once 'process_client/ProcessClient.php';
        ProcessClient::getInstance("dream")->addTask("live_sync_control", array('liveid'=>$liveid, 'cover'=>$cover));

        $this->render();
    }
    
    public function setLiveinfoAction()
    {
        $liveid = $this->getParam("liveid") ? intval(trim($this->getParam("liveid"))) : 0;
        $cover = $this->getParam("cover") ? strip_tags(trim($this->getParam("cover"))) : "";
        $title = $this->getParam("title") ? strip_tags(trim($this->getParam("title"))) : "";
        
        Interceptor::ensureNotFalse($liveid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "liveid");
        Interceptor::ensureNotEmpty($cover, ERROR_PARAM_IS_EMPTY, "cover");
        Interceptor::ensureNotEmpty($title, ERROR_PARAM_IS_EMPTY, "title");
        Interceptor::ensureNotFalse(strlen($cover)<=300, ERROR_PARAM_INVALID_FORMAT, 'cover');
        
        $live = new Live();
        $live_info = $live->getLiveInfo($liveid);
        Interceptor::ensureNotEmpty($live_info, ERROR_BIZ_LIVE_NOT_EXIST);
        //Interceptor::ensureNotFalse(Context::get("userid") == $live_info["uid"], ERROR_BIZ_LIVE_NOT_OWNER);
        
        try{
            $live = new Live();
            Interceptor::ensureNotFalse($live->setLiveinfo($liveid, $cover, $title), ERROR_LIVE_SET_COVER);
        }catch(Exception $e){
            Interceptor::ensureNotFalse(false, ERROR_LIVE_SET_COVER);
        }
        
        $this->render();
    }
    
    public function breakAction()
    {
        $liveid     = $this->getParam("liveid")       ? intval($this->getParam("liveid"))   : 0;
        $clean      = $this->getParam("clean")        ? trim($this->getParam("clean"))      : '';
        $operator   = $this->getParam("operator")     ? trim($this->getParam("operator"))   : '';
        $reason       = $this->getParam("reason")       ? trim($this->getParam("reason"))     : '';

        Interceptor::ensureNotEmpty($liveid, ERROR_PARAM_IS_EMPTY, "liveid");
        Interceptor::ensureNotFalse(in_array($clean, array('Y','N'), true), ERROR_PARAM_INVALID_FORMAT, 'clean');
        Interceptor::ensureNotEmpty($operator, ERROR_PARAM_IS_EMPTY, "operator");

        $live = new Live();
        $live_info = $live->getLiveInfo($liveid);

        Interceptor::ensureNotEmpty($live_info, ERROR_BIZ_LIVE_NOT_EXIST);
        Interceptor::ensureNotFalse((in_array($live_info['status'], array(Live::ACTIVING, Live::PAUSED))), ERROR_BIZ_LIVE_NOT_ACTIVE);

        $live->interrupt($liveid, $clean, $reason);

        include_once 'process_client/ProcessClient.php';
        ProcessClient::getInstance("dream")->addTask("delete_feeds_worker", array('relateid' => $live_info['liveid'], 'type' => Feeds::FEEDS_LIVE, 'uid' => Context::get("userid")));

        $this->render();
    }

    public function pauseAction()
    {
        $liveid = $this->getParam("liveid") ? intval(trim($this->getParam("liveid"))) : 0;

        Interceptor::ensureNotFalse($liveid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "liveid");

        $live = new Live();
        $live_info = $live->getLiveInfo($liveid);

        Interceptor::ensureNotEmpty($live_info, ERROR_BIZ_LIVE_NOT_EXIST);
        Interceptor::ensureNotFalse(Context::get("userid") == $live_info["uid"], ERROR_BIZ_LIVE_NOT_OWNER);
        Interceptor::ensureNotFalse($live_info["status"] == Live::ACTIVING, ERROR_BIZ_LIVE_NOT_ACTIVE);

        $live->pause($liveid);

        $this->render();
    }

    public function resumeAction()
    {
        $liveid = $this->getParam("liveid") ? intval(trim($this->getParam("liveid"))) : 0;

        Interceptor::ensureNotFalse($liveid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "liveid");

        $live = new Live();
        $live_info = $live->getLiveInfo($liveid);

        Interceptor::ensureNotEmpty($live_info, ERROR_BIZ_LIVE_NOT_EXIST);
        Interceptor::ensureNotFalse(Context::get("userid") == $live_info["uid"], ERROR_BIZ_LIVE_NOT_OWNER);
        Interceptor::ensureNotFalse($live_info["status"] == Live::PAUSED, ERROR_BIZ_LIVE_NOT_PAUSED);

        $live->resume($liveid);

        $this->render();
    }

    public function deleteAction()
    {
        $liveid = $this->getParam("liveid") ? intval(trim($this->getParam("liveid"))) : 0;

        Interceptor::ensureNotFalse($liveid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "liveid");

        $live = new Live();
        $live_info = $live->getLiveInfo($liveid);

        Interceptor::ensureNotFalse(Context::get("userid") == $live_info["uid"], ERROR_BIZ_LIVE_NOT_OWNER);

        Interceptor::ensureNotEmpty($live_info, ERROR_LIVE_NOT_EXIST);

        $live->delete($liveid);

        include_once 'process_client/ProcessClient.php';
        ProcessClient::getInstance("dream")->addTask("live_delete_control", $live_info);
        ProcessClient::getInstance("dream")->addTask("delete_feeds_worker", array('relateid' => $live_info['liveid'], 'type' => Feeds::FEEDS_LIVE, 'uid' => Context::get("userid")));


        $this->render();
    }

    public function deleteReplayAction()
    {
        $liveid = $this->getParam("liveid") ? intval(trim($this->getParam("liveid"))) : 0;

        Interceptor::ensureNotFalse($liveid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "liveid");

        $live = new Live();
        $live_info = $live->getLiveInfo($liveid);

        Interceptor::ensureNotFalse(Context::get("userid") == $live_info["uid"], ERROR_BIZ_LIVE_NOT_OWNER);

        Interceptor::ensureNotEmpty($live_info, ERROR_LIVE_NOT_EXIST);

        $live->delRepaly($liveid);

        include_once 'process_client/ProcessClient.php';
        ProcessClient::getInstance("dream")->addTask("live_delete_control", $live_info);
        ProcessClient::getInstance("dream")->addTask("delete_feeds_worker", array('relateid' => $liveid, 'type' => Feeds::FEEDS_REPLAY, 'uid' => Context::get("userid")));

        $this->render();
    }

    public function cleanAction()
    {
        $liveid     = $this->getParam("liveid")       ? intval(trim($this->getParam("liveid"))) : 0;
        $operator   = $this->getParam("operator")     ? trim($this->getParam("operator"))   : '';
        $reason       = $this->getParam("reason")       ? trim($this->getParam("reason"))     : '';

        Interceptor::ensureNotFalse($liveid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "liveid");

        $live = new Live();
        $live->clean($liveid, $reason);

        $this->render();
    }

    public function praiseAction()
    {
        $liveid = $this->getParam("liveid") ? strip_tags(trim($this->getParam("liveid")))   : 0;
        $num    = $this->getParam("num")    ? strip_tags(trim($this->getParam("num")))      : 1;
        $isfirst = $this->getParam("isfirst")    ? intval($this->getParam("isfirst"))      : 0;//是否第一次点赞0，1

        Interceptor::ensureNotEmpty($liveid, ERROR_PARAM_IS_EMPTY, "liveid");
        Interceptor::ensureNotFalse(is_numeric($liveid), ERROR_PARAM_INVALID_FORMAT, "liveid");
        Interceptor::ensureNotFalse($liveid >= 0, ERROR_PARAM_NOT_SMALL_ZERO, "liveid");
        Interceptor::ensureNotFalse(is_numeric($num), ERROR_PARAM_INVALID_FORMAT, "num");
        Interceptor::ensureNotFalse($num >= 0, ERROR_PARAM_NOT_SMALL_ZERO, "num");
        Interceptor::ensureFalse($num >= 30, ERROR_PARAM_INVALID_FORMAT, "num");

        $live       = new Live();
        $live_info  = $live->getLiveInfo($liveid);
        Interceptor::ensureNotEmpty($live_info, ERROR_BIZ_LIVE_NOT_EXIST);
        
        if ($live_info['status'] == Live::FINISHED) {
            $userid     = Context::get("userid");
            $cache = Cache::getInstance("REDIS_CONF_CACHE");
            $praise_redis_key = Live::REPLAY_PRAISE_REDIS_KEY. $liveid . "_" . $userid;
            $bool = $cache->get($praise_redis_key);
             
            if ($cache->get($praise_redis_key) > 0) {
                $total      = Counter::get(Counter::COUNTER_TYPE_LIVE_PRAISES, $liveid);
            } else {
                $cache->incr($praise_redis_key);
                $total      = Counter::increase(Counter::COUNTER_TYPE_LIVE_PRAISES, $liveid, $num);
            }
            
        } else {
            $total      = Counter::increase(Counter::COUNTER_TYPE_LIVE_PRAISES, $liveid, $num);
        }
        

        $userinfo = User::getUserInfo(Context::get("userid"));
        $watchs_total          = Counter::get(Counter::COUNTER_TYPE_LIVE_WATCHES, $liveid);
        
        if ($live_info['status'] == Live::ACTIVING) {
            $user_guard = UserGuard::getUserGuardRedis(Context::get("userid"), $live_info['uid']);
            Messenger::sendPraise($liveid, Context::get("userid"), "有人赞了你", $num, $total, $isfirst, $watchs_total, $userinfo['nickname'], $userinfo['avatar'], $userinfo['level'], intval($user_guard), $userinfo['gender'], $userinfo['vip']);
        }


        include_once "process_client/ProcessClient.php";
        ProcessClient::getInstance("dream")->addTask("live_rank_generate", array("type" => "praise", "action" => "increase", "userid" => $live_info['uid'], "score" => $num, "relateid" => 0));

        $this->render(array("praises"=>(int) $total, 'isfirst'=>$isfirst));
    }

    public function traceAction()
    {
        $liveid   = $this->getParam("liveid")   ? intval($this->getParam("liveid"))             : 0;
        $bps      = $this->getParam("bps")      ? intval($this->getParam("bps"))                : 0;
        $fps      = $this->getParam("fps")      ? intval($this->getParam("fps"))                : 0;
        $plr      = $this->getParam("plr")      ? intval($this->getParam("plr"))                : 0;
        $direct   = $this->getParam("direct")   ? trim(strip_tags($this->getParam("direct")))   : "PUSH";
        $relayip  = $this->getParam("relayip")  ? trim(strip_tags($this->getParam("relayip")))  : "";
        $lng      = $this->getParam("lng")      ? trim(strip_tags($this->getParam("lng")))      : "0.0";
        $lat      = $this->getParam("lat")      ? trim(strip_tags($this->getParam("lat")))      : "0.0";
        $location = $this->getParam("location") ? trim(strip_tags($this->getParam("location"))) : "";
        $province = $this->getParam("province") ? trim(strip_tags($this->getParam("province"))) : "";
        $city       = $this->getParam("city") ? trim(strip_tags($this->getParam("city"))) : "";
        $district = $this->getParam("district") ? trim(strip_tags($this->getParam("district"))) : "";
        $platform = $this->getParam("platform") ? trim(strip_tags($this->getParam("platform"))) : "Android";
        $brand       = $this->getParam("brand")     ? trim(strip_tags($this->getParam("brand")))     : "";
        $model       = $this->getParam("model")     ? trim(strip_tags($this->getParam("model")))     : "";
        $network  = $this->getParam("network")  ? trim(strip_tags($this->getParam("network")))  : "";
        $version  = $this->getParam("version")  ? trim(strip_tags($this->getParam("version")))  : "";
        $deviceid = $this->getParam("deviceid") ? trim(strip_tags($this->getParam("deviceid"))) : "";
        $errormsg = $this->getParam("errormsg") ? trim(strip_tags($this->getParam("errormsg"))) : "";
        $localbps = $this->getParam("localbps") ? trim(strip_tags($this->getParam("localbps"))) : "";
        $webpull = $this->getParam("webpull") ? trim(strip_tags($this->getParam("webpull"))) : "";//给网页拉流用
        $block_count   = $this->getParam("block_count")   ? intval($this->getParam("block_count")): 0;
        $block_duration   = $this->getParam("block_duration")   ? intval($this->getParam("block_duration")): 0;
        $first_open   = $this->getParam("first_open")   ? intval($this->getParam("first_open")): 0;

        if (!empty($errormsg)) {
            $this->render();
        }

        $live = new Live();
        $live_info = $live->getLiveInfo($liveid);

        if ($direct == 'PULL' && !empty($localbps) && $localbps == '9999') {
            
        } else {
            Interceptor::ensureNotFalse($live_info["status"] == Live::ACTIVING, ERROR_BIZ_LIVE_NOT_ACTIVE);
        }

        $info = array(
        'liveid'    => $liveid,
        'userid'    => Context::get("userid"),
        'bps'        => $bps,
        'fps'        => $fps,
        'plr'        => $plr,
        'direct'    => $direct,
        'relayip'    => $relayip,
        'userip'    => Util::getIP(),
        'lng'        => $lng,
        'lat'        => $lat,
        'location'    => $province.','.$city.','.$district,
        'platform'  => $platform,
        'province'    => $province,
        'city'        => $city,
        'district'    => $district,
        'version'    => $version,
        'brand'        => $brand,
        'model'        => $model,
        'network'    => $network,
        'deviceid'    => $deviceid,
        'localbps'  => $localbps,
            'webpull'=>$webpull,
            'partner'=>$live_info['partner'],
            'block_count'=>$block_count,
            'block_duration'=>$block_duration,
            'first_open'=>$first_open,
        );

        include_once 'process_client/ProcessClient.php';
        ProcessClient::getInstance("dream")->addTask("live_monitor_addpoint", $info);

        $this->render();
    }

    /**
     * 给王磊的错误上报接口
     */
    public function tracemsgAction()
    {
        $liveid   = $this->getParam("liveid")   ? intval($this->getParam("liveid"))             : 0;
        $bps      = $this->getParam("bps")      ? intval($this->getParam("bps"))                : 0;
        $fps      = $this->getParam("fps")      ? intval($this->getParam("fps"))                : 0;
        $plr      = $this->getParam("plr")      ? intval($this->getParam("plr"))                : 0;
        $direct   = $this->getParam("direct")   ? trim(strip_tags($this->getParam("direct")))   : "PUSH";
        $relayip  = $this->getParam("relayip")  ? trim(strip_tags($this->getParam("relayip")))  : "";
        $lng      = $this->getParam("lng")      ? trim(strip_tags($this->getParam("lng")))      : "0.0";
        $lat      = $this->getParam("lat")      ? trim(strip_tags($this->getParam("lat")))      : "0.0";
        $location = $this->getParam("location") ? trim(strip_tags($this->getParam("location"))) : "";
        $province = $this->getParam("province") ? trim(strip_tags($this->getParam("province"))) : "";
        $city       = $this->getParam("city")     ? trim(strip_tags($this->getParam("city"))) : "";
        $district = $this->getParam("district") ? trim(strip_tags($this->getParam("district"))) : "";
        $errormsg = $this->getParam("errormsg") ? trim(strip_tags($this->getParam("errormsg"))) : "";
        $platform = $this->getParam("platform") ? trim(strip_tags($this->getParam("platform"))) : "";
        $localbps = $this->getParam("localbps") ? trim(strip_tags($this->getParam("localbps"))) : "";

        $live = new Live();
        $live_info = $live->getLiveInfo($liveid);

        //Interceptor::ensureNotFalse($live_info["status"] == Live::ACTIVING, ERROR_BIZ_LIVE_NOT_ACTIVE);

        $info = array(
        'liveid'    => $liveid,
        'userid'    => Context::get("userid"),
        'bps'        => $bps,
        'fps'        => $fps,
        'plr'        => $plr,
        'direct'    => $direct,
        'relayip'    => $relayip,
        'userip'    => Util::getIP(),
        'lng'        => $lng,
        'lat'        => $lat,
        'location'    => $province.','.$city.','.$district,
        'errormsg'    => $errormsg,
                'localbps'  => $localbps
        );
        include_once 'process_client/ProcessClient.php';
        ProcessClient::getInstance("dream")->addTask("live_monitor_errormsg", $info);

        $this->render();
    }

    public function traceStreamAction()
    {
        $relayip  = $this->getParam("relayip")  ? trim(strip_tags($this->getParam("relayip")))  : "";
        $lng      = $this->getParam("lng")      ? trim(strip_tags($this->getParam("lng")))      : "0.0";
        $lat      = $this->getParam("lat")      ? trim(strip_tags($this->getParam("lat")))      : "0.0";
        $location = $this->getParam("location") ? trim(strip_tags($this->getParam("location"))) : "";
        $province = $this->getParam("province") ? trim(strip_tags($this->getParam("province"))) : "";
        $city       = $this->getParam("city")     ? trim(strip_tags($this->getParam("city")))     : "";
        $district = $this->getParam("district") ? trim(strip_tags($this->getParam("district"))) : "";
        $platform = $this->getParam("platform") ? trim(strip_tags($this->getParam("platform"))) : "";
        $extends  = $this->getParam("extends")  ? trim(strip_tags($this->getParam("extends")))  : "";
        
        $extends  = json_decode($extends, true);
        $uid      = Context::get("userid");
        $DAOStreamPoint = new DAOStreamPoint();
        $streamInfo = $DAOStreamPoint->getInfoByUid($uid);
        if (! empty($streamInfo['extends']) && $streamInfo['extends'] != 'null') {
            $newExtends = json_decode($streamInfo['extends'], true);
            $newExtends = json_encode(array_replace($newExtends, $extends));
        } else {
            $newExtends = json_encode($extends);
        }
        file_put_contents('/tmp/traceStream_'.date('Y-m').'.log', 'oldExtends='.$streamInfo['extends'].'  extends='.json_encode($extends).'   newExtends='.$newExtends."\n", FILE_APPEND);
        
        $info = array(
        'uid'        => Context::get("userid"),
        'relayip'    => $relayip,
        'userip'    => Util::getIP(),
        'lng'        => $lng,
        'lat'        => $lat,
        'location'    => $province.','.$city.','.$district,
            'extends'   => $newExtends,
            'addtime'   => date("Y-m-d H:i:s")
        );

        $DAOStreamPoint = new DAOStreamPoint();
        $DAOStreamPoint->addData($info);

        //require_once('process_client/ProcessClient.php');
        //ProcessClient::getInstance("dream")->addTask("stream_monitor_addpoint", $info);

        $this->render();
    }

    public function restartAction()
    {
        $sn       = $this->getParam("sn")       ? strip_tags(trim($this->getParam("sn")))       : "";
        $partner  = $this->getParam("partner")  ? strip_tags(trim($this->getParam("partner")))  : "";
        $liveid   = $this->getParam("liveid")   ? intval($this->getParam("liveid"))             : 0;

        $live = new Live();
        $live_info = $live->getLiveInfo($liveid);

        Interceptor::ensureNotFalse($live_info["status"] == Live::ACTIVING, ERROR_BIZ_LIVE_NOT_ACTIVE);

        $live->restart($liveid, $sn, $partner);

        $this->render();
    }

    public function heartBeatAction()
    {
        $liveid = $this->getParam("liveid")     ? intval(trim($this->getParam("liveid")))     : 0;
        $sn     = $this->getParam("sn")         ? trim($this->getParam("sn"))                 : '';
        $partner= $this->getParam("live_partner")     ? trim($this->getParam("live_partner")) : '';
        
        
        
        $live = new Live();
        if (empty($liveid)) {
            Interceptor::ensureNotEmpty($sn, ERROR_BIZ_LIVE_SN_NOT_EMPTY, "sn");
            Interceptor::ensureNotEmpty($partner, ERROR_BIZ_LIVE_PRTNER_NOT_EMPTY, "partner");

            $live_info = $live->getLiveInfoBySn($sn, $partner);
        } else {
            Interceptor::ensureNotFalse($liveid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "liveid");

            $live_info = $live->getLiveInfo($liveid);
        }
        
        //         if (in_array($live_info['uid'], array(10058492))) {
        //             $this->render();
        //         }
        
        Interceptor::ensureNotEmpty($live_info, ERROR_BIZ_LIVE_NOT_EXIST);

        $live->heartbeat($live_info['liveid']);

        $this->render();
    }

    /**
     * 直播录制完成回调接口
     */
    public function setReplayUrlAction()
    {
        $sn        = $this->getParam("sn")              ? trim($this->getParam("sn"))              : '';
        $url       = $this->getParam("url")          ? trim($this->getParam("url"))          : '';
        $partner   = $this->getParam("live_partner") ? trim($this->getParam("live_partner")) : '';
        $stime     = $this->getParam("startTime")    ? trim($this->getParam("startTime"))    : '';
        $etime     = $this->getParam("endTime")      ? trim($this->getParam("endTime"))      : '';

        Interceptor::ensureNotEmpty($sn, ERROR_BIZ_LIVE_SN_NOT_EMPTY, "sn");
        Interceptor::ensureNotEmpty($partner, ERROR_BIZ_LIVE_SN_NOT_EMPTY, "live_partner");
        Interceptor::ensureNotEmpty($url, ERROR_BIZ_LIVE_SN_NOT_EMPTY, "url");
        Interceptor::ensureNotEmpty($stime, ERROR_BIZ_LIVE_SN_NOT_EMPTY, "startTime");
        Interceptor::ensureNotEmpty($etime, ERROR_BIZ_LIVE_SN_NOT_EMPTY, "endTime");

        $dao_live = new DAOLive();
        $liveInfo = $dao_live->getLiveInfoBySn($sn, $partner);
        
        $status = 'N';
        if((strtotime($etime)-strtotime($stime))>240) {
            $live = new Live();
            $data = $live->addReplay($sn, $url, $partner, $stime, $etime);
            $info = array(
                'uid'      => $liveInfo['uid'],
                'type'     => Feeds::FEEDS_REPLAY,//回放
                'relateid' => $liveInfo['liveid'],
                'addtime'  => $liveInfo['addtime'],
            );
            include_once 'process_client/ProcessClient.php';
            
            //注释newsfeeds by yangqing
            //ProcessClient::getInstance("dream")->addTask("resources_add_newsfeeds", $info);
            
            ProcessClient::getInstance("dream")->addTask("live_sync_control", array('liveid'=>$liveInfo['liveid'],'replayurl'=>$url,'partner'=>$partner,'stime'=>$stime,'etime'=>$etime));
            $status = 'Y';
        }
        $option = array();
        $option['liveid']    = $liveInfo['liveid'];
        $option['sn']        = $sn;
        $option['replayurl'] = $url;
        $option['partner']   = $partner;
        $option['status']    = $status;
        $option['stime']     = $stime;
        $option['etime']     = $etime;
        $option['creatime']  = date("Y-m-d H:i:s");
        $DAOReplayurlDetails  = new DAOReplayurlDetails();
        $DAOReplayurlDetails->addData($option);
        
        $result = array('time'=>strtotime($liveInfo['endtime']) - strtotime($liveInfo['addtime']),'data'=>$data);
        $this->render($result);
    }

    public function joinRoomErrorAction()
    {
        /*{{{加入聊天室失败上报*/
        $liveid   = $this->getParam("liveid")   ? intval($this->getParam("liveid"))                  : 0;
        $platform = $this->getParam("platform") ? trim($this->getParam("platform"))                    : '';//app
        $version  = $this->getParam("version")  ? trim($this->getParam("version"))                  : '';//app
        $brand    = $this->getParam("brand")    ? trim($this->getParam("brand"))                    : 0;
        $sdkversion= $this->getParam("sdkversion")? trim(strip_tags($this->getParam("sdkversion"))) : "";//融云sdk版本
        $model    = $this->getParam("model")    ? trim(strip_tags($this->getParam("model")))          : "";
        $token    = $this->getParam("sdktoken") ? trim(strip_tags($this->getParam("sdktoken")))       : "";//融云token
        $location = $this->getParam("location") ? trim(strip_tags($this->getParam("location")))     : "";//地点
        $errormsg = $this->getParam("errormsg") ? trim(strip_tags($this->getParam("errormsg")))     : "";//融云错误码
        $errorcode= $this->getParam("errorcode")? trim(strip_tags($this->getParam("errorcode")))     : "";//融云错误信息
        $sdklog   = $this->getParam("sdklog")     ? trim(strip_tags($this->getParam("sdklog")))         : "";//融云错误信息
        $province = $this->getParam("province") ? trim(strip_tags($this->getParam("province"))) : "";
        $city       = $this->getParam("city") ? trim(strip_tags($this->getParam("city"))) : "";
        $district = $this->getParam("district") ? trim(strip_tags($this->getParam("district"))) : "";
        $network  = $this->getParam("network")  ? trim(strip_tags($this->getParam("network")))  : "";

        $live = new Live();
        $live_info = $live->getLiveInfo($liveid);

        Interceptor::ensureNotFalse($live_info["status"] == Live::ACTIVING, ERROR_BIZ_LIVE_NOT_ACTIVE);

        $info = array(
            "liveid"    => $liveid,
        "userid"    => Context::get("userid"),
            "platform"    => $platform,
            "ip"        => Util::getIP(),
            'province'    => $province,
        'city'        => $city,
        'district'    => $district,
        'version'    => $version,
        'brand'        => $brand,
        'model'        => $model,
        'network'    => $network,
            "errorcode"    => $errorcode,
        "errormsg"    => $errormsg,
        "token"        => $token,
        "location"    => $location,
        "sdkversion"=> $sdkversion,
        "loginfo"    => $sdklog
        );

        include_once 'process_client/ProcessClient.php';
        ProcessClient::getInstance("dream")->addTask("live_error_worker", $info);

        $this->render();
    }/*}}}*/
    public function joinRoomErrorNewAction()
    {
        /*{{{加入聊天室失败上报*/
        $liveid   = $this->getParam("liveid")   ? intval($this->getParam("liveid"))                  : 0;
        $platform = $this->getParam("platform") ? trim($this->getParam("platform"))                    : '';//app
        $version  = $this->getParam("version")  ? trim($this->getParam("version"))                  : '';//app
        $brand    = $this->getParam("brand")    ? trim($this->getParam("brand"))                    : 0;
        $sdkversion= $this->getParam("sdkversion")? trim(strip_tags($this->getParam("sdkversion"))) : "";//融云sdk版本
        $model    = $this->getParam("model")    ? trim(strip_tags($this->getParam("model")))          : "";
        $token    = $this->getParam("sdktoken") ? trim(strip_tags($this->getParam("sdktoken")))       : "";//融云token
        $location = $this->getParam("location") ? trim(strip_tags($this->getParam("location")))     : "";//地点
        $errormsg = $this->getParam("errormsg") ? trim(strip_tags($this->getParam("errormsg")))     : "";//融云错误码
        $errorcode= $this->getParam("errorcode")? trim(strip_tags($this->getParam("errorcode")))     : "";//融云错误信息
        $sdklog   = $this->getParam("sdklog")     ? trim(strip_tags($this->getParam("sdklog")))         : "";//融云错误信息
        $province = $this->getParam("province") ? trim(strip_tags($this->getParam("province"))) : "";
        $city       = $this->getParam("city") ? trim(strip_tags($this->getParam("city"))) : "";
        $district = $this->getParam("district") ? trim(strip_tags($this->getParam("district"))) : "";
        $network  = $this->getParam("network")  ? trim(strip_tags($this->getParam("network")))  : "";//网络
        $data     = $this->getParam('data')?trim(strip_tags($this->getParam('data'))):'';

        $live = new Live();
        $live_info = $live->getLiveInfo($liveid);

        Interceptor::ensureNotFalse($live_info["status"] == Live::ACTIVING, ERROR_BIZ_LIVE_NOT_ACTIVE);

        $info = array(
            "liveid"    => $liveid,
            "userid"    => Context::get("userid"),
            "platform"    => $platform,
            "ip"        => Util::getIP(),
            'province'    => $province,
            'city'        => $city,
            'district'    => $district,
            'version'    => $version,
            'brand'        => $brand,
            'model'        => $model,
            'network'    => $network,
            "errorcode"    => $errorcode,
            "errormsg"    => $errormsg,
            "token"        => $token,
            "location"    => $location,
            "sdkversion"=> $sdkversion,
            "loginfo"    => $sdklog,
            "data"     => $data
        );

        include_once 'process_client/ProcessClient.php';
        ProcessClient::getInstance("dream")->addTask("live_error_new_worker", $info);

        $this->render();
    }

    public function getLiveInfoByUidsAction()
    {
        /*{{{根据用户id获取直播详情*/
        $uids        = $this->getParam("uids")              ? trim($this->getParam("uids"))              : '';

        $uids = explode(',', $uids);

        $live = new Live();
        $live_info_all = $live->getLiveInfoByUids($uids);


        $this->render($live_info_all);
    }/*}}}*/

    public function replayNoTitleAction()
    {
        /*{{{回放没有字幕上报*/
        $liveid   = $this->getParam("liveid")   ? intval($this->getParam("liveid"))                  : 0;
        $indexdir = $this->getParam("indexdir") ? trim($this->getParam("indexdir"))                    : '';//app

        $info = array(
        'liveid' => $liveid,
        'indexdir'    => $indexdir
        );
        //require_once('process_client/ProcessClient.php');
        //ProcessClient::getInstance("dream")->addTask("replay_no_title", $info);

        $this->render();
    }/*}}}*/

    public function watchedAction()
    {
        $liveid     = $this->getParam("liveid") ? strip_tags(trim($this->getParam("liveid")))   : 0;



        Interceptor::ensureNotEmpty($liveid, ERROR_PARAM_IS_EMPTY, "liveid");
        Interceptor::ensureNotFalse(is_numeric($liveid), ERROR_PARAM_INVALID_FORMAT, "liveid");
        Interceptor::ensureNotFalse($liveid >= 0, ERROR_PARAM_NOT_SMALL_ZERO, "liveid");


        $live = new Live();
        $live_info = $live->getLiveInfo($liveid);

        Interceptor::ensureNotEmpty($live_info, ERROR_BIZ_LIVE_NOT_EXIST);

        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $watches_redis_key = "replay_watches_". $liveid . "_" . Context::get("userid");

        if ($cache->incr($watches_redis_key) > 1) {
            $total      = Counter::get(Counter::COUNTER_TYPE_LIVE_WATCHES, $liveid);
            $this->render(array("watches"=>(int) $total));
        }

        $total      = Counter::increase(Counter::COUNTER_TYPE_LIVE_WATCHES, $liveid, 1);

        $this->render(array("watches"=>(int) $total));
    }
    
    public function noticepushAction()
    {
        $text         = $this->getParam("text") ? strip_tags(trim($this->getParam("text")))   : '';
        $image         = $this->getParam("image") ? strip_tags(trim($this->getParam("image")))   : '';
        $sender     = $this->getParam("sender") ? strip_tags(trim($this->getParam("sender")))   : '';
        $type         = $this->getParam("type") ? strip_tags(trim($this->getParam("type")))   : 'big';
        
        $info = array(
        "text"    => $text,
        "image"    => $image,
        "sender"=> $sender,
        "type"  => $type,
        );
        
        include_once 'process_client/ProcessClient.php';
        ProcessClient::getInstance("dream")->addTask("admin_notice_worker", $info);
        
        $this->render();
    }
    
    public function notifyAction()
    {
        $sn        = $this->getParam("sn")              ? trim($this->getParam("sn"))              : '';
        $url       = $this->getParam("url")          ? trim($this->getParam("url"))          : '';
        $stime     = $this->getParam("startTime")    ? trim($this->getParam("startTime"))    : '';
        $etime     = $this->getParam("endTime")      ? trim($this->getParam("endTime"))      : '';
        
        Interceptor::ensureNotEmpty($sn, ERROR_BIZ_LIVE_SN_NOT_EMPTY, "sn");
        Interceptor::ensureNotEmpty($url, ERROR_BIZ_LIVE_SN_NOT_EMPTY, "url");
        Interceptor::ensureNotEmpty($stime, ERROR_BIZ_LIVE_SN_NOT_EMPTY, "startTime");
        Interceptor::ensureNotEmpty($etime, ERROR_BIZ_LIVE_SN_NOT_EMPTY, "endTime");
        $url = str_replace(array(WSStream::CN_REPALY,WSStream::US_REPALY,CCStream::CN_REPALY,CCStream::US_REPALY), '', $url);
        
        $partner  = substr($sn, 0, 2);
        $partner  = strtolower($partner);
        $dao_live = new DAOLive();
        $liveInfo = $dao_live->getLiveInfoBySn($sn, $partner);
        Interceptor::ensureNotEmpty($liveInfo, ERROR_BIZ_LIVE_NOT_EXIST, "sn");
        file_put_contents('/tmp/notify_'.date('Y-m').'.log', 'sn='.$sn.' partner='.$partner.'  url='.$url.'  stime='.$stime.'  etime='.$etime."\n", FILE_APPEND);
        
        $status = 'N';
        if((intval($etime)-intval($stime))>240 && $liveInfo['replay'] == 'Y' && $liveInfo['record'] == 'Y') {
            $live = new Live();
            $data = $live->addReplay($sn, $url, $partner, date('Y-m-d H:i:s', $stime), date('Y-m-d H:i:s', $etime));
            $info = array(
                'uid'      => $liveInfo['uid'],
                'type'     => Feeds::FEEDS_REPLAY,//回放
                'relateid' => $liveInfo['liveid'],
                'addtime'  => $liveInfo['addtime'],
            );
            include_once 'process_client/ProcessClient.php';
            
            //注释newsfeeds by yangqing
            //ProcessClient::getInstance("dream")->addTask("resources_add_newsfeeds", $info);
            
            ProcessClient::getInstance("dream")->addTask("live_sync_control", array('liveid'=>$liveInfo['liveid'],'replayurl'=>$url,'partner'=>$partner,'stime'=>$stime,'etime'=>$etime));
            $status = 'Y';
        }
        $option = array();
        $option['liveid']    = $liveInfo['liveid'];
        $option['sn']        = $sn;
        $option['replayurl'] = $url;
        $option['partner']   = $partner;
        $option['status']    = $status;
        $option['stime']     = date('Y-m-d H:i:s', $stime);
        $option['etime']     = date('Y-m-d H:i:s', $etime);
        $option['creatime']  = date("Y-m-d H:i:s");
        $DAOReplayurlDetails  = new DAOReplayurlDetails();
        $DAOReplayurlDetails->addData($option);
        
        $this->render();
    }
    
    public function authorityAction()
    {
        $ABSTime  = $this->getParam("ABSTime") ? trim($this->getParam("ABSTime"))   : '';
        $secret   = $this->getParam("secret")  ? trim($this->getParam("secret"))    : '';
        $sn       = $this->getParam("sn")      ? trim($this->getParam("sn"))        : '';
        $point    = $this->getParam("point")   ? trim($this->getParam("point"))     : '';
        $domain   = $this->getParam("domain")  ? trim($this->getParam("domain"))    : '';
        
        $partner  = substr($sn, 0, 2);
        $partner  = strtolower($partner);
        $url      = 'rtmp://'.$domain.'/'.$point.'/'.$sn;
        file_put_contents('/tmp/streamws_'.date('Y-m').'.log', 'sn='.$sn.' partner='.$partner.'  ABSTime='.$ABSTime.'  secret='.$secret.'  point='.$point.'    domain='.$domain."\n", FILE_APPEND);

        $list = array(
            'WS_1510732897_20025370_6702.7e6a',
            'WS_1516586801_20025370_6146.fc46',
            'WS_1516586869_20025370_9278.2025',
            'WS_1517471652_20025370_9717.2a81'
        );
        if(in_array($sn, $list)) {
            $this->render();
        }
        
        $stream = new Stream();
        Interceptor::ensureNotFalse(($stream->isValidSN($sn)), ERROR_BIZ_LIVE_SN_ERROR, "sn");
        Interceptor::ensureNotFalse((hexdec($ABSTime)>time()), AUTHORITY_WS_TIME_ERROR, "ABSTime");
        Interceptor::ensureNotFalse(($secret == md5($url . StreamDispatch::getPartnerSecret($partner))), AUTHORITY_WS_SECRET_ERROR, "wsSecret");
        

        $live   = new DAOLive();
        $isLive = $live->isLiveAuthorityStatus($sn, $partner);
        $authority = StreamDispatch::getRedis($partner, $secret, $ABSTime);
        file_put_contents('/tmp/streamws_'.date('Y-m').'.log', 'isLive='.print_r($isLive, true).'  authority='.print_r($authority, true)."\n", FILE_APPEND);
        Interceptor::ensureNotFalse(($isLive || $authority), AUTHORITY_WS_LIVE_ERROR, "fail");
        
        $this->render();
    }
    
    public function streamSpeedAction()
    {
        $partner = $this->getParam("partners") ? trim($this->getParam("partners")) : '';
        $region  = $this->getParam("region")  ? trim($this->getParam("region"))  : '';
        $uid     = $this->getParam("uid")     ? trim($this->getParam("uid"))     : '';
        $ip      = $this->getParam("ip")      ? trim($this->getParam("ip"))      : '';
        $liveid  = $this->getParam("liveid")  ? trim($this->getParam("liveid"))  : '';
        
        Interceptor::ensureNotEmpty($partner, ERROR_BIZ_LIVE_SN_NOT_EMPTY, "partner");
        Interceptor::ensureNotEmpty($region, ERROR_BIZ_LIVE_SN_NOT_EMPTY, "region");
        Interceptor::ensureNotEmpty($uid, ERROR_BIZ_LIVE_SN_NOT_EMPTY, "uid");
        Interceptor::ensureNotEmpty($ip, ERROR_BIZ_LIVE_SN_NOT_EMPTY, "ip");
        Interceptor::ensureNotEmpty($liveid, ERROR_BIZ_LIVE_SN_NOT_EMPTY, "liveid");
            
        // 获取sn
        $stream = new Stream();
        $stream->setPartner($uid, $partner);
        list ($sn, $partnersn) = $stream->getStream($uid);
        
        // 判断是否具有录制的权限
        $live   = new Live();
        $replay = $live->isPeplayPermissions($uid);
        $privacyReplay  = Privacy::getPrivacyReplay($uid);
        if($replay || $privacyReplay) {
            $replay = true;
        }
        $rtmp   = $stream->getRTMPUrl($sn, $partner, $replay);
        
        $speed  = StreamDispatch::getDispatch($region, $uid, $ip, $partner);
        $stream = StreamDispatch::getDispatchStream($region, $rtmp, $partner, $ip);
        $stream[] = $rtmp;
        
        file_put_contents('/tmp/stream_speed_'.date('Y-m').'.log', 'liveid='.$liveid.'   uid='.$uid.'   data='.json_encode($stream)."\n", FILE_APPEND);
        Messenger::sendUserStreamSpeedNodes($uid, $speed, array_values($stream), $sn, $liveid);
        $this->render();
    }
    
    public function changeSNAction()
    {
        $liveid     = $this->getParam("liveid")   ? intval($this->getParam("liveid"))             : 0;
        $sn         = $this->getParam("sn")       ? strip_tags(trim($this->getParam("sn")))       : "";
        $partner    = $this->getParam("partner")  ? strip_tags(trim($this->getParam("partner")))  : "";
        
        Interceptor::ensureNotEmpty($liveid, ERROR_BIZ_LIVE_SN_NOT_EMPTY, "liveid");
        Interceptor::ensureNotEmpty($sn, ERROR_BIZ_LIVE_SN_NOT_EMPTY, "sn");
        Interceptor::ensureNotEmpty($partner, ERROR_BIZ_LIVE_SN_NOT_EMPTY, "partner");
        
        //设置sn
        $live = new Live();
        $live->setPartnerSn($liveid, $sn, $partner);
        $liveInfo = $live->getLiveInfo($liveid);
        
        //发送消息
        $stream = new Stream();
        $replay = ($liveInfo['record'] == 'Y') ? true : false;
        $flv    = $stream->getFLVUrl($sn, $partner, $liveInfo['region'], $replay);
        $hls    = $stream->getHLSUrl($sn, $partner, $liveInfo['region'], $replay);
        $data = array(
            "sn" => $sn,
            "partner" => $partner,
            "flv" => $flv,
            "hls" => $hls
        );
        
        file_put_contents('/tmp/change_sn_'.date('Y-m').'.log', 'liveid='.$liveid.'   uid='.$liveInfo['uid'].'   data='.json_encode($data)."\n", FILE_APPEND);
        Messenger::sendToGroup($liveid, Messenger::MESSAGE_TYPE_CHANGE_SN, $liveInfo['uid'], '切换流地址', $data);
        $this->render();
    }
    
    
    public function getLiveListAction()
    {
        $uid      = $this->getParam("uid")       ? intval($this->getParam("uid"))           : 0;
        $startime = $this->getParam("startime")  ? intval($this->getParam("startime"))      : 0;
        $endtime  = $this->getParam("endtime")   ? intval($this->getParam("endtime"))       : 0;
        $num      = $this->getParam("num")       ? strip_tags(trim($this->getParam("num"))) : 10;
        $offset   = $this->getParam("offset")    ? (int)($this->getParam("offset"))         : 0;
        
        Interceptor::ensureNotEmpty($uid, ERROR_BIZ_LIVE_SN_NOT_EMPTY, "uid");
        
        $startime = $startime > 0 ? date('Y-m-d', $startime).' 00:00:00' : '';
        $endtime  = $endtime  > 0 ? date('Y-m-d', intval($endtime)+86400).' 00:00:00'  : '';
        $Live = new Live();
        $result = $Live->getUserLiveList($uid, $startime, $endtime, $num, $offset);
        $this->render($result);
    }
}
?>
