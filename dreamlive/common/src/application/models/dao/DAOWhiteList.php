<?php
class DAOWhiteList extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("white_list");
    }

    public function setData($key,array $data)
    {
        $r=$this->getRow("select * from ".$this->getTableName()." where `key`=? order by id desc limit 1", array('key'=>$key));
        if ($r) {
            $d=array(
            'modtime'=>date('Y-m-d H:i:s'),
            'data'=>json_encode($data),
            );
            return $this->update($this->getTableName(), $d, '`key`=?', array('key'=>$key));
        }else{
            $n=date("Y-m-d H:i:s");
            $d=array(
            'key'=>$key,
            'data'=>json_encode($data),
            'addtime'=>$n,
            'modtime'=>$n,
            );
            return $this->insert($this->getTableName(), $d);
        }
    }

    public function getWhiteList($key)
    {
        $re= $this->getRow("select * from ".$this->getTableName()." where `key`=?", array('key'=>$key));
        if ($re) {
            $d=@json_decode($re['data'], true);
            if ($d) { return $d;
            }
        }
        return array();
    }

    /*public function hasInWhiteList($key,$item){
    $re=$this->getWhiteList($key);
    if (in_array($item,$re ))return true;
    return false;
    }*/

    public function getAllData()
    {
        return $this->getAll("select * from ".$this->getTableName()." order by id desc");
    }
}
?>
