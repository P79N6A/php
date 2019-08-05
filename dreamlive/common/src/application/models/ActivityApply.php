<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/31
 * Time: 15:17
 */

/**
 * Class ActivityApply
 * 报名模块配置
 * stime
 * etime
 */
class ActivityApply
{
    public static function register($ctl,$activityid,$moduleid,$uid)
    {
        $activityinfo=Activity::getActivityInfo($activityid);
        Interceptor::ensureNotEmpty($activityinfo, ERROR_PARAM_DATA_NOT_EXIST, ' activity not exists');
        $userinfo=User::getUserInfo($uid);
        Interceptor::ensureNotEmpty($userinfo, ERROR_PARAM_DATA_NOT_EXIST, ' user not exists');
        $moduleinfo=Activity::getModuleInfoById($activityinfo, $moduleid);
        Interceptor::ensureNotEmpty($moduleinfo, ERROR_PARAM_DATA_NOT_EXIST, ' module not exists');

        $data=self::beforeRegister($activityinfo, $moduleinfo, $userinfo, $ctl);

        $name=!empty($data['name'])?$data['name']:'';
        $age=!empty($data['age'])?$data['age']:0;
        $sex=!empty($data['sex'])?$data['sex']:"M";
        $phone=!empty($data['phone'])?$data['phone']:"";
        $email=!empty($data['email'])?$data['email']:"";
        $address=!empty($data['address'])?$data['address']:"";
        $zone=!empty($data['zone'])?$data['zone']:"";
        $ext=!empty($data['ext'])?$data['ext']:[];
        $ext_json=@json_decode($ext, true);
        if (!$ext_json) { $ext_json=[];
        }

        $dao_apply=new DAOActivityApply();
        //重复报名//采用数据库去重
        $apply=$dao_apply->getApplyByUid($activityid, $moduleid, $uid);
        Interceptor::ensureEmpty($apply, ERROR_CUSTOM, 'repeat apply');

        $apply_id=$dao_apply->add($activityid, $moduleinfo['roundid'], $moduleid, $uid, $name, $age, $sex, $phone, $email, $address, $zone, $ext_json);
        $result=[
            'applyid'=>$apply_id,
        ];
        return self::afterRegister($ctl, $result);
    }

    private static function beforeRegister($activityinfo,$moduleinfo,$userinfo,$ctl)
    {
        $data=[];
        $config=ActivityModule::getModuleConfig($moduleinfo['extends']);
        //检查报名时间
        $now=time();
        if (isset($config['stime'])) {
            Interceptor::ensureNotFalse($now>=strtotime($config['stime']), ERROR_CUSTOM, 'not begin');
        }
        if (isset($config['etime'])) {
            Interceptor::ensureNotFalse($now<=strtotime($config['etime']), ERROR_CUSTOM, 'be over');
        }

        $name=$ctl->getParam("name", "");
        $age=$ctl->getParam("age", 0);
        $sex=$ctl->getParam("sex", "M");
        $phone=$ctl->getParam("phone", "");
        $email=$ctl->getParam("email", "");
        $address=$ctl->getParam("address", "");
        $zone=$ctl->getParam("zone", "");
        $ext=$ctl->getParam("ext", "");

        $data['name']=$name;
        $data['age']=$age;
        $data['sex']=$sex;
        $data['phone']=$phone;
        $data['email']=$email;
        $data['address']=$address;
        $data['zone']=$zone;
        $data['ext']=$ext;

        if (!empty($module['scripts'])) {
            eval($module['scripts']);
        }

        return $data;
    }

    private static function afterRegister($ctl,$result)
    {
        return $result;
    }

    public static function getApplyInfoByUid($activityid,$moduleid,$uid)
    {
        $daoApply=new DAOActivityApply();
        return $daoApply->getApplyByUid($activityid, $moduleid, $uid);
    }

    public static function getApplyInfoByMidUid($moduleid,$uid)
    {
        $daoApply=new DAOActivityApply();
        return $daoApply->getApplyInfoByUid($moduleid, $uid);
    }
}