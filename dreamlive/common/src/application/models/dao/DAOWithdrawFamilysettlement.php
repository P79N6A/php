<?php

class DAOWithdrawFamilysettlement extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("withdraw_familysettlement");
    }

    public function add($withdrawid, $orderid, $familyid, $name, $owner, $family_percent, $ticket, $cash, $admin)
    {
        $info = [
            'withdrawid' => $withdrawid,
            'orderid' => $orderid,
            'familyid' => $familyid,
            'name' => $name,
            'owner' => $owner,
            'family_percent' => $family_percent,
            'ticket' => $ticket,
            'cash' => $cash,
            'admin' => $admin,
        ];

        return $this->insert($this->getTableName(), $info);
    }
}