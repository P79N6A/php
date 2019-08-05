<?php
class ExpressSdk
{

    public static function getClient($source, $company = "", $number = "")
    { 
        static $clientObj = array();

        $key = $source. "-". $company. "-". $number;

        if (! isset($clientObj[$key])) {
            $c = ucfirst($source);
            include_once dirname(__FILE__).DIRECTORY_SEPARATOR. "express_sdk". DIRECTORY_SEPARATOR. "$c.class.php";
            if (! class_exists($c)) {
                throw new ExpressException("The class you called is not exist!");
            }

            $clientObj[$key] = new $c($company, $number);
        }

        return $clientObj[$key];
    }

    public static function poll($source, $company, $number)
    { 
        return self::getClient($source, $company, $number)->poll();
    }

    public static function query($source, $company, $number)
    {
        return self::getClient($source, $company, $number)->query();
    }

    public static function eorder($source, $company, $orderid, $recName, $recPrintAddr, $recMobile, $recTel, $sendName, $sendPrintAddr, $sendMobile, $sendTel, $weight, $count, $volume, $desp, $remark)
    { 
        return self::getClient($source, $company)->eorder($orderid, $recName, $recPrintAddr, $recMobile, $recTel, $sendName, $sendPrintAddr, $sendMobile, $sendTel, $weight, $count, $volume, $desp, $remark);
    }
    
    public static function pickuporder($source, $company, $orderid, $recName, $recPrintAddr, $recTel, $sendName, $sendPrintAddr, $sendTel, $weight, $count, $volume, $productName, $desp, $remark)
    { 
        return self::getClient($source, $company)->pickuporder($orderid, $recName, $recPrintAddr, $recTel, $sendName, $sendPrintAddr, $sendTel, $weight, $count, $volume, $productName, $desp, $remark);
    }

    public static function notify($source)
    {
        return self::getClient($source)->notify();
    }

}

class ExpressException extends Exception
{
}
