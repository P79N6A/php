<?php
//测试时文件的编码方式要是UTF8
$str='中文a字1符';
echo strlen($str)."\n";//14
echo mb_strlen($str,'utf8')."\n";//6
echo mb_strlen($str,'gbk')."\n";//8
echo mb_strlen($str,'gb2312')."\n";//10
/**
 * 结果分析：在strlen计算时，对待一个UTF8的中文字符是3个长度，所以“中文a字1符”长度是3*4+2=14,在mb_strlen计算时，选定内码为UTF8，则会将一个中文字符当作长度1来计算，所以“中文a字1符”长度是6
 */

echo (strlen($str) + mb_strlen($str,'UTF8')) / 2 . "\n";
//例如 “中文a字1符” 的strlen($str)值是14，mb_strlen($str)值是6，则可以计算出“中文a字1符”的占位是10.

echo mb_internal_encoding() . "\n";