<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2017/8/15
 * Time: 14:23
 */
class DAOGameLog extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_GAME");
        $this->setTableName("game_log");
    }
    public function addGameLog($content,$plat,$ips)
    {
        $info = array(
            'content'   => $content,
            'plat'      => $plat,
            'ip'        => $ips,
            'addtime'   => date("Y-m-d H:i:s")
        );

        return $this->insert($this->getTableName(), $info);
    }
}
