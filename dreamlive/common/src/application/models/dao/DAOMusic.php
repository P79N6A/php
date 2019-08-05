<?php
class DAOMusic extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_LIVE");
        $this->setTableName("music");
    }

    public function addMusic($name, $singer, $lyric, $mp3)
    {
        $info = array(
            'name' => $name,
            'singer' => $singer,
            'lyric' => $lyric,
            'mp3' => $mp3,
            'addtime' => date('Y-m-d H:i:s'),
        );
        return $this->insert($this->getTableName(), $info);
    }

    
    public function getMusicById($id)
    {
        $sql = "select musicid, name, singer, lyric, mp3 from {$this->getTableName()} where musicid=?";

        return $this->getRow($sql, $id);
    }

    public function searchByName($keyword, $offset = 0, $num = 20)
    {
        $condition = 'name like ?';
        
        $values[] =  "%".$keyword."%";
        $values[] = $offset;
        $values[] = $num;

        $sql = "select a.musicid, a.name, a.singer, a.lyric, a.mp3 from {$this->getTableName()} as a INNER JOIN (select musicid from {$this->getTableName()} where {$condition} limit ?,?) as b USING(musicid)";

        return $this->getAll($sql, $values);
    }

    public function searchBySinger($keyword, $offset = 0, $num = 20)
    {
        $condition = 'singer like ?';
        
        $values[] =  "%".$keyword."%";
        $values[] = $offset;
        $values[] = $num;
        
        $sql = "select a.musicid, a.name, a.singer, a.lyric, a.mp3 from {$this->getTableName()} as a INNER JOIN (select musicid from {$this->getTableName()} where {$condition} limit ?,?) as b USING(musicid)";
        
        return $this->getAll($sql, $values);
    }

    public function getHotList($offset, $num)
    {
        $values[] = $offset;
        $values[] = $num;
        $sql = "select musicid, name, singer, lyric, mp3 from {$this->getTableName()} limit ?,?";

        return $this->getAll($sql, $values);
    }

    public function exists($id)
    {
        $sql = "select count(*) from {$this->getTableName()} where musicid=?";
        return (int) $this->getOne($sql, array($id)) > 0;
    }

    public function getMusicListByIds($ids)
    {
        $ids = implode(',', $ids);

        $sql = "select musicid, name, singer, lyric, mp3 from {$this->getTableName()} where musicid in ({$ids})";

        return $this->getAll($sql);
    }
}
?>