<?php
class Link
{
    const LINK_TYPE_ANCHOR_USER   = 1; // 主播-用户  1：1
    const LINK_TYPE_ANCHOR_ANCHOR = 2; // 主播-主播  1：1

    const LINK_MEMBER_NOTACTIVING = 0; // 连麦未完成
    const LINK_MEMBER_ACTIVING    = 1; // 进行中
    const LINK_MEMBER_FINISHED    = 2; // 主动断开
    const LINK_MEMBER_DISCONNECT  = 3; // 主播断开
    const LINK_MEMBER_FORBIDED    = 4; // 运营关闭
    const LINK_MEMBER_STOP        = 5; // 直播结束
    const LINK_MEMBER_RECLICT     = 6; // 僵尸直播清残留
    const LINK_MEMBER_CRUMBLE     = 7; // 崩溃直播
    const LINK_MEMBER_REPEATED    = 8; // 重复清理
    
    const LINK_APPLY_CONNECT      = 0; // 连麦中
    const LINK_APPLY_ACTIVING     = 1; // 申请中
    const LINK_APPLY_ACCEPT       = 2; // 主播接受未完成
    const LINK_APPLY_REFUSE       = 3; // 主播拒绝
    const LINK_APPLY_CANCEL       = 4; // 用户取消
    const LINK_APPLY_SWITCH       = 5; // 用户切换主播申请
    const LINK_APPLY_CLOSE        = 6; // 连麦结束


    
    const APPLY_SCORE_ANCHOR      = 10000000;
    const APPLY_SCORE_VIP         = 10000;
    
    const LINK_APPLY_TIMES_LIMIT  = 10;   //申请连麦次数限制
    const LINK_APPLY_EXPIRY_TIME  = 1800; //申请连麦过期时间

    /**
     * 关注的在线主播
     *
     * @param int $userid
     * @param int $offset
     * @param int $num
     */
    public static function getFollowingsOnlineAnchor($userid, $offset = 0, $num = 20)
    {
        $arrTemp = $uids = array();
        
        $userFollowings = Follow::getUserFollowings($userid, $offset, 3000);//我关注的人列表
        $anchorList = Live::getUserLiveAnchorList();//在线直播主播列表
        
        
        foreach($userFollowings as $item){
            if(in_array($item['uid'], $anchorList) && self::isUserPermit($item['uid'])) {
                array_push($uids, $item['uid']);
            }
        }
        
        $userInfoList = User::getUserInfos($uids);
        foreach ($uids as $uid) {
            $arrTemp['list'][] = $userInfoList[$uid];
        }
        return $arrTemp;
    }
    
    /**
     * 连麦申请
     *
     * @param int $userid
     * @param int $relateid
     * @param int $liveid
     */
    public static function apply($uid, $relateid, $liveid)
    {
        try {
            $DAOLinkApply = new DAOLinkApply();
            $DAOLinkApply->switchLinkApply($uid);
            
            $score = self::getUserScore($uid);
            return $DAOLinkApply->addLinkApply($uid, $relateid, $liveid, $score);
        } catch (Exception $e) {
            throw new BizException($e->getMessage());
        }
    }
    
    /**
     * 取消连麦申请
     *
     * @param int $userid
     * @param int $relateid
     * @param int $liveid
     */
    public static function cancel($uid, $relateid, $liveid)
    {
        try {
            $DAOLinkApply = new DAOLinkApply();
            return $DAOLinkApply->cancelLinkApply($uid, $relateid, $liveid);
        } catch (Exception $e) {
            throw new BizException($e->getMessage());
        }
    }
    
    /**
     * 连麦申请列表
     *
     * @param  int $relateid
     * @param  int $liveid
     * @param  int $num
     * @param  int $offset
     * @return array
     */
    public static function getApplyList($relateid, $liveid, $num, $offset)
    {
        $arrTemp = $uids = array();
        try {
            $DAOLinkApply = new DAOLinkApply();
            $arrTemp['total'] = $DAOLinkApply->getApplyTotal($relateid, $liveid);
        
            $list = $DAOLinkApply->getApplyList($relateid, $liveid, $num, $offset);
            foreach ($list as $item) {
                array_push($uids, $item['uid']);
            }
            
            $userInfoList = User::getUserInfos($uids);
            $DAOLink = new DAOLink();
            foreach ($list as $key => $val) {
                if (($liveid == Live::getLiveRoomByUser($val['relateid']))) {
                    $apply = $val;
                    $apply['isLive'] = Live::isUserLive($val['uid']);
                    if($apply['status']==0) {
                        $apply['linkid'] = $DAOLink->isExistLinkid($apply['relateid'], $apply['liveid'], $apply['status']);
                    }
                    $arrTemp['list'][] = array(
                        "apply" => $apply,
                        "user"  => $userInfoList[$list[$key]['uid']]
                    );
                    $offset = $list[$key]['score'];
                }else {
                    $arrTemp['total'] --;
                }
            }
            $arrTemp['offset'] = $offset;
            return $arrTemp;
        } catch (Exception $e) {
            throw new BizException($e->getMessage());
        }
    }

