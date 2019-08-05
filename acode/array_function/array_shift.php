<?php
//删除数组中的第一个元素（red），并返回被删除元素的值：
$a=array("a"=>"red","b"=>"green","c"=>"blue");
echo array_shift($a);
print_r ($a);