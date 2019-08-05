<?php

class DAOCheckingOutbillAlipay extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("checking_outbill_alipay");
    }

    public function addBill(
        $trade_no, $out_trade_no, $business_type, $subject, $gmt_create, $gmt_close, $shop_id, $shop_name,
        $operating_staff, $terminal_id, $buyer_name, $total_amount, $receipt_amount, $coupon_amount, $point_amount, $discount_amount, $mdiscount_amount,
        $voucher_amount, $voucher_name, $seller_coupon_amount, $card_amount, $refund_out_request_no, $service, $fee_splitting, $memo, $addtime
    ) {
        $info = [
            'trade_no' => $trade_no,
            'out_trade_no' => $out_trade_no,
            'business_type' => $business_type,
            'subject' => $subject,
            'gmt_create' => $gmt_create,
            'gmt_close' => $gmt_close,
            'shop_id' => $shop_id,
            'shop_name' => $shop_name,
            // 
            'operating_staff' => $operating_staff,
            'terminal_id' => $terminal_id,
            'buyer_name' => $buyer_name,
            'total_amount' => $total_amount,
            'receipt_amount' => $receipt_amount,
            'coupon_amount' => $coupon_amount,
            'point_amount' => $point_amount,
            'discount_amount' => $discount_amount,
            'mdiscount_amount' => $mdiscount_amount,
            // 
            'voucher_amount' => $voucher_amount,
            'voucher_name' => $voucher_name,
            'seller_coupon_amount' => $seller_coupon_amount,
            'card_amount' => $card_amount,
            'refund_out_request_no' => $refund_out_request_no,
            'service' => $service,
            'fee_splitting' => $fee_splitting,
            'memo' => $memo,
            'addtime' => $addtime,
        ];

        return $this->insert($this->getTableName(), $info);
    }

}