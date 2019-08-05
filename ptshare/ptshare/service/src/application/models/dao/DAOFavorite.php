<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 2018/5/9
 * Time: 下午5:30
 */

class DAOFavorite extends DAOProxy
{

    public function __construct(){
        $this->setDBConf("MYSQL_CONF_MALL");
        $this->setTableName("favorite");
        parent::__construct();
    }

    public function add($id, $userid)
    {

        $data = [
            'uid'       => $userid,
            'packageid' => $id,
        ];

        $this->insert($this->getTableName(), $data);

    }

    public function deleteInfo($id, $userid)
    {

        $param = [
            'uid'       => $userid,
            'packageid' => $id,
        ];

        return $this->delete($this->getTableName(),'uid = ? AND packageid = ?', $param);

    }

    public function getInfo($id, $userid)
    {

        $sql = "select * from ". $this->getTableName() . " where packageid = ? and uid = ? ";

        return $this->getRow($sql, [$id, $userid]);

    }

    public function getListByUserId($userid, $num = 7, $offset = 0)
    {
        $where = " uid = ?";
        $param[] = $userid;
        if ($offset > 0) {
            $where .= " and id < ? ";
            $param[] = $offset;
        }
        $sql = " SELECT * FROM " . $this->getTableName() . " WHERE ".$where;
        $sql .= " ORDER BY id DESC LIMIT ?";
        $param[] = $num;
        $list = $this->getAll($sql, $param);

        $more = false;
        $offset = end($list)['id'] ? end($list)['id'] : 0;

        if ($offset) {
            $offsetSql = "SELECT * FROM " . $this->getTableName() . " WHERE uid = ? and id < ? ";
            $moreList = $this->getAll($offsetSql, [$userid, $offset]);
            $more = (bool)$moreList;
        }

        return ['list' => $list, 'offset' => $offset, 'more' => $more];
    }



}