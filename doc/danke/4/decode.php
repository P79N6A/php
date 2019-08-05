<?php

include_once("./code/BaseLogic.php");
include_once("./code/Optimus.php");
include_once("./code/ForgePublicId.php");
$public = new ForgePublicId(145033);

$encode_id = $public->publicId();

var_dump($encode_id);

$decode_id = \ForgePublicId::optimusDecode(1614564389);

var_dump($decode_id);exit;

/*
月租房源 取后台月租价，month_price
非月租房源 取后台月租价，price
判断是不是月租，房间关键词有”月租房源“或者，公寓强制月租suites表is_month字段值为”是"
*/

/*
//1.建立连接
    $connect=mysqli_connect('localhost','root','Xubaoguo_123','aa','3306');
//2.定义sql语句
    $sql='select * from aa';
    mysqli_query($connect,'set names utf8');
//3.发送SQL语句
    $result=mysqli_query($connect,$sql);
    $arr=array();//定义空数组
    while($row =mysqli_fetch_array($result)){
        //var_dump($row);
            //array_push(要存入的数组，要存的值)
        array_push($arr,$row);
    }
    var_dump($arr);
//4.关闭连接
   mysqli_close($connect);
   exit;
*/
set_time_limit(0);

$mysqli = new mysqli('127.0.0.1','root','Xubaoguo_123','aa','3306');
$sql = "select * from  ids ";
$mysqli -> query('set names utf8');




$f= fopen("./id2.txt","r");
$sql2 = "INSERT INTO ids (`public_id`,`room_id`) VALUES ";
$sql1 = '';
while (!feof($f))
{
  $line = fgets($f);
  $line = trim($line);
  $line = trim($line, "'");
  $line = trim($line, '"');
  $line = str_replace('页', '', $line);
  if (strpos($line, 'id') === false) {
  	$public_id = str_replace('id', '', $line);
  	//var_dump($public_id);
  	$room_id = \ForgePublicId::optimusDecode($public_id);
  	echo $public_id. "\t\t" . $room_id . "\n";
  	$sql1 .= " ({$public_id}, {$room_id}),";
  }

}
exit;
$sql = $sql2 . trim($sql1, ',');
//echo $sql;exit;
$res = $mysqli -> query($sql);
fclose($f);
$mysqli -> close();
exit;