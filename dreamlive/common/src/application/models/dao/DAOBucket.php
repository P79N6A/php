<?php
class DAOBucket extends DAOProxy
{
    const OPEN = 1;
    const CLOSE = 2;

    public function __construct()
    {
        parent::__construct();
        $this->setDBConf("MYSQL_CONF_LIVE");
        $this->setTableName('bucket');
    }

    public function getBucket($bucket_name)
    {
        $sql = "select * from {$this->getTableName()} where name = ?";
        $record = $this->getRow($sql, $bucket_name);

        isset($record['extends']) && $record['extends'] = json_decode($record['extends'], true);

        return $record;
    }

    public function setBucket($bucket_name, $capacity, $official, $extends)
    {
        $sql = "insert into {$this->getTableName()} (name, capacity, official, extends) values(?,?,?,?) on duplicate key update capacity = values(capacity), official = values(official), extends = values(extends)";

        return $this->execute($sql, array($bucket_name, $capacity, $official, json_encode($extends)));
    }

    public function setForward($bucket_name, $forward_name)
    {
        $sql = "update {$this->getTableName()} set forward=? where name=?";

        return $this->Execute($sql, array($forward_name, $bucket_name));
    }

    public function setTotal($bucket_name, $total)
    {
        $sql = "update {$this->getTableName()} set total=? where name=?";

        return $this->Execute($sql, array($total, $bucket_name));
    }

    public function delBucket($bucket_name)
    {
        return $this->delete($this->getTableName(), 'name = ?', $bucket_name);
    }

    public function bucketOpen($bucket_name)
    {
        $sql = "update {$this->getTableName()} set open=? where name=?";
        return $this->Execute($sql, array(DAOBucket::OPEN, $bucket_name));
    }

    public function bucketClose($bucket_name)
    {
        $sql = "update {$this->getTableName()} set open=? where name=?";
        return $this->Execute($sql, array(DAOBucket::CLOSE, $bucket_name));
    }

    /**
     * 
     * $bucket_name是否存在
     *
     * @param  string $bucket_name            
     * @return boolean
     */
    public function isExistByName($bucket_name)
    {
        $sql = "SELECT count(bucketid) as cnt FROM " . $this->getTableName() . " WHERE neme=? ";
        $result = $this->getRow($sql, $bucket_name);
        if (isset($result['cnt']) && $result['cnt'] > 0) {
            return true;
        }
        return false;
    }
}
?>
