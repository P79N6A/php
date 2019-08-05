<?php
class Orders extends Table
{

    const

        STATUS_DEFAULT = 100,   // 默认状态，待完成
        STATUS_SUCCESS = 200,   // 成功
        STATUS_FAIL    = 500,   // 失败
        STATUS_CANCEL  = 501,   // 取消

        end = '';

    protected $userid = 0;
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("orders");
        $this->setPrimary("orderid");

    }

    public static function statusData()
    {
    	return [0=> "待确认",1=> "已确认", 2 => "已取消"];
    }

    public static function payStatusData()
    {
    	return [0=> "未支付",1=> "支付准备", 2 => "支付成功"];
    }

    public static function expressStatusData()
    {
    	return [0=> "未发货",1=> "已发货", 2 => "已收货"];
    }

    public static function payMethodData()
    {
    	return [0=> "葡萄",1=> "葡萄+券", 2 => "葡萄+现金" , 3 => "葡萄+现金+券"];
    }

    public function getList($start, $limit, $status, $type = '1',$pay_status = '', $express_status = "", $orderid = 0, $create_start_time, $create_end_time, $pay_start_time, $pay_end_time)
    {

        $condition_ar[] = " 1=? ";
        $params[] = 1;

        if (!empty($status) && $status != '-1') {
            $condition_ar[] = " status = ? ";
            $params[] = $status;
        }

        if (!empty($orderid)) {
            $condition_ar[] = " orderid = ? ";
            $params[] = $orderid;
        }
        if (!empty($pay_status) && $pay_status != '-1') {
            $condition_ar[] = " pay_status = ? ";
            $params[] = $pay_status;
        }

        if (!empty($type) && $type != '-1') {
            $condition_ar[] = " type = ? ";
            $params[] = $type;
        }
        if (!empty($express_status) && $express_status != '-1') {
            $condition_ar[] = " express_status = ? ";
            $params[] = $express_status;
        }

        if ($create_start_time) {
        	$condition_ar[] = " addtime >= ?";
        	$params[] = $create_start_time . " 00:00:00";
        }

        if ($create_end_time) {
        	$condition_ar[] = " addtime < ?";
        	$params[] = $create_end_time. " 23:59:59";
        }

        if ($pay_start_time) {
        	$condition_ar[] = " pay_time >= ?";
        	$params[] = $pay_start_time. " 00:00:00";
        }

        if ($pay_end_time) {
        	$condition_ar[] = " pay_time < ?";
        	$params[] = $pay_end_time. " 23:59:59";
        }

        $condition = implode(" and ", $condition_ar);

        $sql = "select * from ".$this->getTableName()." where {$condition} order by orderid desc ";

        $sql.= $limit > 0 ? " limit $start, $limit" : "";
        $data = $this->getAll($sql, $params);

        $sql_count = "select count(*) from ".$this->getTableName()." where {$condition} order by orderid desc ";
        $total = $this->getOne($sql_count, $params);

        return array($data, $total);

    }

    public function getInfo($orderid)
    {
        $sql = "select * from ".$this->getTableName()." where orderid=?";

        return $this->getRow($sql, [$orderid]);
    }

    public static function getStatusData()
    {
        return [

            self::STATUS_DEFAULT => '待操作',
            self::STATUS_SUCCESS => '成功',
            self::STATUS_FAIL    => '失败',
            self::STATUS_CANCEL  => '取消'
        ];
    }


}