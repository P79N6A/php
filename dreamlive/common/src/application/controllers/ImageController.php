<?php
class ImageController extends BaseController
{
    public function addAction()
    {
        $point = $this->getPost("point") ? strip_tags(trim($this->getPost("point"))) : "";
        $url = $this->getPost("url") ? strip_tags(trim($this->getPost("url"))) : "";
        $content = $this->getPost("content") ? strip_tags(trim($this->getPost("content"))) : "";
        $province = $this->getPost("province") ? strip_tags(trim($this->getPost("province"))) : "";
        $city = $this->getPost("city") ? strip_tags(trim($this->getPost("city"))) : "";
        $district = $this->getPost("district") ? strip_tags(trim($this->getPost("district"))) : "";
        $location = $this->getPost("location") ? strip_tags(trim($this->getPost("location"))) : "";
        $width = $this->getPost("width") ? intval($this->getPost("width")) : 0;
        $height = $this->getPost("height") ? intval($this->getPost("height")) : 0;
        $iscapture = $this->getPost("iscapture") ? $this->getPost("iscapture") : 'N';//是否是截图
        
        Interceptor::ensureNotEmpty($point, ERROR_PARAM_IS_EMPTY, "point");
        Interceptor::ensureNotFalse(preg_match("/-?[\d\.]+,-?[\d\.]+/", $point) > 0, ERROR_PARAM_INVALID_FORMAT, "point");
        Interceptor::ensureNotFalse($width > 0, ERROR_PARAM_NOT_SMALL_ZERO, "width");
        Interceptor::ensureNotFalse($height > 0, ERROR_PARAM_NOT_SMALL_ZERO, "height");
        Interceptor::ensureNotEmpty($url, ERROR_PARAM_IS_EMPTY, "url");
        
        if (strpos($url, 'http://') !== false) {
            $url= str_replace(STATIC_DOMAIN_NAME, '', $url);
        }
        
        $image = new Image();
        $imageid = $image->add(Context::get("userid"), $content, $url, $width, $height, $point, $province, $city, $district, $location, $iscapture);

        $image_info = $image->getImageInfo($imageid);
        
        if (strpos($image_info['url'], 'http://') !== false) {
            $image_info['url'] = str_replace(STATIC_DOMAIN_NAME, '', $image_info['url']);
        }
        
        $image_info = array(
            'imageid'  => $image_info['imageid'],
        'uid'      => $image_info['uid'],
        'url'      => $image_info['url'],
        'title'    => $image_info['title'],
        'width'    => $image_info['width'],
        'height'   => $image_info['height'],
        'point'    => $image_info['point'],
        'province' => $image_info['province'],
        'city'     => $image_info['city'],
        'district' => $image_info['district'],
        'location' => $image_info['location'],
        'addtime'  => $image_info['addtime'],
        'extends'  => $image_info['extends'],
        'iscapture'=> $image_info['iscapture'],
        );
        
        if ($iscapture=='N') {
            include_once 'process_client/ProcessClient.php';
            ProcessClient::getInstance("dream")->addTask("image_create_control", $image_info);
            
            $info = array(
                'uid'      => $image_info['uid'],
                'type'     => Feeds::FEEDS_IMAGE,//图片
                'relateid' => $image_info['imageid'],
                'addtime'  => $image_info['addtime'],
                'iscapture'=> $image_info['iscapture'],
            );
            //注释newsfeeds by yangqing
               //ProcessClient::getInstance("dream")->addTask("resources_add_newsfeeds", $info);
        }
        
        $this->render(array("imageid" => $imageid,"addtime"=>$image_info['addtime']));
    }
    
    public function praiseAction()
    {
        $imageid     = $this->getParam("imageid") ? strip_tags(trim($this->getParam("imageid")))   : 0;
        $num        = $this->getParam("num")     ? strip_tags(trim($this->getParam("num")))       : 1;
        
        $userid     = Context::get("userid");
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $praise_redis_key = Image::PRAISE_REDIS_KEY. $imageid . "_" . $userid;
        
        if ($cache->get($praise_redis_key) > 0) {
            $total      = Counter::get(Counter::COUNTER_TYPE_IMAGE_PRAISES, $imageid, $num);
            $this->render(array("praises"=>$total));
        }
        
        $cache->incr($praise_redis_key);
        
        Interceptor::ensureNotEmpty($imageid, ERROR_PARAM_IS_EMPTY, "imageid");
        Interceptor::ensureNotFalse(is_numeric($imageid), ERROR_PARAM_INVALID_FORMAT, "imageid");
        Interceptor::ensureNotFalse($imageid >= 0, ERROR_PARAM_NOT_SMALL_ZERO, "imageid");
        Interceptor::ensureNotFalse(is_numeric($num), ERROR_PARAM_INVALID_FORMAT, "num");
        Interceptor::ensureNotFalse($num >= 0, ERROR_PARAM_NOT_SMALL_ZERO, "num");
        Interceptor::ensureFalse($num >= 30, ERROR_PARAM_INVALID_FORMAT, "num");
    
        $image = new Image();
        $image_info = $image->getImageInfo($imageid);
        
        Interceptor::ensureNotEmpty($image_info, ERROR_BIZ_IMAGE_NOT_EXIST);
    
        $total      = Counter::increase(Counter::COUNTER_TYPE_IMAGE_PRAISES, $imageid, $num);
    
        $this->render(array("praises"=>$total));
    }
    
