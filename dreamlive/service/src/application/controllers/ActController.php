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

        $this->render(ActSummer::getInfo($uid, $stage));
    }
    //获取登录用户详情
    public function getLoginUserInfoAction()
    {
        $userid     = Context::get('userid');
        Interceptor::ensureNotEmpty($userid, ERROR_PARAM_IS_EMPTY, "userid");

        $user_info  = UserRecall::getLoginUserInfo($userid);
        $this -> render($user_info);
    }
    //添加分享用户与点击用户关系
    public function addUserRelationAction()
    {
        $sharid     = $this -> getParam('sharid', '');
        $userid     = $this -> getParam('userid', '');
        $type       = $this -> getParam('type', 0);
        $phone      = $this -> getParam('phones', 0);

        Interceptor::ensureNotEmpty($sharid>0, ERROR_PARAM_IS_EMPTY, "sharid");
        if($phone) { Interceptor::ensureNotFalse(preg_match("/^1[34578]\d{9}$/", $phone), ERROR_PARAM_IS_EMPTY, "phone");
        }
        Interceptor::ensureNotFalse(($userid>0||$phone>0), ERROR_PARAM_IS_EMPTY, "userid或phone{$phone}:{$userid}");

        $this -> render(UserRecall::addUserRelation($sharid, $userid, $type, $phone));
    }

    public function recallActAction()
    {
        $uid= Context::get('userid');
        $uid=$uid?$uid:0;
        $this->render(UserRecall::getList($uid));
    }

    public function yuanxiaoActAction()
    {
        $this->render(ActYuanXiao::getRank());
    }

    public function rbRegisterAction()
    {
        $anchorid=$this->getParam('anchorid', 0);
        $group=$this->getParam('group', 0);
        $this->render(TeamPk::register($anchorid, $group));
    }

    public function rbRankAction()
    {
        $anchorid=$this->getParam("anchorid", 0);
        $uid=$this->getParam("uid", 0);
        $this->render(TeamPk::getRank($anchorid, $uid));
    }

    public function qingMingAction()
    {
        $uid=$this->getParam('uid', 0);
        $this->render(ActQingMing::getRank($uid));
    }
    public function addUserSroceListAction()
    {
        $info = $this -> getParam('info', '');
        $cache      = Cache::getInstance("REDIS_CONF_CACHE");
        $cache->set('crontab_reward_gift_uids', $info);
    }
}