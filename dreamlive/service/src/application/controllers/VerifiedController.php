<?php
class VerifiedController extends UserController
{
    public function getVerifiedInfoAction()
    {
        /* {{{认证信息 */
        $uid = Context::get("userid");
        $verifyLog = $dao_verified = array();

        $daoVerifyLog = new DAOVerifyLog();
        $verifyLog = $daoVerifyLog->getLogStatus($uid);
        if($verifyLog['done']=='N') {
            $verifiedStatus = $verifyLog['apply_status'];
        }else if($verifyLog['done']=='Y') {
            $verifiedStatus = $verifyLog['result_status'];
        }else{
            $verifiedStatus = DAOVerifyLog::STATUS_NONE;
        }

        $dao_verified = new DAOVerified();
        $verified_info = $dao_verified->getVerifiedInfo($uid);

        if(!empty($verified_info)) {


            $verifiedinfo = array(
                "realname" => $verified_info["realname"],
                "credentials" => $verified_info["credentials"],
                "mobile" => $verified_info["mobile"],
                "idcard" => $verified_info["idcard"],
                "imgs" => $verified_info["imgs"],
            );
        }
        $verifiedinfo['status'] = $verifiedStatus;

        $this->render($verifiedinfo);
    }
    /* }}} */
    public function modifyApplyAction()
    {
        /*{{{修改认证信息*/
        $credentials = trim(strip_tags($this->getParam("credentials")));
        $realname    = trim(strip_tags($this->getParam("realname")));
        $mobile      = trim(strip_tags($this->getParam("mobile")));
        $idcard      = trim(strip_tags($this->getParam("idcard")));
        $imgs        = trim(strip_tags($this->getParam("imgs")));
        $code        = trim(strip_tags($this->getParam("code")));
        $uid         = (int)$this->getParam("uid");
        //$uid         = Context::get("userid");
        Interceptor::ensureNotEmpty($uid,    ERROR_PARAM_IS_EMPTY, "uid");

        $notice_modify_info = array();
        $notice_modify_info["uid"] = $uid;
        if($realname) {
            $notice_modify_info["realname"] = $realname;
        }

        if ($credentials) {
            $notice_modify_info["credentials"] = $credentials;
        }

        if ($mobile) {
            //Interceptor::ensureNotEmpty($code, ERROR_PARAM_IS_EMPTY, "code");
            //Interceptor::ensureNotFalse(Captcha::verify($mobile, $code, "verify"), ERROR_CODE_INVALID, $mobile . "($code)");
            $notice_modify_info["mobile"] = $mobile;
        }

        if ($imgs) {
            $sImgs = explode(',', $imgs);
            foreach($sImgs as &$img){
                Interceptor::ensureNotFalse(filter_var($img, FILTER_VALIDATE_URL), ERROR_PARAM_INVALID_FORMAT, "studentid_img");
                $img_arr = array();
                $img_arr = parse_url($img);
                $img = $img_arr['path'];
            }
            $imgs = implode(',', $sImgs);

            $notice_modify_info["imgs"] = $imgs;
        }

        if ($idcard) {
            $notice_modify_info["idcard"] = $idcard;
        }

        if($notice_modify_info) {
            $daoVerifyLog = new DAOVerifyLog();
            $daoVerifyLog->addVerifyLog($uid, $notice_modify_info, DAOVerifyLog::STATUS_MODIFYING);
            // 发消息给审核
            include_once 'process_client/ProcessClient.php';
            ProcessClient::getInstance("dream")->addTask("verified_modify", $notice_modify_info);
        }

        $this->render();
    }/*}}}*/

