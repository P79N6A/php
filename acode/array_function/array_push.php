<?php
//函数向第一个参数的数组尾部添加一个或多个元素（入栈），然后返回新数组的长度。
$a=array("red","green");
$b = array_push($a,"blue","yellow");
print_r($a);

var_dump($b);