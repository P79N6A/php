<?php
class Video
{
    const PRAISE_REDIS_KEY = 'video_praise';

    public function add($uid, $mp4, $duration, $content, $cover, $width, $height, $point, $province, $city, $district, $location,$iscapture)
    {
        $videoid = Counter::increase(Counter::COUNTER_TYPE_FEEDS, "idgen");
        Interceptor::ensureNotFalse($videoid > 0, ERROR_BIZ_COUNTER_BUSY_RETRY);

        $extends = array(
            "network"   => Context::get("network"),
            "platform"  => Context::get("platform"),
            "brand"     => Context::get("brand"),
            "model"     => Context::get("model"),
            "version"   => Context::get("version"),
            "platform"  => Context::get("platform")
        );
        $extends = json_encode($extends);

        $dao_video = new DAOVideo();
        try {

            $dao_video->startTrans();
            $dao_video->add($videoid, $uid, $mp4, $duration, $content, $cover, $width, $height, $point, $province, $city, $district, $location, $extends, $iscapture);
            $dao_user_feeds = new DAOUserFeeds($uid);

            if($iscapture=='Y') {
                $feedsType = Feeds::FEEDS_VIDEO_SCREEN;
            }else{
                $feedsType = Feeds::FEEDS_VIDEO;
                $dao_user_feeds->addUserFeeds($uid, $feedsType, $videoid, date("Y-m-d H:i:s"));
            }
            //$dao_user_feeds->addUserFeeds($uid, $feedsType, $videoid,date("Y-m-d H:i:s"));

            //by yangqing
            //$dao_feeds = new DAOFeeds();
            //$dao_feeds->addFeeds($uid, $videoid, Feeds::FEEDS_VIDEO);

            $dao_video->commit();
        } catch (MySQLException $e) {
            $dao_video->rollback();
            throw new BizException(ERROR_SYS_DB_SQL);
        }

        $total = $dao_user_feeds->getUserFeedsTotal($uid);
        Counter::set(Counter::COUNTER_TYPE_PASSPORT_FEEDS_NUM, $uid, $total);

        // flush cache
        $video_info = $this->getVideoInfo($videoid);
        return $videoid;
    }


    public function getVideoInfo($videoid)
    {
        $key = "video_cache_$videoid";
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        
        if ((($video_info = $cache->get($key)) !== false)) {
            $video_info = json_decode($video_info, true);
            if (! empty($video_info["url"])) {
                if (strpos($video_info['cover'], 'http://') === false) {
                    $video_info['cover'] = STATIC_DOMAIN_NAME . $video_info['cover'];
                }
                if (strpos($video_info['mp4'], 'http://') === false) {
                    $video_info['mp4'] = STATIC_DOMAIN_NAME . $video_info['mp4'];
                }
                
                $praises = Counter::get(Counter::COUNTER_TYPE_VIDEO_PRAISES, $videoid);
                $watches = Counter::get(Counter::COUNTER_TYPE_VIDEO_WATCHES, $videoid);
                $praises_status = $cache->get(Video::PRAISE_REDIS_KEY. $videoid . "_" . Context::get("userid"));
                $video_info['praise_status'] =  empty($praises_status) ? 0 : 1;
                
                $video_info['praises'] =(int) $praises;
                $video_info['watches'] =(int) $watches*11;//20180119 观看 x11
                $video_info['extends'] = json_decode($video_info['extends'], true);
                return $video_info;
            }
        }

        $dao_video = new DAOVideo();
        $video_info = $dao_video->getVideoInfo($videoid);
        if (is_array($video_info)) {
            //话题处理
            $DAOTopic = new DAOTopic();
            $list = $DAOTopic->getTopicName($videoid, Context::get("region"), Feeds::FEEDS_VIDEO);
            $topic = array();
            foreach($list as $item){
                $topic[] = $item['name'];
            }
            $video_info['topic'] = array_values($topic);
            $cache->set($key, json_encode($video_info));
        }

        if (empty($video_info)) {
            return array();
        }

        $praises = Counter::get(Counter::COUNTER_TYPE_VIDEO_PRAISES, $videoid);
        $watches = Counter::get(Counter::COUNTER_TYPE_VIDEO_WATCHES, $videoid);
        $praises_status = $cache->get(Video::PRAISE_REDIS_KEY. $videoid . "_" . Context::get("userid"));
        $video_info['praise_status'] =  empty($praises_status) ? 0 : 1;

        $video_info['praises'] =(int) $praises;
        $video_info['watches'] =(int) $watches*11;//20180119 观看 x11
        $video_info['extends'] = json_decode($video_info['extends'], true);

        if (!empty($video_info['uid'])) {
            $userinfo = User::getUserInfo($video_info['uid']);
            $video_info['nickname'] = $userinfo['nickname'];
        }
        // 信息改变发送同步到运营后台 先注掉
        // require_once('process_client/ProcessClient.php');
        // ProcessClient::getInstance("dream")->addTask("video_data_sync_admin", $video_info);
        if (strpos($video_info['cover'], 'http://') === false) {
            $video_info['cover'] = STATIC_DOMAIN_NAME. $video_info['cover'];
        }
        if (strpos($video_info['mp4'], 'http://') === false) {
            $video_info['mp4'] = STATIC_DOMAIN_NAME. $video_info['mp4'];
        }
        
        return $video_info;
    }

    public function delete($videoid, $uid)
    {
        $dao_video = new DAOVideo();
        try {
            $dao_video->startTrans();

            $dao_video->delVideo($videoid);

            $dao_user_feeds = new DAOUserFeeds($uid);
            $dao_user_feeds->delUserFeeds($videoid);

            //by yangqing
            //$dao_feeds    = new DAOFeeds();
            //$dao_feeds->delFeeds($videoid);

            $dao_video->commit();
        } catch (MySQLException $e) {
            $dao_video->rollback();
            throw new BizException(ERROR_SYS_DB_SQL);
        }

        $total = $dao_user_feeds->getUserFeedsTotal($uid);
        Counter::set(Counter::COUNTER_TYPE_PASSPORT_FEEDS_NUM, $uid, $total);

        $key = "video_cache_$videoid";
        $cache = Cache::getInstance("REDIS_CONF_CACHE", $videoid);
        $cache->delete($key);

        return true;
    }

    public function setCover($videoid, $cover)
    {
        $dao_video = new DAOVideo();
        try {
            $dao_video->setCover($videoid, $cover);

            $key = "video_cache_$videoid";
            $cache = Cache::getInstance("REDIS_CONF_CACHE", $videoid);
            $cache->delete($key);
        } catch (MySQLException $e) {
            throw new BizException(ERROR_SYS_DB_SQL);
        }

        return true;
    }

    private function _clean($videoid)
    {
        $key = "image_cache_$videoid";
        $cache = Cache::getInstance("REDIS_CONF_CACHE", $videoid);
        $cache->delete($key);
    }
}
?>
