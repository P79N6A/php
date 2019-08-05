<?php
class ActivityController extends BaseController
{

    //GET
    public function getActivityInfoAction()
    {
        $activityid = $this->getParam('activityid', 0);
        Interceptor::ensureNotFalse($activityid > 0, ERROR_PARAM_INVALID_FORMAT, 'activityid');
        $this->render(Activity::getActivityInfo($activityid));
    }

    //需要登陆
    //GET
    public function getActivityListAction()
    {
        $uid = Context::get('userid', 0);
        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, 'userid');
        $this->render(Activity::getCurrentActivityListByUid($uid));
    }

    //需要登录,
    //POST
    public function registerAction()
    {
        $uid = $uid = Context::get("userid", 0);
        $activityid = $this->getParam('activityid', 0);
        $moduleid = $this->getParam('moduleid', 0);

        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, 'uid');
        Interceptor::ensureNotFalse($activityid > 0, ERROR_PARAM_INVALID_FORMAT, 'activityid');
        Interceptor::ensureNotEmpty($moduleid > 0, ERROR_PARAM_INVALID_FORMAT, 'moduleid');


        $this->render(ActivityApply::register($this, $activityid, $moduleid, $uid));
    }

    //抽奖
    public function lottoAction()
    {
        $uid = $uid = Context::get("userid", 0);
        $activityid = $this->getParam('activityid', 0);
        $moduleid = $this->getParam('moduleid', 0);

        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, 'uid');
        Interceptor::ensureNotFalse($activityid > 0, ERROR_PARAM_INVALID_FORMAT, 'activityid');
        Interceptor::ensureNotEmpty($moduleid > 0, ERROR_PARAM_INVALID_FORMAT, 'moduleid');

        $this->render(ActivityLotto::freeUserDraw($this, $activityid, $moduleid, $uid));

    }

    //打榜投票
    public function voteAction()
    {
        //$uid= $uid = Context::get("userid",0);
        //$activityid=$this->getParam('activityid',0);
        //$moduleid=$this->getParam('moduleid',0);

        //Interceptor::ensureNotFalse($uid>0,ERROR_PARAM_INVALID_FORMAT,'uid' );
        //Interceptor::ensureNotFalse($activityid>0, ERROR_PARAM_INVALID_FORMAT,'activityid');
        //Interceptor::ensureNotEmpty($moduleid>0,ERROR_PARAM_INVALID_FORMAT,'moduleid' );

        $this->render(ActivitySupport::voteSupport($this));
    }

    //排行
    public function rankAction()
    {
        $activityid = $this->getParam('activityid', 0);
        $moduleid = $this->getParam('moduleid', 0);
        $page = $this->getParam('page', 1);
        $page = $page ? $page : 1;

        Interceptor::ensureNotFalse($activityid > 0, ERROR_PARAM_INVALID_FORMAT, 'activityid');
        Interceptor::ensureNotEmpty($moduleid > 0, ERROR_PARAM_INVALID_FORMAT, 'moduleid');

        $this->render(ActivityRank::getRank($this, $activityid, $moduleid, $page));
    }

    //评委晋级
    public function juryPromotionAction()
    {
        $activityid = $this->getParam('activityid', 0);
        $moduleid = $this->getParam('moduleid', 0);
        Interceptor::ensureNotFalse($activityid > 0, ERROR_PARAM_INVALID_FORMAT, 'activityid');
        Interceptor::ensureNotEmpty($moduleid > 0, ERROR_PARAM_INVALID_FORMAT, 'moduleid');

        $this->render(ActivityPromotion::promotion($activityid, $moduleid, $this));
    }

    //活动结果处理，比如奖品发放
    public function resultAction()
    {

    }

    //添加活动
    public function addActivityAction()
    {
        $type = $this->getParam('type', 0);
        $name = $this->getParam('name', '');
        $icon = $this->getParam('icon', '');
        $url = $this->getParam('url', '');
        $vote = $this->getParam('vote', '');
        $online = $this->getParam('online', "N");
        $startime = $this->getParam('startime', '');
        $endtime = $this->getParam('endtime', '');
        $remark = $this->getParam('remark', '');
        $extends = $this->getParam('extends', '');

        Interceptor::ensureNotFalse($type > 0, ERROR_PARAM_INVALID_FORMAT, 'type');
        Interceptor::ensureNotFalse(!empty($name), ERROR_PARAM_INVALID_FORMAT, 'name');
        Interceptor::ensureNotFalse(!empty($icon), ERROR_PARAM_INVALID_FORMAT, 'icon');
        Interceptor::ensureNotFalse(!empty($url), ERROR_PARAM_INVALID_FORMAT, 'url');
        Interceptor::ensureNotFalse(!empty($vote), ERROR_PARAM_INVALID_FORMAT, 'vote');
        Interceptor::ensureNotFalse(!empty($online), ERROR_PARAM_INVALID_FORMAT, 'online');
        Interceptor::ensureNotFalse(!empty($startime), ERROR_PARAM_INVALID_FORMAT, 'startime');
        Interceptor::ensureNotFalse(!empty($endtime), ERROR_PARAM_INVALID_FORMAT, 'endtime');

        $daoActivity = new DAOActivity();
        $this->render($daoActivity->add($type, $name, $icon, $url, $vote, $online, $startime, $endtime, $remark, json_decode($extends)));
    }

    //修改活动
    public function modActivityAction()
    {
        $activityid = $this->getParam('activityid', 0);
        $type = $this->getParam('type', 0);
        $name = $this->getParam('name', '');
        $icon = $this->getParam('icon', '');
        $url = $this->getParam('url', '');
        $vote = $this->getParam('vote', '');
        $online = $this->getParam('online', "N");
        $startime = $this->getParam('startime', '');
        $endtime = $this->getParam('endtime', '');
        $remark = $this->getParam('remark', '');
        $extends = $this->getParam('extends', '');

        Interceptor::ensureNotFalse($activityid > 0, ERROR_PARAM_INVALID_FORMAT, 'activityid');
        Interceptor::ensureNotFalse($type > 0, ERROR_PARAM_INVALID_FORMAT, 'type');

        $daoActivity = new DAOActivity();
        $this->render($daoActivity->mod($activityid, $name, $icon, $url, $vote, $online, $startime, $endtime, $remark, $extends));
    }

    //添加模块
    public function addModuleAction()
    {
        $activityid = $this->getParam('activityid', 0);
        $roundid = $this->getParam('roundid', 0);
        $name = $this->getParam('name', "");
        $type = $this->getParam("type", 0);
        $scripts = $this->getParam('scripts', "");
        $extends = $this->getParam("extends", "");

        Interceptor::ensureNotFalse($activityid > 0, ERROR_PARAM_INVALID_FORMAT, 'activityid');
        Interceptor::ensureNotFalse($roundid > 0, ERROR_PARAM_INVALID_FORMAT, 'roundid');
        Interceptor::ensureNotFalse(!empty($name), ERROR_PARAM_INVALID_FORMAT, 'name');
        Interceptor::ensureNotFalse($type > 0, ERROR_PARAM_INVALID_FORMAT, 'type');

        $this->render(ActivityModule::addModule($activityid, $roundid, $name, $type, $scripts, $extends));
    }

    //修改模块
    public function modModuleAction()
    {
        $moduleid = $this->getParam('moduleid', 0);
        $name = $this->getParam('name', "");
        $scripts = $this->getParam('scripts', "");
        $extends = $this->getParam("extends", "");
        $ext = @json_decode($extends, true);
        if (!$ext) { $ext = [];
        }
        Interceptor::ensureNotFalse($moduleid > 0, ERROR_PARAM_INVALID_FORMAT, 'moduleid');

        $this->render(ActivityModule::modModule($moduleid, $name, $scripts, $ext));
    }

    //添加轮次
    public function addRoundAction()
    {
        $activityid = $this->getParam('activityid', 0);
        $name = $this->getParam('name', '');
        $round = $this->getParam('round', 0);
        $startime = $this->getParam('startime', '');
        $endtime = $this->getParam('endtime', '');
        $extends = $this->getParam('extends', '');

        $ext = @json_decode($extends, true);
        if (!$ext) { $ext = [];
        }
        Interceptor::ensureNotFalse($activityid > 0, ERROR_PARAM_INVALID_FORMAT, 'activityid');
        Interceptor::ensureNotEmpty($name, ERROR_PARAM_INVALID_FORMAT, 'name');
        Interceptor::ensureNotFalse($round > 0, ERROR_PARAM_INVALID_FORMAT, 'round');
        Interceptor::ensureNotEmpty($startime, ERROR_PARAM_INVALID_FORMAT, 'startime');
        Interceptor::ensureNotEmpty($endtime, ERROR_PARAM_INVALID_FORMAT, 'endtime');

        $this->render(ActivityRound::addRound($activityid, $name, $round, $startime, $endtime, $ext));

    }

    //修改轮次
    public function modRoundAction()
    {
        $roundid = $this->getParam('roundid', 0);
        $name = $this->getParam('name', '');
        $round = $this->getParam('round', 0);
        $startime = $this->getParam('startime', '');
        $endtime = $this->getParam('endtime', '');
        $extends = $this->getParam('extends', '');
        $ext = @json_decode($extends, true);
        if (!$ext) { $ext = [];
        }

        Interceptor::ensureNotFalse($roundid > 0, ERROR_PARAM_INVALID_FORMAT, 'roundid');
        $this->render(ActivityRound::modRound($roundid, $name, $round, $startime, $endtime, $ext));
    }

    //删除活动
    public function delActivityAction()
    {
        $activityid=$this->getParam('activityid', 0);
        Interceptor::ensureNotFalse($activityid>0, ERROR_PARAM_INVALID_FORMAT, 'activityid');
        $this->render(Activity::delActivity($activityid));
    }

    //删除轮次
    public function delRoundAction()
    {
        $roundid=$this->getParam("roundid", 0);
        Interceptor::ensureNotFalse($roundid>0, ERROR_PARAM_INVALID_FORMAT, 'roundid');
        $this->render(ActivityRound::delRound($roundid));
    }

    //删除模块
    //注意清理模块关联配置
    public function delModuleAction()
    {
        $moduleid=$this->getParam('moduleid', 0);
        Interceptor::ensureNotFalse($moduleid>0, ERROR_PARAM_INVALID_FORMAT, 'moduleid');
        $this->render(ActivityModule::delModule($moduleid));
    }

    //获取晋级排行榜
    public function getPromotionRankAction()
    {
        $supportModuleId = $this->getParam('supportModuleId', 0);
        $zone = $this->getParam('zone', '');//eg:北京
        $date = $this->getParam('date', '总');
        $type = $this->getParam("type", 0);

        Interceptor::ensureNotFalse($supportModuleId>0, ERROR_PARAM_INVALID_FORMAT, 'supportModuleid');
        //Interceptor::ensureNotEmpty($zone,ERROR_PARAM_INVALID_FORMAT,'zone' );
        //Interceptor::ensureNotEmpty($date,ERROR_PARAM_INVALID_FORMAT,'date' );
        //Interceptor::ensureNotEmpty($type, ERROR_PARAM_INVALID_FORMAT,'type');

        $this->render(ActivityRank::getPromotionRank($supportModuleId, $zone, $date, $type));
    }

    public function testAction()
    {
        $sender = $this->getParam("sender", 0);
        $receiver = $this->getParam("receiver", 0);
        $giftid = $this->getParam('giftid', 0);
        $num = $this->getParam("num", 1);

        $this->render(ActivitySupport::giftSupport($sender, $receiver, $giftid, $num));
    }
}
