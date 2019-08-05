<?php
class DAOELog extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_GAME");
        $this->setTableName("elog");
    }

    public function add($key,array $data)
    {
        $now=date("Y-m-d H:i:s");
        $d=[
            'key'=>$key,
            'data'=>json_encode($data),
            'addtime'=>date("Y-m-d H:i:s"),
        ];

        return $this->insert($this->getTableName(), $d);
    }
}
?>
