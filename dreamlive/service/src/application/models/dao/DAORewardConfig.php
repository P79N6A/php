<?php

class DAORewardConfig extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("reward_config");
    }

    public function addConfig($giftid, $lower, $upper, $orderid)
    {
        $info = [
            'giftid' => $giftid,
            'lower' => $lower,
            'upper' => $upper,
            'orderid' => $orderid,
            'addtime' => date('Y-m-d H:i:s'),
        ];

        return $this->insert($this->getTableName(), $info);
    }

    public function deleteConfig($id)
    {
        return $this->delete($this->getTableName(), 'id=?', $id);
    }

    public function getRewardInfos($amount)
    {
        $sql = "select giftid from {$this->getTableName()} where `lower`<=? and `upper`>=? order by orderid ASC";

        return $this->getAll($sql, [$amount, $amount]);
    }
}