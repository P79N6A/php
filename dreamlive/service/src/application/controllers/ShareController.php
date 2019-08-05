<?php
class ShareController extends BaseController
{
    const ROLE_OTHER     = 1;
    const ROLE_ME         = 2;
    const AUTHOR_NEWBIE = 1; // 约定：author为1表示新用户

    protected $roles     = array(self::ROLE_OTHER, self::ROLE_ME);
    protected $types     = array('live', 'before_live', 'replay', 'image', 'video', 'user', 'html5', 'screenshot', 'record');
    protected $targets     = array('wx', 'weibo', 'qq', 'qzone', 'circle','facebook', 'twitter','miniapp');//微信好友、微信朋友圈、微博、QQ、QQ空间

    public function indexAction()
    {
        $type         = trim(strip_tags($this->getParam('type')));        // 开播前、直播、回放、图片、小视频、用户等等
        $author     = $this->getParam('author');        // 资源作者
        $relateid     = $this->getParam('relateid');    // 资源id、直播sn、用户uid等等
        $target     = trim(strip_tags($this->getParam('target')));        // 目的：微信好友、微信朋友圈、微博、QQ、QQ空间
        $title         = trim(strip_tags($this->getParam('title', '')));    // 标题

        // 登录用户uid
        $loginUid = Context::get('userid');
        $loginUid || $loginUid = 0;

        Interceptor::ensureNotFalse(in_array($type, DAOShare::TYPES), ERROR_PARAM_INVALID_FORMAT, 'type');
        Interceptor::ensureNotEmpty($relateid, ERROR_PARAM_IS_EMPTY, 'relateid');
        Interceptor::ensureNotFalse(in_array($target, DAOShare::TARGETS), ERROR_PARAM_INVALID_FORMAT, 'target');

        $role = $author == $loginUid ? self::ROLE_ME : self::ROLE_OTHER;

        // 是否针对author设置了特殊文案标识
        $special = false;

        $share = new Share();
        $contents = $share->get($author, $role, $type, $target, $title, null, $special);

        if (!$contents) {
            // 默认文案处理
            $this->render(array('title' => '', 'content' => '', 'shareid' => 0));
        }

        /*// 新用户分享文案处理 新用户：注册7天内  优化策略：上线时间点7天开始的uid才需要判断是否为新用户
        if (!$special && true) {//$author > (int)SimpleConfig::get('NEWBIE_UID_START')
        $userinfo = User::getUserInfo($author);
        if ($userinfo && strtotime($userinfo['addtime']) >= time() - 86400 * 7){
        $specialContents = $share->getSpecial(self::AUTHOR_NEWBIE, $role, $type, $target, $title);
        $specialContents && $contents = $specialContents;
        }
        }*/

        shuffle($contents);

        $title = $contents[0]['title'];
        $content = $contents[0]['content'];

        $this->render(array('title' => $title, 'content' => $content, 'shareid' => $contents[0]['id']));
    }

    public function addAction()
    {
        $uid         = $this->getParam('uid', 0);
        $role         = $this->getParam('role');
        $type         = $this->getParam('type');
        $target     = $this->getParam('target');
        $titled        = $this->getParam('titled');
        $begin         = $this->getParam('begin');
        $finish     = $this->getParam('finish');
        $starttime     = $this->getParam('starttime');
        $endtime     = $this->getParam('endtime');
        $title         = $this->getParam('title', '');
        $content     = $this->getParam('content');

        Interceptor::ensureNotFalse(in_array($role, $this->roles), ERROR_PARAM_INVALID_FORMAT, 'role');
        Interceptor::ensureNotFalse(in_array($type, DAOShare::TYPES), ERROR_PARAM_INVALID_FORMAT, 'type');
        Interceptor::ensureNotFalse(in_array($target, DAOShare::TARGETS), ERROR_PARAM_INVALID_FORMAT, 'target');
        Interceptor::ensureNotFalse(in_array($titled, array("Y","N"), true), ERROR_PARAM_INVALID_FORMAT, "titled");
        Interceptor::ensureNotFalse(preg_match("/\d{1,2}\:\d{1,2}/", $begin) > 0, ERROR_PARAM_INVALID_FORMAT, 'begin');
        Interceptor::ensureNotFalse(preg_match("/\d{1,2}\:\d{1,2}/", $finish) > 0, ERROR_PARAM_INVALID_FORMAT, 'finish');
        Interceptor::ensureNotFalse(strtotime($starttime), ERROR_PARAM_INVALID_FORMAT, 'starttime');
        Interceptor::ensureNotFalse(strtotime($endtime), ERROR_PARAM_INVALID_FORMAT, 'endtime');
        Interceptor::ensureNotFalse(strlen($title) < 200, ERROR_PARAM_INVALID_FORMAT, 'title');
        Interceptor::ensureNotFalse(strlen($content) > 0 && strlen($content) < 200, ERROR_PARAM_INVALID_FORMAT, 'content');

        $share = new Share();
        $result = $share->add($uid, $role, $type, $target, $titled, $begin, $finish, $starttime, $endtime, $title, $content);
        Interceptor::ensureNotFalse($result, ERROR_SYS_DB_SQL);

        $this->render();
    }

