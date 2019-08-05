<?php

class Packet
{
    private $expire = 24 * 60 * 60;

    public function sendSinglePacket($receiver, $amount, $remark, $liveid)
    {
        $userid = Context::get('userid');
        $orderid = Account::getOrderId($userid);

        // 账户冻结
        $dao_profile = new DAOProfile($userid);
        $profiles = $dao_profile->getUserProfiles();
        Interceptor::ensureNotFalse((!(isset($profiles['frozen']) && $profiles['frozen'] == 'Y')), ERROR_USER_FROZEN, "uid");

        $diamond = (int)Account::getBalance($userid, ACCOUNT::CURRENCY_DIAMOND);
        Interceptor::ensureNotFalse($diamond >= $amount, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);

        $userinfo = User::getUserInfo($receiver);
        Interceptor::ensureNotFalse(isset($userinfo['uid']), ERROR_USER_NOT_EXIST);

        $dao_packet = new DAOPacket();
        $dao_packet_receive = new DAOPacketReceive();

        try {
            $dao_packet->startTrans();
            $packetid = $dao_packet->add($userid, 1, $amount, 1, 0, $remark, $liveid, '', 1, 'N', date('Y-m-d H:i:s'));
            $dao_packet_receive->add($packetid, $receiver, explode(',', $amount), $liveid, 1, 1, 1);

            $message = "[普通红包][packetid:{$packetid}][发:{$userid}][收:{$receiver}]";
            Account::decrease($userid, Account::TRADE_TYPE_RED_PACKET, $orderid, $amount, Account::CURRENCY_DIAMOND, $message);
            Account::increase($receiver, Account::TRADE_TYPE_RED_PACKET, $orderid, $amount, Account::CURRENCY_DIAMOND, $message);

            $dao_packet->commit();

        } catch (Exception $e) {
            $dao_packet->rollback();
            throw new BizException(ERROR_BIZ_PACKET_SEND_ERROR, $e->getMessage());
        }

        return $packetid;
    }

    public function sendGroupPacket($amount, $num, $remark, $liveid)
    {
        $userid = Context::get('userid');
        $orderid = Account::getOrderId($userid);

        // 账户冻结
        $dao_profile = new DAOProfile($userid);
        $profiles = $dao_profile->getUserProfiles();
        Interceptor::ensureNotFalse((!(isset($profiles['frozen']) && $profiles['frozen'] == 'Y')), ERROR_USER_FROZEN, "uid");

        $diamond = Account::getBalance($userid, ACCOUNT::CURRENCY_DIAMOND);
        Interceptor::ensureNotFalse($diamond >= $amount, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);

        $dao_packet = new DAOPacket();
        $dao_packet_receive = new DAOPacketReceive();
        $extends = $this->getBonus($amount, $num, ceil($amount / $num) + 1, 1);

        try {
            $dao_packet->startTrans();
            $packetid = $dao_packet->add($userid, 2, $amount, $num, 0, $remark, $liveid, json_encode($extends), 0, 'Y', date('Y-m-d H:i:s'));
            $dao_packet_receive->add($packetid, 0, $extends, $liveid, 0, $num);

            $receive_data = $dao_packet_receive->getReceiveInfoByPackageid($packetid, 0);
            $this->createOpenCache($packetid, $receive_data);

            // 对比redis 数据库信息
            if (!$this->checkCreateOpenCache($packetid, $num, $receive_data)) {
                throw new Exception('红包发送失败');
            }

            $message = "[群红包][发放][packetid:{$packetid}][senduid:{$userid}][金额:{$amount}][个数:{$num}]";
            $journal_extends = ['type' => 'send'];
            Account::decrease($userid, Account::TRADE_TYPE_RED_PACKET, $orderid, $amount, Account::CURRENCY_DIAMOND, $message, $journal_extends);
            $uid_system = $this->getPacketAccount($packetid);
            Account::increase($uid_system, Account::TRADE_TYPE_RED_PACKET, $orderid, $amount, Account::CURRENCY_DIAMOND, $message, $journal_extends);

            $dao_packet->commit();

            return $packetid;

        } catch (Exception $e) {
            $dao_packet->rollback();
            throw new BizException(ERROR_BIZ_PACKET_SEND_ERROR, $e->getMessage());
        }
    }

