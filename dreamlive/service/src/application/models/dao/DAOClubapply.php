<?php

class DAOClubapply extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_CLUB");
        $this->setTableName('apply');
    }

    public function getInfoById($applyid)
    {
        $sql = "SELECT * from {$this->getTableName()} WHERE applyid=? ";
        return $this->getRow($sql, $applyid);
    }


    public function create($clubid, $applicant, $approver, $type, $apply_status, $value, $old_value, $reason)
    {
        $now = date('Y-m-d H:i:s');
        $info = [
            'clubid' => $clubid,
            'applicant' => $applicant,
            'approver' => $approver,
            'type' => $type,
            'apply_status' => $apply_status,
            'value' => $value,
            'old_value' => $old_value,
            'reason' => $reason,
            'addtime' => $now,
            'modtime' => $now,
        ];

        return $this->insert($this->getTableName(), $info);
    }

    public function update($applyid, $apply_status)
    {
        $info = [
            'apply_status' => $apply_status,
        ];

        return $this->update($this->getTableName(), $info, "applyid=?", $applyid);
    }

    public function cleanAll($clubid)
    {
        return $this->delete($this->getTableName(), "clubid=? ", $clubid);
    }

    public function getList($clubid, $offse, $num)
    {
        $sql = "SELECT * FROM {$this->getTableName()} WHERE clubid=? AND apply_status=? ORDER BY applyid DESC LIMIT ?,?";

        return $this->getAll($sql, [$clubid, 'APPLY', $offse, $num]);
    }
}