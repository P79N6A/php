<?php

include_once("./code/BaseLogic.php");
include_once("./code/Optimus.php");
include_once("./code/ForgePublicId.php");


$f= fopen("./ddd.txt","r");

while (!feof($f))
{
  $line = fgets($f);
  $line = trim($line);
  $ara = explode(" ", $line);

  $a1 = trim($ara[0]);
	$a2 = trim($ara[1]);
  $a3 = trim($ara[2]);
  $a4 = trim($ara[3]);
  $a5 = trim($ara[4]);
  if ($a2 == 69328) {
  	//print_r($ara);exit;
  }


  if (empty($a4)) {
  	if (!empty($a5)) {
  		$a4 = $a5;
  	}

  }
  if (strpos($line, '神策') === false) {
  	$public_id = (new ForgePublicId($a2))->publicId();
  	echo $a1. "\t" . $a2 . "\t" . $a4 .  "\t" . $public_id . "\n";
  }

}
fclose($f);