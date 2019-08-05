<?php

function print_yanghui_sanjiao($rowline)
{
	//每行的第一个和最后一个都为1，写了6行
	for($i=0; $i<$rowline; $i++) {
	  	$a[$i][0]	=	1;
		$a[$i][$i]	=	1;
	}
	//print_r($a);
	//出除了第一位和最后一位的值，保存在数组中
	for($i=2; $i<$rowline; $i++) {
		for($j=1; $j<$i; $j++) {
	   		$a[$i][$j] = $a[$i-1][$j-1] + $a[$i-1][$j];
	  	}
	}

	//print_r($a);
	//打印
	for($i=0; $i<$rowline; $i++) {
	  	for($j=0; $j<=$i; $j++) {
	  		echo $a[$i][$j].' ';
	  	}
	  	echo "\n";
	}
}

print_yanghui_sanjiao(6);