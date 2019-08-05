<?php
class DAOUserInviter extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PASSPORT");
        $this->setTableName("user_inviter");
    }

    public function addUserInviter($uid, $inviter, $type, $extend, $addtime)
    {
        $info = array(
            "uid"     => $uid,
            "inviter" => $inviter,
            "type"    => $type,
            "extend"  => $extend,
            "addtime" => $addtime
        );

        return $res = $this->insert($this->getTableName(), $info);
    }

   
}
?>
