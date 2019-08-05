<?php
class Seizing extends Table
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_MALL");
        $this->setTableName("seizing");
        $this->setPrimary("id");
    }

    public function getList($page, $pagesize, $option = array()){
        $offset = ($page - 1) * $pagesize;

        // 条件拼接
        $where  = '';
        $params = array();
        if (! empty ( $option )) {
            foreach ( $option as $key => $val ) {
                if($option[$key]){
                    $where .= ' and ' . $key . '=? ';
                    array_push($params, $option[$key]);
                }
            }
        }

        // 获取数量
        $sql = " SELECT count(id) as c FROM  " . $this->getTableName().  " ";
        $sql .= ' where 1 ' . $where;
        $result = $this->getRow($sql, $params);

        $count = $result ['c'];
        $pages = ceil ( $count / $pagesize );
        $start = ($page - 1) * $pagesize;
        if ($start < 0) {
            $start = 0;
        }

        // 获取结果
        $resultSql = " select * from  " . $this->getTableName().  " ";
        $resultSql .= ' where 1 ' . $where;
        $resultSql .= " order by id desc limit " . $start . " , " . $pagesize;
        $list = $this->getAll($resultSql, $params);
        return array($list, $count);
    }

}