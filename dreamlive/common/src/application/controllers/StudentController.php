<?php
class StudentController extends BaseController
{
    public function applyAction()
    {
        $uid = Context::get("userid");
        $realname    = trim(strip_tags($this->getParam("realname")));
        $school      = trim(strip_tags($this->getParam("school")));
        $year      = trim(strip_tags($this->getParam("year")));
        $studentidImg        = trim(strip_tags($this->getParam("imgs")));

        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");
        Interceptor::ensureNotFalse(strlen($realname) > 0, ERROR_PARAM_INVALID_FORMAT, "realname");
        Interceptor::ensureNotFalse(strlen($school) > 0, ERROR_PARAM_INVALID_FORMAT, "school");
        Interceptor::ensureNotFalse(filter_var($studentidImg, FILTER_VALIDATE_URL), ERROR_PARAM_INVALID_FORMAT, "studentid_img");

        $daoVerifiedStudent = new DAOVerifiedStudent();
        $student_info = $daoVerifiedStudent->getVerify($uid);
        if (empty($student_info)) {
            $daoVerifiedStudent->addVerify($uid, $realname, $school, $year, $studentidImg, '', DAOVerifiedStudent::VERIFING);
        }else{
            if($student_info['status']==DAOVerifiedStudent::VERIFY_FAIL) {
                $daoVerifiedStudent->updateVerify($uid, $realname, $school, $year, $studentidImg, '', DAOVerifiedStudent::VERIFING);
            }elseif($student_info['status']==DAOVerifiedStudent::VERIFIED) {
                $daoVerifiedStudent->applyModify($uid, $realname, $school, $year, $studentidImg);
            }
        }

        $this->render();
    }

    public function getVerifiedInfoAction()
    {
        $uid = Context::get("userid");
        $daoVerifiedStudent = new DAOVerifiedStudent();
        $student_info = $daoVerifiedStudent->getVerify($uid);

        $this->render(
            array(
            'realname'=>$student_info['realname'],
            'school'=>$student_info['school'],
            'year'=>$student_info['year'],
            'imgs'=>$student_info['studentid_img'],
            )
        );
    }

    public function verifiedStudentAction()
    {
        $passed         = intval($this->getPost('passed', 0));
        $uid            = (int)$this->getPost("uid");
        $realname       = trim(strip_tags($this->getPost("realname")));
        $year           = trim(strip_tags($this->getParam("year")));
        $school         = trim(strip_tags($this->getPost("school")));
        $studentidImg   = trim(strip_tags($this->getPost("imgs")));
        $reason         = trim(strip_tags($this->getPost("reason", "")));
        $status         = $passed ? DAOVerifiedStudent::VERIFIED : DAOVerifiedStudent::VERIFY_FAIL;

        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");
        if($passed) {
            Interceptor::ensureNotFalse(strlen($realname) > 0, ERROR_PARAM_INVALID_FORMAT, "realname");
            Interceptor::ensureNotFalse(strlen($year) > 0, ERROR_PARAM_INVALID_FORMAT, "year");
            Interceptor::ensureNotFalse(strlen($school) > 0, ERROR_PARAM_INVALID_FORMAT, "school");
        }

        $sImgs = explode(',', $studentidImg);
        foreach($sImgs as $img){
            Interceptor::ensureNotFalse(filter_var($img, FILTER_VALIDATE_URL), ERROR_PARAM_INVALID_FORMAT, "studentid_img");
        }

        $daoVerifiedStudent = new DAOVerifiedStudent();
        $student_info = $daoVerifiedStudent->getVerify($uid);
        $status = $passed ? DAOVerifiedStudent::VERIFIED : DAOVerifiedStudent::VERIFY_FAIL;

        if (empty($student_info)) {
            $daoVerifiedStudent->addVerify($uid, $realname, $school, $year, $studentidImg, $reason, $status);
        }else{
            if($student_info['status']==DAOVerifiedStudent::MODIFYVERIFING) {
                $status = DAOVerifiedStudent::VERIFIED;
            }
            $daoVerifiedStudent->updateVerify($uid, $realname, $school, $year, $studentidImg, $reason, $status);
        }

        if ($passed) {
            UserMedal::addUserMedal($uid, UserMedal::KIND_STUDENT, 1);
            $msg = $student_info['status']==DAOVerifiedStudent::MODIFYVERIFING ? "恭喜！您的学生认证修改通过啦" : "恭喜！您的学生认证通过啦";
        }else{
            $msg = $student_info['status']==DAOVerifiedStudent::MODIFYVERIFING ? "很遗憾！您的学生认证信息修改未通过。".($reason ? "原因：".$reason : ""). "。" : "很遗憾！您的学生认证未通过。".($reason ? "原因：".$reason : ""). "请按照要求重新进行申请。";
        }

        User::reload($uid);

        Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $uid,  '学生认证', $msg, 0, array());

        $this->render();
    }

    public function cancelStudentAction()
    {
        $uid = trim($this->getPost("uid"));
        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");
        $daoVerifiedStudent = new DAOVerifiedStudent();
        $student_info = $daoVerifiedStudent->getVerify($uid);
        Interceptor::ensureNotEmpty($student_info, ERROR_USER_NOT_EXIST);
        Interceptor::ensureNotFalse(!empty($student_info['status']), ERROR_STUDENT_APPLY_FIRST);

        $daoVerifiedStudent->deleteVerify($uid);

        $msg = "您的学生认证已被取消";
        UserMedal::delUserMedal($uid, UserMedal::KIND_STUDENT);

        User::reload($uid);

        Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $uid,  '学生认证', $msg, 0, array());

        $this->render();
    }
}
?>
