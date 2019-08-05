<?php
class Kuaidiyibai
{
    const KEY = "JJMpReKv9559";
    //订阅
    const POLL_URL = "https://poll.kuaidi100.com/poll";
    const POLL_CALLBACK = "https://api.ptshare.com.cn/express/kuaidiyibai";
    //实时快递查询
    const QUERY_URL = "https://poll.kuaidi100.com/poll/query.do";
    const QUERY_CUSTOMER = "970D946B810018C68A512C546F2C2E40";
    //电子面单
    const EORDER_URL = "http://api.kuaidi100.com/eorderapi.do?method=getElecOrder";
    // const EORDER_URL = "http://poll.kuaidi100.com/test/eorderapi.do?method=getElecOrder";
    const EORDER_SECRET = "c7cda147a508495ba3c57d6ba33cfca0";

    private $_company;

    private $_number;

    public function __construct($company = "", $number = "")
    {
        $this->_company = $company;
        $this->_number  = $number;
    }

    public function poll($from = "", $to = "")
    {
        $param = array(
            "company"                => $this->_company,
            "number"                 => $this->_number,
            "from"                   => $from,
            "to"                     => $to,
            "key"                    => self::KEY,
            "parameters"             => array(
                "callbackurl"        => self::POLL_CALLBACK,
                "salt"               => "",
                "resultv2"           => "1",
                "autoCom"            => "0",
                "interCom"           => "0",
                "departureCountry"   => "",
                "departureCom"       => "",
                "destinationCountry" => "",
                "destinationCom"     => ""
                )
        );

        $post_data["param"]  = json_encode($param);
        $post_data["schema"] = 'json' ;

        $data = $this->_post(self::POLL_URL, $post_data);

        $data = json_decode($data, true);

        if((isset($data['returnCode']) && $data['returnCode'] != 200) && isset($data['result']) && $data['result'] == false) {
            throw new ExpressException($data['message'], $data['returnCode']);
        }

        return true;
    }

    public function query()
    {
        $post_data["customer"] = self::QUERY_CUSTOMER;
        $param = array(
            "com" => $this->_company,
            "num" => $this->_number,
        );
        $post_data["param"] = json_encode($param);

        $post_data["sign"] = strtoupper(md5($post_data["param"]. self::KEY. self::QUERY_CUSTOMER));

        $data = $this->_post(self::QUERY_URL, $post_data);

        $data = json_decode($data, true);

        if(isset($data['returnCode']) && isset($data['result']) && $data['result'] == false) {
            throw new ExpressException($data['message'], $data['returnCode']);
        }
        
        $this->updateExpress($data['data'], $data['state']);

        return $data;
    }

    public function updateExpress($content, $status)
    {
        $orderids = Express::getOrderidByNumber($this->_company, $this->_number);
        $content = json_encode($content);
        foreach($orderids as $orderid){
            if($orderid){
                try{
                    switch ($status) {
                        case 0:
                            Express::setStatusOnOrder($orderid, $content);
                            break;
                        case 1:
                        //130 已发出
                            Express::setStatusTook($orderid, $content);
                            break;
                        case 2:
                        //上门取件失败 152
                        //收件人地址 电话错误
                            break;
                        case 3:
                            Express::setStatusSingin($orderid, $content);
                            break;
                        case 4:
                            break;
            
                    }
                }catch(Exception $e){
                    Logger::log("express_notify_error", 'kuaidiyibai', array("errno" => $e->getCode(), 'errmsg'=>$e->getMessage(), 'orderid'=>$orderid, 'content'=>$content, 'status'=>$status));
                    return false;
                }
            }

        }

        return true;
    }

