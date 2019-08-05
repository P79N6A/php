<?php

//例举一个URL格式的字符串:
$str = 'http://test.com/testdir/index.php?param1=10&param2=20&param3=30&param4=40&param5=50&param6=60';

//1.0 用parse_url解析URL,此处是$str
$arr = parse_url($str);
var_dump($arr);


