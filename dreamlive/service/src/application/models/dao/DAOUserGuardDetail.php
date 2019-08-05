<?php

/**
 * @desc   守护购买明细
 * @author yangqing
 */
class DAOUserGuardDetail extends DAOProxy
{

    /**
     * 构造方法
     */
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_MESSAGE");
        $this->setTableName("user_guard_detail");
    }

    /**
     * 添加数据
     *
     * @param array $option            
     */
    public function addData($option)
    {
        return $this->insert($this->getTableName(), $option);
    }

    /**
     * 修改数据
     *
     * @param int   $id            
     * @param array $option            
     */
    public function updateData($id, $option)
    {
        return $this->update($this->getTableName(), $option, "id=?", $id);
    }
    /**
     * @param $orderid
     */
    public function getUserGuardDetailByOrderid($orderid)
    {
        return $this -> getRow("select * from {$this->getTableName()} where orderid=? limit 1", $orderid);
    }
}
