<?php

class Family
{
    public function getFamilyInfoById($familyid)
    {
        $result = [];
        $family = new DAOFamily();
        $family_info = $family->getFamilyInfoById($familyid);

        if ($family_info) {
            $result['id'] = $family_info['id'];
            $result['name'] = $family_info['name'];
            $result['logo'] = Util::joinStaticDomain($family_info['logo']);
            $result['owner'] = $family_info['owner'];
            $result['corporate'] = $family_info['corporate'];
            $user_info = User::getUserInfo($family_info['owner']);
            $result['owner_nickname'] = $user_info['nickname'];
            $result['family_percent'] = $family_info['family_percent'];
            $result['author_percent'] = $family_info['author_percent'];
            $result['organization'] = $family_info['organization'];
            $result['corporation'] = $family_info['corporation'];
            $result['addtime'] = $family_info['addtime'];
            $result['modtime'] = $family_info['modtime'];
        }

        return $result;
    }

    public function search($uid, $familyid, $search_owner)
    {
        $family_info = $this->getFamilyInfoById($familyid);

        $result = [];
        if ($family_info) {
            if ($family_info['owner'] == $search_owner) {
                $result = $family_info;
                $result['ownerinfo'] = User::getUserInfo($search_owner);
                $follower_info = Follow::isFollower($family_info['owner'], $uid);
                $result['follower'] = $follower_info[$uid];
            }
        }

        return $result;
    }