    public function sendSharePacket($amount, $num, $remark, $liveid, $threshold)
    {
        $userid = Context::get('userid');
        $orderid = Account::getOrderId($userid);

        // 账户冻结
        $dao_profile = new DAOProfile($userid);
        $profiles = $dao_profile->getUserProfiles();
        Interceptor::ensureNotFalse((!(isset($profiles['frozen']) && $profiles['frozen'] == 'Y')), ERROR_USER_FROZEN, "uid");

        $diamond = Account::getBalance($userid, ACCOUNT::CURRENCY_DIAMOND);
        Interceptor::ensureNotFalse($diamond >= $amount, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);

        $dao_packet = new DAOPacket();
        // 直播间有未领取完的红包
        Interceptor::ensureEmpty($dao_packet->hasActive($liveid, 3), ERROR_BIZ_PACKET_SEND_NOTALLOW);

        $dao_packet_receive = new DAOPacketReceive();
        $extends = $this->getPacketBonus($amount, $num);

        try {
            $dao_packet->startTrans();
            $packetid = $dao_packet->add($userid, 3, $amount, $num, $threshold, $remark, $liveid, json_encode($extends), 0, 'N', date('Y-m-d H:i:s'));
            $dao_packet_receive->add($packetid, 0, $extends, $liveid, 0, $num);

            $receive_data = $dao_packet_receive->getReceiveInfoByPackageid($packetid, 0);
            $this->createOpenCache($packetid, $receive_data);

            // 对比redis 数据库信息
            if (!$this->checkCreateOpenCache($packetid, $num, $receive_data)) {
                throw new Exception('红包发送失败');
            }

            $message = "[分享红包][发放][packetid:{$packetid}][senduid:{$userid}][liveid:{$liveid}][金额:{$amount}][个数:{$num}]";
            $journal_extends = ['type' => 'send'];
            Account::decrease($userid, Account::TRADE_TYPE_RED_PACKET, $orderid, $amount, Account::CURRENCY_DIAMOND, $message, $journal_extends);
            $uid_system = $this->getPacketAccount($packetid);
            Account::increase($uid_system, Account::TRADE_TYPE_RED_PACKET, $orderid, $amount, Account::CURRENCY_DIAMOND, $message, $journal_extends);

            $dao_packet->commit();

            // 下发消息
            $this->package_message($packetid, 'create');
            // 直播间红包状态
            $this->setLivePacket($liveid, $packetid);

            return $packetid;

        } catch (Exception $e) {
            $dao_packet->rollback();
            throw new BizException(ERROR_BIZ_PACKET_SEND_ERROR, $e->getMessage());
        }
    }

    public function receive($packetid)
    {
        $userid = Context::get('userid');
        $packet_info = $this->packageInfo($packetid);

        $share_count = 0;
        if ($packet_info['type'] == 3) {
            $share_count = $this->shareInfo($packet_info);
            if ($share_count >= $packet_info['threshold']) {
                if ($this->checkCanOpenCache($packetid)) {
                    $stage = 'active';
                } else {
                    $stage = 'end';
                }
            } else {
                $stage = 'share';
            }
        } else {
            if ($this->checkCanOpenCache($packetid)) {
                $stage = 'active';
            } else {
                $stage = 'end';
            }
        }

        if ($stage == 'active') {
            // 检查是否领取过 #
            Interceptor::ensureFalse($this->checkUserOpenLock($packetid, $userid), ERROR_BIZ_PACKET_OPEN_FINISH);
            $this->receiveCache($packetid, $userid);
        }

        return [
            'stage' => $stage,
            'packetid' => (int)$packetid,
            'packet' => $packet_info,
            'share' => $share_count,
        ];
    }

