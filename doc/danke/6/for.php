<?php

for ($i = 0; $i < 100;$i++)
{
	$sql = "ALTER  TABLE  `user_collect_{$i}`  modify column rent_type varchar(100) COMMENT '租房类型';";
	//$sql = "ALTER  TABLE  `room_views_{$i}`  modify column rent_type varchar(100) COMMENT '租房类型';";
	echo $sql;echo "\n";
}