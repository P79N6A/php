<?php
class DAOBuyingLog extends DAOProxy
{
    const RESULT_INIT   = 0;
    const RESULT_FINISH = 1;
    
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_MALL");
        $this->setTableName("buying_log");
        parent::__construct();
    }
    
    public function addBuyingLog($uid, $orderid, $buyid, $packageid, $sn, $extends, $result, $remark = '')
    {
        $option = array(
            'uid'       => $uid,
            'orderid'   => $orderid,
            'buyid'     => $buyid,
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
    public function updateBuyingLogResult($id, $result)
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