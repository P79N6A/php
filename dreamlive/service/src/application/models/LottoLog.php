<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/18
 * Time: 15:06
 */
class LottoLog
{
    const USER_LEVEL_LIMIT=80;//用户级别
    const PAGE_NUM=50;
    public static function modPrizeLog($id, $prizeid)
    {
        $lotto_log      = new DAOLottoLog();
        $lotto_extends  = json_decode($lotto_log->getInfoById($id), true);
        if($lotto_extends) {
            foreach($lotto_extends as &$value){
                if($value['prizeid'] == $prizeid) { $value['status'] = 1;
                }
            }
        }
        return $lotto_log->modGameLotto($id, json_encode($lotto_extends));
    }

    public static function isFree($uid)
    {
        $userInfo=User::getUserInfo($uid);
        if (isset($userInfo['level'])&&$userInfo['level']>self::USER_LEVEL_LIMIT) {
            $daoLottoLog=new DAOLottoLog();
            $free=$daoLottoLog->isFree($uid);
            if ($free) { return true;
            }
        }
        if (Bag::hasFreeLottoTicket($uid)) { return true;
        }
        return false;
    }
    
    public static function getListByUid($uid,$page)
    {
        //LottoPrize::getPrizeList();
        $daoLottoLog=new DAOLottoLog();
        $re=$daoLottoLog->getList($uid, $page, self::PAGE_NUM);
        foreach ($re['list'] as &$i){
            $ext=@json_decode($i['extends'], true);
            $ext=!$ext?[]:$ext;
            $i['extends']=$ext;
            foreach ($i['extends'] as &$j){
                $j['name']=$j['name']."x".$j['num'];
            }
        }
        return $re;
    }

    public static function addLog($uid,$type,$amount,$liveid,array $ext=[])
    {
        $daoLottoLog=new DAOLottoLog();
        return $daoLottoLog->add($uid, $type, $amount, $liveid, $ext);
    }

    public static function getConfirm($id,$index)
    {
        $daoLog=new DAOLottoLog();
        return $daoLog->updateStatusGet($id, $index);
    }
}