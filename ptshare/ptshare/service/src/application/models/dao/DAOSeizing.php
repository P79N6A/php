<?php
class DAOSeizing extends DAOProxy
{
    public function __construct(){
        $this->setDBConf("MYSQL_CONF_MALL");
        $this->setTableName("seizing");
        parent::__construct();
    }
    
    
    public function addSeizing($uid, $packageid, $number, $grape, $orderid){
        
        //$msec    = rand(100,999);
        list($usec, $seconds) = explode(" ", microtime());
        $msec		= sprintf("%03d", round($usec*1000));
        $addtime 	= date("Y-m-d H:i:s", $seconds);
        $option = array(
            'uid'       => $uid,
            'packageid' => $packageid,
            'number'    => $number,
            'grape'     => $grape,
            'orderid'   => $orderid,
        	'addtime'   => $addtime,
            'msec'      => $msec,
            //'msec'      => Util::getMillisecond()%1000,rand(100,999),
        );
        $this->insert($this->getTableName(), $option);
        return $addtime.".".$msec;
    }
    
    /**
     * 修改获奖号码
     * @param int $packageid
     * @param int $win_number
     */
    public function updateSeizingWinNumberByPackageid($packageid, $win_number){
        $condition = ' packageid=? ';
        $params = array(
            'packageid' => $packageid
        );
        $option = array(
            'win_number' => $win_number,
            'type'       => 'Y',
            'modtime'    => date("Y-m-d H:i:s")
        );
        return $this->update($this->getTableName(), $option, $condition, $params);
    }
    
    /**
     * 夺宝详情
     * @param int $buyid
     * @return array
     */
    public function getSeizingInfo($id){
        $sql = " SELECT * FROM " . $this->getTableName() . " WHERE id=? order by id desc limit 1";
        return $this->getRow($sql, $id);
    }
    
    /**
     * 夺宝列表
     * @param int $uid
     * @param string $status
     * @param int $num
     * @param int $offset
     */
    public function getSeizingList($packageid, $num, $offset){
        $where = "";
        if ($offset > 0) {
            $where .= " and id<" . $offset . " ";
        }
        $sql = " SELECT * FROM " . $this->getTableName() . " WHERE packageid=? ";
        $sql .= $where;
        $sql .= " ORDER BY id DESC LIMIT " . $num;
        return $this->getAll($sql, array('packageid' => $packageid));
    }
    
    /**
     * 总数
     * @param int $uid
     * @param string $status
     */
    public function getSeizingTotal($packageid){
        $sql = " SELECT count(*) as cnt FROM " . $this->getTableName() . " WHERE packageid=?  ";
        return $this->getOne($sql, array('packageid' => $packageid));
    }
    
    /**
     * 是否有下一页数据
     * @param int $uid
     * @param string $status
     * @param int $offset
     */
    public function getSeizingMore($packageid, $offset){
        $where = "";
        if ($offset > 0) {
            $where .= " and id<" . $offset . " ";
        }
        $sql = " SELECT count(id) as cnt FROM " . $this->getTableName() . " WHERE packageid=? ";
        $sql .= $where;
        return $this->getOne($sql, array('packageid' => $packageid));
    }
    
    /**
     * 用户夺宝列表
     * @param int $uid
     * @param string $status
     * @param int $num
     * @param int $offset
     */
    public function getUserSeizingList($uid, $type, $num, $offset){
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
     * 用户夺宝总数
     * @param int $uid
     * @param string $status
     */
    public function getUserSeizingTotal($uid, $type){
        $sql = " SELECT count(*) as cnt FROM " . $this->getTableName() . " WHERE uid=? and type=?  ";
        return $this->getOne($sql, array('uid' => $uid, 'type'=>$type));
    }
    
    /**
     * 是否有下一页数据
     * @param int $uid
     * @param string $status
     * @param int $offset
     */
    public function getUserSeizingMore($uid, $type, $offset){
        $where = "";
        if ($offset > 0) {
            $where .= " and id<" . $offset . " ";
        }
        $sql = " SELECT count(id) as cnt FROM " . $this->getTableName() . " WHERE uid=? and type=? ";
        $sql .= $where;
        return $this->getOne($sql, array('uid' => $uid, 'type'=>$type));
    }
    
    /**
     * 是否已经夺宝
     * @param int $uid
     * @param int $packageid
     * @param string $sn
     */
    public function isExistSeizing($uid, $packageid)
    {
        $sql = "SELECT count(*) as cnt FROM ".$this->getTableName()." WHERE uid=? and packageid=? ";
        $reault = $this->getRow($sql, array('uid' => $uid, 'packageid'=>$packageid, 'sn'=>$sn));
        if(isset($reault['cnt']) && $reault['cnt']>0){
            return true;
        }
        return false;
    }
    
    /**
     * 获取中奖信息
     * @param string $packageid
     * @param int $number
     * @return array
     */
    public function getSeizingWinInfo($packageid, $number){
        $sql = " SELECT * FROM " . $this->getTableName() . " WHERE packageid=?  and number=? order by id desc limit 1";
        return $this->getRow($sql, array('packageid' => $packageid, 'number' => $number));
    }
    
    /**
     * 获取最后10数据
     * @param int $packageid
     * @return array
     */
    public function getLastTenSeizing($packageid){
        $sql = " SELECT addtime,msec FROM " . $this->getTableName() . " WHERE packageId=?  ORDER BY id DESC LIMIT 10 ";
        return $this->getAll($sql, array('packageid' => $packageid));
    }
    
    /**
     * 批量获取完成数
     * @param array $packageids
     * @return array
     */
    public function getSeizingCompleteAmount($packageids){
        $sql = "select packageid,count(*) as cnt from {$this->getTableName()} where packageid in (".implode(',', $packageids).") group by packageid ";
        return $this->getAll($sql);
    }
    
    /**
     * 获取未开奖数据
     */
    public function getNoPrizeList(){
        $sql = "select DISTINCT(packageid),count(*) as cnt from seizing where type='N' group by packageid";
        return $this->getAll($sql);
    }
}