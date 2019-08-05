<?php

class DAOEmploye extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_FAMILY");
        $this->setTableName("employe");
    }

    public function isEmploye($authorid)
    {
        $sql = "SELECT familyid as fid from {$this->getTableName()} WHERE authorid=? AND apply_status='ACCEPT'";

        return $this->getRow($sql, $authorid);
    }

    public function getEmployePercent($authorid)
    {
        $sql = "SELECT familyid as fid, author_percent as percent, frozen_deposit as frozen, frozen_consume  from {$this->getTableName()} WHERE authorid=? ";

        return $this->getRow($sql, $authorid);
    }

    public function getEmployeInfo($familyid, $authorid)
    {
        $sql = "SELECT * from {$this->getTableName()} WHERE familyid=? AND authorid=?";

        return $this->getRow($sql, [$familyid, $authorid]);
    }

    public function getEmployeByuid($authorid)
    {
        $sql = "SELECT * from {$this->getTableName()} WHERE authorid=? ";

        return $this->getRow($sql, $authorid);
    }

    public function getAllEmployeByFamilyid($familyid)
    {
        $sql = "SELECT * from {$this->getTableName()} WHERE familyid=? AND apply_status='ACCEPT'";

        return $this->getAll($sql, $familyid);
    }

    public function getEmploye($familyid, $offset, $num)
    {
        $sql = "SELECT * from {$this->getTableName()} WHERE familyid=? ORDER BY modtime DESC LIMIT $offset, $num ";

        return $this->getAll($sql, [$familyid, $offset, $num]);
    }

    public function canJoin($authorid)
    {
        $sql = "SELECT authorid from {$this->getTableName()} WHERE authorid=? AND apply_status='REJECT'";

        return $this->getRow($sql, $authorid);
    }

    public function add($familyid, $authorid, $apply_status, $starttime, $expiretime, $author_percent, $global_percent)
    {
        $time = date("Y-m-d H:i:s");
        $info = [
            'familyid' => $familyid,
            'authorid' => $authorid,
            'apply_status' => $apply_status,
            'contract_status' => 'SIGNED',
            'author_percent' => $author_percent,
            'global_percent' => $global_percent,
            'starttime' => $starttime,
            'expiretime' => $expiretime,
            'addtime' => $time,
            'modtime' => $time,
        ];
        return $this->insert($this->getTableName(), $info);
    }

    public function reject($familyid, $authorid)
    {
        return $this->delete($this->getTableName(), "familyid=? and authorid=?", [$familyid, $authorid]);
    }

    public function ownerAccept($familyid, $authorid, $starttime, $expiretime, $author_percent, $global_percent, $contract_status)
    {
        $time = date("Y-m-d H:i:s");

        $sql = "SELECT * from {$this->getTableName()} WHERE authorid=? AND familyid=?";
        $data = $this->getRow($sql, [$authorid, $familyid]);
        if ($data) {
            $info = [
                'apply_status' => 'ACCEPT',
                'author_percent' => $author_percent,
                'global_percent' => $global_percent,
                'contract_status' => $contract_status,
                'starttime' => $starttime,
                'expiretime' => $expiretime,
                'modtime' => $time,
            ];

            return $this->update($this->getTableName(), $info, "authorid=? AND familyid=?", [$authorid, $familyid]);

        } else {
            $info = [
                'familyid' => $familyid,
                'authorid' => $authorid,
                'apply_status' => 'ACCEPT',
                'author_percent' => $author_percent,
                'global_percent' => $global_percent,
                'contract_status' => $contract_status,
                'starttime' => $starttime,
                'expiretime' => $expiretime,
                'addtime' => $time,
                'modtime' => $time,
            ];

            return $this->insert($this->getTableName(), $info);
        }
    }

    public function authorPercent($familyid, $authorid, $author_percent, $global_percent)
    {
        $info = [
            'author_percent' => $author_percent,
            'global_percent' => $global_percent,
            'modtime' => date("Y-m-d H:i:s"),
        ];
        return $this->update($this->getTableName(), $info, "familyid=? AND authorid=?", [$familyid, $authorid]);
    }
}
