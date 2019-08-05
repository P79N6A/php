<?php
class UserController extends BaseController
{
    public function activeAction()
    {
        $source        = trim($this->getParam('source', 'xcx'));
        $code          = trim($this->getParam('code'));
        $iv            = trim($this->getParam('iv'));
        $encryptedData = trim($this->getParam('encryptedData'));
        $inviter       = trim($this->getParam('uid', 0));
        $type          = trim($this->getParam('type' , ''));
        $taskid        = trim($this->getParam('taskid' , ''));

        Interceptor::ensureNotEmpty($source, ERROR_PARAM_IS_EMPTY, 'source');
        Interceptor::ensureNotEmpty($code, ERROR_PARAM_IS_EMPTY, 'code');
        Interceptor::ensureNotEmpty($iv, ERROR_PARAM_IS_EMPTY, 'iv');
        Interceptor::ensureNotEmpty($encryptedData, ERROR_PARAM_IS_EMPTY, 'encryptedData');

        $user     = new User();
        $userinfo = $user->active($source, $code, $iv, $encryptedData, $inviter, $type, $taskid);

        if($userinfo['isnew']){
            //新用户注册任务
            try {
                Task::execute($userinfo['uid'], 1, 1);
            } catch (Exception $e) {}
            if($inviter){
                try {
                    $award = Task::execute($inviter, 5, 1);

                    $message = new Message($inviter);
                    $message->sendMessage(DAOMessage::TYPE_INVITE, array($userinfo['nickname'], $award['award']['grape']));
                } catch (Exception $e) {}
            }
        }

        $this->render($userinfo);
    }

    public function syncAction()
    {
        $uid           = Context::get('userid');
        $source        = trim($this->getParam('source', 'xcx'));
        $code          = trim($this->getParam('code'));
        $iv            = trim($this->getParam('iv'));
        $encryptedData = trim($this->getParam('encryptedData'));

        Interceptor::ensureNotEmpty($iv, ERROR_PARAM_IS_EMPTY, 'iv');
        Interceptor::ensureNotEmpty($encryptedData, ERROR_PARAM_IS_EMPTY, 'encryptedData');

        $user     = new User();
        $userinfo = $user->setUserInfo($uid, $source, $code, $iv, $encryptedData);

        $this->render($userinfo);
    }

    public function getUserInfoAction()
    {
        $loginid = Context::get("userid");
        $uid = (int)$this->getParam("uid",  0);

        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid($uid)");

        $userinfo = User::getUserInfo($uid);
        Interceptor::ensureNotEmpty($userinfo, ERROR_USER_NOT_EXIST);

        $counters = Counter::mixed(
            array(
                Counter::COUNTER_TYPE_FOLLOWERS,
                Counter::COUNTER_TYPE_FOLLOWINGS,
            ),
            array($uid)
        );
        $return = User::format($userinfo);
        $return['followers']  = (int) $counters[$uid][Counter::COUNTER_TYPE_FOLLOWERS];
        $return['followings'] = (int) $counters[$uid][Counter::COUNTER_TYPE_FOLLOWINGS];

        $dao_profile = new DAOProfile($uid);
        $profiles = $dao_profile->getUserProfiles();

        $return['signature'] = (string) $profiles['signature'];
        $return['tag']       = (string) $profiles['tag'];
        $return['social']    = (array) json_decode($profiles['social'], true);
        $return["followed"]  = !! current(Follow::isFollowed($loginid, $uid));

        $this->render($return);
    }

    public function getMyUserInfoAction()
    {
        $uid = Context::get("userid");
        
        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid($uid)");

        $userinfo = User::getUserInfo($uid);
        Interceptor::ensureNotFalse($userinfo, ERROR_LOGINUSER_NOT_EXIST);

        $counters = Counter::mixed(
            array(
                Counter::COUNTER_TYPE_FOLLOWERS,
                Counter::COUNTER_TYPE_FOLLOWINGS,
            ),
            array($uid)
         );

        $return = User::format($userinfo);

        $return['phone']      = (string) $userinfo['phone'];
        $return['credit']     = (int) 0;
        $return['grade']      = (int) 0;
        $return['signature']  = (string) $profiles['signature'];
        $return['followers']  = (int) $counters[$uid][Counter::COUNTER_TYPE_FOLLOWERS];
        $return['followings'] = (int) $counters[$uid][Counter::COUNTER_TYPE_FOLLOWINGS];

        $dao_profile = new DAOProfile($uid);
        $profiles = $dao_profile->getUserProfiles();

        $return['signature'] = (string) $profiles['signature'];
        $return['tag']       = (string) $profiles['tag'];
        $return['social']    = (array) json_decode($profiles['social'], true);

        $this->render($return);
    }

    public function fastLoginAction()
    {
        $uid = Context::get("userid");

        $userinfo = User::getUserInfo($uid);
        Interceptor::ensureNotEmpty($userinfo, ERROR_LOGINUSER_NOT_EXIST);

        $token = Session::getToken($uid);
        $userinfo['token'] = $token;

        $this->render($userinfo);
    }

    public function refreshCodeAction()
    {
        $uid  = Context::get("userid");
        $code = $this->getParam("code");


        Interceptor::ensureNotFalse($uid > 0, ERROR_USER_NOT_LOGIN);
        Interceptor::ensureNotEmpty($code, ERROR_PARAM_IS_EMPTY, 'code');

        User::refreshCode($uid, $code);

        $this->render();
    }
}
?>