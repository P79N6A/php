<?php
class Jd
{
    //https://oauth.jd.com/oauth/authorize?response_type=code&client_id=4D3A1FBB926E7B9ED2D18B060806BA9C&redirect_uri=urn:ietf:wg:oauth:2.0:oob&state=1212
    const APP_KEY       = "4D3A1FBB926E7B9ED2D18B060806BA9C";
    const APP_SECRET    = "b06a380e45774f519b7670eb691917a0";
    const ACCESS_TOKEN  = "79cfe43e-105b-44d1-beef-50472e022157";

    const BASE_URL       = "https://api.jd.com/routerjson";
    const VERSION        = "2.0";
    const FORMAT         = "json";
    const JSON_PARAM_KEY = "360buy_param_json";

    const CUSTOMER_CODE = "028k227596"; // 商家编码

    const API_WAYBILLCODE_GET     = "jingdong.etms.waybillcode.get";    // 获取快递运单号
    const API_RANGE_CHECK         = "jingdong.etms.range.check";        // 是否可以京配
    const API_WAYBILL_SEND        = "jingdong.etms.waybill.send";       // 青龙接单接口
    const API_TRACE_GET           = "jingdong.ldop.receive.trace.get";  // 查询物流跟踪消息
    const API_PICKUPORDER_RECEIVE = "jingdong.ldop.receive.pickuporder.receive";  // 取件单下单

    const QUEUE_DELIVERYID = "PT_QUEUE_JD_DELIVERY_ID"; // 运单id

    private $_company;

    private $_number; //物流单号

    private $_status = [
        "快递签收"      => 2,
        "配送员完成揽收" => 2,
        "分拣中心发货"  => 3,
        "分拣中心验货"  => 3,
        "站点收货"      => 3,
        "站点验货"      => 3,
        "配送员收货"    => 4,
        "妥投"         => 10,
        "拒收"         => 20,
    ];

    public function __construct($company = "", $number = "")
    {
        $this->_company = $company;
        $this->_number  = $number;
    }

    public function poll($from = "", $to = "")
    {

        return true;
    }

    public function query($from = "", $to = "")
    {
        $params['customerCode'] = self::CUSTOMER_CODE;
        $params['waybillCode'] = $this->_number;
        // $params['waybillCode'] = "VB44006699225";

        $result = $this->_callMethod(self::API_TRACE_GET, $params);

        $resultInfo = $result['querytrace_result'];

        // $resultInfo = json_decode('{
        //     "data": [{
        //         "opeTitle": "快递签收",
        //         "opeTime": "2018\/07\/04 17:28:42",
        //         "opeRemark": "货物已交付京东物流",
        //         "opeName": "京东快递",
        //         "waybillCode": "VB44006699225"
        //     }, {
        //         "opeTitle": "分拣中心验货",
        //         "opeTime": "2018\/07\/04 22:54:12",
        //         "opeRemark": "货物已到达【成都青白江分拣中心】",
        //         "opeName": "京东快递",
        //         "waybillCode": "VB44006699225"
        //     }],
        //     "messsage": "成功",
        //     "code": 100
        // }',true);

        if($resultInfo['code'] != 100){
            Logger::log("express_err", "jd", array("data" => json_encode($result), "params"=>json_encode($params)));
            throw new ExpressException($resultInfo['message'], $resultInfo['code']);
        }else{
            
            $this->updateExpress($resultInfo['data']);

            return $resultInfo['data'];
        }

        return [];
    }

    public function updateExpress($jdContent)
    {
        $lastContent = end($jdContent);
        if(!$lastContent){
            return false;
        }
        $status = $this->_status[$lastContent["opeTitle"]];
        $orderids = Express::getOrderidByNumber($this->_company, $this->_number);

        if($orderids){
            $content = [];
            foreach($jdContent as $k => $v){
                $temp['ftime'] =  date("Y-m-d H:i:s", strtotime($v['opeTime']));
                $temp['context'] = $v['opeRemark'];
                $content[] = $temp;
            }
            $content = json_encode(array_reverse($content));

            foreach($orderids as $orderid){
                if(!$orderid){
                    continue;
                }
                try{

                    switch ($status) {
                        case DAOExpress::STATUS_TOOK:
                            Express::setStatusTook($orderid, $content);
                            break;
                        case DAOExpress::STATUS_ONORDER:
                            Express::setStatusOnOrder($orderid, $content);
                            break;
                        case DAOExpress::STATUS_DELIVERY:
                            Express::setStatusDelivery($orderid, $content);
                            break;
                        case DAOExpress::STATUS_SINGIN:
                            Express::setStatusSingin($orderid, $content);
                            break;
                        case DAOExpress::STATUS_REJECTED:
                            Express::setStatusRjected($orderid, $content);
                            break;
            
                    }
                }catch(Exception $e){
                    Logger::log("express_notify_error", 'jd', array("errno" => $e->getCode(), 'errmsg'=>$e->getMessage(), 'orderid'=>$orderid, 'content'=>$content, 'status'=>$status));
                    return false;
                }
            }
        }

        return true;
    }

