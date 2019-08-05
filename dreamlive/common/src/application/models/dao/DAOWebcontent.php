<?php
class DAOWebcontent extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_ACTIVITY");
        $this->setTableName("web_content");
    }

    public function getList($category_fid, $offset, $limit)
    {
        $sql = "select id, category_fid, type, status, score, dreamid, title, introduction, image, url, startime, endtime,if(type=3,content,null) as content,addtime from {$this->getTableName()} where status='Y' and category_id=? order by score desc limit {$offset},{$limit}";
        $list = $this->getAll($sql, array($category_fid));
        $sql = "select count(*) from {$this->getTableName()} where status='Y' and category_id=?";
        $total = $this->getOne($sql, array($category_fid));
        return array($total, $list);
    }

    public function getContent($id)
    {
        $sql = "select * from {$this->getTableName()} where status='Y' and id=?";
        return $this->getRow($sql, array($id));
    }
}
