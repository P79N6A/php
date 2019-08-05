<?php
class DAOInvite extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_MALL");
        $this->setTableName("invite");
    }

    public function addInvite($uid, $inviter, $extend, $addtime, $expiretime)
    {
        $info = array(
            "uid"     => $uid,
            "inviter" => $inviter,
            "extend"  => $extend,
            "addtime" => $addtime,
            "expiretime" => $expiretime,
        );
        $info['status'] = "N";

        return $res = $this->replace($this->getTableName(), $info);
    }

    public function getUserInviter($uid)
    {
        $sql = "select * from " . $this->getTableName() . " where uid=? and expiretime >? and status = 'N'";

        return $this->getRow($sql, [$uid, date('Y-m-d H:i:s')]);
    }

}
?>
