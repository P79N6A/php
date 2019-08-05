<?php
/**
 * 区别如下：

 当下标为数值时，array_merge()不会覆盖掉原来的值，但array＋array合并数组则会把最先出现的值作为最终结果返回，而把后面的数组拥有相同键名的那些值“抛弃”掉（不是覆盖）.

当下标为字符时，array＋array仍然把最先出现的值作为最终结果返回，而把后面的数组拥有相同键名的那些值“抛弃”掉，但array_merge()此时会覆盖掉前面相同键名的值.
 * @var [type]
 */
$arr1 = ['PHP', 'apache'];
$arr2 = ['PHP', 'MySQl', 'HTML', 'CSS'];
$mergeArr = array_merge($arr1, $arr2);
$plusArr = $arr1 + $arr2;
// print_r($mergeArr);
// print_r($plusArr);
// exit;
$arr1 = ['PHP', 'a'=>'MySQl'];
$arr2 = ['PHP', 'MySQl', 'a'=>'HTML', 'CSS'];
$mergeArr = array_merge($arr1, $arr2);
$plusArr = $arr1 + $arr2;
print_r($mergeArr);
print_r($plusArr);
exit;
$arr1 = ['PHP', 'a'=>'MySQl','6'=>'CSS'];
$arr2 = ['PHP', 'MySQl', 'a'=>'HTML', 'CSS'];
$mergeArr = array_merge($arr1, $arr2);
$plusArr = $arr1 + $arr2;
print_r($mergeArr);
print_r($plusArr);