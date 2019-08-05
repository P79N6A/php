<?php
class Captcha
{
    /*
    3039075 更换支付宝
    3032287更换手机
    3031193找回密码
    3030166签约家族
    3032138个人认证
    3032136提现
    3031189登录
    3033126绑定手机
    3032137注册
    */
    private static $_msg = array(
        "login"     => "3031189",
        "reg"       => "3032137",
        "forgot"    => "3031193",
        "bind"      =>  "3033126",
        "unbind"    => "",
        "verify"    => "3032138",
        "exchange"  => "3032136",
        "withdraw"    => "3039075",// 提现绑定
        "contract"    => "3030166",// 签约家族
        "bindwithdraw"=> "3053223",// 绑定提现手机号
        );
    private static $_ali_msg = array(
        "login"     => "SMS_122289530",
        "reg"       => "SMS_125024564",
        "forgot"    => "SMS_122284617",
        "bind"      => "SMS_122284618",
        "unbind"    => "",
        "verify"    => "SMS_122299665",
        "exchange"  => "SMS_122299666",
        "withdraw"    => "SMS_122284661",// 提现绑定
        "contract"    => "SMS_122289542",// 签约家族
        "bindwithdraw"=> "SMS_122299669",// 绑定提现手机号
        );

    private static $_mail_msg = array(
        "reg"       => array(
            "subject" =>"追梦直播-注册",
            "content" =>"欢迎来到追梦直播，你的验证码是： [%s]"
        ),
        "forgot"       => array(
            "subject" =>"追梦直播-找回密码",
            "content" =>"我们收到了你的重置密码请求，你的验证码是：[%s]"
        ),
    );

    private static $_abroad_mobile_msg = array(
        "bind"     => "【DreamApp】You are now binding a mobile phone. The authentication code is %s which is valid within 10 minutes. It is highly important, so please do not disclose it to anyone.",
        "reg"      => "【DreamApp】You are now registering for a Dream App. The authentication code is %s which is valid within 10 minutes. It is highly important, so please do not disclose it to anyone.",
        "forgot"   => "【DreamApp】You are now retrieving your password. The authentication code is %s which is valid within 10 minutes. It is highly important, so please do not disclose it to anyone.",
    );

    const EXPIRE_CODE       = 600;

    const EXPIRE_TIMES      = 86400;

