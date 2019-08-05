<?php
class DAOExpressWorker extends DAOProxy
{
    public function __construct()
    {
        parent::__construct();
        
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("express_worker");
    }


    public function addExpress($source, $company, $orderid, $recName, $recPrintAddr, $recMobile, $recTel, $sendName, $sendPrintAddr, $sendMobile, $sendTel, $weight, $count)
    {
        $info = array(
            "source"        => $source,
            "company"       => $company,
            "orderid"       => $orderid,
            "recName"       => $recName,
            "recPrintAddr"  => $recPrintAddr,
            "recMobile"     => $recMobile,
            "recTel"        => $recTel,
            "sendName"      => $sendName,
            "sendPrintAddr" => $sendPrintAddr,
            "sendMobile"    => $sendMobile,
            "sendTel"       => $sendTel,
            "weight"        => $weight,
            "count"         => $count,
            "result"        => 'N',
            "addtime"       => date('Y-m-d H:i:s'),
            "modtime"       => date('Y-m-d H:i:s')
        );

        return $this->insert($this->getTableName(), $info);
    }

    public function finishExpress($orderid)
    {
        $info = array(
            "result"  => 'Y',
            "modtime" => date('Y-m-d H:i:s')
        );
        return $this->update($this->getTableName(), $info, "orderid=?", array($orderid));
    }

    public function failExpress($orderid, $content)
    {
        $info = array(
            "result"  => 'F',
            "content" => $content,
            "modtime" => date('Y-m-d H:i:s')
        );
        return $this->update($this->getTableName(), $info, "orderid=?", array($orderid));
    }
    
}
?>