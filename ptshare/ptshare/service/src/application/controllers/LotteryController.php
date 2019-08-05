<?php
class LotteryController extends BaseController
{
    public function addQXCAction()
    {
        $sellid    = trim($this->getParam("sellid"));
        $number    = trim($this->getParam("number"));
        $userid = Context::get("userid");

        Interceptor::ensureNotEmpty($number, ERROR_PARAM_IS_EMPTY, "number");
        Interceptor::ensureNotEmpty($sellid, ERROR_PARAM_IS_EMPTY, "sellid");
        Interceptor::ensureNotFalse(!!preg_match("/^\d{7}$/", $number), ERROR_PARAM_IS_EMPTY, "number");

        $lottery = new Lottery();
        $isUsed = $lottery->isSellidUsed($sellid);

        $sell = new DAOSell();
        $sellExist = $sell->existUidSellid($userid, $sellid);

        Interceptor::ensureNotFalse(!$isUsed && $sellExist, ERROR_PARAM_IS_EMPTY, "sellid");

        $config = new Config();
        $results = $config->getConfig("china", "activity_config", "xcx");
        Interceptor::ensureNotFalse(in_array(Lottery::ACTIVITY_CONFIG_ID, $results['value']), ERROR_LOTTERY_DISABLE);

        $config = $lottery->getCurrentLotteryConfig();
        Interceptor::ensureNotFalse($config && $config['issue'], ERROR_LOTTERY_DISABLE);

        $lottery->addQXC($userid, $sellid, $config['issue'], $config['pt_issue'], $number);

        $this->render();
    }

    public function getQXCAction()
    {
        $userid = Context::get("userid");

        $lottery = new Lottery();
        $config = $lottery->getCurrentLotteryConfig();

        $list = $lottery->getUserLottery($userid);
        foreach($list as $k=>$v){
            $issueId[] = $v['issue'];
        }
        $winNumbers = $lottery->getWinNumberByIssue($issueId);

        $current = array(
            "qxc_issue" => (string) $config['issue'],
            "endtime"   => (string) $config['endtime'] ? date('Y年m月d日H:i', $config['endtime']) : "",
            "number"    => [],

        );
        $past = [];

        if($list){
            $lotteryList = [];
            foreach($list as $k=>$v){
                $lotteryList[$v['issue']][] = $v;
            }
            if($config){
                foreach($lotteryList[$config['issue']] as $v){
                    $current["number"][] = $v['number'];
                }

                unset($lotteryList[$config['issue']]);
            }

            foreach($lotteryList as $k=>$issue){
                $currentIssue = current($issue);
                $pastNumber = [];
                $pastNumber["qxc_issue"] = $currentIssue['issue'];
                $pastNumber["pt_issue"]  = $currentIssue['pt_issue'];
                $pastNumber["win_number"]  = (string) $winNumbers[$currentIssue['issue']];

                foreach($issue as $v){
                    $temp['result'] = Lottery::getResult($v['result']);
                    $temp['status'] = Lottery::getStatus($v['status'], $v['result']);

                    $n = $f = $format = [];
                    $n = str_split($v['number']);
                    $f = str_split($v['number_format']);
                    foreach($n as $k2=>$v2){
                        $format[] = [
                            "num"   => $v2,
                            "state" =>(bool) $f[$k2]
                        ];
                    }
                    $temp['number'] = $format;

                    $pastNumber["number"][] = $temp;
                }

                $past[] = $pastNumber;
            }
        }

        $this->render(
            array(
                "current" => $current,
                "past"    => $past,
            )
        );
    }

    public function adminSetLotteryConfigAction()
    {
        $issue     = trim($this->getParam("issue"));
        $status    = trim($this->getParam("status"));
        $number    = trim($this->getParam("number"));

        $lottery = new Lottery();
        $lotteryInfo = $lottery->getLotteryConfigInfo($issue);

        if($number){
            Interceptor::ensureEmpty($lotteryInfo['number'], ERROR_LOTTERY_EDIT_NUMBER_ALREADY);
            Interceptor::ensureNotFalse(!!preg_match("/^\d{7}$/", $number), ERROR_PARAM_IS_EMPTY, "number");
        }
        Interceptor::ensureNotFalse($lotteryInfo['status'] == Lottery::STATUS_WAIT, ERROR_LOTTERY_STATUS_NOT_WAIT);
        Interceptor::ensureNotFalse($lotteryInfo['endtime'] < time() , ERROR_LOTTERY_EDIT_LT_ENDTIME);

        $config = $lottery->setLotteryConfig($issue, $status, $number);

        $this->render();
    }

    public function adminSetLotteryRunAction()
    {
        $issue = trim($this->getParam("issue"));

        $lottery = new Lottery();
        $lotteryInfo = $lottery->getLotteryConfigInfo($issue);

        Interceptor::ensureNotFalse($lotteryInfo['status'] == Lottery::STATUS_WAIT, ERROR_LOTTERY_STATUS_NOT_WAIT);
        Interceptor::ensureNotFalse($lotteryInfo['endtime'] < time() , ERROR_LOTTERY_EDIT_LT_ENDTIME);

        $lottery->run($issue);

        $this->render();
    }

    public function adminSetWinnerPayAction()
    {
        $id = trim($this->getParam("id"));
        Interceptor::ensureNotEmpty($id, ERROR_PARAM_IS_EMPTY, "id");

        $lottery = new Lottery();
        $lottery->winnerPay($id);

        $this->render();
    }

    public function adminAddQxcConfigAction()
    {
        $issue    = trim($this->getParam('issue', ''));
        $ptIssue  = trim($this->getParam('pt_issue', ''));
        $startime = trim($this->getParam('startime', ''));
        $endtime  = trim($this->getParam('endtime', ''));
        
        Interceptor::ensureNotEmpty($issue, ERROR_PARAM_IS_EMPTY, "issue");
        Interceptor::ensureNotEmpty($ptIssue, ERROR_PARAM_IS_EMPTY, "pt_issue");
        Interceptor::ensureNotEmpty($startime, ERROR_PARAM_IS_EMPTY, "startime");
        Interceptor::ensureNotEmpty($endtime, ERROR_PARAM_IS_EMPTY, "endtime");

        $startime = strtotime($startime);
        $endtime = strtotime($endtime);
        Interceptor::ensureNotFalse($startime > 1, ERROR_PARAM_IS_EMPTY, "startime");
        Interceptor::ensureNotFalse($endtime > 1, ERROR_PARAM_IS_EMPTY, "endtime");

        $lottery = new Lottery();
        
        $lotteryInfo = $lottery->getLotteryConfigInfo($issue);
        Interceptor::ensureEmpty($lotteryInfo, ERROR_LOTTERY_EXIST, "issue");

        $dao = new DAOLotteryConfig();

        Interceptor::ensureFalse($dao->existPtIssueLottery($ptIssue), ERROR_LOTTERY_EXIST, "葡萄期号");
        Interceptor::ensureFalse($dao->existTimeLottery($startime), ERROR_LOTTERY_EXIST, "开始时间");
        Interceptor::ensureFalse($dao->existTimeLottery($endtime), ERROR_LOTTERY_EXIST, "结束时间");

        $dao->addQxcConfig($issue, $ptIssue, $startime, $endtime);

        $this->render();
    }

}