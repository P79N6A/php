<?php
class Gift
{
    const GIFT_RED_PACKET=array(
        'giftid'=>1000000000,//十亿
        'name'=>'红包',
        'label'=>'',
        'type'=>10000,//
        //'image'=>'http://static.dreamlive.tv/images/f768eafff4435ccf598862d50b2f4bbc.jpg',
        'image'=>'http://static.dreamlive.tv/images/9b7c8504eb97657b54dc03cc8221df80.png',
        'url'=>'',
        'zip_md5'=>'',
        'consume'=>"",
        'price'=>0,
        'ticket'=>0,
        'score'=>0,
        'extends'=>'',
        'addtime'=>'',
        'modtime'=>'',
        'tag'=>"",
        'region'=>'china',
    );



    public function send($sender, $receiver, $liveid, $giftid, $consume, $price, $ticket, $num, $gift_name,$isBagGift=false)
    {
        Interceptor::ensureNotFalse(PaymentCheckTester::ruleCheckAction('checker', [[__CLASS__, __FUNCTION__], func_get_args()]), ERROR_BIZ_PAYMENT_TRADE_LIMIT);
        Interceptor::ensureNotFalse(Active::check($sender, $receiver), ERROR_BIZ_PAYMENT_ACTIVE_LIMIT);
        Interceptor::ensureNotFalse(in_array($consume, array(DAOGift::GIFT_CONSUME_COIN, DAOGift::GIFT_CONSUME_DIAMOND)), ERROR_BIZ_GIFT_NOT_FIND);
        Interceptor::ensureFalse(($isBagGift&&$consume==DAOGift::GIFT_CONSUME_COIN), ERROR_CUSTOM, "Sorry,don't support star gift in bag at the moment!");

        if ($liveid) {
            //不在直播中或者暂停，不能送礼
            $live_dao=new DAOLive();
            $live_info=$live_dao->isLiveStatus($receiver);
            Interceptor::ensureNotFalse($live_info==true, ERROR_BIZ_LIVE_NOT_ACTIVE);
        }
        
        $account = new Account();
        $dao_gift_log = new DAOGiftLog();
        $gift_star_log_dao=new DAOGiftStarLog();
        //if($consume==DAOGift::GIFT_CONSUME_DIAMOND){
        try {
            $dao_gift_log->startTrans();

            $sysAccount7000=self::use7001($giftid)?Account::ACTIVE_ACCOUNT7001:Account::ACTIVE_ACCOUNT7000;
            //$bagGiftTradeType=Account::TRADE_TYPE_BAG_GIFT;
            $sysToSenderTradeType=Account::TRADE_TYPE_BAG_GIFT_7000;
            $senderToReceiverTradeType=Account::TRADE_TYPE_BAG_GIFT_SEND;
            $starGiftTradeType=Star::TYPE_GIFT;
            //$privateGiftTradeType=Account::TRADE_TYPE_PRIVATE_GIFT_SEND;
                
            //$senderToReceiverTradeType=$bagGiftTradeType;
            $coinOutType=$consume==DAOGift::GIFT_CONSUME_DIAMOND?Account::CURRENCY_DIAMOND:Account::CURRENCY_COIN;
            $coinInType=$consume==DAOGift::GIFT_CONSUME_DIAMOND?Account::CURRENCY_TICKET:Account::CURRENCY_STAR;

            $orderid = $account->getOrderId($sender);
            $total_diamond = $price * $num;
            $total_ticket  = $ticket * $num;
                
            $extends = [
                'sender' => $sender,
                'receiver' => $receiver,
                'giftid' => $giftid,
                'gift_name' => $gift_name,
                'price' => $price,
                'ticket' => $ticket,
                'num' => $num,
                'liveid' => $liveid,
                'bag_gift'=>$isBagGift,
                'private_gift'=>!$liveid?true:false,
            ];
            $consumeStr=$consume==DAOGift::GIFT_CONSUME_DIAMOND?"星钻":"星光";
            $remark="用户:[{$sender}]发送礼物:[{$gift_name}]给:[{$receiver}],礼物单价:[{$price}],币种:[{$consumeStr}],数量:[{$num}]个,[礼物id:{$giftid}][liveid:{$liveid}]";
            $remark=!$liveid?$remark.",私信礼物":$remark;

            if ($isBagGift&&$consume==DAOGift::GIFT_CONSUME_DIAMOND) {
                /*if (!$liveid){
                    $sysToSenderTradeType=$privateGiftTradeType;
                }*/
                /*if ($consume==DAOGift::GIFT_CONSUME_COIN){
                    $sysToSenderTradeType=$starGiftTradeType;
                }*/
                $payAccountBalance = $account->getBalance($sysAccount7000, $coinOutType);
                Interceptor::ensureNotEmpty($payAccountBalance, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
                Interceptor::ensureNotFalse($payAccountBalance>=$total_diamond, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
                //1.  7000账户减去600钻（礼物价值）
                $account->decrease($sysAccount7000, $sysToSenderTradeType, $orderid, $total_diamond, $coinOutType, $remark.",背包礼物扣除{$sysAccount7000}账户{$total_diamond}", $extends);
                //2.  sender账户加600钻（礼物价值）
                $account->increase($sender, $sysToSenderTradeType, $orderid, $total_diamond, $coinOutType, $remark.",背包礼物增加送礼者{$sender}{$total_diamond}", $extends);
                //3.  sender账户减去600钻（礼物价值）

                //4.  receiver账户加600票

                $remark.=',背包礼物';
            }

            $diamond = $account->getBalance($sender, $coinOutType);
            Interceptor::ensureNotEmpty($diamond, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            Interceptor::ensureNotFalse($diamond>=$total_diamond, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);

            $t_type=$isBagGift?$senderToReceiverTradeType:ACCOUNT::TRADE_TYPE_GIFT;
            /*if (!$liveid){
                $t_type=$privateGiftTradeType;
            }*/
            if ($consume==DAOGift::GIFT_CONSUME_COIN) {
                $t_type=$starGiftTradeType;
            }
            $account->decrease($sender, $t_type, $orderid, $total_diamond, $coinOutType, $remark, $extends);
            $account->increase($receiver, $t_type, $orderid, $total_ticket, $coinInType, $remark, $extends);

            //礼物折损给官方账户
            if($consume==DAOGift::GIFT_CONSUME_DIAMOND&&$total_diamond>$total_ticket) {
                $total_price_profit = ($total_diamond-$total_ticket)/10;
                $unit_price_profit = ($price-$ticket)/10;
                $sysRemark="用户{$sender}发送礼物给{$receiver},礼物阻力系统账户盈利{$total_price_profit}现金，单个礼物盈利{$unit_price_profit}, 总收益礼物数{$num}".($isBagGift?",背包礼物":"");
                Interceptor::ensureNotFalse($account->increase(ACCOUNT::COMAPNY_ACCOUNT, $t_type, $orderid, $total_price_profit, ACCOUNT::CURRENCY_CASH, $sysRemark, array()), ERROR_BIZ_PAYMENT_TICKET_ACCOUNTED_FOR);
            }

            if ($consume==DAOGift::GIFT_CONSUME_DIAMOND) {
                $dao_gift_log->add($orderid, $sender, $receiver, $giftid, $liveid, DAOGift::GIFT_CONSUME_DIAMOND, $price, $ticket, $num);
            }elseif ($consume==DAOGift::GIFT_CONSUME_COIN) {
                $gift_star_log_dao->add($orderid, $sender, $receiver, $liveid, $giftid, GiftStarLog::CONSUME_STAR, $price, $ticket, $num);
            }

            static::giftSendAfter();

            $dao_gift_log->commit();
        } catch (Exception $e) {
            $dao_gift_log->rollback();
            throw $e;
        }

        /*} elseif ($consume==DAOGift::GIFT_CONSUME_COIN){
            $this->sendStarGift($sender,$receiver,$liveid,$giftid,$price,$ticket,$num,$gift_name);
        }*/

    }

    //hook 不要删除
    public static function giftSendAfter()
    {
    }
    /**
     * 赠送星光礼物 废弃
     */
    public function sendStarGift($sender,$receiver,$liveid,$giftid,$price,$ticket,$num,$gift_name='')
    {
        Interceptor::ensureNotFalse(
            PaymentCheckTester::ruleCheckAction(
                'checker',
                [[__CLASS__, __FUNCTION__], func_get_args()]
            ),
            ERROR_BIZ_PAYMENT_TRADE_LIMIT
        );

        //Logger::debug("HHHHH:sendStarGift",[$sender,$liveid,$receiver,$giftid,$price,$ticket,$num]);
        $orderid=Account::getOrderId($sender);

        $amount_send=$price*$num;
        $amount_receive=$ticket*$num;

        $account_sender_dao=new DAOAccount($sender);
        $balance=$account_sender_dao->getBalance(Account::CURRENCY_COIN);
        Interceptor::ensureNotFalse($balance>0, ERROR_BIZ_PAYMENT_ACCOUNT_BALANCE_LACK, "余额不足1");
        Interceptor::ensureNotFalse($balance>=$amount_send, ERROR_BIZ_PAYMENT_ACCOUNT_BALANCE_LACK, "余额不足2");

        $account_receiver_dao=new DAOAccount($receiver);
        $gift_star_log_dao=new DAOGiftStarLog();
        $star_journal_sender_dao=new DAOStarJournal($sender);
        $star_journal_receiver_dao=new DAOStarJournal($receiver);

        try{
            $gift_star_log_dao->startTrans();
            $extends = [
                'sender' => $sender,
                'receiver' => $receiver,
                'giftid' => $giftid,
                'gift_name' => $gift_name,
                'price' => $price,
                'ticket' => $ticket,
                'num' => $num,
                'liveid' => $liveid,
            ];
            $_extends=json_encode($extends);
            Interceptor::ensureNotFalse($account_sender_dao->decrease($sender, Account::CURRENCY_COIN,   $amount_send), ERROR_CUSTOM, "送者减少星光错");
            Interceptor::ensureNotFalse($account_receiver_dao->insert($receiver, Account::CURRENCY_STAR,  $amount_receive), ERROR_CUSTOM, "收者增加星星错");

            Interceptor::ensureNotFalse($gift_star_log_dao->add($orderid, $sender, $receiver, $liveid, $giftid, GiftStarLog::CONSUME_STAR, $price, $ticket, $num), ERROR_CUSTOM, "记录星光礼物日志错");

            Interceptor::ensureNotFalse($star_journal_sender_dao->add($orderid, Star::TYPE_GIFT, Star::DIRECT_OUT, Star::CURRENCY_STAR_SHINE, $amount_send, '送礼物消费星光', $_extends), ERROR_CUSTOM, "送者添加星变日志错");
            Interceptor::ensureNotFalse($star_journal_receiver_dao->add($orderid, Star::TYPE_GIFT, Star::DIRECT_IN, Star::CURRENCY_STAR, $amount_receive, '送礼物收获星星', $_extends), ERROR_CUSTOM, "收者添加星变日志错");

            $gift_star_log_dao->commit();
        }catch (Exception $e){
            $gift_star_log_dao->rollback();
            throw $e;
        }
    }

    public static function giftListProcess()
    {
        $gift=new Gift();
        $gift_list=$gift->getGiftList();
        $gift_list = array_values($gift_list);

        //临时把当前版本礼物去除faceu和动态礼物
        if(($platform=Context::get("platform")) &&  $platform == 'ios'  ) {
            $config_model=new Config();
            $name="gift_number_limit";
            $region=Context::get("region");
            $version=Context::get("version");
            $config=$config_model->getConfig($region, $name, $platform, $version);
            if($config&&$config['value']==0) {
                if($gift_list) {
                    $gift_list_pass = array();
                    foreach( $gift_list as $gift_item ) {
                        if(in_array($gift_item['type'], [DAOGift::GIFT_TYPE_COMMON,DAOGift::GIFT_TYPE_DBCLICK])  ) {
                            $gift_list_pass[] = $gift_item;
                        }
                    }
                    $gift_list = $gift_list_pass;
                }
            }
        }
        //添加标签数组
        $tags=DAOGift::GIFT_TAG_LIST;
        usort(
            $tags, function ($a,$b) {
                if ($a['sort']==$b['sort']) { return 0;
                }
                return $a['sort']>$b['sort']?1:-1;
            }
        );
        $gift_list=[
            'gift_list'=>$gift_list,
            'tag_list'=>$tags,
        ];
        /*$platform=Context::get('platform');
        $version=Context::get('version');
        if (($platform=='ios'&&version_compare($version,'2.6.3','>' ))||($platform='android'&&version_compare($version,'2.6.4','>' ))){
            $tags=DAOGift::GIFT_TAG_LIST;
            usort($tags, function($a,$b){
                if ($a['sort']==$b['sort'])return 0;
                return $a['sort']>$b['sort']?1:-1;
            });
            $gift_list=[
                'gift_list'=>$gift_list,
                'tag_list'=>$tags,
            ];
        }*/

        //添加红包项
        $tags=$gift_list['tag_list'];
        $packet=self::GIFT_RED_PACKET;
        $packet['image']    = Util::joinStaticDomain($packet['image']);
        $packet['tag']=strval($tags[0]['id']);
        array_unshift($gift_list['gift_list'], $packet);

        /* if (version_compare($version,'2.9.0' ,'>=')){
            //添加红包项
            $tags=$gift_list['tag_list'];
            $packet=self::GIFT_RED_PACKET;
            $packet['image']    = Util::joinStaticDomain($packet['image']);
            $packet['tag']=strval($tags[0]['id']);
            array_unshift($gift_list['gift_list'], $packet);
        }*/
        return $gift_list;
    }

    public function getGiftList($tag='')
    {

        $key = $this->_getGiftKey();

        $cache = Cache::getInstance("REDIS_CONF_CACHE");//REDIS_CONF_PAYMENT
        $gift_list = $cache->get($key);
        if(empty($gift_list)) {
            $dao_gift = new DAOGift();
            $gifts = $dao_gift->getList('Y');
            foreach($gifts as $val){
                unset($val['status']);
                $gift_list[$val['giftid']] = $val;
            }
            $gift_list = json_encode($gift_list);
            $cache->set($key, $gift_list);
        }
        $gift_list = json_decode($gift_list, true);
        if($gift_list) {
            foreach($gift_list as &$value){
                $value['label']     = Util::joinStaticDomain($value['label']);
                $value['image']     = Util::joinStaticDomain($value['image']);
                $value['url']     = Util::joinStaticDomain($value['url']);
                $extends            = json_decode($value['extends'], true);
                $extends['original_url'] = Util::joinStaticDomain($extends['original_url']);
                $value['extends']     = json_encode($extends);
                unset($extends);
            }
        }
        return $gift_list;
    }

    public function getGiftInfo($giftid,$off=false)
    {
        $key = $this->_getGiftKey();

        $gift_list = $this->getGiftList(null);
        $gift_info=null;
        if (isset($gift_list[$giftid])) {
            $gift_info = $gift_list[$giftid];
        }
        if (!$gift_info&&$off) {
            $daoGift=new DAOGift();
            $gift_info=$daoGift->getInfo($giftid);
        }

        return $gift_info;
    }

    private function _reload()
    {
        $key = $this->_getGiftKey();
        $cache = Cache::getInstance("REDIS_CONF_CACHE");//REDIS_CONF_PAYMENT
        $cache->delete($key);

        $this->getGiftList(null);

        return true;
    }

    private function _getGiftKey()
    {
        return 'gift_list';
    }

    public function addGift($name, $image, $label, $type, $price, $ticket, $consume, $score, $status, $url, $zip_md5, $extends,$tag, $region)
    {
        $dao_gift = new DAOGift();
        $dao_gift->addGift($name, $image, $label, $type, $price, $ticket, $consume, $score, $status, $url, $zip_md5, $extends, $tag, $region);
        self::_reload();
    }

    public function setGift($giftid, $name, $image, $label, $type, $price, $ticket, $consume, $score, $status, $url, $zip_md5, $extends,$tag, $region)
    {
        $dao_gift = new DAOGift();
        $dao_gift->setGift($giftid, $name, $image, $label, $type, $price, $ticket, $consume, $score, $status, $url, $zip_md5, $extends, $tag, $region);

        self::_reload();
    }

    public  function sendGiftProcess($sender,$receiver,$giftid,$num,$liveid,$doublehit, $giftUniTag,$isBagGift=false)
    {
        $uid=$sender;
        $dao_profile = new DAOProfile($uid);
        $profiles = $dao_profile->getUserProfiles();
        Interceptor::ensureNotFalse((!(isset($profiles['frozen']) && $profiles['frozen'] == 'Y')), ERROR_BIZ_GIFT_FROZEN, "uid");

        //判断主播是否有私信收礼权限
        self::hasPrivateSendAuth($liveid, $receiver);
        //$gift = new gift();
        $gift_info = $this->getGiftInfo($giftid);
        Interceptor::ensureNotEmpty($gift_info, ERROR_BIZ_GIFT_NOT_FIND);

        //守护专属礼物验证
        $user_guard = UserGuard::getUserGuardRedis($uid, $receiver);
        if ($gift_info['tag']==DAOGift::GIFT_TAG_EXCLUSIVE) {
            Interceptor::ensureNotEmpty($user_guard, ERROR_BIZ_GIFT_NOT_GUARD, "sorry ,u are not the guard");
        }

        $this->send($sender, $receiver, $liveid, $giftid, $gift_info['consume'], $gift_info['price'], $gift_info['ticket'], $num, $gift_info['name'], $isBagGift);

        if ($liveid) {
            Counter::increase(Counter::COUNTER_TYPE_LIVE_GIFT, $liveid, $num);//直播间收礼数
        }
        if($gift_info['consume']=='DIAMOND') {
            $receive_ticket = Counter::increase(Counter::COUNTER_TYPE_PAYMENT_RECEIVE_GIFT, $receiver, $num*$gift_info['price']);//主播收礼金额，2018/1/29 把ticket改成price
            if ($liveid) {
                $live_ticket = Counter::increase(Counter::COUNTER_TYPE_LIVE_TICKET, $liveid,  $gift_info['ticket']*$num);//直播间收入数
            }
            Counter::increase(Counter::COUNTER_TYPE_PAYMENT_SEND_GIFT, $uid, $num*$gift_info['price']);//用户送礼金额
        }else{
            $receive_ticket = Counter::get(Counter::COUNTER_TYPE_PAYMENT_RECEIVE_GIFT, $receiver);
            if ($liveid) {
                $live_ticket = Counter::get(Counter::COUNTER_TYPE_LIVE_TICKET, $liveid);
            }
        }

        /*Counter::increase(Counter::COUNTER_TYPE_LIVE_GIFT, $liveid, $num);//直播间收礼数
        if($gift_info['consume']=='DIAMOND'){
            //$tickets = $gift_info['ticket']*$num;
            $live_ticket = Counter::increase(Counter::COUNTER_TYPE_LIVE_TICKET, $liveid,  $gift_info['ticket']*$num);//直播间收入数
            Counter::increase(Counter::COUNTER_TYPE_PAYMENT_SEND_GIFT, $uid, $num*$gift_info['price']);
        }else{
            $live_ticket = Counter::get(Counter::COUNTER_TYPE_LIVE_TICKET, $liveid);
        }*/

        try{
            //发送消息
            $user = new User();
            $sender_info = $user->getUserInfo($uid);

            //唯一标示
            list($usec, $sec) = explode(" ", microtime());
            $only_mark = rand(1000, 9999).$sec.$uid;

            /*$live = new Live();
            $liveinfo = $live->getLiveInfo($liveid);
            $user_guard = UserGuard::getUserGuardRedis($uid,$liveinfo['uid']);*/

            /* $match_score = 0;
            if($gift_info['consume']=='DIAMOND'){
                $matchid = MatchMessage::getRedisMatchId($receiver);

                if (!empty($matchid)) {
                    $match_score = MatchMessage::sendMatchMemberScore($sender, $receiver, $num*$gift_info['ticket'], 'gift', $matchid);
                }
            }
            //Logger::log("pk_match_log", "sendgift", array("matchid" => $matchid, "sender" => $sender, "receiver" => $receiver));*/

            $match_score=self::pkProcess($sender, $receiver, $gift_info['consume'], $num, $gift_info['ticket']);

            if ($liveid) {
                Messenger::sendGift($liveid, "发送礼物:{$gift_info['name']}", $uid, $sender_info['nickname'], $sender_info['avatar'], $sender_info['level'], $receiver, $gift_info['name'], $gift_info['image'], $gift_info['type'], $giftid, $num, $doublehit, $giftUniTag, $receive_ticket, $live_ticket, $only_mark, $gift_info['consume'], intval($user_guard), $sender_info['vip'], $match_score);
            }
            Messenger::sendGiftPublish($receiver, "发送礼物:{$gift_info['name']}", $uid, $sender_info['nickname'], $sender_info['avatar'], $sender_info['level'], $liveid, $gift_info['name'], $gift_info['image'], $gift_info['type'], $giftid, $num, $doublehit, $giftUniTag, $receive_ticket, $live_ticket, $only_mark, $gift_info['consume'], intval($user_guard), $sender_info['vip'], $match_score);

            if($gift_info['consume']=='DIAMOND') {//跑道消息 
                Track::showTrackGift($uid, $receiver, $giftid, $num);
            }
        }catch (Exception $e){

        }

        include_once "process_client/ProcessClient.php";

        if($gift_info['consume']=='DIAMOND') {
            ProcessClient::getInstance("dream")->addTask("match_release_worker", array('giftid'=>$giftid,'receiver'=>$receiver));
            ProcessClient::getInstance("dream")->addTask("live_rank_generate", array("type" => "protect", "action" => "increase", "userid" => $uid, "score" => $gift_info['price']*$num, "relateid" => $receiver));
            ProcessClient::getInstance("dream")->addTask("live_rank_generate", array("type" => "sendgift", "action" => "increase", "userid" => $uid, "score" => $gift_info['price']*$num, "relateid" => $uid));
            if ($liveid) {
                ProcessClient::getInstance("dream")->addTask("passport_task_execute", array("uid" => $uid, "taskid" => Task::TASK_ID_SEND_GIFT, "num" => $num, "ext"=>json_encode(array('price'=>$gift_info['price'],'liveid'=>$liveid))));
                ProcessClient::getInstance("dream")->addTask("live_rank_generate", array("type" => "receivegift", "action" => "increase", "userid" => $receiver, "score" => $gift_info['price']*$num, "relateid" => $receiver, "sender" => $uid, "liveid" => $liveid));
            }
            //$ps=new PubSubMq('send_gift_mq');
            //PubSubMq::publish('send_gift_mq',array('uid'=>$uid,'receiveid'=>$receiver,'relateid'=>$giftid,'num'=>$num));
        }

        $account_balance = new Account($sender);
        $currency_diamond= $account_balance->getAccountList($sender);

        $currency_diamond['num'] = $num;
        $currency_diamond['doublehit'] = $doublehit;

        return $currency_diamond;

    }

    private static function hasPrivateSendAuth($liveid,$receiver)
    {
        if (!$liveid) {//工会主播和白名单可以私信送礼
            $daoEmployee=new DAOEmploye();
            $isEmployee=$daoEmployee->isEmploye($receiver);
            $isEmployee=empty($isEmployee)?false:true;
            $key=WhiteList::WL_PRIVATE_GIFT;
            $isWhiteList=WhiteList::isInList($receiver, $key);
            Interceptor::ensureNotFalse(($isEmployee||$isWhiteList), ERROR_BIZ_GIFT_HAS_NOT_PRIVATE_SEND_AUTH, "receiver no auth");
        }
    }

    private static function use7001($giftid)
    {
        $login_gift     = new DAOLoginGift();
        $gifts7001          = $login_gift->getAllGifts();
        $giftids7001        = array_column($gifts7001, 'giftid');
        if(in_array($giftid, $giftids7001)) {//登录送背包礼物(可达鸭)
            return true;
        }
        return false;
    }

    private static function pkProcess($sender,$receiver,$consume,$num,$ticket)
    {
        $match_score = 0;
        if($consume=='DIAMOND') {
            $matchid = MatchMessage::getRedisMatchId($receiver);
            if (!empty($matchid)) {
                $match_score = MatchMessage::sendMatchMemberScore($sender, $receiver, $num*$ticket, 'gift', $matchid);
            }
        }
        //送礼移除小黑屋
        //Logger::log("pk_match_log", "sendgift", array("matchid" => $matchid, "sender" => $sender, "receiver" => $receiver));
        return $match_score;
    }
}
