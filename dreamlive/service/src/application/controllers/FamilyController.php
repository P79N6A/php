<?php

class FamilyController extends BaseController
{
    /**
     * 用户状态
     */
    public function statusAction()
    {
        $uid = Context::get('userid');

        $employe_model = new Employe();
        $data = $employe_model->status($uid);

        $this->render($data);
    }

    /**
     * 用户所在家族
     */
    public function myFamilyAction()
    {
        $uid = Context::get('userid');

        $employe_model = new Employe();
        $data = $employe_model->myFamily($uid);

        $this->render($data);
    }

    /**
     * 搜索家族
     */
    public function searchAction()
    {
        $uid = Context::get('userid');
        $familyid = trim($this->getParam('familyid'));
        $owner = trim($this->getParam('owner'));

        Interceptor::ensureNotFalse(preg_match('/^[1-9]\d*$/', $familyid) > 0, ERROR_PARAM_INVALID_FORMAT, 'familyid');
        Interceptor::ensureNotFalse(preg_match('/^[1-9]\d*$/', $owner) > 0, ERROR_PARAM_INVALID_FORMAT, 'owner');

        $family = new Family();
        $data = $family->search($uid, $familyid, $owner);

        $this->render($data);
    }

    public function applyEmployeAction()
    {
        $uid = Context::get('userid');
        $familyid = (int)$this->getParam('familyid', '');
        $authorid = (int)$this->getParam('authorid', '');
        $type = (int)$this->getParam('type', '');
        $value = trim(strip_tags($this->getParam('value', '')));
        $old_value = trim(strip_tags($this->getParam('old_value', '')));
        $reason = trim(strip_tags($this->getParam('reason', '')));

        Interceptor::ensureNotFalse(preg_match('/^[1-9]\d*$/', $type) > 0, ERROR_PARAM_INVALID_FORMAT, 'type');

        $apply = new FamilyApply();
        $data = $apply->applyEmploye($uid, $familyid, $authorid, $type, $value, $old_value, $reason);

        $this->render($data);
    }

    public function acceptEmployeAction()
    {
        $uid = Context::get('userid');
        $applyid = (int)$this->getParam('applyid', '');

        Interceptor::ensureNotFalse(preg_match('/^[1-9]\d*$/', $applyid) > 0, ERROR_PARAM_INVALID_FORMAT, 'applyid');

        $apply = new FamilyApply();
        $data = $apply->acceptEmploye($uid, $applyid);

        $this->render($data);
    }

    public function rejectEmployeAction()
    {
        $uid = Context::get('userid');
        $applyid = (int)$this->getParam('applyid', '');
        Interceptor::ensureNotFalse(preg_match('/^[1-9]\d*$/', $applyid) > 0, ERROR_PARAM_INVALID_FORMAT, 'applyid');

        $apply = new FamilyApply();
        $data = $apply->rejectEmploye($uid, $applyid);

        $this->render($data);
    }

    public function applyListAction()
    {
        $uid = Context::get('userid');
        $offset = (int)$this->getParam("offset", 0);
        $num = (int)$this->getParam("num", 20);

        $apply = new FamilyApply();
        $data = $apply->applyList($uid, $offset, $num);

        $this->render($data);
    }

    public function employeListAction()
    {
        $uid = Context::get('userid');
        $offset = (int)$this->getParam("offset", 0);
        $num = (int)$this->getParam("num", 20);
        $search = trim(strip_tags($this->getParam("search", '')));

        $employe_model = new Employe();
        $data = $employe_model->employeList($uid, $offset, $num, $search);

        $this->render($data);
    }

    public function employeInfoAction()
    {
        $uid = Context::get('userid');
        $authorid = trim(strip_tags($this->getParam('authorid', '')));

        $employe_model = new Employe();
        $data = $employe_model->employeInfo($uid, $authorid);

        $this->render($data);
    }

    public function applySearchAction()
    {
        $uid = Context::get('userid');
        $authorid = trim(strip_tags($this->getParam('authorid', '')));
        $type = (int)$this->getParam('type', '');

        $apply = new FamilyApply();
        $data = $apply->applySearch($uid, $authorid, $type);

        $this->render($data);
    }

    /**
     * 家族信息
     */
    public function familyInfoAction()
    {
        $familyid = $this->getParam('familyid');

        Interceptor::ensureNotFalse(preg_match('/^[1-9]\d*$/', $familyid) > 0, ERROR_PARAM_INVALID_FORMAT, 'familyid');

        $family_model = new Family();
        $family_info = $family_model->getFamilyInfoById($familyid);
        Interceptor::ensureNotEmpty($family_info, ERROR_BIZ_PAYMENT_FAMILY_NOT_EXIST);

        $this->render($family_info);
    }

