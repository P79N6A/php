<?php
class Lottery
{
    const STATUS_WAIT       = "N"; // 未开奖
    const STATUS_PREPARE  = "P"; // 准备开奖
    const STATUS_RUN      = "R"; // 开奖中
    const STATUS_COMPLETE = "Y"; // 开奖结束

    const ACTIVITY_CONFIG_ID = 1;
    //开奖结果
    public static $_result = array(
        "0" => "未开奖",
        "1" => "一等奖",
        "2" => "二等奖",
        "3" => "三等奖",
        "4" => "四等奖",
        "5" => "五等奖",
        "6" => "六等奖",
        "99" => "未中奖",
    );

    public static $_status = array(
        "N" => "待发放",
        "Y" => "已发放",
    );

    public static $_prize = array(
        "1" => "10000",
        "2" => "5000",
        "3" => "3000",
        "4" => "1000",
        "5" => "100",
        "6" => "10",
    );

    public function getLotteryStatus($issue)
    {
        $dao = new DAOLotteryConfig();

        return $dao->getLotteryStatus($issue);
    }

    public function getLotteryConfigInfo($issue)
    {
        $dao = new DAOLotteryConfig();

        return $dao->getLotteryInfo($issue);
    }

    public function getCurrentLotteryConfig()
    {
        $dao = new DAOLotteryConfig();

        return $dao->getCurrentLottery();
    }

    public function addQXC($uid, $sellid, $issue, $ptIssue, $number)
    {
        $dao = new DAOLottery();

        return $dao->addQXC($uid, $sellid, $issue, $ptIssue, $number);
    }

    public function getUserLottery($uid)
    {
        $dao = new DAOLottery();

        return $dao->getUserLottery($uid);
    }

    public static function getResult($result)
    {
        if(isset(self::$_result[$result])){
            return self::$_result[$result];
        }

        return "";
    }

    public static function getStatus($status, $result)
    {
        if(isset(self::$_status[$status])){
            if(in_array($result, [1,2,3,4,5,6])){
                return self::$_status[$status];
            }
        }

        return "";
    }

    public function setLotteryConfig($issue, $status, $number)
    {
        $dao = new DAOLotteryConfig();

        return $dao->setLotteryConfig($issue, $status, $number);
    }

    public function isSellidUsed($sellid)
    {
        $dao = new DAOLottery();

        return $dao->isSellidUsed($sellid);
    }

    public function run($issue)
    {
        $dao       = new DAOLottery();
        $daoConfig = new DAOLotteryConfig();

        $daoConfig->setLotteryConfig($issue, self::STATUS_RUN);

        $numberList    = $dao->getAllUserLotterys($issue);
        $lotteryNumber = $daoConfig->getLotteryNumber($issue);

        foreach($numberList as $k=>$v){
            try{
                list($result, $format) = $this->redeem($v['number'], $lotteryNumber);

                $dao->setUserResult($v['id'], $result, $format);
                // 后台手动发奖

            }catch (Exception $e) {
                var_dump($e);die;
            }
        }

        $daoConfig->setLotteryConfig($issue, self::STATUS_COMPLETE);
    }

    public function winnerPay($id)
    {
        $dao = new DAOLottery();
        $info = $dao->getUserLotteryById($id);

        if($info['result'] >= 1 && $info['result']<=6){
            $award = self::$_prize[$info['result']];

            $orderid = Account::addGrapeLottery($info['uid'], $award, "彩票奖励葡萄");
        }
        $dao->setUserOrderid($id, $orderid, 'Y');
        try{
            $model_message = new Message($info['uid']);
            $model_message->sendMessage(DAOMessage::TYPE_LOTTERY_PAY, array(self::getResult($info['result']),$award));
        }catch(Exception $e){}

    }

    public function getWinNumberByIssue($issue)
    {
        $dao = new DAOLotteryConfig();

        return $dao->getWinNumberByIssue($issue);
    }

    function redeem(string $number, string $win)
    {
        $len    = strlen($number);
        $dp     = [0];

        $max    = 0;
        $start  = 0;
        $format = "0000000";

        $result = [
            "0" => "99",
            "1" => "99",
            "2" => "6",
            "3" => "5",
            "4" => "4",
            "5" => "3",
            "6" => "2",
            "7" => "1",
        ];

        for ($i = 0; $i < $len; $i++) {
            if ($number[$i] == $win[$i]) {
                $dp[$i]= $dp[$i-1] + 1;

                if($dp[$i] > $max){
                    $max = $dp[$i];
                    $start = $i - $max + 1;
                }
            } else {
                $dp[$i] = 0;
            }
        }

        if($max >= 2){
            $format = substr_replace($format, str_repeat("1", $max), $start, $max);
        }

        return [$result[$max], $format];
    }

}