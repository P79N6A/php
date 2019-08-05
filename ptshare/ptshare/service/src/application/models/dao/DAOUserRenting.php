<?php
class DAOUserRenting extends DAOProxy
{
    public function __construct(){
        $this->setDBConf("MYSQL_CONF_MALL");
        $this->setTableName("user_renting");
        parent::__construct();
    }
    
    public function addUserRenting($uid, $packageid, $sn, $num, $month, $grape)
    {
        $option = array(
            'uid'       => $uid,
            'packageid' => $packageid,
            'sn'        => $sn,
            'num'       => $num,
            'month'     => $month,
            'grape'     => $grape,
            'addtime'   => date('Y-m-d H:i:s')
        );
        return $this->insert($this->getTableName(), $option);
    }

    /**
     * 续租
     * @param int $rentid
     * @param strng $endtime
     */
    public function delayUserRenting($id, $month, $grape, $endtime, $type, $status)
    {
        $modtime = date("Y-m-d H:i:s");
        $sql  = " UPDATE ".$this->getTableName()." SET month=?, grape=?, endtime=?, modtime=?, type=?, status=? WHERE id=? ";
        return $this->Execute($sql, array($month, $grape, $endtime, $modtime, $type, $status,  $id))? true : false;
    }
    
    /**
     * 获取上一次的租用流水
     * @param int $relateid
     * @return array
     */
    public function getPrevUserRentingInfoBySn($uid, $sn){
        //$sql = " SELECT * FROM " . $this->getTableName() . " WHERE uid != ? and sn=? and status=".UserRenting::USER_RENTING_TYPE_TRANSMIT." order by id desc limit 1";
        //return $this->getRow($sql, array($uid, $sn));
        $sql = " SELECT * FROM " . $this->getTableName() . " WHERE sn=? and status=".UserRenting::USER_RENTING_TYPE_TRANSMIT." order by id desc limit 1";
        return $this->getRow($sql, $sn);
    }
    
    /**
     * 修改租用状态
     * @param int $rentid
     * @param int $status
     */
    public function updateUserRentingStatus($id, $type, $status){
        $condition = ' id=? ';
        $params = array(
            'id' => $id
        );
        $option = array(
            'type'    => $type,
            'status'  => $status,
            'modtime' => date("Y-m-d H:i:s")
        );
        return $this->update($this->getTableName(), $option, $condition, $params);
    }
    
    /**
     * 修改rentid
     * @param int $id
     * @param int $rentid
     */
    public function updateUserRentingRentidOrderid($id, $rentid, $orderid){
        $condition = ' id=? ';
        $params = array(
            'id' => $id
        );
        $option = array(
            'rentid'  => $rentid,
            'orderid' => $orderid,
            'modtime' => date("Y-m-d H:i:s")
        );
        return $this->update($this->getTableName(), $option, $condition, $params);
    }
    
    /**
     * 收货状态修改
     * @param int $id
     * @param int $type
     * @param int $status
     * @param date $startime
     * @param date $endtime
     */
    public function updateUserRentingReceive($id, $type, $status, $startime, $endtime){
        $condition = ' id=? ';
        $params = array(
            'id' => $id
        );
        $option = array(
            'startime' => $startime,
            'endtime'  => $endtime,
            'type'     => $type,
            'status'   => $status,
            'modtime'  => date("Y-m-d H:i:s")
        );
        return $this->update($this->getTableName(), $option, $condition, $params);
    }
    
    /**
     * 租用info
     * @param int $uid
     * @param int $rentid
     * @return array
     */
    public function getUserRentingInfo($id)
    {
        $sql = " SELECT * FROM " . $this->getTableName() . " WHERE id=? order by id desc limit 1";
        return $this->getRow($sql, $id);
    }
    
    /**
     * 租用列表
     * @param int $uid
     * @param string $status
     * @param int $num
     * @param int $offset
     */
    public function getUserRentingList($uid, $type, $num, $offset){
        $where = "";
        if ($offset > 0) {
            $where .= " and id<" . $offset . " ";
        }
        $sql = " SELECT * FROM " . $this->getTableName() . " WHERE uid=? and type=? ";
        $sql .= $where;
        $sql .= " ORDER BY id DESC LIMIT " . $num;
        return $this->getAll($sql, array('uid' => $uid, 'type'=>$type));
    }
    
    /**
     * 总数
     * @param int $uid
     * @param string $status
     */
    public function getUserRentingTotal($uid, $type){
        $sql = " SELECT count(*) as cnt FROM " . $this->getTableName() . " WHERE uid=? and type=?  ";
        return $this->getOne($sql, array('uid' => $uid, 'type'=>$type));
    }
    
    /**
     * 是否有下一页数据
     * @param int $uid
     * @param string $status
     * @param int $offset
     */
    public function getUserRentingMore($uid, $type, $offset){
        $where = "";
        if ($offset > 0) {
            $where .= " and id<" . $offset . " ";
        }
        $sql = " SELECT count(id) as cnt FROM " . $this->getTableName() . " WHERE uid=? and type=? ";
        $sql .= $where;
        return $this->getOne($sql, array('uid' => $uid, 'type'=>$type));
    }

    /**
     * 获取租用即将到期的待传递数据
     * @param date $startime
     * @param date $endtime
     * @return array
     */
    public function getUserRentingExpire($startime, $endtime)
    {
        $sql = " SELECT * FROM " . $this->getTableName() . " WHERE type = ".UserRenting::USER_RENTING_TYPE_RENTING." and status = ".UserRenting::USER_RENTING_TYPE_RENTING_ST_INIT." and endtime > '" . $startime . "' and endtime <  '" . $endtime . "' ";
        return $this->getAll($sql);
    }
    
    /**
     * 获取租用即将到期的租用完成数据
     * @param date $startime
     * @param date $endtime
     * @return array
     */
    public function getUserRentingExpireFinish($startime, $endtime)
    {
        $sql = " SELECT * FROM " . $this->getTableName() . " WHERE type = ".UserRenting::USER_RENTING_TYPE_TRANSMIT." and status = ".UserRenting::USER_RENTING_TYPE_TRANSMIT_ST_INIT." and endtime > '" . $startime . "' and endtime <  '" . $endtime . "' ";
        return $this->getAll($sql);
    }
    
    
}