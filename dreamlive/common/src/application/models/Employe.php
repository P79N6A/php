<?php

class Employe
{
    const EXPIRETIME = '+5 year';

    public function status($authorid)
    {
        $login_time_interval = '-72 hours';
        $release_time_interval = '-72 hours';
        $display = 0;

        $employe = new DAOEmploye();
        $employe_info = $employe->getEmployeByuid($authorid);

        if ($employe_info) {
            $display = 1;
        } else {
            $dao_live = new DAOLive();
            $author_info = $dao_live->getEarliestLive($authorid);

            if ($author_info) {
                if (strtotime($author_info['addtime']) >= strtotime($login_time_interval)) {
                    $display = 1;
                } else {
                    $release_time = date('Y-m-d H:i:s', strtotime($release_time_interval));
                    $release = new DAOEmployeRelease();
                    $release_info = $release->getRecent($authorid, $release_time);
                    if ($release_info) {
                        $display = 1;
                    }
                }
            }
        }

        $result = [];
        $result['display'] = $display;
        $result['employe'] = $employe_info;
        $result['authorid'] = $authorid;

        return $result;
    }

    public function myFamily($authorid)
    {
        $result = [];
        $result['join'] = 'no';
        $result['role'] = '';
        $result['author_percent'] = 0;
        $result['global_percent'] = 0;
        $result['approver_count'] = 0;
        $result['owner_info'] = [];
        $result['family'] = [];

        $employe = new DAOEmploye();
        $employe_info = $employe->getEmployeByuid($authorid);

        if ($employe_info) {
            $result['join'] = 'accept';

            $family = new Family();
            $family_info = $family->getFamilyInfoById($employe_info['familyid']);
            if ($family_info['owner'] == $authorid) {
                $result['role'] = 'owner';
            } else {
                $result['role'] = 'author';
            }
            $result['family'] = $family_info;
            $result['owner_info'] = User::getUserInfo($family_info['owner']);
            $result['author_percent'] = $employe_info['author_percent'];
            $result['global_percent'] = $employe_info['global_percent'];

            $follower_info = Follow::isFollower($family_info['owner'], $authorid);
            $result['follower'] = $follower_info[$authorid];

            $accept = new DAOFamilyApply();
            $result['approver_count'] = (int)$accept->getApproverCount($family_info['id'], $authorid, 'APPLY');

        } else {
            $apply = new DAOFamilyApply();
            $apply_info = $apply->getApplicantByType($authorid, 1, 'APPLY');
            if ($apply_info) {
                $apply_info = reset($apply_info);
                $result['join'] = 'apply';

                $family = new Family();
                $family_info = $family->getFamilyInfoById($apply_info['familyid']);
                $result['family'] = $family_info;
                $result['owner_info'] = User::getUserInfo($family_info['owner']);
                $follower_info = Follow::isFollower($family_info['owner'], $authorid);
                $result['follower'] = $follower_info[$authorid];
            }
        }

        return $result;
    }

    public function employeList($uid, $offset, $num, $search)
    {
        $dao_employe = new DAOEmploye();
        $employe_info = $dao_employe->getEmployeByuid($uid);
        $familyid = $employe_info['familyid'];

        $family = new DAOFamily();
        $family_info = $family->getFamilyInfoById($familyid);
        Interceptor::ensureNotEmpty($family_info, ERROR_BIZ_PAYMENT_FAMILY_NOT_EXIST);
        Interceptor::ensureNotFalse($uid == $family_info['owner'], ERROR_BIZ_PAYMENT_FAMILY_OWNER);

        $dao_employe = new DAOEmploye();
        $data = $dao_employe->getEmploye($familyid, $offset, $num);

        foreach ($data as &$value) {
            $value['user_info'] = User::getUserInfo($value['authorid']);
        }
        unset($value);

        if ($search) {
            $result = [];
            foreach ($data as $value) {
                $user_info = $value['user_info'];
                if ((string)$user_info['uid'] === $search) {
                    $result[] = $value;
                    break;
                } elseif ($user_info['nickname'] === $search) {
                    $result[] = $value;
                    break;
                }
            }
        } else {
            $result = $data;
        }

        return $result;
    }

