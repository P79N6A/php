<?php
class DAOWeekStar extends DAOProxy
{

    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_REPORT");
        $this->setTableName("audiences_new_wangsu");
    }

    public function batchAdd($data)
    {
        $sql="insert into ".$this->getTableName()." (redate,zid,num) values ";
        $r=[];
        foreach ($data as $i){
            $r[]="('".date('Y-m-d H:i').":00',".$i['zid'].",".$i['num'].") ";
        }
        $sql.=implode(',', $r);
        return $this->execute($sql);
    }
}
?>
