<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/1
 * Time: 14:53
 */

/**
 * Class ActivitySupport
 * ******************************************************************************************************************
 *
 * 打榜模块配置：
 * 打榜开始时间：stime
 * 打榜结束时间：etime
 * 收费投票：vote_ratio:10;//1票=10星钻
 * 投票配置：vote_list:[10,20,30]
 * 收费投票帐变类型: vote_trade_type:1302
 * 
 * 活动参数：support_ratio:300;//1票=300爱意值
 * 活动参数单位：support_unit:"爱意值“
 * 活动排行算法：rank_score:[
 *      'gift':[
 *          giftid1:100,//100票
 *          giftid2:200,
 *      ]
 *      ‘vote'=>10,
 *       'free_vote'=>1,
 *       'jury_vote'=>2,
 *       'share'=>1,
 *       'upload'=>1,
 * ]
 * 排行榜配置：rank_list[name]:[
 *      'zone'=>true,
 *      'date=>'总’,
 *      'type'=>1,
 *      'gift'=>[giftid1,giftid2],
 * ]
 * 依赖关系配置：relation:[
 *      "apply_module"=>m1
 *      排行榜模块=>[m2,m3]
 * ]
 *
 * 直播间活动排名排行榜：
 * live_rank:[
 *      'zone'=>true,
 *      'date'=>,
 *      'type'=>
 * ]
 *
 * 晋级排名排行榜：
 * promotion_rank:[
 *      'zone'
 *      'date'
 *      'type'
 * ]
 * promotion_topn:10;
 */
class ActivitySupport
{

    private static function beforeVote($ctl)
    {
        $data = [];
        $receiver=$ctl->getParam('receiver', 0);
        $sender=$ctl->getParam('sender', 0);
        $moduleid=$ctl->getParam('moduleid', 0);
        $num=$ctl->getParam('num', 1);
        $type=$ctl->getParam('type', DAOActivitySupport::SUPPORT_TYPE_VOTE);

        $data['receiver']=$receiver;
        $data['sender']=$sender;
        $data['moduleid']=$moduleid;
        $data['num']=$num;
        $data['type']=$type;


        Interceptor::ensureNotFalse($receiver>0, ERROR_PARAM_INVALID_FORMAT, 'receiver');
        Interceptor::ensureNotFalse($sender>0, ERROR_PARAM_INVALID_FORMAT, 'sender');
        Interceptor::ensureNotFalse($moduleid>0, ERROR_PARAM_INVALID_FORMAT, 'moduleid');

        if (isset($data['receiver']) && $data['receiver']) {
            Interceptor::ensureNotFalse($data['sender'] != $data['receiveid'], ERROR_CUSTOM, 'sender cant be receiver');
        }

        $moduleinfo =ActivityModule::getModuleById($moduleid);
        $config = ActivityModule::getModuleConfig($moduleinfo['extends']);
        //检查打榜时间
        $now = time();
        if (isset($config['stime'])) {
            Interceptor::ensureNotFalse($now >= strtotime($config['stime']), ERROR_CUSTOM, 'not begin');
        }
        if (isset($config['etime'])) {
            Interceptor::ensureNotFalse($now <= strtotime($config['etime']), ERROR_CUSTOM, 'be over');
        }

        if (!empty($module['scripts'])) {
            eval($module['scripts']);
        }
        return $data;
    }

