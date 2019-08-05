<?php

class DAOFamilyContract extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_FAMILY");
        $this->setTableName("contract");
    }

    public function add($familyid, $authorid, $starttime, $expiretime, $organization, $corporation)
    {
        $info = [
            'familyid' => $familyid,
            'authorid' => $authorid,
            'starttime' => $starttime,
            'expiretime' => $expiretime,
            'organization' => $organization,
            'corporation' => $corporation,
        ];

        return $this->insert($this->getTableName(), $info);
    }
}