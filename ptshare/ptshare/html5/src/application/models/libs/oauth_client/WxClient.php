<?php

class WxClient
{
 /* {{{ */
    private $_baseurl = "https://api.weixin.qq.com";

    private $_appid = "wxf1357947df240cd2";

    private $_secret = "0c9114802fd7e610dbeab3452603677b";

    private $_redirect = "http://h5.putaofenxiang.com/callback/index";

    private $_redirectBridge = "http://h5.putaofenxiang.com/callback/bridge";

    private $_token;

    private $_uid;

    public function __construct($token, $uid)
    { /* {{{ */
        $this->_token = $token;
        $this->_uid = $uid;
    }
 /* }}} */
    public function getOauthUrl($state = "")
    { /* {{{ */
        if(Context::get('ENVIRONMENT') == 'release'){
            $redirect_uri = $this->_redirect;
        }else{
            $redirect_uri = $this->_redirectBridge . "?host=" . $_SERVER["HTTP_HOST"];
        }

        return "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $this->_appid . "&redirect_uri=" . urlencode($redirect_uri) . "&response_type=code&scope=snsapi_userinfo&state=" . urlencode($state);
    }
 /* }}} */
    public function getAccessToken($code)
    { /* {{{ */
        $params["appid"] = $this->_appid;
        $params["secret"] = $this->_secret;
        $params["code"] = $code;
        $params["grant_type"] = "authorization_code";
        
        $data = $this->_get("sns/oauth2/access_token", $params);
        return json_decode($data, true);
    }
 /* }}} */
    public function getUser()
    { /* {{{ */
        $user = $this->_callMethod("sns/userinfo");
        
        if ($user) {
//             if(substr($user["unionid"],0,6) != 'oWehWw') {
//             throw new OauthException("appkey is invalid", OAuth::ERRNO_INVALID_TOKEN);
//             }
            
            $extend = array(
                "weixin_info" => array(
                    "unionid" => $user["unionid"]
                )
            );
            return array(
                "rid" => $user["unionid"],
                "nickname" => $user["nickname"],
                "avatar" => $user["headimgurl"] == '/0'? '': $user["headimgurl"],
                "signature" => "",
                "gender" => $user["sex"] == 1 ? "M" : ($user["sex"] == 2 ? "F" : "N"),
                "location" => $user["province"] . " " . $user["city"],
                "extend" => $extend
            );
        }
        
        return false;
    }
 /* }}} */
    public function getFriends($cursor = 0, $num = 30)
    { /* {{{ */
        return array();
    }
 /* }}} */
    private function _callMethod($method, $params = array())
    { /* {{{ */
        $params["access_token"] = $this->_token;
        $params["openid"] = $this->_uid;
        
        $data = $this->_get($method, $params);
        $data = json_decode($data, true);
        
        if (isset($data['errcode']) && isset($data['errmsg']) && $data['errcode'] != 0) {
            throw new OauthException($data['errmsg'], $data['errorcode']);
        }
        
        return $data;
    }
 /* }}} */
    private function _get($url, $params = array())
    { /* {{{ */
        $url = $this->_baseurl . "/" . ($params ? ($url . "?" . http_build_query($params)) : $url);
        
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
            throw new OauthException($error);
        }
        
        if (200 != $http_code) {
            $error = "http code:$http_code,response:$curl_result\n";
            throw new OauthException($error);
        }
        
        return $curl_result;
    }
 /* }}} */
    private function _post($url, $params = array())
    { /* {{{ */
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_URL, $this->_baseurl . "/" . $url);
        
        $curl_result = curl_exec($ch);
        $curl_errno = curl_errno($ch);
        $curl_errmsg = curl_error($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        curl_close($ch);
        
        if ((FALSE === $curl_result) || (0 !== $curl_errno)) {
            $error = "curl errno:$curl_errno,errmsg:$curl_errmsg\n";
            throw new OauthException($error);
        }
        
        if (200 != $http_code) {
            $error = "http code:$http_code,response:$curl_result\n";
            throw new OauthException($error);
        }
        
        return $curl_result;
    } /* }}} */
}/*}}}*/
