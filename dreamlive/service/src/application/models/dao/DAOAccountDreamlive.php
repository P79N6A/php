<?php
class DAOAccountDreamlive extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("account_dreamlive");
    }

    public function getAccountList()
    {
        $sql = "select id,uid from " . $this->getTableName() . " where flag=0 and uid>1000000 limit 1";

        return $this->getRow($sql, null, false);
    }

    public function update($id)
    {
        $sql = "update " . $this->getTableName() . " set flag=1 where id=$id limit 1";

        return $this->execute($sql, null, false);
    }

    

}
?>
