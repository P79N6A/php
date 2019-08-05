<?php
class RedPacketLog
{
    /**
     * 写红包日志
     * @param int $uid
     * @param int $relateid
     * @param int $redid
     * @param int $type
     * @param int $amount
     */
    public static function addRedPacketLog($uid, $relateid, $redid, $type, $amount, $flags){
        $DAORedPacketLog = new DAORedPacketLog($uid);
        return $DAORedPacketLog->addRedPacketLog($uid, $relateid, $redid, $type, $amount, $flags);
    }

    /**
     * 修改领取红包的orderid
     * @param int $uid
     * @param int $logid
     * @param int $orderid
     */
    public static function updateRedPacketLog($uid, $logid, $orderid){
        $DAORedPacketLog = new DAORedPacketLog($uid);
        return $DAORedPacketLog->updateRedPacketLog($logid, $orderid);
    }

    /**
     * 获取领取红包详情
     * @param int $userid
     * @param int $uid
     * @param int $redid
     */
    public static function getUserRedPacketLogInfo($userid, $uid, $redid){
    	$DAORedPacketLog = new DAORedPacketLog($userid);
        $redPacketLogInfo = $DAORedPacketLog->getRedPacketLogInfo($userid, $uid, $redid, 'receive');

        $redPacket = array(
            'redid'   => $redPacketLogInfo['redid'],
            'amount'  => $redPacketLogInfo['amount'],
            'addtime' => $redPacketLogInfo['addtime'],
        );
        $userInfo = User::getUserInfo($redPacketLogInfo['relateid']);
        return array($redPacket,$userInfo);
    }

    /**
     * 获取领取红包的列表数据
     * @param int $uid
     * @param int $type send:我发的红包被哪些人领取，receive:我领取哪些人发的红包
     * @param int $num
     * @param int $offset
     * @return array
     */
    public static function getUserRedPacketLogList($uid, $type, $num, $offset)
    {
        $uids = $arrTemp = array();

        $DAORedPacketLog = new DAORedPacketLog($uid);
        $list = $DAORedPacketLog->getUserTaskLogsList($uid, $type, $num, $offset);
        foreach ($list as $item) {
            array_push($uids, $item['relateid']);
        }
        $user = new User();
        $userInfos = $user->getUserInfos($uids);

        foreach ($list as $key => $val) {
            $redPacket = array(
                'redid'   => $val['redid'],
                'amount'  => $val['amount'],
                'addtime' => $val['addtime']
            );
            $arrTemp[$key] = array(
                'userInfo' => $userInfos[$val['relateid']],
                'redPacket' => $redPacket
            );
            $offset = $val['id'];
        }

        $total = $DAORedPacketLog->getUserTaskLogsTotal($uid, $type);
        $more  = (bool) $DAORedPacketLog->getMoreUserTaskLogs($uid, $type, $offset);
        return array($arrTemp,$total,$offset,$more);
    }
}