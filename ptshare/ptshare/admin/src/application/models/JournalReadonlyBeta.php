<?php

class JournalReadonlyBeta extends Table
{
    protected $uid = 0;

    public function __construct($uid)
    {
//         $this->uid = $uid;
//         $shardid = abs(substr($uid, -2));
//         parent::__construct("journal_{$shardid}", "id", "payment");
    	$this->setDBConf("MYSQL_CONF_PAYMENT");
    	$this->setShardId($uid);
    	$this->setTableName("journal");
    }

	public function getListByUid($start, $num, $starttime, $endtime)
    {
        $where_time = '';
        $sql_param = array($this->uid);

        if(!empty($starttime) || !empty($endtime)) {
            $where_time .= " and addtime >= ? and addtime <= ? ";
            $sql_param[] = $starttime;
            $sql_param[] = $endtime;
        }

        $sql = "select * from {$this->getTableName()} where uid=? and direct='OUT' and currency=1 {$where_time} order by addtime desc limit {$start}, {$num}";

        return $this->getAll($sql, $sql_param);
    }
	public function getTotalByUid($start, $num, $starttime, $endtime) {
        $where_time = '';
        $sql_param = array($this->uid);

        if (!empty($starttime) || !empty($endtime)) {
            $where_time .= " and addtime >= ? and addtime <= ? ";
            $sql_param[] = $starttime;
            $sql_param[] = $endtime;
        }

        $sql = "select count(*) from {$this->getTableName()} where uid=? and direct='OUT' and currency=1 {$where_time} ";

        return $this->getOne($sql, $sql_param);
    }

	public function getCountByUid($param = array())
    {
        $pdata = array($this->uid);
        if(!empty($param['starttime']) && !empty($param['endtime'])) {
            $durtime = ' and addtime >= ? and addtime <= ? ';
            $pdata[] = $param['starttime'];
            $pdata[] = $param['endtime'];
        }
        $sql = "select sum(amount) as sum from {$this->getTableName()} where uid=? and direct='IN' and currency=1 {$durtime}";
        $ticket_in = $this->getRow($sql, $pdata);

		$sql = "select sum(amount) as sum from {$this->getTableName()} where uid=? and direct='OUT' and currency=1 {$durtime}";
        $ticket_out = $this->getRow($sql, $pdata);

		$sql = "select sum(amount) as sum from {$this->getTableName()} where uid=? and direct='IN' and currency=2 {$durtime}";
        $dismod_in = $this->getRow($sql, $pdata);

		$sql = "select sum(amount) as sum from {$this->getTableName()} where uid=? and direct='OUT' and currency=2 {$durtime}";
        $dismod_out = $this->getRow($sql, $pdata);

		return array('ticket_in'=>$ticket_in['sum'], 'ticket_out'=>$ticket_out['sum'], 'dismod_in'=>$dismod_in['sum'], 'dismod_out'=>$dismod_out['sum']);

    }



    //获取游戏机器人充值记录
    public function getSumRobots($uid, $type, $currency, $direct, $begin_time, $end_time)
    {
        $sql = "SELECT sum(amount) as sum_amount FROM {$this->getTableName()} WHERE uid=? and type=? AND currency=? AND direct=? AND addtime BETWEEN ? AND ?";
        return $this->getOne($sql, [$uid, $type, $currency, $direct, $begin_time, $end_time]);
    }

    public function getHasJournal($begin_time, $end_time)
    {
        $sql = "SELECT uid,currency FROM {$this->getTableName()} WHERE addtime BETWEEN ? AND ? GROUP BY uid,currency";
        return $this->getAll($sql, [$begin_time, $end_time]);
    }

    public function getSumJournal($uid, $currency, $direct, $begin_time, $end_time)
    {
        $sql = "SELECT  sum(amount) as sum_amount FROM {$this->getTableName()} WHERE uid=? AND currency=? AND direct=? AND addtime BETWEEN ? AND ?";
        return $this->getOne($sql, [$uid, $currency, $direct, $begin_time, $end_time]);
    }

