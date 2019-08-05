<?php
class DAORenting extends DAOProxy
{
    public function __construct(){
        $this->setDBConf("MYSQL_CONF_MALL");
        $this->setTableName("renting");
        parent::__construct();
    }

    public function addRenting($uid, $relateid, $orderid, $packageid, $sn, $num, $month, $rent_type, $express_type, $rent_price, $deposit_price, $pay_price,$pay_coupon, $service_price, $express_price)
    {
        $option = array(
            'uid'           => $uid,
            'relateid'      => $relateid,
            'orderid'       => $orderid,
            'packageid'     => $packageid,
            'sn'            => $sn,
            'num'           => $num,
            'month'         => $month,
            'rent_type'     => $rent_type,
            'express_type'  => $express_type,
            'rent_price'    => $rent_price,
            'deposit_price' => $deposit_price,
            'pay_price'     => $pay_price,
            'pay_coupon'    => $pay_coupon,
            'service_price' => $service_price,
            'express_price' => $express_price,
            'addtime'       => date('Y-m-d H:i:s')
        );
        return $this->insert($this->getTableName(), $option);
    }
    
    /**
     * 是否租借过
     * @param int $uid
     * @param int $packageid
     * @param string $sn
     * @param int $month
     */
    public function isExistRenting($uid, $packageid, $sn, $month)
    {
        $addtime = date('Y-m-d H:i:s', time() - 3);
        $sql = "SELECT count(*) as cnt FROM ".$this->getTableName()." WHERE uid=? and packageid=? and sn=? and month=? and addtime >'".$addtime."' ";
        $reault = $this->getRow($sql, array('uid' => $uid, 'packageid'=>$packageid, 'sn'=>$sn, 'month'=>$month));
        if(isset($reault['cnt']) && $reault['cnt']>0){
            return true;
        }
        return false;
    }
    
    /**
     * 获取租用信息
     * @param int $id
     * @return array
     */
    public function getRentingInfoByOrderid($orderid)
    {
    	$sql = " SELECT * FROM " . $this->getTableName() . " WHERE orderid=? order by rentid desc limit 1";
    	return $this->getRow($sql, $orderid);
    }

    /**
     * 获取租用信息
     * @param int $rentid
     * @return array
     */
    public function getRentingInfo($rentid)
    {
        $sql = " SELECT * FROM " . $this->getTableName() . " WHERE rentid=? order by rentid desc limit 1";
        return $this->getRow($sql, $rentid);
    }
    
    /**
     * 批量获取租用信息
     * @param int $rentid
     * @return array
     */
    public function getRentingInfos($rentids)
    {
        $sql = " SELECT * FROM " . $this->getTableName() . " WHERE rentid in (".implode(',', $rentids).")";
        return $this->getAll($sql);
    }
    
    /**
     * 获取上一次的租用流水
     * @param int $relateid
     * @return array
     */
    public function getPrevRentingInfoByRelateid($relateid){
        $sql = " SELECT * FROM " . $this->getTableName() . " WHERE relateid=? order by rentid desc limit 1";
        return $this->getRow($sql, $relateid);
    }
    
    /**
     * 获取上一次的租用流水
     * @param int $relateid
     * @return array
     */
    public function getPrevRentingInfoBySn($sn){
        $sql = " SELECT * FROM " . $this->getTableName() . " WHERE sn=? and status=1 order by rentid desc limit 1";
        return $this->getRow($sql, $sn);
    }

    /**
     * 修改租用状态
     * @param int $rentid
     * @param int $status
     */
    public function updateRentingResult($rentid, $type, $status, $result){
        $condition = ' rentid=? ';
        $params = array(
            'rentid' => $rentid
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
     * 修改状态
     * @param int $rentid
     * @param int $type
     * @param int $status
     * @param int $result
     */
    public function updateRentingStatus($rentid, $type, $status){
        $condition = ' rentid=? ';
        $params = array(
            'rentid' => $rentid
        );
        $option = array(
            'type'    => $type,
            'status'  => $status,
            'modtime' => date("Y-m-d H:i:s")
        );
        return $this->update($this->getTableName(), $option, $condition, $params);
    }
    
    
    /**
     * 确认支付订单
     * @param int $rentid
     * @param int $status
     */
    public function confirm($rentid, $pay_price, $pay_coupon){
    	$condition = ' rentid=? ';
    	$params = array(
    			'rentid' => $rentid
    	);
        $option = array(
            "type"       => UserRenting::USER_RENTING_TYPE_RECEIVED,
            "status"     => UserRenting::USER_RENTING_TYPE_RECEIVED_ST_NO_SEND,
            'pay_price'  => $pay_price,
            'pay_coupon' => $pay_coupon,
            'modtime'    => date("Y-m-d H:i:s")
        );
    	return $this->update($this->getTableName(), $option, $condition, $params);
    }

}