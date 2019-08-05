<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 2018/5/10
 * Time: 下午5:03
 */

class DAOMessage extends DAOProxy
{

    public function __construct($userid)
    {
        $this->setDBConf("MYSQL_CONF_MESSAGE");
        $this->setShardId($userid);
        $this->setTableName("message");
    }

    const
        TYPE_SELL_AUDIT_SUCCESS        = 30,
        TYPE_SELL_SENT_OUT             = 31,
        TYPE_SELL_RECIVE               = 32,

        TYPE_INVITE                    = 11,
        TYPE_TASK                      = 12,
        TYPE_ORDER_NO_PAY		       = 13, //下单后取消支付
        TYPE_ORDER_PAYED		       = 14, //用户支付成功
        TYPE_ORDER_SEND                = 15, //订单发货
        TYPE_ORDER_RECEIVE		       = 16, //确认收货
        TYPE_ORDER_RELET		       = 17, //续期完成
        TYPE_ORDER_RENT_SOON_FINISH    = 18, //距离完成3天时早晨8:00
        TYPE_ORDER_RENT_FINISH         = 19, //发货后(传递)
        TYPE_ORDER_RENT_FINISH_RECEIVE = 20, //新用户确认收货后(传递)
        TYPE_LOTTERY_PAY               = 33, //彩票发奖
        
        TYPE_LOTTERY_TRAVEL_INVITER    = 34, //【活动有人参与，发给邀请者】
        TYPE_LOTTERY_TRAVEL_INVITEE    = 35, //【活动有人参与，发给参与者】
        TYPE_LOTTERY_GROUP_SUCCESSR    = 36, //【活动已成团，发给邀请者】
        TYPE_LOTTERY_GROUP_SUCCESSE    = 37, //【活动已成团，发给参与者】
        TYPE_LOTTERY_TRAVEL_WINNER	   = 38, //【活动结束，发给所有参与者，已中奖】
        
        TYPE_SELL_AUDIT_REFUSE_ERROR   = 39, //线上审核失败
        
        TYPE_SEIZING_SUCCESS           = 40, //夺宝成功
        

        READ                           = 1, // 已读
        UNREAD                         = 0, // 未读

    end = '';

    public function add($type, $receiver, $content, $ext=[])
    {

        $data = [
            'type'      => $type,
            'receiver'  => $receiver,
            'content'   => $content,
        ];
        if (!empty($ext)) {
            $data['ext'] = json_encode($ext);
        }

        $this->insert($this->getTableName(), $data);

        return $this->getInsertId();

    }

    public function getList($receiver, $limit, $offset)
    {
        $countSql = "select count(*) as total from " . $this->getTableName(). " WHERE receiver = ?";
        $countResult = $this->getRow($countSql, [$receiver]);
        $total = $countResult['total'];
        $list = [];
        $more = false;

        if ($total > 0){
            $selectSql = "select * from " . $this->getTableName(). " WHERE receiver = ?";
            $offsetSql = '';
            if ($offset) {
                $offsetSql = " and id <".$offset;
            }
            $orderSql = " order by id desc limit ?";
            $list = $this->getAll($selectSql.$offsetSql.$orderSql, [$receiver, $limit]);
            $offset = end($list)['id'] ? end($list)['id'] : 0;
            if ($offset) {
                $offsetSql .= " and id <".$offset;
                $moreList = $this->getAll($selectSql.$offsetSql, [$receiver, $limit]);
                $more = (bool)$moreList;
            }
        }

        return ['list' => $list, 'offset' => $offset, 'more' => $more];
    }

    // 获取未读信息个数
    public function getUnReadTotal($receiver)
    {
        $countSql = "select count(*) as total from " . $this->getTableName(). " WHERE receiver = ? and `read` = ?";
        $countResult = $this->getRow($countSql, [$receiver, self::UNREAD]);
        return max($countResult['total'], 0);
    }

    // 全部标记为已读
    public function updateRead($receiver, $idArr=[])
    {
        if (empty($idArr)) {
            return false;
        }
        $ids = implode(',', $idArr);

        $record = [
            '`read`'  => self::READ,
        ];

        $condition = "receiver = ? and id in ({$ids}) and `read` = ? ";

        $params = [$receiver, self::UNREAD];

        return $this->update($this->getTableName(), $record, $condition, $params);

    }

    public function updateAllRead($receiver)
    {
        $record = [
            '`read`'  => self::READ,
        ];

        $condition = "receiver = ? ";

        $params = [$receiver];

        return $this->update($this->getTableName(), $record, $condition, $params);
    }




}