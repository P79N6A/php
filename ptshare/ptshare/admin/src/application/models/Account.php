<?php
class Account extends Table
{
	const CURRENCY_GRAPE  			= 1; // 葡萄账户
	const CURRENCY_FREEZE_GRAPE 	= 2; // 冻结葡萄账户
	const CURRENCY_CASH   			= 3; // 代金券账户
	const CURRENCY_FREEZE_CASH		= 4; // 冻结代金券账户

    public function __construct($uid)
    {
    		$this->setDBConf("MYSQL_CONF_PAYMENT");
    		$this->setTableName("account");
    		$this->setShardId($uid);
	}

	public function add($adminid, $title, $sourceid, $targetid, $status, $amount)
    {
        $info = array(
            "adminid"=>$adminid,
            "title"=>$title,
            "sourceid"=>$sourceid,
            "targetid"=>$targetid,
            "status"=>$status,
            "amount"=>$amount,
            "addtime"=>date('Y-m-d H:i:s'),
        );
    	return $this->insert('account_apply', $info);
    }

	public function update($id)
    {
        $sql = "update account_apply set status=1 where id=?";

        return $this->getAll($sql, $id);
    }

    public function getBalanceByUid($uid, $currency)
    {
        $sql = "select balance from " . $this->getTableName() . " where uid=? and currency=?";
        $balance = $this->getOne($sql, array($uid, $currency));
        $balance = !empty($balance) ? $balance : 0;

        return $balance;
    }

	public function getList($start, $num)
    {
        $sql = "select * from account where type=0";

        return $this->getAll($sql, null, false);
    }

	public function getApplyId($id)
    {
        $sql = "select * from account_apply where id=?";

        return $this->getRow($sql, $id);
    }

	public function getApplyList($start, $num)
    {
        $sql = "select * from account_apply order by id desc limit ?,?";

        return $this->getAll($sql, [$start, $num]);
    }

    public function getAllBalanceByUid($uid)
    {
        $sql = "SELECT uid,balance,currency,balance,addtime,modtime from " . $this->getTableName() . " WHERE uid=?";

        return $this->getAll($sql, $uid);
    }

    //获取所有账户余额
    public function getTotalBalance($currency,$filter_system=true){
        $tbls=range(0,99);
        $balance=0;
        foreach ($tbls as $i){
            $sql="select sum(balance) as total from "."account_".$i." where currency=? ";
            if ($filter_system){
                $sql.=" and uid not in (1000,1001,1002,1003,1004,1005,1006,1007,1008,1009,1100,1800,1850,1899,1900,1950,1999,2000) ";
            }

            $result=$this->getRow($sql,['currency'=>$currency]);
            $balance+=$result['total'];
        }
        return $balance;
    }
    
    public function updateAccount($uid, $currency, $balance, $edit)
    {
    	$info = array(
    			"uid"		=> $uid,
    			"currency"	=> $currency,
    			"balance"	=> $balance,
    			"modtime"	=> date('Y-m-d H:i:s'),
    	);
    	if (empty($edit)) {
    		$info['addtime'] = date('Y-m-d H:i:s');
    	}
    	return $this->replace($this->getTableName(), $info);
    }
}
