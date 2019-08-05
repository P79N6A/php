<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/31
 * Time: 17:51
 */
class WxMiniProgram
{
    //const APP_ID='wxdf04fc4469c6be6d';
    const APP_ID='wx25be9fe8117439d5';
    //const APP_SECRET="21a51b6a3bf39c6c931af3300338d587";
    //const APP_SECRET="970bebdf87871ff6b1f99a47abab24d5";
    const APP_SECRET="72d27e36cbf105e85fb95bca83e663c7";
    const OPENID_URL="https://api.weixin.qq.com/sns/jscode2session";

    const TOKEN_URL = "https://api.weixin.qq.com/cgi-bin/token";//获取access_token
    const NOTICE_URL="https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send";//小程序模板通知
    const ACODE_URL_A="https://api.weixin.qq.com/wxa/getwxacode?access_token=";
    const ACODE_URL_B="https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=";
    const ACODE_URL_C="https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token=";


    public static function getAcode($xuan)
    {
        $access_token=self::getAccessToken();
        $data=[
            'path'=>'components/index/index?xuan='.$xuan,
            'width'=>430,
        ];
        return self::post(self::ACODE_URL_C.$access_token, $data);
    }

    public static function getOpenId($code)
    {
        $params=array(
            'appid'=>self::APP_ID,
            'secret'=>self::APP_SECRET,
            'js_code'=>$code,
            'grant_type'=>'authorization_code',
        );
        $res=self::get(self::OPENID_URL, $params);
        $r=json_decode($res, true);
        //Util::log('kkk',array($r) ,'ooo');
        if (!$r) { throw new Exception("获取openid失败");
        }
        if(isset($r['errcode'])&&$r['errcode']!=0) { throw new Exception("errcode=".$r['errcode']." errmsg=".$r['errmsg']);
        }
        return $r;
    }

    public static function decryptData($miData,$iv,$sessionKey)
    {
        try{
            $pc = new WXBizDataCrypt(self::APP_ID, $sessionKey);
            $mingData=array();
            $errCode = $pc->decryptData($miData, $iv, $mingData);
            //Util::log("kkk4",array('errCode'=>$errCode,'sskey'=>$sessionKey,'iv'=>$iv,'mi'=>$miData,'ming'=>$mingData,'appid'=>self::APP_ID),'ddd6');
            if ($errCode==0) {
                return json_decode($mingData, true);
            }
        }catch (Exception $e){

        }

        return array();
    }


    /**
     * 获取ACCESS_TOKEN
     *
     * @return mixed
     * @throws Exception
     * @throws OauthException
     */
    public static function getAccessToken()
    {
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $key = 'get_wx_access_token';
        $access_token = $cache->get($key);
        if(!$access_token) {
            $params=array(
                'appid'=>self::APP_ID,
                'secret'=>self::APP_SECRET,
                'grant_type'=>'client_credential',
            );
            $res = self::get(self::TOKEN_URL, $params);
            $r = json_decode($res, true);
            if (!$r) { throw new Exception("获取access_token失败");
            }
            if(isset($r['errcode'])&&$r['errcode']!=0) {
                throw new Exception("errcode=".$r['errcode']." errmsg=".$r['errmsg']);
            }else{
                $access_token = $r['access_token'];
                $expires_in = $r['expires_in'];
                $cache->set($key, $access_token, 180);
            }
            return $access_token;
        }else{
            return $access_token;
        }
    }

    /**
     * 推送小程序订阅通知
     *
     * @return mixed
     * @throws Exception
     * @throws OauthException
     */
    public static function sendNotice($data)
    {
        $access_token = self::getAccessToken();
        $openid = $data['openid'];
        $liveid = $data['liveid'];
        $formid = $data['formid'];
        $keywords['keyword1']['value'] = $data['keyword1'];
        $keywords['keyword2']['value'] = $data['keyword2'];
        $keywords['keyword3']['value'] = $data['keyword3'];
        //$template_id = 'j5DfRyaxX3J8GxpzI6rBoLgLoI3bfgPmDK9qdFr6BPs';//模板ID
        $template_id = 'a_fMziEL2Mjcs8RW2O8q2fGh0tJG5jMYrvrj5KN5kTo';//模板ID
        $params=array(
            'touser'=>$openid,
            'template_id'=>$template_id,
            'page'=>'components/index/index?type=live&liveid='.$liveid,
            'form_id' => $formid,
            'data' => $keywords,
            'emphasis_keyword' => "keyword1.DATA"
        );
        $url = self::NOTICE_URL.'?access_token='.$access_token;
        $res = self::post($url, $params);
        $r = json_decode($res, true);
        //if (!$r)throw new Exception("推送通知失败");
        //if(isset($r['errcode'])&&$r['errcode']!=0)throw new Exception("errcode=".$r['errcode']." errmsg=".$r['errmsg']);
        return $r;
    }


    private static  function get($url, $params = array())
    {
        /* {{{ */
        //$url = $this->_baseurl . "/" . ($params ? ($url . "?" . http_build_query($params)) : $url);
        $url.='?'.http_build_query($params);
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

    /* PHP CURL HTTPS POST */
    private static function post($url,$data)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $tmpInfo = curl_exec($curl);
        if (curl_errno($curl)) {
            echo 'Errno'.curl_error($curl);
        }
        curl_close($curl);
        return $tmpInfo;
    }

}