<?php

class FamilyApply
{
    const EXPIRETIME = '+5 year';

    public function applyEmploye($uid, $familyid, $authorid, $type, $value, $old_value, $reason)
    {
        $result = [];

        switch ($type) {
        case 1:
            // 加入公会
            $result = $this->applyJoin($uid, $familyid);
            break;
        case 3:
            // 公会长申请变更提现比例
            $result = $this->applyAuthorPercent($uid, $authorid, $value, $reason);
            break;
        case 4:
            // 公会长移出公会成员
            $result = $this->applyRemove($uid, $authorid, $reason);
            break;
        default:
            break;
        }

        return $result;
    }

    private function applyJoin($authorid, $familyid)
    {
        Interceptor::ensureNotFalse(preg_match('/^[1-9]\d*$/', $familyid) > 0, ERROR_PARAM_INVALID_FORMAT, 'familyid');
        $family_model = new Family();
        $family_info = $family_model->getFamilyInfoById($familyid);
        Interceptor::ensureNotEmpty($family_info, ERROR_BIZ_PAYMENT_FAMILY_NOT_EXIST);

        $employe = new DAOEmploye();
        $employe_info = $employe->getEmployeByuid($authorid);
        Interceptor::ensureEmpty($employe_info, ERROR_BIZ_PAYMENT_FAMILY_MEMBER_INVALID);

        $apply = new DAOFamilyApply();
        $apply_info = $apply->getApplicantByStatus($authorid, 'APPLY');
        Interceptor::ensureEmpty($apply_info, ERROR_BIZ_PAYMENT_FAMILY_ISAPPLY);

        $owner = $family_info['owner'];
        $result = $apply->add($familyid, $authorid, $owner, 1, '', '', '主播申请加入公会');

        if ($result) {
            $this->sendMessage(1, 'APPLY', $authorid, $family_info['owner']);
        }

        return ['applyid' => $result];
    }

    private function applyAuthorPercent($owner, $authorid, $value, $reason)
    {
        Interceptor::ensureNotFalse(preg_match('/^[1-9]\d*$/', $authorid) > 0, ERROR_PARAM_INVALID_FORMAT, 'authorid');

        $employe = new DAOEmploye();
        $owner_info = $employe->getEmployeByuid($owner);

        Interceptor::ensureNotEmpty($owner_info, ERROR_BIZ_PAYMENT_FAMILY_MEMBER_INVALID);

        $familyid = $owner_info['familyid'];
        $family = new DAOFamily();
        $family_info = $family->getFamilyInfoById($familyid);
        Interceptor::ensureNotEmpty($family_info, ERROR_BIZ_PAYMENT_FAMILY_NOT_EXIST);
        Interceptor::ensureNotFalse($owner == $family_info['owner'], ERROR_BIZ_PAYMENT_FAMILY_OWNER);
        Interceptor::ensureNotFalse(preg_match('/^[1-9]\d*$/', $value) > 0, ERROR_PARAM_INVALID_FORMAT, 'value');
        Interceptor::ensureNotFalse($value > 0, ERROR_PARAM_INVALID_FORMAT, 'value');
        // 最大分成比例
        $family_percent = $family_info['family_percent'];
        Interceptor::ensureNotFalse($value <= $family_percent, ERROR_BIZ_PAYMENT_FAMILY_AUTHOR_MAXPERCENT);
        Interceptor::ensureNotFalse($value <= $family_info['author_maxpercent'], ERROR_BIZ_PAYMENT_FAMILY_AUTHOR_MAXPERCENT);

        $author_info = $employe->getEmployeByuid($authorid);
        Interceptor::ensureNotFalse($owner_info['familyid'] == $author_info['familyid'], ERROR_BIZ_PAYMENT_FAMILY_MEMBER_INVALID);

        $old_value = $author_info['global_percent'];

        $apply = new DAOFamilyApply;
        $apply_info = $apply->getApproverByStatus($authorid, 'APPLY');
        Interceptor::ensureEmpty($apply_info, ERROR_BIZ_PAYMENT_FAMILY_ISAPPLY);

        $result = $apply->add($familyid, $owner, $authorid, 3, $value, $old_value, $reason);

        if ($result) {
            $this->sendMessage(3, 'APPLY', $owner, $authorid);
        }

        return ['applyid' => $result];
    }

