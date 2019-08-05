<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2017/12/11
 * Time: 14:54
 */
class DAOMatch extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_GAME");
        $this->setTableName("match");
    }
    /*
     * 验证用户是否存在pk
     * @param int $uid
     * */
    public function isPK($uid)
    {
        return $this -> getOne("select count(*) from {$this->getTableName()} where (inviter=? or invitee=?) and status in (0,1)", [$uid,$uid]);
    }
    /*
     * 添加pk记录
     * */
    public function addPK($uid, $type, $invitee, $duration, $config)
    {
        $info   = array(
            'inviter'  => $uid,
            'type'     => $type,
            'invitee'  => $invitee,
            'duration' => $duration,
            'config'   => $config,
            'addtime'  => date("Y-m-d H:i:s"),
        );

        return $this -> insert($this->getTableName(), $info);
    }
    /*
     * 获取发起的pk
     * @param int $uid
     * */
    public function getApplyPK($uid)
    {
        return $this -> getRow("select * from {$this->getTableName()} where inviter=? and status in (0,1) limit 1", [$uid]);
    }
    /*
     * 通过matchid获取pk详情
     * @param int $matchid 场次id
     * */
    public function getMatchByMatchid($matchid)
    {
        return $this -> getRow("select * from {$this->getTableName()} where matchid=? limit 1", [$matchid]);
    }
    /*
     * 修改受邀人
     * @param   int $uid
     * @param   int $matchid
     * */
    public function modMatch($uid, $matchid)
    {
        $param = array(
            'invitee'   => $uid,
            'startime'  => date("Y-m-d H:i:s"),
            'modtime'   => date("Y-m-d H:i:s")
        );
        return $this -> update($this->getTableName(), $param, "matchid=? and invitee=?", [$matchid,0]);
    }
    /*
     * 获取用户的收到邀请的pk列表
     * @param   int $uid
     * */
    public function getMyPKList($uid)
    {
        return $this -> getAll("select * from {$this->getTableName()} where (invitee=? or inviter=?) and status=? order by invitee desc,addtime desc", [$uid,$uid,0]);
    }
    /*
     * 获取用户的pk记录
     * @param int $uid
     * */
    public function getMyMatchList($uid)
    {
        return $this -> getAll("select * from {$this->getTableName()} where (invitee=? or inviter=?) and status=? order by addtime desc limit 15", [$uid,$uid,2]);
    }


    /*
     * 获取正在进行的PK
     * */
    public function getPkList()
    {
        return $this-> getAll("select * from {$this->getTableName()} where status=? order by adddtime desc", [1]);
    }
    /*
     * 获取所有场次
     * */
    public function getAllMatchList()
    {
        return $this->getAll("select * from {$this->getTableName()} where addtime>=? and status=?", ['2016-1-16 00:00:00',2]);
    }
    /*
     * 获取胜利最多的三个用户
     * */
    public function getUserTop3()
    {
        return $this->getAll();
    }
}
