<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018/3/2
 * Time: 11:24
 */
class UserRecall
{
    const ACT_USER_RECALL_KEY='act_user_recall_key';
    public static function getLoginUserInfo($userid)
    {
        return User::getUserInfo($userid);
    }
    public static function addUserRelation($sharid, $userid, $type, $phone)
    {
        $key    = "user_recall";
        $user_recall    = new DAOUserRecall();
        if(($userid>0&&Counter::increase($key, $userid)>1)||($phone>0&&Counter::increase($key, $phone)>1)) {
            $user_recall -> modUserRecall($sharid, $userid, $phone);
        }else{
            $user_recall -> addUserRecall($sharid, $userid, $type, $phone);
        }
        return array('sharid'=>$sharid,'userid'=>$userid,'phone'=>$phone);
    }

    public static function getList($uid=0)
    {
        $cache=Cache::getInstance("REDIS_CONF_CACHE");
        $info=$cache->get(self::ACT_USER_RECALL_KEY);
        $info=json_decode($info, true);
        if (!$info) { $info=array();
        }
        $result['list']=$info;
        if ($uid) {
            $self=array();
            foreach ($info as $i){
                if ($i['uid']==$uid) {
                    $self=$i;
                    break;
                }
            }
            if (empty($self)) {
                $daoUserRecall = new DAOUserRecall();
                $curUser=$daoUserRecall->getUserRank($uid);
                if (empty($curUser)) {
                    $curUser['num']=0;
                    $curUser['amount']=0;
                }
                $curUser['uid']=$uid;
                $curUser['rank']='50+';
                
                $t[]=$curUser;
                $r=self::getExt($t);
                if (!empty($r)) {
                    $self=$r[0];
                }
            }
            $result['self']=$self;
        }
        return $result;
    }

    public static function genList()
    {
        $cache=Cache::getInstance("REDIS_CONF_CACHE");
        $daoUserRecall = new DAOUserRecall();
        $info=$daoUserRecall->getRank();
        if (empty($info)) {
            $info=array();
        }else{
            $info=self::getExt($info);
        }
        $cache->set(self::ACT_USER_RECALL_KEY, json_encode($info));
    }

    private static function getExt($info)
    {
        foreach ($info as &$i){
            $userInfo=User::getUserInfo($i['uid']);
            if (empty($userInfo)) { continue;
            }
            $i['avatar']=$userInfo?$userInfo['avatar']:"";
            $i['nickname']=$userInfo?$userInfo['nickname']:"";
            $i['gender']=$userInfo?$userInfo['gender']:"M";
            //$i['icon']=$userInfo?self::getIcon($userInfo['medal']):null;
            $i['level']=$userInfo?$userInfo['level']:0;
            //$i['num']=$i['total'];
            $i['king']=0;
            $i['vip']=0;
            //if ($i['rank']>50)$i['rank']='50+';
            /* foreach ($userInfo['medal'] as $j){
                if ($j['kind']==UserMedal::KIND_KING){
                    $i['king']=intval($j['medal']);
                    continue;
                }elseif ($j['kind']==UserMedal::KIND_VIP){
                    $i['vip']=intval($j['medal']);
                    continue;
                }
            }*/
        }
        return $info;
    }
}
