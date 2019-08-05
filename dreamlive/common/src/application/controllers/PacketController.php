<?php

class PacketController extends BaseController
{
    public function sendAction()
    {
        $type = trim(strip_tags($this->getParam('type', '')));
        $amount = trim(strip_tags($this->getParam('amount', '')));
        $remark = '恭喜发财，大吉大利。';
        $liveid = trim(strip_tags($this->getParam('liveid', '0')));

        Interceptor::ensureNotFalse(preg_match('/^[1-9]\d*$/', $amount) > 0, ERROR_PARAM_INVALID_FORMAT, 'amount');
        Interceptor::ensureNotFalse(mb_strlen($remark) <= 20, ERROR_PARAM_INVALID_FORMAT, 'remark');

        switch ($type) {
        case '3':
            $packetid = $this->sendSharePacket($amount, $remark, $liveid);
            break;
        default:
            throw new BizException(ERROR_PARAM_INVALID_FORMAT, 'type');
                break;
        }

        $userid = Context::get('userid');
        $account = new Account();
        $currency_diamond = $account->getBalance($userid, ACCOUNT::CURRENCY_DIAMOND);

        $this->render(['packetid' => (int)$packetid, 'diamond' => (int)$currency_diamond, 'remark' => $remark]);
    }

    private function sendSinglePacket($amount, $remark, $liveid)
    {
        $packet = new Packet();

        $receiver = trim(strip_tags($this->getParam('uid', '')));
        Interceptor::ensureNotFalse(preg_match('/^[1-9]\d*$/', $receiver) > 0, ERROR_PARAM_INVALID_FORMAT, 'uid');

        if ($liveid != '0') {
            Interceptor::ensureNotFalse(preg_match('/^[1-9]\d*$/', $liveid) > 0, ERROR_PARAM_INVALID_FORMAT, 'liveid');
        }

        return $packet->sendSinglePacket($receiver, $amount, $remark, $liveid);
    }

    private function sendGroupPacket($amount, $remark, $liveid)
    {
        $num = trim(strip_tags($this->getParam('num', '')));
        Interceptor::ensureNotFalse(preg_match('/^[1-9]\d*$/', $num) > 0, ERROR_PARAM_INVALID_FORMAT, 'num');
        Interceptor::ensureNotFalse($amount >= $num, ERROR_BIZ_PACKET_SEND_AMOUNT_NOT_ENOUGH, 'num');

        Interceptor::ensureNotFalse(preg_match('/^[1-9]\d*$/', $liveid) > 0, ERROR_PARAM_INVALID_FORMAT, 'liveid');
        $dao_live = new DAOLive();
        $live_info = $dao_live->getLiveInfo($liveid);
        Interceptor::ensureNotEmpty($live_info, ERROR_BIZ_LIVE_NOT_ACTIVE);
        Interceptor::ensureNotFalse($live_info['status'] == Live::ACTIVING, ERROR_BIZ_LIVE_NOT_ACTIVE);

        $packet = new Packet();

        return $packet->sendGroupPacket($amount, $num, $remark, $liveid);
    }

    private function sendSharePacket($amount, $remark, $liveid)
    {
        Interceptor::ensureNotFalse($amount >= 50, ERROR_PARAM_INVALID_FORMAT, 'amount');

        $num = trim(strip_tags($this->getParam('num', '')));
        Interceptor::ensureNotFalse(in_array($num, [5, 10, 15, 20]), ERROR_PARAM_INVALID_FORMAT, 'num');
        Interceptor::ensureNotFalse($amount >= $num, ERROR_BIZ_PACKET_SEND_AMOUNT_NOT_ENOUGH, 'num');

        // 最低分享次数
        $threshold = trim(strip_tags($this->getParam('threshold', 0)));
        Interceptor::ensureNotFalse(in_array($threshold, [5, 10, 15, 20]), ERROR_PARAM_INVALID_FORMAT, 'threshold');

        Interceptor::ensureNotFalse(preg_match('/^[1-9]\d*$/', $liveid) > 0, ERROR_PARAM_INVALID_FORMAT, 'liveid');
        $dao_live = new DAOLive();
        $live_info = $dao_live->getLiveInfo($liveid);
        Interceptor::ensureNotEmpty($live_info, ERROR_BIZ_LIVE_NOT_ACTIVE);
        Interceptor::ensureNotFalse(in_array($live_info['status'], [Live::ACTIVING, Live::PAUSED]), ERROR_BIZ_LIVE_NOT_ACTIVE);

        $packet = new Packet();

        return $packet->sendSharePacket($amount, $num, $remark, $liveid, $threshold);
    }

    public function receiveAction()
    {
        $packetid = trim(strip_tags($this->getParam('packetid', '')));
        Interceptor::ensureNotFalse(preg_match('/^[1-9]\d*$/', $packetid) > 0, ERROR_PARAM_INVALID_FORMAT, 'packetid');

        $packet = new Packet();
        $result = $packet->receive($packetid);

        $this->render($result);
    }

    public function openAction()
    {
        $packetid = trim(strip_tags($this->getParam('packetid', '')));
        Interceptor::ensureNotFalse(preg_match('/^[1-9]\d*$/', $packetid) > 0, ERROR_PARAM_INVALID_FORMAT, 'packetid');

        $packet = new Packet();
        $result = $packet->open($packetid);

        $this->render($result);
    }

    public function getReceiveListAction()
    {
        $packetid = trim(strip_tags($this->getParam('packetid', '')));
        Interceptor::ensureNotFalse(preg_match('/^[1-9]\d*$/', $packetid) > 0, ERROR_PARAM_INVALID_FORMAT, 'packetid');

        $packet = new Packet();
        $data = $packet->getReceiveList($packetid);

        $this->render($data);
    }

    public function getPacketAction()
    {
        $liveid = trim(strip_tags($this->getParam('liveid', '0')));
        Interceptor::ensureNotFalse(preg_match('/^[1-9]\d*$/', $liveid) > 0, ERROR_PARAM_INVALID_FORMAT, 'liveid');

        $packet = new Packet();
        $data = $packet->getPacket($liveid);

        $this->render($data);
    }
}