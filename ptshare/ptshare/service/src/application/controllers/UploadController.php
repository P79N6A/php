<?php
class UploadController extends BaseController
{
    public function uploadFileAction()
    {
        $filename = $this->getParam("filename") ? trim(strip_tags($this->getParam("filename"))): "";
        $filename = ltrim($filename,"/");
        $filename = urldecode($filename);

        $file = $_FILES["file"];

        Interceptor::ensureNotEmpty($filename, ERROR_PARAM_IS_EMPTY, "filename");
        Interceptor::ensureNotFalse(UPLOAD_ERR_OK == $file["error"], ERROR_PARAM_IS_EMPTY, "file");
        Interceptor::ensureNotFalse(file_exists($file["tmp_name"]), ERROR_PARAM_NOT_EXIST, "tmp_name");

        $data = file_get_contents($file["tmp_name"]);

        $storage = new Storage();
        $url = $storage->addFile($filename, $data);

        $this->render(array("url" => $url));
    }

    public function uploadImageAction()
    {
        $kind    = $this->getParam("kind")? $this->getParam("kind"): "default";
        $data    = $this->getParam("data")? $this->getParam("data"): "";
        $file    = $_FILES["file"];

        Interceptor::ensureNotFalse(UPLOAD_ERR_OK == $file["error"], ERROR_PARAM_IS_EMPTY, "file");

        $data = $file['tmp_name']?file_get_contents($file["tmp_name"]) : urldecode($data);

        $bin = substr($data,0,2);
        $str = @unpack("C2c", $bin);
        $typeCode = intval($str['c1'].$str['c2']);

        switch($typeCode){
            case 255216:
                $suffix = 'jpg';break;
            case 7173:
                $suffix = 'gif';break;
            case 6677:
                $suffix = 'bmp';break;
            case 13780:
                $suffix = 'png';break;
            case 8273:
                $suffix = 'webp';break;
            default:
                $suffix = 'unknown';
        }

        Interceptor::ensureFalse($suffix == 'unknown', ERROR_PARAM_IS_EMPTY, "file");

        $storage = new Storage();
        $url = $storage->addImage($suffix, $data, $kind);

        $this->render(array("url" => $url));
    }

    public function uploadVideoAction(){
        $file = $_FILES["file"];
        Interceptor::ensureNotFalse(UPLOAD_ERR_OK == $file["error"], ERROR_PARAM_IS_EMPTY, "file");

        $name = explode('.', $_FILES["file"]["name"]);

        $suffix = end($name);
        Interceptor::ensureNotFalse(in_array($suffix,['mp4', 'mpg', 'mpeg', 'mov', 'flv', 'webm']), ERROR_PARAM_IS_EMPTY, "file");

        $data = file_get_contents($file["tmp_name"]);

        $storage = new Storage();
        $url = $storage->addVideo($suffix, $data);

        $this->render(array("url" => $url));
    }
}
?>
