<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/31
 * Time: 16:02
 */

/**
 * Class ActivityRank
 * 排行榜可以有多个,
 *
 * ******************************************************************************************************************
 * 排行榜模块配置
 *
 * 模块依赖配置：relation：[support_module=>mid,apply_module=>mid]
 */
class ActivityRank
{
    const RANK_PREF='activity:';
    const RANK_PAGE_NUM=100;

    const RANK_HAS_ZONE='Y';
    const RANK_NO_ZONE="N";
    
    const RANK_DATE_TOTAL='总';
    const RANK_DATE_DAY='日';
    const RANK_DATE_WEEK='周';

    const RANK_TYPE_SEND=1;//送礼
    const RANK_TYPE_RECEIVE=2;//收礼
    const RANK_TYPE_SHARE=3;//分享
    const RANK_TYPE_UPLOAD=4;//上传
    const RANK_TYPE_MAN=5;//男
    const RANK_TYPE_WOMAN=6;//女

    const RANK_MEMBER_SENDER='S';
    const RANK_MEMBER_RECEIVER='R';

    public static $RANK_TYPE=array(
        self::RANK_TYPE_SEND=>'sendgift',
        self::RANK_TYPE_RECEIVE=>'receivegift',
        self::RANK_TYPE_SHARE=>'share',
        self::RANK_TYPE_UPLOAD=>'upload',
        self::RANK_TYPE_MAN=>'man',
        self::RANK_TYPE_WOMAN=>'woman',
    );

    public static $RANK_DATE=array(
        self::RANK_DATE_TOTAL=>'total',
        self::RANK_DATE_DAY=>'day',
        self::RANK_DATE_WEEK=>'week',
    );


    public static function getPromotionRank($supportModuleId,$zone,$date,$type)
    {
        $rankData=[];
        $rankData['zone']=$zone;
        $rankData['date']=$date;
        $rankData['type']=$type;
        $supportModuleInfo=ActivityModule::getModuleById($supportModuleId);
        $config=ActivityModule::getModuleConfig($supportModuleInfo['extends']);
        $name=self::getRankName($config['promotion_rank'], $supportModuleInfo['moduleid'], 0, $rankData);
        $myrank=new MyRank($name);

        $rank=$myrank->getRankByIndex(0, $config['promotion_topn']-1);
        $re=[];
        $index=1;
        foreach ($rank as $k=>$v){
            $uinfo=User::getUserInfo($k);
            $re[$index]=[
                'uid'=>$k,
                'score'=>$v,
                'nickname'=>$uinfo?$uinfo['nickname']:"",
            ];
            $index++;
        }
        return $re;
    }

    public static function getUidRankOfActivity($uid,$supportModuleId,$isPromotion=false)
    {
        $re=[
            'rank'=>0,
            'score'=>0,
        ];
        $supportModuleInfo=ActivityModule::getModuleById($supportModuleId);
        if (!$supportModuleInfo) { return $re;
        }
        $supportModuleConfig=ActivityModule::getModuleConfig($supportModuleInfo['extends']);

        $liveRank=$supportModuleConfig['live_rank'];
        if ($isPromotion) { $liveRank=$supportModuleConfig['promtion_rank'];
        }
        
        $name=self::getRankName($liveRank, $supportModuleId, $uid);
        if (!$name) { return $re;
        }
        $myrank=new MyRank($name);
        $memberRank=$myrank->getMemberRankByOrder($uid);
        $memberScore=$myrank->getMemberScore($uid);
        return [
            'rank'=>$memberRank,
            'score'=>$memberScore,
        ];
    }


