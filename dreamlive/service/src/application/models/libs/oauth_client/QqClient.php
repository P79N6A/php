<?php

class QqClient
{
    /* {{{ */
    private $_appid = "1106514858";

    private $_appid_ios = "1105717319";

    private $_appid_web = "101385196";

    private $_baseurl = "https://graph.qq.com";

    private $_token;

    private $_uid;

    public function __construct($token, $uid, $token_secret = "", $channel = "")
    {
        /* {{{ */
        $this->_token = $token;
        $this->_uid = $uid;
        $this->_channel = $channel;
    }
    /* }}} */
    public function getUser()
    {
        /* {{{ */
        $user = $this->_callMethod("user/get_user_info");

        $rid = $this->getUnionid();
        if ($user) {
            return array(
                "rid" => $rid,
                "nickname" => $user["nickname"],
                "avatar" => $user["figureurl_qq_2"] ? $user["figureurl_qq_2"] : $user["figureurl_qq_1"],
                "signature" => "",
                "gender" => $user["gender"] == "男" ? "M" : "F"
            );
        }
        
        return false;
    }
    /* }}} */
    public function getUnionid()
    {
        /* {{{ */
        $url = $this->_baseurl . "/oauth2.0/me?unionid=1&access_token=" . $this->_token;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, true);
        curl_setopt($ch, CURLOPT_URL, $url);

        $curl_result = curl_exec($ch);
        $curl_errno = curl_errno($ch);
        $curl_errmsg = curl_error($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ((false === $curl_result) || (0 !== $curl_errno)) {
            $error = "curl errno:$curl_errno,errmsg:$curl_errmsg\n";
            throw new OauthException($error);
        }

        if (200 != $http_code) {
            $error = "http code:$http_code,response:$curl_result\n";
            throw new OauthException($error);
        }

        $data = substr($curl_result, 10);
        $data = substr($data, 0, -4);

        $data = json_decode($data, true);
        if(!$data) {
            throw new OauthException("unionid errror");
        }
        if($data['error']) {
            throw new OauthException("code:".$data['error'].",response: ".$data['error_description']."\n");
        }

        return $data['unionid'];
    }
    /* }}} */
    public function getFriends($cursor = 0, $num = 30)
    {
        /* {{{ */
        $data = $this->_callMethod(
            "relation/get_idollist", array(
            "reqnum" => $num,
            "startindex" => $cursor,
            "mode" => 1
            )
        );
        
        if ($data) {
            $friends = $data["data"];
            if ($friends["info"]) {
                foreach ($friends["info"] as $k => $v) {
                    $ids[] = $v["openid"];
                }
            }
            return array(
                "ids" => $ids,
                "offset" => $friends["hasnext"] == 0 ? ($cursor + 1) : 0
            );
        }
        
        return false;
    }
    /* }}} */
    private function _callMethod($method, $params = array())
    {
        /* {{{ */
        $data = $this->_get($method, $params);
        $data = json_decode($data, true);
        
        if (isset($data['ret']) && isset($data['msg']) && $data['ret'] != 0) {
            if (in_array(
                $data['ret'], array(
                100013,
                100014,
                100015,
                100016
                )
            )
            ) {
                $data['msg'] = $data['ret'] . ":" . $data['msg'];
                $data['ret'] = OAuth::ERRNO_INVALID_TOKEN;
            }
            
            throw new OauthException($data['msg'], $data['ret']);
        }
        
        return $data;
    }
    /* }}} */
    private function _get($url, $params = array())
    {
        /* {{{ */
        $params["access_token"] = $this->_token;
        $params["oauth_consumer_key"] = $this->_channel == "web" ? $this->_appid_web : Context::get("platform") == 'ios'?$this->_appid_ios: $this->_appid;
        $params["openid"] = $this->_uid;
        
        $url = $this->_baseurl . "/" . ($params ? ($url . "?" . http_build_query($params)) : $url);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        
        $curl_result = curl_exec($ch);
        $curl_errno = curl_errno($ch);
        $curl_errmsg = curl_error($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        curl_close($ch);

        if ((false === $curl_result) || (0 !== $curl_errno)) {
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
    {
        /* {{{ */
        $params["access_token"] = $this->_token;
        $params["oauth_consumer_key"] = $this->_channel == "web" ? $this->_appid_web : $this->_appid;
        $params["openid"] = $this->_uid;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_URL, $this->_baseurl . "/" . $url);
        
        $curl_result = curl_exec($ch);
        $curl_errno = curl_errno($ch);
        $curl_errmsg = curl_error($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        curl_close($ch);
        
        if ((false === $curl_result) || (0 !== $curl_errno)) {
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
