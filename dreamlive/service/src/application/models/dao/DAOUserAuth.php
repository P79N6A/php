<?php

/**
 * Class DAOUserAuth
 * 有记录代表无权限，无记录代表又权限
 */
class DAOUserAuth extends DAOProxy
{
    const SWITCH_ON='on';
    const SWITCH_OFF='off';

    const AUTH_LIVE=1;//开播权限

    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PASSPORT");
        $this->setTableName("user_auth");
    }

    public function setUserAuth($uid,$auth,$switch)
    {
        //检查switch
        $switchs=array(self::SWITCH_ON,self::SWITCH_OFF);
        if (!in_array($switch, $switchs)) { throw new Exception('switch err');
        }
        //检查auth
        if (!in_array($auth, self::getAuthConstants())) { throw new Exception('auth id err');
        }

        if ($switch==self::SWITCH_ON) {
            $re=$this->getUserAuth($uid, $auth);
            if (!empty($re)) { return;
            }
            $d=array(
            'uid'=>$uid,
            'auth'=>$auth,
            'addtime'=>date("Y-m-d H:i:s"),
            );
            return $this->insert($this->getTableName(), $d);
        }elseif ($switch==self::SWITCH_OFF) {
            return $this->delete($this->getTableName(), 'uid=? and auth=?', array('uid'=>$uid,'auth'=>$auth));
        }else{
            throw new Exception("switch err");
        }
    }

    public function getUserAuth($uid,$auth=0)
    {
        $where='';
        if ($auth) {
            $where.=' and auth='.$auth;
        }
        return $this->getAll("select * from ".$this->getTableName()." where uid=? ".$where, array('uid'=>$uid));
    }

    public static function getAuthConstants()
    {
        return array(
        self::AUTH_LIVE,
        );
    }
}
?>
