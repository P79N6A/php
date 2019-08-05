<?php

function yuesefu($n, $m) {
    $r = 0;
    for($i = 2; $i <= $n; $i++) {
        $r = ($r + $m) % $i;
        var_dump($i .'--'.$r);
    }

    var_dump($i);
    return $r + 1;
}
var_dump(yuesefu(10, 3) . "是猴王");

function killMonkey($monkeys, $m, $current = 0) {
    $number = count($monkeys);
    $num = 1;
    if (count($monkeys) == 1) {
        var_dump($monkeys[0]."成为猴王了");
        return;
    } else {
        while ($num++ < $m) {
            $current++ ;
            var_dump($num . "-" . $current . "-" . $m . "num is:" .$number);
            $current = $current%$number;
        }
        $last = count($monkeys);
        var_dump($monkeys[$current]."的猴子被踢掉了 {$last} {$current}");
        array_splice($monkeys, $current, 1);
        killMonkey($monkeys, $m, $current);
    }
}
$monkeys = range(1, 41);
//var_dump($monkeys);
$m = 3; //数到第几只猴子被踢出
killMonkey($monkeys, $m);


foreach ($monkeys as $key => $value) {
	$key++;
    $number = count($monkeys);
	var_dump($key % $number);
}