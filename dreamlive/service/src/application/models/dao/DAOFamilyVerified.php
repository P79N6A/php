<?php

class DAOFamilyVerified extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_FAMILY");
        $this->setTableName("verified");
    }

    public function getVerifiedInfo($authorid)
    {
        $sql = "select * from {$this->getTableName()} where authorid=?";
        return $this->getRow($sql, $authorid);
    }

    public function add($authorid, $realname, $mobile, $idcard, $address, $qq, $wechat, $imgs)
    {
        $info = [
            'authorid' => $authorid,
            'realname' => $realname,
            'mobile' => $mobile,
            'idcard' => $idcard,
            'address' => $address,
            'qq' => $qq,
            'wechat' => $wechat,
            'imgs' => $imgs,
            'verified_status' => 'ACCEPT',
            'addtime' => date('Y-m-d H:i:s'),
            'modtime' => date('Y-m-d H:i:s'),
        ];
        return $this->insert($this->getTableName(), $info);
    }

}
