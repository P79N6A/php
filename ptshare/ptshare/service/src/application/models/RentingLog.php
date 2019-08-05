<?php
class RentingLog
{
    public static function addRentingLog($uid, $packageid, $sn, $extends){
        $DAORentingLog = new DAORentingLog();
        return $DAORentingLog->addRentingLog($uid, $packageid, $sn, $extends);
    }
}