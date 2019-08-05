<?php
class ArtistVerifiedController extends BaseController
{
    public function applyAction()
    {
        $uid        = Context::get("userid");
        $reason     = trim(strip_tags($this->getParam("reason")));
        $wb_rid     = intval($this->getParam("openid"));
        $contact    = trim(strip_tags($this->getParam("contact")));
        $artist_img = trim(strip_tags($this->getParam("artist_img")));
        $supplementary = trim(strip_tags($this->getParam("supplementary")));

        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");
        Interceptor::ensureNotFalse(strlen($reason) > 0, ERROR_PARAM_INVALID_FORMAT, "reason");
        Interceptor::ensureNotFalse(strlen($contact) > 0, ERROR_PARAM_INVALID_FORMAT, "contact");
        Interceptor::ensureNotFalse(filter_var($artist_img, FILTER_VALIDATE_URL), ERROR_PARAM_INVALID_FORMAT, "artist_img");

        $daoVerifiedArtist = new DAOVerifiedArtist();
        $artist_info = $daoVerifiedArtist->getVerify($uid);
        if (empty($artist_info)) {
            $daoVerifiedArtist->addVerify($uid, $reason, $wb_rid, $contact, $artist_img, $supplementary, DAOVerifiedArtist::VERIFING);
        }else{
            if($artist_info['status']==DAOVerifiedArtist::VERIFY_FAIL) {
                $daoVerifiedArtist->updateVerify($uid, $reason, $wb_rid, $contact, $artist_img, $supplementary, DAOVerifiedArtist::VERIFING);
            }elseif($artist_info['status']==DAOVerifiedArtist::VERIFIED) {
                $daoVerifiedArtist->applyModify($uid, $reason, $wb_rid, $contact, $artist_img, $supplementary);
            }
        }

        $this->render();
    }

    public function getVerifiedInfoAction()
    {
        $uid = Context::get("userid");
        $daoVerifiedArtist = new DAOVerifiedArtist();
        $artist_info = $daoVerifiedArtist->getVerify($uid);

        $this->render(
            array(
            'reason'=>$artist_info['reason'],
            'openid'=>$artist_info['wb_rid'],
            'contact'=>$artist_info['contact'],
            'artist_img'=>$artist_info['artist_img'],
            )
        );
    }

    public function verifiedArtistAction()
    {
        $passed         = intval($this->getPost('passed', 0));
        $uid            = (int)$this->getPost("uid");
        $reason       = trim(strip_tags($this->getPost("reason")));
        $wb_rid           = trim(strip_tags($this->getParam("wb_rid")));
        $contact         = trim(strip_tags($this->getPost("contact")));
        $artist_img   = trim(strip_tags($this->getPost("artist_img")));
        $supplementary = trim(strip_tags($this->getParam("supplementary")));
        $refuse_reason = trim(strip_tags($this->getParam("refuse_reason")));

        $status         = $passed ? DAOVerifiedArtist::VERIFIED : DAOVerifiedArtist::VERIFY_FAIL;

        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");
        if($passed) {
            Interceptor::ensureNotFalse(strlen($reason) > 0, ERROR_PARAM_INVALID_FORMAT, "reason");
            Interceptor::ensureNotFalse(strlen($contact) > 0, ERROR_PARAM_INVALID_FORMAT, "contact");
            Interceptor::ensureNotFalse(filter_var($artist_img, FILTER_VALIDATE_URL), ERROR_PARAM_INVALID_FORMAT, "artist_img");
        }

        $daoVerifiedArtist = new DAOVerifiedArtist();
        $artist_info = $daoVerifiedArtist->getVerify($uid);
        $status = $passed ? DAOVerifiedArtist::VERIFIED : DAOVerifiedArtist::VERIFY_FAIL;

        if (empty($artist_info)) {
            $daoVerifiedArtist->addVerify($uid, $reason, $wb_rid, $contact, $artist_img, $supplementary, $status);
        }else{
            if($artist_info['status']==DAOVerifiedArtist::MODIFYVERIFING) {
                $status = DAOVerifiedArtist::VERIFIED;
            }
            $daoVerifiedArtist->updateVerify($uid, $reason, $wb_rid, $contact, $artist_img, $supplementary, $status);
        }

        if ($passed) {
            UserMedal::addUserMedal($uid, UserMedal::KIND_V, 'purple');
            $msg = $artist_info['status']==DAOVerifiedArtist::MODIFYVERIFING ? "恭喜！您的艺人认证修改通过啦" : "恭喜！您的艺人认证通过啦";
        }else{
            $msg = $artist_info['status']==DAOVerifiedArtist::MODIFYVERIFING ? "很遗憾！您的艺人认证信息修改未通过。".($refuse_reason ? "原因：".$refuse_reason : ""). "。" : "很遗憾！您的艺人认证未通过。".($refuse_reason ? "原因：".$refuse_reason : ""). "请按照要求重新进行申请。";
        }

        User::reload($uid);

        Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $uid,  '艺人认证', $msg, 0, array());

        $this->render();
    }

    public function cancelArtistAction()
    {
        $uid = trim($this->getPost("uid"));
        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");
        $daoVerifiedArtist = new DAOVerifiedArtist();
        $artist_info = $daoVerifiedArtist->getVerify($uid);
        Interceptor::ensureNotEmpty($artist_info, ERROR_USER_NOT_EXIST);
        Interceptor::ensureNotFalse(!empty($artist_info['status']), ERROR_ARTIST_APPLY_FIRST);

        $daoVerifiedArtist->deleteVerify($uid);

        $msg = "您的艺人认证已被取消";
        UserMedal::delUserMedal($uid, UserMedal::KIND_V);

        User::reload($uid);

        Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $uid,  '艺人认证', $msg, 0, array());

        $this->render();
    }
}
?>
