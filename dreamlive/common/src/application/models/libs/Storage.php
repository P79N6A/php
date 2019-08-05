<?php
class Storage
{
    private $_client = null;
    private $_bucket = null;
    const SLICE_MIN_SIZE = 102400;
    const SLICE_MAX_SIZE = 512000;//
    const REDIS_KEY = "dreamlive_storage_aliyun_";

    public function __construct()
    {
        $config = Context::getConfig("STORAGE_CONF");
        
        $this->_bucket = $config['bucket'];
        
        try {
            $this->_client = new \OSS\OssClient($config['accessKeyId'], $config['accessKeySecret'], $config['endpoint'], false);
        } catch (Exception $e) {
            Logger::log("storage_connect_err", $e->getMessage(), array("errno" => $e->getCode()));
            
            throw new BizException(ERROR_OSS_CONNECT_FAILD, $e->getCode());
        }
    }

    public function set($key, $data)
    {
        try {
            $this->_client->putObject($this->_bucket, $key, $data);
        } catch (Exception $e) {
            Logger::log("storage_set_err", $e->getMessage(), array("key" => $key,"errno" => $e->getCode()));
            
            throw new BizException(ERROR_OSS_PUT_FAILD, $e->getCode());
        }
        
        return true;
    }

    public function get($key, $options)
    {
        try {
            $data = $this->_client->getObject($this->_bucket, $key, $options);
        } catch (Exception $e) {
            Logger::log("storage_get_err", $e->getMessage(), array("key" => $key,"errno" => $e->getCode()));
            throw new BizException(ERROR_OSS_GET_FAILD, $e->getCode());
        }
        
        return $data;
    }

    public function delete($key, $throuth = false)
    {
        try {
            $redis = $this->getRedis($key);

            if(!$throuth) {
                $count = $redis->hGet(self::getRedisHash($key), $key);
                if($count>1) {
                    $redis->hIncrBy(self::getRedisHash($key), $key, -1);
                    return;
                }
            }
 
            $redis->hDel(self::getRedisHash($key), $key);
            if ($this->_client->doesObjectExist($this->_bucket, $key)) {
                $this->_client->deleteObject($this->_bucket, $key);
            }
        } catch (Exception $e) {
            Logger::log("storage_delete_err", $e->getMessage(), array("key" => $key, "errno" => $e->getCode()));
            
            throw new BizException(ERROR_OSS_DELETE_FAILD, $e->getCode());
        }
    }
    
    public function addImage($suffix, $data, $kind, $throuth)
    {
        if(strlen($data) > 204800) {
            $data = Image::quality($data, 95);
        }

        $front_name = "images/".md5($data);
        $filename = $front_name.".".$suffix;

        $url = $this->addFile($filename, $data, $throuth);

        $thumb_config = array(
            'avatar' => array(
                array(800,800),
                array(324,324),
                array(200,200),
                array(100,100)
            ),
            'cover' => array(
                array(800,800),
                array(324,324),
                array(100,100)
            ),
        );
    
        if(isset($thumb_config[$kind]) && !empty($thumb_config[$kind])) {
            list($w, $h) = getimagesize('data://image/jpeg;base64,'. base64_encode($data));
            $h = $h ? $h : 1000;
            $w = $w ? $w : 1000;
            foreach ($thumb_config[$kind] as $thumb) {
                list($width, $height) = $thumb;

                if($h>$height) {
                    $data = Image::thumbnail($data, $width, $height);
                }else{
                    if($w>$h) {
                        $w = $h;
                    }else{
                        $h = $w;
                    }
                    
                    $data = Image::thumbnail($data, $w, $h, 90);
                }

                $filename = $front_name. "_".$width."-".$height.".".$suffix;
    
                $this->addFile($filename, $data, $throuth);
            }
        }
    
        return $url;
    }
    
    public function addFile($filename, $data, $throuth = false)
    {
        $key = md5($filename);

        $this->set($key, $data);

        if(!$throuth) {
            $this->getRedis($key)->hIncrBy(self::getRedisHash($key), $key, 1);
        }
    
        return Context::getConfig("STATIC_DOMAIN")."/" . $filename;
    }
    
    public function getContent($filename, $options = array())
    {
        $key = md5($filename);
        $data = $this->get($key, $options);

        return $data;
    }
    
