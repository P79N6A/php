<?php

class FacebookClient
{
    /* {{{ */
    private $_baseurl = "https://www.facebook.com";

    private $_graphUrl = "https://graph.facebook.com";

    private $_graphVersion = "v2.8";

    private $_appid = "935612963235344";

    private $_secret = "2cb0547925b441d0fd0c2cd6671ecc4d";

    private $_redirect = "http://weiyingonline.com/test/fbcallback";

    private $_token;

    private $_uid;

    public function __construct($token, $uid)
    {
        /* {{{ */
        $this->_token = $token;
        $this->_uid = $uid;
    }/* }}} */

    public function getOauthUrl($state = null,$scope = array())
    {
        /* {{{ */
        $params = array(
        'client_id' => $this->_appid,
        'state' => $state,
        'response_type' => 'token',
        'redirect_uri' => $this->_redirect,
        'scope' => implode(',', $scope),
        );

        return $this->_baseurl . '/' . $this->_graphVersion . '/dialog/oauth?' . http_build_query($params, null, '&');
    }/* }}} */

    public function getUser()
    {
        /* {{{ */
        $user = $this->_callMethod("me", array("fields"=>"id,name,email,first_name,last_name,link,birthday,picture"));

        if ($user) {
            return array(
            "rid" => $user["id"],
            "nickname" => $user["name"],
            "avatar" => "",
            "signature" => "",
            );
        }

        return false;
    }/* }}} */

    public function getFriends($cursor = 0, $num = 30)
    {
        /* {{{ */
        return array();
    }
    /* }}} */
    private function _callMethod($method, $params = array())
    {
        /* {{{ */
        $params["access_token"] = $this->_token;

        $data = $this->_get($method, $params);
        $data = json_decode($data, true);

        if (isset($data['errcode']) && isset($data['errmsg']) && $data['errcode'] != 0) {
            throw new OauthException($data['errmsg'], $data['errorcode']);
        }

        return $data;
    }
    /* }}} */
    private function _get($url, $params = array())
    {
        /* {{{ */
        $url = $this->_graphUrl . "/" . $this->_graphVersion . "/" . ($params ? ($url . "?" . http_build_query($params)) : $url);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
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
