<?php

class Club
{
    public function setup($name, $shortname, $owner, $announcement)
    {
        $this->setupRule($name, $shortname, $owner, $announcement);

        $dao_club = new DAOClub();
        $clubid = (int)$dao_club->create($name, $shortname, $owner, $announcement, 1, 0);

        if ($clubid) {
            $dao_member = new DAOClubmember();
            $memberid = $dao_member->create($owner, $clubid, 'OWNER');
        }

        return ['clubid' => $clubid];
    }

    private function setupRule($name, $shortname, $owner, $announcement)
    {
        Interceptor::ensureNotFalse((2 <= mb_strlen($name)) && (mb_strlen($name) <= 12), ERROR_PARAM_INVALID_FORMAT, '社团名称');
        Interceptor::ensureNotFalse((2 <= mb_strlen($shortname)) && (mb_strlen($shortname)) <= 6, ERROR_PARAM_INVALID_FORMAT, '社团勋章名称');
        Interceptor::ensureNotFalse(mb_strlen($announcement) <= 300, ERROR_PARAM_INVALID_FORMAT, '社团公告');

        $dao_club = new DAOClub();

        Interceptor::ensureEmpty($dao_club->searchName($name), ERROR_BIZ_CLUB_NAME_EXIST);
        Interceptor::ensureEmpty($dao_club->searchShortName($shortname), ERROR_BIZ_CLUB_SHORTNAME_EXIST);

        $user_info = User::getUserInfo($owner);
        // vip等级大于等于6
        $vip = 0;
        foreach ($user_info['medal'] as $value) {
            if ($value['kind'] == 'vip') {
                $vip = $value['medal'];
                break;
            }
        }
        unset($value);

        Interceptor::ensureNotFalse($vip >= 6, ERROR_BIZ_CLUB_SETUP_VIP);

        $dao_club_member = new DAOClubmember();
        Interceptor::ensureEmpty($dao_club_member->getInfoByUid($owner), ERROR_BIZ_CLUB_MEMBER_EXIST);
    }

    public function announcement($clubid, $announcement)
    {
        $dao_club = new DAOClub();
        $club_info = $dao_club->getInfoById($clubid);

        $result = $dao_club->update($clubid, $club_info['name'], $club_info['shortname'], $announcement);

        return $result;
    }

    public function breakup($clubid)
    {
        $dao_club = new DAOClub();
        $result = $dao_club->deleteclub($clubid);

        $dao_club_member = new DAOClubmember();
        $dao_club_member->rejectAll($clubid);

        return $result;
    }

    public function apply($uid, $clubid)
    {
        $dao_club = new DAOClub();
        $club_info = $dao_club->getInfoById($clubid);

        $dao_club_apply = new DAOClubapply();
        $applyid = $dao_club_apply->create($clubid, $uid, $club_info['owner'], '1', 'APPLY', '', '', '');

        return ['applyid' => $applyid];
    }

    public function accept($applyid, $owner)
    {
        $dao_club_apply = new DAOClubapply();
        $apply_info = $dao_club_apply->getInfoById($applyid);

        $dao_club_member = new DAOClubmember();
        $memberid = $dao_club_member->create($apply_info['applicant'], $apply_info['clubid'], 'MEMBER');

        if ($memberid) {
            $dao_club_apply->update($applyid, 'ACCEPT');
        }

        return ['memberid' => $memberid];
    }

    public function reject($applyid, $owner)
    {
        $dao_club_apply = new DAOClubapply();
        $apply_info = $dao_club_apply->getInfoById($applyid);

        return $dao_club_apply->update($applyid, 'REJECT', '');
    }

    public function cleanApply($uid)
    {
        $dao_member = new DAOClubmember();
        $member_info = $dao_member->getInfoByUid($uid);

        $dao_club_apply = new DAOClubapply();

        return $dao_club_apply->cleanAll($member_info['clubid']);
    }

    public function applyList($uid, $offset, $num)
    {
        $dao_member = new DAOClubmember();
        $member_info = $dao_member->getInfoByUid($uid);

        $dao_club_apply = new DAOClubapply();
        $data = $dao_club_apply->getList($member_info['clubid'], $offset, $num);

        return $data;
    }

    public function quit($uid)
    {
        $dao_member = new DAOClubmember();
        $member_info = $dao_member->getInfoByUid($uid);

        $data = $dao_member->reject($uid);

        return $data;
    }

    public function out($uid, $memberuid)
    {
        $dao_member = new DAOClubmember();
        $member_info = $dao_member->getInfoByUid($memberuid);

        $data = $dao_member->reject($memberuid);

        return $data;
    }

    public function setStaff($uid, $memberuid, $action)
    {
        $dao_member = new DAOClubmember();

        if ($action == 'staff') {
            $data = $dao_member->updateRole($memberuid, 'STAFF');
        } elseif ($action == 'member') {
            $data = $dao_member->updateRole($memberuid, 'MEMBER');
        }

        return $data;
    }

    public function memberList($clubid, $offset, $num)
    {
        $dao_member = new DAOClubmember();
        $data = $dao_member->getList($clubid, $offset, $num);

        return $data;
    }

    public function clubList($offset, $num, $search)
    {
        $dao_club = new DAOClub();
        $data = $dao_club->getList($offset, $num, $search);

        return $data;
    }
}