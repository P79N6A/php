<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 2018/5/12
 * Time: 上午11:43
 */

class User extends Table
{

    public function __construct()
    {
        parent::__construct();
        $this->setDBConf("MYSQL_CONF_PASSPORT");
        $this->setTableName("user");
        $this->setPrimary("uid");
    }

    public function getListByUids($uidArr)
    {

        if (empty($uidArr)) {
            return '';
        }

        if (!is_array($uidArr)) {
            $uidArr = [$uidArr];
        }

        // 去重
        $uidArr = array_unique($uidArr);
        // 去空
        $uidArr = array_filter($uidArr);

        $uids = implode(',', $uidArr);

        $where = ' uid in ('.$uids.')';

        return $this->getRecords($where);

    }

    public function getList($start, $limit, $uid = 0, $nickname = '')
    {
    	$condition_ar[] = " 1=? ";
    	$params[] = 1;
    	
    	if (!empty($uid)) {
    		$condition_ar[] = " uid = ? ";
    		$params[] = $uid;
    	}
    	
    	
    	
    	$condition = implode(" and ", $condition_ar);
    	
    	if (!empty($nickname)) {
    		$condition  .= " and nickname like '%{$nickname}%' ";
    	}
    	$sql = "select * from ".$this->getTableName()." where {$condition} order by uid desc ";
    	$sql.= $limit > 0 ? " limit $start, $limit" : "";
    	$data = $this->getAll($sql, $params);

    	$sql_count = "select count(*) from ".$this->getTableName()." where {$condition} order by uid desc ";
    	$total = $this->getOne($sql_count, $params);

    	return array($data, $total);
    }

    public function getOneByPhone($phone)
    {
        $sql = "select * from {$this->getTableName()} where phone = ?";

        return $this->getRow($sql, [$phone]);
    }

    public function getOneById($id)
    {
        return $this->getRecord($id);
    }

}