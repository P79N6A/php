<?php
class DAORedPacket extends DAOProxy
{
    /**
     * 构造方法
     */
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("red_packet");
    }

    /**
     * 创建红包
     * @param int $uid
     * @param int $type
     * @param int $num
     */
    public function addRedPacket($uid, $type, $num)
    {
        $option = array(
            'uid'     => $uid,
            'num'     => $num,
            'type'    => $type,
            'status'  => 'N',
            'addtime' => date("Y-m-d H:i:s")
        );
        return $this->insert($this->getTableName(), $option);
    }

    /**
     * 修改红包状态
     * @param int $redid
     */
    public function updateRedPacketStatus($redid)
    {
        $condition = ' redid=? ';
        $params = array(
            'redid'  => $redid
        );
        $option = array(
            'status'  => 'Y',
            'modtime' => date("Y-m-d H:i:s")
        );
        return $this->update($this->getTableName(), $option, $condition, $params);
    }

    /**
     * 今日是否创建过红包
     * @param int $uid
     * @param int $type
     */
    public function isExistRedPacket($uid, $type){
        $date = date("Y-m-d")." 00:00:00";
        $sql = "SELECT redid FROM " . $this->getTableName() . " WHERE uid=? and type=? and addtime>'" . $date . "'";
        return $this->getOne($sql, array('uid' => $uid, 'type' => $type));
    }
}
?>