    /**
        printAddr    string
       
     */
    /** 
    * some_func  
    * 函数的含义说明 
    * 
    * @access public 
    * @param string recName         必须    张三 收件人姓名
    * @param string recMobile       可选    13898896666 收件人的手机号，手机号和电话号二者其一必填
    * @param string recTel          可选    0755-86689999    收件人的电话号，手机号和电话号二者其一必填
    * @param string recAddr         可选    科技南十二路2号金蝶软件园    收件人所在地址
    * @param string recZipCode      可选    518000    收件人所在地邮编
    * @param string recProvince     可选    广东省    收件人所在省份
    * @param string recCity         可选    深圳市    收件人所在市
    * @param string recDistrict     可选    南山区    收件人所在区
    * @param string recCompany      可选    快递100    收件人所在公司名称
    * @param string recPrintAddr    可选    广东深圳市深圳市南山区科技南十二路2号金蝶软件园    收件人所在完整地址；province、city、distinct、addr 和 printAddr 任选一个必填。如果有填写province，city，distinct，addr 则系统优先读取这些信息；如果只填写printAddr，系统将自动识别对应的省、市与区
    * @param string sendName        必须    李四    寄件人姓名
    * @param string sendMobile      可选    13898896666    寄件人的手机号，手机号和电话号二者其一必填
    * @param string sendTel         可选    0755-86689999    寄件人的电话号，手机号和电话号二者其一必填
    * @param string sendAddr        可选    科技南十二路2号金蝶软件园    寄件人所在地址
    * @param string sendZipCode     可选    518000    寄件人所在地邮编
    * @param string sendProvince    可选    广东省    寄件人所在省份
    * @param string sendCity        可选    深圳市    寄件人所在市
    * @param string sendDistrict    可选    南山区    寄件人所在区
    * @param string sendCompany     可选    快递100    寄件人所在公司名称
    * @param string sendPrintAddr   可选    广东深圳市深圳市南山区科技南十二路2号金蝶软件园    寄件人所在完整地址；province、city、distinct、addr 和 printAddr 任选一个必填。如果有填写province，city，distinct，addr 则系统优先读取这些信息；如果只填写printAddr，系统将自动识别对应的省、市与区
    * @param string partnerI        可选    DB83CDE6E35CEB298B47716DF3048991    电子面单客户账户或月结账号，需向快递公司在贵司当地的网点申请；若已和快递100超市业务合作，则可不填。顺丰、EMS的可输入月结账号；若所选快递公司为宅急送（即kuaidicom字段为zhaijisong），则此项可不填。
    * @param string partnerKey      可选    EpLzwptJ8922    电子面单密码，需向快递公司在贵司当地的网点申请；若已和快递100超市业务合作，则可不填。顺丰、EMS的如果partnerId填月结账号，则此字段不填；若所选快递公司为宅急送（即kuaidicom字段为zhaijisong），则此项可不填。
    * @param string net             可选    EpLzwptJ8922    收件网点名称,由快递公司当地网点分配，若已和快递100超市业务合作，则可不填。顺丰、EMS的如果partnerId填月结账号，则此字段不填；若所选快递公司为宅急送（即kuaidicom字段为zhaijisong），则此项可不填。
    * @param string kuaidinum       可选    881443775034378914    快递单号，单号的最大长度是32个字符
    * @param string orderId         可选    881443775034378914    贵司内部自定义的订单编号,需要保证唯一性
    * @param string payType         可选    SHIPPER    支付方式：SHIPPER:寄方付（默认）、CONSIGNEE:到付、MONTHLY:月结、THIRDPARTY:第三方支付
    * @param string expType         可选    标准快递    快递类型:标准快递（默认）、顺丰特惠、EMS经济
    * @param double weight          必须    0.5    物品总重量 KG
    * @param double volumn          可选    0.1    物品总体积：CM*CM*CM
    * @param int    count           必须    1    物品总数量；如果需要子单（指同一个订单打印出多张电子面单，即同一个订单返回多个面单号），needChild = 1、count 需要大于1，如count = 2 则一个主单 一个子单，count = 3则一个主单 二个子单；返回的子单号码见返回结果的childNum字段
    * @param string remark          可选    发票    备注
    * @param double valinsPay       可选    0    保价额度
    * @param double collection      可选    0    代收货款额度
    * @param string needChild       可选    0    是否需要子单：1:需要、0:不需要(默认)。如果需要子单（指同一个订单打印出多张电子面单，即同一个订单返回多个面单号），needChild = 1、count 需要大于1，如count = 2 一个主单 一个子单，count = 3 一个主单 二个子单，返回的子单号码见返回结果的childNum字段
    * @param string needBack        可选    0    是否需要回单：1:需要、 0:不需要(默认)。返回的回单号见返回结果的returnNum字段
    * @param string cargo           可选    发票    物品名称
    * @param string needTemplate    可选    0    是否需要打印模板：1:需要、 0 不需要(默认)。如果需要，则返回要打印的模版的HTML代码，贵司可以直接将之显示到IE等浏览器，然后通过浏览器进行打印
    * @return array 
    */  
    public function eorder(
        $orderid,
        $recName,
        $recPrintAddr,
        $recMobile    = '',
        $recTel       = '',
        $sendName,
        $sendPrintAddr,
        $sendMobile   = '',
        $sendTel      = '',
        $weight,
        $count,
        $recZipCode   = '',
        $recProvince  = '',
        $recCity      = '',
        $recDistrict  = '',
        $recAddr      = '',
        $recCompany   = '',
        $sendZipCode  = '',
        $sendProvince = '',
        $sendCity     = '',
        $sendDistrict = '',
        $sendAddr     = '',
        $sendCompany  = '',
        $partnerId    = '',
        $partnerKey   = '',
        $net          = '',
        $kuaidinum    = '',
        $payType      = '',
        $expType      = '',
        $volumn       = 0,
        $remark       = '',
        $valinsPay    = 0,
        $collection   = 0,
        $needChild    = 0,
        $needBack     = 0,
        $cargo        = '',
        $needTemplate = 1
        )
    {
        $param = array(
            "recMan" => array(
                "name"      => $recName,
                "mobile"    => $recMobile,
                "tel"       => $recTel,
                "zipCode"   => $recZipCode,
                "province"  => $recProvince,
                "city"      => $recCity,
                "district"  => $recDistrict,
                "addr"      => $recAddr,
                "company"   => $recCompany,
                "printAddr" => $recPrintAddr
            ),
            "sendMan" => array(
                "name"      => $sendName,
                "mobile"    => $sendMobile,
                "tel"       => $sendTel,
                "zipCode"   => $sendZipCode,
                "province"  => $sendProvince,
                "city"      => $sendCity,
                "district"  => $sendDistrict,
                "addr"      => $sendAddr,
                "company"   => $sendCompany,
                "printAddr" => $sendPrintAddr
            ),
            "kuaidicom"    => $this->_company,
            "partnerId"    => $partnerId,
            "partnerKey"   => $partnerKey,
            "net"          => $net,
            "kuaidinum"    => $kuaidinum,
            "orderId"      => $orderid,
            "payType"      => $payType,
            "expType"      => $expType,
            "weight"       => $weight,
            "volumn"       => $volumn,
            "count"        => $count,
            "remark"       => $remark,
            "valinsPay"    => $valinsPay,
            "collection"   => $collection,
            "needChild"    => $needChild,
            "needBack"     => $needBack,
            "cargo"        => $cargo,
            "needTemplate" => $needTemplate
        );

        $post_data["param"] = json_encode($param);
        $post_data["key"] = self::KEY;
        $post_data["t"] = time();
        $post_data["sign"] = strtoupper(md5($post_data["param"]. $post_data["t"]. self::KEY. self::EORDER_SECRET));

        $json = $this->_post(self::EORDER_URL, $post_data);

        Logger::log("express_eorder", "kuaidiyibai", array("data" => $json, "post_data"=>json_encode($post_data)));

        $data = json_decode($json, true);
//{"result":true,"message":"成功","status":"200","data":[{"expressCode":"H","payaccount":"","destCode":"021","kuaidinum":"7462855050","kdOrderNum":"0946220692","destSortingCode":"SSPZ"}]}
//{"result":false,"message":"电子面单账号校验失败，请确认账号信息是否正确","status":"601","data":null}

        if(isset($data['result']) && $data['result'] == true) {
            try{
                Express::setStatusSentdown($orderid, $data['data'][0]['kuaidinum'], $json);
            }catch(Exception $e){
                throw new BizException(ERROR_SYS_DB_SQL);
            }
        }else{
            $dao = new DAOExpress();
            $dao->setStatusFail($orderid, $json);
            Logger::log("express_eorder_err", "kuaidiyibai", array("data" => $data));

            throw new ExpressException($data['message'], $data['status']);
        }

        return true;
    }