    public function getWaybillcode($preNum = 1)
    {
        $params['customerCode'] = self::CUSTOMER_CODE;
        $params['preNum']       = $preNum; // 获取运单号数量（需要填写正整数，最大100）
        $params['orderType']    = "";

        $result = $this->_callMethod(self::API_WAYBILLCODE_GET, $params);

        $resultInfo = $result['resultInfo'];

        if($resultInfo['code'] != 100){
            Logger::log("express_err", "jd", array("data" => json_encode($result), "params"=>json_encode($params)));
            throw new ExpressException($resultInfo['message'], $resultInfo['code']);
        }else{
            return $resultInfo['deliveryIdList'];
        }

        return [];
    }

    public function getDeliveryId()
    {
        try {
            $cache = Cache::getInstance("REDIS_CONF_CACHE");

            $deliveryId = $cache->lPop(self::QUEUE_DELIVERYID);
            if(!$deliveryId){
                throw new BizException(ERROR_QUEUE_DELIVERYID_ISNULL);
            }
        } catch(Exception $e) {
            $idList = $this->getWaybillcode();
            if(empty($idList)){
                throw new BizException(ERROR_QUEUE_DELIVERYID_ISNULL);
            }

            $deliveryId = current($idList);
        }

        return $deliveryId;
    }
    /** 
    * @access public 
    * @param string orderId         必须    订单id
    * @param string goodsType       必须    配送业务类型（ 1:普通，3:填仓，4:特配，5:鲜活，6:控温，7:冷藏，8:冷冻，9:深冷）默认是1
    * @param string wareHouseCode   必须    发货仓
    * @param string receiveAddress  必须    收件人地址
    * @return array 
    */  
    public function rangeCheck($orderId, $receiveAddress, $wareHouseCode = "", $goodsType = 1)
    {
        $method = self::API_RANGE_CHECK;

        $params['customerCode']   = self::CUSTOMER_CODE;
        $params['orderId']        = $orderId;
        $params['goodsType']      = $goodsType;
        $params['wareHouseCode']  = $wareHouseCode ? $wareHouseCode : self::CUSTOMER_CODE;
        $params['receiveAddress'] = $receiveAddress;
        
        $result = $this->_callMethod($method, $params);

        $resultInfo = $result['resultInfo'];

        if($resultInfo['rcode'] == 200){
            //超区
            Logger::log("express_err", "jd", array("data" => json_encode($result), "params"=>json_encode($params), "method" => $method));
            throw new ExpressException($resultInfo['rmessage'], $resultInfo['rcode']);
        }elseif($resultInfo['rcode'] == 150){
            //人工预分拣 
            Logger::log("express_err", "jd", array("data" => json_encode($result), "params"=>json_encode($params), "method" => $method));
            throw new ExpressException($resultInfo['rmessage'], $resultInfo['rcode']);
        }

        return $resultInfo;
    }