    private static function beforeGift($data)
    {

        Interceptor::ensureNotFalse($data['receiver']>0, ERROR_PARAM_INVALID_FORMAT, 'receiver');
        Interceptor::ensureNotFalse($data['sender']>0, ERROR_PARAM_INVALID_FORMAT, 'sender');
        Interceptor::ensureNotFalse($data['giftid']>0, ERROR_PARAM_INVALID_FORMAT, 'giftid');
        Interceptor::ensureNotFalse($data['num']>=1, ERROR_PARAM_INVALID_FORMAT, 'num');

        if (isset($data['receiver']) && $data['receiver']) {
            Interceptor::ensureNotFalse($data['sender'] != $data['receiveid'], ERROR_CUSTOM, 'sender cant be receiver');
        }

        $moduleinfo =ActivityModule::getModuleById($data['moduleid']);
        $config = ActivityModule::getModuleConfig($moduleinfo['extends']);
        //检查打榜时间
        $now = time();
        if (isset($config['stime'])) {
            Interceptor::ensureNotFalse($now >= strtotime($config['stime']), ERROR_CUSTOM, 'not begin');
        }
        if (isset($config['etime'])) {
            Interceptor::ensureNotFalse($now <= strtotime($config['etime']), ERROR_CUSTOM, 'be over');
        }

        if (!empty($module['scripts'])) {
            eval($module['scripts']);
        }
        return $data;
    }

    private static function afterVote(array $data, array $result)
    {
        $ret = [];
        $ret['data'] = $data;
        $ret['result'] = $result;
        return $ret;
    }


    private static function calRankScore($moduleConfig, $type, $num, $relateid)
    {
        $rankScore = $moduleConfig['rank_score'];
        $supportRatio = $moduleConfig['support_ratio'];

        $val = 0;
        switch ($type) {
        case DAOActivitySupport::SUPPORT_TYPE_GIFT:
            $val = $rankScore['gift'][$relateid];
            break;
        case DAOActivitySupport::SUPPORT_TYPE_VOTE:
            $val = $rankScore['vote'];
            break;
        case DAOActivitySupport::SUPPORT_TYPE_FREE_VOTE:
            $val = $rankScore['free_vote'];
            break;
        case DAOActivitySupport::SUPPORT_TYPE_SHARE:
            $val = $rankScore['share'];
            break;
        case DAOActivitySupport::SUPPORT_TYPE_UPLOAD:
            $val = $rankScore['upload'];
            break;
        case DAOActivitySupport::SUPPORT_TYPE_JURY_VOTE:
            $val = $rankScore['jury_vote'];
            break;
        default:
            $val = 1;
            break;
        }
        return $val * $num * $supportRatio;
    }


