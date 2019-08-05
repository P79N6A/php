<?php
class Message
{

    protected $daoMessage;
    protected $receiver;

    public function __construct($userid)
    {

        Interceptor::ensureNotFalse(($userid > 0 && is_numeric($userid)), ERROR_PARAM_INVALID_FORMAT, 'receiver');

        $this->daoMessage = new DAOMessage($userid);
        $this->receiver = $userid;

    }

    public function getList($userid, $limit, $offset)
    {
        $result = $this->daoMessage->getList($userid, $limit, $offset);
        if (!empty($result['list'])) {
            foreach ($result['list'] as $key => $value)
            {
                $result['list'][$key]['show_time'] = Util::timeTransBefore($value['addtime']);
                $result['list'][$key]['name'] = ' 小葡萄';

                // 获取跳转地址
                $ext = '';
                if (!empty($value['ext'])) {
                    $ext = json_decode($value['ext'], true);
                }
                $result['list'][$key]['jump_url'] = self::getJumpUrlByType($value['type'], $ext);

//                if (DAOMessage::UNREAD == $value['read']) {
//                    $idArr[] = $value['id'];
//                }
            }
            $this->daoMessage->updateAllRead($userid);
            // 获取当前列表后，标记状态为已读
//            $this->updateRead($idArr);
        }

        return $result;
    }

    public static function getJumpUrlByType($type, $param=[])
    {
        if (empty($type)) {
            return '';
        }

//        TYPE_SELL_AUDIT_SUCCESS        = 30,
//        TYPE_SELL_SENT_OUT             = 31,
//        TYPE_SELL_RECIVE               = 32,
//
//        TYPE_INVITE                    = 11,
//        TYPE_TASK                      = 12,
//        TYPE_ORDER_NO_PAY		         = 13, //下单后取消支付
//        TYPE_ORDER_PAYED		         = 14, //用户支付成功
//        TYPE_ORDER_SEND                = 15, //订单发货
//        TYPE_ORDER_RECEIVE		     = 16, //确认收货
//        TYPE_ORDER_RELET		         = 17, //续期完成
//        TYPE_ORDER_RENT_SOON_FINISH    = 18, //距离完成3天时早晨8:00
//        TYPE_ORDER_RENT_FINISH         = 19, //发货后(传递)
//        TYPE_ORDER_RENT_FINISH_RECEIVE = 20, //新用户确认收货后(传递)

        $jumpUrlArr = [
            DAOMessage::TYPE_SELL_AUDIT_SUCCESS         => '/page/user/pages/myShare',
            DAOMessage::TYPE_SELL_SENT_OUT              => '/page/user/pages/myShare',
            DAOMessage::TYPE_SELL_RECIVE                => '/page/user/pages/myShare',

            DAOMessage::TYPE_INVITE                     => '/page/task/index',
            DAOMessage::TYPE_ORDER_NO_PAY               => '/page/user/pages/myBorrow',
            DAOMessage::TYPE_ORDER_PAYED                => '/page/user/pages/myBorrow',
            DAOMessage::TYPE_ORDER_SEND                 => '/page/user/pages/myBorrow',
            DAOMessage::TYPE_ORDER_RECEIVE              => '/page/user/pages/myBorrow',
            DAOMessage::TYPE_ORDER_RELET                => '/page/user/pages/myBorrow',
            DAOMessage::TYPE_ORDER_RENT_SOON_FINISH     => '/page/user/pages/myBorrow',

        	DAOMessage::TYPE_LOTTERY_TRAVEL_INVITER		=> '/page/user/pages/myTeamShare',
        	DAOMessage::TYPE_LOTTERY_TRAVEL_INVITEE		=> '/page/user/pages/myTeamShare',
        	DAOMessage::TYPE_LOTTERY_GROUP_SUCCESSR		=> '/page/user/pages/myTeamShare',
        	DAOMessage::TYPE_LOTTERY_GROUP_SUCCESSE		=> '/page/user/pages/myTeamShare',
        	DAOMessage::TYPE_LOTTERY_TRAVEL_WINNER		=> '/page/user/pages/myTeamShare',
        	DAOMessage::TYPE_SELL_AUDIT_REFUSE_ERROR	=> '/page/user/pages/myShare',

            DAOMessage::TYPE_SEIZING_SUCCESS	        => '/page/user/pages/myBuy',
        ];

        $confPage = isset($jumpUrlArr[$type]) ? $jumpUrlArr[$type] : '';
        if ($confPage && !empty($param)) {
            $confPage .= "?";
            foreach ($param as $key => $value)
            {
                $confPage.= $key."=".$value."&";
            }
        }

        return $confPage;
    }

    public function getUnReadTotal()
    {
        return $this->daoMessage->getUnReadTotal($this->receiver);
    }

    public function updateRead($idArr = [])
    {
        return $this->daoMessage->updateRead($this->receiver, $idArr);
    }

    public static function getContentByParam($type, $args=[])
    {
        $language_config = Context::getConfig("SYSTERM_COMMON_LANGUAGE");
        $message_content = $language_config['message']['china']['title'][$type];
        if (!empty($args)) {
            $message_content = vsprintf($message_content, $args);
        }
        return $message_content;
    }

    public static function getExtByType($type)
    {
        $jumpUrlArgs = [
            DAOMessage::TYPE_SELL_AUDIT_SUCCESS         => [
                'type'  => 'wait_sent'
            ],
            DAOMessage::TYPE_SELL_SENT_OUT              => [
                'type'  => 'sent'
            ],
            DAOMessage::TYPE_SELL_RECIVE                => [
                'type'  => 'finish'
            ],

            DAOMessage::TYPE_ORDER_RECEIVE              => [
                'type'  => 200
            ],
            DAOMessage::TYPE_ORDER_RELET                => [
                'type'  => 200
            ],
            DAOMessage::TYPE_ORDER_RENT_SOON_FINISH     => [
                'type'  => 300
            ],
        ];
        return isset($jumpUrlArgs[$type]) ? $jumpUrlArgs[$type] : '';
    }

    public function sendMessage($type, $args=[])
    {
        $content = self::getContentByParam($type, $args);
        $ext = self::getExtByType($type);
        return $this->daoMessage->add($type, $this->receiver, $content, $ext);
    }

}