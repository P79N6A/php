<?php

$total = 5;
$a = 3;

var_dump($total % $a);exit;

$a = '{"\u516c\u5171\u533a\u57df":["\u667a\u80fd\u9501"],"\u536b\u751f\u95f4":["\u6d17\u8863\u673a"],"\u53a8\u623f":["\u62bd\u6cb9\u70df\u673a","\u51b0\u7bb1","\u5fae\u6ce2\u7089"],"A":["\u5e8a","\u8863\u67dc","\u4e66\u684c","\u7a7a\u8c03","\u5e8a\u5355","\u6795\u82af","\u6795\u5957","\u5bb6\u5c45\u793c\u5305"]}';

$b = json_decode($a, true);

//print_r($b);


$c = '{"\u516c\u5171\u533a\u57df":["\u667a\u80fd\u9501"],"\u536b\u751f\u95f4":["\u6d17\u8863\u673a"],"\u53a8\u623f":["\u62bd\u6cb9\u70df\u673a","\u51b0\u7bb1","\u5fae\u6ce2\u7089"],"A":["\u5e8a","\u8863\u67dc","\u4e66\u684c","\u7a7a\u8c03","\u5e8a\u5355","\u6795\u82af","\u6795\u5957","\u5bb6\u5c45\u793c\u5305"]}';

$d = json_decode($c, true);

//print_r($d);

$b1 = json_decode($a1, true);
//print_r($b1);


$success = false;
$message = "ddd";


$aa = compact('success', 'message');

print_r($aa);