    /* }}} */
    public function addApplyAction()
    {
        /*{{{*/
        $uid         = (int)$this->getParam("uid");
        $credentials = trim(strip_tags($this->getParam("credentials")));
        $realname    = trim(strip_tags($this->getParam("realname")));
        $mobile      = trim(strip_tags($this->getParam("mobile")));
        $code        = trim(strip_tags($this->getParam("code")));
        $idcard      = trim(strip_tags($this->getParam("idcard")));
        $imgs        = trim(strip_tags($this->getParam("imgs")));

        if($mobile) {
            Interceptor::ensureNotEmpty($code,    ERROR_PARAM_IS_EMPTY, "code");
            Interceptor::ensureNotFalse(Captcha::verify($mobile, $code, "verify"), ERROR_CODE_INVALID, $mobile. "($code)");
        }

        if($imgs) {
            $sImgs = explode(',', $imgs);
            foreach($sImgs as &$img){
                $img_arr = array();
                $img_arr = parse_url($img);
                $img = $img_arr['path'];
            }
            $imgs = implode(',', $sImgs);
        }

        $apply_info = array(
            "uid"         => $uid,
            "realname"    => $realname,
            "mobile"      => $mobile,
            "idcard"      => $idcard,
            "credentials" => $credentials,
            "imgs"        => $imgs,
        );


        $daoVerifyLog = new DAOVerifyLog();
        $daoVerifyLog->addVerifyLog($uid, $apply_info, DAOVerifyLog::STATUS_VERIFYING);

        include_once 'process_client/ProcessClient.php';
        ProcessClient::getInstance("dream")->addTask("verified_apply", $apply_info);

        $this->render();
    }
    /* }}} */

    //通过
    public function passedAction()
    {
        $uid             = trim($this->getParam('uid'));
        $type            = trim($this->getParam('type'));
        $realname        = trim($this->getParam('realname'));
        $credentials     = trim($this->getParam('credentials'));
        $mobile          = trim($this->getParam('mobile'));
        $idcard          = trim(strip_tags($this->getParam("idcard")));
        $imgs            = trim(strip_tags($this->getParam("imgs")));

        if ($imgs) {
            $sImgs = explode(',', $imgs);
            foreach($sImgs as &$img){
                $img_arr = array();
                $img_arr = parse_url($img);
                $img = $img_arr['path'];
            }
            $imgs = implode(',', $sImgs);
        }

        $apply_info = array(
            "uid"         => $uid,
            "type"        => $type,
            "realname"    => $realname,
            "mobile"      => $mobile,
            "idcard"      => $idcard,
            "credentials" => $credentials,
            "imgs"        => $imgs,
        );
        $daoVerifyLog = new DAOVerifyLog();
        $daoVerifyLog->setResult($uid, $apply_info, DAOVerifyLog::STATUS_PASSED);

        $user = new User();
        $user->setVerified($uid, true);

        $verified = new DAOVerified();
        $verified->addVerified($uid, $type, $realname, $mobile, $idcard, $credentials, $imgs);

        UserMedal::addV($uid, UserMedal::KIND_V, $type);

        User::reload($uid);
        //TODO 往前台发消息
        $msg = "恭喜！您的加V认证通过啦";
        Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $uid,  '加V认证', $msg, 0, array());

