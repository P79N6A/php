<?php

$a = "+主播Q252329看ÂVガ";
$a = '234567';
$a = preg_replace('/\s+/', '', $a);

$b = preg_replace('/(.)+\d{5,}/', "***", $a);

var_dump($b);

preg_match_all('/\d+/',$a, $arr);

print_r($arr);

$json = "{\"liveid\":11813611,\"uid\":26572984,\"title\":\"\\u6211\\u4e0d\\u7ba1\\u6211\\u6700\\u5e05\\u6211\\u662f\\u4f60\\u4eec\\u7684\\u5c0f\\u53ef\\u7231\",\"sn\":\"WS_1522216862_26572984_1527.f0e3\",\"partner\":\"ws\",\"point\":\"118.908415,42.271643\",\"province\":\"\\u5185\\u8499\\u53e4\\u81ea\\u6cbb\\u533a\",\"city\":\"\\u8d64\\u5cf0\",\"district\":\"\\u677e\\u5c71\\u533a\",\"location\":\"\\u5185\\u8499\\u53e4\\u81ea\\u6cbb\\u533a\\u8d64\\u5cf0\\u5e02\\u677e\\u5c71\\u533a\\u897f\\u7ad9\\u8def117\\u5385\\u9760\\u8fd1\\u8d64\\u5cf0\\u6c7d\\u8f66\\u7ad9\",\"width\":432,\"height\":768,\"status\":1,\"subtitle\":\" \",\"extends\":\"{\\\"network\\\":\\\"wifi\\\",\\\"brand\\\":\\\"OPPO\\\",\\\"model\\\":\\\"OPPO+R11s\\\",\\\"version\\\":\\\"3.0.6\\\",\\\"platform\\\":\\\"android\\\",\\\"deviceid\\\":\\\"5117ca9c7fea187085eca908ee16df03\\\",\\\"ip\\\":\\\"1.27.104.27\\\",\\\"position\\\":\\\"Y\\\"}\",\"addtime\":\"2018-03-28 14:01:35\",\"endtime\":\"0000-00-00 00:00:00\",\"modtime\":\"2018-03-28 14:54:56\",\"replayurl\":\"\",\"replay\":\"N\",\"virtual\":\"N\",\"region\":\"china\",\"cover\":\"\\/images\\/dc2dddfccd52428f043758702022ae44.jpg\",\"stime\":\"0000-00-00 00:00:00\",\"etime\":\"0000-00-00 00:00:00\",\"record\":\"N\",\"pullurl\":\"\",\"privacy\":\"\",\"author\":{\"uid\":26572984,\"vid\":0,\"nickname\":\"\\u4f60\\u53ef\\u7231\\u7684\\u4f73\\u7426\\ud83d\\udc95\",\"avatar\":\"http:\\/\\/static.tongyigg.com\\/images\\/dc2dddfccd52428f043758702022ae44.jpg\",\"signature\":\"\\u76f4\\u64ad\\u65f6\\u95f4\\u4e0a9-12\\u4e0b2-5q\\u7fa4\\u53f7733831657\",\"founder\":false,\"gender\":\"F\",\"birth\":\"1998-08-31\",\"location\":\"\\u5317\\u4eac\",\"region\":\"china\",\"verified\":false,\"verifiedinfo\":{\"credentials\":\"\",\"type\":0,\"realname\":\"\"},\"exp\":2551,\"level\":6,\"medal\":[],\"vs_school\":\"\",\"vip\":0,\"va_reason\":\"\",\"L2_cached\":true,\"anchor_token\":\"\",\"rideid\":0,\"rideurl\":\"\"}}";
$ap = json_decode($json,true);

print_r($ap);