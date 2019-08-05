<?php
class UserController extends BaseController
{
    public function activeAction()
    {
        $wxclient = new WxClient();
        $url = $wxclient->getOauthUrl("/vip/index");

        header("Location:{$url}");
    }

    public function vipAction()
    {

        $this->display("vip/index.html");
    }

}