<?php
class DAOPackage extends DAOProxy
{

    const

        SOURCE_SELL             = 1,    // 来源 sell
        SOURCE_PACKAGE          = 2,    // 来源 包裹

        FORWARD_GRPAE			= 1,	//自己用
        FORWARD_AWARD			= 2,	//吸粉
        FORWARD_DONATE			= 3,	//捐平台

        STATUS_PREPARE          = 'PREPARE',    //待编辑
        STATUS_ONLINE	        = 'ONLINE',     //上架中
        STATUS_OFFLINE          = 'OFFLINE',    //已下架
        STATUS_SELLOUT          = 'SELLOUT',     //已售罄

    END = '';

    const SELL_TYPE_NORMAL  = 0;
    const SELL_TYPE_ZEIZING = 1;


    public function __construct(){
        $this->setDBConf("MYSQL_CONF_MALL");
        $this->setTableName("package");
        parent::__construct();
    }

    public function modify($id, $online, $description, $deposit_price, $rent_price, $status, $num, $location,$type, $vip)
    {
    	$up_ar = [];
    	$where_ar = [];
    	if (!empty($online)) {
    		$where_ar[] = " online = ?";
    		$up_ar[] = $online;
    	}

        if (!empty($type)) {
            $where_ar[] = " type = ?";
            $up_ar[] = $type;
        }

        if (!empty($vip)) {
            $where_ar[] = " vip = ?";
            $up_ar[] = $vip;
        }

    	if (!empty($num)) {
    		$where_ar[] = " num = ?";
    		$up_ar[] = $num;
    	}

    	if (!empty($location)) {
    		$where_ar[] = " location = ?";
    		$up_ar[] = $location;
    	}

    	if (!empty($status)) {
    		$where_ar[] = " status = ?";
    		$up_ar[] = $status;
    	}

    	if (!empty($description)) {
    		$where_ar[] = " description = ?";
    		$up_ar[] = $description;
    	}

    	if (!empty($deposit_price)) {
    		$where_ar[] = " deposit_price = ?";
    		$up_ar[] = $deposit_price;
    	}

    	if (!empty($rent_price)) {
    		$where_ar[] = " rent_price = ?";
    		$up_ar[] = $rent_price;
    	}

    	$where_ar[] = " modtime = ?";
    	$up_ar[] = date("Y-m-d H:i:s");

    	$up_ar[] = $id;

    	$where = implode(",", $where_ar);
    	$sql = "update ". $this->getTableName() ." set  $where where id = ?  limit 1";
    	Logger::log("account", "sql :", array("sql" => $sql , "online" => $online, 'id' => $id, "des" => $description, "dep_price" => $deposit_price, "rent_price" => $rent_price));
    	return $this->execute($sql, $up_ar);
    }
    /**
     * 获取Package详情
     * @param int $packageId
     */
    public function getOneById($id){
        $sql = "select * from {$this->getTableName()} where id = ? ";
        return $this->getRow($sql, $id);
    }

    public function getOneBySn($sn){
        $sql = "select * from {$this->getTableName()} where sn = ? ";
        return $this->getRow($sql, $sn);
    }

    /**
     * 根据$packageid获取package详情
     * @param string $packageid
     */
    public function getPackageInfoByPackageid($packageid){
        $sql = "select * from {$this->getTableName()} where packageid = ? ";
        return $this->getRow($sql, $packageid);
    }
	
    /**
     * 根据$source_id获取package详情
     * @param string $source_id
     */
    public function getPackageInfoBySourceid($source_id){
    	$sql = "select * from {$this->getTableName()} where source_id = ? ";
    	return $this->getRow($sql, $source_id);
    }
    
    /**
     * 根据$orderid获取package详情
     * @param string $orderid
     */
    public function getPackageInfoByOrderid($orderid){
        $sql = "select * from {$this->getTableName()} where orderid = ? ";
        return $this->getRow($sql, $orderid);
    }

    /**
     * 批量获取packageInfos
     * @param array $packageids
     * @return array
     */
    public function getPackageInfosByPackageids($packageids){
        $sql = "select * from {$this->getTableName()} where packageid in (".implode(',', $packageids).") ";
        return $this->getAll($sql);
    }

    /**
     * 列表
     * @param int $uid
     * @param string $type
     * @param int $num
     * @param int $offset
     */
    public function getPackageList($type, $num, $offset){
        $where = "";
        if ($offset > 0) {
            $where .= " and id<" . $offset . " ";
        }
        $sql = " SELECT * FROM " . $this->getTableName() . " WHERE status = ?  and type = ? ";
        $sql .= $where;
        $sql .= " ORDER BY id DESC LIMIT " . $num;
        return $this->getAll($sql, array('status' => self::STATUS_ONLINE, 'type' => $type));
    }

    /**
     * 总数
     * @param int $uid
     * @param string $type
     */
    public function getPackageTotal($type){
        $sql = " SELECT count(*) as cnt FROM " . $this->getTableName() . " WHERE status = ? and type = ? ";
        return $this->getOne($sql, array('status' => self::STATUS_ONLINE, 'type' => $type));
    }

    /**
     * 获取上架状态
     * @param int $uid
     * @param string $packageid
     */
    public function getPackageOnlineStatus($packageid)
    {
    	$sql = " SELECT status FROM " . $this->getTableName() . " WHERE packageid = ?  ";
    	return $this->getOne($sql, array($packageid));
    }

