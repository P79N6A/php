<?php
class UserFeeds
{
    public function getUserFeeds($uid, $num, $feedid = PHP_INT_MAX)
    {
        $dao_user_feeds = new DAOUserFeeds($uid);
        $feeds_list     = $dao_user_feeds->getUserFeeds($uid, $num, $feedid);
        
        $lastid = 0;
        
        $feeds = new Feeds();
        $userfeeds = array();
        $deleteids = array();
        foreach($feeds_list as $feeds_info) {
            if(false !== ($feed_info = $feeds->getFeedInfo($feeds_info["relateid"], $feeds_info["type"]))) {
                if ($feed_info['feed']['privacy'] == true && Context::get("version") <= '2.6.3') {
                    continue;
                }
                $userfeeds[] = $feed_info;
            } else {
                $deleteids[] = $feeds_info["feedid"];
            }
        
            $lastid = $feeds_info["feedid"];
        }
        
        if (!empty($deleteids)) {
            $dao_user_feeds->clean($uid, $deleteids);
        }
        
        if (empty($userfeeds)) {
            $lastid = $feedid;
        }
        
        $data = array("feeds"   => $userfeeds,"offset"  => (string) $lastid);
        
        return $data;
    }

    /**
     * 
     * 删除我的作品
     *
     * @param int $uid            
     * @param int $relateid            
     * @param int $type            
     */
    public function delUserFeeds($uid, $relateid, $type)
    {
        
        // 1,删除UserFeeds
        $userFeeds = new DAOUserFeeds($uid);
        $result = $userFeeds->delUserFeedsByRelateidType($relateid, $type);

        
        // 2,删除newsFeeds
        //注释newsfeeds by yangqing
        /**
* 
        $newsFeeds = new DAONewsFeeds();
        for($i=0;$i<100;$i++){
            $result = $newsFeeds->delNewsFeedsByRelateidType($i,$relateid, $type);
        }
*/
        return ;
    }
    
    /**
     * 删除回放
     *
     * @param  int $liveid
     * @return boolean
     */
    public function delRepaly($liveid)
    {
        $live = new Live();
        $live_info = $live->getLiveInfo($liveid);
        
        Interceptor::ensureNotEmpty($live_info, ERROR_BIZ_VIDEO_NOT_EXIST);
        Interceptor::ensureNotFalse(Context::get("userid") == $live_info["uid"], ERROR_BIZ_IMAGE_NOT_OWNER);
        
        $live->deleteRepaly($liveid);
        
        include_once 'process_client/ProcessClient.php';
        ProcessClient::getInstance("dream")->addTask("live_delete_control", $live_info);
        
        return true;
    }
    
    /**
     * 删除图片
     *
     * @param  int $imageid
     * @param  int $uid
     * @return boolean
     */
    public function delImage($imageid)
    {
        $image = new Image();
        $image_info = $image->getImageInfo($imageid);
        
        Interceptor::ensureNotEmpty($image_info, ERROR_BIZ_IMAGE_NOT_EXIST);
        Interceptor::ensureNotFalse(Context::get("userid") == $image_info["uid"], ERROR_BIZ_IMAGE_NOT_OWNER);
        
        $image->delete($imageid, $image_info["uid"]);
        
        include_once 'process_client/ProcessClient.php';
        ProcessClient::getInstance("dream")->addTask("image_delete_control", $image_info);
        
        return true;
    }

    /**
     * 删除短视频
     *
     * @param int $videoid
     */
    public function delVideo($videoid)
    {
        $video = new Video();
        $video_info = $video->getVideoInfo($videoid);
        
        Interceptor::ensureNotEmpty($video_info, ERROR_BIZ_VIDEO_NOT_EXIST);
        Interceptor::ensureNotFalse(Context::get("userid") == $video_info["uid"], ERROR_BIZ_VIDEO_NOT_OWNER);
        
        $video->delete($videoid, $video_info["uid"]);
        
        include_once 'process_client/ProcessClient.php';
        ProcessClient::getInstance("dream")->addTask("video_delete_control", $video_info);
        
        return true;
    }

}
?>
