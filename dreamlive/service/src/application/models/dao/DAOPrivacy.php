<?php
class DAOPrivacy extends DAOProxy
{
    static $_fields = "privacyid, uid, live_price, replay_price, startime, `endtime`, preview, freetime, addtime, modtime, paylong";
    
    private function _getFields()
    {
        return self::$_fields;
    }
    
    /**
     * 构造方法
     */
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_LIVE");
        $this->setTableName("privacy");
    }

    /**
     * 添加数据
     * 
     * @param array $option            
     */
    public function addPrivacy($uid, $live_price, $replay_price, $startime, $paylong, $preview, $freetime)
    {
        $s = strtotime($startime);
        $endtime = date("Y-m-d H:i:s", ($s + $paylong));
        
        $option = array();
        $option['uid']            = $uid;
        $option['live_price']     = $live_price;
        $option['replay_price'] = $replay_price;
        $option['startime']     = $startime;
        $option['endtime']        = $endtime;
        $option['paylong']        = $paylong;
        $option['preview']        = $preview;
        $option['freetime']        = $freetime;
        $option['addtime']        = date("Y-m-d H:i:s");
        
        try {
            $privacyid = $this->lastPrivacyId($uid);
            if (!empty($privacyid)) {
                $option['modtime']        = date("Y-m-d H:i:s");
                $this->update($this->getTableName(), $option, "privacyid=?", $privacyid);
                return $privacyid;
            } else {
                $this->insert($this->getTableName(), $option);
                 
                return $this->getInsertId();
            }
        } catch (MySQLException $e) {
            throw new BizException(ERROR_SYS_DB_SQL);
        }
    }

    /**
     * 延长私密直播时间
     * 
     * @param int   $id            
     * @param array $option            
     */
    public function updateData($id, $time)
    {
        
        $option = array();
        $option['modtime'] = date("Y-m-d H:i:s");
        $option['endtime'] = date("Y-m-d H:i:s", $time);
        
        return $this->update($this->getTableName(), $option, "privacyid=?", $id);
    }
    
    /**
     * 修改私密直播价格
     *
     * @param int   $id
     * @param array $option
     */
    public function modifyReplayPrice($id, $replay_price)
    {
         
        $option = array();
        $option['modtime'] = date("Y-m-d H:i:s");
        $option['replay_price'] = $replay_price;
         
        return $this->update($this->getTableName(), $option, "privacyid=?", $id);
    }
    
    
    /**
     * 删除私密信息
     *
     * @param int   $id
     * @param array $option
     */
    public function remove($id)
    {
        
        $option = array();
        $option['modtime'] = date("Y-m-d H:i:s");
        $option['deleted'] = 'Y';
        
        return $this->update($this->getTableName(), $option, "privacyid=?", $id);
    }
    
    /**
     * 删除数据
     * 
     * @param  int $id            
     * @return boolean
     */
    public function del($id)
    {
        return $this->delete($this->getTableName(), 'privacyid = ?', $id);
    }
    
    /**
     * 获取私密直播详情
     *
     * @param int $privacyid
     */
    public function getPrivacyInfoById($privacyid)
    {
        $sql = "select {$this->_getFields()} from {$this->getTableName()} where privacyid=?";
        return $this->getRow($sql, $privacyid);
    }
    
    /**
     * 上次设置的收费房间
     *
     * @param  int $uid
     * @return int
     */
    public function lastPrivacyId($uid)
    {
        $sql = "select privacyid from {$this->getTableName()} where uid =? and unix_timestamp(`startime`) >= ? and deleted = ? order by privacyid desc limit 1";
    
        return $this->getOne($sql, array($uid, time(), 'N'));
    }
    
    /**
     * 是否开过私密房间
     *
     * @param  int $uid
     * @return array
     */
    public function liveExists($uid, $now)
    {
        $sql = "select {$this->_getFields()} from {$this->getTableName()} where uid =? and unix_timestamp(`startime`) <= ? and unix_timestamp(`endtime`) >= ? and deleted  = ? order by privacyid desc limit 1";
        
        return $this->getRow($sql, array($uid, $now, $now, 'N'));
    }
    
    public function repeatPrivacyLive($uid, $startime)
    {
        $sql = "select {$this->_getFields()} from {$this->getTableName()} where uid= ? and `startime` = ? order by privacyid desc limit 1";
        
        return $this->getRow($sql, array($uid, $startime));
    }
    
    /**
     * 是否开过私密房间
     *
     * @param  int $uid
     * @return array
     */
    public function exists($uid, $startime)
    {
        //$startime     = strtotime($startime);
        $sql = "select {$this->_getFields()} from {$this->getTableName()} where uid= ? and `startime` > ? order by privacyid desc limit 1";

        return $this->getRow($sql, array($uid, $startime));
    }
    
    /**
     * 获取当前或回放私密直播信息
     *
     * @param int      $uid
     * @param datetime $startime
     * @param datetime $endtime
     */
    public function getPrivacy($uid, $startime, $endtime)
    {
        //         $startime     = strtotime($startime);
        //         $endtime     = strtotime($endtime);
        //         $sql = "select {$this->_getFields()} from {$this->getTableName()} where uid= ? and (
        //         (unix_timestamp(`startime`) > ? and unix_timestamp(`endtime`) < ?) or 
        //         (unix_timestamp(`startime`) < ? and unix_timestamp(`endtime`) > ?) or 
        //         (unix_timestamp(`startime`) < ? and unix_timestamp(`startime`) > ?) or 
        //         ( unix_timestamp(`endtime`) > ? and unix_timestamp(`endtime`) < ?)) limit 1";
        $sql = "select {$this->_getFields()} from {$this->getTableName()} where uid= ? and (
    	(`startime` > ? and `endtime` < ?) or
    	(`startime` < ? and `endtime` > ?) or
    	(`startime` < ? and `startime` > ?) or
    	( `endtime` > ? and `endtime` < ?)) limit 1";
        //$sql = "select {$this->_getFields()} from {$this->getTableName()} where uid= ? and ((unix_timestamp(`startime`) < ? and unix_timestamp(`endtime`) > ?) ) limit 1";
        return $this->getRow(
            $sql, array($uid, 
            $startime, $endtime,
                 $startime, $endtime, 
            $endtime, $startime, 
            $startime, $endtime)
        );
    }
}
