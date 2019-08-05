<?php

include_once("./code/BaseLogic.php");
include_once("./code/Optimus.php");
include_once("./code/ForgePublicId.php");


$f= fopen("./id3.txt","r");

while (!feof($f))
{
  $line = fgets($f);
  $line = trim($line);

  if (strpos($line, '房源') === false) {
  	$public_id = (new ForgePublicId($line))->publicId();
  	echo $line. "\t" . $public_id . "\n";
  }

}
fclose($f);