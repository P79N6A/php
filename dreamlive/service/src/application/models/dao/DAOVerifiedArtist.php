<?php
class DAOVerifiedArtist extends DAOProxy
{
    const NOVERIFY = 0;//未认证
    const VERIFING = 1;//认证进行中
    const VERIFIED = 2;//认证完成
    const MODIFYVERIFING = 3;//认证修改中
    const VERIFY_FAIL = -1;//认证失败
    const VERIFY_CANCEL = -2;//认证取消

    public function __construct()
    {
        parent::__construct();
        $this->setDBConf("MYSQL_CONF_PASSPORT");
        $this->setTableName("verified_artist");
    }

    public function getVerify($uid)
    {
        $sql = "select " . $this->_getFields() . " from " . $this->getTableName() . " where uid=?";

        $info = $this->getRow($sql, $uid);

        if($info['artist_img']) {
            $sImgs = explode(',', $info['artist_img']);
            foreach($sImgs as &$img){
                $img_arr = array();
                $img_arr = parse_url($img);
                $img = Context::getConfig("STATIC_DOMAIN").$img_arr['path'];
            }
            $info['artist_img'] = implode(',', $sImgs);
        }

        return $info;
    }

    public function addVerify($uid, $reason, $wb_rid, $contact, $artist_img, $supplementary, $status)
    {
        $verifiedinfo = array(
            "uid"=>$uid,
            "reason"=>$reason,
            "wb_rid"=>$wb_rid,
            "contact"=>$contact,
            "artist_img"=>$artist_img,
            "supplementary"=>$supplementary,
            "extends"=>'',
            "status"=>$status,
            "addtime"=>date('Y-m-d H:i:s'),
            "modtime"=>date('Y-m-d H:i:s')
        );

        return $this->replace($this->getTableName(), $verifiedinfo);
    }

    public function deleteVerify($uid)
    {
        return $this->delete($this->getTableName(), "uid = ?", array($uid));
    }

    public function updateVerify($uid, $reason, $wb_rid, $contact, $artist_img, $supplementary, $status=0)
    {
        $verifiedinfo = array(
            "reason"=>$reason,
            "wb_rid"=>$wb_rid,
            "contact"=>$contact,
            "artist_img"=>$artist_img,
            "supplementary"=>$supplementary,
            "modtime"=>date('Y-m-d H:i:s')
        );
        if(!empty($status)) {
            $verifiedinfo['status'] = $status;
        }

        return $this->update($this->getTableName(), $verifiedinfo, "uid = ?", array($uid));
    }

    public function applyModify($uid, $reason, $wb_rid, $contact, $artist_img, $supplementary)
    {
        $verifiedinfo['extends'] = json_encode(
            array(
            "reason"=>$reason,
            "wb_rid"=>$wb_rid,
            "contact"=>$contact,
            "artist_img"=>$artist_img,
            "supplementary"=>$supplementary,
            )
        );
        $verifiedinfo["status"] = self::MODIFYVERIFING;
        $verifiedinfo["modtime"] = date('Y-m-d H:i:s');

        return $this->update($this->getTableName(), $verifiedinfo, "uid = ?", array($uid));
    }

    public function getVerifiedReasonByUid($uid)
    {
        $sql = "select reason from " . $this->getTableName() . " where status=".self::VERIFIED." and uid=?";

        return $this->getOne($sql, $uid);
    }

    private function _getFields()
    {
        return "uid, reason, wb_rid, contact, artist_img, addtime, modtime, status";
    }
}