    private function applyRemove($owner, $authorid, $reason)
    {
        Interceptor::ensureNotFalse(preg_match('/^[1-9]\d*$/', $authorid) > 0, ERROR_PARAM_INVALID_FORMAT, 'authorid');

        $employe = new DAOEmploye();
        $owner_info = $employe->getEmployeByuid($owner);

        Interceptor::ensureNotEmpty($owner_info, ERROR_BIZ_PAYMENT_FAMILY_MEMBER_INVALID);

        $familyid = $owner_info['familyid'];
        $family = new DAOFamily();
        $family_info = $family->getFamilyInfoById($familyid);
        Interceptor::ensureNotEmpty($family_info, ERROR_BIZ_PAYMENT_FAMILY_NOT_EXIST);
        Interceptor::ensureNotFalse($owner == $family_info['owner'], ERROR_BIZ_PAYMENT_FAMILY_OWNER);

        $author_info = $employe->getEmployeByuid($authorid);
        Interceptor::ensureNotFalse($owner_info['familyid'] == $author_info['familyid'], ERROR_BIZ_PAYMENT_FAMILY_MEMBER_INVALID);

        // 公会长不可操作
        Interceptor::ensureNotFalse($owner != $authorid, ERROR_BIZ_PAYMENT_FAMILY_NOTOWNER);

        $apply = new DAOFamilyApply;
        $apply_info = $apply->getApproverByStatus($authorid, 'APPLY');
        Interceptor::ensureEmpty($apply_info, ERROR_BIZ_PAYMENT_FAMILY_ISAPPLY);

        $result = $apply->add($familyid, $owner, $authorid, 4, '', '', $reason);

        if ($result) {
            $this->sendMessage(4, 'APPLY', $owner, $authorid);
        }

        return ['applyid' => $result];
    }

    public function acceptEmploye($uid, $applyid)
    {
        $apply = new DAOFamilyApply();
        $apply_info = $apply->getApplyInfo($applyid);

        Interceptor::ensureNotEmpty($apply_info, ERROR_PARAM_INVALID_FORMAT, 'applyid');
        Interceptor::ensureNotFalse($uid == $apply_info['approver'], ERROR_PARAM_INVALID_FORMAT, 'applyid');
        Interceptor::ensureNotFalse($apply_info['apply_status'] == 'APPLY', ERROR_PARAM_INVALID_FORMAT, 'applyid');

        $result = [];
        switch ($apply_info['type']) {
        case 1:
            // 加入公会
            $result = $this->acceptJoin($apply_info);
            break;
        case 3:
            // 变更提现比例
            $result = $this->acceptAuthorPercent($apply_info);
            break;
        case 4:
            // 公会长移出公会成员
            $result = $this->acceptRemove($apply_info);
            break;
        default:
            break;
        }

        return $result;
    }

    private function acceptJoin($apply_info)
    {
        $apply = new DAOFamilyApply();
        $employe = new DAOEmploye();
        $contract = new DAOFamilyContract();

        $familyid = $apply_info['familyid'];
        Interceptor::ensureNotFalse(preg_match('/^[1-9]\d*$/', $familyid) > 0, ERROR_PARAM_INVALID_FORMAT, 'familyid');
        $family = new DAOFamily();
        $family_info = $family->getFamilyInfoById($familyid);

        Interceptor::ensureNotEmpty($family_info, ERROR_BIZ_PAYMENT_FAMILY_NOT_EXIST);


        $starttime = date('Y-m-d');
        $expiretime = new DateTime($starttime);
        $expiretime = $expiretime->modify(self::EXPIRETIME)->format('Y-m-d');

        $global_percent = $family_info['author_percent'];
        $author_percent = Employe::globalToAuthorPercent($family_info['family_percent'], $global_percent);

        // 将主播的所有可提现星票，按照加入工会时的提现比例，结算到主播的现金账号中
        $withdraw = new Withdraw();
        $withdraw->addToFamily($apply_info['applicant']);

        try {
            $employe->startTrans();

            $contract->add($familyid, $apply_info['applicant'], $starttime, $expiretime, $family_info['organization'], $family_info['corporation']);
            $employe->add($familyid, $apply_info['applicant'], 'ACCEPT', $starttime, $expiretime, $author_percent, $global_percent);
            $result = $apply->updateApply($apply_info['applyid'], 'ACCEPT', '申请通过');

            $employe->commit();

        } catch (Exception $e) {
            $employe->rollback();
            $result = '';
        }

        if ($result) {
            $this->sendMessage(1, 'ACCEPT', $apply_info['applicant'], $apply_info['applicant']);
        }

        return ['result' => $result];
    }