    public function employeInfo($owner, $authorid)
    {
        Interceptor::ensureNotFalse(preg_match('/^[1-9]\d*$/', $authorid) > 0, ERROR_PARAM_INVALID_FORMAT, 'authorid');

        $employe = new DAOEmploye();
        $owner_info = $employe->getEmployeByuid($owner);

        Interceptor::ensureNotEmpty($owner_info, ERROR_BIZ_PAYMENT_FAMILY_MEMBER_INVALID);

        $author_info = $employe->getEmployeByuid($authorid);
        Interceptor::ensureNotFalse($owner_info['familyid'] == $author_info['familyid'], ERROR_BIZ_PAYMENT_FAMILY_MEMBER_INVALID);

        $author_info['user_info'] = User::getUserInfo($author_info['authorid']);

        return $author_info;
    }

    public function ownerAccept($familyid, $authorid, $family_percent, $global_percent, $organization, $corporation)
    {
        $starttime = date('Y-m-d');
        $expiretime = new DateTime($starttime);
        $expiretime = $expiretime->modify(self::EXPIRETIME)->format('Y-m-d');

        $employe = new DAOEmploye();
        $employe_info = $employe->getEmployeByuid($authorid);
        if ($employe_info) {
            Interceptor::ensureNotFalse($familyid == $employe_info['familyid'], ERROR_BIZ_PAYMENT_FAMILY_ISEMPLOYE, 'authorid');
        }

        $apply = new DAOFamilyApply();
        Interceptor::ensureEmpty($apply->getApplicantByType($authorid, '1', 'APPLY'), ERROR_BIZ_PAYMENT_FAMILY_ISAPPLY);

        $contract = new DAOFamilyContract();
        $contract->add($familyid, $authorid, $starttime, $expiretime, $organization, $corporation);

        $author_percent = Employe::globalToAuthorPercent($family_percent, $global_percent);

        return $employe->ownerAccept($familyid, $authorid, $starttime, $expiretime, $author_percent, $global_percent, 'SIGNED');
    }

    public function release($familyid, $authorid, $reason)
    {
        // 结算余额
        $withdraw = new Withdraw();
        $extends = $withdraw->familyEmplyeRelease($authorid);

        $employe = new DAOEmploye();
        $emplye_release = new DAOEmployeRelease();
        $emplye_info = $employe->getEmployeInfo($familyid, $authorid);

        $emplye_release->add($emplye_info, $reason, date('Y-m-d H:i:s'));
        $employe->reject($familyid, $authorid);

        return $extends;
    }

    public function isCorporate($authorid)
    {
        $is_corporate = false;

        $employe = new DAOEmploye();
        $employe_info = $employe->getEmployePercent($authorid);
        if ($employe_info) {
            $family = new Family();
            $family_info = $family->getFamilyInfoById($employe_info['fid']);
            if ($family_info['corporate'] == 'Y') {
                $is_corporate = true;
            }
        }

        return $is_corporate;
    }

    public static function globalToAuthorPercent($family_percent, $global_percent)
    {
        $author_percent = $global_percent / $family_percent * 100;
        $author_percent = bcmul($author_percent, 1, 6);

        return $author_percent;
    }

    public static function authorPercent($authorid, $global_percent)
    {
        $employe = new DAOEmploye();
        $employe_info = $employe->getEmployeByuid($authorid);

        Interceptor::ensureNotEmpty($employe_info, ERROR_BIZ_PAYMENT_FAMILY_MEMBER_INVALID);
        $familyid = $employe_info['familyid'];
        $family = new DAOFamily();
        $family_info = $family->getFamilyInfoById($familyid);
        Interceptor::ensureNotEmpty($family_info, ERROR_BIZ_PAYMENT_FAMILY_NOT_EXIST);
        // 最大分成比例
        $family_percent = $family_info['family_percent'];
        Interceptor::ensureNotFalse($global_percent <= $family_percent, ERROR_BIZ_PAYMENT_FAMILY_AUTHOR_MAXPERCENT);
        Interceptor::ensureNotFalse($global_percent <= $family_info['author_maxpercent'], ERROR_BIZ_PAYMENT_FAMILY_AUTHOR_MAXPERCENT);

        $author_percent = Employe::globalToAuthorPercent($family_percent, $global_percent);

        return $employe->authorPercent($familyid, $authorid, $author_percent, $global_percent);
    }
}