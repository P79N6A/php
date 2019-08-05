<?php
class Goods extends Table
{
	public function __construct($table = 'goods', $primary = 'id')
	{
	    parent::__construct();
		$this->setDBConf("MYSQL_CONF_MALL");
		$this->setTableName($table);
		$this->setPrimary($primary);
	}

    const

        STATUS_DEFAULT      = 100,
        STATUS_AUDIT        = 110,
        STATUS_IN_STORAGE   = 200,
        STATUS_OUT_STORAGE  = 300,
        STATUS_FAIL         = 500,
        STATUS_DESTROY      = 400,

        UNPACKED            = 1,
        PACKED              = 2,

        END = '';

    public function getList($page, $limit, $status, $sellid, $packageid, $category, $create_start_time, $create_end_time)
    {

        $page = max($page - 1, 0);

        $where = ' 1 = ?';
        $params[] = 1;

        if ($status > 0) {
            $where .= " and status = ?";
            $params[] = $status;
        }

        if ($sellid > 0) {
            $where .= " and sellid = ?";
            $params[] = $sellid;
        }

        if ($packageid > 0) {
            $where .= " and packageid = ?";
            $params[] = $packageid;
        }

        if ($category) {
            $where .= " and category = ?";
            $params[] = $category;
        }

        if ($create_start_time) {
            $where .= " and addtime >= ?";
            $params[] = $create_start_time;
        }

        if ($create_end_time) {
            $where .= " and addtime < ?";
            $params[] = $create_end_time;
        }

        $result = $this->getRecords($where, $params, $page * $limit, $limit);

        $total = $result[0];

        $goodsList = $result[1];

        if (!empty($goodsList)) {
            foreach ($goodsList as $key => $value){
                $goodsList[$key]['file'] = Util::joinStaticDomain($value['file']);
            }
        }

        return ['total' => $total, 'list' => $goodsList];

    }

    public function getUnpackedList($page, $limit)
    {
        $page = max($page - 1, 0);

        $where = ' 1 = ?';
        $params[] = 1;


        $where .= " and is_packed = ?";
        $params[] = 1;


        $result = $this->getRecords($where, $params, $page * $limit, $limit);

        $total = $result[0];

        $goodsList = $result[1];

        if (!empty($goodsList)) {
            foreach ($goodsList as $key => $value){
                $goodsList[$key]['file'] = Util::joinStaticDomain($value['file']);
            }
        }

        return ['total' => $total, 'list' => $goodsList];

    }

	public function getListBySellId($sellid, $status='')
	{
	    $query = "select * from ".$this->getTableName()." where sellid = ? ";
        $params = [
            'sellid'    => $sellid
        ];

	    if ($status) {
	        $query .= " and status = ? ";
	        $params['status'] = $status;
        }

        $query .= " order by id desc";

		$list = $this->getAll($query, $params);
        if (!empty($list)) {
            foreach ($list as $key => $value)
            {
                $list[$key]['file'] = Util::joinStaticDomain($value['file']);
            }
        }
        return $list;
	}


    public function getListByPackageId($packageid)
    {

        $queryPackageGoods = "select * from package_goods where package_id = ? ";
        $packageGoods = $this->getAll($queryPackageGoods, $packageid);
        if (empty($packageGoods)) {
            return [];
        }
        $goodsIdArr = Util::arrayToIds($packageGoods, 'goods_id');
        $ids = implode(',', $goodsIdArr);

        $query = "select * from ".$this->getTableName()." where id in ({$ids})";

        $list = $this->getAll($query);

        if (!empty($list)) {
            foreach ($list as $key => $value)
            {
                $list[$key]['file'] = Util::joinStaticDomain($value['file']);
            }
        }
        return $list;
    }

    public static function getStatusArr($status='')
    {
        $statusArr = [
            self::STATUS_DEFAULT        => '待审核',
            self::STATUS_AUDIT          => '审核通过',
//            self::STATUS_IN_STORAGE     => '入库',
//            self::STATUS_OUT_STORAGE    => '出库',
            self::STATUS_FAIL           => '拒收',
            self::STATUS_DESTROY        => '损坏',
        ];

        return isset($statusArr[$status]) ? $statusArr[$status] : $statusArr;
    }

    public function getOneById($id)
    {
        $result = $this->getRecord($id);
        if (!empty($result)) {
            $result['file'] = Util::joinStaticDomain($result['file']);
        }
        return $result;
    }

    public function getGrapeBySellId($sellid)
    {

        $sql = "select sum(show_grape) as show_grape, sum(worth_grape) as worth_grape from ".$this->getTableName()." where sellid = ? ";
        return $this->getRow($sql, [$sellid]);
    }

    public function updatePacked($id)
    {
    	return $this->setRecord($id, ['is_packed' => 2]);
    }


    public function getPriceSum($ids)
    {
    	$sql = "select sum(show_grape) as show_grape from ".$this->getTableName()." where id in ({$ids}) ";
    	return $this->getRow($sql);
    }
}