    private function acceptAuthorPercent($apply_info)
    {
        $authorid = $apply_info['approver'];
        $familyid = $apply_info['familyid'];

        $employe = new DAOEmploye();
        $apply = new DAOFamilyApply();

        $global_percent = $apply_info['value'];

        $family = new Family();
        $family_info = $family->getFamilyInfoById($familyid);
        $family_percent = $family_info['family_percent'];
        $author_percent = Employe::globalToAuthorPercent($family_percent, $global_percent);

        Interceptor::ensureNotFalse($global_percent <= $family_percent, ERROR_BIZ_PAYMENT_FAMILY_AUTHOR_MAXPERCENT);

        try {
            $employe->startTrans();

            $employe->authorPercent($familyid, $authorid, $author_percent, $global_percent);
            $result = $apply->updateApply($apply_info['applyid'], 'ACCEPT', '审核通过');

            $employe->commit();

        } catch (Exception $e) {
            $employe->rollback();
            $result = '';
        }

        if ($result) {
            $this->sendMessage(3, 'ACCEPT', $apply_info['applicant'], $apply_info['approver']);
        }

        return ['result' => $result];
    }

    private function acceptRemove($apply_info)
    {
        $authorid = $apply_info['approver'];
        $familyid = $apply_info['familyid'];

        // 结算余额
        $withdraw = new Withdraw();
        $extends = $withdraw->familyEmplyeRelease($authorid);

        $employe = new DAOEmploye();
        $emplye_release = new DAOEmployeRelease();
        $apply = new DAOFamilyApply();

        try {
            $employe->startTrans();

            $emplye_info = $employe->getEmployeInfo($familyid, $authorid);

            $reason = '[' . __FUNCTION__ . ':' . $apply_info['applyid'] . ']';
            $emplye_release->add($emplye_info, $reason, date('Y-m-d H:i:s'));
            $employe->reject($familyid, $authorid);

            $result = $apply->updateApply($apply_info['applyid'], 'ACCEPT', $reason);
            $employe->commit();

        } catch (Exception $e) {
            $employe->rollback();
            $result = '';
        }

        if ($result) {
            $this->sendMessage(4, 'ACCEPT', $apply_info['applicant'], $apply_info['approver']);
        }

        return ['result' => $result];
    }

    public function rejectEmploye($uid, $applyid)
    {
        $apply = new DAOFamilyApply();
        $apply_info = $apply->getApplyInfo($applyid);

        Interceptor::ensureNotEmpty($apply_info, ERROR_PARAM_INVALID_FORMAT, 'applyid');
        Interceptor::ensureNotFalse($uid == $apply_info['approver'], ERROR_PARAM_INVALID_FORMAT, 'applyid');
        Interceptor::ensureNotFalse($apply_info['apply_status'] == 'APPLY', ERROR_PARAM_INVALID_FORMAT, 'applyid');

        $result = [];
        switch ($apply_info['type']) {
        case 1:
            // 拒绝加入公会
            $result = $this->rejectJoin($apply_info);
            break;
        case 3:
            // 变更提现比例
            $result = $this->rejectAuthorPercent($apply_info);
            break;
        case 4:
            // 公会长移出公会成员
            $result = $this->rejectRemove($apply_info);
            break;
        default:
            break;
        }

        return $result;
    }

    private function rejectJoin($apply_info)
    {
        $uid = $apply_info['applicant'];

        $employe = new DAOEmploye();
        $employe_info = $employe->getEmployeByuid($uid);

        Interceptor::ensureEmpty($employe_info, ERROR_BIZ_PAYMENT_FAMILY_MEMBER_INVALID);

        $apply = new DAOFamilyApply;

        $result = $apply->updateApply($apply_info['applyid'], 'REJECT', '审核拒绝');

        if ($result) {
            $this->sendMessage(1, 'REJECT', $apply_info['applicant'], $apply_info['applicant']);
        }

        return ['result' => $result];
    }