    /** 
    * 仓库发 用户
    * @access public 
    * @param string receiveName         必须    张三 收件人姓名
    * @param string receiveMobile       可选    13898896666 收件人的手机号，手机号和电话号二者其一必填
    * @param string receiveTel          可选    0755-86689999    收件人的电话号，手机号和电话号二者其一必填
    * @param string receiveAddress      必须    科技南十二路2号金蝶软件园    收件人所在地址
    * @return array 
    */  
    public function eorder(
        $orderid,
        $receiveName,
        $receiveAddress,
        $receiveMobile    = '',
        $receiveTel       = '',
        $senderName,
        $senderAddress,
        $senderMobile   = '',
        $senderTel      = '',
        $weight,
        $packageCount,
        $volume = 1,
        $desp = "",
        $remark = ""
        )
    {
        $rangeInfo = $this->rangeCheck($orderid, $receiveAddress);

        $params['deliveryId'] = $this->getDeliveryId(); // 运单号
        // $params['deliveryId']     = "VD43990526930";
        $params['salePlat']       = "0030001"; // 销售平台 其他(0030001
        $params['customerCode']   = self::CUSTOMER_CODE;

        $params['orderId']        = $orderid;
        $params['senderName']     = $senderName;
        $params['senderAddress']  = $senderAddress;
        $params['senderTel']      = $senderTel;
        $params['senderMobile']   = $senderMobile;

        $params['receiveName']    = $receiveName;
        $params['receiveAddress'] = $receiveAddress;
        $params['receiveTel']     = $receiveTel;
        $params['receiveMobile']  = $receiveMobile;
        
        $params['packageCount']   = $packageCount; // 包裹数(大于0，小于1000)
        $params['weight']         = $weight; // 重量(单位：kg，保留小数点后两位)
        $params['vloumn']         = $volume; // 体积(单位：cm3，保留小数点后两位) volumn
        $params['description']    = $desp; // 商品描述
        $params['remark']         = $remark; // 商品描述

        $result = $this->_callMethod(self::API_WAYBILL_SEND, $params);
        
        // $result = json_decode('{"code":"0","resultInfo":{"message":"成功","code":100,"deliveryId":"VD45067245134","orderId":"1"}}', true);

        Logger::log("express_eorder", "jd", array("data" => json_encode($result), "params"=>json_encode($params)));
        $resultInfo = $result['resultInfo'];
        
        if($resultInfo['code'] == 100){
            try{
                Express::setStatusSentdown($orderid, $this->_company, $resultInfo['deliveryId'], json_encode($result));
            }catch(Exception $e){
                throw new BizException(ERROR_SYS_DB_SQL);
            }
    
        }else{
            // 下单失败
            $dao = new DAOExpress();
            $dao->setStatusFail($orderid, json_encode($result));

            Logger::log("express_err", "jd", array("data" => json_encode($result), "params"=>json_encode($params), "method" => $method));
            throw new ExpressException($resultInfo['message'], $resultInfo['code']);
        }
        
        return true;
    }
    //用户之间 下单
    public function pickuporder(
        $orderid,
        $receiveName,
        $receiveAddress,
        $receiveTel,
        $senderName,
        $senderAddress,
        $senderTel,
        $weight,
        $packageCount,
        $volume,
        $productName,
        $desp,
        $remark
    )
    {
        $params['customerCode'] = self::CUSTOMER_CODE;

        $params['orderId']          = $orderid;
        $params['pickupName']       = $senderName;
        $params['pickupAddress']    = $senderAddress;
        $params['pickupTel']        = $senderTel;

        $params['customerContract'] = $receiveName;
        $params['backAddress']      = $receiveAddress;
        $params['customerTel']      = $receiveTel;
        
        $params['productCount']     = $packageCount; // 商品数量
        $params['weight']           = $weight; // 重量 
        $params['volume']           = $volume; // 体积
        $params['productName']      = $productName; // 商品名称

        $params['desp']             = $desp; // 商品描述
        $params['remark']           = $remark; // 备注

        $result = $this->_callMethod(self::API_PICKUPORDER_RECEIVE, $params);
        // $result = '{"jingdong_ldop_receive_pickuporder_receive_responce":{"code":"0","receivepickuporder_result":{"pickUpCode":"QWD00005996116","messsage":"成功","code":100}}}';
        // $result = json_decode($result, true);
        // $result = current($result);
        $resultInfo = $result['receivepickuporder_result'];

        Logger::log("express_eorder", "jd", array("data" => json_encode($result), "params"=>json_encode($params)));

        if($resultInfo['code'] == 100){
            try{
                Express::setStatusSentdown($orderid, $this->_company, $resultInfo['pickUpCode'], json_encode($result));
            }catch(Exception $e){

                throw new BizException(ERROR_SYS_DB_SQL);
            }
    
        }else{
            // 下单失败
            $dao = new DAOExpress();
            $dao->setStatusFail($orderid, json_encode($result));

            Logger::log("express_err", "jd", array("data" => json_encode($result), "params"=>json_encode($params), "method" => $method));
            throw new ExpressException($resultInfo['message'], $resultInfo['code']);
        }
        
        return true;
    }

    private function _callMethod($method, $params = array())
    {
        $json = $this->_get($method, $params);
        $data = json_decode($json, true);

        // $result = $data[$this->_getRespKey($method)];
        $result = current($data);

        if (!$result || $result['code']) {
            Logger::log("express_err", "jd", array("data" => $json, "params"=>json_encode($params), "method" => $method));
            throw new ExpressException($result['zh_desc'], $result['code']);
        }

        return $result;
    }
    private function _get($method, $params = array())
    {
        $sysParams["app_key"]   = self::APP_KEY;
        $sysParams["v"]         = self::VERSION;
        $sysParams["format"]    = self::FORMAT;
        $sysParams["method"]    = $method;
        $sysParams["timestamp"] = date("Y-m-d H:i:s");
        $sysParams["access_token"] = self::ACCESS_TOKEN;
        $sysParams[self::JSON_PARAM_KEY] = json_encode($params);

        $sysParams["sign"] = $this->_generateSign($sysParams);

        $url = self::BASE_URL . "?" . ($sysParams ? (http_build_query($sysParams)) : "");

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        
        $curl_result = curl_exec($ch);
        $curl_errno = curl_errno($ch);
        $curl_errmsg = curl_error($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        curl_close($ch);

        if ((FALSE === $curl_result) || (0 !== $curl_errno)) {
            $error = "curl errno:$curl_errno,errmsg:$curl_errmsg\n";
            throw new ExpressException($error);
        }
        
        if (200 != $http_code) {
            $error = "http code:$http_code,response:$curl_result\n";
            throw new ExpressException($error);
        }
        
        return $curl_result;
    }

    public function _getRespKey($method)
    {
        return str_replace(".", "_", $method)."_responce";
    }

    protected function _generateSign($params)
    {
        ksort($params);
        $stringToBeSigned = self::APP_SECRET;
        foreach ($params as $k => $v)
        {
            if("@" != substr($v, 0, 1))
            {
                $stringToBeSigned .= "$k$v";
            }
        }
        unset($k, $v);
        $stringToBeSigned .= self::APP_SECRET;
        return strtoupper(md5($stringToBeSigned));
    }

    public static function notify()
    {
       return ;
    }

}

?>