    /**
     * 主播接受连麦申请
     *
     * @param int $uid            
     * @param int $liveid            
     */
    public static function accept($userid, $relateid, $liveid, $linktype, $streamtype)
    {
        $DAOLink = new DAOLink();
        try {
            $DAOLink->startTrans();
                
            // 1,创建link
            $linkid = $DAOLink->isExistLinkid($userid, $liveid, $linktype);
            if (! $linkid) {
                $linkid = $DAOLink->addLink($userid, $liveid, $linktype, $streamtype);
            }
            
            // 2,写主播member
            $DAOLinkMember = new DAOLinkMember();
            $result = $DAOLinkMember->addLinkMember($userid, $relateid, $linkid, $liveid);
            
            // 3,写用户member
            $result = $DAOLinkMember->addLinkMember($relateid, $userid, $linkid, $liveid);
            
            // 4,写用户申请连麦状态(主播接受未完成状态)
            $DAOLinkApply = new DAOLinkApply();
            $result = $DAOLinkApply->acceptLinkApply($relateid, $userid, $liveid);
            
            $DAOLink->commit();
        } catch (Exception $e) {
            $DAOLink->rollback();
            throw new BizException($e->getMessage());
        }
        return $linkid;
    }
    
    /**
     * 用户链接连麦完成
     *
     * @param  int $uid            
     * @param  int $liveid  
     * @param  int $linkid  
     * @throws Exception
     */
    public static function connected($uid, $relateid, $liveid, $linkid)
    {
        $DAOLinkMember = new DAOLinkMember();
        try {
            $DAOLinkMember->startTrans();
            
            // 2,修改主播member状态
            $result = $DAOLinkMember->connectedLinkMember($uid, $relateid, $linkid, $liveid);
            
            // 2,修改主播member状态
            $result = $DAOLinkMember->connectedLinkMember($relateid, $uid, $linkid, $liveid);
            
            // 3，修改连麦申请状态
            $DAOLinkApply = new DAOLinkApply();
            $result = $DAOLinkApply->connectedLinkApply($relateid, $uid, $liveid);
            
            // 4,修改直播状态
            $DAOLink = new DAOLink();
            $linkInfo = $DAOLink->getLinkInfo($linkid);
            $live = new Live();
            $result = $live->setUserLiveLink($liveid, $linkInfo['linktype'], $linkInfo['streamtype']);
            
            $DAOLinkMember->commit();
        } catch (Exception $e) {
            $DAOLinkMember->rollback();
            throw new BizException($e->getMessage());
        }
        return true;
    }

    /**
     * 连麦挂断,type:anchor主播挂断,user用户挂断
     *
     * @param  int $userid            
     * @param  int $uid            
     * @param  int $liveid            
     * @param  int $linkid            
     * @throws Exception
     */
    public static function disconnected($uid, $relateid, $liveid, $linkid, $type)
    {
        $status = ($type == 'anchor') ? self::LINK_MEMBER_DISCONNECT : self::LINK_MEMBER_FINISHED;
        $DAOLinkMember = new DAOLinkMember();
        try {
            $DAOLinkMember->startTrans();
            $result = $DAOLinkMember->disconnectedLinkMember($uid, $relateid, $linkid, $liveid, $status);
            //$result = $DAOLinkMember->disconnectedLinkMember($relateid, $uid, $linkid, $liveid, $status);
            
            $DAOLinkApply = new DAOLinkApply();
            $result = $DAOLinkApply->closeLinkApply($uid, $relateid, $liveid);
            
            $DAOLinkMember->commit();
        } catch (Exception $e) {
            $DAOLinkMember->rollback();
            throw new BizException($e->getMessage());
        }
    }

