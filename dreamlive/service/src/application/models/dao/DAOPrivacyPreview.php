<?php
class DAOPrivacyPreview extends DAOProxy
{

    /**
     * 构造方法
     */
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_LIVE");
        $this->setTableName("privacy_preview");
    }

    /**
     * 添加数据
     * 
     * @param array $option            
     */
    public function addPrivacyPreview($privacyid, $uid)
    {
        $option = array();
        $option['privacyid'] = $privacyid;
        $option['uid'] = $uid;
        $option['addtime'] = date("Y-m-d H:i:s");
        
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
     * 是否预览过
     *
     * @param  int $privacyid
     * @param  int $uid
     * @return boolean
     */
    public function exists($privacyid, $uid)
    {
        $sql = "select count(*) as cnt from {$this->getTableName()} where privacyid=? and uid =? limit 1";
        return $this->getOne($sql, array($privacyid, $uid)) > 0;
    }
}
