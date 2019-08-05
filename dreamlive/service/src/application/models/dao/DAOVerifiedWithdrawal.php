<?php
class DAOVerifiedWithdrawal extends DAOProxy
{
    const STATUS_NONE = 0;//待审核
    const STATUS_YES  = 1;//通过
    const STATUS_NO   = 2;//拒绝

    public function __construct()
    {
        $this->setDBConf('MYSQL_CONF_PASSPORT');
        $this->setTableName('verified_withdrawal');
    }

    public function getItems($uid)
    {
        $sql = "select * from {$this->getTableName()} where uid=?";
        return $this->getRow($sql, $uid);
    }

    public function add($data)
    {
        $in_data['uid'] = $data['uid'];
        $in_data['realname'] = $data['realname'];
        $in_data['status'] = $data['status'];
        $in_data['mobile'] = $data['mobile'];
        $in_data['idcard'] = $data['idcard'];
        $in_data['img_a'] = $data['img_a'];
        $in_data['img_b'] = $data['img_b'];
        $in_data['img_s'] = $data['img_s'];
        $in_data['addtime'] = date('Y-m-d H:i:s');
        $in_data['modtime'] = date('Y-m-d H:i:s');
        return $this->insert($this->getTableName(), $in_data);
    }
    
    public function modify($uid, $data)
    {
        //删除用户uid，防止更新uid
        if(isset($data['uid'])) {
            unset($data['uid']);
        }
        
        //处理更新字段
        $fields = array();
        if($data) {
            foreach($data as $k=>$v){
                $fields[] = "$k='$v'";
            }      
        }
        $fields = implode(',', $fields);  
        
        $sql = "UPDATE {$this->getTableName()} SET $fields WHERE uid in($uid)";
        return $this->execute($sql, null, false);
    }
}
?>
