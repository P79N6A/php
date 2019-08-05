<?php
class Lottery extends Table
{

    public $status = array(
        'Y' => '开启',
        'N' => '关闭',

    );

    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_MALL");
        $this->setTableName('lottery');
    }

    public function getLotteryConfigDetail($issue)
    {
        $query = "select * from lottery_config where issue = ? ";

        return $this->getRow($query, array($issue));
    }

    public function getConfigList($start, $limit, $issue, $pt_issue, $result, $status)
    {
        $condition_ar[] = " 1=? ";
        $params[] = 1;

        if (!empty($issue)) {
            $condition_ar[] = " issue = ? ";
            $params[] = $issue;
        }

        if (!empty($pt_issue)) {
            $condition_ar[] = " pt_issue = ? ";
            $params[] = $pt_issue;
        }

        if (!empty($result)) {
            $condition_ar[] = " result = ? ";
            $params[] = $result;
        }

        if (!empty($status)) {
            $condition_ar[] = " status = ? ";
            $params[] = $status;
        }

        $condition = implode(" and ", $condition_ar);

        $sql = "select * from lottery_config where {$condition} order by issue desc ";

        $sql.= $limit > 0 ? " limit $start, $limit" : "";
        $data = $this->getAll($sql, $params);

        $sql_count = "select count(*) from lottery_config where {$condition} order by issue desc ";
        $total = $this->getOne($sql_count, $params);

        return array($data, $total);
    }

    public function getList($start, $limit, $issue, $pt_issue, $result, $status)
    {
        $condition_ar[] = " 1=? ";
        $params[] = 1;

        if (!empty($issue)) {
            $condition_ar[] = " issue = ? ";
            $params[] = $issue;
        }

        if (!empty($pt_issue)) {
            $condition_ar[] = " pt_issue = ? ";
            $params[] = $pt_issue;
        }

        if (!empty($result)) {
            $condition_ar[] = " result = ? ";
            $params[] = $result;
        }

        if (!empty($status)) {
            $condition_ar[] = " status = ? ";
            $params[] = $status;
        }

        $condition = implode(" and ", $condition_ar);

        $sql = "select * from ".$this->getTableName()." where {$condition} order by issue desc ";

        $sql.= $limit > 0 ? " limit $start, $limit" : "";
        $data = $this->getAll($sql, $params);

        $sql_count = "select count(*) from ".$this->getTableName()." where {$condition} order by issue desc ";
        $total = $this->getOne($sql_count, $params);

        return array($data, $total);
    }

}