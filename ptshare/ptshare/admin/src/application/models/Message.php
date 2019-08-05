<?php
class Message extends Table
{
	public function __construct()
	{
		$this->setDBConf("MYSQL_CONF_ADMIN");
		$this->setTableName("notice");
		$this->setPrimary("id");
	}

	public function setSendAdmin($sendadmin)
    {
        $info = array(
            "sendadmin"		=> $sendadmin,
            "is_send"		=> 'Y',
            "sendtime"   	=> date("Y-m-d H:i:s"),
        );

        return $this->setRecord($adminid, $info);
    }

	public function addNotice($title, $content ,$adminid, $type)
    {
        $info = array(
            "title"     => $title,
            "content"  	=> $content,
            "addadmin"  => $adminid,
            "type"    	=> $type,
            "addtime"   => date("Y-m-d H:i:s"),
        );
        return $this->addRecord($info);
    }

	public function getList($start, $limit, $name, $award)
	{
		$condition_ar[] = " 1 = ? ";
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

	public function getInfo($id)
    {
        $query = "select * from ".$this->getTableName()." where id = ? ";
        return $this->getRow($query, array($id));
    }
}