<?php
//在数组开头插入一个或多个元素。
$a=array("a"=>"red","b"=>"green");
array_unshift($a,"blue", 'apple');
print_r($a);