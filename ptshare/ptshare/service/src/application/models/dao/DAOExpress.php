<?php
class DAOExpress extends DAOProxy
{
    const STATUS_PREPARE  = 0; //待发货
    const STATUS_SENTDOWN = 1; //已下单
    const STATUS_TOOK     = 2; //已揽件
    const STATUS_ONORDER  = 3; //在途
    const STATUS_DELIVERY = 4; //派件
    const STATUS_SINGIN   = 10; //签收
    const STATUS_REJECTED = 20; //拒收
    const STATUS_FAILURE  = -1; //失败

    public function __construct()
    {
        parent::__construct();
        
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("express");
    }
    public function addExpress($source, $company, $orderid, $recName, $recPrintAddr, $recMobile, $recTel, $sendName, $sendPrintAddr, $sendMobile, $sendTel, $weight, $count, $productName, $desp = "", $remark = "")
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
            "productName"   =>$productName,
            "desp"          =>$desp,
            "remark"        =>$remark,
            "content"       => '',
            "eordercontent" => '',
            "addtime"       => date('Y-m-d H:i:s'),
            "modtime"       => date('Y-m-d H:i:s')
        );

        return $this->insert($this->getTableName(), $info);
    }

    public function setStatusSentdown($orderid, $company, $number, $eordercontent)
    {
        $info = array(
            "orderid" => $orderid,
            "company" => $company,
            "number"  => $number,
            "status"  => self::STATUS_SENTDOWN,
            "modtime" => date('Y-m-d H:i:s')
        );
        if($eordercontent){
            $info['eordercontent'] = $eordercontent;
        }

        return $this->update($this->getTableName(), $info, "orderid=?", array($orderid));
    }

    public function setStatusFail($orderid, $eordercontent)
    {
        $info = array(
            "orderid" => $orderid,
            "status"  => self::STATUS_FAILURE,
            "modtime" => date('Y-m-d H:i:s')
        );
        if($eordercontent){
            $info['eordercontent'] = $eordercontent;
        }

        return $this->update($this->getTableName(), $info, "orderid=?", array($orderid));
    }

    public function setStatusTook($orderid, $content)
    {
        $info = array(
            "orderid" => $orderid,
            "status"  => self::STATUS_TOOK,
            "modtime" => date('Y-m-d H:i:s')
        );
        if($content){
            $info['content'] = $content;
        }

        return $this->update($this->getTableName(), $info, "orderid=?", array($orderid));
    }

    public function setStatusOnOrder($orderid, $content)
    {
        $info = array(
            "orderid" => $orderid,
            "status"  => self::STATUS_ONORDER,
            "modtime" => date('Y-m-d H:i:s')
        );
        if($content){
            $info['content'] = $content;
        }

        return $this->update($this->getTableName(), $info, "orderid=?", array($orderid));
    }

    public function setStatusDelivery($orderid, $content)
    {
        $info = array(
            "orderid" => $orderid,
            "status"  => self::STATUS_DELIVERY,
            "modtime" => date('Y-m-d H:i:s')
        );
        if($content){
            $info['content'] = $content;
        }

        return $this->update($this->getTableName(), $info, "orderid=?", array($orderid));
    }

    public function setStatusSingin($orderid, $content)
    {
        $info = array(
            "orderid" => $orderid,
            "status"  => self::STATUS_SINGIN,
            "modtime" => date('Y-m-d H:i:s')
        );
        if($content){
            $info['content'] = $content;
        }

        return $this->update($this->getTableName(), $info, "orderid=?", array($orderid));
    }
    
    public function setStatusRjected($orderid, $content)
    {
        $info = array(
            "orderid" => $orderid,
            "status"  => self::STATUS_REJECTED,
            "modtime" => date('Y-m-d H:i:s')
        );
        if($content){
            $info['content'] = $content;
        }

        return $this->update($this->getTableName(), $info, "orderid=?", array($orderid));
    }

    public function updateExpress($company, $number, $status, $content)
    {
        $info = array(
            "status"  => $status,
            "content" => $content,
            "modtime" => date('Y-m-d H:i:s')
        );
        return $this->update($this->getTableName(), $info, "company=? and number=?", array($company, $number));
    }

    public function updateExpressByOrderid($orderid, $content, $status)
    {
        $info = array(
            "content" => $content,
            "modtime" => date('Y-m-d H:i:s')
        );
        if($status){
            $info['status'] = $status;
        }

        return $this->update($this->getTableName(), $info, "orderid=?", array($orderid));
    }

    public function getExpressInfo($orderidList)
    {
        $orderids = implode(',', $orderidList);
        $sql = "select id,orderid,company,number,status,content,addtime,modtime,source, productName, desp, remark from " . $this->getTableName() . " where orderid in({$orderids})";

        return $this->getAll($sql);
    }
    
    public function getExpressInfoByOrderid($orderid)
    {
        $sql = "select id,orderid,company,number,status,content,addtime,modtime,source, productName, desp, remark from " . $this->getTableName() . " where orderid =?";

        return $this->getRow($sql, array($orderid));
    }

    public function getExpressInfoByNumber($company, $number)
    {
        $sql = "select id,orderid,company,number,status,content,addtime,modtime,source, productName, desp, remark from " . $this->getTableName() . " where company=? and number=?";

        return $this->getAll($sql, array($company, $number));
    }
    
}
?>