	public function fixReceiveGift($uid, $currency, $direct, $begin_time, $end_time)
    {
        $sql = "SELECT  sum(amount) as sum_amount FROM {$this->getTableName()} WHERE uid=? AND currency=? AND type!=2 AND direct=? AND addtime BETWEEN ? AND ?";

        $data1 = $this->getOne($sql, [$uid, $currency, $direct, $begin_time, $end_time]);

		$sql = "SELECT  sum(amount) as sum_amount FROM {$this->getTableName()} WHERE uid=? AND currency=? AND type IN (30, 31) AND direct=? AND addtime BETWEEN ? AND ?";

        $data2 = $this->getOne($sql, [$uid, $currency, $direct, $begin_time, $end_time]);

		return $data1 + $data2;

    }

    public function getSumTypeJournal($type, $currency, $direct, $begin_time, $end_time)
    {
        $sql = "SELECT sum(amount) as sum_amount FROM {$this->getTableName()} WHERE type=? AND currency=? AND direct=? AND addtime>? AND addtime<=?";
        return $this->getOne($sql, [$type, $currency, $direct, $begin_time, $end_time]);
    }

    public function getOrderidJournal($type, $currency, $direct, $begin_time, $end_time)
    {
        $sql = "SELECT uid,orderid,amount FROM {$this->getTableName()} WHERE type=? AND currency=? AND direct=? AND addtime>? AND addtime<=?";
        return $this->getAll($sql, [$type, $currency, $direct, $begin_time, $end_time]);
    }

    public function getSummarySumTypeJournal($type, $currency, $direct, $end_time)
    {
        $sql = "SELECT sum(amount) as sum_amount FROM {$this->getTableName()} WHERE type=? AND currency=? AND direct=? AND addtime<=?";
        return $this->getOne($sql, [$type, $currency, $direct, $end_time]);
    }

    public function getBalanceSumJournal($type, $direct, $begin_time, $end_time)
    {
        $type = implode(',', $type);
        $sql = "SELECT sum(amount) as sum_amount FROM {$this->getTableName()} WHERE type IN ({$type}) AND amount>0 AND direct=? AND addtime BETWEEN ? AND ?";
        return $this->getOne($sql, [$direct, $begin_time, $end_time]);
    }

    public function getSumByOrderid($uid, $orderid, $type, $direct, $begin_time, $end_time)
    {
        $orderid = implode(',', $orderid);
        $sql = "SELECT sum(amount) as sum_amount,currency FROM {$this->getTableName()} WHERE uid=? AND orderid IN({$orderid}) AND type=? AND direct=? AND addtime>? AND addtime<=? GROUP BY currency";

        return $this->getAll($sql, [$uid, $type, $direct, $begin_time, $end_time]);
    }

    public function getInfoByOrderid($uid, $orderid, $type, $direct, $begin_time, $end_time)
    {
        $orderid = implode(',', $orderid);
        $sql = "SELECT * FROM {$this->getTableName()} WHERE uid=? AND orderid IN({$orderid}) AND type=? AND direct=? AND addtime>? AND addtime<=?";

        return $this->getAll($sql, [$uid, $type, $direct, $begin_time, $end_time]);
    }

    public function getAllInfoByOrderid($orderid)
    {
        $sql = "SELECT * FROM {$this->getTableName()} WHERE orderid=?";

        return $this->getAll($sql, $orderid);
    }

    public function getCountUidJournal($uid, $type, $currency, $direct, $begin_time = '', $end_time = '')
    {
        $sql = "SELECT count(*) as cnum FROM {$this->getTableName()} WHERE uid=? AND type=? AND currency=? AND direct=?";
        $params = [$uid, $type, $currency, $direct];

        if ($begin_time) {
            $sql .= ' AND addtime>? ';
            $params[] = $begin_time;
        }

        if ($end_time) {
            $sql .= ' AND addtime<=? ';
            $params[] = $end_time;
        }

        return $this->getOne($sql, $params);
    }


	public function getAllByGift($uid, $begin_time = '', $end_time = '')
    {
        $sql = "select receiver, sum(price*num) as sum from giftlog where sender in($uid) and addtime>'$begin_time' AND addtime<='$end_time' GROUP BY receiver ORDER BY sum desc";

        return $this->getAll($sql, $uid, false);
    }


