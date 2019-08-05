<?php
class SMS
{
    const URL = 'https://api.netease.im/';

    const APP_KEY = 'd8f7866e5f3d8177d9d083b757399fd1';

    const APP_SECRET = 'd33b409612d0';

    const YUNPIAN_APP_KEY = 'd071488cb16016591c4b32b08cd3e4ba';


    public static function send($phone, $code, $templateid)
    {
        /* {{{ 网易云信 发送模板短信*/
        $start_time = microtime(true);

        $actionUrl = self::URL. 'sms/sendtemplate.action';
        $Nonce = mt_rand();
        $CurTime = time();
        $head_arr = array(
            'Content-Type:application/x-www-form-urlencoded; charset=utf-8',
            'AppKey:'. self::APP_KEY,
            'Nonce:'. $Nonce,
            'CurTime:'. $CurTime,
            'CheckSum:'. strtolower(sha1(self::APP_SECRET.$Nonce.$CurTime))
        );

        $data = array(
            'templateid' => $templateid,
            'params' => json_encode(array($code)),
            'mobiles' => json_encode(array($phone)),
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, true);
        curl_setopt($ch, CURLOPT_URL, $actionUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $head_arr);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

         $content = curl_exec($ch);
         $errno = curl_errno($ch);
         $error = curl_error($ch);
         $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

         curl_close($ch);

        Logger::log('sms', 'result:'.$status.$content, array('params'=>http_build_query($data), 'consume'=>round((microtime(true) - $start_time) * 1000)));

        if((false === $content) || (0 !== $errno)) {
            throw new Exception($error, -1);
        }

        if($status != 200) {
            throw new Exception("http request fail, status:$status", $status);
        }

         $result = json_decode($content, true);

        if($result["code"] != 200) {
            throw new Exception($result["msg"], $result["code"]);
        }
        return true;
    } /* }}} */
    public static function sendAbroad($phone, $code, $text)
    {
        $data=array('text'=>sprintf($text, $code),'apikey'=>self::YUNPIAN_APP_KEY,'mobile'=>$phone);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept:text/plain;charset=utf-8', 'Content-Type:application/x-www-form-urlencoded','charset=utf-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, 'https://sms.yunpian.com/v2/sms/single_send.json');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

        $json_data = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if($status != 200) {
            $result = json_decode($json_data, true);
            Logger::log('sms', 'result:'.$result["code"].$result["msg"], array($phone, $text, $result));
            throw new Exception();
        }
        return true;
    }
}
