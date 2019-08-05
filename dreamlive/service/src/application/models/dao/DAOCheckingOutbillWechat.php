<?php

class DAOCheckingOutbillWechat extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("checking_outbill_wechat");
    }

    public function addBill($tradetime, $ghid, $mchid, $submch, $deviceid, $wxorder, $bzorder, $openid, $tradetype, $tradestatus, $bank, $currency, $totalmoney, $redpacketmoney,
        $wxrefund, $bzrefund, $refundmoney, $redpacketrefund, $refundtype, $refundstatus, $productname, $bzdatapacket, $fee, $rate, $addtime
    ) {
        $now = date('Y-m-d');
        $info = [
            'tradetime' => $tradetime,
            'ghid' => $ghid,
            'mchid' => $mchid,
            'submch' => $submch,
            'deviceid' => $deviceid,
            'wxorder' => $wxorder,
            'bzorder' => $bzorder,
            'openid' => $openid,
            'tradetype' => $tradetype,
            'tradestatus' => $tradestatus,
            'bank' => $bank,
            'currency' => $currency,
            'totalmoney' => $totalmoney,
            'redpacketmoney' => $redpacketmoney,
            'wxrefund' => $wxrefund,
            'bzrefund' => $bzrefund,
            'refundmoney' => $refundmoney,
            'redpacketrefund' => $redpacketrefund,
            'refundtype' => $refundtype,
            'refundstatus' => $refundstatus,
            'productname' => $productname,
            'bzdatapacket' => $bzdatapacket,
            'fee' => $fee,
            'rate' => $rate,
            'addtime' => $addtime,
        ];

        return $this->insert($this->getTableName(), $info);
    }

}