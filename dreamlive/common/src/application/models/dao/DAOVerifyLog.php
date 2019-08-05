<?php
class DAOVerifyLog extends DAOProxy
{
    const STATUS_NONE      = 0;//未申请
    const STATUS_VERIFYING = 1;//申请中
    const STATUS_REFUSED   = 2;//申请拒绝
    const STATUS_PASSED    = 3;//申请通过
    const STATUS_CANCELED  = 4;//认证取消
    const STATUS_MODIFYING = 5;//修改信息申请中
    const STATUS_MODIFY_PASSED  = 6;//修改信息通过
    const STATUS_MODIFY_REFUSED = 7;//修改信息拒绝
    const STATUS_ADMIN_VERIFIED = 8;//后台认证
    const STATUS_ADMIN_MODIFY = 9;//后台修改信息

    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PASSPORT");
        $this->setTableName("verify_log");
    }

    public function getLogStatus($uid)
    {
        $sql = "select * from {$this->getTableName()} where uid=? order by id desc";
        return $this->getRow($sql, $uid);
    }

    public function addVerifyLog($uid, $apply_info, $apply_status, $result='', $result_status=0, $done='N')
    {
        /*{{{*/
        $apply_info = array(
            "uid"           => $uid,
            "apply_info"    => json_encode($apply_info),
            "apply_status"  => $apply_status,
            "result_info"   => json_encode($result),
            "result_status" => $result_status,
            "done"          => $done,
            "addtime"       => date("Y-m-d H:i:s"),
            "modtime"       => date("Y-m-d H:i:s"),
        );
        return $this->insert($this->getTableName(), $apply_info);
    } /* }}} */

    public function setResult($uid, $result, $result_status)
    {
        /*{{{*/
        $apply_info = array(
            "result_info"   => json_encode($result),
            "result_status" => $result_status,
            "done"          => 'Y',
            "modtime"       => date("Y-m-d H:i:s"),
        );
        return $this->update($this->getTableName(), $apply_info, "uid=? and done=?", array($uid, 'N'));
    } /* }}} */
}
?>