    private function rejectAuthorPercent($apply_info)
    {
        $apply = new DAOFamilyApply();
        $result = $apply->updateApply($apply_info['applyid'], 'REJECT', '申请被拒绝');

        if ($result) {
            $this->sendMessage(3, 'REJECT', $apply_info['applicant'], $apply_info['approver']);
        }

        return ['result' => $result];
    }

    private function rejectRemove($apply_info)
    {
        $apply = new DAOFamilyApply();
        $result = $apply->updateApply($apply_info['applyid'], 'REJECT', '');

        if ($result) {
            $this->sendMessage(4, 'REJECT', $apply_info['applicant'], $apply_info['approver']);
        }

        return ['result' => $result];
    }

    public function applySearch($uid, $authorid, $type)
    {
        $result = 'no';
        Interceptor::ensureNotFalse(preg_match('/^[1-9]\d*$/', $type) > 0, ERROR_PARAM_INVALID_FORMAT, 'type');

        switch ($type) {
        case 3:
            // 公会长申请变更提现比例
            $employe = new DAOEmploye();
            $employe_info = $employe->getEmployeByuid($authorid);
            Interceptor::ensureNotEmpty($employe_info, ERROR_BIZ_PAYMENT_FAMILY_MEMBER_INVALID);

            $familyid = $employe_info['familyid'];
            $family = new DAOFamily();
            $family_info = $family->getFamilyInfoById($familyid);
            $owner = $family_info['owner'];

            if ($uid == $owner) {
                $dao_apply = new DAOFamilyApply();
                $apply_info = $dao_apply->getInfoByBoth($uid, $authorid, 3, 'APPLY');
                if ($apply_info) {
                    $result = 'apply';
                } else {
                    $other_applicant_info = $dao_apply->getApplicantByStatus($authorid, 'APPLY');
                    if ($other_applicant_info) {
                        $result = 'no';
                    } else {
                        $other_approver_info = $dao_apply->getApproverByStatus($authorid, 'APPLY');
                        if ($other_approver_info) {
                            $result = 'no';
                        } else {
                            $result = 'can';
                        }
                    }
                }
            }

            break;
        case 4:
            // 公会长移出公会成员
            if ($uid == $authorid) {
                $result = 'no';
            } else {

                $employe = new DAOEmploye();
                $employe_info = $employe->getEmployeByuid($authorid);
                Interceptor::ensureNotEmpty($employe_info, ERROR_BIZ_PAYMENT_FAMILY_MEMBER_INVALID);

                $familyid = $employe_info['familyid'];
                $family = new DAOFamily();
                $family_info = $family->getFamilyInfoById($familyid);
                $owner = $family_info['owner'];

                if ($uid == $owner) {
                    $dao_apply = new DAOFamilyApply();
                    $apply_info = $dao_apply->getInfoByBoth($uid, $authorid, 4, 'APPLY');
                    if ($apply_info) {
                        $result = 'apply';
                    } else {
                        $other_applicant_info = $dao_apply->getApplicantByStatus($authorid, 'APPLY');
                        if ($other_applicant_info) {
                            $result = 'no';
                        } else {
                            $other_approver_info = $dao_apply->getApproverByStatus($authorid, 'APPLY');
                            if ($other_approver_info) {
                                $result = 'no';
                            } else {
                                $result = 'can';
                            }
                        }
                    }
                }

            }
            break;
        default:
            break;
        }

        return ['result' => $result];
    }

    public function applyList($uid, $offset, $num)
    {
        $result = [];

        $employe = new DAOEmploye();
        $employe_info = $employe->getEmployeByuid($uid);
        Interceptor::ensureNotEmpty($employe_info, ERROR_BIZ_PAYMENT_FAMILY_MEMBER_INVALID);
        $familyid = $employe_info['familyid'];

        $type_name = $this->getTypeName();
        $apply = new DAOFamilyApply();
        $data = $apply->getApply($familyid, $uid, $offset, $num);

        $user_info = [];
        if ($data) {
            $applicant_uid = array_column($data, 'applicant');
            $approver_uid = array_column($data, 'approver');
            $all_uid = array_merge($applicant_uid, $approver_uid);
            $all_uid = array_unique($all_uid);
            foreach ($all_uid as $value) {
                $user_info[$value] = User::getUserInfo($value);
            }
            unset($value);
        }

        foreach ($data as &$value) {
            $value['type_name'] = $type_name[$value['type']];
            $value['applicant_info'] = $user_info[$value['applicant']];
            $value['approver_info'] = $user_info[$value['approver']];

            $result[] = $value;
        }
        unset($value);

        return $result;
    }

