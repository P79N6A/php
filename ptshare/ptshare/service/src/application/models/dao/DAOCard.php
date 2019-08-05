<?php
class DAOCard extends DAOProxy
{
	public function __construct()
	{
		$this->setDBConf("MYSQL_CONF_MALL");
		$this->setTableName("mini_card");
	}

	public function add($uid, $cover, $type, $text)
	{
		$info = array(
				"uid"		=> $uid,
				"cover"		=> $cover,
				"type"		=> $type,
				"text"		=> $text,
				"addtime"	=> date("Y-m-d H:i:s")
		);
		return $this->insert($this->getTableName(), $info);
	}

	public function getCardList($uid, $num, $offset)
	{
		$where = " ";
		if ($offset > 0) {
			$where .= " and id<" . $offset . " ";
		}
		$sql = " SELECT * FROM " . $this->getTableName() . " WHERE uid=? ";
		$sql .= $where;
		$sql .= " ORDER BY id DESC LIMIT " . $num;
		return $this->getAll($sql, array('uid' => $uid));
	}

	public function getCardTotal($uid)
	{
		$sql   = " SELECT count(*) as cnt FROM " . $this->getTableName() . " WHERE uid=?  ";
		return $this->getOne($sql, array('uid' => $uid));
	}

	public function getCardInfoById($id)
	{
		$sql   = " SELECT * FROM " . $this->getTableName() . " WHERE id=?  ";
		return $this->getRow($sql, array('id' => $id));
	}


	public function remove($id)
	{
		return $this->delete($this->getTableName(), 'id = ?', $id);
	}
}