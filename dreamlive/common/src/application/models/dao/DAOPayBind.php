<?php
class DAOPayBind extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("paybind");
    }
    
    public function add($uid, $account, $realname, $source)
    {
        if (!empty($this->getPaybindTotalByUid($uid))) {
            $this->setDefaulted($uid);
        }
        
        $info = array(        
        "uid"        => $uid,
        "realname"    => $realname,
        "account"    => $account,
        "source"    => $source,
        "defaulted"    =>  'Y',
        "addtime"    => date("Y-m-d H:i:s")
        );
        
        return $this->insert($this->getTableName(), $info);
    }
    
    
    public function setDefaulted($uid)
    {
        $info = array(
        "defaulted"    => 'N',
        "modtime"    => date("Y-m-d H:i:s")
        );
        
        return $this->update($this->getTableName(), $info, "uid=?", $uid);
    }
    
    public function edit($uid, $account, $realname, $source, $relateid)
    {
        $info = array(
        "uid"        => $uid,
        "modtime"    => date("Y-m-d H:i:s")
        );
        !empty($realname) ? $info['realname'] = $realname : '';
        !empty($account) ? $info['account'] = $account : '';
        !empty($source) ? $info['source'] = $source : '';
        return $this->update($this->getTableName(), $info, "id=?", $relateid);
    }
    
    public function getPaybindTotalByUid($uid) 
    {
        $sql = "select count(*) from " . $this->getTableName() . " where uid=?";
        return $this->getOne($sql, $uid);
    }
    
    public function getPaybindById($id)
    {
        $sql = "select * from " . $this->getTableName() . " where id=?";
        return $this->getRow($sql, $id);
    }
    
    public function getPayBindList($uid)
    {
        $sql = "select account,realname,source,defaulted,addtime,modtime,id from {$this->getTableName()} where uid=? ";
        
        return $this->getAll($sql, $uid);
    }
}
?>