	public function getAllType($uid)
    {
		$sql = "SELECT sum(amount) as sum, type from {$this->getTableName()} where uid=?  and currency=? and direct='IN' GROUP BY type";
        $dismod_in = $this->getAll($sql, [$uid, 2]);

		$sql = "SELECT sum(amount) as sum, type from {$this->getTableName()} where uid=?  and currency=? and direct='OUT' GROUP BY type";
        $dismod_out = $this->getAll($sql, [$uid, 2]);


		$sql = "SELECT sum(amount) as sum, type from {$this->getTableName()} where uid=?  and currency=? and direct='IN' GROUP BY type";
        $ticket_in = $this->getAll($sql, [$uid, 1]);

		$sql = "SELECT sum(amount) as sum, type from {$this->getTableName()} where uid=?  and currency=? and direct='OUT' GROUP BY type";
        $ticket_out = $this->getAll($sql, [$uid, 1]);



		$sql = "SELECT sum(amount) as sum, type from {$this->getTableName()} where uid=?  and currency=? and direct='IN' GROUP BY type";
        $money_in = $this->getAll($sql, [$uid, 3]);

		$sql = "SELECT sum(amount) as sum, type from {$this->getTableName()} where uid=?  and currency=? and direct='OUT' GROUP BY type";
        $money_out = $this->getAll($sql, [$uid, 3]);

		$sql = "SELECT sum(amount) as sum, type from star_{$this->getTableName()} where uid=?  and currency=? and direct='IN' GROUP BY type";
        $coin_in = $this->getAll($sql, [$uid, 4]);

		$sql = "SELECT sum(amount) as sum, type from star_{$this->getTableName()} where uid=?  and currency=? and direct='OUT' GROUP BY type";
        $coin_out = $this->getAll($sql, [$uid, 4]);

		$sql = "SELECT sum(amount) as sum, type from star_{$this->getTableName()} where uid=?  and currency=? and direct='IN' GROUP BY type";
        $star_in = $this->getAll($sql, [$uid, 5]);

		$sql = "SELECT sum(amount) as sum, type from star_{$this->getTableName()} where uid=?  and currency=? and direct='OUT' GROUP BY type";
        $star_out = $this->getAll($sql, [$uid, 5]);
		

		return array('dismod_in'=>$dismod_in, 'dismod_out'=>$dismod_out, 'ticket_in'=>$ticket_in, 'ticket_out'=>$ticket_out,'money_in'=>$money_in, 'money_out'=>$money_out,'coin_in'=>$coin_in,'coin_out'=>$coin_out,'star_in'=>$star_in,'star_out'=>$star_out);

    }

    public function getSumUidJournal($uid, $type, $currency, $direct, $begin_time = '', $end_time = '')
    {
        $sql = "SELECT sum(amount) as sum_amount FROM {$this->getTableName()} WHERE uid=? AND type=? AND currency=? AND direct=?";
        $params = [$uid, $type, $currency, $direct];

        if ($begin_time) {
            $sql .= ' AND addtime>? ';
            $params[] = $begin_time;
        }

        if ($end_time) {
            $sql .= ' AND addtime<=? ';
            $params[] = $end_time;
        }

        return $this->getOne($sql, $params);
    }

	public function getSumGroupUidJournal($uids)
    {
		if(is_array($uids)){
			foreach ($uids as $key => $value) {
				$tmp = (int)substr($value, -2);
				$uids_arr[$tmp][] = $value;
			}
			$sql = array();
			for($i=0;$i<100;$i++){
				$uids_new = $uids_arr[$i];
				$uids_str = implode(',', $uids_new);
				if($uids_str){
					$sql[] = "SELECT SUM(amount) as num, uid FROM `journal_{$i}` WHERE direct='IN' AND currency=2  and type = 1 and uid in($uids_str) group by uid ";
				}
				
			}
			$sql_str = implode(" union all ", $sql);

		}
        $result = $this->getAll($sql_str, null, false);
        
        $list = array();
        foreach($result as $v){
            $list[$v['uid']] = $v['num'];
        }

		return $list;
    }