    public function watchedAction()
    {
        $imageid     = $this->getParam("imageid") ? strip_tags(trim($this->getParam("imageid")))   : 0;
        $num        = $this->getParam("num")     ? strip_tags(trim($this->getParam("num")))       : 1;
    
        Interceptor::ensureNotEmpty($imageid, ERROR_PARAM_IS_EMPTY, "imageid");
        Interceptor::ensureNotFalse(is_numeric($imageid), ERROR_PARAM_INVALID_FORMAT, "imageid");
        Interceptor::ensureNotFalse($imageid >= 0, ERROR_PARAM_NOT_SMALL_ZERO, "imageid");
        Interceptor::ensureNotFalse(is_numeric($num), ERROR_PARAM_INVALID_FORMAT, "num");
        Interceptor::ensureNotFalse($num >= 0, ERROR_PARAM_NOT_SMALL_ZERO, "num");
        Interceptor::ensureFalse($num >= 30, ERROR_PARAM_INVALID_FORMAT, "num");
        
        $image = new Image();
        $image_info = $image->getImageInfo($imageid);
        
        Interceptor::ensureNotEmpty($image_info, ERROR_BIZ_IMAGE_NOT_EXIST);
        
        $total      = Counter::increase(Counter::COUNTER_TYPE_IMAGE_WATCHES, $imageid, $num);
    
        $this->render(array("watches"=>$total));
    }
    
    public function getImageInfoAction()
    {
        $imageid = $this->getParam("imageid") ? trim($this->getParam("imageid")) : 0;
        Interceptor::ensureNotFalse($imageid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "imageid");
        
        $image = new Image();
        $image_info = $image->getImageInfo($imageid);
        
        Interceptor::ensureNotEmpty($image_info, ERROR_BIZ_IMAGE_NOT_EXIST);
        
        $this->render(array("image_info" => $image_info));
    }

    public function deleteAction()
    {
        $imageid = $this->getParam("imageid") ? intval(trim($this->getParam("imageid"))) : 0;
        Interceptor::ensureNotFalse($imageid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "imageid");
        
        $image = new Image();
        $image_info = $image->getImageInfo($imageid);
        
        Interceptor::ensureNotEmpty($image_info, ERROR_BIZ_IMAGE_NOT_EXIST);
        Interceptor::ensureNotFalse(Context::get("userid") == $image_info["uid"], ERROR_BIZ_IMAGE_NOT_OWNER);
        
        $image->delete($imageid, $image_info["uid"]);

        include_once 'process_client/ProcessClient.php';
        ProcessClient::getInstance("dream")->addTask("image_delete_control", $image_info);
        ProcessClient::getInstance("dream")->addTask("delete_feeds_worker", array('relateid' => $imageid, 'type' => Feeds::FEEDS_IMAGE, 'uid' => Context::get("userid")));
        
        $this->render();
    }
    
    public function adminDeleteAction()
    {
        $imageid = $this->getParam("imageid") ? intval(trim($this->getParam("imageid"))) : 0;
        Interceptor::ensureNotFalse($imageid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "imageid");
    
        $image = new Image();
        $image_info = $image->getImageInfo($imageid);
    
        Interceptor::ensureNotEmpty($image_info, ERROR_BIZ_IMAGE_NOT_EXIST);

        Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $image_info["uid"], '很遗憾，您的图片存在违规行为，已经被删除。', '很遗憾，您的图片存在违规行为，已经被删除。', $imageid);
        
        $image->delete($imageid, $image_info["uid"]);
        
        include_once 'process_client/ProcessClient.php';
        ProcessClient::getInstance("dream")->addTask("delete_feeds_worker", array('relateid' => $imageid, 'type' => Feeds::FEEDS_IMAGE, 'uid' => Context::get("userid")));
        

        $this->render();
    }
}
?>