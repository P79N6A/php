<?php

class LotteryTravel extends Table
{
	public function __construct()
	{
		$this->setDBConf("MYSQL_CONF_MALL");
		$this->setTableName("travel_lottery");
		$this->setPrimary("id");
	}

	public function getList($start, $limit, $name, $award)
	{
		$condition_ar[] = " isshow = ? ";
		$params[] = 1;

		if (!empty($name)) {
			$condition_ar[] = " name like '%{$name}%' ";
		}

		if (!empty($award)) {
			$condition_ar[] = " award like '%{$award}%' ";
		}

		$condition = implode(" and ", $condition_ar);

		$sql = "select * from ".$this->getTableName()." where {$condition} order by id desc ";

		$sql.= $limit > 0 ? " limit $start, $limit" : "";
		$data = $this->getAll($sql, $params);

		$sql_count = "select count(*) from ".$this->getTableName()." where {$condition} order by id desc ";
		$total = $this->getOne($sql_count, $params);

		return array($data, $total);
	}

	public  function createTravel($name, $award, $list_cover, $total, $num, $startime, $endtime, $detail_cover, $share_cover, $gate_cover)
	{
		$info = [
				'name' 			=> $name,
				'award' 		=> $award,
				"num"			=> $num,
				"list_cover"	=> $list_cover,
				"total"			=> $total,
				"startime"		=> $startime,
				"endtime"		=> $endtime,
				"detail_cover" 	=> $detail_cover,
				"share_cover"  	=> $share_cover,
				"gate_cover"	=> $gate_cover,
				"addtime"		=> date("Y-m-d H:i:s"),
				"isshow"		=> 1,
		];

		return $this->insert($this->getTableName(), $info);
	}

	public function updateTravel($id, $name, $award, $list_cover, $total, $num, $startime, $endtime, $detail_cover, $share_cover, $gate_cover)
	{
		$up_ar = [];
    	$where_ar = [];
    	if (!empty($name)) {
    		$where_ar[] = " name = ?";
    		$up_ar[] = $name;
    	}

    	if (!empty($award)) {
    		$where_ar[] = " award = ?";
    		$up_ar[] = $award;
    	}

    	if (!empty($list_cover)) {
    		$where_ar[] = " list_cover = ?";
    		$up_ar[] = $list_cover;
    	}

    	if (!empty($detail_cover)) {
    		$where_ar[] = " detail_cover = ?";
    		$up_ar[] = $detail_cover;
    	}

    	if (!empty($share_cover)) {
    		$where_ar[] = " share_cover = ?";
    		$up_ar[] = $share_cover;
    	}

    	if (!empty($detail_cover)) {
    		$where_ar[] = " detail_cover = ?";
    		$up_ar[] = $detail_cover;
    	}

    	if (!empty($total)) {
    		$where_ar[] = " total = ?";
    		$up_ar[] = $total;
    	}

    	if (!empty($num)) {
    		$where_ar[] = " num = ?";
    		$up_ar[] = $num;
    	}

    	if (!empty($startime)) {
    		$where_ar[] = " startime = ?";
    		$up_ar[] = $startime;
    	}

    	if (!empty($endtime)) {
    		$where_ar[] = " endtime = ?";
    		$up_ar[] = $endtime;
    	}

    	$where_ar[] = " modtime = ?";
    	$up_ar[] = date("Y-m-d H:i:s");

    	$up_ar[] = $id;

    	$where = implode(",", $where_ar);
    	$sql = "update ". $this->getTableName() ." set  $where where id = ?  limit 1";
		return $this->execute($sql, $up_ar);
		$info = [
				'name' 		=> $name,
				'award' 	=> $award,
				"num"		=> $num,
				"cover"		=> $cover,
				"total"		=> $total,
				"startime"	=> $startime,
				"endtime"	=> $endtime,
				"modtime"	=> date("Y-m-d H:i:s")
		];
		return $this->update($this->getTableName(), $info, " id=? ", array($id));
	}

	public function getInfo($id)
    {
        $query = "select * from ".$this->getTableName()." where id = ? ";
        return $this->getRow($query, array($id));
    }
}