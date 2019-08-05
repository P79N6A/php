<?php
class DAOUser extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PASSPORT");
        $this->setTableName("user");
    }

    public function addUser($uid, $nickname, $avatar, $signature, $gender, $location, $birth , $region, $addtime, $modtime)
    {
        $info = array(
            "uid"=>$uid,
            "nickname"=>$nickname,
            "nicknamemd5"=>md5($nickname),
            "avatar"=>$avatar,
            "signature"=>$signature,
            "gender"=>$gender,
            "location"=>$location,
            "birth"=>$birth ? $birth:'0000-00-00',
            "region"=>$region,
            // "channel"=>Context::get("channel"),
            "addtime"=>$addtime,
            "modtime"=>$modtime
        );
        $channel     = Context::get("channel");
        $ip          = Util::getIP();
        $active_time = date('Y-m-d H:i:s');
        $platform    = Context::get("platform");
        $brand       = Context::get("brand");
        $model       = Context::get("model");
        $deviceid    = Context::get("deviceid");
        $info["channel"] = ShortlinkNotice::getChannel($channel, $ip, $active_time, $platform, $brand, $model, $deviceid);

        return $res = $this->insert($this->getTableName(), $info);
    }

    public function setPassword($uid, $password, $salt)
    {
        $userinfo = array(
          "password"=>$password,
          "salt"=>$salt
        );

        return $this->update($this->getTableName(), $userinfo, "uid=?", $uid);
    }

    public function getUserInfo($uid)
    {
        $sql = "select " . $this->_getFields() . " from " . $this->getTableName() . " where uid=?";

        return $this->getRow($sql, $uid);
    }

    public function setVerified($uid, $verified = true)
    {
        return $this->update($this->getTableName(), array("verified"=>$verified ? "Y" : "N"), "uid=?", $uid);
    }

    public function exists($nickname)
    {
        $sql = "select count(*) from {$this->getTableName()} where nicknamemd5=?";
        return $this->getOne($sql, md5($nickname)) > 0;
    }

    private function _getFields()
    {
        return "uid, password, salt, nickname, avatar, signature, gender, location, birth, verified, addtime, modtime, region, channel";
    }

    public function setUserInfo($uid, $nickname = "", $signature = "", $avatar = "", $gender = "", $location = "", $birth = "0000-00-00")
    {
        $info = array();

        if(!is_null($signature)) {
            $info["signature"] = $signature;
        }

        if($nickname) {
            $info["nickname"] = $nickname;
            $info["nicknamemd5"] = md5($nickname);
        }

        if($avatar) {
            $info["avatar"] = $avatar;
        }

        if($gender) {
            $info["gender"] = $gender;
        }

        if($location) {
            $info["location"] = $location;
        }

        if($birth) {
            $info["birth"] = $birth;
        }

        $info["modtime"] = date('Y-m-d H:i:s');

        $res = $this->update($this->getTableName(), $info, "uid=?", $uid);

        if(!empty($info)) {
            $this->_inform($uid, $info);
        }

        return $res;
    }

    public function getUidListByNickname($nickname)
    {
        $sql = "select uid from " . $this->getTableName() . " where nickname like ? limit 20";
        return $this->getAll($sql, $nickname.'%');
    }

    private function _inform($uid, $user_info)
    {
        $keys = array(
            "nickname"      => "昵称",
            "avatar"        => "头像",
            "signature"     => "签名",
            "gender"        => "性别",
            "birth"         => "生日",
            "location"      => "位置",
            "verified"      => "是否认证",
            "region"        => "区域",
            "addtime"       => "注册时间",
            "modtime"       => "修改时间"
        );

        if (array_intersect(array_keys($keys), array_keys($user_info))) {
            include_once 'process_client/ProcessClient.php';
            $user_info["uid"] = $uid;
            ProcessClient::getInstance("dream")->addTask("passport_sync_data", $user_info);
        }
    }

    public function setUserChannel($uid, $channel)
    {
        $info["channel"] = $channel;
        $info["modtime"] = date('Y-m-d H:i:s');

        return $this->update($this->getTableName(), $info, "uid=?", $uid);
    }

    /**
     * 是否存在
     *
     * @param int    $uid
     * @param string $where
     */
    public function isExistByFields($uid,$where)
    {
        $sql    = " select count(0) as cnt from ".$this->getTableName()." where uid=? and ".$where;
        $result = $this->getRow($sql, $uid);
        if(isset($result['cnt']) && $result['cnt']>0) {
            return true;
        }
        return false;
    }

}
?>