    /**
     * 修改家族
     */
    public function modifyFamilyAction()
    {
        $familyid = trim(strip_tags($this->getParam('familyid', '')));
        $name = trim(strip_tags($this->getParam('name', '')));
        $logo = trim(strip_tags($this->getParam('logo', '')));
        $author_percent = trim(strip_tags($this->getParam('author_percent', '')));
        $maximum = trim(strip_tags($this->getParam('maximum', '')));
        $organization = trim(strip_tags($this->getParam('organization', '')));
        $image_organizationlicence1 = trim(strip_tags($this->getParam('image_organizationlicence1', '')));
        $corporation = trim(strip_tags($this->getParam('corporation', '')));
        $corporationphone = trim(strip_tags($this->getParam('corporationphone', '')));
        $corporationidcard = trim(strip_tags($this->getParam('corporationidcard', '')));
        $broker = trim(strip_tags($this->getParam('broker', '')));
        $brokerphone = trim(strip_tags($this->getParam('brokerphone', '')));
        $brokeridcard = trim(strip_tags($this->getParam('brokeridcard', '')));
        $image_corporationimage1 = trim(strip_tags($this->getParam('image_corporationimage1', '')));
        $image_corporationimage2 = trim(strip_tags($this->getParam('image_corporationimage2', '')));
        $image_brokerimage1 = trim(strip_tags($this->getParam('image_brokerimage1', '')));
        $image_brokerimage2 = trim(strip_tags($this->getParam('image_brokerimage2', '')));
        $image_brokerimage3 = trim(strip_tags($this->getParam('image_brokerimage3', '')));
        $declaration = trim(strip_tags($this->getParam('declaration', '')));
        $announcement = trim(strip_tags($this->getParam('announcement', '')));
        $collaborate = trim(strip_tags($this->getParam('collaborate', '')));
        $adminid = trim(strip_tags($this->getParam('adminid', '')));

        Interceptor::ensureNotEmpty($name, ERROR_PARAM_IS_EMPTY, 'name');
        Interceptor::ensureNotEmpty($logo, ERROR_PARAM_IS_EMPTY, 'logo');
        Interceptor::ensureNotFalse(0 < $author_percent && $author_percent <= 100, ERROR_PARAM_INVALID_FORMAT, 'author_percent');
        Interceptor::ensureNotEmpty($maximum, ERROR_PARAM_IS_EMPTY, 'maximum');
        Interceptor::ensureNotEmpty($organization, ERROR_PARAM_IS_EMPTY, 'organization');
        Interceptor::ensureNotEmpty($image_organizationlicence1, ERROR_PARAM_IS_EMPTY, 'image_organizationlicence1');
        Interceptor::ensureNotEmpty($corporation, ERROR_PARAM_IS_EMPTY, 'corporation');
        Interceptor::ensureNotEmpty($corporationphone, ERROR_PARAM_IS_EMPTY, 'corporationphone');
        Interceptor::ensureNotEmpty($corporationidcard, ERROR_PARAM_IS_EMPTY, 'corporationidcard');
        Interceptor::ensureNotEmpty($broker, ERROR_PARAM_IS_EMPTY, 'broker');
        Interceptor::ensureNotEmpty($brokerphone, ERROR_PARAM_IS_EMPTY, 'brokerphone');
        Interceptor::ensureNotEmpty($brokeridcard, ERROR_PARAM_IS_EMPTY, 'brokeridcard');
        Interceptor::ensureNotEmpty($image_brokerimage1, ERROR_PARAM_IS_EMPTY, 'image_brokerimage1');
        Interceptor::ensureNotEmpty($image_brokerimage2, ERROR_PARAM_IS_EMPTY, 'image_brokerimage2');
        Interceptor::ensureNotEmpty($image_brokerimage3, ERROR_PARAM_IS_EMPTY, 'image_brokerimage3');
        Interceptor::ensureNotEmpty($declaration, ERROR_PARAM_IS_EMPTY, 'declaration');
        Interceptor::ensureNotEmpty($announcement, ERROR_PARAM_IS_EMPTY, 'announcement');
        Interceptor::ensureNotEmpty($adminid, ERROR_PARAM_IS_EMPTY, 'adminid');

        $family_model = new Family();
        $data = $family_model->modifyFamily(
            $familyid,
            $name, $logo, $author_percent, $maximum,
            $organization, $image_organizationlicence1,
            $corporation, $corporationphone, $corporationidcard, $image_corporationimage1, $image_corporationimage2,
            $broker, $brokerphone, $brokeridcard,
            $image_brokerimage1, $image_brokerimage2, $image_brokerimage3,
            $declaration, $announcement,
            $collaborate,
            $adminid
        );

        $this->render(['status' => 'ok', 'familyid' => $familyid]);
    }

