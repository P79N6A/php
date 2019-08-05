<?php
//51796031604323
$a = [1,2,3,4,5];
$b = [6,7,8,9,10];

foreach ($a as $key => $value) {
	# code...
	foreach ($b as $k => $val) {
		# code...
		if ($val == 7) {
			//continue 2;
		}
		var_dump($val);
	}
	var_dump($value);
}

purchasing_tasks