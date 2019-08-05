<?php
// crontab 每小时跑一次, 红包退钱
ini_set("display_errors", "On");
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

$ROOT_PATH = dirname(dirname(dirname(dirname(__FILE__))));
$LOAD_PATH = [
    $ROOT_PATH . "/src/www",
    $ROOT_PATH . "/config",
    $ROOT_PATH . "/src/application/controllers",
    $ROOT_PATH . "/src/application/models",
    $ROOT_PATH . "/src/application/models/libs",
    $ROOT_PATH . "/src/application/models/dao",
];
set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $LOAD_PATH));

function __autoload($classname)
{
    $classname = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
    include_once "$classname.php";
}

require $ROOT_PATH . "/config/server_conf.php";

//| 红包退钱流程
//| 扫描一天前的没有领完的status=0 and addtime>一天前
//| 处理过程: 
//| 1. 将list清空.
//| 2. 统计没领的红包金额 status=0
//| 3. 发红包系统帐号进
//| 4. 红包系统帐号出
//| 5. 发退还消息
//| 6. 更新红包状态为2
//| 7. 更新红包的所有的状态为自已
$dao_packet = new DAOPacket();
$dao_live = new DAOLive();

$now = date('Y-m-d H:i:s');

//| 扫描一天前的没有领完的status=0 and addtime>一天前
$date = date("Y-m-d H:i:s", time() - 12 * 60 * 60);
$sql = "select * from packet where status=0 and addtime<'$date'";
$data = $dao_packet->getAll($sql, null, false);
if ($data) {
    $cache = Cache::getInstance('REDIS_CONF_CACHE');

    foreach ($data as $key => $value) {
        //| 1. 将list清空.
        $cache->del("packet:open:{$value['packetid']}");
        $cache->del("packet:receive:{$value['packetid']}");
        //| 2. 统计没领的红包金额 status=0
        $sql = "select sum(amount) as num from packet_receive where packetid={$value['packetid']} and status=0";
        $data = $dao_packet->getRow($sql, null, false);
        $num = $data['num']; //没领的总数
        if ($num > 0) {
            try {
                $dao_packet->startTrans();

                $journal_message = "[红包][过期返还][packetid:{$value['packetid']}][type:{$value['type']}][senduid:{$value['uid']}][liveid:{$value['liveid']}][红包金额:{$value['amount']}][返还金额:{$num}]";
                $journal_extends = ['type' => 'back'];
                //| 3. 发红包帐号进
                $orderid = Account::getOrderId($value['uid']);
                Account::increase($value['uid'], ACCOUNT::TRADE_TYPE_RED_PACKET, $orderid, $num, ACCOUNT::CURRENCY_DIAMOND, $journal_message, $journal_extends);
                //| 4. 红包系统帐号出
                $system_account = $value['packetid'] % 100 + 1800;
                Account::decrease($system_account, ACCOUNT::TRADE_TYPE_RED_PACKET, $orderid, $num, ACCOUNT::CURRENCY_DIAMOND, $journal_message, $journal_extends);
                //| 6. 更新红包状态为2
                $sql = "update packet set status=2, modtime='{$now}' where packetid={$value['packetid']} limit 1";
                $dao_packet->execute($sql, null, false);

                //| 7. 更新红包的所有的状态为自已
                $sql = "update packet_receive set uid={$value['uid']}, status=1, modtime='{$now}' where packetid={$value['packetid']} and status=0";
                $dao_packet->execute($sql, null, false);

                $dao_packet->commit();

                //| 5. 发退还消息
                // 您于yyyy-mm-dd hh:mm:ss在[主播昵称]的直播间发送的分享红包未被及时领取，剩余xx星钻已退还至您的账户，请注意查收。
                $liveid = $value['liveid'];
                if ($liveid) {
                    $live_info = $dao_live->getLiveInfo($liveid);
                    $userinfo = User::getUserInfo($live_info['uid']);
                    $nickname = $userinfo['nickname'];
                } else {
                    $nickname = '';
                }

                $message = "您于{$value['addtime']}在[{$nickname}]的直播间发送的分享红包未被及时领取，剩余{$num}星钻已退还至您的账户，请注意查收。";

                Messenger::sendSystemPublish(
                    Messenger::MESSAGE_TYPE_BROADCAST_SOME,
                    $value['uid'],
                    $message,
                    $message,
                    '0'
                );

                echo $message . "\n";
                echo "packetid:{$value['packetid']}-num:{$num}\n";

            } catch (Exception $e) {
                $dao_packet->rollback();
                throw new BizException(ERROR_CUSTOM, $e->getMessage());
            }
        }

    }
}

print "<pre>";
print_r($date);
print "</pre>";
