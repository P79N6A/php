<?php
class DepositController extends BaseController
{
    public function __construct()
    {
        Consume::start();
        if (strstr($_SERVER['REQUEST_URI'], 'notify')) {
        } else {
            $this->init();
        }


    }
    
    public function prepareAction()
    {
        //Interceptor::ensureNotFalse(false, ERROR_BIZ_PAYMENT_DEPOSIT_CLOSE);
        $uid      = Context::get("userid");
        $source   = $this->getParam('source');
        $currency = $this->getParam('currency')?$this->getParam('currency'):'CNY';
        $amount   = $this->getParam('amount');

        $user = new User();
        $userinfo = $user->getUserInfo($uid);
        Interceptor::ensureNotFalse(isset($userinfo['uid']), ERROR_USER_NOT_EXIST);

        $dao_profile = new DAOProfile($uid);
        $frozen_ticket = $dao_profile->getUserProfile('frozenTicket');
        Interceptor::ensureNotFalse((!($frozen_ticket == 'Y')), ERROR_BIZ_PAYMENT_TICKET_FROZEN, "uid"); //冻结票, 不能充值

        
        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");
        Interceptor::ensureNotEmpty($amount, ERROR_PARAM_IS_EMPTY, 'amount');
        Interceptor::ensureNotEmpty($source, ERROR_PARAM_IS_EMPTY, 'source');

        $deposit = new Deposit();
        $sign = $deposit->prepare($uid, $source, $currency, $amount);

        $this->render($sign);
    }

    public function notifyAction()
    {
        $source   = $this->getParam('source');
        $deposit = new Deposit();
        $result  = $deposit->notify($source);

        $this->render(array("result"=>$result));
    }

    public function getDepositListAction()
    {
        $uid    = Context::get("userid");
        $offset = (int) $this->getParam("offset")?(int) $this->getParam("offset"):PHP_INT_MAX;
        $num    = (int) $this->getParam("num", 20);

        Interceptor::ensureFalse($num > 200, ERROR_PARAM_INVALID_FORMAT, "num");

        $deposit = new Deposit();
        list($total, $list, $offset) = $deposit->getDepositList($uid, $offset, $num);

        $this->render(
            array(
            'list' => $list,
            'total' => $total,
            'offset' => $offset,
            )
        );
    }

    public function verifyAction()
    {
        $orderid  = $this->getParam('orderid');
        $uid = Context::get("userid");

        Interceptor::ensureNotEmpty($orderid, ERROR_PARAM_IS_EMPTY, 'orderid');

        $deposit = new Deposit();
        $deposit_info = $deposit->getDepositInfo($orderid);
        $result = $deposit->verify($orderid, $deposit_info['source']);

        $this->render(array("result"=>$result));
    }

    public function exchangeRateAction()
    {
        $this->render(Deposit::exchangeRate());
    }


    //为后台提供的直接加钻接口
    public function operaterPlusAction()
    {
        $uid         = $this->getParam('uid');
        $source      = $this->getParam('source')?$this->getParam('source'):'taobao';
        $key         = $this->getParam('key');
        $amount      = (int)$this->getParam('amount');
        $deposit_num = (int)$this->getParam('deposit_num');
        $tradeid     = (int)$this->getParam('tradeid');
        $operater    = $this->getParam('adminid');

        $userinfo = User::getUserInfo($uid);

        Interceptor::ensureNotEmpty($uid, ERROR_PARAM_IS_EMPTY, 'uid');
        Interceptor::ensureFalse(!$userinfo, ERROR_USER_NOT_EXIST, "uid");
        Interceptor::ensureNotEmpty($operater, ERROR_PARAM_IS_EMPTY, 'operater');
        Interceptor::ensureNotEmpty($amount, ERROR_PARAM_IS_EMPTY, 'amount');
        Interceptor::ensureFalse($key != 'yjjertert345krtwerwertwert', ERROR_PARAM_INVALID_FORMAT, "key");
        Interceptor::ensureNotEmpty($deposit_num, ERROR_PARAM_IS_EMPTY, 'deposit_num');

        $dao_profile = new DAOProfile($uid);
        $frozen_ticket = $dao_profile->getUserProfile('frozenTicket');
        Interceptor::ensureNotFalse((!($frozen_ticket == 'Y')), ERROR_BIZ_PAYMENT_TICKET_FROZEN, "uid"); //冻结票, 不能充值


        $deposit = new Deposit();
        $deposit_info = $deposit->operaterPlus($uid, $source, $amount, $deposit_num, $tradeid, $operater);

        $account = new Account();
        $result = $account->getBalance($uid, Account::CURRENCY_DIAMOND);
        
        $this->render($result);
    }



    public function setTokenAction()
    {

        $token  = $this->getParam('token');

        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wxd42f1708840ee63d&secret=0f7758d71e900ec9387d689b8890ba8b";
        $result = self:: getCurlData($url, []);
        $token  = json_decode($result, true);

        $config = new Config();
        $result = $config->setConfig(325, 'china', "payment_token", $token['access_token'], 7200, 'server', '1.0.0.0', '1000000');

        $this->render($result);
    }

    public function getTokenAction()
    {
        $config = new Config();
        $config_info = $config->getConfig('china', "payment_token", "server", '1.0.0.0');

        $this->render($config_info);
    }

    public function setJsTokenAction()
    {
        $config = new Config();
        $access_token = $config->getConfig('china', "payment_token", "server", '1.0.0.0');

        $jsurl = "https://api.weixin.qq.com/cgi-bin/ticket/getticket";
        $url   = $jsurl . "?type=jsapi&access_token=" . $access_token['value'];
        $result = self:: getCurlData($url, []);
        $json = json_decode($result, true);

        $config->setConfig(326, 'china', "payment_js_token", $json['ticket'], 7200, 'server', '1.0.0.0', '1000000');

        $this->render($json);
    }

    public function getJsTokenAction()
    {
        $config = new Config();
        $config_info = $config->getConfig('china', "payment_js_token", "server", '1.0.0.0');

        $this->render($config_info);
    }

    public static function getCurlData($url, $xml, $useCert = false, $second = 30)
    {
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);//严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, false);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
        }
    }


}
?>
