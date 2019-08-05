<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2017/12/11
 * Time: 10:59
 */
class Match
{
    //发起pk
    public static function apply($uid, $invitee, $duration)
    {
        //判断主播是不是正在直播
        $live       = new DAOLive();
        Interceptor::ensureNotEmpty($live -> isLive($uid), ERROR_MATCH_USER_LIVE_NOT_ACTIVE);
        //判断受邀请人是否在直播
        if($invitee>0) { Interceptor::ensureNotEmpty($live -> isLive($invitee), ERROR_MATCH_INVITEE_LIVE_NOT_ACTIVE);
        }
        //pk双方不能是一个人
        Interceptor::ensureNotEmpty($uid!=$invitee, ERROR_MATCH_INVITEE_NOT_MY);
        //时长类型
        $config     = new Config();
        $value      = $config->getConfig("china", "pk_match_config", "server", '1000000000000');
        $config_value = json_decode($value['value'], true);
        $duration_arr = array_column($config_value['time_select'], 'duration');
        Interceptor::ensureNotEmpty(in_array($duration, $duration_arr), ERROR_MATCH_DURATION_NOT_EXIST);
        //判断主播是不是处于小黑屋
        $prison     = new DAOMatchPrison();
        Interceptor::ensureNotEmpty($prison -> isPrison($uid) == 0, ERROR_MATCH_USER_IS_PRISON);
        //判断受邀用户在不在小黑屋
        if($invitee) { Interceptor::ensureNotEmpty($prison -> isPrison($invitee) == 0, ERROR_MATCH_INVITEE_IS_PRISON);
        }
        //判断主播是不是正在pk
        $match      = new DAOMatchs();
        Interceptor::ensureNotEmpty($match -> isAllPk($uid) == 0, ERROR_MATCH_USER_IS_PK);
        //判断受邀用户是不是正在直播
        if($invitee) { Interceptor::ensureNotEmpty($match -> isPK($invitee) == 0, ERROR_MATCH_INVITEE_IS_PK);
        }

        try{
            $match -> addPK($uid, $invitee>0?'ONE':'ALL', $invitee, $duration, json_encode($config_value));
        }catch(Exception $e){
            throw new BizException(ERROR_MATCH_APPLY_FALSE, $e->getMessage());
        }
        $userinfo       = User::getUserInfo($uid);
        $match_info     = $match->getApplyPK($uid);
        $extends    = array(
            'userid'    => $uid,
            'duration'  => $duration,
            'nickname'  => $userinfo['nickname'],
            'avatar'    => $userinfo['avatar'],
            'wating_duration' => $config_value['wating_duration'],
            'matchid'   => $match_info['matchid'],
            'medal'     => $userinfo['medal'],
            'vip'       => $userinfo['vip'],
            'level'     => $userinfo['level']
        );
        //给受邀者推送pk消息
        if($invitee>0) { Messenger::sendToUser($invitee, Messenger::MESSAGE_TYPE_USER_APPLY_MATCH, "用户{$uid}向您发出pk邀请", $extends);
        }
        return array(
            'wating_duration'   => $config_value['wating_duration'],
            'matchid'            => $match_info['matchid']
        );
    }
    //接受pk
    public static function accept($uid, $matchid)
    {
        //判断pk场次是否存在
        $match      = new DAOMatchs();
        $match_info = $match -> getMatchByMatchid($matchid);
        Interceptor::ensureNotEmpty(!empty($match_info), ERROR_MATCH_NOT_EXIST);
        //判断是否有正在进行pk
        Interceptor::ensureNotEmpty($match -> isPK($uid) == 0, ERROR_MATCH_USER_IS_PK);
        Interceptor::ensureNotEmpty($match -> isPK($match_info['inviter']) == 0, ERROR_MATCH_INVITER_IS_PK);
        //验证该次pk是否有人接受
        Interceptor::ensureNotEmpty($match_info['status']==0, ERROR_MATCH_ACCEPT_EXIST);
        //pk双方不能是一个人
        Interceptor::ensureNotEmpty($uid!=$match_info['inviter'], ERROR_MATCH_INVITEE_NOT_MY);
        if($match_info['invitee'] != 0) { Interceptor::ensureNotEmpty($uid==$match_info['invitee'], ERROR_MATCH_NOT_ACCEPT);
        }
        //判断该次pk是否流局
        $time       = time();
        $extends    = json_decode($match_info['config'], true);
        $date       = strtotime($match_info['addtime'])+$extends['wating_duration']*60;
        Interceptor::ensureNotEmpty($time<$date, ERROR_MATCH_IS_FLOW);
        //验证小黑屋
        $prison     = new DAOMatchPrison();
        Interceptor::ensureNotEmpty($prison -> isPrison($uid) == 0, ERROR_MATCH_USER_IS_PRISON);
        $member     = new DAOMatchMember();
        Interceptor::ensureNotEmpty(empty($member->isAccept($matchid)), ERROR_MATCH_ACCEPT_EXIST);

        $member -> startTrans();
        try{
            $match -> modMatch($uid, $matchid);
            $member -> addMatchMember($matchid, $uid);
            $member -> addMatchMember($matchid, $match_info['inviter']);
            $member ->commit();

        }catch (Exception $e){
            $member ->rollback();
            throw new BizException(ERROR_MATCH_ACCEPT_FALSE, $e->getMessage());
        }
        //获取liveid
        $cache      = Cache::getInstance("REDIS_CONF_CACHE");
        $living_user_key       = "User_living_cache";
        $userinfo       = User::getUserInfo($uid);
        //添加炫章
        try{
            UserMedal::addUserMedal($uid, UserMedal::KIND_PK, $match_info['matchid']);
            UserMedal::addUserMedal($match_info['inviter'], UserMedal::KIND_PK, $match_info['matchid']);
            $matchinfo = $match->getMatchByMatchid($matchid);
            
            $endtime = strtotime($matchinfo['startime'])+($matchinfo['duration']*60);
            MatchMessage::setMatchMemberRedis([$uid, $match_info['inviter']], $matchid, date('Y-m-d H:i:s', $endtime));
            User::reload($match_info['inviter']);
            User::reload($uid);
            $live = new Live();
            $live->_reload($cache->zScore($living_user_key, $uid));
            $live->_reload($cache->zScore($living_user_key, $match_info['inviter']));
        }catch (Exception $e){
            throw new BizException(ERROR_MATCH_ACCEPT_FALSE, $e->getMessage());
        }

        $extends    = array(
            'userid'    => $uid,
            'nickname'  => $userinfo['nickname'],
            'avatar'    => $userinfo['avatar'],
            'matchid'   => $match_info['matchid'],
            'medal'     => $userinfo['medal'],
            'vip'       => $userinfo['vip'],
            'liveid'       => $cache->zScore($living_user_key, $uid),
            'level'     => $userinfo['level'],
            'data'      => self::getMatchInfo($uid, $match_info['matchid'])
        );
        $userinfo1       = User::getUserInfo($match_info['inviter']);
        $extends1    = array(
            'userid'    => $uid,
            'nickname'  => $userinfo1['nickname'],
            'avatar'    => $userinfo1['avatar'],
            'matchid'   => $match_info['matchid'],
            'medal'     => $userinfo1['medal'],
            'vip'       => $userinfo1['vip'],
            'liveid'       => $cache->zScore($living_user_key, $match_info['inviter']),
            'level'     => $userinfo1['level'],
            'data'      => self::getMatchInfo($match_info['inviter'], $match_info['matchid'])
        );
        //通知用户更新pk消息

        //Messenger::sendToUser($match_info['inviter'],Messenger::MESSAGE_TYPE_USER_ACCEPT_MATCH,"用户{$uid}已接受您pk邀请,赶快pk吧",$extends);
        Messenger::sendToGroup($cache->zScore($living_user_key, $uid), Messenger::MESSAGE_TYPE_USER_ACCEPT_MATCH, Messenger::KEFUID, "通知受邀者直播间用户更新pk信息", $extends);
        Messenger::sendToGroup($cache->zScore($living_user_key, $match_info['inviter']), Messenger::MESSAGE_TYPE_USER_ACCEPT_MATCH, Messenger::KEFUID, "通知发起者直播间用户更新pk信息", $extends1);
    }
    //pk广场
    public static function getMatchList($uid, $offset, $num)
    {
        //获取用户的收到邀请的pk列表
        $match          = new DAOMatchs();
        $list           = $match->getMyPKList($offset, $num);
        $total          = $match->getMyPKTotal();
        $lists  = [];
        if($list) {
            foreach($list as $value){
                if($value['inviter']!=$uid&&($value['invitee']!=0&&$value['invitee']!=$uid)) { continue;
                }
                $offset         = $value['matchid'];
                $config         = json_decode($value['config'], true);
                $userinfo       = User::getUserInfo($value['inviter']);
                if($value['inviter'] == $uid) {
                    $lists[]   = array(
                        'uid'       => $uid,
                        'nickname'  => $userinfo['nickname'],
                        'avatar'    => $userinfo['avatar'],
                        'vip'       => $userinfo['vip'],
                        'medal'     => $userinfo['medal'],
                        'duration'  => $value['duration'],
                        'matchid'   => $value['matchid'],
                        'type'      => 1,
                        'startime'  => (strtotime($value['addtime'])+$config['wating_duration']*60)
                    );
                }else{
                    $type = $value['invitee'] == $uid?2:3;
                    $lists[]  = array(
                        'uid'       => $value['inviter'],
                        'nickname'  => $userinfo['nickname'],
                        'avatar'    => $userinfo['avatar'],
                        'vip'       => $userinfo['vip'],
                        'medal'     => $userinfo['medal'],
                        'duration'  => $value['duration'],
                        'matchid'   => $value['matchid'],
                        'type'      => $type
                    );
                }
            }
        }
        $more   = "Y";
        if(count($lists)<$num) { $more = "N";
        }
        return array(
            'lists'     => $lists,
            'total'     => $total,
            'offset'    => $offset,
            'more'      => $more=="Y"?true:false
        );
    }
    //我的pk记录
    public static function getMyMatchList($uid)
    {
        $match      = new DAOMatchs();
        $member     = new DAOMatchMember();
        $lists      = $match -> getMyMatchList($uid);
        $time       = time();
        $list       = [];
        if($lists) {
            foreach ($lists as $k=>$value)
            {
                $config        = json_decode($value['config'], true);
                $other_uid     =  $value['inviter'] == $uid?$value['invitee']:$value['inviter'];
                $userinfo      = User::getUserInfo($other_uid);
                $i             = floor(($time - strtotime($value['startime']))/60);
                $h             = floor(($time - strtotime($value['startime']))/3600);
                $d             = floor($h/24);
                $m             = floor($d/30);
                $y             = floor($m/12);
                if($y>0) {
                    $spacing = $y.'年前';
                }else{
                    if($m>0) {
                        $spacing = $m.'月前';
                    }else{
                        if($d>0) {
                            $spacing = $d.'天前';
                        }else{
                            if($h>0) {
                                $spacing = $h.'小时前';
                            }else{
                                $spacing = $i.'分钟前';
                            }

                        }
                    }
                }

                //获取分数
                $match_result       = $member -> getPKResult($uid, $value['matchid']);
                foreach($config['time_select'] as $val){
                    if($val['duration'] == $value['duration']) { $results     = $val['winner_line'] >= $match_result['score']?false:true;
                    }
                }
                unset($config);
                $list[$k]   = array(
                    'duration'      => $value['duration'],
                    'matchid'       => $value['matchid'],
                    'nickname'      => $userinfo['nickname'],
                    'avatar'        => $userinfo['avatar'],
                    'uid'           => $other_uid,
                    'spacing'       => $spacing,
                    'medal'         => $userinfo['medal'],
                    'results'       => $results
                );
                unset($value, $userinfo, $other_uid, $results);
            }
        }
        return array('list'=>$list);
    }
    //贡献榜
    public static function rank($uid, $matchid)
    {
        //判断pk场次是否存在
        $match      = new DAOMatchs();
        $match_info = $match -> getMatchByMatchid($matchid);
        Interceptor::ensureNotEmpty(!empty($match_info), ERROR_MATCH_NOT_EXIST);

        $cache      = Cache::getInstance("REDIS_CONF_CACHE");
        $rank       = $cache->zRevRangeByScore("devote_rank_" . $matchid . '_' . $uid, PHP_INT_MAX, 0, ['withscores' => true, 'limit' => [0, 5]]);
        $ranks      = [];
        $i = 1;
        if($rank) {
            foreach($rank as $k=>$value){
                $userinfo       = User::getUserInfo($k);
                $ranks[]        = array(
                    'nickname'  => $userinfo['nickname'],
                    'avatar'    => $userinfo['avatar'],
                    'score'     => $value,
                    'rank'      => $i
                );
                $i++;
            }
        }
        return array('list'=>$ranks);
    }
    //pk详情
    public static function getMatchInfo($uid, $matchid)
    {
        $match          = new DAOMatchs();
        $match_info     = $match->getNowMatch($matchid);
        Interceptor::ensureNotEmpty(!empty($match_info), ERROR_MATCH_NOT_EXIST);
        $inviter_info   = User::getUserInfo($match_info['inviter']);
        $invitee_info   = User::getUserInfo($match_info['invitee']);
        $match_info['inviter_ticket']         = Counter::get(Counter::COUNTER_TYPE_MATCH_RECEIVE_GIFT, $match_info['inviter'] .'_' .$matchid);
        $match_info['invitee_ticket']         = Counter::get(Counter::COUNTER_TYPE_MATCH_RECEIVE_GIFT, $match_info['invitee'] .'_' .$matchid);
        $match_info['inviter_nickname']    = $inviter_info['nickname'];
        $match_info['invitee_nickname']    = $invitee_info['nickname'];
        $match_info['inviter_avatar']      = $inviter_info['avatar'];
        $match_info['invitee_avatar']      = $invitee_info['avatar'];
        $match_info['config']               = json_decode($match_info['config'], true);
        $match_info['startime']             = strtotime($match_info['startime']);
        return $match_info;
    }
    //添加小黑屋
    public static function imprison($uid, $second, $source, $matchid, $adminid)
    {
        $match      = new DAOMatchs();
        $prison     = new DAOMatchPrison();
        Interceptor::ensureEmpty($match->isAllPk($uid)!=0, ERROR_MATCH_USER_LIVE);
        /*$match_info = $match -> getMatchByMatchid($matchid);
        Interceptor::ensureNotEmpty(!empty($match_info), ERROR_MATCH_NOT_EXIST);*/

        try{
            $endtime    = date("Y-m-d H:i:s", (time()+$second*24*3600));
            $result     = $prison ->addPrison($uid, $endtime, $source, $matchid, $adminid);
        }catch (Exception $e){
            throw new BizException(ERROR_MATCH_ADD_PRISON_FALSE, $e->getMessage());
        }
        return $result;
    }
    //移除小黑屋
    public static function release($prisonid, $note, $uid=0)
    {
        $prison     = new DAOMatchPrison();
        try{
            if($prisonid) {
                $result = $prison->delPrison($prisonid, $note);
            }else{
                $result = $prison->delPrisonByUid($uid, $note);
            }

        }catch (Exception $e){
            throw new BizException(ERROR_MATCH_DEL_PRISON_FALSE, $e->getMessage());
        }
        return $result;
    }
    //获取正在进行的PK
    public static function getConductPK()
    {
        $cache      = Cache::getInstance("REDIS_CONF_CACHE");
        $match      = new DAOMatchs();
        $live       = new Live();
        $stream     = new Stream();
        $list       = $match -> getPkList();
        if($list) {
            foreach($list as $val)
            {
                $inviter_userinfo       = User::getUserInfo($val['inviter']);
                $invitee_userinfo       = User::getUserInfo($val['invitee']);
                $inviter_liveid     = $cache->zScore('User_living_cache', $val['inviter']);
                $invitee_liveid     = $cache->zScore('User_living_cache', $val['invitee']);
                if($inviter_liveid) {
                    $inviter_liveinfo   = $live->getLiveInfo($inviter_liveid);
                    $inviter_replay     = ($inviter_liveinfo['record'] == 'Y') ? true : false;
                    $inviter_privacy    = Privacy::getPrivacyInfoByLiveInfo($inviter_liveinfo['privacy']);
                    $inviter_flv        = $stream->getFLVUrl($inviter_liveinfo['sn'], $inviter_liveinfo['partner'], $inviter_liveinfo['region'], $inviter_replay);
                }
                if($invitee_liveid) {
                    $invitee_liveinfo   = $live->getLiveInfo($invitee_liveid);
                    $invitee_replay     = ($invitee_liveinfo['record'] == 'Y') ? true : false;
                    $invitee_privacy    = Privacy::getPrivacyInfoByLiveInfo($invitee_liveinfo['privacy']);
                    $invitee_flv        = $stream->getFLVUrl($invitee_liveinfo['sn'], $invitee_liveinfo['partner'], $invitee_liveinfo['region'], $invitee_replay);
                }
                $lists[]        = array(
                    'inviter'           => $val['inviter'],
                    'inviter_nickname' => $inviter_userinfo['nickname'],
                    'inviter_avatar'   => $inviter_userinfo['avatar'],
                    'inviter_liveid'   => $cache->zScore('User_living_cache', $val['inviter'])?$cache->zScore('User_living_cache', $val['inviter']):0,
                    'inviter_score'    =>  Counter::get(Counter::COUNTER_TYPE_MATCH_RECEIVE_GIFT, $val['inviter'] .'_' .$val['matchid']),
                    'inviter_liveid'   => $inviter_liveid,
                    'inviter_cover'    => $inviter_liveinfo['cover'],
                    'inviter_isPrivacy'=> $inviter_privacy?1:0,
                    'inviter_privacyId'=> $inviter_privacy['privacyid'],
                    'inviter_liveUrl'  => $inviter_flv,
                    'inviter_king'     => self::getMedalKing($inviter_userinfo),
                    'invitee'           => $val['invitee'],
                    'invitee_nickname' => $invitee_userinfo['nickname'],
                    'invitee_avatar'   => $invitee_userinfo['avatar'],
                    'invitee_liveid'   => $cache->zScore('User_living_cache', $val['invitee'])?$cache->zScore('User_living_cache', $val['invitee']):0,
                    'invitee_score'    => Counter::get(Counter::COUNTER_TYPE_MATCH_RECEIVE_GIFT, $val['invitee'] .'_' .$val['matchid']),
                    'invitee_liveid'   => $cache->zScore('User_living_cache', $val['invitee']),
                    'invitee_cover'    => $invitee_liveinfo['cover'],
                    'invitee_isPrivacy'=> $invitee_privacy?1:0,
                    'invitee_privacyId'=> $invitee_privacy['privacyid'],
                    'invitee_liveUrl'  => $invitee_flv,
                    'invitee_king'     => self::getMedalKing($invitee_userinfo),
                );
            }
        }
        return $lists;
    }
    //获取主播榜前三
    public static function getAnchorTop3()
    {
        $rank       = new Rank();
        $live       = new Live();
        $stream     = new Stream();
        $cache      = Cache::getInstance("REDIS_CONF_CACHE");
        $list       = $rank->getRanking('pk_match_winner_num', 0, 3);


        if($list[1]) {
            foreach($list[1] as $k=>$val)
            {
                if($k==3) { break;
                }
                $liveid     = $cache->zScore('User_living_cache', $val['uid']);
                if($liveid) {
                    $liveinfo   = $live->getLiveInfo($liveid);
                    $replay     = $replay = ($liveinfo['record'] == 'Y') ? true : false;
                    $privacy    = Privacy::getPrivacyInfoByLiveInfo($liveinfo['privacy']);
                    $flv        = $stream->getFLVUrl($liveinfo['sn'], $liveinfo['partner'], $liveinfo['region'], $replay);
                }
                $lists[]  = array(
                    'anchorId'    => $val['uid'],
                    'name'          => $val['nickname'],
                    'avatar'        => $val['avatar'],
                    'rank'          => $k+1,
                    'is_live'       => $val['isLive'],
                    'score'         => $val['score'],
                    'cover'         => $liveinfo['cover'],
                    'isPrivacy'     => $privacy?1:0,
                    'privacyId'     => $privacy['privacyid'],
                    'liveUrl'       => $flv,
                    'king'          => self::getMedalKing($val),
                    'liveid'        => $liveid
                );
            }
        }
        return $lists;
    }
    //获取土豪前三
    public static function getUserTop3()
    {
        $rank       = new Rank();
        $live       = new Live();
        $stream     = new Stream();
        $cache      = Cache::getInstance("REDIS_CONF_CACHE");
        $list       = $rank->getRanking('pk_sender_activity', 0, 3);

        if($list[1]) {
            foreach($list[1] as $k=>$val)
            {
                if($k==3) { break;
                }
                $liveid     = $cache->zScore('User_living_cache', $val['uid']);
                if($liveid) {
                    $liveinfo   = $live->getLiveInfo($liveid);
                    $replay     = $replay = ($liveinfo['record'] == 'Y') ? true : false;
                    $privacy    = Privacy::getPrivacyInfoByLiveInfo($liveinfo['privacy']);
                    $flv        = $stream->getFLVUrl($liveinfo['sn'], $liveinfo['partner'], $liveinfo['region'], $replay);
                }
                $lists[]  = array(
                    'anchorId'      => (int)$val['uid'],
                    'name'          => $val['nickname'],
                    'avatar'        => $val['avatar'],
                    'rank'          => $k+1,
                    'is_live'       => $val['isLive'],
                    'score'         => $val['score'],
                    'cover'         => $liveinfo['cover'],
                    'isPrivacy'     => $privacy?1:0,
                    'privacyId'     => $privacy['privacyid'],
                    'liveUrl'       => $flv,
                    'vip'           => $val['vip'],
                    'liveid'        => $liveid
                );
            }
        }
        return $lists;
    }
    /*
     * 获胜调用
     * */
    public static function sendMatch($uid, $score)
    {
        $cache      = Cache::getInstance("REDIS_CONF_CACHE");
        //发红包
        $liveid     = $cache->zScore('User_living_cache', $uid);
        $amount     = floor($score/100)*100*0.05;
        ActChristmas::sendRedPacketPK($liveid, $amount);
        //更新胜利场数
        $rank   = new Rank();
        $rank->setRank('matchwinnernum', 'increase', $uid, 1);
        $userinfo   = User::getUserInfo($uid);
        //发全站消息
        $str    = "{$userinfo['nickname']}在pk中获得胜利，竞技大神降临并留下红包一枚\xF0\x9F\x8E\x89\xF0\x9F\x8E\x89\xF0\x9F\x8E\x89";
        $img    = "";
        Track::showTrackDefault($str, $img);
        return true;
    }
    //获取主播段位
    public static function getMedalKing($userinfo)
    {
        $king       = array(0=>'傲娇塑料',8=>'倔强青铜',7=>'秩序白银',6=>'荣耀黄金',5=>'尊贵铂金',4=>'永恒钻石',3=>'至尊星耀',2=>'最强王者',1=>'神乎其技');
        if($userinfo['medal']) {
            foreach($userinfo['medal'] as $v){
                if($v['kind'] == 'king') {
                    return $v['medal'];
                }
            }
        }
        return '';
    }
    //爆发榜
    //主播榜
    //土豪榜
}
