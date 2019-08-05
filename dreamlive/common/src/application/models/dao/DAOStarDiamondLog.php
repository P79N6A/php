<?php
/**
 * 星星兑换星钻记录表
 * User: User
 * Date: 2018/1/8
 * Time: 16:25
 */
class DAOStarDiamondLog extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("star_diamond_log");
    }
    public function addStartDiamondlog($uid, $star_amount, $diamond_amount, $type=1)
    {
        $info = array(
            'uid'       => $uid,
            'type'      => $type,
            'star_amount' => $star_amount,
            'diamond_amount' => $diamond_amount,
            'addtime'   => date("Y-m-d H:i:s")
        );

        return $this->insert($this->getTableName(), $info);
    }
}
