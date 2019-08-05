<?php
class WxMessage
{
    // 下单并支付成功
    public static function paymentSuccess($uid, $option, $param, $args, $isBuy = 0)
    {
        $type   = WxProgram::TYPE_PAY_ORDER_SUCCESS;
        $status = "待收货";
        $date   = date('Y-m-d H:i:s');
        $languageConfig = Context::getConfig("SYSTERM_COMMON_LANGUAGE");
        $remark = $languageConfig['wx_program_template_msg'][$type]['remark'];
        $remark = vsprintf($remark, $args);
        $option = array($option['orderid'], $option['title'], $status, $date, $option['payment'], $option['address'], $remark);
        Logger::log("WxMessage", "paymentSuccess", array("uid"=>$uid,"type"=>$type, "option" => json_encode($option),"param" => json_encode($param),"args"=>json_encode($args), "line" => __LINE__));
        $wxProgram = new WxProgram();
        return $wxProgram->sendTemplateMessage($uid, $type, $option, $param, $isBuy);
    }
    
    // 下单成功并取消支付
    public static function cancelPaymentSuccess($uid, $option, $param, $args, $isBuy = 0)
    {
        $type   = WxProgram::TYPE_CANCEL_PAY_ORDER;
        $status = "待支付";
        $date   = date('Y-m-d H:i:s');
        $languageConfig = Context::getConfig("SYSTERM_COMMON_LANGUAGE");
        $remark = $languageConfig['wx_program_template_msg'][$type]['remark'];
        $remark = vsprintf($remark, $args);
        $option = array($option['orderid'], $option['title'], $status, $date, $option['payment'], $remark);
        Logger::log("WxMessage", "cancelPaymentSuccess", array("uid"=>$uid,"type"=>$type, "option" => json_encode($option),"param" => json_encode($param),"args"=>json_encode($args), "line" => __LINE__));
        $wxProgram = new WxProgram();
        return $wxProgram->sendTemplateMessage($uid, $type, $option, $param, $isBuy);
    }
    
    // 取消租赁订单成功
    public static function cancelOrderSuccess($uid, $option, $param, $args)
    {
        $type   = WxProgram::TYPE_CANCEL_ORDER_SUCCESS;
        $status = "已取消";
        $date   = date('Y-m-d H:i:s');
        $languageConfig = Context::getConfig("SYSTERM_COMMON_LANGUAGE");
        $remark = $languageConfig['wx_program_template_msg'][$type]['remark'];
        $remark = vsprintf($remark, $args);
        $option = array($option['orderid'], $option['title'], $status, $date, $option['payment'], $remark);
        Logger::log("WxMessage", "cancelOrderSuccess", array("uid"=>$uid,"type"=>$type, "option" => json_encode($option),"param" => json_encode($param),"args"=>json_encode($args), "line" => __LINE__));
        $wxProgram = new WxProgram();
        return $wxProgram->sendTemplateMessage($uid, $type, $option, $param);
    }
    
    // 取消购买订单成功
    public static function cancelBuySuccess($uid, $option, $param, $args)
    {
    	$type   = WxProgram::TYPE_CANCEL_BUY_ORDER_SUCCESS;
    	$status = "已取消";
    	$date   = date('Y-m-d H:i:s');
    	$languageConfig = Context::getConfig("SYSTERM_COMMON_LANGUAGE");
    	$remark = $languageConfig['wx_program_template_msg'][$type]['remark'];
    	$remark = vsprintf($remark, $args);
    	$option = array($option['orderid'], $option['title'], $status, $date, $option['payment'], $remark);
    	Logger::log("WxMessage", "cancelOrderSuccess", array("uid"=>$uid,"type"=>$type, "option" => json_encode($option),"param" => json_encode($param),"args"=>json_encode($args), "line" => __LINE__));
    	$wxProgram = new WxProgram();
    	return $wxProgram->sendTemplateMessage($uid, $type, $option, $param);
    }
    
    // 租用确认收货成功
    public static function receiveSuccess($uid, $option, $param, $args)
    {
        $type   = WxProgram::TYPE_CONFIRM_RECEIVE;
        $status = "租用中";
        $date   = date('Y-m-d H:i:s');
        $languageConfig = Context::getConfig("SYSTERM_COMMON_LANGUAGE");
        $remark = $languageConfig['wx_program_template_msg'][$type]['remark'];
        $remark = vsprintf($remark, $args);
        $option = array($option['orderid'], $option['title'], $status, $date, $option['payment'], $remark);
        Logger::log("WxMessage", "receiveSuccess", array("uid"=>$uid,"type"=>$type, "option" => json_encode($option),"param" => json_encode($param), "args"=>json_encode($args),"line" => __LINE__));
        $wxProgram = new WxProgram();
        return $wxProgram->sendTemplateMessage($uid, $type, $option, $param);
    }
    
    // 购买确认收货成功
    public static function receiveBuySuccess($uid, $option, $param, $args)
    {
    	$type   = WxProgram::TYPE_CONFIRM_BUY_RECEIVE;
    	$status = "已完成";
    	$date   = date('Y-m-d H:i:s');
    	$languageConfig = Context::getConfig("SYSTERM_COMMON_LANGUAGE");
    	$remark = $languageConfig['wx_program_template_msg'][$type]['remark'];
    	$remark = vsprintf($remark, $args);
    	$option = array($option['orderid'], $option['title'], $status, $date, $option['payment'], $remark);
    	Logger::log("WxMessage", "receiveBuySuccess", array("uid"=>$uid,"type"=>$type, "option" => json_encode($option),"param" => json_encode($param), "args"=>json_encode($args),"line" => __LINE__));
    	$wxProgram = new WxProgram();
    	return $wxProgram->sendTemplateMessage($uid, $type, $option, $param);
    }
    
    // 续期成功
    public static function reletSuccess($uid, $option, $param, $args)
    {
        $type   = WxProgram::TYPE_RENEWAL_SUCCESS;
        $status = "租用中";
        $date   = date('Y-m-d H:i:s');
        $languageConfig = Context::getConfig("SYSTERM_COMMON_LANGUAGE");
        $remark = $languageConfig['wx_program_template_msg'][$type]['remark'];
        $remark = vsprintf($remark, $args);
        $option = array($option['orderid'], $option['title'], $status, $date, $option['payment'], $remark);
        Logger::log("WxMessage", "reletSuccess", array("uid"=>$uid,"type"=>$type, "option" => json_encode($option),"param" => json_encode($param),"args"=>json_encode($args), "line" => __LINE__));
        $wxProgram = new WxProgram();
        return $wxProgram->sendTemplateMessage($uid, $type, $option, $param);
    }
}