    public function modifyFamily(
        $familyid,
        $name, $logo, $author_percent, $maximum,
        $organization, $image_organizationlicence1,
        $corporation, $corporationphone, $corporationidcard, $image_corporationimage1, $image_corporationimage2,
        $broker, $brokerphone, $brokeridcard,
        $image_brokerimage1, $image_brokerimage2, $image_brokerimage3,
        $declaration, $announcement,
        $collaborate,
        $adminid
    ) {
        $family = new DAOFamily();
        $family_info = $family->getFamilyInfoById($familyid);
        $family_percent = $family_info['family_percent'];
        Interceptor::ensureNotFalse($author_percent <= $family_percent, ERROR_BIZ_PAYMENT_FAMILY_AUTHOR_MAXPERCENT);

        $family = new DAOFamily();

        return $family->updateFamily(
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
    }

    public function updateFamilyPercent($familyid, $family_percent)
    {
        Interceptor::ensureNotFalse(preg_match('/^[1-9]\d*$/', $familyid) > 0, ERROR_PARAM_INVALID_FORMAT, 'familyid');

        $family = new DAOFamily();
        $family_info = $family->getFamilyInfoById($familyid);
        Interceptor::ensureNotEmpty($family_info, ERROR_PARAM_INVALID_FORMAT, 'family_info');

        Interceptor::ensureNotFalse(preg_match('/^[1-9]\d*$/', $family_percent) > 0, ERROR_PARAM_INVALID_FORMAT, 'family_percent');
        Interceptor::ensureNotFalse(0 <= $family_percent && $family_percent <= 100, ERROR_PARAM_INVALID_FORMAT, 'family_percent');

        Interceptor::ensureNotFalse($family_percent >= $family_info['author_percent'], ERROR_PARAM_INVALID_FORMAT, '公会分成比例要大于等于默认主播分成比例');

        $result = $family->updateFamilyPercent($familyid, $family_percent);

        return $result;
    }

    public function updateAuthorMaxpercent($familyid, $author_maxpercent)
    {
        Interceptor::ensureNotFalse(preg_match('/^[1-9]\d*$/', $familyid) > 0, ERROR_PARAM_INVALID_FORMAT, 'familyid');

        $family = new DAOFamily();
        $family_info = $family->getFamilyInfoById($familyid);
        Interceptor::ensureNotEmpty($family_info, ERROR_PARAM_INVALID_FORMAT, 'family_info');

        Interceptor::ensureNotFalse(preg_match('/^[1-9]\d*$/', $author_maxpercent) > 0, ERROR_PARAM_INVALID_FORMAT, 'family_percent');
        Interceptor::ensureNotFalse(0 <= $author_maxpercent && $author_maxpercent <= 100, ERROR_PARAM_INVALID_FORMAT, 'family_percent');

        $result = $family->updateAuthorMaxpercent($familyid, $author_maxpercent);

        return $result;
    }

    public function updateKprate($familyid, $kprate)
    {
        Interceptor::ensureNotFalse(preg_match('/^[1-9]\d*$/', $familyid) > 0, ERROR_PARAM_INVALID_FORMAT, 'familyid');

        $family = new DAOFamily();
        $family_info = $family->getFamilyInfoById($familyid);
        Interceptor::ensureNotEmpty($family_info, ERROR_PARAM_INVALID_FORMAT, 'family_info');

        Interceptor::ensureNotFalse(preg_match('/^[1-9]\d*$/', $kprate) > 0, ERROR_PARAM_INVALID_FORMAT, 'kprate');
        Interceptor::ensureNotFalse(0 <= $kprate && $kprate < 100, ERROR_PARAM_INVALID_FORMAT, 'kprate');

        $result = $family->updateKprate($familyid, $kprate);

        return $result;
    }

    public function updateBfprate($familyid, $bfprate)
    {
        Interceptor::ensureNotFalse(preg_match('/^[1-9]\d*$/', $familyid) > 0, ERROR_PARAM_INVALID_FORMAT, 'familyid');

        $family = new DAOFamily();
        $family_info = $family->getFamilyInfoById($familyid);
        Interceptor::ensureNotEmpty($family_info, ERROR_PARAM_INVALID_FORMAT, 'family_info');

        Interceptor::ensureNotFalse(preg_match('/^[1-9]\d*$/', $bfprate) > 0, ERROR_PARAM_INVALID_FORMAT, 'bfprate');
        Interceptor::ensureNotFalse(0 <= $bfprate && $bfprate < 100, ERROR_PARAM_INVALID_FORMAT, 'bfprate');

        $result = $family->updateBfprate($familyid, $bfprate);

        return $result;
    }

    public function updateCorporate($familyid, $corporate)
    {
        $family = new DAOFamily();
        $family_info = $family->getFamilyInfoById($familyid);
        Interceptor::ensureNotEmpty($family_info, ERROR_PARAM_INVALID_FORMAT, 'family_info');
        Interceptor::ensureNotFalse($family_info['corporate'] != $corporate, ERROR_PARAM_INVALID_FORMAT, $corporate);

        return $family->updateCorporate($familyid, $corporate);
    }

    public function createFamilyDraft(
        $name, $logo, $owner, $corporate, $family_percent, $author_percent, $author_maxpercent, $maximum,
        $organization, $image_organizationlicence1,
        $corporation, $corporationphone, $corporationidcard, $image_corporationimage1, $image_corporationimage2,
        $broker, $brokerphone, $brokeridcard,
        $image_brokerimage1, $image_brokerimage2, $image_brokerimage3,
        $declaration, $announcement,
        $adminid,
        $kprate, $bfprate
    ) {
        $employe = new DAOEmploye();
        $employe_info = $employe->getEmployeByuid($owner);
        Interceptor::ensureEmpty($employe_info, ERROR_BIZ_PAYMENT_FAMILY_ISEMPLOYE, 'owner');

        $apply = new DAOFamilyApply();
        Interceptor::ensureEmpty($apply->getApplicantByType($owner, '1', 'APPLY'), ERROR_BIZ_PAYMENT_FAMILY_ISAPPLY);

        $dao_familydraft = new DAOFamilyDraft();
        $result = $dao_familydraft->createFamily(
            $name, $logo, $owner, $corporate, $family_percent, $author_percent, $author_maxpercent, $maximum,
            $organization, $image_organizationlicence1,
            $corporation, $corporationphone, $corporationidcard, $image_corporationimage1, $image_corporationimage2,
            $broker, $brokerphone, $brokeridcard,
            $image_brokerimage1, $image_brokerimage2, $image_brokerimage3,
            $declaration, $announcement,
            $adminid,
            $kprate, $bfprate, 'APPLY'
        );

        return $result;
    }

    public function accept($familyid, $review_reason)
    {
        $dao_familydraft = new DAOFamilyDraft();
        $familydraft_info = $dao_familydraft->getFamilyInfoById($familyid);
        Interceptor::ensureNotEmpty($familydraft_info, ERROR_PARAM_IS_EMPTY, 'familyid');
        Interceptor::ensureNotFalse($familydraft_info['review'] == 'APPLY', ERROR_PARAM_INVALID_FORMAT, 'familyid');

        $dao_employe = new DAOEmploye();
        $employe_info = $dao_employe->getEmployeByuid($familydraft_info['owner']);
        Interceptor::ensureEmpty($employe_info, ERROR_BIZ_PAYMENT_FAMILY_ISEMPLOYE, 'owner');

        $dao_apply = new DAOFamilyApply();
        Interceptor::ensureEmpty($dao_apply->getApplicantByType($familydraft_info['owner'], '1', 'APPLY'), ERROR_BIZ_PAYMENT_FAMILY_ISAPPLY);

        $dao_family = new DAOFamily();
        $employe = new Employe();

        try {
            $dao_familydraft->startTrans();

            $dao_family->createFamily(
                $familydraft_info['id'], $familydraft_info['name'], $familydraft_info['logo'], $familydraft_info['owner'], $familydraft_info['corporate'], $familydraft_info['family_percent'], $familydraft_info['author_percent'], $familydraft_info['author_maxpercent'], $familydraft_info['maximum'],
                $familydraft_info['organization'], $familydraft_info['organizationlicence1'],
                $familydraft_info['corporation'], $familydraft_info['corporationphone'], $familydraft_info['corporationidcard'], $familydraft_info['corporationimage1'], $familydraft_info['corporationimage2'],
                $familydraft_info['broker'], $familydraft_info['brokerphone'], $familydraft_info['brokeridcard'],
                $familydraft_info['brokerimage1'], $familydraft_info['brokerimage2'], $familydraft_info['brokerimage3'],
                $familydraft_info['declaration'], $familydraft_info['announcement'],
                $familydraft_info['adminid'], $familydraft_info['kprate'], $familydraft_info['bfprate']
            );

            $employe->ownerAccept($familydraft_info['id'], $familydraft_info['owner'], $familydraft_info['family_percent'], $familydraft_info['author_percent'], $familydraft_info['organization'], $familydraft_info['corporation']);

            $result = $dao_familydraft->accept($familyid, $review_reason);

            $dao_familydraft->commit();

        } catch (Exception $e) {
            $dao_familydraft->rollback();

            throw new Exception($e->getMessage(), $e->getCode());

            $result = '';
        }

        return $result;
    }

    public function refuse($familyid, $review_reason)
    {
        $dao_familydraft = new DAOFamilyDraft();

        $familydraft_info = $dao_familydraft->getFamilyInfoById($familyid);
        Interceptor::ensureNotEmpty($familydraft_info, ERROR_PARAM_INVALID_FORMAT, 'familyid');
        Interceptor::ensureNotFalse($familydraft_info['review'] == 'APPLY', ERROR_PARAM_INVALID_FORMAT, 'familyid');

        $result = $dao_familydraft->refuse($familyid, $review_reason);

        return $result;
    }
}