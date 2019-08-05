<?php
class DAOVerifiedStudent extends DAOProxy
{
    const NOVERIFY = 0;//未认证
    const VERIFING = 1;//认证进行中
    const VERIFIED = 2;//认证完成
    const MODIFYVERIFING = 3;//认证修改中
    const VERIFY_FAIL = -1;//认证失败
    const VERIFY_CANCEL = -2;//认证取消

    public function __construct()
    {
        parent::__construct();
        $this->setDBConf("MYSQL_CONF_PASSPORT");
        $this->setTableName("verified_student");
    }

    public function getVerify($uid)
    {
        $sql = "select " . $this->_getFields() . " from " . $this->getTableName() . " where uid=?";

        return $this->getRow($sql, $uid);
    }

    public function addVerify($uid, $realname, $school, $year, $studentidImg, $reason, $status)
    {
        $verifiedinfo = array(
            "uid"=>$uid,
            "realname"=>$realname,
            "school"=>$school,
            "year"=>$year,
            "studentid_img"=>$studentidImg,
            "reason"=>$reason,
            "extends"=>'',
            "status"=>$status,
            "addtime"=>date('Y-m-d H:i:s'),
            "modtime"=>date('Y-m-d H:i:s')
        );

        return $this->replace($this->getTableName(), $verifiedinfo);
    }

    public function deleteVerify($uid)
    {
        return $this->delete($this->getTableName(), "uid = ?", array($uid));
    }

    public function updateVerify($uid, $realname, $school, $year, $studentidImg, $reason, $status=0)
    {
        $verifiedinfo = array(
            "realname"=>$realname,
            "school"=>$school,
            "year"=>$year,
            "studentid_img"=>$studentidImg,
            "reason"=>$reason,
            "modtime"=>date('Y-m-d H:i:s')
        );
        if(!empty($status)) {
            $verifiedinfo['status'] = $status;
        }

        return $this->update($this->getTableName(), $verifiedinfo, "uid = ?", array($uid));
    }

    public function applyModify($uid, $realname, $school, $year, $studentidImg)
    {
        $verifiedinfo['extends'] = json_encode(
            array(
            "realname"=>$realname,
            "school"=>$school,
            "year"=>$year,
            "studentid_img"=>$studentidImg,
            )
        );
        $verifiedinfo["status"] = self::MODIFYVERIFING;
        $verifiedinfo["modtime"] = date('Y-m-d H:i:s');

        return $this->update($this->getTableName(), $verifiedinfo, "uid = ?", array($uid));
    }

    public function getVerifiedSchoolByUid($uid)
    {
        $sql = "select school from " . $this->getTableName() . " where status=".self::VERIFIED." and uid=?";

        return $this->getOne($sql, $uid);
    }

    private function _getFields()
    {
        return "uid, realname, school, year, studentid_img, addtime, modtime, status, reason";
    }
}
