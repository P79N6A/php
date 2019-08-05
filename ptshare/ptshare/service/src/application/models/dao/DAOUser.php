<?php
class DAOUser extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PASSPORT");
        $this->setTableName("user");
    }

    public function addUser($uid, $rid, $nickname, $avatar, $salt, $gender, $city, $province, $country, $addtime, $modtime, $openid)
    {
        $info = array(
            "uid"      => $uid,
            "rid"      => $rid,
            "openid"   => $openid,
            "nickname" => $nickname,
            "avatar"   => $avatar,
            "salt"     => $salt,
            "gender"   => $gender,
            "city"     => $city,
            "province" => $province,
            "country"  => $country,
            "addtime"  => $addtime,
            "modtime"  => $modtime
        );
        $info['channel'] = Context::get("channel");

        return $res = $this->insert($this->getTableName(), $info);
    }

    public function getUserInfo($uid)
    {
        $sql = "select " . $this->_getFields() . " from " . $this->getTableName() . " where uid=?";

        return $this->getRow($sql, $uid);
    }

    public function getUserInfoByRid($rid)
    {
        $sql = "select " . $this->_getFields() . " from " . $this->getTableName() . " where rid=?";
        
        return $this->getRow($sql, $rid);
    }

    public function exists($nickname)
    {
        $sql = "select count(*) from {$this->getTableName()} where nicknamemd5=?";
        return $this->getOne($sql, md5($nickname)) > 0;
    }

    private function _getFields()
    {
        return "uid, nickname, avatar, addtime, modtime, channel, phone, openid, gender, channel";
    }

    public function setUserInfo($uid, $nickname, $avatar, $gender, $city, $province, $country, $phone)
    {
        $info = array();

        if($nickname) {
            $info["nickname"] = $nickname;
        }
        if($avatar) {
            $info["avatar"] = $avatar;
        }
        if($gender) {
            $info["gender"] = $gender;
        }
        if($city) {
            $info["city"] = $city;
        }
        if($province) {
            $info["province"] = $province;
        }
        if($country) {
            $info["country"] = $country;
        }
        if($phone) {
            $info["phone"] = $phone;
        }

        $info["modtime"] = date('Y-m-d H:i:s');

        $res = $this->update($this->getTableName(), $info, "uid=?", $uid);

        return $res;
    }

    public function getUidListByNickname($nickname)
    {
        $sql = "select uid from " . $this->getTableName() . " where nickname like ? limit 20";
        return $this->getAll($sql, $nickname.'%');
    }

}
?>