    /**
     * 创建家族草稿
     */
    public function createFamilyDraftAction()
    {
        $name = trim(strip_tags($this->getParam('name', '')));
        $logo = trim(strip_tags($this->getParam('logo', '')));
        $owner = trim(strip_tags($this->getParam('owner', '')));
        $corporate = trim(strip_tags($this->getParam('corporate', '')));
        $family_percent = (int)trim(strip_tags($this->getParam('family_percent', '')));
        $author_percent = (int)trim(strip_tags($this->getParam('author_percent', '')));
        $author_maxpercent = (int)trim(strip_tags($this->getParam('author_maxpercent', '')));
        $maximum = trim(strip_tags($this->getParam('maximum', '')));
        $organization = trim(strip_tags($this->getParam('organization', '')));
        $image_organizationlicence1 = trim(strip_tags($this->getParam('image_organizationlicence1', '')));
        $corporation = trim(strip_tags($this->getParam('corporation', '')));
        $corporationphone = trim(strip_tags($this->getParam('corporationphone', '')));
        $corporationidcard = trim(strip_tags($this->getParam('corporationidcard', '')));
        $image_corporationimage1 = trim(strip_tags($this->getParam('image_corporationimage1', '')));
        $image_corporationimage2 = trim(strip_tags($this->getParam('image_corporationimage2', '')));
        $broker = trim(strip_tags($this->getParam('broker', '')));
        $brokerphone = trim(strip_tags($this->getParam('brokerphone', '')));
        $brokeridcard = trim(strip_tags($this->getParam('brokeridcard', '')));
        $image_brokerimage1 = trim(strip_tags($this->getParam('image_brokerimage1', '')));
        $image_brokerimage2 = trim(strip_tags($this->getParam('image_brokerimage2', '')));
        $image_brokerimage3 = trim(strip_tags($this->getParam('image_brokerimage3', '')));
        $declaration = trim(strip_tags($this->getParam('declaration', '')));
        $announcement = trim(strip_tags($this->getParam('announcement', '')));
        $adminid = trim(strip_tags($this->getParam('adminid', '')));
        $kprate = trim(strip_tags($this->getParam('kprate', '')));
        $bfprate = trim(strip_tags($this->getParam('bfprate', '')));

        Interceptor::ensureNotEmpty($name, ERROR_PARAM_IS_EMPTY, 'name');
        Interceptor::ensureNotEmpty($logo, ERROR_PARAM_IS_EMPTY, 'logo');
        Interceptor::ensureNotEmpty($owner, ERROR_PARAM_IS_EMPTY, 'owner');
        Interceptor::ensureNotFalse(0 < $family_percent && $family_percent <= 100, ERROR_PARAM_INVALID_FORMAT, 'family_percent');
        Interceptor::ensureNotFalse(0 < $author_percent && $author_percent <= 100, ERROR_PARAM_INVALID_FORMAT, 'author_percent');
        Interceptor::ensureNotFalse(0 < $author_maxpercent && $author_maxpercent <= 100, ERROR_PARAM_INVALID_FORMAT, 'author_maxpercent');
        Interceptor::ensureNotFalse($author_percent <= $family_percent, ERROR_BIZ_PAYMENT_FAMILY_AUTHOR_MAXPERCENT);
        Interceptor::ensureNotEmpty($maximum, ERROR_PARAM_IS_EMPTY, 'maximum');
        Interceptor::ensureNotEmpty($organization, ERROR_PARAM_IS_EMPTY, 'organization');
        Interceptor::ensureNotEmpty($image_organizationlicence1, ERROR_PARAM_IS_EMPTY, 'image_organizationlicence1');
        Interceptor::ensureNotEmpty($corporation, ERROR_PARAM_IS_EMPTY, 'corporation');
        Interceptor::ensureNotEmpty($corporationphone, ERROR_PARAM_IS_EMPTY, 'corporationphone');
        Interceptor::ensureNotEmpty($corporationidcard, ERROR_PARAM_IS_EMPTY, 'corporationidcard');
        Interceptor::ensureNotEmpty($image_corporationimage1, ERROR_PARAM_IS_EMPTY, 'image_corporationimage1');
        Interceptor::ensureNotEmpty($image_corporationimage2, ERROR_PARAM_IS_EMPTY, 'image_corporationimage2');
        Interceptor::ensureNotEmpty($broker, ERROR_PARAM_IS_EMPTY, 'broker');
        Interceptor::ensureNotEmpty($brokerphone, ERROR_PARAM_IS_EMPTY, 'brokerphone');
        Interceptor::ensureNotEmpty($brokeridcard, ERROR_PARAM_IS_EMPTY, 'brokeridcard');
        Interceptor::ensureNotEmpty($image_brokerimage1, ERROR_PARAM_IS_EMPTY, 'image_brokerimage1');
        Interceptor::ensureNotEmpty($image_brokerimage2, ERROR_PARAM_IS_EMPTY, 'image_brokerimage2');
        Interceptor::ensureNotEmpty($image_brokerimage3, ERROR_PARAM_IS_EMPTY, 'image_brokerimage3');
        Interceptor::ensureNotEmpty($declaration, ERROR_PARAM_IS_EMPTY, 'declaration');
        Interceptor::ensureNotEmpty($announcement, ERROR_PARAM_IS_EMPTY, 'announcement');
        Interceptor::ensureNotEmpty($adminid, ERROR_PARAM_IS_EMPTY, 'adminid');

        $family = new Family();
        $data = $family->createFamilyDraft(
            $name, $logo, $owner, $corporate, $family_percent, $author_percent, $author_maxpercent, $maximum,
            $organization, $image_organizationlicence1,
            $corporation, $corporationphone, $corporationidcard, $image_corporationimage1, $image_corporationimage2,
            $broker, $brokerphone, $brokeridcard,
            $image_brokerimage1, $image_brokerimage2, $image_brokerimage3,
            $declaration, $announcement,
            $adminid,
            $kprate, $bfprate
        );

        $this->render(['status' => 'ok', 'familyid' => $data]);
    }