    public function open($packetid)
    {
        $userid = Context::get('userid');
        $packet_info = $this->packageInfo($packetid);
        // 红包状态
        Interceptor::ensureNotFalse($packet_info['active'] == 'Y', ERROR_BIZ_PACKET_SERCEIVE_NOT_ACTIVE);
        Interceptor::ensureNotFalse($packet_info['status'] == '0', ERROR_BIZ_PACKET_SERCEIVE_EMPTY);

        // 分享红包是否达到可领状态
        if ($packet_info['type'] == 3) {
            $share_count = $this->shareInfo($packet_info);
            Interceptor::ensureNotFalse($share_count >= $packet_info['threshold'], ERROR_BIZ_PACKET_SERCEIVE_NOT_ACTIVE);
        }

        // 检查set
        Interceptor::ensureNotFalse($this->checkReceiveCache($packetid, $userid), ERROR_BIZ_PACKET_SERCEIVE_NOT_ACTIVE);

        // 检查list, 能否领取
        Interceptor::ensureNotFalse($this->checkCanOpenCache($packetid), ERROR_BIZ_PACKET_SERCEIVE_EMPTY);

        // 加锁
        Interceptor::ensureNotFalse($this->userOpenLock($packetid, $userid), ERROR_BIZ_PACKET_OPEN_FINISH);

        $dao_packet_receive = new DAOPacketReceive();
        $orderid = Account::getOrderId($userid);

        $receiveid = $this->assignOpenCache($packetid);
        Interceptor::ensureNotEmpty($receiveid, ERROR_BIZ_PACKET_OPEN_ERROR);

        $dao_packet = new DAOPacket();
        try {

            $dao_packet->startTrans();
            $receive_info = $dao_packet_receive->getInfoByReceiveid($receiveid, true);
            Interceptor::ensureNotFalse($receive_info['uid'] == '0' && $receive_info['status'] == '0' && $packet_info['packetid'] == $receive_info['packetid'], ERROR_BIZ_PACKET_SERCEIVE_EMPTY);
            $amount = $receive_info['amount'];
            $message = "[群红包][领取][packetid:{$packet_info['packetid']}][senduid:{$packet_info['uid']}][receiveid:{$receiveid}][receiveuid:{$userid}][金额:{$amount}]";
            $journal_extends = ['type' => 'open'];

            $row = $dao_packet_receive->modify($receiveid, $userid, 1);
            if ($row <= 0) {
                throw new Exception('ERROR_BIZ_PACKET_OPEN_ERROR');
            }

            $uid_system = $this->getPacketAccount($packetid);
            Account::decrease($uid_system, Account::TRADE_TYPE_RED_PACKET, $orderid, $amount, Account::CURRENCY_DIAMOND, $message, $journal_extends);

            Account::increase($userid, Account::TRADE_TYPE_RED_PACKET, $orderid, $amount, Account::CURRENCY_DIAMOND, $message, $journal_extends);

            // 领取完毕
            $can_open = $this->checkCanOpenCache($packetid);
            if (!$can_open) {
                $dao_packet = new DAOPacket();
                $dao_packet->modify($packetid, 1, 'N');
            }

            $dao_packet->commit();

            if (!$can_open) {
                // 红包关闭消息
                $this->package_message($packetid, 'end');
                // 清空直播间packetid
                $this->deleteLivePacket($packet_info['liveid']);
            }

        } catch (Exception $e) {

            $dao_packet->rollback();
            $this->pushOpenCache($packetid, $receiveid);
            $this->userOpenUnlock($packetid, $userid);
            throw new BizException(ERROR_BIZ_PACKET_OPEN_ERROR, $e->getMessage());
        }

        $stage = $this->stageInfo($packet_info);
        $open = $this->openInfo($packetid, $stage);

        $account = new Account();
        $currency_diamond = $account->getBalance($userid, ACCOUNT::CURRENCY_DIAMOND);

        return [
            'packetid' => (int)$packetid,
            'packet' => $packet_info,
            'open' => $open,
            'share' => $share_count,
            'stage' => $stage,
            'diamond' => (int)$currency_diamond,
        ];

        return $packetid;
    }

    public function getReceiveList($packetid)
    {
        $packet_info = $this->packageInfo($packetid);
        $share = $this->shareInfo($packet_info);
        $stage = $this->stageInfo($packet_info);
        $open = $this->openInfo($packetid, $stage);

        return [
            'packetid' => (int)$packetid,
            'packet' => $packet_info,
            'open' => $open,
            'share' => $share,
            'stage' => $stage,
        ];
    }

