<?php
class Renting extends Table{

    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_MALL");
        $this->setTableName('renting');
    }

    public function getList($start, $limit, $package_num, $sn, $status, $uid)
    {
        $condition_ar[] = " 1=? ";
        $params[] = 1;

        if (!empty($package_num)) {
            $condition_ar[] = " packageid = ? ";
            $params[] = $package_num;
        }

        if (!empty($sn)) {
            $condition_ar[] = " sn = ? ";
            $params[] = $sn;
        }

        if (!empty($status)) {
            $condition_ar[] = " status = ? ";
            $params[] = $status;
        }

        if (!empty($uid)) {
            $condition_ar[] = " uid = ? ";
            $params[] = $uid;
        }

        $condition = implode(" and ", $condition_ar);

        $sql = "select * from ".$this->getTableName()." where {$condition} order by rentid desc ";

        $sql.= $limit > 0 ? " limit $start, $limit" : "";
        $data = $this->getAll($sql, $params);

        $sql_count = "select count(*) from ".$this->getTableName()." where {$condition} order by rentid desc ";
        $total = $this->getOne($sql_count, $params);

        return array($data, $total);
    }

    public function getInfo($id)
    {
        $query = "select * from ".$this->getTableName()." where id = ? ";
        return $this->getRow($query, array($id));
    }

}