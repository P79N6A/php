<?php
class VipController extends BaseController
{
    public function testAction()
    {
        $token = $this->getParam("token");
        $amont = 0.01;

        if(!$token){
            $wxclient = new WxClient();
            $url = $wxclient->getOauthUrl("/vip/test");

            header("Location:{$url}");
            die;
        }
        $jssdk = new Jssdk();
        $signPackage = $jssdk->getSignPackage();

        $share_client = ShareClient::getInstance();
        $share_client->setToken($token);

        $payInfo = $share_client->payPrepare('wx', 'CNY', 'VIP', $amont);
        

        $this->assign("signPackage", $signPackage);
        $this->assign("payInfo", $payInfo);
        $this->display("vip/test.html");
    }

    public function indexAction()
    {
        $token = $this->getParam("token");
        $amont = 0.01;

        if(!$token){
            $wxclient = new WxClient();
            $url = $wxclient->getOauthUrl("/vip/index");

            header("Location:{$url}");
            die;
        }
        $userinfo = $inviter = $configs = array();

        $share_client = ShareClient::getInstance();
        $share_client->setToken($token);
        
        try{
            $userinfo = $share_client->getMyUserinfo();
            $inviter = $share_client->getUserInviter();

            $configs = $share_client->getConfigs('china', 'xcx', '1.0', 'app_config');
            $config = json_decode($configs['app_config']['value'], true);

        }catch(Exception $e){

        }

        $this->assign("userinfo", $userinfo);
        $this->assign("vip", (bool) $userinfo['vip']);
        $this->assign("config", $config);
        $this->assign("inviter", $inviter);

        $this->display("vip/index.html");
    }

    public function createGroupAction()
    {
        $token = $this->getParam("token");
        $orderid = trim(strip_tags($this->getParam("orderid")));

        Interceptor::ensureNotEmpty($token, ERROR_PARAM_IS_EMPTY, "token");
        
        $share_client = ShareClient::getInstance();
        $share_client->setToken($token);

        $res = $share_client->vipCreateGroup($orderid);

        $this->render($res);
    }

    public function groupDetailAction()
    {
        $token = $this->getParam("token");

        Interceptor::ensureNotEmpty($token, ERROR_PARAM_IS_EMPTY, "token");
        
        $share_client = ShareClient::getInstance();
        $share_client->setToken($token);

        $res = $share_client->vipGroupDetail();

        $this->render($res);
    }

}