    private function packageInfo($packetid)
    {
        $dao_packet = new DAOPacket();
        $packet_info = $dao_packet->getPacketInfo($packetid);

        Interceptor::ensureNotEmpty($packet_info, ERROR_PARAM_INVALID_FORMAT, 'packetid');

        $packet_info['userinfo'] = User::getUserInfo($packet_info['uid']);
        unset($packet_info['extends']);

        return $packet_info;
    }

    private function openInfo($packetid, $stage)
    {
        $dao_packet_receive = new DAOPacketReceive();
        $open = $dao_packet_receive->getReceiveInfoByPackageid($packetid, 1);
        if ($open) {
            foreach ($open as &$value) {
                $value['userinfo'] = User::getUserInfo($value['uid']);
                $value['best'] = 0;
            }
            unset($value);

            if ($stage == 'end') {
                $open_sort = $open;
                $amount = array_column($open, 'amount');
                array_multisort($amount, SORT_DESC, SORT_NUMERIC, $open_sort);
                $open_best = reset($open_sort);

                foreach ($open as &$value) {
                    if ($value['id'] == $open_best['id']) {
                        $value['best'] = 1;
                    } else {
                        $value['best'] = 0;
                    }
                }
                unset($value);
            }
        } else {
            $open = [];
        }

        return $open;
    }

    private function shareInfo($packet_info)
    {
        $share = 0;
        if ($packet_info['type'] == 3) {
            $key = "{$packet_info['liveid']}:{$packet_info['packetid']}";
            $share = Counter::get(Counter::COUNTER_TYPE_PACKET_SHARE, $key);
        }

        return (int)$share;
    }

    private function stageInfo($packet_info)
    {
        if ($packet_info['status'] == 1 || $packet_info['status'] == 2) {
            $stage = 'end';
        } else {
            $packetid = $packet_info['packetid'];
            if ($packet_info['type'] == 3) {
                $share_count = $this->shareInfo($packet_info);
                if ($share_count >= $packet_info['threshold']) {
                    if ($this->checkCanOpenCache($packetid)) {
                        $stage = 'active';
                    } else {
                        $stage = 'end';
                    }
                } else {
                    $stage = 'share';
                }
            } else {
                if ($this->checkCanOpenCache($packetid)) {
                    $stage = 'active';
                } else {
                    $stage = 'end';
                }
            }
        }

        return $stage;
    }

    public function getPacket($liveid)
    {
        $packetid = $this->getLivePacket($liveid);

        if ($packetid) {
            return $this->getReceiveList($packetid);
        } else {
            return [
                'stage' => 'end',
            ];
        }
    }

    private function openCacheKey($packetid)
    {
        return "packet:open:{$packetid}";
    }

    private function createOpenCache($packetid, $receive_data)
    {
        $cache = Cache::getInstance("REDIS_CONF_CACHE");

        $key = $this->openCacheKey($packetid);
        $cache->expire($key, $this->expire);

        foreach ($receive_data as $value) {
            $cache->rPush($key, $value['id']);
        }
        unset($value);
    }

    private function checkCreateOpenCache($packetid, $num, $receive_data)
    {
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $receive_id = array_column($receive_data, 'id');

        $key = $this->openCacheKey($packetid);
        $cache_length = $cache->lLen($key);

        if (count($receive_id) != $cache_length || $num != $cache_length) {
            return false;
        }

        $cache_id = $cache->lRange($key, 0, $cache_length);
        if (count(array_intersect($receive_id, $cache_id)) == count($receive_id)) {
            return true;
        } else {
            return false;
        }
    }

    private function checkCanOpenCache($packetid)
    {
        $key = $this->openCacheKey($packetid);
        $cache = Cache::getInstance("REDIS_CONF_CACHE");

        if ($cache->lLen($key) > 0) {
            return true;
        } else {
            return false;
        }
    }

    private function assignOpenCache($packetid)
    {
        $key = $this->openCacheKey($packetid);
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        return $cache->lPop($key);
    }

    private function pushOpenCache($packetid, $receiveid)
    {
        $cache = Cache::getInstance("REDIS_CONF_CACHE");

        $key = $this->openCacheKey($packetid);
        $cache->rPush($key, $receiveid);
    }

    private function receiveCacheKey($packetid)
    {
        return "packet:receive:{$packetid}";
    }