    private function getTypeName()
    {
        $name = [
            '1' => '申请加入公会',
            '3' => '申请提现比例',
            '4' => '申请移出公会',
        ];

        return $name;
    }

    private function sendMessage($type, $status, $applicant, $approver)
    {
        switch ($type) {
        case 1:
            if ($status == 'APPLY') {
                $approver_apply_message = '主播发起申请加入公会，请及时处理';
                $this->message($approver, $approver_apply_message, $approver_apply_message);
            } elseif ($status == 'ACCEPT') {
                $applicant_accept_message = '加入公会的申请，公会长已同意~';
                $this->message($approver, $applicant_accept_message, $applicant_accept_message);
            } elseif ($status == 'REJECT') {
                $applicant_accept_message = '加入公会的申请，公会长已拒绝~';
                $this->message($approver, $applicant_accept_message, $applicant_accept_message);
            }
            break;
        case 3:
            if ($status == 'APPLY') {
                $approver_apply_message = '公会长发起申请变更比例，请及时处理';
                $this->message($approver, $approver_apply_message, $approver_apply_message);
            } elseif ($status == 'ACCEPT') {
                $applicant_accept_message = '变更提现比例的申请，主播已同意~';
                $this->message($applicant, $applicant_accept_message, $applicant_accept_message);
            } elseif ($status == 'REJECT') {
                $applicant_accept_message = '变更提现比例的申请，主播已拒绝~';
                $this->message($applicant, $applicant_accept_message, $applicant_accept_message);
            }
            break;
        case 4:
            if ($status == 'APPLY') {
                $approver_apply_message = '公会长发起申请移出工会，请及时处理';
                $this->message($approver, $approver_apply_message, $approver_apply_message);
            } elseif ($status == 'ACCEPT') {
                $applicant_accept_message = '你的移出工会的申请，主播已同意~';
                $this->message($applicant, $applicant_accept_message, $applicant_accept_message);
            } elseif ($status == 'REJECT') {
                $applicant_accept_message = '你的移出工会的申请，主播已拒绝~';
                $this->message($applicant, $applicant_accept_message, $applicant_accept_message);
            }
            break;
        }
    }

    private function message($uid, $title, $message)
    {
        Messenger::sendSystemPublish(
            Messenger::MESSAGE_TYPE_BROADCAST_SOME,
            $uid,
            $title,
            $message,
            '0'
        );
    }

    public function pastDue()
    {
        $apply = new DAOFamilyApply();

        $config = new Config();
        $family_apply = $config->getConfig('china', 'family_apply', 'server', '1.0.0.0');
        $due = json_decode($family_apply['value'], true);

        // 申请加入公会超时
        $due_time = date('Y-m-d H:i:s', time() - $due['due']['1']);
        $data = $apply->getInfoByAddtime('1', $due_time, 'APPLY');
        foreach ($data as $apply_info) {
            $result = $apply->updateApply($apply_info['applyid'], 'REJECT', '申请超时未处理');
            if ($result) {
                $this->sendMessage('1', 'REJECT', $apply_info['applicant'], $apply_info['applicant']);
            }
        }
        unset($apply_info);

        // 公会长申请修改比例
        $due_time = date('Y-m-d H:i:s', time() - $due['due']['3']);
        $data = $apply->getInfoByAddtime('3', $due_time, 'APPLY');
        foreach ($data as $apply_info) {
            $result = $apply->updateApply($apply_info['applyid'], 'REJECT', '申请超时未处理');
            if ($result) {
                $this->sendMessage(3, 'REJECT', $apply_info['applicant'], $apply_info['approver']);
            }
        }
        unset($value);

        // 公会长移出公会成员
        $due_time = date('Y-m-d H:i:s', time() - $due['due']['4']);
        $data = $apply->getInfoByAddtime('4', $due_time, 'APPLY');
        foreach ($data as $apply_info) {
            $this->acceptRemove($apply_info);
        }
    }
}