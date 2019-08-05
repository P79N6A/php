<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018/1/11
 * Time: 16:03
 */
class DAOActivityHeaderSummit extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_GAME");
        $this->setTableName("activity_header_summit");
    }
    public function addSummit($code)
    {
        $info   = array(
            'uid'   => 0,
            'code'  => $code,
            'addtime' => date("Y-m-d H:i:s")
        );

        return $this->insert($this->getTableName(), $info);
    }
    public function getEmptyCode()
    {
        $sql = "select code from {$this->getTableName()} where uid=? limit ? for UPDATE";

        return $this->getOne($sql, [0,1]);
    }
    public function modEmptyCode($code, $uid)
    {
        return $this->update($this->getTableName(), array('uid'=>$uid), 'code=? and uid=?', [$code,0]);
    }
}
