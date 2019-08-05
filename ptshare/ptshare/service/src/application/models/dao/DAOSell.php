<?php
class DAOSell extends DAOProxy
{

    /**
     * 业务名称	    状态码
    待审核	        100
    待确认报价	    110
    待发出	        120
    已发出	        130
    成功	140
    线上审核失败	    151
    上门取件失败	    152
    线下审核失败	    153
    线下审核退回	    154
    发放奖励失败	    155	不存在该情况
    用户发布后取消	160
    上门取件前取消	161


     */
    const

        STATUS_WAIT_ONLINE_AUDIT    = 100,
//        STATUS_WAIT_CONFIRM         = 110,
        STATUS_WAIT_SENT            = 120,
        STATUS_SENT_OUT             = 130,

        STATUS_SUCCESS              = 140,

        STATUS_ONLINE_AUDIT_ERROR   = 151,
        STATUS_EXPRESS_PICK_ERROR   = 152,
        STATUS_OFFLINE_AUDIT_ERROR  = 153,
        STATUS_OFFLINE_BACK_ERROR   = 154,
        STATUS_AWARD_ERROR          = 155,

        STATUS_USER_AUDIT_CANCEL    = 160,
        STATUS_USER_EXPRESS_CANCEL  = 161,
        
        STATUS_COMPANY_BUYED		= 3,//买断
        
        SELL_TYPE_BUY				= 1,//出售
        SELL_TYPE_RENT				= 2,//出租


    END = '';

    // 待审核，200, 201
    // 待发出，202
    // 已发出，100
    // 已完成，101, 102, 103, 203

    public function __construct(){
        $this->setDBConf("MYSQL_CONF_MALL");
        $this->setTableName("sell");
        parent::__construct();
    }

    public static function getStatusData($status='')
    {

        $data = [
            self::STATUS_WAIT_ONLINE_AUDIT    => '待审核',
//            self::STATUS_WAIT_CONFIRM         => '待确认报价',
            self::STATUS_WAIT_SENT            => '待发出',
            self::STATUS_SENT_OUT             => '已发出',
            self::STATUS_SUCCESS              => '成功',
            self::STATUS_ONLINE_AUDIT_ERROR   => '线上审核失败',
            self::STATUS_EXPRESS_PICK_ERROR   => '上门取件失败',
            self::STATUS_OFFLINE_AUDIT_ERROR  => '线下审核失败',
            self::STATUS_OFFLINE_BACK_ERROR   => '线下审核退回',
            self::STATUS_AWARD_ERROR          => '发放奖励失败',
            self::STATUS_USER_AUDIT_CANCEL    => '用户发布后取消',
            self::STATUS_USER_EXPRESS_CANCEL  => '上门取件前取消',
        ];

        return (isset($data[$status]) && $status) ? $data[$status] : $data;

    }


    public function add($sellNum, $sn, $uid, $cover, $cover_type, $categoryid, $description, $num, $free, $contact, $extends, $cardid = 0, $grape_forward = 1, $sales_type = 0)
    {

        $data = [
            'sell_num'      => $sellNum,
            'sn'            => $sn,
            'uid'           => $uid,
            'cover'         => $cover,
            'cover_type'    => $cover_type,
            'categoryid'    => $categoryid,
            'description'   => $description,
            'num'           => $num,
            'free'          => $free,
            'contact'       => $contact,
            'extends'       => $extends,
            'status'        => self::STATUS_WAIT_ONLINE_AUDIT,
        	'cardid'		=> intval($cardid),
        	'grape_forward' => intval($grape_forward),
            'sales_type'    => intval($sales_type)
        ];

        $this->insert($this->getTableName(), $data);

        return $this->getInsertId();

    }

