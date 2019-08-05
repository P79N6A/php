<?php

/**
 * @desc   守护
 * @author yangqing
 */
class DAOUserGuard extends DAOProxy
{
    /**
     * 构造方法
     */
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_MESSAGE");
        $this->setTableName("user_guard");
    }

    /**
     * 添加数据
     *
     * @param array $option            
     */
    public function addData($option)
    {
        return $this->replace($this->getTableName(), $option);
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
     * 获取用户购买主播守护的详情
     *
     * @param  int $uid            
     * @param  int $relateid            
     * @return array
     */
    public function getInfoByUidRelateid($uid, $relateid)
    {
        $sql = "SELECT * FROM ".$this->table." where uid=? and relateid=? order by id desc limit 1 ";
        return $this->getRow($sql, array('uid'=>$uid,'relateid'=>$relateid));
    }
    
    /**
     * 获取我守护的人的列表
     *
     * @param int $uid
     */
    public function getGuardingList($uid)
    {
        $sql  = "SELECT * FROM ".$this->table." where uid=?  order by type desc,endtime desc ";
        return $this->getAll($sql, array('uid'=>$uid));
    }
    
    /**
     * 获取守护我的人的列表
     *
     * @param int $uid
     */
    public function getGuardedList($relateid)
    {
        $sql = "SELECT * FROM ".$this->table." where relateid=?  order by type desc,endtime desc ";
        return $this->getAll($sql, array('relateid'=>$relateid));
    }
    
}