    public static function getMimeType($filename)
    {
        $suffix = ".".end(explode(".", $filename));
    
        $types = array(
            ".webp" => "image/webp",
            ".jpeg" => "image/jpeg",
            ".jpg" => "image/jpeg",
            ".ico" => "image/x-icon",
            ".gif" => "image/gif",
            ".png" => "image/png",
            ".js" => "application/javascript",
            ".css" => "text/css",
            ".xml" => "text/xml",
            ".txt" => "text/plain",
            ".htm" => "text/html",
            ".html" => "text/html",
            ".php" => "text/html",
            ".swf" => "application/x-shockwave-flash",
            ".apk" => "application/vnd.android.package-archive",
            ".ipa" => "application/iphone",
            ".bin" => "application/octet-stream",
            ".exe" => "application/octet-stream",
            ".dll" => "application/octet-stream",
            ".class" => "application/octet-stream",
            ".zip" => "application/zip",
            ".dot" => "application/msword",
            ".doc" => "application/msword",
            // audio
            ".au" => "audio/basic",
            ".snd" => "audio/basic",
            ".mid" => "audio/mid",
            ".rmi" => "audio/mid",
            ".mp3" => "audio/mpeg",
            ".aif" => "audio/x-aiff",
            ".aifc" => "audio/x-aiff",
            ".aiff" => "audio/x-aiff",
            ".wav" => "audio/x-wav",
            ".amr" => "audio/amr",
            // video
            ".mp4" => "video/mp4",
            ".mp2" => "video/mpeg",
            ".mpa" => "video/mpeg",
            ".mpeg" => "video/mpeg",
            ".mpg" => "video/mpeg",
            ".mpv2" => "video/mpeg",
            ".mov" => "video/quicktime"
        );
    
        return isset($types[$suffix]) ? $types[$suffix] : "text/plain";
    }

    public function createTask($key)
    {
        try {
            $result = $this->_client->initiateMultipartUpload($this->_bucket, $key);
        } catch (Exception $e) {
            Logger::log("storage_task_err", $e->getMessage(), array("key" => $key,"errno" => $e->getCode()));
            throw new BizException(ERROR_OSS_PUT_FAILD, $e->getCode());
        }

        return $result;
    }

    public function uploadSlice($key, $uploadId, $filename, $num)
    {
        $upOptions = array(
            "fileUpload" => $filename,
            "partNumber" => $num,
        );
        try {
            $return = $this->_client->uploadPart($this->_bucket, $key, $uploadId, $upOptions);
        } catch (Exception $e) {
            Logger::log("storage_task_err", $e->getMessage(), array("key" => $key,"errno" => $e->getCode()));
            throw new BizException(ERROR_OSS_PUT_FAILD, $e->getCode());
        }

        return $return;
    }

    public function completeTask($key, $uploadId, $uploadParts, $throuth = false)
    {
        try {
            $this->_client->completeMultipartUpload($this->_bucket, $key, $uploadId, $uploadParts);
            if(!$throuth) {
                $this->getRedis($key)->hIncrBy(self::getRedisHash($key), $key, 1);
            }
        } catch (Exception $e) {
            Logger::log("storage_task_err", $e->getMessage(), array("key" => $key,"errno" => $e->getCode()));
            throw new BizException(ERROR_OSS_PUT_FAILD, $e->getCode());
        }

        return true;
    }

    public function listParts($key, $uploadId)
    {
        try {
            $listPartsInfo = $this->_client->listParts($this->_bucket, $key, $uploadId);
        } catch (OssException $e) {
            Logger::log("storage_task_err", $e->getMessage(), array("key" => $key,"errno" => $e->getCode()));
            throw new BizException(ERROR_OSS_PUT_FAILD, $e->getCode());
        }
        return $listPartsInfo->getListPart();
    }

    public function abortTask($key, $uploadId)
    {
        try {
            $this->_client->abortMultipartUpload($this->_bucket, $key, $uploadId);
        } catch (OssException $e) {
            Logger::log("storage_task_err", $e->getMessage(), array("key" => $key,"errno" => $e->getCode()));
            throw new BizException(ERROR_OSS_PUT_FAILD, $e->getCode());
        }
        return true;
    }

    public static function getSlices($filesize, $netspeed = 0, $network = null, $slicesize = 0)
    {
        /*{{{*/
        if($slicesize) {
            if($slicesize > $filesize) {
                $slicesize = $filesize;
            }
            if($slicesize < self::SLICE_MIN_SIZE) {
                $slicesize = self::SLICE_MIN_SIZE;
            }
        } elseif($netspeed) {
            $slicesize = $netspeed * 4;

            if($slicesize > self::SLICE_MAX_SIZE) {
                $slicesize = self::SLICE_MAX_SIZE;
            } elseif($slicesize < self::SLICE_MIN_SIZE) {
                $slicesize = self::SLICE_MIN_SIZE;
            }
        } else {
            switch($network) {
            case "wifi":
                $slicesize = self::SLICE_MAX_SIZE;
                break;
            case "2g":
            case "3g":
                $slicesize = self::SLICE_MIN_SIZE;
                break;
            default:
                $slicesize = self::SLICE_MAX_SIZE;
            }
        }

        return array($slicesize, ceil($filesize/$slicesize));
    }/*}}}*/

    public function getObjectMeta($filename)
    {
        $key = md5($filename);
        
        try {
            $data = $this->_client->getObjectMeta($this->_bucket, $key);
        } catch (Exception $e) {
            Logger::log("storage_get_err", $e->getMessage(), array("key" => $key,"errno" => $e->getCode()));
            throw new BizException(ERROR_OSS_GET_FAILD, $e->getCode());
        }
        
        return $data;
    }
    public static function getRedisHash($key)
    {
        return self::REDIS_KEY. abs(crc32($key))%1000;
    }
    public function getRedis($key)
    {
        
        return Cache::getInstance("REDIS_CONF_COUNTER");
    }

}
?>