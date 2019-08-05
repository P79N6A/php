<?php

class DAOLinkMember extends DAOProxy
{

    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_LIVE");
        $this->setTableName("link_member");
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
     * 添加linkMember
     *
     * @param int $uid            
     * @param int $relateid            
     * @param int $linkid            
     * @param int $liveid            
     */
    public function addLinkMember($uid, $relateid, $linkid, $liveid)
    {
        $option = array(
            'uid'      => $uid,
            'relateid' => $relateid,
            'linkid'   => $linkid,
            'liveid'   => $liveid,
            'status'   => link::LINK_MEMBER_NOTACTIVING,
            'addtime'  => date('Y-m-d H:i:s')
        );
        return $this->replace($this->getTableName(), $option);
    }

    /**
     * 链接连麦成功,修改连麦状态
     *
     * @param int $uid
     * @param int $relateid
     * @param int $linkid
     * @param int $liveid
     */
    public function connectedLinkMember($uid, $relateid, $linkid, $liveid)
    {
        $condition = ' uid=? and relateid=? and linkid=? and liveid=? ';
        $params = array(
            'uid'      => $uid,
            'relateid' => $relateid,
            'linkid'   => $linkid,
            'liveid'   => $liveid
        );
        $option = array(
            'status'  => link::LINK_MEMBER_ACTIVING
        );
        return $this->update($this->getTableName(), $option, $condition, $params);
    }

    /**
     * 连麦挂断，写状态
     *
     * @param int $uid
     * @param int $relateid
     * @param int $linkid
     * @param int $liveid
     * @param int $status
     */
    public function disconnectedLinkMember($uid, $relateid, $linkid, $liveid, $status)
    {
        $condition = ' uid=? and relateid=? and  linkid=? and liveid=?  ';
        $params = array(
            'uid'      => $uid,
            'relateid' => $relateid,
            'linkid'   => $linkid,
            'liveid'   => $liveid
        );
        $option = array(
            'status'  => $status,
            'endtime' => date('Y-m-d H:i:s')
        );
        return $this->update($this->getTableName(), $option, $condition, $params);
    }
    
    /**
     * 直播(异常/非异常)结束
     *
     * @param int $liveid
     * @param int $status
     */
    public function finishLinkMember($liveid,$status)
    {
        $condition = ' liveid=? ';
        $params = array(
            'liveid'   => $liveid
        );
        $option = array(
            'status'  => $status,
            'endtime' => date('Y-m-d H:i:s')
        );
        return $this->update($this->getTableName(), $option, $condition, $params);
    }

    /**
     * 连麦列表列表
     *
     * @param int $relateid            
     * @param int $liveid            
     * @param int $num            
     * @param int $offset            
     */
    public function getLinkList($uid, $num, $offset)
    {
        $where = "";
        if ($offset > 0) {
            $where .= " and liveid<" . $offset . " ";
        }
        $sql = " SELECT * FROM " . $this->getTableName() . " WHERE uid=? ";
        $sql .= $where;
        $sql .= " ORDER BY id DESC LIMIT " . $num;
        return $this->getAll($sql, array('uid' => $uid));
    }
    
    /**
     * 连麦次数
     *
     * @param int  $uid
     * @param date $startime
     * @param date $endtime
     */
    public function getLinkTotal($uid)
    {
        $sql = " SELECT count(*) as cnt FROM " . $this->getTableName() . " WHERE uid=?  ";
        return $this->getOne($sql, array('uid' => $uid));
    }
    
}