    private function _post($url, $params = array())
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $url);

        $curl_result = curl_exec($ch);
        $curl_errno  = curl_errno($ch);
        $curl_errmsg = curl_error($ch);
        $http_code   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
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

    public static function notify()
    {
        $recvSuccess = '{"result": "true","returnCode" : "200","message": "成功"}';
        $recvFail    = '{"result": "false","returnCode": "500","message": "失败"}';

        Logger::log("express_notify", "log", array("data" => $_POST['param'],"source"=>'kuaidiyibai'));
        if(isset($_POST['param'])){
            $param = json_decode($_POST['param'], true);
            
            if($param){
                if($param['status'] == 'abort'){
                    //todo  abort 异常处理 单号不存在或者已经过期
                    return $recvSuccess;
                }
                if($param['status'] == 'polling' || $param['status'] == 'shutdown'){
                    $lastResult = $param['lastResult'];

                    $state   = $lastResult['state'];
                    $company = $lastResult['com'];
                    $number  = $lastResult['nu'];
                    $content = json_encode($lastResult['data']);
                    if($company && $number){
                            $orderids = Express::getOrderidByNumber($company, $number);
                            foreach($orderids as $orderid){
                                if($orderid){
                                    //0在途中、1已揽收、2疑难、3已签收、4退签、5同城派送中、6退回、7转单等7个状态，其中4-7需要另外开通才有效
                                    try{
                                        switch ($state) {
                                            case 0:
                                                Express::setStatusOnOrder($orderid, $content);
                                                break;
                                            case 1:
                                            //130 已发出
                                                Express::setStatusTook($orderid, $content);
                                                break;
                                            case 2:
                                            //上门取件失败 152
                                            //收件人地址 电话错误
                                                break;
                                            case 3:
                                                Express::setStatusSingin($orderid, $content);
                                                break;
                                            case 4:
                                                break;
                                
                                        }
                                    }catch(Exception $e){
                                        Logger::log("express_notify", "error", array('data'=>$_POST['param'], "errno" => $e->getCode(), 'errmsg'=>$e->getMessage(), 'orderid'=>$orderid));
                                        return $recvFail;
                                    }
    
                                }
                            }
                    }
                    
                    return $recvSuccess;
                }
            }

        }

        return $recvFail;
    }

}
?>