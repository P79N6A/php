<?php
class Frozen extends Table
{
    public function __construct()
	{
		$this->setDBConf("MYSQL_CONF_ADMIN");
		$this->setTableName("frozen_log");
		$this->setPrimary("uid");
    }

    public function getFrozenList($cond, $start, $num=20)
    {

        if($cond['uid']){
            $condition   .= " uid in (".$cond['uid'].")";
        }
        $sql = "select * from {$this->getTableName()}";
        $sql.= $condition != "" ? " where " . $condition : "";
        $sql.= " order by addtime desc ";
        $sql.= $num > 0 ? " limit $start, $num" : "";
        $lists = $this->getAll($sql);
        //$total = $this->getOne("select count(*) from {$this->getTableName()}  ".($condition != "" ? " where " . $condition : ""));
        $total = 99999;
        return array($total, $lists);
    }

    public function isFrozen($uid)
    {
        $row  = $this->getRecord($uid);
        return !empty($row) ? true:false;
    }

    public function frozen($uid, $reason)
    {
        $info = array(
            "uid"=>$uid,
            "reason"=>$reason,
            "addtime"=>date("Y-m-d H:i:s"),
        );
        
        $account = new Account($uid);
        $amount = $account->getBalance($uid, Account::CURRENCY_GRAPE);
        ShareClient::frozen($uid, $amount, $reason);
        return $this->addRecord($info);
    }

    public function unFrozen($uid)
    {
        $sql = "delete from {$this->getTableName()} where  uid=?";
        $account = new Account($uid);
        $amount = $account->getBalance($uid, Account::CURRENCY_FREEZE_GRAPE);
        ShareClient::unfrozen($uid, $amount);
        return $this->Execute($sql, array($uid));
    }
}
