<?php
class DAOMusicFavorite extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_LIVE");
        $this->setTableName("music_favorite");
    }

    public function addFavorite($uid, $musicid)
    {
        $info = array(
            'uid' => $uid,
            'musicid' => $musicid,
            'modtime' => date('Y-m-d H:i:s'),
        );
        return $this->replace($this->getTableName(), $info);
    }

    public function getFavoriteIds($uid, $offset = 0, $num = 0)
    {
        if($num > 0) {
            $limit = 'limit ?,?';
            $values = array($uid, $offset, $num);
        }else{
            $limit = '';
            $values = array($uid);
        }
        $sql = "select musicid from " . $this->getTableName() . " where uid=? order by modtime desc {$limit}";
        
        $musicids = $this->getAll($sql, $values);
        
        $ids = array();
        foreach ($musicids as $v) {
            $ids[] = $v['musicid'];
        }
        
        return $ids;
    }

    public function getTotalFavorite($uid)
    {
        $sql = "select count(1) as cnt from " . $this->getTableName() . " where uid=? ";
        
        return $this->getOne($sql, $uid);
    }

    public function deleteTheLastMusicId($uid)
    {
        $sql = "select uid, musicid from " . $this->getTableName() . " where uid = ? order by modtime limit 1";

        $list = $this->getRow($sql, $uid);

        if($list) {
            $sql = "delete from " . $this->getTableName() . " where uid=? and musicid=? ";
            
            return $this->execute($sql, array($list['uid'], $list['musicid']));
        }

        return ;
    }
   
}
?>