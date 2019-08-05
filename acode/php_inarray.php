<?php
$a = 9;
echo count($a);

$post_dirty_id = '1092 ORDER BY #1';
$safe_arr = [
  987 => '小明',
  1092 => '汤姆',
  1256 => '奥立升'
];
$array_key = array_keys($safe_arr);
print_r($array_key);
var_dump($post_dirty_id);
if(in_array($post_dirty_id, $array_key, true)) {
  echo 'find me';
} else {
  echo 'do not find me';
}
echo "\n";

$a = ['a', 32, true, 'x' => 'y'];
var_dump(in_array(25, $a)); // true, one would expect false
var_dump(in_array('ggg', $a)); // true, one would expect false
var_dump(in_array(0, $a)); // true
var_dump(in_array(null, $a)); // false