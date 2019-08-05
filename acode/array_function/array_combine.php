<?php

//通过合并两个数组来创建一个新数组。
$fname=array("Bill","Steve","Mark");
$age=array("60","56","31");

$c=array_combine($fname,$age);
print_r($c);