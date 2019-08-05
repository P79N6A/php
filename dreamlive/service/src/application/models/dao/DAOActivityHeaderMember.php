<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018/1/11
 * Time: 17:27
 */
class DAOActivityHeaderMember extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_GAME");
        $this->setTableName("activity_header_member");
    }
    public function addHeaderMember($code, $uid)
    {
        $info   = array(
            'uid'   => $uid,
            'code'  => $code,
            'addtime' => date("Y-m-d H:i:s")
        );
        return $this->insert($this->getTableName(), $info);
    }
}
