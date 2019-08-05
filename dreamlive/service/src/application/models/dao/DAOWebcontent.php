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
        foreach($list as $k => &$v) {
            $v['image'] = str_replace('dreamlive.tv', 'bjhdxc.com', $v['image']);
            $v['url'] = str_replace('dreamlive.tv', 'bjhdxc.com', $v['url']);
            $v['image'] = str_replace('dreamlive.com', 'bjhdxc.com', $v['image']);
            $v['url'] = str_replace('dreamlive.com', 'bjhdxc.com', $v['url']);
        }
        $sql = "select count(*) from {$this->getTableName()} where status='Y' and category_id=?";
        $total = $this->getOne($sql, array($category_fid));
        return array($total, $list);
    }

    public function getContent($id)
    {
        $sql = "select * from {$this->getTableName()} where status='Y' and id=?";
        $data =  $this->getRow($sql, array($id));
        $data['image'] = str_replace('dreamlive.tv', 'bjhdxc.com', $data['image']);
        $data['url'] = str_replace('dreamlive.tv', 'bjhdxc.com', $data['url']);
        $data['image'] = str_replace('dreamlive.com', 'bjhdxc.com', $data['image']);
        $data['url'] = str_replace('dreamlive.com', 'bjhdxc.com', $data['url']);
        $data['content'] = str_replace('dreamlive.tv', 'bjhdxc.com', $data['content']);
        $data['content'] = str_replace('dreamlive.com', 'bjhdxc.com', $data['content']);

        return $data;
    }
}