    public function getList($uid, $statusArr, $limit, $offset)
    {
        $statusArr = is_array($statusArr) ? $statusArr : [$statusArr];
        $statusArr = array_filter($statusArr);
        $status = implode(',', $statusArr);

        $countSql = "select count(*) as total from " . $this->getTableName(). " WHERE uid = ? AND status in ({$status})";
        $countResult = $this->getRow($countSql, [$uid]);
        $total = $countResult['total'];
        $list = [];
        $more = false;

        if ($total > 0){
            $selectSql = "select * from " . $this->getTableName(). "  WHERE uid = ? AND status in ({$status})";
            $offsetSql = '';
            if ($offset) {
                $offsetSql = " and id < ".$offset;
            }
            $orderSql = " order by id desc limit ?";
            $list = $this->getAll($selectSql.$offsetSql.$orderSql, [$uid, $limit]);
            $offset = end($list)['id'] ? end($list)['id'] : 0;
            if ($offset) {
                $offsetSql .= " and id <".$offset;
                $moreList = $this->getAll($selectSql.$offsetSql, [$uid, $limit]);
                $more = (bool)$moreList;
            }
        }

        return ['list' => $list, 'offset' => $offset, 'more' => $more];

    }

    /**
     * @param $id
     * @param $show_grape
     * @param $worth_grape
     * @return mixed
     * @desc 线上审核成功, 更新葡萄数，价值，变更状态
     */
    public function setOnlineAuditSuccess($id, $show_grape, $worth_grape)
    {

        $record = [
            'show_grape'    => $show_grape,
            'worth_grape'   => $worth_grape,
            'status'        => self::STATUS_WAIT_SENT,
        ];

        $condition = "id=? and status=?";

        $params = [$id, self::STATUS_WAIT_ONLINE_AUDIT];

        return $this->update($this->getTableName(), $record, $condition, $params);

    }


    public function setSellStatus($id, $status, $remark)
    {

        $record = [
            'status'        => $status,
        	"modtime"	=> date("Y-m-d H:i:s")
        ];

        if (!empty($remark)) {
            $record['remark'] = $remark;
        }

        $condition = "id = ? and status <> ?";

        $params = [$id, $status];

        return $this->update($this->getTableName(), $record, $condition, $params);

    }

    /**
     * @param $id
     * @param $userid
     * @return mixed
     * @desc 标记状态，调用一个方法，model 判断不同的参数
     */
    public function setStatus($id, $userid, $current_status, $target_status, $type = 1, $vip = 0)
    {

        $record = [
            'status'        => $target_status,
        ];
        
        if ($target_status == DAOSell::STATUS_WAIT_SENT) {
        	$record['type']	 	= $type;
        	$record['vip']  	= $vip;
        }
		
        $condition = "id=? and uid = ? and status=?";

        $params = [$id, $userid, $current_status];

        return $this->update($this->getTableName(), $record, $condition, $params);

    }

    public function getOneById($id)
    {
        $sql = "select * from ".$this->getTableName()." where id = ? ";
        return $this->getRow($sql, [$id]);
    }


    /**
     * @param $id
     * @param $data
     * @return mixed
     * @desc '待审核',
    '待确认报价',
    '待发出',
     * 以上状态下，才能修改地址
     */
    public function updateContact($id, $data)
    {
        $record = [
            'contact'        => json_encode($data),
        ];

        $allowStatus = [
            self::STATUS_WAIT_ONLINE_AUDIT,
//            self::STATUS_WAIT_CONFIRM,
            self::STATUS_WAIT_SENT,
        ];

        $allowStatus = implode(',', $allowStatus);

        $condition = "id=? and status in ({$allowStatus})";

        $params = [$id];

        return $this->update($this->getTableName(), $record, $condition, $params);
    }

    public function updateOrderId($id, $userid, $orderid)
    {
        $record = [
            'orderid'        => $orderid,
        ];
        $condition = "id = ? and uid = ?";
        $params = [$id, $userid];
        return $this->update($this->getTableName(), $record, $condition, $params);


    }

    public function existUidSellid($uid, $sellid)
    {
        $sql = " select count(0) as cnt from ".$this->getTableName()." where id=? and uid=?";

        return $this->getOne($sql, [$sellid, $uid]) > 0;
    }

    public function getLastSellNum($uid)
    {
        $sql = " select num from ".$this->getTableName()." where uid=? order by id desc limit 1";

        return $this->getOne($sql, [$uid]);
    }

}