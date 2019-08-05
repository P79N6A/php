<?php
$hour = date("H");
$hour = "13";
var_dump(intval($hour));
if ($hour > 0 && $hour < 6) {
	$array = ['上午','下午','晚上'];
} elseif ($hour > 11 && $hour < 13) {
	$array = ['下午','晚上'];
} elseif ($hour > 13 && $hour < 17) {
	$array = ['晚上'];
} else {
	$array = [];
}
var_dump($hour);

$a=array("red","green","blue","yellow","brown");
print_r(array_slice($a,0,2));

function aa($a,$b,$c) {
	$list = func_get_args();
	$str = implode(',', $list);
	var_dump($str);
}


aa(1,2,3);