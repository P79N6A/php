<?php

/**
 * 取消订单返回的数据类型
 *
 * @author auto create
 */
class Gamezctopcancel
{
    
    /**
     * 
     * 合作商的订单号
     **/
    public $coop_order_no;
    
    /**
     * 
     * 商品信息快照
     **/
    public $coop_order_snap;
    
    /**
     * 
     * 合作商的订单状态
     **/
    public $coop_order_status;
    
    /**
     * 
     * 充值成功时间
     **/
    public $coop_order_success_time;
    
    /**
     * 
     * 失败代码
     **/
    public $failed_code;
    
    /**
     * 
     * 失败原因
     **/
    public $failed_reason;
    
    /**
     * 
     * 淘宝订单号
     **/
    public $tb_order_no;    
}
?>