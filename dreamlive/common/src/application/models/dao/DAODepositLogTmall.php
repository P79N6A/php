<?php


class DAODepositLogTmall extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("depositlog_tmall");
    }

    public function add($tradeid, $api, $content)
    {
        $log_info = [
            'tradeid' => $tradeid,
            'api' => $api,
            'content' => $content,
            'addtime' => date("Y-m-d H:i:s"),
        ];

        return $this->insert($this->getTableName(), $log_info);
    }
}