<?php
class PayController extends BaseController
{
    public function vipPrepareAction()
    {
        $amont = 0.01;
        $token = $this->getParam("token");

        Interceptor::ensureNotEmpty($token, ERROR_PARAM_IS_EMPTY, "token");
        
        $share_client = ShareClient::getInstance();
        $share_client->setToken($token);

        $payInfo = $share_client->payPrepare('wx', 'CNY', 'VIP', $amont);

        $this->render($payInfo);
    }

}