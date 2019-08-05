<?php
//删除并替换数组中指定的元素
$a1=array("a"=>"red","b"=>"green","c"=>"blue","d"=>"yellow");
$a2=array("a"=>"purple","b"=>"orange");
//array_splice($a1,0,2,$a2);
print_r(array_splice($a1,0,2,$a2));
print_r($a1);