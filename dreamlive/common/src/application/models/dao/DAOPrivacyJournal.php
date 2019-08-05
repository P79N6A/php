<?php
class DAOPrivacyJournal extends DAOProxy
{

    /**
     * 构造方法
     */
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_LIVE");
        $this->setTableName("privacy_journal");
    }

    /**
     * 添加数据
     * 
     * @param array $option            
     */
    public function addPrivacyJournal($privacyid, $author, $buyer, $type, $price, $orderid)
    {
        try {
            $option = array();
            $option['privacyid'] = $privacyid;
            $option['author']     = $author;
            $option['buyer']     = $buyer;
            $option['type']         = $type;
            $option['price']     = $price;
            $option['orderid']     = $orderid;
            $option['addtime']     = date("Y-m-d H:i:s");
            
            return $this->insert($this->getTableName(), $option);
        } catch (MySQLException $e) {
            throw new BizException(ERROR_SYS_DB_SQL);
        }
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
     * 删除数据
     * 
     * @param  int $id            
     * @return boolean
     */
    public function del($id)
    {
        return $this->delete($this->getTableName(), 'id = ?', $id);
    }
    
    /**
     * 是否购买过
     *
     * @param  int $privacyid
     * @param  int $uid
     * @return boolean
     */
    public function exists($privacyid, $uid)
    {
        $sql = "select count(*) as cnt from {$this->getTableName()} where privacyid=? and buyer =? limit 1";
        return $this->getOne($sql, array($privacyid, $uid));
    }
    /**
     * @param $orderid
     */
    public function getPrivacyJournalByOrderid($orderid)
    {
        return $this -> getRow("select * from {$this->getTableName()} where orderid=? limit 1", $orderid);
    }
}
