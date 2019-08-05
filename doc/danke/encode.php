<?php

include_once("./code/BaseLogic.php");
include_once("./code/Optimus.php");
include_once("./code/ForgePublicId.php");


$f= fopen("./dd.txt","r");

while (!feof($f))
{
  $line = fgets($f);
  $ara = explode("\t", $line);
  print_r($ara);exit;
  $line = trim($line);
  $line = trim($line, "'");
  $line = trim($line, '"');
  $line = str_replace('页', '', $line);
  if (strpos($line, '神策') === false) {
  	$public = new ForgePublicId(8807);
  	//echo $public_id. "\t\t" . $room_id . "\n";
  }

}
fclose($f);