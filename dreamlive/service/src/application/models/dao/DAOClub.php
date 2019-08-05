<?php

class DAOClub extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_CLUB");
        $this->setTableName('club');
    }

    public function getInfoById($clubid)
    {
        $sql = "SELECT * from {$this->getTableName()} WHERE clubid=? ";
        return $this->getRow($sql, $clubid);
    }

    public function create($name, $shortname, $owner, $announcement, $membernum, $wealth)
    {
        $now = date('Y-m-d H:i:s');
        $info = [
            'name' => $name,
            'shortname' => $shortname,
            'owner' => $owner,
            'announcement' => $announcement,
            'membernum' => $membernum,
            'wealth' => $wealth,
            'addtime' => $now,
            'modtime' => $now,
        ];

        return $this->insert($this->getTableName(), $info);
    }

    public function update($clubid, $name, $shortname, $announcement)
    {
        $info = [
            'name' => $name,
            'shortname' => $shortname,
            'announcement' => $announcement,
            'modtime' => date('Y-m-d H:i:s'),
        ];

        return $this->update($this->getTableName(), $info, "clubid=?", $clubid);
    }

    public function deleteclub($clubid)
    {
        return $this->delete($this->getTableName(), "clubid=? ", $clubid);
    }

    public function getList($offset, $num, $search)
    {
        $sql = "SELECT * FROM {$this->getTableName()} ORDER BY wealth DESC LIMIT ?,? ";

        return $this->getAll($sql, [$offset, $num]);
    }

    public function searchName($name)
    {
        $sql = "SELECT * FROM {$this->getTableName()} WHERE name=? ";

        return $this->getRow($sql, $name);
    }

    public function searchShortName($shortname)
    {
        $sql = "SELECT * FROM {$this->getTableName()} WHERE shortname=? ";

        return $this->getRow($sql, $shortname);
    }

}