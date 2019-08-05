<?php
class DAOBucketElement extends DAOProxy
{
    const PADING_OFFSET = 'offset'; // offset
    const PADING_LIMIT = 'limit'; // limit

    public function __construct()
    {
        parent::__construct();
        $this->setDBConf("MYSQL_CONF_LIVE");
        $this->setTableName('bucket_element');
    }

    public function exists($bucket_name, $type, $relateid)
    {
        $sql = "select * from {$this->getTableName()} where bucket_name=? and relateid=? and type=?";

        return $this->getRow($sql, array($bucket_name, $relateid, $type));
    }

    public function set($bucket_name, $relateid, $type, $score, $extends)
    {
        $info = array(
        "bucket_name" => $bucket_name,
        "relateid"    => $relateid,
        "type"        => $type,
        "score"       => $score,
        "extends"     => $extends,
        "addtime"     => date("Y-m-d H:i:s"),
        "modtime"     => date("Y-m-d H:i:s")
        );

        return $this->replace($this->getTableName(), $info);
    }

    public function import($bucket_name, $data)
    {
        //$sql = "insert ignore into {$this->getTableName()} (bucket_name, relateid, `type`, score, extends, addtime) values ";
        $sql = "replace into {$this->getTableName()} (bucket_name, relateid, `type`, score, extends, addtime) values ";

        $datas = array_chunk($data, 100);
        $rows = 0;
        foreach ($datas as $d) {
            $values = array();
            $placeholder = array();
            foreach ($d as $v) {
                $dd = array(
                    $bucket_name,
                    $v['relateid'],
                    $v['type'],
                    isset($v['score']) ? $v['score'] : 0,
                    isset($v['extends']) ? $v['extends'] : '',
                    date('Y-m-d H:i:s'),
                );
                $placeholder[] = "(?,?,?,?,?,?)";
                $values = array_merge($values, $dd);
            }

            $stmt = $this->query($sql . implode(',', $placeholder), $values);
            $rows += $stmt->getEffectedRows();
        }

        return $rows;
    }

    public function fetch($bucket_name, $offset, $num, $paging)
    {
        if($paging==self::PADING_OFFSET) {
            $sql = "select * from {$this->getTableName()} where bucket_name=? and score<? order by score desc limit $num";
        }else if($paging==self::PADING_LIMIT) {
            $sql = "select * from {$this->getTableName()} where bucket_name=? order by score desc limit $offset, $num";
        }

        $data = $this->getAll($sql, array($bucket_name, $offset));

        foreach ($data as &$d) {
            $d['extends'] = json_decode($d['extends'], true);
        }
        unset($d);

        return $data;
    }

    public function delete($bucket_name, $relateid, $type)
    {
        $sql = "delete from {$this->getTableName()} where bucket_name = ? and relateid = ? and type = ?";

        return $this->Execute($sql, array($bucket_name, $relateid, $type));
    }

    /**
     * 倒序截断榜单
     */
    public function truncate($bucket_name, $num)
    {
        $sql = "delete from {$this->getTableName()} where bucket_name = ? order by score asc limit $num";

        return $this->Execute($sql, $bucket_name);
    }

    public function getBucketNames($relateid, $type)
    {
        $sql = "select bucket_name from {$this->getTableName()} where relateid = ? and type = ?";
        $result = $this->getAll($sql, array($relateid, $type));

        $ranknames = array();
        foreach ($result as $r){
            $ranknames[] = $r['bucket_name'];
        }

        return $ranknames;
    }

    public function clean($relateid, $type)
    {
        $sql = "delete from {$this->getTableName()} where relateid = ? and type = ?";
        return $this->Execute($sql, array($relateid, $type));
    }

    public function deleteAll($bucket_name)
    {
        $sql = "delete from {$this->getTableName()} where bucket_name =?";
        return $this->Execute($sql, $bucket_name);
    }

    public function getTotal($bucket_name)
    {
        $sql = "select count(*) from {$this->getTableName()} where bucket_name = ?";

        return $this->getOne($sql, $bucket_name);
    }
    
    /**
     * 置顶
     *
     * @param string $bucket_name
     * @param int    $relateid
     * @param int    $type
     */
    public function topBucketElement($bucket_name,$relateid,$type,$extends)
    {
        $sql = "update ".$this->getTableName()." set score = score +".BucketElement::TOP_SCORE.",extends='".$extends."' where bucket_name=? and relateid=? and type=?";
        return $this->Execute($sql, array('bucket_name'=>$bucket_name,'relateid'=>$relateid,'type'=>$type));
    }
    
    /**
     * 取消置顶
     *
     * @param string $bucket_name
     * @param int    $relateid
     * @param int    $type
     */
    public function unTopBucketElement($bucket_name,$relateid,$type,$extends)
    {
        $sql = "update ".$this->getTableName()." set score = score -".BucketElement::TOP_SCORE.",extends='".$extends."' where bucket_name=? and relateid=? and type=?";
        return $this->Execute($sql, array('bucket_name'=>$bucket_name,'relateid'=>$relateid,'type'=>$type));
    }
}
?>
