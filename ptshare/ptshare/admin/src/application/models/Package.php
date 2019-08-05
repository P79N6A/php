<?php
class Package extends  Table{

	public function __construct($table = 'package', $primary = 'id')
	{
		$this->setDBConf("MYSQL_CONF_MALL");
		$this->setTableName($table);
		$this->setPrimary($primary);
	}

	public function getList($start, $limit, $package_num, $sn, $online, $sales_type, $type, $vip)
	{
		$condition_ar[] = " 1=? ";
		$params[] = 1;

		if (!empty($package_num)) {
			$condition_ar[] = " packageid = ? ";
			$params[] = $package_num;
		}

		if ($sales_type != '-1') {
			$condition_ar[] = " sales_type = ? ";
			$params[] = $sales_type;
		}

		if ($type != '-1') {
			$condition_ar[] = " type = ? ";
			$params[] = $type;
		}

		if ($vip != '-1') {
			$condition_ar[] = " vip = ? ";
			$params[] = $vip;
		}

		if (!empty($sn)) {
			$condition_ar[] = " sn = ? ";
			$params[] = $sn;
		}

		if (!empty($online)) {
			$condition_ar[] = " status = ? ";
			$params[] = $online;
		}

		$condition = implode(" and ", $condition_ar);

		$sql = "select * from ".$this->getTableName()." where {$condition} order by id desc ";

		$sql.= $limit > 0 ? " limit $start, $limit" : "";
		$data = $this->getAll($sql, $params);

		$sql_count = "select count(*) from ".$this->getTableName()." where {$condition} order by id desc ";
		$total = $this->getOne($sql_count, $params);

		return array($data, $total);
	}

	public function getInfo($id)
	{
		$query = "select * from ".$this->getTableName()." where id = ? ";
        $result = $this->getRow($query, array($id));
        if ($result) {
            $result['cover'] = Util::joinStaticDomain($result['cover']);
        }
        return $result;
	}

	public static function getStatusData()
	{
		return ['PREPARE' => '待编辑','ONLINE'=> '上架中', 'OFFLINE' => '下架中', 'SELLOUT' => '已售罄'];
	}

	public function getCoverByPackageids($packageids)
	{
		if(empty($packageids)) return array();

		$sql = "select packageid, cover from {$this->getTableName()} where packageid in (" . implode(",", $packageids) . ")";

		return $this->getAll($sql);
	}

	public  function createPackage($rent_price, $cover, $categoryid, $cover_type, $num, $location, $description, $contact, $deposit_price, $type, $vip)
	{
		$uids = [21000331,21000332,21000333,21000334,21000335,21000336,21000337,21000338,21000339,21000340];
		$sell_user_id = $uids[rand(0,10)];
		$info = [
				'rent_price' 	=> $rent_price,
				'deposit_price' => $deposit_price,
				"packageid"		=> IDGEN::generate(IDGEN::PACKAGEID),
				"sn"			=> SnowFlake::nextId(),
				"num"			=> $num,
				"categoryid"	=> $categoryid,
				"cover_type"	=> $cover_type,
				"cover"			=> str_replace('https://static.putaofenxiang.com', '', $cover),
				"location"		=> $location,
				"description"	=> $description,
				"contact"		=> $contact,
				"source"		=> 3,
				"sell_user_id"	=> $sell_user_id,
				'favorite_num'	=> mt_rand(0, 999),
				'view_num'		=> mt_rand(1, 99999),
				'source_uid'	=> $sell_user_id,
				'type'			=> $type,
				'vip'			=> $vip,
				"extends"		=> '',
				"status"		=> 'ONLINE',
				"addtime"		=> date("Y-m-d H:i:s")
		];

		return $this->insert($this->getTableName(), $info);
	}




	public function createPackageGoods($packageid, array $items)
	{
		$goods = new Goods();
		foreach ($items as $goodsid) {

			$info = [
					'package_id' => $packageid,
					'goods_id'	 => $goodsid,
			];

			$goods->updatePacked($goodsid);
			$this->insert("package_goods", $info);


		}
		return true;
	}

}