    /**
     * 主播拒绝用户连麦申请
     *
     * @param  int $userid            
     * @param  int $uid            
     * @param  int $liveid            
     * @throws Exception
     */
    public static function refuse($userid, $uid, $liveid)
    {
        try {
            $DAOLinkApply = new DAOLinkApply();
            return $DAOLinkApply->refuseLinkApply($uid, $userid, $liveid);
        } catch (Exception $e) {
            throw new BizException($e->getMessage());
        }
    }

    /**
     * 用户连麦记录
     *
     * @param int $uid            
     * @param int $num            
     * @param int $offset            
     */
    public static function getLinkList($uid, $num, $offset)
    {
        $arrTemp = $uids = $linkids = array();
        try {
            $DAOLinkMember = new DAOLinkMember();
            $arrTemp['total'] = $DAOLinkMember->getLinkTotal($uid);

            $list = $DAOLinkMember->getLinkList($uid, $num, $offset);
            foreach ($list as $item) {
                array_push($uids, $item['relateid']);
                array_push($linkids, $item['linkid']);
            }
            
            $DAOLink = new DAOLink();
            $linkInfoList = $DAOLink->getBatchLinkList($linkids);

            $userInfoList = User::getUserInfos($uids);
            foreach ($list as $key => $val) {
                $link = $val;
                $link['linktype']  = $linkInfoList[$list[$key]['linkid']]['linktype'];
                $arrTemp['list'][] = array("link" => $link,"user" => $userInfoList[$list[$key]['relateid']]);
                $offset = $val['id'];
            }
            $arrTemp['offset'] = $offset;
            return $arrTemp;
        } catch (Exception $e) {
            throw new BizException($e->getMessage());
        }
    }
    
    /**
     * 直播(异常/非异常)结束
     *
     * @param int $liveid
     * @param int $status
     */
    public static function finishLinkMember($liveid,$status)
    {
        try {
            $DAOLinkMember = new DAOLinkMember();
            return $DAOLinkMember->finishLinkMember($liveid, $status);
        } catch (Exception $e) {
            throw new BizException($e->getMessage());
        }
    }
    
    /**
     * 重复申请
     *
     * @param int $userid            
     * @param int $relateid            
     * @param int $liveid            
     */
    public static function isRepeatApply($uid, $relateid, $liveid)
    {
        $DAOLinkApply = new DAOLinkApply();
        return $DAOLinkApply->isExist($uid, $relateid, $liveid);
    }
    
    /**
     * 获取连麦已经申请次数
     *
     * @param int $relateid
     * @param int $liveid
     */
    public static function getApplyTotal($relateid,$liveid)
    {
        $DAOLinkApply = new DAOLinkApply();
        return $DAOLinkApply->getApplyTotal($relateid, $liveid);
    }
    
    /**
     * 获取分数
     *
     * @param  int $uid
     * @return int
     */
    public static function getUserScore($uid)
    {
        $score = time();
        if(Live::isUserLive($uid)) {
            $score += self::APPLY_SCORE_ANCHOR;
        }
        $userInfo = User::getUserInfo($uid);
        $score += intval($userInfo['vip']) * self::APPLY_SCORE_VIP;
        return $score;
    }

    
    /**
     * 主播权限
     *
     * @param int $uid
     */
    public static function isAnchorPermissions($uid)
    {
        $key = self::_getKey();
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        if (($cache->ZSCORE($key, $uid)) == 1) {
            return true;
        }
        return false;
    }
    
    /**
     * 用户权限
     *
     * @param int $uid
     */
    public static function isUserPermissions($uid)
    {
        $key = self::_getKey();
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        if (($cache->ZSCORE($key, $uid)) > 0) {
            return true;
        }
        
        // 运控判断用户等级权限
        $user = new User();
        $userInfo = $user->getUserInfo($uid);
        if ($userInfo['level'] >= 10) {
            return true;
        }
        return false;
    }

    /**
     * 主播连麦许可
     *
     * @param int    $userid            
     * @param string $permit            
     */
    public static function setUserPermit($userid, $permit)
    {
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $key = "anchor_link_permit_string_" . $userid;
        if ($permit == 'open') {
            $cache->add($key, 1);
        }
        if ($permit == 'close') {
            $cache->delete($key);
        }
        return true;
    }
    
    /**
     * 开启连麦许可
     *
     * @param unknown $userid
     */
    public static function isUserPermit($userid)
    {
        $key   = "anchor_link_permit_string_" . $userid;
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        if (($cache->get($key)) > 0) {
            return true;
        }
        return false;
    }
    
    
    /**
     * 用户权限redis key
     */
    public static function _getKey()
    {
        return 'Link_rights_users';
    }
}
