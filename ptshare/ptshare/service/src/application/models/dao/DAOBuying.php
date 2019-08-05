<?php
class DAOBuying extends DAOProxy
{
    public function __construct(){
        $this->setDBConf("MYSQL_CONF_MALL");
        $this->setTableName("buying");
        parent::__construct();
    }

    /**
     * 购买
     * @param int $uid
     * @param int $packageid
     * @param sting $sn
     * @param int $num
     * @param int $grape
     * @param int $deposit_price
     * @param int $pay_price
     * @param int $pay_coupon
     * @param int $service_price
     * @param int $express_price
     * @return unknown
     */
    public function addBuying($uid, $packageid, $sn, $num, $grape, $pay_price, $pay_coupon, $orderid)
    {
        $option = array(
            'uid'           => $uid,
            'packageid'     => $packageid,
            'sn'            => $sn,
            'num'           => $num,
            'grape'         => $grape,
            'pay_price'     => $pay_price,
            'pay_coupon'    => $pay_coupon,
            'orderid'       => $orderid,
            'addtime'       => date('Y-m-d H:i:s')
        );
        return $this->insert($this->getTableName(), $option);
    }
    
    
    /**
     * 修改购买状态
     * @param int $rentid
     * @param int $status
     */
    public function updateBuyingStatus($buyid, $type, $status){
        $condition = ' buyid=? ';
        $params = array(
            'buyid' => $buyid
        );
        $option = array(
            'type'    => $type,
            'status'  => $status,
            'modtime' => date("Y-m-d H:i:s")
        );
        return $this->update($this->getTableName(), $option, $condition, $params);
    }
    
    /**
     * 修改购买完成状态
     * @param int $rentid
     * @param int $status
     */
    public function updateBuyingRelust($buyid, $type, $status, $result){
        $condition = ' buyid=? ';
        $params = array(
            'buyid' => $buyid
        );
        $option = array(
            'type'    => $type,
            'status'  => $status,
            'result'  => $result,
            'modtime' => date("Y-m-d H:i:s")
        );
        return $this->update($this->getTableName(), $option, $condition, $params);
    }
    
    /**
     * 购买详情
     * @param int $buyid
     * @return array
     */
    public function getBuyingInfo($buyid)
    {
        $sql = " SELECT * FROM " . $this->getTableName() . " WHERE buyid=? order by buyid desc limit 1";
        return $this->getRow($sql, $buyid);
    }
    
    /**
     * 购买详情
     * @param string $orderid
     * @return array
     */
    public function getBuyingInfoByOrderid($orderid){
        $sql = " SELECT * FROM " . $this->getTableName() . " WHERE orderid=? order by buyid desc limit 1";
        return $this->getRow($sql, $orderid);
    }
    
    /**
     * 批量获取购买详情
     * @param arry $buyids
     * @return array
     */
    public function getBuyingInfos($buyids){
        $sql = " SELECT * FROM {$this->getTableName()} where buyid in (".implode(',', $buyids).") ";
        return $this->getAll($sql);
    }
    
    /**
     * 购买列表
     * @param int $uid
     * @param string $status
     * @param int $num
     * @param int $offset
     */
    public function getBuyingList($uid, $type, $num, $offset){
        $where = "";
        if ($offset > 0) {
            $where .= " and buyid<" . $offset . " ";
        }
        $sql = " SELECT * FROM " . $this->getTableName() . " WHERE uid=? and type=? ";
        $sql .= $where;
        $sql .= " ORDER BY buyid DESC LIMIT " . $num;
        return $this->getAll($sql, array('uid' => $uid, 'type'=>$type));
    }
    
    /**
     * 总数
     * @param int $uid
     * @param string $status
     */
    public function getBuyingTotal($uid, $type){
        $sql = " SELECT count(*) as cnt FROM " . $this->getTableName() . " WHERE uid=? and type=?  ";
        return $this->getOne($sql, array('uid' => $uid, 'type'=>$type));
    }
    
    /**
     * 是否有下一页数据
     * @param int $uid
     * @param string $status
     * @param int $offset
     */
    public function getBuyingMore($uid, $type, $offset){
        $where = "";
        if ($offset > 0) {
            $where .= " and buyid<" . $offset . " ";
        }
        $sql = " SELECT count(buyid) as cnt FROM " . $this->getTableName() . " WHERE uid=? and type=? ";
        $sql .= $where;
        return $this->getOne($sql, array('uid' => $uid, 'type'=>$type));
    }
    
    /**
     * 是否重复购买
     * @param int $uid
     * @param int $packageid
     * @param string $sn
     */
    public function isExistBuying($uid, $packageid, $sn)
    {
        $addtime = date('Y-m-d H:i:s', time() - 3);
        $sql = "SELECT count(*) as cnt FROM ".$this->getTableName()." WHERE uid=? and packageid=? and sn=? and  addtime >'".$addtime."' ";
        $reault = $this->getRow($sql, array('uid' => $uid, 'packageid'=>$packageid, 'sn'=>$sn));
        if(isset($reault['cnt']) && $reault['cnt']>0){
            return true;
        }
        return false;
    }
}