    private function receiveCache($packetid, $userid)
    {
        $key = $this->receiveCacheKey($packetid);
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $cache->expire($key, $this->expire);
        $cache->sAdd($key, $userid);
    }

    private function checkReceiveCache($packetid, $userid)
    {
        $key = $this->receiveCacheKey($packetid);
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        return $cache->sIsMember($key, $userid);
    }

    private function userOpenLockKey($packetid, $userid)
    {
        return "packet:lock:{$packetid}:{$userid}";
    }

    private function userOpenLock($packetid, $userid)
    {
        $key = $this->userOpenLockKey($packetid, $userid);
        $cache = Cache::getInstance("REDIS_CONF_CACHE");

        return $cache->add($key, '1', $this->expire);
    }

    private function checkUserOpenLock($packetid, $userid)
    {
        $key = $this->userOpenLockKey($packetid, $userid);
        $cache = Cache::getInstance("REDIS_CONF_CACHE");

        return (boolean)$cache->get($key);
    }

    private function userOpenUnlock($packetid, $userid)
    {
        $key = $this->userOpenLockKey($packetid, $userid);
        $cache = Cache::getInstance("REDIS_CONF_CACHE");

        $cache->delete($key);
    }

    private function livePacketkey($liveid)
    {
        return "packet:{$liveid}";
    }

    public function getLivePacket($liveid)
    {
        $key = $this->livePacketkey($liveid);
        $cache = Cache::getInstance("REDIS_CONF_CACHE");

        return $cache->get($key);
    }

    private function setLivePacket($liveid, $packetid)
    {
        $key = $this->livePacketkey($liveid);
        $cache = Cache::getInstance("REDIS_CONF_CACHE");

        return $cache->set($key, $packetid, 4 * 60 * 60);
    }

    private function deleteLivePacket($liveid)
    {
        $key = $this->livePacketkey($liveid);
        $cache = Cache::getInstance("REDIS_CONF_CACHE");

        $cache->delete($key);
    }

    private function getPacketAccount($packetid)
    {
        return $packetid % 100 + 1800;
    }

    private function sqr($n)
    {
        return $n * $n;
    }

    private function xRandom($bonus_min, $bonus_max)
    {
        $sqr = intval($this->sqr($bonus_max - $bonus_min));
        $rand_num = mt_rand(0, ($sqr - 1));
        return intval(sqrt($rand_num));
    }

    private function getPacketBonus($bonus_total, $bonus_count)
    {
        $bonus_min = 1;
        // $bonus_max = floor($bonus_total - $bonus_min * $bonus_count - 1);
        $bonus_max = ceil($bonus_total / $bonus_count) * 7;
        $result_bonus = $this->getBonus($bonus_total, $bonus_count, $bonus_max, $bonus_min);

        $result_max = max($result_bonus);
        $keys_max = array_keys($result_bonus, $result_max);
        if (count($keys_max) > 1) { // 多个最佳手气变成一个
            $i = 1;
            $key = reset($keys_max);
            $result_bonus[$key] = $result_bonus[$key] + $i;
            $key = end($keys_max);
            $result_bonus[$key] = $result_bonus[$key] - $i;
        }

        return $result_bonus;
    }

