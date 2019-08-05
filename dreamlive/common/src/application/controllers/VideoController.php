<?php
class VideoController extends BaseController
{
    public function addAction()
    {
        $mp4 = $this->getPost("mp4") ? strip_tags(trim($this->getPost("mp4"))) : "";
        $content = $this->getPost("content") ? strip_tags(trim($this->getPost("content"))) : "";
        $duration = $this->getPost("duration") ? strip_tags(trim($this->getPost("duration"))) : 0;
        $cover = $this->getPost("cover") ? strip_tags(trim($this->getPost("cover"))) : "";
        $point = $this->getPost("point") ? strip_tags(trim($this->getPost("point"))) : "";
        $province = $this->getPost("province") ? strip_tags(trim($this->getPost("province"))) : "";
        $city = $this->getPost("city") ? strip_tags(trim($this->getPost("city"))) : "";
        $district = $this->getPost("district") ? strip_tags(trim($this->getPost("district"))) : "";
        $location = $this->getPost("location") ? strip_tags(trim($this->getPost("location"))) : "";
        $width = $this->getPost("width") ? intval($this->getPost("width")) : 0;
        $height = $this->getPost("height") ? intval($this->getPost("height")) : 0;
        $topic = $this->getPost("topic") ? strip_tags(trim($this->getPost("topic"))) : "";
        $title = $this->getPost("title") ? strip_tags(trim($this->getPost("title"))) : "";
        
        $iscapture = $this->getPost("iscapture") ? $this->getPost("iscapture") : 'N';//是否是录屏
        
        Interceptor::ensureNotEmpty($point, ERROR_PARAM_IS_EMPTY, "point");
        Interceptor::ensureNotFalse(preg_match("/-?[\d\.]+,-?[\d\.]+/", $point) > 0, ERROR_PARAM_INVALID_FORMAT, "point");
        Interceptor::ensureNotFalse($width > 0, ERROR_PARAM_NOT_SMALL_ZERO, "width");
        Interceptor::ensureNotFalse($height > 0, ERROR_PARAM_NOT_SMALL_ZERO, "height");
        Interceptor::ensureNotEmpty($mp4, ERROR_PARAM_IS_EMPTY, "mp4");
        Interceptor::ensureNotEmpty($cover, ERROR_PARAM_IS_EMPTY, "cover");
        
        if (strpos($mp4, 'http://') !== false) {
            $mp4 = str_replace(STATIC_DOMAIN_NAME, '', $mp4);
        }
        
        if (strpos($cover, 'http://') !== false) {
            $cover = str_replace(STATIC_DOMAIN_NAME, '', $cover);
        }
        
        $video = new Video();
        $uid = Context::get("userid");

        $videoid = $video->add($uid, $mp4, $duration, $content, $cover, $width, $height, $point, $province, $city, $district, $location, $iscapture);

        $video_info = $video->getVideoInfo($videoid);
        $video_info = array(
            'videoid'  => $video_info['videoid'],
        'content'  => $video_info['content'],
        'uid'      => $video_info['uid'],
        'url'      => $video_info['mp4'],
        'title'    => $video_info['title']?$video_info['title']:$video_info['videoid'],
        'addtime'  => $video_info['addtime'],
        'cover'    => $video_info['cover']?$video_info['cover']:$video_info['videoid'],
        'iscapture'=> $video_info['iscapture'],
        'topic'    => $topic,
        );
        
        if (strpos($video_info['cover'], 'http://') !== false) {
            $video_info['cover'] = str_replace(STATIC_DOMAIN_NAME, '', $video_info['cover']);
        }
        
        if (strpos($video_info['mp4'], 'http://') !== false) {
            $video_info['mp4'] = str_replace(STATIC_DOMAIN_NAME, '', $video_info['mp4']);
        }
        
        if ($iscapture=='N') {
            include_once 'process_client/ProcessClient.php';
            ProcessClient::getInstance("dream")->addTask("video_create_control", $video_info);
    
            $info = array(
                'uid'      => $video_info['uid'],
                'type'     => Feeds::FEEDS_VIDEO,//小视频
                'relateid' => $video_info['videoid'],
                'addtime'  => $video_info['addtime'],
            );
            
            //注释newsfeeds by yangqing
               //ProcessClient::getInstance("dream")->addTask("resources_add_newsfeeds", $info);
        }
        

        $this->render(array("videoid" => $videoid,"addtime"=>$video_info['addtime']));
    }

