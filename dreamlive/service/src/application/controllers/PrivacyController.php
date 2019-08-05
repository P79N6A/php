<?php
class PrivacyController extends BaseController
{

    /**
     * 获取私密房间配置私密房间直播开始
     */
    public function getConfigAction()
    {
        $uid = Context::get('userid');

        $result = Privacy::getConfig($uid);

        $this->render($result);
    }

    /**
     * 私密房间直播开始
     */
    public function startAction()
    {
        $liveid         = $this->getParam("liveid")   ? intval(trim($this->getParam("liveid")))         : 0;
        $sn             = $this->getParam("sn")       ? strip_tags(trim($this->getParam("sn")))         : "";
        $partner        = $this->getParam("partner")  ? strip_tags(trim($this->getParam("partner")))    : "";
        $title          = $this->getParam("title")    ? strip_tags(trim($this->getParam("title")))      : "";
        $width          = $this->getParam("width")    ? intval($this->getParam("width"))                : 0;
        $height         = $this->getParam("height")   ? intval($this->getParam("height"))               : 0;
        $point          = $this->getParam("point")    ? strip_tags(trim($this->getParam("point")))      : "0.0";
        $province       = $this->getParam("province") ? strip_tags(trim($this->getParam("province")))   : "";
        $city           = $this->getParam("city")     ? strip_tags(trim($this->getParam("city")))       : "";
        $district       = $this->getParam("district") ? strip_tags(trim($this->getParam("district")))   : "";
        $location       = $this->getParam("location") ? strip_tags(trim($this->getParam("location")))   : "";
        $virtual        = $this->getParam("virtual")  ? strip_tags(trim($this->getParam("virtual")))    : "N";
        $position       = $this->getParam("position") ? strip_tags(trim($this->getParam("position")))   : "Y";
        $replay         = $this->getParam("replay")   ? strip_tags(trim($this->getParam("replay")))     : "N";
        $cover          = $this->getParam("cover")    ? strip_tags(trim($this->getParam("cover")))      : "";

        $live_price     = $this->getParam("live_price")     ? strip_tags(trim($this->getParam("live_price")))       : "";
        $replay_price   = $this->getParam("replay_price")     ? strip_tags(trim($this->getParam("replay_price")))        : 50;
        $paytime            = $this->getParam("paytime")         ? strip_tags(trim($this->getParam("paytime")))           : "";
        $paylong         = $this->getParam("paylong")           ? strip_tags(trim($this->getParam("paylong")))             : "";
        $preview          = $this->getParam("preview")        ? strip_tags(trim($this->getParam("preview")))          : "Y";
        $freetime          = $this->getParam("freetime")        ? strip_tags(trim($this->getParam("freetime")))          : 0;

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

        Interceptor::ensureNotFalse(Privacy::getPrivacy($uid), ERROR_BIZ_PRIVACY_PERMISSION);
        Interceptor::ensureNotFalse($replay_price > 0, ERROR_PARAM_NOT_SMALL_ZERO, "replay_price");
        Interceptor::ensureNotFalse($paylong > 0, ERROR_PARAM_NOT_SMALL_ZERO, "paylong");
        Interceptor::ensureFalse(preg_match("/^\d+\.\d+$/", $paylong) > 0, ERROR_PARAM_NOT_HAS_POINT, "paylong");
        Interceptor::ensureNotFalse($live_price > 0, ERROR_PARAM_NOT_SMALL_ZERO, "live_price");
        Interceptor::ensureNotFalse($replay_price > 0, ERROR_PARAM_NOT_SMALL_ZERO, "replay_price");
        Interceptor::ensureFalse(preg_match("/^\d+\.\d+$/", $live_price) > 0, ERROR_PARAM_NOT_HAS_POINT, "live_price");
        Interceptor::ensureFalse(preg_match("/^\d+\.\d+$/", $replay_price) > 0, ERROR_PARAM_NOT_HAS_POINT, "replay_price");
        Interceptor::ensureNotFalse(preg_match("/-?[\d\.]+,-?[\d\.]+/", $point) > 0, ERROR_PARAM_INVALID_FORMAT, "point");
        Interceptor::ensureNotFalse(preg_match("/^\d{4}\-\d{2}\-\d{2}\s+\d{2}:\d{2}:\d{2}$/i", $paytime) > 0, ERROR_PARAM_INVALID_FORMAT, "paytime");
        Interceptor::ensureNotFalse(in_array($virtual, array("Y","N"), true), ERROR_PARAM_INVALID_FORMAT, "virtual");
        //Interceptor::ensureNotFalse($freetime > 0, ERROR_PARAM_NOT_SMALL_ZERO, "freetime");

        $live = new Live();
        $live_info = $live->getLiveInfo($liveid);

        if (!empty($live_info)) {
            $dao_privacy = new DAOPrivacy();
            $privacyInfo = $dao_privacy->repeatPrivacyLive($uid, $paytime);

            if (empty($privacyInfo)) {
                $privacyid = $dao_privacy->addPrivacy($uid, $live_price, $replay_price, $paytime, $paylong, $preview, $freetime);
                Privacy::setPrivacyToRedis($privacyid);
            } else {
                $privacyid     = $privacyInfo['privacyid'];
                Privacy::setPrivacyToRedis($privacyid, $privacyInfo);
            }

            $cache = Cache::getInstance("REDIS_CONF_CACHE");
            $privacy_cache_key = Privacy::PRIVAACY_CACHE . $liveid;
            $cache->set($privacy_cache_key, $privacyid);

            //添加私密直播
            Privacy::setPrivacyToLive($privacyid, $live_info['privacy'], $live_info['liveid']);
            $live->_reload($liveid);

            $result = array(
                "live" => array(
                    "liveid" => $liveid
                ),
                "ticket" => Task::makeTicket(Context::get("userid"), Task::TASK_ID_LIVE_START, strtotime($live_info['addtime'])),
            );

            if (!empty($privacyid)) {
                $result['live']['privacyid'] = $privacyid;
            }

            $this->render($result);
        }

        //------------------客户端同时调多次解决办法--------------------
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $start_only_once_redis_key = "live_start_only_start_once_". $liveid;

        if ($cache->INCR($start_only_once_redis_key) > 1) {
            $dao_privacy = new DAOPrivacy();

            $privacyInfo = $dao_privacy->repeatPrivacyLive($uid, $paytime);

            if (empty($privacyInfo)) {
                $privacyid = $dao_privacy->addPrivacy($uid, $live_price, $replay_price, $paytime, $paylong, $preview, $freetime);
                Privacy::setPrivacyToRedis($privacyid);
            } else {
                $privacyid     = $privacyInfo['privacyid'];
                Privacy::setPrivacyToRedis($privacyid, $privacyInfo);
            }

            $cache = Cache::getInstance("REDIS_CONF_CACHE");
            $privacy_cache_key = Privacy::PRIVAACY_CACHE . $liveid;
            $cache->set($privacy_cache_key, $privacyid);

            $result = array(
                "live" => array(
                    "liveid" => $liveid,
                    "privacyid"    => $privacyid
                ),
                "ticket" => Task::makeTicket(Context::get("userid"), Task::TASK_ID_LIVE_START, strtotime($live_info['addtime'])),
            );
        }
        $cache->expire($start_only_once_redis_key, 36000);
        //------------------客户端同时调多次解决办法--------------------
        $privacy = new Privacy();
        $result = $privacy->add(
            $liveid, $uid, $sn, $partner, $title, $width, $height, $point, $province,
            $city, $district, $location, $virtual, $extends, $replay, $cover, $live_price, $replay_price, $paytime, $paylong, $preview, $freetime
        );
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

        $this->render($result);
    }

