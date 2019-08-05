<?php

class DAOFamilyApply extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_FAMILY");
        $this->setTableName("apply");
    }

    public function add($familyid, $applicant, $approver, $type, $value, $old_value, $reason)
    {
        $now = date('Y-m-d H:i:s');

        $info = [
            'familyid' => $familyid,
            'applicant' => $applicant,
            'approver' => $approver,
            'type' => $type,
            'apply_status' => 'APPLY',
            'value' => $value,
            'old_value' => $old_value,
            'reason' => $reason,
            'addtime' => $now,
            'modtime' => $now,
        ];

        return $this->insert($this->getTableName(), $info);
    }

    public function getApplyInfo($applyid)
    {
        $sql = "SELECT * FROM {$this->getTableName()} WHERE applyid=?";

        return $this->getRow($sql, $applyid);
    }

    public function updateApply($applyid, $apply_status, $result)
    {
        $info = [
            'apply_status' => $apply_status,
            'result' => $result,
            'modtime' => date('Y-m-d H:i:s'),
        ];

        return $this->update($this->getTableName(), $info, "applyid=?", $applyid);
    }

    public function getApply($familyid, $uid, $offset, $num)
    {
        $sql = "SELECT * FROM {$this->getTableName()} WHERE familyid=? AND approver=? ORDER BY modtime DESC LIMIT ?,?";

        return $this->getAll($sql, [$familyid, $uid, $offset, $num]);
    }

    public function getApproverCount($familyid, $uid, $apply_status)
    {
        $sql = "SELECT count(*) as cnum FROM {$this->getTableName()} WHERE familyid=? AND approver=? AND apply_status=? ";

        return $this->getOne($sql, [$familyid, $uid, $apply_status]);
    }

    /**
     * 发起申请
     *
     * @param  $uid
     * @param  $apply_status
     * @return mixed
     */
    public function getApplicantByStatus($uid, $apply_status)
    {
        $sql = "SELECT * FROM {$this->getTableName()} WHERE applicant=? AND apply_status=?";

        return $this->getAll($sql, [$uid, $apply_status]);
    }

    /**
     * 待审核
     *
     * @param  $uid
     * @param  $apply_status
     * @return mixed
     */
    public function getApproverByStatus($uid, $apply_status)
    {
        $sql = "SELECT * FROM {$this->getTableName()} WHERE approver=? AND apply_status=?";

        return $this->getAll($sql, [$uid, $apply_status]);
    }


    public function getApplicantByType($uid, $type, $apply_status)
    {
        $sql = "SELECT * FROM {$this->getTableName()} WHERE applicant=? AND type=? AND apply_status=?";

        return $this->getAll($sql, [$uid, $type, $apply_status]);
    }

    public function getInfoByBoth($applicant, $approver, $type, $apply_status)
    {
        $sql = "SELECT * FROM {$this->getTableName()} WHERE applicant=? AND approver=? AND type=? AND apply_status=? LIMIT 1";

        return $this->getRow($sql, [$applicant, $approver, $type, $apply_status]);
    }

    public function getInfoByAddtime($type, $duetime, $status)
    {
        $sql = "SELECT * FROM {$this->getTableName()} WHERE type=? AND addtime<? AND apply_status=? ";

        return $this->getAll($sql, [$type, $duetime, $status]);
    }
}