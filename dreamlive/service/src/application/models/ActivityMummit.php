<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018/1/15
 * Time: 17:29
 */
class ActivityMummit
{
    /**/
    public static function getUserCodeInfo($userid)
    {
        $cache      = Cache::getInstance("REDIS_CONF_CACHE");
        $key        = 'activity_code_'.$userid;
        $code       = $cache->get($key);
        Interceptor::ensureNotFalse(!empty($code), ERROR_PARAM_INVALID_FORMAT, "userid");
        $userinfo       = User::getUserInfo($userid);
        Interceptor::ensureNotFalse(!empty($userinfo), ERROR_PARAM_INVALID_FORMAT, "userid");
        return array(
            'code'  => $code,
            'uid'   => $userinfo['uid'],
            'nickname' => $userinfo['nickname'],
            'avatar'=> $userinfo['avatar']
        );
    }
    /*分享邀请码*/
    public static function applyInviteCode($userid)
    {
        $cache      = Cache::getInstance("REDIS_CONF_CACHE");
        $key        = 'activity_code_'.$userid;
        $code = $cache->get($key);
        if($cache->get($key)&&$cache->get('activity_code_'.$code)==$userid) {
            $code = $cache->get($key);
        }else{
            $summit     = new DAOActivityHeaderSummit();
            $summit->startTrans();
            try{
                $code = $summit->getEmptyCode();
                $summit->modEmptyCode($code, $userid);
                $summit->commit();
            }catch (Exception $e){
                $summit->rollback();
                throw $e;
            }
            $cache -> set($key, $code);
            $cache ->set('activity_code_'.$code, $userid);
        }
        $userinfo       = User::getUserInfo($userid);
        return array(
            'code'  => $code,
            'uid'   => $userinfo['uid'],
            'nickname' => $userinfo['nickname'],
            'avatar'=> $userinfo['avatar']
        );
    }
    /*使用邀请码*/
    public static function useInviteCode($userid, $code)
    {
        $cache      = Cache::getInstance("REDIS_CONF_CACHE");
        $addtime    = $cache -> get("login_send_gift_".$userid);
        if(!$addtime) {
            //获取注册时间
            $dao_user = new DAOUser();
            $userinfo = $dao_user->getUserInfo($userid);
            $cache -> set("login_send_gift_".$userid, $userinfo['addtime']);
            $addtime  = $userinfo['addtime'];
        }
        Interceptor::ensureNotFalse(time()-strtotime($addtime)<=7*24*3600, ERROR_SUMMIT_USER_IS_FALSE);

        $key1       = 'invite_code_'.$userid;
        Interceptor::ensureNotFalse(Counter::get('activity_code', $key1)<1, ERROR_SUMMIT_CODE_EXIST);
        //添加复活卡

        $uid        = $cache->get('activity_code_'.$code);
        Interceptor::ensureNotFalse($uid>0, ERROR_SUMMIT_CODE_NOT_EXIST);
        Interceptor::ensureNotFalse($uid!=$userid, ERROR_SUMMIT_USER_IS_EXIST);
        Counter::increase(Counter::COUNTER_TYPE_ROUND_NUM, $uid, 1);
        Counter::increase(Counter::COUNTER_TYPE_ROUND_NUM, $userid, 1);
        //添加数据库
        $card       = new DAOActivityHeaderCard();
        $member     = new DAOActivityHeaderMember();
        $member ->startTrans();
        try{
            if(Counter::increase('activity_code', 'card_'.$uid)>1) {
                $card->modCard($uid, 1);
            }else{
                $card->addCard($uid, 1);
            }
            if(Counter::increase('activity_code', 'card_'.$userid)>1) {
                $card->modCard($userid, 1);
            }else{
                $card->addCard($userid, 1);
            }
            $member->addHeaderMember($code, $userid);
            $member->commit();
        }catch (Exception $e){
            $member->rollback();
            throw $e;
        }
        Counter::increase('activity_code', $key1);
    }
    /*排行榜*/
    public static function getRank()
    {

    }
    /*
     * 获取场次详情
     * */
    public static function getMatchInfo($userid)
    {
        //获取当前用户信息
        $account    = new DAOAccount($userid);
        $blance     = $account->getBalance(Account::CURRENCY_CASH, true);
        $cache      = Cache::getInstance("REDIS_CONF_CACHE");
        $match      = json_decode($cache->get('act_match_bonus'), true);
        $key1       = 'invite_code_'.$userid;
        $liveid     = $cache->zScore('User_living_cache', 666666)?$cache->zScore('User_living_cache', 666666):0;
        $live       = new Live();
        $stream     = new Stream();
        if($liveid) {
            $live_info  = $live->getLiveInfo($liveid);
            $replay     = ($live_info['record'] == 'Y') ? true : false;
            $flv        = $stream->getFLVUrl($live_info['sn'], $live_info['partner'], $live_info['region'], $replay);
            $hls        = $stream->getHLSUrl($live_info['sn'], $live_info['partner'], $live_info['region'], $replay);
        }
        //获取注册时间
        $addtime    = $cache -> get("login_send_gift_".$userid);
        if(!$addtime) {
            //获取注册时间
            $dao_user = new DAOUser();
            $userinfo1 = $dao_user->getUserInfo($userid);
            $cache -> set("login_send_gift_".$userid, $userinfo1['addtime']);
            $addtime  = $userinfo1['addtime'];
        }
        $userinfo   = User::getUserInfo($userid);


        return array(
            'time'      => time(),
            'uid'       => $userinfo['uid'],
            'nickname' => $userinfo['nickname'],
            'avatar'    => $userinfo['avatar'],
            'blance'    => $blance?$blance:0,
            'amount'    => $match['amount'],
            'stime'     => strtotime($match['startime']),
            'title'     => $match['title'],
            'roundid'   => $match['roundid'],
            'liveid'    => $liveid,
            'people_num'=> Counter::get(Counter::COUNTER_TYPE_LIVE_WATCHES, $liveid),
            'card_num'  => Counter::get(Counter::COUNTER_TYPE_ROUND_NUM, $userid),
            'flv'        => $flv?$flv:'',
            'hls'       => $hls?$hls:'',
            'is_use'    => Counter::get('activity_code', $key1)==1?0:1,
            'is_end'    => $match['is_end']?$match['is_end']:0,
            'is_old'    => time()-strtotime($addtime)<=7*24*3600?1:0,
            'rank'      => 0
        );
    }
    //领取复活码
    public static function freeRevivalCard($uid, $num)
    {
        Interceptor::ensureNotFalse(Counter::increase("free_revival_card", $uid, 1) == 1, ERROR_SUMMIT_REVIVAL_IS_EXIST);

        Counter::increase(Counter::COUNTER_TYPE_ROUND_NUM, $uid, $num);
        $card       = new DAOActivityHeaderCard();
        if(Counter::get('activity_code', 'card_'.$uid)>0) {
            $card->modCard($uid, $num);
        }else{
            $card->addCard($uid, $num);
        }
        return array("num"=>Counter::get(Counter::COUNTER_TYPE_ROUND_NUM, $uid));
    }
    //充值送复活卡
    public static function freeRevivalCardexchange($uid)
    {
        $num    = 1;
        Logger::log("free_revival_card_log", "false", array("uid" => $uid,'starttime'=>date("Y-m-d H:i:s")));
        if(Counter::increase("free_revival_card_".date("Ymd"), $uid, 1) > 1) { return true;
        }

        Logger::log("free_revival_card_log", "false start", array("uid" => $uid));
        Counter::increase(Counter::COUNTER_TYPE_ROUND_NUM, $uid, $num);
        $card       = new DAOActivityHeaderCard();
        if(Counter::get('activity_code', 'card_'.$uid)>0) {
            $card->modCard($uid, $num);
        }else{
            $card->addCard($uid, $num);
        }
        Logger::log("free_revival_card_log", "true end", array("uid" => $uid));
    }
}
