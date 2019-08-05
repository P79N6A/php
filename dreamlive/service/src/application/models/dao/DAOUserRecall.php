<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018/3/2
 * Time: 11:26
 */
class DAOUserRecall extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_ACTIVITY_JAVA");
        $this->setTableName("user_recall");
    }
    public function addUserRecall($sharid, $userid, $type, $phone)
    {
        $info   = array(
            'inviter'   => $sharid?$sharid:0,
            'invitee'   => $userid?$userid:0,
            'rid'       => $phone?$phone:0,
            'deviceid'  => '0',
            //'type'      => $type,
            'diamond'   => 0,
            'addtime'   => date("Y-m-d H:i:s"),
            'modtime'   => date("Y-m-d H:i:s")
        );
        return $this -> insert($this->getTableName(), $info);
    }
    public function modUserRecall($sharid, $userid, $phone)
    {
        $info   = array(
            'inviter'   => $sharid,
            'modtime'   => date("Y-m-d H:i:s")
        );
        return $this -> update($this->getTableName(), $info, '(invitee=? or rid=?) and status=?', [$userid,$phone,0]);
    }

    public function getRank($num=50)
    {
        $sql="select count(inviter) as num,sum(diamond) as amount,inviter as uid from ".$this->getTableName()." where diamond>0 group by uid order by amount desc limit {$num}";
        $re=$this->getAll($sql, '');
        array_walk(
            $re, function (&$v,$k) {
                $v['rank']=$k+1;
            } 
        );
        return $re;
    }

    public function getUserRank($uid)
    {
        return $this->getRow("select count(inviter) as num,sum(diamond) as amount from ".$this->getTableName()." where inviter=? group by inviter", array('inviter'=>$uid));
    }

    public function isUsedDeviceid($deviceid)
    {
        $sql = "select count(*) from {$this->getTableName()} where deviceid=? and status > 0";
        return (int) $this->getOne($sql, array($deviceid)) > 0;
    }

    public function isUsedUid($uid)
    {
        $sql = "select count(*) from {$this->getTableName()} where invitee=? and status > 0";
        return (int) $this->getOne($sql, array($uid)) > 0;
    }
    public function existsInvitee($uid)
    {
        $sql = "select count(*) from {$this->getTableName()} where invitee=?";
        return (int) $this->getOne($sql, array($uid)) > 0;
    }

    public function modUserRecalDiamondOld($invitee, $oldDiamond = 0)
    {
        $info   = array(
            'old_diamond'   => $oldDiamond,
            'status'   => 1,
            'modtime'   => date("Y-m-d H:i:s")
        );

        return $this->update($this->getTableName(), $info, 'invitee=?', array($invitee));
    }

    public function modUserRecalDiamond($invitee, $diamond = 0)
    {
        $info   = array(
            'diamond'   => $diamond,
            'status'   => 2,
            'modtime'   => date("Y-m-d H:i:s")
        );

        return $this->update($this->getTableName(), $info, 'invitee=?', array($invitee));
    }

    public function modUserRecalType($invitee, $type, $deviceid)
    {
        $info = array(
            'type'     => $type,
            'deviceid' => $deviceid,
            'modtime'  => date("Y-m-d H:i:s")
        );

        return $this->update($this->getTableName(), $info, 'invitee=?', array($invitee));
    }

    public function adddInvitee($invitee, $rid)
    {
        $info = array(
            'invitee' => $invitee,
            'modtime' => date("Y-m-d H:i:s")
        );

        return $this->update($this->getTableName(), $info, 'rid=?', array($rid));
    }
}