    public function fetchAction()
    {
        $offset     = $this->getParam('offset', 0);
        $num         = $this->getParam('num', 100);
        $uid         = $this->getParam('uid');
        $role         = $this->getParam('role', 0);
        $type         = $this->getParam('type', 0);
        $target     = $this->getParam('target');
        $titled        = $this->getParam('titled', 'Y');

        Interceptor::ensureNotFalse(in_array($titled, array("Y","N"), true), ERROR_PARAM_INVALID_FORMAT, "titled");

        $share = new Share();
        list($offset, $list) = $share->fetch($offset, $num, $uid, $role, $type, $target, $titled);

        $this->render(array('offset' => $offset, 'list' => $list));
    }

    public function updateAction()
    {
        $id         = $this->getParam('id');
        $uid         = $this->getParam('uid', 0);
        $role         = $this->getParam('role');
        $type         = $this->getParam('type');
        $target     = $this->getParam('target');
        $titled        = $this->getParam('titled');
        $begin         = $this->getParam('begin');
        $finish     = $this->getParam('finish');
        $starttime     = $this->getParam('starttime');
        $endtime     = $this->getParam('endtime');
        $title         = $this->getParam('title', '');
        $content     = $this->getParam('content');

        Interceptor::ensureNotFalse(is_numeric($id) && $id > 0, ERROR_PARAM_INVALID_FORMAT, 'id');
        Interceptor::ensureNotFalse(is_numeric($uid) && $uid >= 0, ERROR_PARAM_INVALID_FORMAT, 'id');
        Interceptor::ensureNotFalse(in_array($role, $this->roles), ERROR_PARAM_INVALID_FORMAT, 'role');
        Interceptor::ensureNotFalse(in_array($type, DAOShare::TYPES), ERROR_PARAM_INVALID_FORMAT, 'type');
        Interceptor::ensureNotFalse(in_array($target, DAOShare::TARGETS), ERROR_PARAM_INVALID_FORMAT, 'target');
        Interceptor::ensureNotFalse(in_array($titled, array('N', 'Y'), true), ERROR_PARAM_INVALID_FORMAT, 'titled');
        Interceptor::ensureNotFalse(preg_match("/\d{1,2}\:\d{1,2}/", $begin) > 0, ERROR_PARAM_INVALID_FORMAT, 'begin');
        Interceptor::ensureNotFalse(preg_match("/\d{1,2}\:\d{1,2}/", $finish) > 0, ERROR_PARAM_INVALID_FORMAT, 'finish');
        Interceptor::ensureNotFalse(strtotime($starttime), ERROR_PARAM_INVALID_FORMAT, 'starttime');
        Interceptor::ensureNotFalse(strtotime($endtime), ERROR_PARAM_INVALID_FORMAT, 'endtime');
        Interceptor::ensureNotFalse(strlen($title) < 200, ERROR_PARAM_INVALID_FORMAT, 'title');
        Interceptor::ensureNotFalse(strlen($content) > 0 && strlen($content) < 200, ERROR_PARAM_INVALID_FORMAT, 'content');

        $share = new Share();

        $result = $share->update($id, $uid, $role, $type, $target, $titled, $begin, $finish, $starttime, $endtime, $title, $content);
        Interceptor::ensureNotFalse($result, ERROR_SYS_DB_SQL);

        $this->render();
    }

