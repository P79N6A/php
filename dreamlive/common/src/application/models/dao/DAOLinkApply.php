<?php

class DAOLinkApply extends DAOProxy
{

    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_LIVE");
        $this->setTableName("link_apply");
    }

    /**
     * 添加数据
     *
     * @param array $option            
     */
    public function add($option)
    {
        return $this->insert($this->getTableName(), $option);
    }

    /**
     * 连麦申请
     *
     * @param int $uid
     * @param int $relateid
     * @param int $liveid
     * @param int $score
     */
    public function addLinkApply($uid, $relateid, $liveid, $score)
    {
        $option = array(
            'uid'      => $uid,
            'relateid' => $relateid,
            'liveid'   => $liveid,
            'status'   => link::LINK_APPLY_ACTIVING,
            'score'    => $score,
            'addtime'  => date('Y-m-d H:i:s')
        );
        return $this->insert($this->getTableName(), $option);
    }

    /**
     * 取消连麦申请
     *
     * @param int $uid
     * @param int $relateid
     * @param int $liveid
     */
    public function cancelLinkApply($uid, $relateid, $liveid)
    {
        $condition = ' uid=? and relateid=? and liveid=? and status='.Link::LINK_APPLY_ACTIVING;
        $params = array(
            'uid'      => $uid,
            'relateid' => $relateid,
            'liveid'   => $liveid
        );
        $option = array(
            'status'  => link::LINK_APPLY_CANCEL,
            'addtime' => date('Y-m-d H:i:s')
        );
        return $this->update($this->getTableName(), $option, $condition, $params);
    }

    /**
     * 主播拒绝用户连麦申请
     *
     * @param int $uid
     * @param int $relateid
     * @param int $liveid
     */
    public function refuseLinkApply($uid, $relateid, $liveid)
    {
        $condition = ' uid=? and relateid=? and liveid=? and status='.Link::LINK_APPLY_ACTIVING;
        $params = array(
            'uid'      => $uid,
            'relateid' => $relateid,
            'liveid'   => $liveid
        );
        $option = array(
            'status'  => link::LINK_APPLY_REFUSE,
            'modtime' => date('Y-m-d H:i:s')
        );
        return $this->update($this->getTableName(), $option, $condition, $params);
    }
    
    /**
     * 主播接受连麦，连麦未完成状态
     *
     * @param int $uid
     * @param int $relateid
     * @param int $liveid
     */
    public function acceptLinkApply($uid, $relateid, $liveid)
    {
        $condition = ' uid=? and relateid=? and liveid=? and status='.Link::LINK_APPLY_ACTIVING;
        $params = array(
            'uid'      => $uid,
            'relateid' => $relateid,
            'liveid'   => $liveid
        );
        $option = array(
            'status'  => link::LINK_APPLY_ACCEPT,
            'modtime' => date('Y-m-d H:i:s')
        );
        return $this->update($this->getTableName(), $option, $condition, $params);
    }
    
    /**
     * 链接连麦成功,写状态
     *
     * @param int $uid
     * @param int $relateid
     * @param int $liveid
     */
    public function connectedLinkApply($uid, $relateid, $liveid)
    {
        $condition = ' uid=? and relateid=? and liveid=?  and status='.Link::LINK_APPLY_ACCEPT;
        $params = array(
            'uid'      => $uid,
            'relateid' => $relateid,
            'liveid'   => $liveid
        );
        $option = array(
            'status'  => link::LINK_APPLY_CONNECT,
            'modtime' => date('Y-m-d H:i:s')
        );
        return $this->update($this->getTableName(), $option, $condition, $params);
    }
    
    /**
     * 切换申请
     *
     * @param int $uid
     */
    public function switchLinkApply($uid)
    {
        $condition = ' uid=? and status='.Link::LINK_APPLY_ACTIVING;
        $params = array('uid' => $uid);
        $option = array(
            'status'  => link::LINK_APPLY_SWITCH,
            'modtime' => date('Y-m-d H:i:s')
        );
        return $this->update($this->getTableName(), $option, $condition, $params);
    }

    /**
     * 重复申请
     *
     * @param int $uid            
     * @param int $relateid            
     * @param int $liveid            
     */
    public function isExist($uid, $relateid, $liveid)
    {
        $sql = " SELECT count(*) as cnt FROM " . $this->getTableName() . " WHERE uid=? and relateid=? and liveid=? and status=".Link::LINK_APPLY_ACTIVING;
        $result = $this->getRow($sql, array('uid' => $uid,'relateid' => $relateid,'liveid' => $liveid));
        if (isset($result['cnt']) && $result['cnt'] > 0) {
            return true;
        }
        return false;
    }
    

    /**
     * 申请列表
     *
     * @param int $relateid
     * @param int $liveid
     * @param int $num
     * @param int $offset
     */
    public function getApplyList($relateid, $liveid, $num, $offset)
    {
        $time = date('Y-m-d H:i:s', time() - Link::LINK_APPLY_EXPIRY_TIME);
        $where = "";
        if ($offset > 0) {
            $where .= " and liveid<" . $offset . " ";
        }
        $sql = " SELECT * FROM " . $this->getTableName() . " WHERE relateid=? and liveid=? and ((status=".Link::LINK_APPLY_CONNECT.") or (status=".Link::LINK_APPLY_ACTIVING." and addtime>'" . $time . "'  )) ";
        $sql .= $where;
        $sql .= " ORDER BY status ASC, score DESC LIMIT " . $num;
        return $this->getAll($sql, array('relateid' => $relateid,'liveid' => $liveid));
    }
    
    /**
     * 获取连麦次数
     *
     * @param int  $uid
     * @param date $startime
     * @param date $endtime
     */
    public function getApplyTotal($relateid, $liveid)
    {
        $time = date('Y-m-d H:i:s', time() - Link::LINK_APPLY_EXPIRY_TIME);
        $sql = " SELECT count(*) as cnt FROM " . $this->getTableName() . " WHERE relateid=? and liveid=? and ((status=".Link::LINK_APPLY_CONNECT.") or (status=".Link::LINK_APPLY_ACTIVING." and addtime>'" . $time . "'  )) ";
        return $this->getOne($sql, array('relateid' => $relateid,'liveid' => $liveid));
    }

}
?>
