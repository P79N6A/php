<?php
class DAOQuickWord extends DAOProxy
{
    public function __construct($userid='')
    {
        $this->setDBConf("MYSQL_CONF_LIVE");
        $this->setTableName("quick_chat_word");
    }
    
    public function getList($uid)
    {
        $sql = "select * from {$this->getTableName()} where uid = ? ";
        $data = $this->getAll($sql, array($uid));//'CONFIRMED'
        return $data;
    }
    

    
    public function getTotal($uid)
    {
        $sql = "select count(*) as cnt from {$this->getTableName()} where uid =? ";
        $data = $this->getOne($sql, $uid);
        return $data;
    }
    
    public function getInfo($id)
    {
        $sql = "select * from {$this->getTableName()} where id =? limit 1 ";
        $data = $this->getRow($sql, $id);
        return $data;
    }
    
    public function add($uid, $content)
    {
        $info = array(
        "uid"        => $uid,
        "content"    => $content,
        "addtime"    => date("Y-m-d H:i:s")
        );
        
        return $this->insert($this->getTableName(), $info);
    }
    
    public function adminupdate($id, $adminid, $status) 
    {
        $info["modtime"] = date("Y-m-d H:i:s");
        $info['status'] = $status;
        $info['adminid'] = $adminid;
        return $this->update($this->getTableName(), $info, "id=?", $id);
    }
    
    public function modify($id, $content)
    {
        $info["modtime"] = date("Y-m-d H:i:s");
        $info['content'] = $content;
        return $this->update($this->getTableName(), $info, "id=?", $id);
    }
}