        $this->render();
    }

    //拒绝
    public function refuseAction()
    {

        $uid             = trim($this->getParam('uid'));
        $reason          = trim($this->getParam('reason'));

        $daoVerifyLog = new DAOVerifyLog();
        $daoVerifyLog->setResult($uid, array('reason'=>$reason), DAOVerifyLog::STATUS_REFUSED);

        //TODO 往前台发消息
        $msg = "很遗憾！您的加V认证未通过。".($reason ? "原因：".$reason : ""). "请按照要求重新进行申请。";
        Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $uid,  '加V认证', $msg, 0, array());

        $this->render();
    }


    public function cancelAction()
    {
        /* {{{ */
        $uid = trim($this->getParam("uid"));

        $daoVerifyLog = new DAOVerifyLog();
        $daoVerifyLog->addVerifyLog($uid, '', 0, '', DAOVerifyLog::STATUS_CANCELED, 'Y');

        $user = new User();
        $user->setVerified($uid, false);

        $verified = new DAOVerified();
        $verified->delete($uid);

        UserMedal::delV($uid, UserMedal::KIND_V);

        User::reload($uid);

        //TODO 往前台发消息
        $msg = "您的加V认证已被撤销";
        Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $uid,  '加V认证', $msg, 0, array());

        $this->render();
    }
    /* }}} */
    public function passedModifyAction()
    {
        $uid             = trim($this->getParam('uid'));
        $type            = trim($this->getParam('type'));
        $realname        = trim($this->getParam('realname'));
        $credentials     = trim($this->getParam('credentials'));
        $mobile          = trim($this->getParam('mobile'));
        $idcard          = trim(strip_tags($this->getParam("idcard")));
        $imgs            = trim(strip_tags($this->getParam("imgs")));

        if ($imgs) {
            $sImgs = explode(',', $imgs);
            foreach($sImgs as &$img){
                $img_arr = array();
                $img_arr = parse_url($img);
                $img = $img_arr['path'];
            }
            $imgs = implode(',', $sImgs);
        }

        $apply_info = array(
            "uid"         => $uid,
            "type"        => $type,
            "realname"    => $realname,
            "mobile"      => $mobile,
            "idcard"      => $idcard,
            "credentials" => $credentials,
            "imgs"        => $imgs,
        );
        $daoVerifyLog = new DAOVerifyLog();
        $daoVerifyLog->setResult($uid, $apply_info, DAOVerifyLog::STATUS_MODIFY_PASSED);

        $verified = new DAOVerified();
        $verified->modVerified($uid, $type, $realname, $mobile, $idcard, $credentials, $imgs);

        //TODO 往前台发消息
        $msg = "恭喜！您的加V认证修改通过啦";
        Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $uid,  '加V认证', $msg, 0, array());



        $this->render();
    }

    public function refuseModifyAction()
    {
        /*{{{*/
        $reason          = trim($this->getParam('reason'));
        $uid             = trim($this->getParam('uid'));

        $daoVerifyLog = new DAOVerifyLog();
        $daoVerifyLog->setResult($uid, '', DAOVerifyLog::STATUS_MODIFY_REFUSED);

        $msg = "很遗憾！您的加V认证修改未通过。".($reason ? "原因：".$reason : ""). "请按照要求重新进行申请。";
        Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $uid,  '加V认证', $msg, 0, array());

        $this->render();
    }
    /* }}} */

    //admin添加
    public function adminAddAction()
    {
        $uid             = trim($this->getParam('uid'));
        $type            = trim($this->getParam('type'));
        $realname        = trim($this->getParam('realname'));
        $credentials     = trim($this->getParam('credentials'));
        $mobile          = trim($this->getParam('mobile'));
        $idcard          = trim(strip_tags($this->getParam("idcard")));
        $imgs            = trim(strip_tags($this->getParam("imgs")));

        if ($imgs) {
            $sImgs = explode(',', $imgs);
            foreach($sImgs as &$img){
                $img_arr = array();
                $img_arr = parse_url($img);
                $img = $img_arr['path'];
            }
            $imgs = implode(',', $sImgs);
        }

        $verified = new DAOVerified();
        $verified->setDebug(true);
        $verified->addVerified($uid, $type, $realname, $mobile, $idcard, $credentials, $imgs);

        $user = new User();
        $user->setVerified($uid, true);

        $daoVerifyLog = new DAOVerifyLog();

        $daoVerifyLog = new DAOVerifyLog();
        $verifyLog = $daoVerifyLog->getLogStatus($uid);
        if($verifyLog['done']=='N') {
            $daoVerifyLog->setResult($uid, '', DAOVerifyLog::STATUS_PASSED, 'Y');
        }else{
            $apply_info = array(
                "uid"         => $uid,
                "type"        => $type,
                "realname"    => $realname,
                "mobile"      => $mobile,
                "idcard"      => $idcard,
                "credentials" => $credentials,
            );
            $daoVerifyLog->addVerifyLog($uid, '', 0, $apply_info, DAOVerifyLog::STATUS_PASSED, 'Y');
        }

        UserMedal::addV($uid, UserMedal::KIND_V, $type);

        User::reload($uid);

        //TODO 往前台发消息
        $msg = "恭喜！您已获得加V认证";
        Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $uid,  '加V认证', $msg, 0, array());

        $this->render();
    }

    //运营修改
    public function adminModifyAction()
    {
        $uid             = trim($this->getParam('uid'));
        $type            = trim($this->getParam('type'));
        $realname        = trim($this->getParam('realname'));
        $credentials     = trim($this->getParam('credentials'));
        $mobile          = trim($this->getParam('mobile'));
        $idcard          = trim(strip_tags($this->getParam("idcard")));

        $apply_info = array(
            "uid"         => $uid,
            "type"        => $type,
            "realname"    => $realname,
            "mobile"      => $mobile,
            "idcard"      => $idcard,
            "credentials" => $credentials,
        );
        $daoVerifyLog = new DAOVerifyLog();
        $daoVerifyLog->addVerifyLog($uid, '', 0, $apply_info, DAOVerifyLog::STATUS_ADMIN_MODIFY, 'Y');

        $verified = new DAOVerified();
        $verified->modVerified($uid, $type, $realname, $mobile, $idcard, $credentials);

        UserMedal::addV($uid, UserMedal::KIND_V, $type);

        User::reload($uid);

        //TODO 往前台发消息

        $this->render();
    }

    /**
     * 提现认证信息
     */
    function withdrawalAction()
    {
        //$uid = trim($this->getParam('uid')); //用户uid
        $uid  = Context::get('userid');
        $realname = trim($this->getParam('realname')); //真实姓名
        $mobile = trim($this->getParam('mobile')); //手机号码
        $code = trim($this->getParam('code')); //验证码
        $idcard = trim($this->getParam('idcard')); //身份证号码
        $img_a = trim(strip_tags($this->getParam('img_a'))); //身份证正面
        $img_b = trim(strip_tags($this->getParam('img_b'))); //身份证反面
        $img_s = trim(strip_tags($this->getParam('img_s'))); //手持身份证

        //如果手机号码不为空，则校验
        if($mobile) {
            Interceptor::ensureNotEmpty($code, ERROR_PARAM_IS_EMPTY, 'code');
            Interceptor::ensureNotFalse(Captcha::verify($mobile, $code, 'bindwithdraw'), ERROR_CODE_INVALID, $mobile. "($code)");
        }

        //实例化DAO类
        $dao_verify_Withdrawal = new DAOVerifiedWithdrawal();

        //数据存在返回，否则插入
        if($result = $dao_verify_Withdrawal->getItems($uid)) {
            $data['uid'] = $uid;
            $data['realname'] = $realname;
            $data['status'] = DAOVerifiedWithdrawal::STATUS_NONE; //待审核
            $data['mobile'] = $mobile;
            $data['idcard'] = $idcard;
            $data['img_a'] = $img_a;
            $data['img_b'] = $img_b;
            $data['img_s'] = $img_s;
            $data['modtime'] = date('Y-m-d H:i:s');
            $result = $dao_verify_Withdrawal->modify($uid, $data);
            $this->render($result);
        }else{
            $data['uid'] = $uid;
            $data['realname'] = $realname;
            $data['status'] = DAOVerifiedWithdrawal::STATUS_NONE; //待审核
            $data['mobile'] = $mobile;
            $data['idcard'] = $idcard;
            $data['img_a'] = $img_a;
            $data['img_b'] = $img_b;
            $data['img_s'] = $img_s;

            $result = $dao_verify_Withdrawal->add($data);
            $this->render($result);
        }
    }

    /**
     * 获取提现认证信息
     */
    function getWithdrawalVerifiedInfoAction()
    {
        //获取参数
        //$uid = trim($this->getParam('uid'));
        $uid = Context::get('userid'); //用户uid

        //实例化DAO类
        $dao_verify_Withdrawal = new DAOVerifiedWithdrawal();
        $result = $dao_verify_Withdrawal->getItems($uid);

        //未提交过认证信息处理
        if(!$result) {
            $result['status'] = 3; //未提交过认证信息
        }

        $this->render($result);
    }

    /**
     * 获取提现认证状态
     */
    function getWithdrawalVerifiedStatusAction()
    {
        //获取参数
        $uid = trim($this->getParam('uid'));

        //实例化DAO类
        $dao_verify_Withdrawal = new DAOVerifiedWithdrawal();
        $result = $dao_verify_Withdrawal->getItems($uid);
        $data['status'] = isset($result['status']) ? $result['status'] : 0;
        $this->render($data);
    }

    /**
     * 修改提现认证状态
     */
    function modifyWithdrawalStatusAction()
    {
        $uid = trim($this->getParam('uid')); //用户uid
        $status = trim($this->getParam('status')); //状态
        $note = trim($this->getParam('note')); //备注

        //实例化DAO类
        $dao_verify_Withdrawal = new DAOVerifiedWithdrawal();

        $data['status'] = $status;
        $data['note'] = $note;

        $result = $dao_verify_Withdrawal->modify($uid, $data);
        $this->render($result);
    }

}
?>