    /**
     * 购买
     */
    public function buyAction()
    {
        $privacyid     = $this->getParam("privacyid")  ? intval(trim($this->getParam("privacyid")))     : 0;
        $liveid     = $this->getParam("liveid")      ? intval(trim($this->getParam("liveid")))         : 0;
        $buyer         = Context::get("userid");
        $deviceid     = trim(strip_tags($this->getParam("deviceid")));
        $user_ip     = Util::getIP();

        //验证
        Interceptor::ensureNotFalse($privacyid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "privacyid");
        Interceptor::ensureNotFalse($buyer > 0, ERROR_PARAM_NOT_SMALL_ZERO, "userid");


        $result = Privacy::buy($privacyid, $buyer, $liveid, $user_ip, $deviceid);

        $this->render();
    }

    /**
     * 删除（后台操作）
     */
    public function removeAction()
    {
        $privacyid     = $this->getParam("privacyid")  ? intval(trim($this->getParam("privacyid")))     : 0;


        //验证
        Interceptor::ensureNotFalse($privacyid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "privacyid");


        $result = Privacy::remove($privacyid);

        $this->render();
    }

    /**
     * 延长收费时间
     */
    public function delayAction()
    {
        $privacyid = $this->getParam("privacyid")  ? intval(trim($this->getParam("privacyid"))) : 0;
        $delaytime = $this->getParam("delaytime")  ? intval(trim($this->getParam("delaytime"))) : 0;
        $uid       = Context::get("userid");

        Interceptor::ensureNotFalse($privacyid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "privacyid");
        Interceptor::ensureNotFalse($delaytime > 0, ERROR_PARAM_INVALID_FORMAT, "delaytime");
        //Interceptor::ensureNotFalse($delaytime < 30*60, ERROR_PARAM_INVALID_FORMAT, "delaytime");

        $result = Privacy::addTime($privacyid, $uid, $delaytime);
        Interceptor::ensureNotFalse($result, ERROR_BIZ_PRIVACY_CHANGE_FAILE);

        $this->render();
    }