    public function praiseAction()
    {
        $videoid     = $this->getParam("videoid") ? strip_tags(trim($this->getParam("videoid")))   : 0;
        $num        = $this->getParam("num")     ? strip_tags(trim($this->getParam("num")))       : 1;

        $userid     = Context::get("userid");
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $praise_redis_key = Video::PRAISE_REDIS_KEY. $videoid . "_" . $userid;

        if ($cache->get($praise_redis_key) > 0) {
            $total      =  Counter::get(Counter::COUNTER_TYPE_VIDEO_PRAISES, $videoid, $num);
            $this->render(array("praises" => (int) $total));
        }

        $cache->incr($praise_redis_key);

        Interceptor::ensureNotEmpty($videoid, ERROR_PARAM_IS_EMPTY, "videoid");
        Interceptor::ensureNotFalse(is_numeric($videoid), ERROR_PARAM_INVALID_FORMAT, "videoid");
        Interceptor::ensureNotFalse($videoid >= 0, ERROR_PARAM_NOT_SMALL_ZERO, "videoid");
        Interceptor::ensureNotFalse(is_numeric($num), ERROR_PARAM_INVALID_FORMAT, "num");
        Interceptor::ensureNotFalse($num >= 0, ERROR_PARAM_NOT_SMALL_ZERO, "num");
        Interceptor::ensureFalse($num >= 30, ERROR_PARAM_INVALID_FORMAT, "num");

        $video = new Video();
        $video_info = $video->getVideoInfo($videoid);

        Interceptor::ensureNotEmpty($video_info, ERROR_BIZ_VIDEO_NOT_EXIST);

        $total      = Counter::increase(Counter::COUNTER_TYPE_VIDEO_PRAISES, $videoid, $num);

        $this->render(array("praises" => (int) $total));
    }

    public function watchedAction()
    {
        $videoid     = $this->getParam("videoid") ? strip_tags(trim($this->getParam("videoid")))   : 0;
        $num        = $this->getParam("num")     ? strip_tags(trim($this->getParam("num")))       : 1;

        Interceptor::ensureNotEmpty($videoid, ERROR_PARAM_IS_EMPTY, "videoid");
        Interceptor::ensureNotFalse(is_numeric($videoid), ERROR_PARAM_INVALID_FORMAT, "videoid");
        Interceptor::ensureNotFalse($videoid >= 0, ERROR_PARAM_NOT_SMALL_ZERO, "videoid");
        Interceptor::ensureNotFalse(is_numeric($num), ERROR_PARAM_INVALID_FORMAT, "num");
        Interceptor::ensureNotFalse($num >= 0, ERROR_PARAM_NOT_SMALL_ZERO, "num");
        Interceptor::ensureFalse($num >= 30, ERROR_PARAM_INVALID_FORMAT, "num");

        $video = new Video();
        $video_info = $video->getVideoInfo($videoid);

        Interceptor::ensureNotEmpty($video_info, ERROR_BIZ_VIDEO_NOT_EXIST);

        $total      = Counter::increase(Counter::COUNTER_TYPE_VIDEO_WATCHES, $videoid, $num);

        $this->render(array("watches"=>$total));
    }

    public function getVideoInfoAction()
    {
        $videoid = $this->getParam("videoid") ? intval(trim($this->getParam("videoid"))) : 0;
        Interceptor::ensureNotFalse($videoid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "videoid");

        $video = new Video();
        $video_info = $video->getVideoInfo($videoid);

        Interceptor::ensureNotEmpty($video_info, ERROR_BIZ_VIDEO_NOT_EXIST);

        $this->render(
            array(
            "video_info" => $video_info
            )
        );
    }

    public function getPraiseAction()
    {
        /*{{{通过用户id获取正在直播的用户*/
        $ids        = $this->getParam("videoids")              ? trim($this->getParam("videoids"))              : '';

        $uids = explode(',', $ids);

        $video_array = array();

        if (!empty($uids)) {
            foreach ($uids as $vid) {
                $video_array[$vid] = (int) Counter::get(Counter::COUNTER_TYPE_VIDEO_PRAISES, $vid);
            }
        }

        $this->render($video_array);
    }/*}}}*/

    public function deleteAction()
    {
        $videoid = $this->getParam("videoid") ? intval(trim($this->getParam("videoid"))) : 0;
        Interceptor::ensureNotFalse($videoid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "videoid");

        $video = new Video();
        $video_info = $video->getVideoInfo($videoid);

        Interceptor::ensureNotEmpty($video_info, ERROR_BIZ_VIDEO_NOT_EXIST);
        Interceptor::ensureNotFalse(Context::get("userid") == $video_info["uid"], ERROR_BIZ_VIDEO_NOT_OWNER);

        $video->delete($videoid, $video_info["uid"]);

        include_once 'process_client/ProcessClient.php';
        ProcessClient::getInstance("dream")->addTask("video_delete_control", $video_info);
        ProcessClient::getInstance("dream")->addTask("delete_feeds_worker", array('relateid' => $videoid, 'type' => Feeds::FEEDS_VIDEO, 'uid' => Context::get("userid")));

        $this->render();
    }

    public function adminDeleteAction()
    {
        $videoid = $this->getParam("videoid") ? intval(trim($this->getParam("videoid"))) : 0;
        Interceptor::ensureNotFalse($videoid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "videoid");

        $video = new Video();
        $video_info = $video->getVideoInfo($videoid);

        Interceptor::ensureNotEmpty($video_info, ERROR_BIZ_VIDEO_NOT_EXIST);

        $video->delete($videoid, $video_info["uid"]);

        Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $video_info["uid"], '很遗憾，您的视频存在违规行为，已经被删除。', '很遗憾，您的视频存在违规行为，已经被删除。', $videoid);