    const MAX_TIMES_DAY     = 20;
    // 每日限制
    const LIMINAL_TIME      = 300;
    // 误差
    const INTERVAL_TIME     = 55;
    // 发送间隔
    const ALLOW_ERROR_TIMES = 5;
    // 发送间隔
    public static function verify($mobile, $code, $type = "login")
    {
        /* {{{ */
        $code_c = self::_getCode($mobile, $type);
        
        if (false !== $code_c) {
            $code_info = explode(":", $code_c);
            if (0 === strcmp($code_info[0], $code)) {
                self::_delCode($mobile, $type);
                self::_delVerifyTimes($mobile, $type);
                return true;
            }
            
            if (! self::_checkVerifyTimes($mobile, $type)) {
                self::_delCode($mobile, $type);
            }
        }
        
        return false;
    }
    public static function VerifyForgotNotDel($mobile, $code)
    {
        /* {{{ */
        $code_c = self::_getCode($mobile, "forgot");

        if (false !== $code_c) {
            $code_info = explode(":", $code_c);
            if (0 === strcmp($code_info[0], $code)) {
                return true;
            }
        }

        return false;
    }
    /* }}} */
    public static function checkSendTimes($mobile, $type = "login")
    {
        /* {{{ */
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $key = self::_getTimesKey($mobile, $type);
        
        if (false !== ($times = $cache->incr($key))) {
            if ($times == 1) {
                $cache->expire($key, self::EXPIRE_TIMES);
            }
            
            return $times <= self::MAX_TIMES_DAY;
        }
        
        if (false !== ($times = $cache->set($key, 1, self::EXPIRE_TIMES))) {
            return true;
        }
        
        return false;
    }
    /* }}} */
    public static function checkIntervalTime($mobile, $type = "login")
    {
        /* {{{ */
        if ($sendtime = self::_getSendTime($mobile, $type)) {
            if ($sendtime + self::INTERVAL_TIME > $_SERVER["REQUEST_TIME"]) {
                return false;
            }
        }
        
        return true;
    }
    /* }}} */
    public static function send($mobile, $type = "login", $msg = null)
    {
        /* {{{ */
        $code = self::_generate($mobile, $type);
        self::_setSendTime($mobile, $type);

        try {
            if(preg_match("/^1[3456789]{1}\d{9}$/", $mobile)) {
                if(mt_rand(1, 100)<0) {
                    SMS::send($mobile, $code, self::$_msg[$type]);
                }else{
                    ALISMS::send($mobile, $code, self::$_ali_msg[$type]);
                }
            }else{
                SMS::sendAbroad($mobile, $code, self::$_abroad_mobile_msg[$type]);
            }
        } catch (Exception $e) {
            self::_delSendTime($mobile, $type);
            throw new BizException(ERROR_BIZ_PASSPORT_ERROR_SMS_SEND);
        }
        
        self::_delVerifyTimes($mobile, $type);
        
        return self::_getTimes($mobile, $type);
    }
    public static function sendEmail($email, $type = "reg")
    {
        /* {{{ */
        if ($code_c = self::_getCode($email, $type)) {
            $code_info = explode(":", $code_c);

            if ($code_info[1] + self::EXPIRE_CODE - self::LIMINAL_TIME > $_SERVER["REQUEST_TIME"]) {
                $code = $code_info[0];
            }
        }

        if (!$code) {
            $code = self::_generate($email, $type);
        }

        try {
            include_once "process_client/ProcessClient.php";
            ProcessClient::getInstance("dream")->addTask(
                "passport_send_email",
                array("email" => $email,
                    "subject" => self::$_mail_msg[$type]['subject'],
                    "content" =>sprintf(self::$_mail_msg[$type]['content'], $code))
            );

        } catch (Exception $e) {
            throw new BizException(ERROR_BIZ_PASSPORT_ERROR_SMS_SEND);
        }

        self::_delVerifyTimes($email, $type);
        self::_setSendTime($email, $type);

        return self::_getTimes($email, $type);
    }
    /* }}} */
    public static function removeCache($mobile, $type = "login")
    {
        /* {{{ */
        self::_delCode($mobile, $type);
        self::_delTimes($mobile, $type);
    }
    /* }}} */
    private static function _checkVerifyTimes($mobile, $type = "login")
    {
        /* {{{ */
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $key = self::_getVerifyTimesKey($mobile, $type);
        
        if (false !== ($times = $cache->incr($key))) {
            if ($times == 1) {
                $cache->expire($key, self::EXPIRE_CODE);
            }
            
            return $times < self::ALLOW_ERROR_TIMES;
        }
        
        if (false !== ($times = $cache->set($key, 1, self::EXPIRE_CODE))) {
            return true;
        }
        
        return false;
    }
    /* }}} */
    private static function _getCode($mobile, $type = "login")
    {
        /* {{{ */
        static $_code;
        
        if (! isset($_code[$type . "_" . $mobile])) {
            $cache = Cache::getInstance("REDIS_CONF_CACHE");
            $key = self::_getCodeKey($mobile, $type);
            
            $_code[$type . "_" . $mobile] = $cache->get($key);
        }
        
        return $_code[$type . "_" . $mobile];
    }
    /* }}} */
    private static function _delCode($mobile, $type = "login")
    {
        /* {{{ */
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $key = self::_getCodeKey($mobile, $type);
        
        return $cache->delete($key);
    }
    /* }}} */
    private static function _delTimes($mobile, $type = "login")
    {
        /* {{{ */
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $key = self::_getTimesKey($mobile, $type);
        
        return $cache->delete($key);
    }
    /* }}} */
    private static function _delVerifyTimes($mobile, $type = "login")
    {
        /* {{{ */
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $key = self::_getVerifyTimesKey($mobile, $type);
        
        return $cache->delete($key);
    }
    /* }}} */
    private static function _getTimes($mobile, $type = "login")
    {
        /*{{{*/
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $key   = self::_getTimesKey($mobile, $type);

        return (int)$cache->get($key);
    }/*}}}*/

    private static function _setSendTime($mobile, $type = "login")
    {
        /* {{{ */
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $key = self::_getSendTimeKey($mobile, $type);
        
        return $cache->set($key, $_SERVER["REQUEST_TIME"], self::INTERVAL_TIME);
    }
    /* }}} */
    private static function _getSendTime($mobile, $type = "login")
    {
        /* {{{ */
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $key = self::_getSendTimeKey($mobile, $type);
        
        return (int) $cache->get($key);
    }
    /* }}} */
    private static function _delSendTime($mobile, $type = "login")
    {
        /* {{{ */
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $key = self::_getSendTimeKey($mobile, $type);
        
        return (int) $cache->delete($key);
    }
    /* }}} */
    private static function _getCodeKey($mobile, $type = "login")
    {
        /* {{{ */
        return "captcha:" . $type . ":c:" . $mobile;
    }
    /* }}} */
    private static function _getTimesKey($mobile, $type = "login")
    {
        /* {{{ */
        return "captcha:" . $type . ":t:" . $mobile;
    }
    /* }}} */
    private static function _getVerifyTimesKey($mobile, $type = "login")
    {
        /* {{{ */
        return "captcha:" . $type . ":v:" . $mobile;
    }
    /* }}} */
    private static function _getSendTimeKey($mobile, $type = "login")
    {
        /* {{{ */
        return "captcha:" . $type . ":s:" . $mobile;
    }
    /* }}} */
    private static function _generate($mobile, $type = "login")
    {
        /* {{{ */
        $code = rand(100000, 999999);
        
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $cache->set(self::_getCodeKey($mobile, $type), $code . ":" . $_SERVER["REQUEST_TIME"], self::EXPIRE_CODE);
        
        return $code;
    } /* }}} */
}
