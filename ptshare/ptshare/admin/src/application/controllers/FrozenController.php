<?php
class FrozenController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->setAuthId('auth_user_frozen');
    }

    public function frozenAction()
    {
        $uid = intval($this->getParam("uid"));
        $reason = trim(strip_tags($this->getParam("reason")));
        Interceptor::ensureNotEmpty($uid, ERROR_PARAM_IS_EMPTY, "uid");
        Interceptor::ensureNotEmpty($reason, ERROR_PARAM_IS_EMPTY, "reason");

        try{
            $frozen = new Frozen();
            $frozen->frozen($uid, $reason);
        }catch(Exception $e){
            Interceptor::ensureNotFalse(false, ERROR_CUSTOM, "冻结失败");
        }

        //日志
        $operate = new Operate();
        $content = array();
        $operate_name = 'user_frozen';
        $operate_uid = $uid;
        $operate->add($this->adminid, $operate_name, 1, $uid, $content, '', $reason, 1, $operate_uid);

        $this->render();
    }

    public function unfrozenAction()
    {
        $uid = intval($this->getParam("uid"));
        Interceptor::ensureNotEmpty($uid, ERROR_PARAM_IS_EMPTY, "uid");

        try{
            $frozen = new Frozen();
            $frozen->unfrozen($uid);
        }catch(Exception $e){
            Interceptor::ensureNotFalse(false, ERROR_CUSTOM, "解冻失败");
        }

        //日志
        $operate = new Operate();
        $content = array();
        $operate_name = 'user_unfrozen';
        $operate_uid = $uid;
        $reason = '';
        $operate->add($this->adminid, $operate_name, 1, $uid, $content, '', $reason, 1, $operate_uid);

        $this->render();
    }
}
