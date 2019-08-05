<?php
class DAOJournal extends DAOProxy
{
	public function __construct($userid)
	{
		$this->setDBConf("MYSQL_CONF_PAYMENT");
		$this->setShardId($userid);
		$this->setTableName("journal");
	}

	public function add($orderid, $uid, $type, $direct, $currency, $amount, $remark, $extends = array())
	{
		$trade_info = array(
				"orderid"	=> $orderid,
				"uid"		=> $uid,
				"type"	    => $type,
				"direct"	=> $direct,
				"currency"  => $currency,
				"amount"	=> $amount,
				"remark"	=> $remark,
				"extends"	=> !empty($extends) ? json_encode($extends) : '',
				"addtime"	=> date("Y-m-d H:i:s")
		);
		return $this->insert($this->getTableName(), $trade_info);
	}

	/**
	 * 流水列表
	 * @param int $uid
	 * @param int $num
	 * @param int $offset
	 */
	public function getJournalList($uid, $currency, $type, $direct, $num, $offset){
	    //$where = " and currency=? and type in (".$type.") and direct ='".$direct."' ";
	    $where = " and currency=?  ";
	    if ($offset > 0) {
	        $where .= " and id<" . $offset . " ";
	    }
	    $sql = " SELECT * FROM " . $this->getTableName() . " WHERE uid=? ";
	    $sql .= $where;
	    $sql .= " ORDER BY id DESC LIMIT " . $num;
	    return $this->getAll($sql, array('uid' => $uid, 'currency'=>$currency));
	}

	/**
	 * 流水列表总数
	 * @param int $uid
	 * @param date $startime
	 * @param date $endtime
	 */
	public function getJournalTotal($uid, $currency, $type, $direct){
	    //$where = " and currency=? and type in (".$type.") and direct ='".$direct."' ";
	    $where = " and currency=?  ";
	    $sql   = " SELECT count(*) as cnt FROM " . $this->getTableName() . " WHERE uid=?  ";
	    $sql  .= $where;
	    return $this->getOne($sql, array('uid' => $uid, 'currency'=>$currency));
	}

	/**
	 * 是否有下一页数据
	 * @param int $uid
	 * @param string $type
	 * @param int $offset
	 */
	public function getMoreJournalList($uid, $currency, $type, $direct, $offset){
	    //$where = " and currency=? and type in (".$type.") and direct ='".$direct."' ";
	    $where = " and currency=?  ";
	    if ($offset > 0) {
	        $where .= " and id<" . $offset . " ";
	    }
	    $sql  = " SELECT * FROM " . $this->getTableName() . " WHERE uid=? ";
	    $sql .= $where;
	    return $this->getOne($sql, array('uid' => $uid, 'currency'=>$currency));
	}
}

?>