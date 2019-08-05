<?php

class SendMobileCode {
    /**
     * 验证验证码是否正确
     * @param $mobile
     * @param $code
     * @return bool
     */
    public function verifyCode($mobile, $code)
    {
        echo __METHOD__;
    }
}

$a = new SendMobileCode();

var_dump($a->verifyCode('1', '2'));

//Firewall:SendMobileCode::verifyCodemobile:15172381977