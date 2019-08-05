<?php
class XcxClient
{


    private $_baseurl = '';

    private $_appid   = '';

    private $_secret  = '';

    private $_code;

    private $_encryptedData;

    private $_iv;

    public function __construct($code, $iv, $encryptedData)
    {
        $this->_code          = $code;
        $this->_iv            = $iv;
        $this->_encryptedData = $encryptedData;

        $wxConfig = Context::getConfig("WX_ACCOUNT_CONFIG");
        $this->_baseurl = $wxConfig['base_url'];
        $this->_appid   = $wxConfig['app_id'];
        $this->_secret  = $wxConfig['secret'];

    }

    public function getUser()
    {
        if($this->_code){
            $requestParams = [
                'js_code' => $this->_code,
                'grant_type' => 'authorization_code'
            ];
            list($session_key, $openid) = array_values($this->_callMethod("sns/jscode2session", $requestParams));
        }else{
            if(!$uid = Context::get('userid')){
                return false;
            }
            if(!$session_key = User::getSessionKey($uid)){
                return false;
            }
        }
        $decryptData = self::_decrypt($session_key, $this->_encryptedData, $this->_iv);

// var_dump($decryptData);die;
        $userinfo = json_decode($decryptData, true);

        if($userinfo && ($userinfo['watermark']['appid'] == $this->_appid)){
            return array(
                "rid"         => $userinfo["unionId"],
                "openid"      => $userinfo["openId"],
                "nickname"    => $userinfo["nickName"],
                "avatar"      => $userinfo["avatarUrl"] == '/0'? '': $userinfo["avatarUrl"],
                "gender"      => $userinfo["gender"]    == 1 ? "M" : ($userinfo["gender"] == 2 ? "F": "N"),
                "city"        => $userinfo["city"],
                "province"    => $userinfo["province"],
                "country"     => $userinfo["country"],
                "phone"       => $userinfo["phoneNumber"],
                "session_key" => $session_key,
            );
        }

        return false;
    }

    public function getSessionKey()
    {
        if($this->_code){
            $requestParams = [
                'js_code' => $this->_code,
                'grant_type' => 'authorization_code'
            ];
            list($session_key, $openid) = array_values($this->_callMethod("sns/jscode2session", $requestParams));

            return $session_key;
        }
        
        return false;
    }

    private function _callMethod($method, $params = array())
    {
        $params["appid"]  = $this->_appid;
        $params["secret"] = $this->_secret;

        $data = $this->_get($method, $params);
        $data = json_decode($data, true);
        
        if (isset($data['errcode']) && isset($data['errmsg']) && $data['errcode'] != 0) {
            throw new Exception($data['errmsg'], $data['errcode']);
        }
        
        return $data;
    }

    private function _get($url, $params = array())
    {
        $url = $this->_baseurl . "/" . ($params ? ($url . "?" . http_build_query($params)) : $url);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        
        $curl_result = curl_exec($ch);
        $curl_errno = curl_errno($ch);
        $curl_errmsg = curl_error($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        curl_close($ch);

        if ((FALSE === $curl_result) || (0 !== $curl_errno)) {
            $error = "curl errno:$curl_errno,errmsg:$curl_errmsg\n";
            throw new Exception($error);
        }
        
        if (200 != $http_code) {
            $error = "http code:$http_code,response:$curl_result\n";
            throw new Exception($error);
        }
        
        return $curl_result;
    }

    public static function _decrypt($sessionKey, $encryptedData, $iv)
    {
        return openssl_decrypt(
            base64_decode($encryptedData),
            'AES-128-CBC',
            base64_decode($sessionKey),
            OPENSSL_RAW_DATA,
            base64_decode($iv)
        );
    }

}