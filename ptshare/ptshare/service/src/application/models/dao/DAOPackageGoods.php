<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 2018/5/8
 * Time: 下午4:36
 */

class DAOPackageGoods extends DAOProxy
{

    public function __construct(){
        $this->setDBConf("MYSQL_CONF_MALL");
        $this->setTableName("package_goods");
        parent::__construct();
    }

    public function add($packageId, $goodsId)
    {
        $data = [
            'package_id'    => $packageId,
            'goods_id'      => $goodsId,
        ];

        return $this->insert($this->getTableName(), $data);
    }

    public function getListByPackageId($packageId)
    {
        $sql = "SELECT * FROM ".$this->getTableName()." WHERE package_id = ? ";
        return $this->getAll($sql, [$packageId]);
    }



}