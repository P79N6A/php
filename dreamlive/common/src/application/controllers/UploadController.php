<?php
class UploadController extends BaseController
{
    public function uploadFileAction()
    {
        $filename = $this->getParam("filename") ? trim(strip_tags($this->getParam("filename"))): "";
        $filename = ltrim($filename, "/");
        $throuth  = trim($this->getParam("throuth", false));

        $file = $_FILES["file"];

        Interceptor::ensureNotEmpty($filename, ERROR_PARAM_IS_EMPTY, "filename");
        Interceptor::ensureNotFalse(UPLOAD_ERR_OK == $file["error"], ERROR_PARAM_IS_EMPTY, "file");
        Interceptor::ensureNotFalse(file_exists($file["tmp_name"]), ERROR_PARAM_NOT_EXIST, "tmp_name");

        $data = file_get_contents($file["tmp_name"]);

        $storage = new Storage();
        $url = $storage->addFile($filename, $data, $throuth);

        $this->render(array("url" => $url));
    }

    public function uploadImageAction()
    {
        $kind    = $this->getParam("kind")? $this->getParam("kind"): "";
        $data    = $this->getParam("data")? $this->getParam("data"): "";
        $file    = $_FILES["file"];
        $throuth = trim($this->getParam("throuth", false));

        Interceptor::ensureNotFalse(UPLOAD_ERR_OK == $file["error"], ERROR_PARAM_IS_EMPTY, "file");

        $data = $file['tmp_name']?file_get_contents($file["tmp_name"]) : urldecode($data);

        $bin = substr($data, 0, 2);
        $str = @unpack("C2c", $bin);
        $typeCode = intval($str['c1'].$str['c2']);

        switch($typeCode){
        case 255216:
            $suffix = 'jpg';
            break;
        case 7173:
            $suffix = 'gif';
            break;
        case 6677:
            $suffix = 'bmp';
            break;
        case 13780:
            $suffix = 'png';
            break;
        case 8273:
            $suffix = 'webp';
            break;
        default:
            $suffix = 'unknown';
        }

        Interceptor::ensureFalse($suffix == 'unknown', ERROR_PARAM_IS_EMPTY, "file");

        $storage = new Storage();
        $url = $storage->addImage($suffix, $data, $kind, $throuth);

        $this->render(array("url" => $url));
    }

    public function createTaskAction()
    {
        /*{{{*/
        $filename   = trim($this->getParam("filename"));
        $filesize   = (int)trim($this->getParam("filesize"));
        $netspeed   = (int)trim($this->getParam("netspeed"));
        $network    = trim($this->getParam("network"));
        $slicesize  = (int)trim($this->getParam("slicesize"));

        Interceptor::ensureNotFalse($filesize >= 102400, ERROR_PARAM_INVALID_FORMAT, "filesize");
        Interceptor::ensureNotEmpty($filename, ERROR_PARAM_INVALID_FORMAT, "filename");

        list($slicesize, $slices) = Storage::getSlices($filesize, $netspeed, $network, $slicesize);

        $suffix = pathinfo($filename, PATHINFO_EXTENSION);
        Interceptor::ensureNotFalse(in_array($suffix, array("mp4","avi","apk","jpg","bmp","png","gif", "ipa")), ERROR_OSS_UNSUPPORTED_TYPE);

        if(in_array($suffix, array("apk", "ipa"))) {
            $filename = "downloads/". $filename;
        }elseif(in_array($suffix, array("jpg","bmp","png","gif"))) {
            $front_name = "largeimages/". md5(microtime(true).rand(100000, 999999));
            $filename   = $front_name.".".$suffix;
        }else{
            $front_name = "video/". md5(microtime(true).rand(100000, 999999));
            $filename   = $front_name.".".$suffix;
        }

        $storage = new Storage();
        $uploadId = $storage->createTask(md5($filename));

        $dao = new DAOUploadTask();
        $dao->addTask(Context::get("userid"), $uploadId, $filename, "", $filesize, $netspeed, $network);

        $this->render(array("uploadid" => $uploadId, "filename" => $filename, "slicesize" => $slicesize, "slices" => $slices));
    }/*}}}*/

