<?php
class HornController extends BaseController
{
    
    public function sendAction()
    {
        /* 发送大喇叭{{{*/
        $uid      = Context::get("userid");
        $content  = $this->getParam("content");
        $liveid   = $this->getParam("liveid");
        
        Interceptor::ensureNotEmpty($uid,    ERROR_PARAM_IS_EMPTY, "uid");
        Interceptor::ensureNotEmpty($content,    ERROR_PARAM_IS_EMPTY, "content");
        Interceptor::ensureNotEmpty($liveid,    ERROR_PARAM_IS_EMPTY, "liveid");
        
        //检验是否包含屏蔽词
        $plus['liveid'] = $liveid;
        $plus['sender'] = $uid;
        
        $chat = new Chat();
        Interceptor::ensureNotFalse(!($chat->isKicked($liveid, $uid)), ERROR_BIZ_CHATROOM_USER_HAS_SILENCED);
        Interceptor::ensureNotFalse(!($chat->isSilence($liveid, $uid)), ERROR_BIZ_CHATROOM_USER_HAS_SILENCED);
        Interceptor::ensureNotFalse(FilterKeyword::check_shield($content, $plus, false), ERROR_KEYWORD_SHIELD, 'content');
        
        //替换内容
        $replace_keyword = array();
        $content = FilterKeyword::content_replace($content, $replace_keyword);
        $replace_keyword = !empty($replace_keyword) ? implode(',', $replace_keyword) : '';
        
        if (!empty($replace_keyword)) {
            $dao_filter = new DAOFilter();
            $type = !empty($replace_keyword) ? FilterKeyword::REPLACE : FilterKeyword::NORMAL;
            $dao_filter->addFilter($uid, 0, $type, $content, $replace_keyword, $liveid);
        }
        
        $isNeedPay = true;
        $user = new User();
        $sender_info = $user->getUserInfo($uid);
        $balance=0;
        //vip
        if($sender_info['vip'] > 0 ) {
            $balance = Vip::incrLeftNumber($uid, Vip::TYPE_HORN_NUM);
            if($balance > -1) {
                $isNeedPay = false;
            }
            list($big,$small)=Bag::getAllHornNum($uid);
            $balance=$big;
        }

        //背包
        if ($isNeedPay) {
            $balance=Bag::useHorn($uid, DAOBag::BAG_CATEID_BIG_HORN);
            if ($balance>-1) {
                $isNeedPay=false;
            }
        }

        if($isNeedPay) {
            $horn = new Horn();
            $horn->send($uid, $liveid, $content);
        }
        
        $live = new Live();
        $liveinfo = $live->getLiveInfo($liveid);
        $user_guard = UserGuard::getUserGuardRedis($uid, $liveinfo['uid']);
        
        $userinfo = User::getUserInfo($uid);
        
        $data = array(
        "userid"=>$uid,
        "nickname"=>$userinfo['nickname'],
        "avatar"=>$userinfo['avatar'],
        "level"=>$userinfo['level'],
        "gender"=>$userinfo['gender'],
        "medal"=>$userinfo['medal'],
        "founder"=>$userinfo['founder'],
        "vip" => (int)$userinfo['vip'],
        'isGuard'=>intval($user_guard),
        "fontcolor" => (string)Vip::getLevelConfig($userinfo['vip'], Vip::TYPE_FONT_COLOR),
        );
        
        Messenger::sendChatroomBroadcast($uid, Messenger::MESSAGE_TYPE_BROADCAST_HORN_ALL, $content, $data);
        
        include_once "process_client/ProcessClient.php";
        ProcessClient::getInstance("dream")->addTask("passport_task_execute", array("uid" => $uid, "taskid" => Task::TASK_ID_PROP, "num" => 1, "ext"=>json_encode(array('price'=>1, 'liveid'=>$liveid))));
        
        
        $account     = new Account();
        $diamond = $account->getBalance($uid, ACCOUNT::CURRENCY_DIAMOND);
        
        $this->render(array("diamond" => $diamond, 'horn_num'=>(int)$balance));
    }/*}}}*/


}