    private static function votePay($uid, $vnum, $moduleConfig)
    {
        $trade_type = $moduleConfig['vote_trade_type'];
        $voteRadio=$moduleConfig['vote_radio'];
        $num=$vnum*$voteRadio;
        $userinfo = User::getUserInfo($uid);
        Interceptor::ensureNotFalse(isset($userinfo['uid']), ERROR_USER_NOT_EXIST);

        $dao_profile = new DAOProfile($uid);
        $profiles = $dao_profile->getUserProfiles();
        Interceptor::ensureNotFalse((!(isset($profiles['frozen']) && $profiles['frozen'] == 'Y')), ERROR_BIZ_GIFT_FROZEN, "userid");

        $account = new Account();
        $dao_gift_log = new DAOGiftLog();
        try {
            $dao_gift_log->startTrans();

            $diamond = $account->getBalance($uid, ACCOUNT::CURRENCY_DIAMOND);
            Interceptor::ensureNotEmpty($diamond, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            Interceptor::ensureNotFalse($diamond >= $num, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);

            $orderid = Account::getOrderId($uid);
            Interceptor::ensureNotFalse($account->decrease($uid, $trade_type, $orderid, $num, ACCOUNT::CURRENCY_DIAMOND, "html5, {$uid}投票$num, 类型$trade_type.", array()), ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            Interceptor::ensureNotFalse($account->increase(Account::COMAPNY_ACCOUNT, $trade_type, $orderid, $num, ACCOUNT::CURRENCY_DIAMOND, "html5, {$uid}投票$num, 类型$trade_type.", array()), ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);

            $dao_gift_log->commit();
        } catch (Exception $e) {
            $dao_gift_log->rollback();
            throw $e;
        }
    }

    private static function _voteSupport($supportModuleId,$sender, $receiver, $num ,$voteType)
    {
        $supportModuleInfo=ActivityModule::getModuleById($supportModuleId);
        if ($supportModuleInfo) {
            $supportModuleConfig = ActivityModule::getModuleConfig($supportModuleInfo['extends']);

            if ($supportModuleConfig) {
                //验证是否报名
                self::isApply($supportModuleConfig['relation'], $receiver);

                $rankList = $supportModuleConfig['rank_list'];
                $relation=$supportModuleConfig['relation'];
                //$apply=ActivityApply::getApplyInfoByMidUid($relation[DAOActivityModule::MODULE_TYPE_APPLY], $receiver);
                //扣钻
                if ($voteType==DAOActivitySupport::SUPPORT_TYPE_VOTE) {
                    self::votePay($sender, $num, $supportModuleConfig);
                }

                //打榜
                if ($rankList) {
                    $score=self::calRankScore($supportModuleConfig, $voteType, $num, 0);
                    if (!$score) { return;
                    }
                    $isSetRank=false;
                    foreach ($rankList as $i) {

                        $member = $receiver;
                        if ($i['type'] == ActivityRank::RANK_TYPE_SEND) {
                            $member = $sender;
                        }

                        $name = ActivityRank::getRankName($i, $supportModuleInfo['moduleid'], $member);

                        ActivityRank::setRank($name, $member, $score);
                        $isSetRank=true;
                    }

                    if ($isSetRank) {
                        //添加记录
                        $daovote = new DAOActivitySupport();
                        $daovote->add($supportModuleInfo['activityid'], $supportModuleInfo['roundid'], $supportModuleInfo['moduleid'], $voteType, $sender, $num, $receiver, 0, []);
                    }

                }
            }
        }
    }
    public static function voteSupport($ctl)
    {
        try{
            $data=self::beforeVote($ctl);
            $data['type']=!$data['type']?DAOActivitySupport::SUPPORT_TYPE_VOTE:$data['type'];
            self::_voteSupport($data['moduleid'], $data['sender'], $data['receiver'], $data['num'], $data['type']);
        }catch (Exception $e){
            //写日志
            return;
        }
    }

    public static function uploadSupport($uid, $uploadid)
    {
        try{
            self::_uploadSupport($uid, $uploadid);
        }catch (Exception $e){

        }
    }

    private static function _uploadSupport($uid,$uploadid,$num=1)
    {

    }

    public static function shareSupport($uid, $anchor, $shareid)
    {

    }


    public static function giftSupport($sender,$receiver,$giftid,$num=1)
    {
        try{
            self::_giftSupport($sender, $receiver, $giftid, $num);
        }catch (Exception $e){
            throw $e;
            //写日志
            return;
        }
    }
    //礼物打榜
    private static function _giftSupport($sender, $receiver, $giftid, $num = 1)
    {
        //计算出活动id
        $supportModuleInfo = self::judgeActivity(DAOActivitySupport::SUPPORT_TYPE_GIFT, $giftid);
        if ($supportModuleInfo) {
            self::beforeGift(array('sender'=>$sender,'receiver'=>$receiver,'giftid'=>$giftid,'num'=>$num,'moduleid'=>$supportModuleInfo['moduleid']));
            //根据配置得到要设置的榜单
            $supportModuleConfig = ActivityModule::getModuleConfig($supportModuleInfo['extends']);

            if ($supportModuleConfig) {
                //验证是否报名
                self::isApply($supportModuleConfig['relation'], $receiver);

                $rankList = $supportModuleConfig['rank_list'];
                $relation=$supportModuleConfig['relation'];
                //$apply=ActivityApply::getApplyInfoByMidUid($relation[DAOActivityModule::MODULE_TYPE_APPLY], $receiver);
                //打榜
                if ($rankList) {
                    $score=self::calRankScore($supportModuleConfig, DAOActivitySupport::SUPPORT_TYPE_GIFT, $num, $giftid);
                    if (!$score) { return;
                    }
                    $isSetRank=false;
                    foreach ($rankList as $i) {
                        if(!isset($i['gift']) || !in_array($giftid, $i['gift'])) { continue;
                        }

                        $member = $receiver;
                        if ($i['type'] == ActivityRank::RANK_TYPE_SEND) {
                            $member = $sender;
                        }

                        $name = ActivityRank::getRankName($i, $supportModuleInfo['moduleid'], $member);
                        if (!$name) { continue;
                        }

                        ActivityRank::setRank($name, $member, $score);
                        $isSetRank=true;
                    }
                    if ($isSetRank) {
                        //添加记录
                        $daovote = new DAOActivitySupport();
                        $daovote->add($supportModuleInfo['activityid'], $supportModuleInfo['roundid'], $supportModuleInfo['moduleid'], DAOActivitySupport::SUPPORT_TYPE_GIFT, $sender, $num, $receiver, $giftid, []);
                    }
                }

            }
        }
    }

    //活动判定
    private static function judgeActivity($type, $relateid = 0)
    {
        $curActivityList = Activity::getCurrentActivityList();
        $moduleList = array_column($curActivityList, 'module_list');
        foreach ($moduleList as $i) {
            foreach ($i as $j) {
                if (DAOActivityModule::MODULE_TYPE_SUPPORT != $j['type']) { continue;
                }
                $config = ActivityModule::getModuleConfig($j['extends']);
                $stime = $config['stime'];
                $etime = $config['etime'];
                if (!$stime && !$etime) { throw new Exception("stime etime must be set");
                }
                $now = time();
                if ($now < strtotime($stime) || $now > strtotime($etime)) { continue;
                }
                switch ($type) {
                case DAOActivitySupport::SUPPORT_TYPE_GIFT:
                    if (!$relateid) { throw new Exception("relateid(giftid) is null");
                    }
                    $vote = $config['rank_score'];
                    $gifts = isset($vote['gift']) ? $vote['gift'] : [];
                    if (!empty($gifts)) {
                        if (array_key_exists($relateid, $gifts)) {
                            return $j;
                        }
                    }
                    break;
                    /* case DAOActivitySupport::SUPPORT_TYPE_VOTE:
                     case DAOActivitySupport::SUPPORT_TYPE_FREE_VOTE:
                     case DAOActivitySupport::SUPPORT_TYPE_JURY_VOTE:
                         return $j;
                         break;*/
                case DAOActivitySupport::SUPPORT_TYPE_SHARE:
                    return $j;
                        break;
                case DAOActivitySupport::SUPPORT_TYPE_UPLOAD:
                    return $j;
                        break;
                default:
                    throw new Exception(" support type is err");
                        break;
                }
            }
        }

        return null;
    }

    //检查是否需要报名
    private static function isApply($relation, $receiver)
    {
        $applyModuleId = isset($relation[DAOActivityModule::MODULE_TYPE_APPLY]) ? $relation[DAOActivityModule::MODULE_TYPE_APPLY] : 0;
        if ($applyModuleId) {
            $applyModuleInfo = ActivityModule::getModuleById($applyModuleId);
            if ($applyModuleInfo) {
                Interceptor::ensureNotEmpty(ActivityApply::getApplyInfoByUid($applyModuleInfo['activityid'], $applyModuleInfo['moduleid'], $receiver), ERROR_CUSTOM, 'not register');
            }
        }
    }

    public static function log( $msg)
    {
        $f="/tmp/support.log";
        if (empty($msg)) { $msg=[];
        }
        $msg['time']=date("Y-m-d H:i:s");
        $str=json_encode($msg);
        $str.="\n\n";
        file_put_contents($f, $str, FILE_APPEND);
    }

}