    /**
     * 是否有下一页数据
     * @param int $uid
     * @param string $type
     * @param int $offset
     */
    public function getPackageMore($type, $offset){
        $where = "";
        if ($offset > 0) {
            $where .= " and id<" . $offset . " ";
        }
        $sql = " SELECT count(id) as cnt FROM " . $this->getTableName() . " WHERE status = ? and type =? ";
        $sql .= $where;
        return $this->getOne($sql, array('status' => self::STATUS_ONLINE, 'type' => $type));
    }

    /**
     * 修改package上下架
     * @param int $id
     * @param string $online
     * @return boolean
     */
    public function updatePackageOnline($packageid, $online){
        $condition = ' packageid=? ';
        $params = array(
            'packageid' => $packageid
        );
        $option = array(
            'status'  => $online,
            'modtime' => Util::dbNow()
        );
        return $this->update($this->getTableName(), $option, $condition, $params);
    }

    /**
     * 修改package的orderid
     * @param int $id
     * @param string $online
     * @return boolean
     */
    public function updatePackageOrderid($packageid, $orderid){
        $condition = ' packageid=? ';
        $params = array(
            'packageid' => $packageid
        );
        $option = array(
            'orderid' => $orderid,
            'modtime' => Util::dbNow()
        );
        return $this->update($this->getTableName(), $option, $condition, $params);
    }

    /**
     * 获取用户分享列表
     * @param int $uid
     * @return array
     */
    public function getUserPackageList($uid, $status){
        $where = "";
        if ($status == '') {
            $where .= " and status in ('".DAOPackage::STATUS_ONLINE."','".DAOPackage::STATUS_SELLOUT."') ";
        }else{
            $where .= " and status = '".trim($status)."' ";
        }
        $sql = "SELECT *, max(id) FROM  ". $this->getTableName() ." WHERE sell_user_id=? ";
        $sql .= $where;
        $sql .= " GROUP BY sn ORDER BY id desc  ";
        return $this->getAll($sql, $uid);
    }


    public function increaseFavorite($id)
    {

        $sql = "UPDATE ". $this->getTableName() ." SET favorite_num = favorite_num + 1 where id=?";

        return $this->execute($sql, [$id]);

    }

    public function decreaseFavorite($id)
    {

        $sql = "UPDATE ". $this->getTableName() ." SET favorite_num = favorite_num - 1 where id=?";

        return $this->execute($sql, [$id]);

    }


    public function add($sell_user_id, $packageid, $sn, $categoryid, $source, $source_id, $cover, $cover_type, $contact, $num, $description, $source_uid, $extends='', $online=false, $location='',$endtime='', $deposit_price=0, $rent_price = 0, $cardid = 0, $grape_forward = 1, $sales_type = 0, $type = 1, $vip = 0)
    {
        $data = [
            'sell_user_id'  => $sell_user_id,
            'packageid'     => $packageid,
            'sn'            => $sn,
            'categoryid'    => $categoryid,
            'source'        => $source,
            'source_id'     => $source_id,
            'cover'         => $cover,
            'cover_type'    => $cover_type,
            'contact'       => $contact,
            'description'   => $description,
            'extends'       => $extends,
            'num'           => $num,
            'source_uid'    => $source_uid,
            'favorite_num'  => rand(20, 300),
            'view_num'      => rand(1000, 9999),
        	'cardid'		=> $cardid,
        	'grape_forward' => $grape_forward,
            'sales_type'    => $sales_type,
        	'type'			=> $type,
        	'vip'			=> $vip,
        ];

        if ($online) {
            $data['status'] = self::STATUS_ONLINE;
        }

        if ($location) {
            $data['location'] = $location;
        }

        if ($endtime) {
            $data['endtime'] = $endtime;
        }

        if ($deposit_price) {
            $data['deposit_price'] = $deposit_price;
        }

        if ($rent_price) {
            $data['rent_price'] = $rent_price;
        }

        return $this->insert($this->getTableName(), $data);
    }

    public function getListByIds($ids=[])
    {
        if (empty($ids)) {
            return [];
        }

        if (!is_array($ids)) {
            $ids = [$ids];
        }
        $ids = implode(',', $ids);

        $sql = "select * from ".$this->getTableName()." where id in ({$ids})";
        return $this->getAll($sql);
    }

    /**
     * @param $id
     * @return mixed
     * @desc 下架
     * 对于 DB 层，接口化设计，model 作为模块层，不需要知道 DB 底层的数据结构或者数据定义类型
     */
    public function setStatusOffLineById($id)
    {
        $condition = 'id = ?';

        $params = array(
            $id
        );

        $option = array(
            'status'  => self::STATUS_OFFLINE,
        );

        return $this->update($this->getTableName(), $option, $condition, $params);

    }

    /**
     * @param $id
     * @return mixed
     * @desc 上架
     */
    public function setStatusOnLineById($id)
    {
        $condition = 'id = ?';

        $params = array(
            $id
        );

        $option = array(
            'status'  => self::STATUS_ONLINE,
        );

        return $this->update($this->getTableName(), $option, $condition, $params);

    }

    public function getInfoBySourceIdFromSell($sourceId)
    {
        $sql = "select * from ".$this->getTableName()." where source_id = ? and source = ? ";

        return $this->getRow($sql, [$sourceId, self::SOURCE_SELL]);
    }

    public function getInfoBySourceIdFromPackage($sourceId)
    {
        $sql = "select * from ".$this->getTableName()." where source_id = ? and source = ? ";

        return $this->getRow($sql, [$sourceId, self::SOURCE_PACKAGE]);
    }



}