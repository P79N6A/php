<?php

class DepositHelper
{
    const DEPOSIT_WECHAT = "wechat";
    const DEPOSIT_ALIPAY = "alipay";
    const DEPOSIT_PAYPAL = "paypal";
    const DESOSIT_GOOGLE = "google";
    const DEPOSIT_APPLE = "apple";//苹果支付
    const DEPOSIT_MYCARD = "mycard";

    public static function getClient($source)
    {
        Interceptor::ensureNotEmpty(in_array($source, [self::DEPOSIT_WECHAT, self::DEPOSIT_ALIPAY, self::DESOSIT_GOOGLE, self::DEPOSIT_PAYPAL, self::DEPOSIT_APPLE, self::DEPOSIT_MYCARD]), ERROR_BIZ_PAYMENT_DEPOSIT_SOURCE_ERROR, $source);

        $driver = ucfirst($source) . 'Pay';
        if (class_exists($driver)) {
            return new $driver();
        } else {

        }
    }

    public static function bill($source, $bill_date)
    {
        return self::getClient($source)->bill($bill_date);
    }
}
