<?php
class FileController
{
    public function getFileAction()
    {
        $filename = trim($_GET["filename"]);
        $filename = ltrim($filename,"/");

        if($filename == "") {
            header("HTTP/1.1 404 Not Found");
            exit();
        }
        $filename = urldecode($filename);

        if(preg_match("/images\/cache/", $filename)){
            $cache = Cache::getInstance("REDIS_CONF_WXTOKEN");

            $data = $cache->get($filename);
            header("Content-type: image/jpeg");
            header("Content-Length: ".strlen($data));
            header('Expires: 0');
            header('Pragma: no-cache');

            echo $data;
            die;
        }

        $storage = new Storage();
        $meta    = $storage->getObjectMeta($filename);

        if(!$meta['ContentLength']){
            header("HTTP/1.1 404 Not Found");
            exit();
        }

        $mime_type = $storage->getMimeType($filename);
        $expire = 7 * 24 * 3600;

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Content-Type, Content-Range, Range, Accept,X-Requested-With');
        header("Content-type: " . $mime_type);
        header("Expires: " . gmdate('D, d M Y H:i:s', time() + $expire) . " GMT");
        header("Last-Modified: " . gmdate('D, d M Y H:i:s', time()) . " GMT");
        header("Vary:Accept-Encoding");
        header("Cache-control:public, max-age=$expire");
        header("Server: nginx/1.2.3");
        header('Accept-Ranges: bytes');

        $filesize  = $meta['ContentLength'];
        $slicesize = 1000000;

        if (isset($_SERVER['HTTP_RANGE'])) {
            if (!preg_match('/^bytes=\d*-\d*$/', $_SERVER['HTTP_RANGE'])) {
                header('HTTP/1.1 416 Requested Range Not Satisfiable');
                exit;
            }

            $ranges   = substr($_SERVER['HTTP_RANGE'], 6);

            $parts    = explode('-', $ranges);
            $startPos = $parts[0];
            $endPos   = $parts[1];

            if ($startPos == '' && $endPos == '') {
                header('HTTP/1.1 416 Requested Range Not Satisfiable');
                exit;
            }

            if ($startPos == '') {
                $startPos = $filesize - $endPos;
                $endPos = $filesize - 1;
            }

            if ($endPos == '') {
                $endPos = $filesize - 1;
            }

            $startPos = $startPos < 0 ? 0 : (int) $startPos;
            $endPos = $endPos > $filesize - 1 ? $filesize - 1 : (int) $endPos;

            if($startPos > $endPos){
                header('HTTP/1.1 416 Requested Range Not Satisfiable');
                exit;
            }

            $length = $endPos - $startPos + 1;

            header('HTTP/1.1 206 Partial Content');
            header("Content-Length: $length");
            header("Content-Range: bytes $startPos-$endPos/$filesize");
        }else{
            header("Content-Length: $filesize");
        }

        $startPos = $startPos ?$startPos: 0;
        $endPos   = $endPos   ?$endPos  : $filesize;

        for($i = $startPos;$i < $endPos; $i+=$slicesize){
            $end = $i+$slicesize-1;
            if($end>$filesize){
                $end = '';
            }
            $option = array(
                'Range' => "bytes=$i-$end",
            );
            $data = $storage->getContent($filename,$option);

            print $data;
            ob_flush();
            flush();
            unset($data);
        }

        exit();
    }
}
?>