    public function deleteAction()
    {
        $id = $this->getParam('id');
        Interceptor::ensureNotFalse(is_numeric($id) && $id > 0, ERROR_PARAM_INVALID_FORMAT, 'id');

        $share = new Share();
        $result = $share->delete($id);
        Interceptor::ensureNotFalse($result, ERROR_SYS_DB_SQL);

        $this->render();
    }

    public function mixedAction()
    {
        $types      = $this->getParam('types');
        $relateids  = $this->getParam('relateids');

        ! is_array($types) && $types = explode(',', $types);
        ! is_array($relateids) && $relateids = explode(',', $relateids);

        Interceptor::ensureNotFalse(count($types) * count($relateids) <= 100, ERROR_PARAM_INVALID_FORMAT, 'types*relateids must less than 100');

        $results = Share::mixed($types, $relateids);
        Interceptor::ensureNotFalse($results, ERROR_BIZ_COUNTER_BUSY_RETRY);

        foreach ($results as $relateid => $numbers) {
            foreach ($numbers as $k => $v) {
                if ($v === false || $v < 0) {
                    $v = 0;
                }
                $numbers[$k] = (string) $v;
            }
            $results[$relateid] = $numbers;
        }

        $this->render($results);
    }

    public function callbackAction()
    {
        $uid         = intval($this->getParam('uid'));
        $shareid     = intval($this->getParam('shareid'));
        $type         = trim(strip_tags($this->getParam('type')));
        $relateid     = intval($this->getParam('relateid'));
        $liveid     = trim(strip_tags($this->getParam('liveid')));
        $target     = trim(strip_tags($this->getParam('target')));
        $userid        = Context::get('userid');

        Interceptor::ensureNotEmpty($uid, ERROR_PARAM_IS_EMPTY, 'uid');
        //Interceptor::ensureNotEmpty($shareid, ERROR_PARAM_IS_EMPTY, 'shareid');
        //Interceptor::ensureNotFalse(in_array($type, DAOShare::TYPES), ERROR_PARAM_INVALID_FORMAT, 'type');
        Interceptor::ensureNotEmpty($relateid, ERROR_PARAM_IS_EMPTY, 'relateid');
        Interceptor::ensureNotFalse(in_array($target, DAOShare::TARGETS), ERROR_PARAM_INVALID_FORMAT, 'target');

        if (!empty($shareid)) {
            $share_callback = new DAOShareCallback();
            $result = $share_callback->add($userid, $shareid, $type, $relateid, $target);
            //Interceptor::ensureNotFalse($result, ERROR_SYS_DB_SQL);
            if (empty($type)) {
                $dao_share = new DAOShare();
                $type = $dao_share->getType($shareid);
            }
        }

        if ($type == 'live' && $relateid) {
            if (!empty($userid)) {
                $userinfo = User::getUserInfo(Context::get("userid"));
            } else if (!empty($uid)) {
                $userinfo = User::getUserInfo($uid);
            }

            if (!empty($userinfo)) {
                $live = new Live();
                $liveinfo = $live->getLiveInfo($relateid);
                $user_guard = UserGuard::getUserGuardRedis($userid, $liveinfo['uid']);
                Messenger::sendLiveShare($relateid, $userinfo['nickname']. "分享了直播", Context::get('userid'), $userinfo['nickname'], $userinfo['avatar'], $userinfo['exp'], $userinfo['level'], intval($user_guard));
            }
        } else {
            if (!empty($liveid)) {
                $userinfo = User::getUserInfo($userid);
                $live = new Live();
                $liveinfo = $live->getLiveInfo($liveid);
                $user_guard = UserGuard::getUserGuardRedis($userid, $liveinfo['uid']);
                $bool = Messenger::sendLiveShareNew($liveid, $userinfo['nickname']. "分享了", $userid, $userinfo['nickname'], $userinfo['avatar'], $userinfo['exp'], $userinfo['level'], intval($user_guard), $type);
            }
        }
        $share_model = new Share();
        $share_model->shareCounter($type, $relateid);

        include_once "process_client/ProcessClient.php";
        ProcessClient::getInstance("dream")->addTask("passport_task_execute", array("uid" => Context::get("userid"), "taskid" => Task::TASK_ID_SHARE, "num" => 1, "ext"=>json_encode(array('liveid'=>$relateid))));
        if($type == 'live') {
            ProcessClient::getInstance("dream")->addTask("package_share", ['liveid' => $relateid, 'uid' => Context::get("userid")]);
        }

        $this->render();
    }
}