	public function getSumUidJournalNotInType($uid, $type, $currency, $direct, $begin_time = '', $end_time = '')
    {
        $sql = "SELECT sum(amount) as sum_amount FROM {$this->getTableName()} WHERE uid=? AND type not in($type) AND currency=? AND direct=?";
        $params = [$uid, $currency, $direct];

        if ($begin_time) {
            $sql .= ' AND addtime>? ';
            $params[] = $begin_time;
        }

        if ($end_time) {
            $sql .= ' AND addtime<=? ';
            $params[] = $end_time;
        }

        return $this->getOne($sql, $params, false);
    }

    public function getListByUidType($uid, $type, $direct, $currency, $begin_time = '', $end_time = '', $offset = 0, $num = 50, $typeno=0)
    {
        $condition[] = 'uid=?';
        $params[] = $uid;

        $condition[] = 'direct=?';
        $params[] = $direct;

        if ($begin_time) {
            $condition[] = 'addtime>=?';
            $params[] = $begin_time;
        }

        if ($end_time) {
            $condition[] = 'addtime<=?';
            $params[] = $end_time;
        }
		
		$condition = implode(" AND ", $condition);
        if($type){
			$type = implode(', ', $type);
			if($type){
				if($typeno){
					$condition .= " AND type not in ({$type})";
				} else {
					$condition .= " AND type in ({$type})";
				}
			}
        }

		if($currency){
			$condition .= " AND currency='$currency'";
		}

        return $this->getRecords($condition, $params, $offset, $num, 'id desc');
    }

    public function getSumByUidType($uid, $type, $direct, $currency, $begin_time = '', $end_time = '', $typeno=0)
    {
        $condition[] = 'uid=?';
        $params[] = $uid;

        $condition[] = 'direct=?';
        $params[] = $direct;

        if ($begin_time) {
            $condition[] = 'addtime>=?';
            $params[] = $begin_time;
        }

        if ($end_time) {
            $condition[] = 'addtime<=?';
            $params[] = $end_time;
        }

        $condition = implode(' AND ', $condition);
		if($type){
			$type = implode(', ', $type);
			if($type){
				if($typeno){
					$condition .= " AND type not in ({$type})";
				} else {
					$condition .= " AND type in ({$type})";
				}
			}
			
		}

		if($currency){
			$condition .= " AND currency='$currency'";
		}
        

        $sql = "SELECT sum(amount) as sum_amount FROM {$this->getTableName()} WHERE {$condition}";

        return $this->getOne($sql, $params);
    }

    public function getTransfer($begin_time, $end_time)
    {
        $params = [];
        if ($begin_time) {
            $condition[] = 'addtime>=?';
            $params[] = $begin_time;
        }

        if ($end_time) {
            $condition[] = 'addtime<=?';
            $params[] = $end_time;
        }

        $condition = implode(' AND ', $condition);

        $sql = "SELECT uid, orderid, addtime,  group_concat(currency Separator ';') AS currency_all, group_concat(amount Separator ';') AS score FROM {$this->getTableName()}
                WHERE {$condition} AND uid={$this->uid} AND type=4 GROUP BY orderid ORDER BY addtime";

        return $this->getAll($sql, $params);
    }

    public function getJournalByUid($uid, $type, $currency, $direct, $begin_time, $end_time)
    {
        $type = implode(',', $type);
        $sql = "SELECT * FROM {$this->getTableName()} WHERE uid=? AND type IN ({$type}) AND currency=? AND direct=? AND addtime>? AND addtime<=?";

        return $this->getAll($sql, [$uid, $currency, $direct, $begin_time, $end_time]);
    }

	public function getCorporateList($uid, $addtime)
    {
        $sql = "select * from {$this->getTableName()} where uid=? and direct='IN' and currency=1 and type=51 and addtime<='$addtime' order by id desc limit 1";
		$data = $this->getAll($sql, $uid);
		$return_data = array();
		if($data[0]['orderid']){
			$sql = "select * from {$this->getTableName()} where orderid=? and direct='IN' and currency=1 and type=51";
			$return_data = $this->getAll($sql, $data[0]['orderid']);
		}

        return $return_data;
    }

}
