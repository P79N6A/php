<?php
class DAOTags extends DAOProxy
{

    public function __construct(){
        $this->setDBConf("MYSQL_CONF_MALL");
        $this->setTableName("tags");
        parent::__construct();
    }

    public function addTag($tagId, $tagName)
    {
        $data = [
            'tagid'   => $tagId,
            'tagname' => $tagName,
        ];
        return $this->insert($this->getTableName(), $data);
    }

    public function getTagInfoByName($tagname)
    {
        $sql = "select tagid, tagname from " . $this->getTableName() . " where tagname=?";

        return $this->getRow($sql, $tagname);
    }

    public function searchByName($keyword, $offset = 0, $num = 20)
    {
        $condition = 'tagname like ?';
        
        $values[] =  $keyword."%";
        $values[] = $offset;
        $values[] = $num;

        $sql = "select tagid,tagname from {$this->getTableName()} where {$condition} limit ?,?";

        return $this->getAll($sql, $values);
    }

    public function getUserHistory($uid)
    {
        $sql = "select tagid from tag_history where uid=? order by addtime desc limit 10";
        $history = $this->getAll($sql, $uid);

        $tagList = array();
        if($history){
            foreach($history as $v){
                $tagidList[] = "'".$v['tagid']."'";
            }
            $tagStr = implode(',',$tagidList);
 
            $sql = "select tagid, tagname from " . $this->getTableName() . " where tagid in({$tagStr}) ";

            $tagList = $this->getAll($sql);
            $tagList = array_column($tagList, 'tagname', 'tagid');
        }
        foreach($history as &$v){
            $v['tagname'] = $tagList[$v['tagid']];
        }

        return $history;
    }

    public function addUserHistory($uid, $tagid)
    {
        $data = [
            'tagid'   => $tagid,
            'uid'     => $uid,
            'addtime' => date('Y-m-d H:i:s')
        ];

        return $this->replace('tag_history', $data);
    }


}