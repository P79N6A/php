<?php
class DAOHelps extends DAOProxy
{

    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_TASK");
        $this->setTableName("helps");
    }

    public function addHelp($title, $content, $rank)
    {
        $info["title"] = $title;
        $info["content"] = $content;
        $info["rank"] = $rank;
        
        return $this->insert($this->getTableName(), $info);
    }

    public function updateHelp($helpid, $title, $content, $rank)
    {
        $info["title"] = $title;
        $info["content"] = $content;
        $info["rank"] = $rank;
        
        return $this->update($this->getTableName(), $info, "id=?", $helpid);
    }

    public function delHelp($helpid)
    {
        return $this->delete($this->getTableName(), 'id = ?', $helpid);
    }

    public function getHelpList()
    {
        return $this->getAll("select id,title,content from ".$this->getTableName()." order by rank asc", array());
    }

    public function getHelpInfo($helpid)
    {
        return $this->getRow("select id,title,content from ".$this->getTableName()." where id=? ", $helpid);
    }

    public function exist($rank, $id = 0)
    {
        $where .= " where rank=?";
        $params[] = $rank;

        if(!empty($id)) {
            $where .= " and id <> ?";
            $params[] = $id;
        }
        $sql = "select count(*) from {$this->getTableName()} {$where} ";
        
        return $this->getOne($sql, $params) ? true : false;
    }
}
