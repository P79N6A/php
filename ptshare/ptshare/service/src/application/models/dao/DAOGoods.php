<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 2018/5/8
 * Time: 下午4:36
 */

class DAOGoods extends DAOProxy
{

    const

        STATUS_DEFAULT      = 100,
        STATUS_AUDIT        = 110,
//        STATUS_IN_STORAGE   = 200,
//        STATUS_OUT_STORAGE  = 300,
        STATUS_FAIL         = 500,
        STATUS_DESTROY      = 400,

        END = '';

    public function __construct(){
        $this->setDBConf("MYSQL_CONF_MALL");
        $this->setTableName("goods");
        parent::__construct();
    }

    public static function getStatusArr($status='')
    {
        $statusArr = [
            self::STATUS_DEFAULT        => '默认',
            self::STATUS_AUDIT          => '审核通过',
//            self::STATUS_IN_STORAGE     => '入库',
//            self::STATUS_OUT_STORAGE    => '出库',
            self::STATUS_FAIL           => '拒收',
            self::STATUS_DESTROY        => '损坏',
        ];


        return isset($statusArr[$status]) ? $statusArr[$status] : $statusArr;
    }

    /**
     * 根据packageid获取goods信息
     * @param int $packageid
     * @return array
     */
    public function getListByPackageid($packageid)
    {
        $sql = "SELECT * FROM ".$this->getTableName()." WHERE packageid =? ";
        return $this->getAll($sql,array('packageid'=>$packageid));
    }

    public function getListBySellid($sellid)
    {
        $sql = "SELECT * FROM ".$this->getTableName()." WHERE sellid = ? ";
        return $this->getAll($sql, [$sellid]);
    }

    public function getCanCreatePackageTotalBySellId($sellid)
    {
        $sql = "SELECT count(*) as total FROM ".$this->getTableName()." WHERE sellid = ? and status = ? ";
        $result = $this->getRow($sql, [$sellid, self::STATUS_AUDIT]);
        return $result['total'];
    }


    public function add($sellid, $labels, $description, $grape, $categoryid, $type, $file, $extends)
    {

        $data = [
            'good_sn'       => SnowFlake::nextId(),
            'sellid'        => $sellid,
            'labels'        => $labels,
            'description'   => $description,
            'categoryid'    => $categoryid,
            'show_grape'    => $grape,
            'type'          => $type,
            'file'          => $file,
            'extends'       => $extends,
        ];

        return $this->insert($this->getTableName(), $data);

    }

    public function updateInfoById($id, $data)
    {

        $condition = "id = ?";

        $param = [
            'id'    => $id,
        ];

        return $this->update($this->getTableName(), $data, $condition, $param);
    }

    public function getGrapeBySellId($sellid)
    {
        $sql = "select sum(show_grape) as show_grape, sum(worth_grape) as worth_grape from ".$this->getTableName()." where sellid = ?";
        return $this->getRow($sql, [$sellid]);
    }

    public function getGrapeBySellIds($sellids)
    {
        if (!is_array($sellids)) {
            $sellids = [$sellids];
        }
        $sellids = implode(',', $sellids);

        $sql = "select sum(show_grape) as show_grape, sum(worth_grape) as worth_grape, sellid from ".$this->getTableName()." where sellid in ({$sellids}) group by sellid";
        return $this->getAll($sql);
    }

    public function updatePackageIdBySellId($sellid, $packageid)
    {
        $goodsRecord = [
            'packageid' => $packageid
        ];

        $condition = " sellid = ? and status = ? ";

        $param = [
             $sellid, self::STATUS_AUDIT
        ];

        return $this->update($this->getTableName(), $goodsRecord, $condition, $param);

    }

    public function getOneById($id)
    {
        $sql = "SELECT * FROM ".$this->getTableName()." WHERE id = ? ";
        return $this->getRow($sql, [$id]);
    }

    public function getListByIds($ids)
    {
        $sql = "SELECT * FROM ".$this->getTableName()." WHERE id in ({$ids}) ";
        return $this->getAll($sql);
    }

    public function getRefuseReasonListBySellid($sellid)
    {
    	$sql = "SELECT refuse_reason FROM ".$this->getTableName()." WHERE sellid = ? and  status = ? ";
    	return $this->getAll($sql, array($sellid, self::STATUS_FAIL));
    }


}