    public function uploadPartAction()
    {
        /*{{{*/
        $filename   = trim($this->getParam("filename"));
        $uploadId   = trim($this->getParam("uploadid"));
        $partNumber = (int)trim($this->getParam("partnumber"));
        $md5        = trim($this->getParam("md5"));

        $file = $_FILES["file"];

        Interceptor::ensureNotEmpty($filename, ERROR_PARAM_INVALID_FORMAT, "filename");
        Interceptor::ensureNotEmpty($uploadId, ERROR_PARAM_INVALID_FORMAT, "uploadid");
        Interceptor::ensureNotFalse($partNumber>0, ERROR_PARAM_INVALID_FORMAT, "partnumber");
        Interceptor::ensureNotFalse(UPLOAD_ERR_OK === $_FILES["file"]["error"], ERROR_PARAM_INVALID_FORMAT, $_FILES["file"]["error"]);

        Interceptor::ensureNotFalse($md5 == md5_file($file["tmp_name"]), ERROR_PARAM_INVALID_FORMAT, "md5");

        $storage = new Storage();
        $etag = $storage->uploadSlice(md5($filename), $uploadId, $file["tmp_name"], $partNumber);

        $dao = new DAOUploadPart();
        $dao->addPart($uploadId, $partNumber, $md5, $etag);

        $this->render();
    }/*}}}*/

    public function completeTaskAction()
    {
        /*{{{*/
        $filename = trim($this->getParam("filename"));
        $uploadId = trim($this->getParam("uploadid"));
        $md5      = trim($this->getParam("md5"));
        $throuth  = trim($this->getParam("throuth", false));

        Interceptor::ensureNotEmpty($filename, ERROR_PARAM_INVALID_FORMAT, "filename");
        Interceptor::ensureNotEmpty($uploadId, ERROR_PARAM_INVALID_FORMAT, "uploadid");

        $dao = new DAOUploadPart();
        $allPart = $dao->getAllPartHash($uploadId);
        $tempHash = '';
        foreach($allPart as $v){
            $tempHash .= $v['hash'];
        }

        Interceptor::ensureNotFalse($md5 == md5($tempHash), ERROR_PARAM_INVALID_FORMAT, "md5");

        $storage = new Storage();
        $list = $storage->listParts(md5($filename), $uploadId);

        $uploadParts = array();
        foreach($list as $k=> $v){
            $uploadParts[] = array(
                'PartNumber' => $v->getPartNumber(),
                'ETag' => $v->getETag(),
            );
        }

        $storage = new Storage();
        $storage->completeTask(md5($filename), $uploadId, $uploadParts, $throuth);

        $url = Context::getConfig("STATIC_DOMAIN")."/" . $filename;

        $this->render(array('url'=>$url));
    }/*}}}*/

    public function abortTaskAction()
    {
        /*{{{*/
        $filename = trim($this->getParam("filename"));
        $uploadId = trim($this->getParam("uploadid"));

        Interceptor::ensureNotEmpty($filename, ERROR_PARAM_INVALID_FORMAT, "filename");
        Interceptor::ensureNotEmpty($uploadId, ERROR_PARAM_INVALID_FORMAT, "uploadid");

        $storage = new Storage();
        $storage->abortTask(md5($filename), $uploadId);

        $this->render();
    }/*}}}*/

    public function listTaskAction()
    {
        /*{{{*/
        $filename = trim($this->getParam("filename"));
        $uploadId = trim($this->getParam("uploadid"));

        Interceptor::ensureNotEmpty($filename, ERROR_PARAM_INVALID_FORMAT, "filename");
        Interceptor::ensureNotEmpty($uploadId, ERROR_PARAM_INVALID_FORMAT, "uploadid");

        $storage = new Storage();
        $list = $storage->listParts(md5($filename), $uploadId);

        $uploadParts = array();
        foreach($list as $k=> $v){
            $uploadParts[] = array(
                'PartNumber' => $v->getPartNumber(),
                'ETag' => $v->getETag(),
            );
        }

        $this->render($uploadParts);
    }/*}}}*/

    public function testAction()
    {
        $file = $_FILES["file"];

        Interceptor::ensureNotNull($file, ERROR_PARAM_IS_EMPTY, "file");
        Interceptor::ensureNotFalse(UPLOAD_ERR_OK == $file["error"], ERROR_PARAM_IS_EMPTY, "file");

        $this->render();
    }

    public function delFileAction()
    {
        $url = trim(strip_tags($this->getParam('url', '')));
        Interceptor::ensureNotEmpty($url, ERROR_PARAM_IS_EMPTY, 'url');
        Interceptor::ensureNotFalse($this->filterUrl($url), ERROR_PARAM_INVALID_FORMAT, 'url');

        $url_info = parse_url($url);

        $storage = new Storage();
        $storage->delete(md5(substr($url_info['path'], 1)));

        $this->render();
    }

    public static function filterUrl($content)
    {
        // $key = 'dreamlive.tv';
        $regex = '@(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))@';

        if(!preg_match($regex, $content)) {
            return false;
        }

        // if(!strpos($content, $key))
        //     return false;

        return true;
    }
}
?>
