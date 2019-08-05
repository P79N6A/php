<?php
class Storage
{
    private $_client = null;
    private $_bucket = null;

    public function __construct()
    {
        $config = Context::getConfig("STORAGE_CONF");
        $this->_bucket = $config['bucket'];
        try {
            $this->_client = new Qcloud\Cos\Client($config);
        } catch (Exception $e) {
            Logger::log("storage_connect_err", $e->getMessage(), array("errno" => $e->getCode()));

            throw new BizException(ERROR_COS_CONNECT_FAILD, $e->getCode());
        }
    }

    public function set($key, $data)
    {
        try {
            $this->_client->putObject(array('Bucket' => $this->_bucket, 'Key' => $key, 'Body' => $data));
        } catch (Exception $e) {
            Logger::log("storage_set_err", $e->getMessage(), array("key" => $key,"errno" => $e->getCode()));

            throw new BizException(ERROR_COS_PUT_FAILD, $e->getCode());
        }

        return true;
    }

    public function get($key, $options)
    {
        try {
            $options['Bucket'] = $this->_bucket;
            $options['Key'] = $key;

            try {
                $data = $this->_client->getObject($options);

            } catch (\Exception $e) {
                // echo "$e\n";
            }

        } catch (Exception $e) {
            Logger::log("storage_get_err", $e->getMessage(), array("key" => $key,"errno" => $e->getCode()));
            throw new BizException(ERROR_COS_GET_FAILD, $e->getCode());
        }

        return $data['Body'];
    }

    public function addImage($suffix, $data, $kind)
    {
        // $data = Image::squareImage($data);
        if(strlen($data) > 204800){
            $data = Image::quality($data, 75);
        }

        $front_name = "images/".md5($data);
        $filename = $front_name.".".$suffix;

        $url = $this->addFile($filename, $data);

        $thumb_config = array(
            'default' => array(
                array(400,400),
                array(200,200),
            ),
        );

        if(isset($thumb_config[$kind]) && !empty($thumb_config[$kind])) {
            foreach ($thumb_config[$kind] as $thumb) {
                list($width, $height) = $thumb;

                $data = Image::thumbnail($data, $width, $height, 90);

                $filename = $front_name. "_".$width."-".$height.".".$suffix;

                $this->addFile($filename, $data, $throuth);
            }
        }

        return $url;
    }

    public function addImageCache($suffix, $data, $expire)
    {

        $front_name = "images/cache/".md5($data);
        $filename = $front_name.".".$suffix;
        $cache = Cache::getInstance("REDIS_CONF_WXTOKEN");

        $cache->set($filename, $data, $expire);

        return Context::getConfig("STATIC_DOMAIN")."/" . $filename;
    }

    public function addVideo($suffix, $data)
    {
        $front_name = "video/".md5($data);
        $filename = $front_name.".".$suffix;

        $url = $this->addFile($filename, $data);

        return $url;
    }

    public function addFile($filename, $data)
    {
        $key = md5($filename);

        $this->set($key, $data);

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


    public function getObjectMeta($filename)
    {
        $key = md5($filename);

        try {
            $data = $this->_client->headObject(array(
                'Bucket' => $this->_bucket,
                'Key' => $key,
            ));
        } catch (Exception $e) {
            Logger::log("storage_get_err", $e->getMessage(), array("key" => $key,"errno" => $e->getCode()));
            throw new BizException(ERROR_COS_GET_FAILD);
        }

        return $data;
    }

}
?>