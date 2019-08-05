<?php

function bin2dec($bin){
    $temp = strrev($bin);
    $result = 0;
    for ($i=0,$len = strlen($temp); $i < $len; $i++) {

        $result += pow(2,$i) * $temp[$i];

    }
    return $result;
}

$a = '10100010';
echo bin2dec($a) . "\n";//结果162