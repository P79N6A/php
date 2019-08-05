<?php
class Express
{
    const KUAIDIYIBAI = "kuaidiyibai";
    const JD = "jd";
    const ASYNC = true; // 异步 true ；同步 false；
    const COMPANY_JD = "jd";

    public static $_company = array(
        'jd' => '京东',
        'zhaijisong' => '宅急送',
    );

    public static $_context = array(
        'jd'          => 'opeRemark',
        'kuaidiyibai' => 'context',
    );

    public static $_time = array(
        'jd'          => 'opeTime',
        'kuaidiyibai' => 'ftime',
    );

    public static function __callStatic($method, $params)
    {
        return call_user_func_array(['ExpressSdk', $method], $params);
    }

    public static function getLastArea($orderidList)
    {
        if (empty($orderidList)) {
            return array();
        }

        if(!is_array($orderidList)){
            $orderidList = [$orderidList];
        }
        $dao = new DAOExpress();
        $info = $dao->getExpressInfo($orderidList);

        foreach($info as $k=>$v){
            if($v['content']){
                $json = json_decode($v['content'], true);
                $json = current($json);
                $temp = $json['context'];
            }else{
                $temp = '';
            }
            $express[$v['orderid']] = $temp;
        }

        foreach($orderidList as $k=>$v){
            $return[$v] = $express[$v]?$express[$v]:'';
        }


        return $return;
    }

    public static function getExpressList($orderidList)
    {
        if (empty($orderidList)) {
            return array();
        }

        if(!is_array($orderidList)){
            $orderidList = [$orderidList];
        }
        $dao = new DAOExpress();
        $info = $dao->getExpressInfo($orderidList);

        foreach($info as $k=>$v){
            $temp['company'] = (string)self::getCompanyName($v['company']);
            $temp['number']  = (string)$v['number'];
            $temp['content'] = [];
            if($v['content']){
                $jsonList = json_decode($v['content'], true);
                foreach($jsonList as $k2=>$v2){
                    $json              = [];
                    $json['context']   = $v2['context'];
                    $json['time']      = $v2['ftime'];
                    $temp['content'][] = $json;
                }

            }

            $express[$v['orderid']] = $temp;
        }

        foreach($orderidList as $k=>$v){
            $return[$v] = $express[$v]?$express[$v]:[];
        }


        return $return;
    }

    public static function eorder($source, $company, $orderid, $recName, $recPrintAddr, $recMobile, $recTel, $sendName, $sendPrintAddr, $sendMobile, $sendTel, $weight, $count, $productName, $desp = "", $remark = "")
    {
        //暂时 全部 jd
        $source = self::JD;
        $company = self::COMPANY_JD;

        $dao = new DAOExpress();
        $return = $dao->addExpress($source, $company, $orderid, $recName, $recPrintAddr, $recMobile, $recTel, $sendName, $sendPrintAddr, $sendMobile, $sendTel, $weight, $count, $productName, $desp, $remark);
        if(!self::ASYNC && $return){

            $return = ExpressSdk::eorder($source, $company, $orderid, $recName, $recPrintAddr, $recMobile, $recTel, $sendName, $sendPrintAddr, $sendMobile, $sendTel, $weight, $count);
        }

        return $return;
    }
    // 手动 下单 添加快递号
    public static function setStatusSentdown($orderid, $company, $number, $eordercontent)
    {
        $dao = new DAOExpress();
        return $dao->setStatusSentdown($orderid, $company, $number, $eordercontent);
    }

    // 手动 快递揽件
    public static function setStatusTook($orderid, $content)
    {
        try{
            Order::updateExpressStatusDeliverByOrderId($orderid);
        }catch(Exception $e){
            Logger::log("express_notify_error", 'order揽件', array("errno" => $e->getCode(), 'errmsg'=>$e->getMessage(), 'orderid'=>$orderid, 'content'=>$content));
            return false;
        }
        $dao = new DAOExpress();
        $dao->setStatusTook($orderid, $content);

        return true;
    }

    // 在途
    public static function setStatusOnOrder($orderid, $content)
    {
        $dao = new DAOExpress();
        $dao->setStatusOnOrder($orderid, $content);

        return true;
    }

    // 派件
    public static function setStatusDelivery($orderid, $content)
    {
        $dao = new DAOExpress();
        $dao->setStatusDelivery($orderid, $content);

        return true;
    }

    // 手动 用户签收
    public static function setStatusSingin($orderid, $content)
    {
        try{
            Order::updateExpressStatusReceiveByOrderId($orderid);
        }catch(Exception $e){
            Logger::log("express_notify_error", 'order签收', array("errno" => $e->getCode(), 'errmsg'=>$e->getMessage(), 'orderid'=>$orderid, 'content'=>$content));
            return false;
        }

        $dao = new DAOExpress();
        $dao->setStatusSingin($orderid, $content);

        return true;
    }

    // 拒签
    public static function setStatusRjected($orderid, $content)
    {
        $dao = new DAOExpress();
        $dao->setStatusRjected($orderid, $content);

        return true;
    }

    // 更新快递 content
    public static function setContent($orderid, $content, $status)
    {
        $dao = new DAOExpress();
        $dao->updateExpressByOrderid($orderid, $content, $status);

        return true;
    }

    public static function setStatusFail($orderid, $eordercontent)
    {
        $dao = new DAOExpress();
        return $dao->setStatusFail($orderid, $eordercontent);
    }

    public static function getCompanyName($company)
    {
        if(isset(self::$_company[$company])){
            return self::$_company[$company];
        }

        return '';
    }

    public static function getOrderidByNumber($company, $number)
    {
        $dao = new DAOExpress();
        $express = $dao->getExpressInfoByNumber($company, $number);
        $orderid = array_column($express, 'orderid');

        return $orderid;
    }

}
