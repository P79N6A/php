<?php

class DAOFamilyDraft extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_FAMILY");
        $this->setTableName("familydraft");
    }

    public function getFamilyInfoById($familyid)
    {
        $sql = "SELECT * from {$this->getTableName()} WHERE id=? ";
        return $this->getRow($sql, $familyid);
    }

    public function createFamily(
        $name, $logo, $owner, $corporate, $family_percent, $author_percent, $author_maxpercent, $maximum,
        $organization, $image_organizationlicence1,
        $corporation, $corporationphone, $corporationidcard, $image_corporationimage1, $image_corporationimage2,
        $broker, $brokerphone, $brokeridcard,
        $image_brokerimage1, $image_brokerimage2, $image_brokerimage3,
        $declaration, $announcement,
        $adminid,
        $kprate, $bfprate, $review
    ) {
        $now = date('Y-m-d H:i:s');
        $info = [
            'name' => $name,
            'logo' => $logo,
            'owner' => $owner,
            'corporate' => $corporate,
            'family_percent' => $family_percent,
            'author_percent' => $author_percent,
            'author_maxpercent' => $author_maxpercent,
            'maximum' => $maximum,
            'organization' => $organization,
            'organizationlicence1' => $image_organizationlicence1,
            'corporation' => $corporation,
            'corporationphone' => $corporationphone,
            'corporationidcard' => $corporationidcard,
            'corporationimage1' => $image_corporationimage1,
            'corporationimage2' => $image_corporationimage2,
            'broker' => $broker,
            'brokerphone' => $brokerphone,
            'brokeridcard' => $brokeridcard,
            'brokerimage1' => $image_brokerimage1,
            'brokerimage2' => $image_brokerimage2,
            'brokerimage3' => $image_brokerimage3,
            'declaration' => $declaration,
            'announcement' => $announcement,
            'adminid' => $adminid,
            'status' => 'ACCEPT',
            'addtime' => $now,
            'modtime' => $now,
            'kprate' => $kprate,
            'bfprate' => $bfprate,
            'review' => $review,
        ];

        return $this->insert($this->getTableName(), $info);
    }

    public function accept($familyid, $review_reason)
    {
        $info = [
            'review' => 'ACCEPT',
            'review_reason' => $review_reason,
            'modtime' => date('Y-m-d H:i:s'),
        ];

        return $this->update($this->getTableName(), $info, "id=?", $familyid);
    }

    public function refuse($familyid, $review_reason)
    {
        $info = [
            'review' => 'REJECT',
            'review_reason' => $review_reason,
            'modtime' => date('Y-m-d H:i:s'),
        ];

        return $this->update($this->getTableName(), $info, "id=?", $familyid);
    }
}