        include_once 'process_client/ProcessClient.php';
        ProcessClient::getInstance("dream")->addTask("delete_feeds_worker", array('relateid' => $videoid, 'type' => Feeds::FEEDS_VIDEO, 'uid' => Context::get("userid")));

        $this->render();
    }

    public function setCoverAction()
    {
        $videoid = $this->getParam("videoid") ? intval(trim($this->getParam("videoid"))) : 0;
        $cover = $this->getPost("cover") ? strip_tags(trim($this->getPost("cover"))) : "";

        Interceptor::ensureNotFalse($videoid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "videoid");
        Interceptor::ensureNotEmpty($cover, ERROR_PARAM_IS_EMPTY, "cover");
        Interceptor::ensureNotFalse(strlen($cover)<=300, ERROR_PARAM_INVALID_FORMAT, 'cover');

        $video = new Video();
        $video_info = $video->getVideoInfo($videoid);
        Interceptor::ensureNotEmpty($video_info, ERROR_BIZ_VIDEO_NOT_EXIST);
        Interceptor::ensureNotFalse(Context::get("userid") == $video_info["uid"], ERROR_BIZ_VIDEO_NOT_OWNER);

        try{
            $video = new Video();
            Interceptor::ensureNotFalse($video->setCover($videoid, $cover), ERROR_VIDEO_SET_COVER);
        }catch(Exception $e){
            Interceptor::ensureNotFalse(false, ERROR_VIDEO_SET_COVER);
        }

        include_once 'process_client/ProcessClient.php';
        ProcessClient::getInstance("dream")->addTask("video_sync_control", array('videoid'=>$videoid, 'cover'=>$cover, 'modtime'=>date('Y-m-d H:i:s')));

        $this->render();
    }

    public function adminAddAction()
    {
        $uid      = intval($this->getPost("userid"));
        $mp4      = $this->getPost("mp4") ? strip_tags(trim($this->getPost("mp4")))    : "";
        $cover    = $this->getPost("cover") ? strip_tags(trim($this->getPost("cover"))): "";

        Interceptor::ensureNotEmpty($uid, ERROR_PARAM_IS_EMPTY, "userid");
        Interceptor::ensureNotEmpty($mp4, ERROR_PARAM_IS_EMPTY, "mp4");
        Interceptor::ensureNotEmpty($cover, ERROR_PARAM_IS_EMPTY, "cover");

        $point    = "0,0";
        $province = "";
        $city     = "";
        $district = "";
        $location = "";
        $width    = 0;
        $height   = 0;
        $duration = 0;
        $content  = "";
        $iscapture = 'N';

        $video = new Video();

        $videoid = $video->add($uid, $mp4, $duration, $content, $cover, $width, $height, $point, $province, $city, $district, $location, $iscapture);

        $video_info = $video->getVideoInfo($videoid);
        $video_info = array(
            'videoid'  => $video_info['videoid'],
        'uid'      => $video_info['uid'],
        'url'      => $video_info['mp4'],
        'title'    => $video_info['title']?$video_info['title']:$video_info['videoid'],
        'addtime'  => $video_info['addtime'],
        'cover'    => $video_info['cover']?$video_info['cover']:$video_info['videoid'],
        );

        include_once 'process_client/ProcessClient.php';
        ProcessClient::getInstance("dream")->addTask("video_create_control", $video_info);

        $info = array(
            'uid'      => $video_info['uid'],
            'type'     => Feeds::FEEDS_VIDEO,//小视频
            'relateid' => $video_info['videoid'],
            'addtime'  => $video_info['addtime'],
        );
        
        //注释newsfeeds by yangqing
        //ProcessClient::getInstance("dream")->addTask("resources_add_newsfeeds", $info);

        $this->render(array("videoid" => $videoid,"addtime"=>$video_info['addtime']));
    }

    public function adminSetCoverAction()
    {
        $videoid = $this->getParam("videoid") ? intval(trim($this->getParam("videoid"))) : 0;
        $cover = $this->getPost("cover") ? strip_tags(trim($this->getPost("cover"))) : "";

        Interceptor::ensureNotFalse($videoid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "videoid");
        Interceptor::ensureNotEmpty($cover, ERROR_PARAM_IS_EMPTY, "cover");
        Interceptor::ensureNotFalse(strlen($cover)<=300, ERROR_PARAM_INVALID_FORMAT, 'cover');

        try{
            $video = new Video();
            Interceptor::ensureNotFalse($video->setCover($videoid, $cover), ERROR_VIDEO_SET_COVER);
        }catch(Exception $e){
            Interceptor::ensureNotFalse(false, ERROR_VIDEO_SET_COVER);
        }

        $this->render();
    }
}
?>
