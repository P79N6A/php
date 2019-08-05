<?php
class PropController extends BaseController
{

    public function sendAction()
    {
        /* 发送弹幕{{{*/
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
        
        $user = new User();
        $sender_info = $user->getUserInfo($uid);

        //vip
        $balance=0;
        $isNeedPay = true;
        if($sender_info['vip'] > 0 ) {
            $balance = Vip::incrLeftNumber($uid, Vip::TYPE_PROP_NUM);
            if($balance > -1) {
                $isNeedPay = false;
            }
            list($big,$small)=Bag::getAllHornNum($uid);
            $balance=$small;
        }
        //背包
        if ($isNeedPay) {
            $balance=Bag::useHorn($uid, DAOBag::BAG_CATEID_SMALL_HORN);
            if ($balance> -1) {
                $isNeedPay=false;
            }
        }
        
        if($isNeedPay) {
            $prop = new Prop();
            $prop->send($uid, $liveid, $content);
        }
        
        Messenger::sendProp($liveid, $content, $uid, $sender_info['nickname'], $sender_info['avatar'], $sender_info['level'], $sender_info['medal']);
        
        include_once "process_client/ProcessClient.php";
        
        
        ProcessClient::getInstance("dream")->addTask("passport_task_execute", array("uid" => $uid, "taskid" => Task::TASK_ID_PROP, "num" => 1, "ext"=>json_encode(array('price'=>1, 'liveid'=>$liveid))));
        
        $account     = new Account();
        $diamond = $account->getBalance($uid, ACCOUNT::CURRENCY_DIAMOND);
        
        $this->render(array("diamond" => $diamond, 'horn_num'=>(int)$balance));
    }/*}}}*/

    
}
