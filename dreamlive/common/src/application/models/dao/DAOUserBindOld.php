<?php
class DAOUserBindOld extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PASSPORT");
        $this->setTableName("userbind_old");
    }

    public function getUserBindBySource($rid, $source)
    {
        $sql = "select " . $this->_getFields() . " from " . $this->getTableName() . " where rid=? and source=?";
        return $this->getRow(
            $sql, array(
            $rid,
            $source
            )
        );
    }

    private function _getFields()
    {
        return "id, uid, rid, nickname, avatar, source, addtime, modtime";
    }
}
?>