    public static function getRankName($rankConfig,$supportModuleId,$uid=0,$rankData=array())
    {

        $i=$rankConfig;

        $zone='';
        $date='';
        $type='';

        $supportModuleInfo=ActivityModule::getModuleById($supportModuleId);
        if (!$supportModuleInfo) { return;
        }

        $supportConfig=ActivityModule::getModuleConfig($supportModuleInfo['extends']);

        if ($uid) {
            $apply=ActivityApply::getApplyInfoByMidUid($supportConfig['relation']["apply_module"], $uid);
        }

        if ($i['zone'] == ActivityRank::RANK_HAS_ZONE) {
            if ($uid) {
                if (!$apply) { return;
                }
                if ($apply) {
                    $zone = $apply['zone'];
                }
            }else{
                $zone=$rankData['zone'];
            }

        }
        if ($i['date'] == ActivityRank::RANK_DATE_TOTAL) {
            $date = 'total';
        } elseif ($i['date'] == ActivityRank::RANK_DATE_DAY) {
            if ($uid) {
                $date = 'day' . date('Y-m-d');
            }else{
                $date='day'.$rankData['date'];
            }

        } elseif ($i['date'] == ActivityRank::RANK_DATE_WEEK) {
            if ($uid) {
                $date = 'week' . date('Y-W');
            }else{
                $date='week'.$rankData['date'];
            }

        }

        $type = ActivityRank::$RANK_TYPE[$i['type']];

        if ($uid) {
            if ($i['type'] == ActivityRank::RANK_TYPE_MAN||$i['type']==ActivityRank::RANK_TYPE_WOMAN) {
                if (isset($apply['sex'])) {
                    $sex=$apply['sex']=="M"?ActivityRank::RANK_TYPE_MAN:ActivityRank::RANK_TYPE_WOMAN;
                    if ($i['type']!=$sex) { return;
                    }
                }
            }
        }

        return self::_getRankName($supportModuleInfo['activityid'], $supportModuleInfo['moduleid'], $zone, $date, $type);

    }


    //增加缓存
    public static function getRank($ctl,$activityid,$moduleid,$page=1)
    {
        $activityinfo=Activity::getActivityInfo($activityid);
        Interceptor::ensureNotEmpty($activityinfo, ERROR_PARAM_DATA_NOT_EXIST, ' activity not exists');
        $moduleinfo=Activity::getModuleInfoById($activityinfo, $moduleid);
        Interceptor::ensureNotEmpty($moduleinfo, ERROR_PARAM_DATA_NOT_EXIST, ' module not exists');
        $rankModuleConfig=ActivityModule::getModuleConfig($moduleinfo['extends']);

        $supportModuleInfo=ActivityModule::getModuleById($rankModuleConfig['relation']["support_module"]);
        $rankName=$ctl->getParam('name', '');

        Interceptor::ensureNotEmpty($rankName, ERROR_PARAM_INVALID_FORMAT, 'name');

        $supportModuleConfig=ActivityModule::getModuleConfig($supportModuleInfo['extends']);
        $rankList=$supportModuleConfig['rank_list'];
        if (!$rankList||!isset($rankList[$rankName])) { return [];
        }

        $zone=$ctl->getParam("zone", "");
        $date=$ctl->getParam("date", "");
        $type=$ctl->getParam("type", 0);
        $rankData=[
            'zone'=>$zone,
            'date'=>$date,
            'type'=>$type,
        ];

        $name=self::getRankName($rankList[$rankName], $supportModuleInfo['moduleid'], 0, $rankData);
        $myrank=new MyRank($name);
        $startIndex=($page-1)*self::RANK_PAGE_NUM;
        $endIndex=$page*self::RANK_PAGE_NUM;
        $rank=$myrank->getRankByIndex($startIndex, $endIndex);
        $res=[];
        if ($rank) {
            foreach ($rank as $k=>$v){
                $user=User::getUserInfo($k);
                if ($user) {
                    $res[]=[
                        'uid'=>$k,
                        'score'=>$v,
                        'nickname'=>$user['nickname'],
                        'avatar'=>$user['avatar'],
                        'gender'=>$user['gender'],
                        'level'=>$user['level'],
                    ];
                }
            }

        }
        return self::afterRank($ctl, $activityinfo, $moduleinfo, $res);
    }
    public static function setRank($name,$member,$score)
    {
        $myrank=new MyRank($name);
        return $myrank->modScore($member, $score);
    }

    public static function serializeToModule()
    {

    }

    private static function afterRank($ctl,$activity,$module,$result)
    {
        $data=[];
        $data=$result;
        if (!empty($module['scripts'])) {
            eval($module['scripts']);
        }
        return $data;
    }

    //activityid
    //moduleid排行榜模块的id
    private static function _getRankName($activityid,$applyModuleid,$zone='',$date='',$type='')
    {
        $name=array(self::RANK_PREF,$activityid,$applyModuleid);
        if ($zone) {
            $name[]=$zone;
        }
        if ($date) {
            $name[]=$date;
        }
        if ($type) {
            $name[]=$type;
        }

        $nameJoin=implode(":", $name);

        return md5($nameJoin);
    }

    public static function clearRank()
    {
        //保存rank快照
        //清除缓存
    }
    
}