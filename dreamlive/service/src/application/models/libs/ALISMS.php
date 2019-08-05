<?php
class ALISMS
{

    const APP_KEY = 'LTAIpHgKCFkwx00O';
    const APP_SECRET = 'ZebOyTa3SFmAba0CR1epHhS0WYFAuw';

    public static function send($phone, $code, $templateid)
    {
        $start_time = microtime(true);
        
        $params = array ();
    
        $accessKeyId = self::APP_KEY;
        $accessKeySecret = self::APP_SECRET;
    
        $params["PhoneNumbers"] = $phone;
    
        $params["SignName"] = "追梦直播";
    
        $params["TemplateCode"] = $templateid;
    
        $params['TemplateParam'] = Array (
            "code" => $code,
        );
    
        if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
            $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
        }
    
        $helper = new SignatureHelper();
        try{
            $content = $helper->request(
                $accessKeyId,
                $accessKeySecret,
                "dysmsapi.aliyuncs.com",
                array_merge(
                    $params, array(
                    "RegionId" => "cn-hangzhou",
                    "Action" => "SendSms",
                    "Version" => "2017-05-25",
                    )
                )
            );
        }catch(Exception $e){
            ;
        }
        Logger::log('alisms', 'result:'.json_encode($content), array('phone'=>$phone,"code"=>$code, "type"=>$templateid, 'addtime'=>date('Y-m-d H:i:s'), 'consume'=>round((microtime(true) - $start_time) * 1000)));
        
        return true;
    }

    //账户警告
    public static function alarm(array $phone, $uid,$name)
    {
        $templateid = "SMS_127158082";
        $start_time = microtime(true);

        $params = array ();

        $accessKeyId = self::APP_KEY;
        $accessKeySecret = self::APP_SECRET;

        $params["PhoneNumbers"] = implode(',', $phone);

        $params["SignName"] = "追梦直播";

        $params["TemplateCode"] = $templateid;

        $params['TemplateParam'] = Array (
            "uid" => $uid,
            'type' => $name,
        );

        if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
            $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
        }

        $helper = new SignatureHelper();
        try{
            $content = $helper->request(
                $accessKeyId,
                $accessKeySecret,
                "dysmsapi.aliyuncs.com",
                array_merge(
                    $params, array(
                    "RegionId" => "cn-hangzhou",
                    "Action" => "SendSms",
                    "Version" => "2017-05-25",
                    )
                )
            );
        }catch(Exception $e){
            ;
        }
        //Logger::log('alisms', 'result:'.json_encode($content), array('phone'=>$phone,"code"=>$uid, "type"=>$templateid, 'addtime'=>date('Y-m-d H:i:s'), 'consume'=>round((microtime(true) - $start_time) * 1000)));

        return true;
    }
}
class SignatureHelper
{

    /**
     * 生成签名并发起请求
     *
     * @param  $accessKeyId string AccessKeyId (https://ak-console.aliyun.com/)
     * @param  $accessKeySecret string AccessKeySecret
     * @param  $domain string API接口所在域名
     * @param  $params array API具体参数
     * @param  $security boolean 使用https
     * @return bool|\stdClass 返回API接口调用结果，当发生错误时返回false
     */
    public function request($accessKeyId, $accessKeySecret, $domain, $params, $security=false)
    {
        $apiParams = array_merge(
            array (
            "SignatureMethod" => "HMAC-SHA1",
            "SignatureNonce" => uniqid(mt_rand(0, 0xffff), true),
            "SignatureVersion" => "1.0",
            "AccessKeyId" => $accessKeyId,
            "Timestamp" => gmdate("Y-m-d\TH:i:s\Z"),
            "Format" => "JSON",
            ), $params
        );
        ksort($apiParams);

        $sortedQueryStringTmp = "";
        foreach ($apiParams as $key => $value) {
            $sortedQueryStringTmp .= "&" . $this->encode($key) . "=" . $this->encode($value);
        }

        $stringToSign = "GET&%2F&" . $this->encode(substr($sortedQueryStringTmp, 1));

        $sign = base64_encode(hash_hmac("sha1", $stringToSign, $accessKeySecret . "&", true));

        $signature = $this->encode($sign);

        $url = ($security ? 'https' : 'http')."://{$domain}/?Signature={$signature}{$sortedQueryStringTmp}";

        try {
            $content = $this->fetchContent($url);
            return json_decode($content);
        } catch( \Exception $e) {
            return false;
        }
    }

    private function encode($str)
    {
        $res = urlencode($str);
        $res = preg_replace("/\+/", "%20", $res);
        $res = preg_replace("/\*/", "%2A", $res);
        $res = preg_replace("/%7E/", "~", $res);
        return $res;
    }

    private function fetchContent($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt(
            $ch, CURLOPT_HTTPHEADER, array(
            "x-sdk-client" => "php/2.0.0"
            )
        );

        if(substr($url, 0, 5) == 'https') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        $rtn = curl_exec($ch);

        if($rtn === false) {
            trigger_error("[CURL_" . curl_errno($ch) . "]: " . curl_error($ch), E_USER_ERROR);
        }
        curl_close($ch);

        return $rtn;
    }
}