    /**
     * 审核家族
     */
    public function reviewFamilyDraftAction()
    {
        $familyid = trim(strip_tags($this->getParam('familyid', '')));
        $review = trim(strip_tags($this->getParam('review', '')));
        $review_reason = trim(strip_tags($this->getParam('review_reason', '')));

        Interceptor::ensureNotFalse(preg_match('/^[1-9]\d*$/', $familyid) > 0, ERROR_PARAM_INVALID_FORMAT, 'familyid');
        Interceptor::ensureNotFalse(in_array($review, ['ACCEPT', 'REJECT']), ERROR_PARAM_INVALID_FORMAT, 'review');

        $family = new Family();
        if ($review == 'ACCEPT') {
            $result = $family->accept($familyid, $review_reason);
        } else {
            $result = $family->refuse($familyid, $review_reason);
        }

        $this->render(['status' => 'ok', 'result' => $result]);
    }

    /**
     * 修改每日统计
     */
    public function moidfyDailyAction()
    {
        $id = trim(strip_tags($this->getParam('id', '')));
        $newfans = trim(strip_tags($this->getParam('newfans', '')));
        $share = trim(strip_tags($this->getParam('share', '')));
        $livelength = trim(strip_tags($this->getParam('livelength', '')));

        Interceptor::ensureNotEmpty($id, ERROR_PARAM_IS_EMPTY, 'id');

        $author_daily = new DAOFamilyAuthorDaily();
        $data = $author_daily->updateDaily($id, $newfans, $share, $livelength);

        $this->render(['status' => 'ok', 'data' => $data]);
    }

    public function syncDailyAction()
    {
        $id = trim($this->getParam('id', ''));

        Interceptor::ensureNotEmpty($id, ERROR_PARAM_IS_EMPTY, 'id');

        $author_daily = new DAOFamilyAuthorDaily();
        $data = $author_daily->syncDaily($id);

        $this->render(['status' => 'ok', 'data' => $data]);
    }

    /**
     * 强制创建家族主播
     */
    public function forceAddEmployeAction()
    {
        $authorid = trim(strip_tags($this->getParam('authorid', '')));
        $familyid = trim(strip_tags($this->getParam('familyid', '')));

        $realname = trim(strip_tags($this->getParam('real_name', '')));
        $mobile = trim(strip_tags($this->getParam('mobile', '')));
        $idcard = trim(strip_tags($this->getParam('idcard', '')));
        $address = trim(strip_tags($this->getParam('address', '')));
        $qq = trim(strip_tags($this->getParam('qq', '')));
        $wechat = trim(strip_tags($this->getParam('wechat', '')));
        $image = trim(strip_tags($this->getParam('image', '')));

        Interceptor::ensureNotEmpty($authorid, ERROR_PARAM_IS_EMPTY, 'authorid');
        Interceptor::ensureNotEmpty($familyid, ERROR_PARAM_IS_EMPTY, 'familyid');
        Interceptor::ensureNotEmpty($realname, ERROR_PARAM_IS_EMPTY, 'realname');

        $family_model = new Family();
        $family_info = $family_model->getFamilyInfoById($familyid);

        $employe_model = new Employe();
        $employe_model->ownerAccept($familyid, $authorid, $family_info['family_percent'], $family_info['author_percent'], $family_info['organization'], $family_info['corporation']);

        $this->render(['status' => 'ok']);
    }