    /**
     *
     * @param $bonus_total 红包总额
     * @param $bonus_count 红包个数
     * @param $bonus_max 每个小红包的最大额, 要大于平均值
     * @param $bonus_min 每个小红包的最小额
     *
     * @return 存放生成的每个小红包的值的一维数组
     */
    public function getBonus($bonus_total, $bonus_count, $bonus_max, $bonus_min)
    {
        $result = [];

        $average = $bonus_total / $bonus_count;

        if ($average == 1) {
            // 平均分
            $result = array_pad($result, $bonus_count, $average);
            return $result;
        }

        $a = $average - $bonus_min;
        $b = $bonus_max - $bonus_min;

        //
        //这样的随机数的概率实际改变了，产生大数的可能性要比产生小数的概率要小。
        //这样就实现了大部分红包的值在平均数附近。大红包和小红包比较少。
        $range1 = $this->sqr($average - $bonus_min);
        $range2 = $this->sqr($bonus_max - $average);

        for ($i = 0; $i < $bonus_count; $i++) {
            //因为小红包的数量通常是要比大红包的数量要多的，因为这里的概率要调换过来。
            //当随机数>平均值，则产生小红包
            //当随机数<平均值，则产生大红包
            if (rand($bonus_min, $bonus_max) > $average) {
                // 在平均线上减钱
                $temp = $bonus_min + $this->xRandom($bonus_min, $average);
                $result[$i] = $temp;
                $bonus_total -= $temp;
            } else {
                // 在平均线上加钱
                $temp = $bonus_max - $this->xRandom($average, $bonus_max);
                $result[$i] = $temp;
                $bonus_total -= $temp;
            }
        }
        // 如果还有余钱，则尝试加到小红包里，如果加不进去，则尝试下一个。
        while ($bonus_total > 0) {
            for ($i = 0; $i < $bonus_count; $i++) {
                if ($bonus_total > 0 && $result[$i] < $bonus_max) {
                    $result[$i]++;
                    $bonus_total--;
                }
            }
        }
        // 如果钱是负数了，还得从已生成的小红包中抽取回来
        while ($bonus_total < 0) {
            for ($i = 0; $i < $bonus_count; $i++) {
                if ($bonus_total < 0 && $result[$i] > $bonus_min) {
                    $result[$i]--;
                    $bonus_total++;
                }
            }
        }

        return $result;
    }

    public function process_share($liveid, $uid)
    {
        // 找到直播间红包
        $packetid = $this->getLivePacket($liveid);

        if (!$packetid) {
            return true;
        }

        $dao_packet = new DAOPacket();
        $packet_info = $dao_packet->getPacketInfo($packetid);
        $share_callback = new DAOShareCallback();
        $user_share = $share_callback->getUserShare($uid, 'live', $liveid, $packet_info['addtime']);

        if (count($user_share) != 1) { // 首次分享才增加红包分享次数, 首先执行callback
            return true;
        }

        $key = "{$liveid}:{$packet_info['packetid']}";
        $share_num = Counter::increase(Counter::COUNTER_TYPE_PACKET_SHARE, $key);
        if ($share_num >= $packet_info['threshold']) {
            // 更新为可领取
            $result = $dao_packet->active($packetid);
            // 下发消息
            if ($result) {
                $this->package_message($packetid, 'active');
            }
        } else {
            $this->package_message($packetid, 'share');
        }

        return true;
    }

    public function package_message($packetid, $stage)
    {
        $dao_packet = new DAOPacket();
        $packet_info = $dao_packet->getPacketInfo($packetid);
        $userinfo = User::getUserInfo($packet_info['uid']);
        $share = $this->shareInfo($packet_info);

        switch ($stage) {
        case 'create' :
            $text = '发送一个分享红包';
            break;
        case 'share':
            $text = '';
            break;
        case 'active':
            $text = '完成分享领红包';
            break;
        case 'end':
            $text = '';
            break;

        }

        $sender_arr = [
            10000500
            , 10000501
            , 10000502
            , 10000503
            , 10000504
            , 10000505
            , 10000506
            , 10000507
            , 10000508
            , 10000509
            , 10000510
            , 10000511
            , 10000512
            , 10000513
            , 10000514
            , 10000515
            , 10000516
            , 10000517
            , 10000518
            , 10000519
            , 10000520
            , 10000521
            , 10000522
            , 10000523
            , 10000524
            , 10000525
            , 10000526
            , 10000527
            , 10000528
            , 10000529
            , 10000530
            , 10000531
            , 10000532
            , 10000533
            , 10000534
            , 10000535
            , 10000536
            , 10000537
            , 10000538
            , 10000539
            , 10000540
            , 10000541
            , 10000542
            , 10000543
            , 10000544
            , 10000545
            , 10000546
            , 10000547
            , 10000548
            , 10000549
            , 10000550,
        ];
        $key = array_rand($sender_arr);
        $sender = $sender_arr[$key];

        Messenger::sendLiveRed(
            $packet_info['liveid'], $text, $sender, $packet_info['uid'], $userinfo['nickname'], $userinfo['avatar'],
            $packetid, $share, $packet_info['threshold'], $stage
        );
    }
}