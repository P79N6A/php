<?php

class DAOClubmember extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_CLUB");
        $this->setTableName('member');
    }

    public function getInfoByUid($uid)
    {
        $sql = "SELECT * from {$this->getTableName()} WHERE uid=? ";

        return $this->getRow($sql, $uid);
    }

    public function create($uid, $clubid, $role)
    {
        $now = date('Y-m-d H:i:s');
        $info = [
            'uid' => $uid,
            'clubid' => $clubid,
            'role' => $role,
            'addtime' => $now,
            'modtime' => $now,
        ];

        return $this->insert($this->getTableName(), $info);
    }

    public function reject($uid)
    {
        return $this->delete($this->getTableName(), "uid=? ", $uid);
    }

    public function rejectAll($clubid)
    {
        return $this->delete($this->getTableName(), "clubid=? ", $clubid);
    }

    public function updateRole($uid, $role)
    {
        $info = [
            'role' => $role,
            'modtime' => date('Y-m-d H:i:s'),
        ];

        return $this->update($this->getTableName(), $info, "uid=?", $uid);
    }

    public function getList($clubid, $offse, $num)
    {
        $sql = "SELECT * FROM {$this->getTableName()} WHERE clubid=? DESC LIMIT ?,?";

        return $this->getAll($sql, [$clubid, $offse, $num]);
    }
}