    public function familyPercentAction()
    {
        $id = trim($this->getParam('id', ''));
        $family_percent = trim($this->getParam('family_percent', ''));

        $family_model = new Family();
        $result = $family_model->updateFamilyPercent($id, $family_percent);

        $this->render(['status' => 'ok', 'data' => $result]);
    }

    public function authorMaxpercentAction()
    {
        $id = trim($this->getParam('id', ''));
        $author_maxpercent = trim($this->getParam('author_maxpercent', ''));

        $family_model = new Family();
        $result = $family_model->updateAuthorMaxpercent($id, $author_maxpercent);

        $this->render(['status' => 'ok', 'data' => $result]);
    }

    public function authorPercentAction()
    {
        $authorid = trim(strip_tags($this->getParam('authorid', '')));
        $global_percent = trim(strip_tags($this->getParam('global_percent', '')));

        Interceptor::ensureNotFalse(preg_match('/^[1-9]\d*$/', $authorid) > 0, ERROR_PARAM_INVALID_FORMAT, 'authorid');

        $employe = new Employe();
        $result = $employe->authorPercent($authorid, $global_percent);

        $this->render(['status' => 'ok', 'data' => $result]);
    }

    public function kprateAction()
    {
        $id = trim($this->getParam('id', ''));
        $kprate = trim($this->getParam('kprate', ''));

        $family_model = new Family();
        $result = $family_model->updateKprate($id, $kprate);

        $this->render(['status' => 'ok', 'data' => $result]);
    }

    public function bfprateAction()
    {
        $id = trim($this->getParam('id', ''));
        $bfprate = trim($this->getParam('bfprate', ''));

        $family_model = new Family();
        $result = $family_model->updateBfprate($id, $bfprate);

        $this->render(['status' => 'ok', 'data' => $result]);
    }

    public function addStatisticsTaskAction()
    {
        $authorid = trim($this->getParam('authorid', ''));
        $begin_time = trim($this->getParam('begin_time', ''));
        $end_time = trim($this->getParam('end_time', ''));

        Interceptor::ensureNotEmpty($authorid, ERROR_PARAM_IS_EMPTY, 'authorid');
        Interceptor::ensureNotEmpty($begin_time && strtotime($begin_time), ERROR_PARAM_INVALID_FORMAT, 'begin_time');
        Interceptor::ensureNotEmpty($end_time && strtotime($end_time), ERROR_PARAM_INVALID_FORMAT, 'end_time');
        $author_daily_task = new DAOFamilyAuthorDailyTask();
        $taskid = $author_daily_task->add($authorid, $begin_time, $end_time);

        $this->render(['taskid' => $taskid]);
    }

    public function releaseAction()
    {
        $familyid = trim($this->getParam('familyid', ''));
        $authorid = trim($this->getParam('authorid', ''));
        $reason = trim(strip_tags($this->getParam('reason', '')));

        Interceptor::ensureNotEmpty($authorid, ERROR_PARAM_IS_EMPTY, 'authorid');
        Interceptor::ensureNotEmpty($familyid, ERROR_PARAM_IS_EMPTY, 'familyid');
        Interceptor::ensureNotEmpty($reason, ERROR_PARAM_IS_EMPTY, 'reason');

        $employe = new Employe();
        $result = $employe->release($familyid, $authorid, $reason);

        $this->render(['status' => 'ok', 'data' => $result]);
    }

    public function corporateAction()
    {
        $familyid = trim($this->getParam('familyid', ''));
        $corporate = trim($this->getParam('corporate', ''));
        Interceptor::ensureNotEmpty($familyid, ERROR_PARAM_IS_EMPTY, 'familyid');
        Interceptor::ensureNotFalse(in_array($corporate, ['Y', 'N']), ERROR_PARAM_IS_EMPTY, 'familyid');

        $family = new Family();
        $result = $family->updateCorporate($familyid, $corporate);

        $this->render(['status' => 'ok', 'data' => $result]);
    }
}