    /**
     * 修改回放价格
     */
    public function modifyPriceAction()
    {
        $privacyid         = $this->getParam("privacyid")      ? intval(trim($this->getParam("privacyid")))         : 0;
        $replay_price   = $this->getParam("replay_price")   ? floatval(trim($this->getParam("replay_price")))     : 0;
        $uid               = Context::get("userid");

        Interceptor::ensureNotFalse($privacyid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "privacyid");
        //Interceptor::ensureNotFalse($replay_price > 0, ERROR_PARAM_INVALID_FORMAT, "replay_price");

        $result = Privacy::modifyPrice($privacyid, $uid, $replay_price);
        Interceptor::ensureNotFalse($result, ERROR_BIZ_PRIVACY_CHANGE_FAILE);

        $dao_privacy = new DAOPrivacy();
        $privacy = $dao_privacy->getPrivacyInfoById($privacyid);

        $DAOLive = new DAOLive();
        $privacyList = $DAOLive->getListByPrivacy($uid, $privacy['startime'], $privacy['endtime']);
        $live = new Live();
        foreach($privacyList as $item){
            Privacy::setPrivacyToLive($privacyid, json_decode($item['privacy'], true), $item['liveid']);
            $live->_reload($item['liveid']);
        }

        $this->render($privacy);
    }

    /**
     * 退款，后台调用
     */
    public function refundAction()
    {
        $id             = $this->getParam("id")      ? intval(trim($this->getParam("id")))         : 0;
        $reason           = $this->getParam("reason")   ? trim($this->getParam("reason"))     : '';
        Interceptor::ensureNotFalse($id > 0, ERROR_PARAM_NOT_SMALL_ZERO, "id");
        Interceptor::ensureNotEmpty($reason, ERROR_PARAM_IS_EMPTY, 'reason');

        $privacy_model = new Privacy();
        $privacy_model->refund($id, $reason);

        $this->render();
    }

    /**
     * 预览
     */
    public function PreviewAction()
    {
        $privacyid = $this->getParam("privacyid")  ? intval(trim($this->getParam("privacyid"))) : 0;
        $uid       = Context::get("userid");

        Interceptor::ensureNotFalse($privacyid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "privacyid");

        Privacy::preview($privacyid, $uid);

        $this->render();
    }

