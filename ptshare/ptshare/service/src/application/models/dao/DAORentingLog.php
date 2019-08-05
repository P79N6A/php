<?php
class DAORentingLog extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_MALL");
        $this->setTableName("renting_log");
        parent::__construct();
    }

    public function addRentingLog($uid, $orderid, $relateid, $rentid, $packageid, $sn, $extends, $result, $remark = '')
    {
        $option = array(
            'uid'       => $uid,
            'orderid'   => $orderid,
            'relateid'  => $relateid,
            'rentid'    => $rentid,
            'packageid' => $packageid,
            'sn'        => $sn,
            'extends'   => $extends,
            'remark'    => $remark,
            'result'    => $result,
            'addtime'   => date('Y-m-d H:i:s'),
            'modtime'   => date('Y-m-d H:i:s')
        );
        return $this->insert($this->getTableName(), $option);
    }

    /**
     * 修改状态
     * @param int $id
     * @param int $result
     */
    public function updateRentingLogResult($id, $result)
    {
        $condition = ' id=? ';
        $params = array(
            'id' => $id
        );
        $option = array(
            'result'  => $result,
            'modtime' => date("Y-m-d H:i:s")
        );
        return $this->update($this->getTableName(), $option, $condition, $params);
    }
}