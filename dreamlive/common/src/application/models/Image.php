<?php
class Image
{
    const PRAISE_REDIS_KEY = 'image_praise';
    
    
    public function add($uid, $content, $url, $width, $height, $point, $province, $city, $district, $location,$iscapture)
    {
        $imageid = Counter::increase(Counter::COUNTER_TYPE_FEEDS, "idgen");
        Interceptor::ensureNotFalse($imageid > 0, ERROR_BIZ_COUNTER_BUSY_RETRY);

        $extends = array(
            "network"   => Context::get("network"),
            "brand"     => Context::get("brand"),
            "model"     => Context::get("model"),
            "version"   => Context::get("version"),
            "platform"  => Context::get("platform"),
            "deviceid"  => Context::get("deviceid"),
            "ip"        => Util::getIP()
        );
        $extends = json_encode($extends);

        $dao_image = new DAOImage();
        try {
            $dao_image->startTrans();

            $dao_image->add($imageid, $uid, $content, $url, $width, $height, $point, $province, $city, $district, $location, $extends, $iscapture);
            $dao_user_feeds = new DAOUserFeeds($uid);
            if($iscapture=='Y') {
                $feedsType = Feeds::FEEDS_IMAGE_SCREEN;
            }else{
                $feedsType = Feeds::FEEDS_IMAGE;
                $dao_user_feeds->addUserFeeds($uid, $feedsType, $imageid, date("Y-m-d H:i:s"));
            }
            //$dao_user_feeds->addUserFeeds($uid, $feedsType, $imageid,date("Y-m-d H:i:s"));

            //by yangqing
            //$dao_feeds = new DAOFeeds();
            //$dao_feeds->addFeeds($uid, $imageid, Feeds::FEEDS_IMAGE);

            $dao_image->commit();
        } catch (MySQLException $e) {
            $dao_image->rollback();
            throw new BizException(ERROR_SYS_DB_SQL);
        }

        $total = $dao_user_feeds->getUserFeedsTotal($uid);
        Counter::set(Counter::COUNTER_TYPE_PASSPORT_FEEDS_NUM, $uid, $total);

        // flush cache
        $image_info = $this->getImageInfo($imageid);
        return $imageid;
    }

    public function delete($imageid, $uid)
    {
        $dao_image = new DAOImage();
        try {
            $dao_image->startTrans();

            $dao_image->delImage($imageid);

            $dao_user_feeds = new DAOUserFeeds($uid);
            $dao_user_feeds->delUserFeeds($imageid);

            //by yangqing
            //$dao_feeds    = new DAOFeeds();
            //$dao_feeds->delFeeds($imageid);

            $dao_image->commit();
        } catch (MySQLException $e) {
            $dao_image->rollback();
            throw new BizException(ERROR_SYS_DB_SQL);
        }

        $total = $dao_user_feeds->getUserFeedsTotal($uid);
        Counter::set(Counter::COUNTER_TYPE_PASSPORT_FEEDS_NUM, $uid, $total);

        $key = "image_cache_$imageid";
        $cache = Cache::getInstance("REDIS_CONF_CACHE", $imageid);
        $cache->delete($key);

        return true;
    }

    public function getImageInfo($imageid)
    {
        $key = "image_cache_$imageid";
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        
        if ((($image_info = $cache->get($key)) !== false)) {
            $image_info = json_decode($image_info, true);
            if (! empty($image_info["url"])) {
                if (strpos($image_info['url'], 'http://') === false) {
                    $image_info['url'] = STATIC_DOMAIN_NAME . $image_info['url'];
                }
                
                $praises = Counter::get(Counter::COUNTER_TYPE_IMAGE_PRAISES, $imageid);
                $watches = Counter::get(Counter::COUNTER_TYPE_IMAGE_WATCHES, $imageid);
                $praises_status = $cache->get(Image::PRAISE_REDIS_KEY. $imageid . "_" . Context::get("userid"));
                $image_info['praise_status'] =(int) empty($praises_status) ? 0 : 1;
                $image_info['praises'] = (int) $praises;
                $image_info['watches'] =(int) $watches;
                $image_info['extends'] = json_decode($image_info['extends'], true);
                return $image_info;
            }
        }

        $dao_image = new DAOImage();
        $image_info = $dao_image->getImageInfo($imageid);

        if (is_array($image_info)) {
            $cache->set($key, json_encode($image_info));
        }
        
        if (empty($image_info)) {
            return array();
        }
        
        $praises = Counter::get(Counter::COUNTER_TYPE_IMAGE_PRAISES, $imageid);
        $watches = Counter::get(Counter::COUNTER_TYPE_IMAGE_WATCHES, $imageid);
        
        $praises_status = $cache->get(Image::PRAISE_REDIS_KEY. $imageid . "_" . Context::get("userid"));
        $image_info['praise_status'] =(int) empty($praises_status) ? 0 : 1;
        $image_info['praises'] = (int) $praises;
        $image_info['watches'] =(int) $watches;
        $image_info['extends'] = json_decode($image_info['extends'], true);
        
        if (!empty($image_info['uid'])) {
            $userinfo = User::getUserInfo($image_info['uid']);
            $image_info['nickname'] = $userinfo['nickname'];
        }
        
        // 信息改变发送同步到运营后台 先注掉
        // require_once('process_client/ProcessClient.php');
        // ProcessClient::getInstance("dream")->addTask("image_data_sync_admin", $image_info);
        
        if (strpos($image_info['url'], 'http://') === false) {
            $image_info['url'] = STATIC_DOMAIN_NAME. $image_info['url'];
        }
        
        return $image_info;
    }

    public static function quality($data, $qulity = 70)
    {
        $im = new Imagick();
        $im->readImageBlob($data);
        $im->setImageCompressionQuality($qulity);

        $data = $im->getImageBlob();

        $im->destroy();

        return $data;
    }

    public static function thumbnail($data, $width, $height, $qulity = 70)
    {
        $im = new Imagick();
        $im->readImageBlob($data);

        $size = $im->getImagePage();
        $src_width = $size['width'];
        $src_height = $size['height'];
        $crop_x = 0;
        $crop_y = 0;

        $crop_w = $src_width;
        $crop_h = $src_height;

        if ($src_width * $height > $src_height * $width) {
            $crop_w = intval($src_height * $width / $height);
        } else {
            $crop_h = intval($src_width * $height / $width);
        }

        $crop_x = intval(($src_width - $crop_w) / 2);

        $im->setImageCompressionQuality($qulity);
        $im->modulateImage(105, 100, 100);

        $im->cropImage($crop_w, $crop_h, $crop_x, $crop_y);
        $im->thumbnailImage($width, $height, true);

        $data = $im->getImageBlob();
        $im->destroy();

        return $data;
    }
}
?>
