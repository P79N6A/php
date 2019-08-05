<?php
class Sell extends Table
{

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

        END = '';

    // 待审核，200, 201
    // 待发出，202
    // 已发出，100
    // 已完成，101, 102, 103, 203


    public static function getStatusData($status='')
    {

        $data = [
            self::STATUS_WAIT_ONLINE_AUDIT    => '待审核',
//            self::STATUS_WAIT_CONFIRM         => '待确认报价',
//            self::STATUS_WAIT_SENT            => '待发出',
            self::STATUS_WAIT_SENT            => '审核通过，待发出',
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


    public function __construct()
    {
        parent::__construct();
        $this->setDBConf("MYSQL_CONF_MALL");
        $this->setTableName("sell");
        $this->setPrimary("id");
    }

    public function getSellList($id, $uid, $status, $create_start_time, $create_end_time, $page, $limit, $sell_num, $sn, $orderid, $sales_type)
    {

        $page = max($page - 1, 0);

        $where = ' 1 = ?';
        $params[] = 1;

        if ($id > 0) {
            $where .= " and id = ?";
            $params[] = $id;
        }

        if ($uid > 0) {
            $where .= " and uid = ?";
            $params[] = $uid;
        }

        if ($sales_type != '-1') {
        	$where .= " and sales_type = ?";
        	$params[] = $sales_type;
        }

        if ($status > 0) {
            $where .= " and status = ?";
            $params[] = $status;
        }

        if ($create_start_time) {
            $where .= " and addtime >= ?";
            $params[] = $create_start_time;
        }

        if ($create_end_time) {
            $where .= " and addtime < ?";
            $params[] = $create_end_time;
        }

        if ($orderid) {
        	$where .= " and orderid = ?";
        	$params[] = $orderid;
        }

        if ($sn) {
        	$where .= " and sn = ?";
        	$params[] = $sn;
        }

        if ($sell_num) {
        	$where .= " and sell_num = ?";
        	$params[] = $sell_num;
        }

        $result = $this->getRecords($where, $params, $page * $limit, $limit);

        $total = $result[0];

        $sellList = $result[1];

        if( !empty($sellList) ) {

            $uidArray = Util::arrayToIds($sellList, 'uid');

            $modelUser = new User();

            $userList = $modelUser->getListByUids($uidArray);

            if (!empty($userList[1])) {

                $userList = Util::arrayToKey($userList[1], 'uid');

                foreach ($sellList as $key => $val)
                {

                    if (isset($userList[$val['uid']])) {

                        $sellList[$key]['phone'] = $userList[$val['uid']]['phone'];

                        $sellList[$key]['nickname'] = $userList[$val['uid']]['nickname'];

                    }

                }

            }


        }

        return ['total' => $total, 'list' => $sellList];

    }

    public function getOneById($id)
    {
        $result = $this->getRecord($id);
        if ($result) {
            $result['cover'] = Util::joinStaticDomain($result['cover']);
        }
        return $result;
    }






}