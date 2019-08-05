<?php
class LotteryController extends BaseController
{

    public $status = array(
        'Y' => '已发奖',
        'N' => '未发奖',

    );

    public $result = array(
        '0' => '未开奖',
        "1" => "一等奖",
        "2" => "二等奖",
        "3" => "三等奖",
        "4" => "四等奖",
        "5" => "五等奖",
        "6" => "六等奖",
        "99" => "未中奖",

    );

    public $config_status = array(
        'N' => '未开奖',
        'P' => '开奖准备中',
        'R' => '正在开奖',
        'Y' => '开奖结束',
    );

    public function qxcAction()
    {
        $issue  = trim($this->getParam('issue', ''));
        $pt_issue  = trim($this->getParam('pt_issue', ''));
        $result = trim($this->getParam('result', ''));
        $status = trim($this->getParam('status', ''));
        $page   = $this->getParam("page") ? intval($this->getParam("page")): 1;
        $num    = $this->getParam("num") ? intval($this->getParam("num"))  : 20;
        $start  = ($page - 1) * $num;

        $Lottery = new Lottery();
        list($list, $total) = $Lottery->getConfigList($start, $num, $issue, $pt_issue, $result, $status);

        $param = [
            'issue'  => $issue,
            'pt_issue'  => $pt_issue,
            'result' => $result,
            'status' => $status,
        ];
        $mutipage = $this->mutipage($total, $page, $num, http_build_query($param), "/Lottery/qxc");

        $data = array();
        $data['data']     = $list;
        $data['total']    = $total;
        $data['mutipage'] = $mutipage;

        $this->assign("param", $param);
        $this->assign("data", $data);

        $this->display("include/header.html", "lottery/index.html", "include/footer.html");
    }

    public function listAction()
    {
        $issue  = trim($this->getParam('issue', ''));
        $pt_issue  = trim($this->getParam('pt_issue', ''));
        $result = trim($this->getParam('result', ''));
        $status = trim($this->getParam('status', ''));
        $page   = $this->getParam("page") ? intval($this->getParam("page")): 1;
        $num    = $this->getParam("num") ? intval($this->getParam("num"))  : 20;
        $start  = ($page - 1) * $num;

        $Lottery = new Lottery();
        list($list, $total) = $Lottery->getList($start, $num, $issue, $pt_issue, $result, $status);

        $param = [
            'issue'  => $issue,
            'pt_issue'  => $pt_issue,
            'result' => $result,
            'status' => $status,
        ];
        $mutipage = $this->mutipage($total, $page, $num, http_build_query($param), "/Lottery/list");

        $data = array();
        $data['data']     = $list;
        $data['total']    = $total;
        $data['mutipage'] = $mutipage;

        $this->assign("param", $param);
        $this->assign("data", $data);

        $this->display("include/header.html", "lottery/list.html", "include/footer.html");
    }

    public function detailAction()
    {
        $issue = trim($this->getParam('issue', ''));

        $param = [
            'issue'  => $issue,
        ];

        $lottery = new Lottery();
        $data= $lottery->getLotteryConfigDetail($issue);

        $this->assign("param", $param);
        $this->assign("data", $data);

        $this->display("include/header.html", "lottery/detail.html", "include/footer.html");
    }

    public function setNumberAction()
    {
        $issue = trim($this->getParam('issue', ''));
        $number  = trim($this->getParam('number', ''));
        Interceptor::ensureNotEmpty($issue, ERROR_PARAM_IS_EMPTY, "issue");
        Interceptor::ensureNotEmpty($number, ERROR_PARAM_IS_EMPTY, "number");

        try{
            ShareClient::setLotteryConfig($issue, "", $number);
            //日志
            $operate = new Operate();
            $operate_content = array(
                'issue'    => $issue,
                'number'     => $number,
            );
            $operate->add($this->adminid, 'Lottery_setNumber', 0, 0, $operate_content, '', '', 1);

            Util::jumpMsg("修改成功!", "");
        }catch (Exception $e){
            Util::jumpMsg("修改失败:{$e->getCode()}:{$e->getMessage()}", "", 3);
        }

    }
    public function runAction()
    {
        $issue = trim($this->getParam('issue', ''));
        Interceptor::ensureNotEmpty($issue, ERROR_PARAM_IS_EMPTY, "issue");

        try{
            ShareClient::setLotteryRun($issue);
            //日志
            $operate = new Operate();
            $operate_content = array(
                'issue'    => $issue,
            );
            $operate->add($this->adminid, 'Lottery_update', 0, 0, $operate_content, '', '', 1);

            Util::jumpMsg("修改成功!", "");
        }catch (Exception $e){
            Util::jumpMsg("修改失败:{$e->getCode()}:{$e->getMessage()}", "", 3);
        }

    }

    public function payAction()
    {
        $id = trim($this->getParam('id', ''));
        Interceptor::ensureNotEmpty($id, ERROR_PARAM_IS_EMPTY, "id");

        try{
            ShareClient::setWinnerPay($id);
            //日志
            $operate = new Operate();
            $operate_content = array(
                'id'    => $id,
            );
            $operate->add($this->adminid, 'Lottery_pay', 0, 0, $operate_content, '', '', 1);

            Util::jumpMsg("发奖成功!", "");
        }catch (Exception $e){
            Util::jumpMsg("发奖失败:{$e->getCode()}:{$e->getMessage()}", "", 3);
        }

    }
    public function addQxcAction()
    {
        $this->display("include/header.html", "lottery/add.html", "include/footer.html");
    }

    public function doaddQxcAction()
    {
        $issue    = trim($this->getParam('issue', ''));
        $ptIssue = trim($this->getParam('pt_issue', ''));
        $startime = trim($this->getParam('startime', ''));
        $endtime  = trim($this->getParam('endtime', ''));
        
        Interceptor::ensureNotEmpty($issue, ERROR_PARAM_IS_EMPTY, "issue");
        Interceptor::ensureNotEmpty($ptIssue, ERROR_PARAM_IS_EMPTY, "pt_issue");
        Interceptor::ensureNotEmpty($startime, ERROR_PARAM_IS_EMPTY, "startime");
        Interceptor::ensureNotEmpty($endtime, ERROR_PARAM_IS_EMPTY, "endtime");

        try{
            ShareClient::addQxcConfig($issue ,$ptIssue, $startime, $endtime);
            //日志
            $operate = new Operate();
            $operate_content = array(
                'issue'    => $issue,
                'pt_issue'    => $ptIssue,
                'startime'    => $startime,
                'endtime'    => $endtime,
            );
            $operate->add($this->adminid, 'lottery_add', 0, 0, $operate_content, '', '', 1);

            Util::jumpMsg("添加成功!", "/lottery/qxc");
        }catch (Exception $e){
            Util::jumpMsg("添加失败:{$e->getCode()}:{$e->getMessage()}", "", 3);
        }

    }
}