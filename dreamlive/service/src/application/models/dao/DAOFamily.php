<?php

class DAOFamily extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_FAMILY");
        $this->setTableName("family");
    }

    public function getFamilyInfoById($familyid)
    {
        $sql = "SELECT * from {$this->getTableName()} WHERE id=? ";
        return $this->getRow($sql, $familyid);
    }

    public function createFamily(
        $id, $name, $logo, $owner, $corporate, $family_percent, $author_percent, $author_maxpercent, $maximum,
        $organization, $image_organizationlicence1,
        $corporation, $corporationphone, $corporationidcard, $image_corporationimage1, $image_corporationimage2,
        $broker, $brokerphone, $brokeridcard,
        $image_brokerimage1, $image_brokerimage2, $image_brokerimage3,
        $declaration, $announcement,
        $adminid, $kprate, $bfprate
    ) {
        $now = date('Y-m-d H:i:s');
        $info = [
            'id' => $id,
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
            'kprate' => $kprate,
            'bfprate' => $bfprate,
            'status' => 'ACCEPT',
            'addtime' => $now,
            'modtime' => $now,
        ];
        
        return $this->insert($this->getTableName(), $info);
    }

    public function updateFamily(
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
        $info = [
            'name' => $name,
            'logo' => $logo,
            'author_percent' => $author_percent,
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
            'collaborate' => $collaborate,
            'adminid' => $adminid,
            'modtime' => date('Y-m-d H:i:s'),
        ];

        return $this->update($this->getTableName(), $info, "id=?", $familyid);
    }

    public function updateFamilyPercent($familyid, $family_percent)
    {
        $info = [
            'family_percent' => $family_percent,
        ];

        return $this->update($this->getTableName(), $info, "id=?", $familyid);
    }

    public function updateAuthorMaxpercent($familyid, $author_maxpercent)
    {
        $info = [
            'author_maxpercent' => $author_maxpercent,
        ];

        return $this->update($this->getTableName(), $info, "id=?", $familyid);
    }

    public function updateKprate($familyid, $kprate)
    {
        $info = [
            'kprate' => $kprate,
        ];

        return $this->update($this->getTableName(), $info, "id=?", $familyid);
    }

    public function updateBfprate($familyid, $bfprate)
    {
        $info = [
            'bfprate' => $bfprate,
        ];

        return $this->update($this->getTableName(), $info, "id=?", $familyid);
    }


    public function updateCorporate($familyid, $corporate)
    {
        $info = [
            'corporate' => $corporate,
        ];

        return $this->update($this->getTableName(), $info, "id=?", $familyid);
    }
}
