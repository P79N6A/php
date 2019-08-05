<?php
class AccountManage extends Table
{
    const CURRENCY_DIAMOND 	= 2;
    const CURRENCY_TICKET 	= 1;
    const CURRENCY_CASH		= 3;
    const CURRENCY_COIN		= 4;//星光
    const CURRENCY_STAR      = 5;//星星

    public function __construct($uid)
    {
    	
    	
		if($uid){
// 			$this->setDBConf("MYSQL_CONF_PAYMENT");
// 			$sharding = $uid%100;
// 			parent::__construct("account_{$sharding}", "id", 'payment');
			$this->setDBConf("MYSQL_CONF_PAYMENT");
			$this->setShardId($uid);
			$this->setTableName("account");
		} else {
			$this->setDBConf("MYSQL_CONF_ADMIN");
			$this->setTableName("account_apply");
			parent::__construct();
		}
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
        $sql = "select * from account ";

        return $this->getAll($sql);
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

	public function getApplyListWhere($uid, $start_time, $end_time, $status, $start, $num)
    {
		if($uid && $status){
			$sql = "select * from account_apply where targetid=? and status=?  and (addtime between ? and ?)  order by id desc limit ?,?";
			return $this->getAll($sql, [$uid, $status, $start_time, $end_time, $start, $num]);
		} else {
			$sql = "select * from account_apply where  (addtime between ? and ?)  order by id desc limit ?,?";

			return $this->getAll($sql, [$start_time, $end_time, $start, $num]);
		}
        
    }
	public function getTotal()
    {
        $sql = "select count(*) from account_apply";

        return $this->getOne($sql);
    }
	public function getTotalWhere($uid, $start_time, $end_time, $status)
    {
		if($uid && $status){
			$sql = "select count(*) from account_apply where targetid=? and status=? and  (addtime between ? and ?)";

			return $this->getOne($sql, [$uid, $status, $start_time, $end_time]);
		} else {
			$sql = "select count(*) from account_apply where (addtime between ? and ?)";

			return $this->getOne($sql, [$start_time, $end_time]);
		}
    }

	public function getSumWhere($uid, $start_time, $end_time, $status)
    {
		if($uid && $status){
			$sql = "select sum(amount) as sum from account_apply where targetid=? and status=? and  (addtime between ? and ?)";

			return $this->getOne($sql, [$uid, $status, $start_time, $end_time]);
		} else {
			$sql = "select sum(amount) as sum from account_apply where (addtime between ? and ?)";

			return $this->getOne($sql, [$start_time, $end_time]);
		}
    }

    public function getAllBalanceByUid($uid)
    {
        $sql = "SELECT uid,balance,currency,balance,addtime,modtime from " . $this->getTableName() . " WHERE uid = ?";
        //echo $this->getTableName();

        return $this->getAll($sql, array($uid));
    }

}
