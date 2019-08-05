<?php
require_once dirname(__FILE__).DIRECTORY_SEPARATOR. "sdk". DIRECTORY_SEPARATOR. "saetv2.ex.class.php";
class SinaClient
{
    /* {{{ */
    private $_obj;

    private $_key = "130857370";

    private $_secret = "7ead38a31735b3a7326cf6b6d614d9e1";

    private $_redirect = "http://sns.whalecloud.com/sina2/callback";

    private $_token;

    private $_uid;

    public function __construct($token = "", $uid = 0)
    {
        /* {{{ */
        $this->_token = $token;
        $this->_uid   = $uid;
        
        $this->_obj   = new SaeTClientV2($this->_key, $this->_secret, $this->_token);
        $this->_oauth = new SaeTOAuthV2($this->_key, $this->_secret);
    }
    /* }}} */
    public function getOauthUrl($state = null)
    {
        /* {{{ */
        return $this->_oauth->getAuthorizeURL($this->_redirect, "code", $state);
    }
    /* }}} */
    public function getAccessToken($code)
    {
        /* {{{ */
        return $this->_oauth->getAccessToken(
            "code", array(
            "code" => $code,
            "redirect_uri" => $this->_redirect
            )
        );
    }
    /* }}} */
    public function getUser()
    {
        /* {{{ */
        $uid_info = $this->_callMethod(
            "get_token_info", array(
            $this->_token
            )
        );
        
        if ($uid_info["appkey"] != $this->_key) {
            throw new OauthException("appkey is invalid:" . $uid_info["appkey"], OAuth::ERRNO_INVALID_TOKEN);
        }
        
        if ($uid_info["uid"] != $this->_uid) {
            throw new OauthException("uid is invalid:" . $uid_info["uid"], OAuth::ERRNO_INVALID_TOKEN);
        }
        
        $user = $this->_callMethod(
            "show_user_by_id", array(
            $this->_uid
            )
        );
        
        if ($user) {
            $extend = array(
                "weibo_info" => array(
                    "verified"        => $user["verified"],
                    "verified_type"   => $user["verified_type"]/*-1为普通用户，200和220为达人用户，0为黄V用户，其它即为蓝V用户*/, 
                    "credentials"     => $user["verified_reason"],
                    "followers_count" => $user["followers_count"]
                )
            );
            return array(
                "rid"       => $user["id"],
                "nickname"  => $user["screen_name"],
                "avatar"    => $user["avatar_hd"] ? $user["avatar_hd"]: $user["avatar_large"],
                "signature" => $user["description"],
                "location"  => $user["location"],
                "gender"    => strtoupper($user["gender"]),
                "extend"    => $extend
            );
        }
        
        return false;
    }
    /* }}} */
    public function getFriends($cursor = 0, $num = 500)
    {
        /* {{{ */
        $data = $this->_callMethod(
            "friends_ids_by_id", array(
            $this->_uid,
            $cursor,
            $num
            )
        );
        
        if ($data) {
            return array(
                "ids" => $data["ids"],
                "offset" => $data["next_cursor"]
            );
        }
        
        return false;
    }
    /* }}} */
    private function _callMethod($method, $params = array())
    {
        /* {{{ */
        $data = call_user_func_array(
            array(
            $this->_obj,
            $method
            ), $params
        );
        
        if (isset($data['error_code']) && isset($data['error'])) {
            if (substr($data['error_code'], 0, 3) == "213" || in_array(
                $data['error_code'], array(
                10006,
                10013
                )
            )
            ) {
                $data['error'] = $data['error_code'] . ":" . $data['error'];
                $data['error_code'] = OAuth::ERRNO_INVALID_TOKEN;
            }
            
            throw new OauthException($data['error'], $data['error_code']);
        }
        
        return $data;
    } /* }}} */
}/*}}}*/