    /**
     * 检查私密权限
     */
    public function checkHadPrivacyRoomRightsAction()
    {
        $uid       = Context::get("userid");

        Interceptor::ensureNotFalse($uid> 0, ERROR_PARAM_NOT_SMALL_ZERO, "userid");

        $result = Privacy::getConfig($uid);

        Interceptor::ensureNotEmpty($result, ERROR_BIZ_CHATROOM_NO_PRIVACY_RIGHTS, "userid");

        $this->render($result);
    }

    /**
     * 修改房间为私密房间
     */
    public function addPrivacyAction()
    {
        $liveid         = $this->getParam("liveid")           ? intval(trim($this->getParam("liveid")))                 : 0;
        $live_price     = $this->getParam("live_price")     ? strip_tags(trim($this->getParam("live_price")))       : "";
        $replay_price   = $this->getParam("replay_price")     ? strip_tags(trim($this->getParam("replay_price")))        : 50;
        $paytime            = $this->getParam("paytime")         ? strip_tags(trim($this->getParam("paytime")))           : date('Y-m-d H:i:s');
        $paylong         = $this->getParam("paylong")           ? strip_tags(trim($this->getParam("paylong")))             : "";
        $preview          = $this->getParam("preview")        ? strip_tags(trim($this->getParam("preview")))          : "N";
        $freetime          = $this->getParam("freetime")        ? strip_tags(trim($this->getParam("freetime")))          : 0;
        $userid            = Context::get("userid");
        Interceptor::ensureNotFalse($liveid> 0, ERROR_PARAM_NOT_SMALL_ZERO, "liveid");
        Interceptor::ensureNotFalse($userid> 0, ERROR_PARAM_NOT_SMALL_ZERO, "userid");
        Interceptor::ensureNotFalse($live_price> 0, ERROR_PARAM_NOT_SMALL_ZERO, "live_price");
        Interceptor::ensureNotFalse($replay_price> 0, ERROR_PARAM_NOT_SMALL_ZERO, "replay_price");

        $privateRoomConfig = Privacy::getPrivateRoomConfig();
        if(!empty($privateRoomConfig[$paylong]) && $live_price < $privateRoomConfig[$paylong]['live_price']) {
            $live_price = $privateRoomConfig[$paylong]['live_price'];
        }

        $live = new Live();
        $live_info = $live->getLiveInfo($liveid);

        if (!empty($live_info)) {
            $dao_privacy = new DAOPrivacy();
            $privacyInfo = $dao_privacy->repeatPrivacyLive($userid, $paytime);

            if (empty($privacyInfo)) {
                $privacyid = $dao_privacy->addPrivacy($userid, $live_price, $replay_price, $paytime, $paylong, $preview, $freetime);
                //$num = Counter::get(Counter::COUNTER_TYPE_LIVE_PRIVACY_WATCHES, $privacyid);
                //Counter::set(Counter::COUNTER_TYPE_LIVE_WATCHES, $liveid, $num);
            } else {
                $privacyid     = $privacyInfo['privacyid'];
                //Counter::set(Counter::COUNTER_TYPE_LIVE_WATCHES, $liveid, 0);
            }

            $cache = Cache::getInstance("REDIS_CONF_CACHE");
            $privacy_cache_key = Privacy::PRIVAACY_CACHE . $liveid;
            $cache->set($privacy_cache_key, $privacyid);

            Privacy::setPrivacyToLive($privacyid, $live_info['privacy'], $liveid);
            $live->_reload($liveid);


            //删除在线列表
            $cache = Cache::getInstance("REDIS_CONF_CACHE");
            $cache->delete('audience_' . $liveid);

            //发送消息
            Messenger::sendLivePrivacy($liveid, $userid, '私密开播');

            $privacyInfo = $dao_privacy->getPrivacyInfoById($privacyid);
        }

        $this->render($privacyInfo);

    }

}
