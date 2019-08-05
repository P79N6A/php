<?php
$ip = exec("ip a|grep inet|grep -v 127|grep -v 128|grep -v 64|awk '{print $2}'");
//
try 
{
    $re_data = getCurlData('https://buy.itunes.apple.com/verifyReceipt', '');
    $re_data_de = json_decode($re_data, true);
    if($re_data_de['status']) {
    } else {
        //发送机器人警告
        $url    = 'https://oapi.dingtalk.com/robot/send?access_token=37ccf087279cd9f53af3e2578494f467cfc8910035ce3ac9c190f3b6450a88f3';
        $post_data = array(
        'msgtype'   => 'text',
        'text'      => array(
        'content'   => "apple充值外网地址检测[https://buy.itunes.apple.com/verifyReceipt], 6秒超时, $ip"
        )
        );
        setPost($url, json_encode($post_data));
    }
} 
catch(Exception $e) 
{

}


function getCurlData($url, $xml, $useCert = false, $second = 6)
{
    $ch = curl_init();
    //设置超时
    curl_setopt($ch, CURLOPT_TIMEOUT, $second);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);//严格校验
    //设置header
    curl_setopt($ch, CURLOPT_HEADER, false);
    //要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
    //运行curl
    $data = curl_exec($ch);
    if ($data) {
        curl_close($ch);
        return $data;
    } else {
        $error = curl_errno($ch);
        curl_close($ch);
    }
}

function setPost($url,$post_string)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array ('Content-Type: application/json;charset=utf-8'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}


