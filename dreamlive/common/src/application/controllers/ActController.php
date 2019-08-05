<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/18
 * Time: 18:07
 */
class ActController extends BaseController
{
    public function actChristmasAction()
    {
        $token = trim($this->getCookie("token"));
        $uid=Session::getLoginId($token);
        $uid=$uid?$uid:0;
        $stage=$this->getParam("stage", 0);

        $this->render(ActSpring::getInfo($uid, $stage));
    }

}