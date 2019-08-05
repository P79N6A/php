<?php
class BucketController extends BaseController
{
    public function getBucketsAction()
    {
        $bucket_names = $this->getParam('bucket_names');
        Interceptor::ensureNotEmpty($bucket_names, ERROR_PARAM_IS_EMPTY, 'bucket_names');

        $bucket_names = explode(",", $bucket_names);

        foreach($bucket_names as &$bucket_name){
            $bucket_name = $this->regionMapping($bucket_name);
        }

        $bucket = new Bucket();
        $buckets = $bucket->getBucketList($bucket_names);

        $this->render(array("buckets"=>$buckets));
    }

    public function setBucketAction()
    {
        $bucket_name = $this->getParam('bucket_name');
        $capacity    = $this->getParam('capacity');
        $extends     = $this->getParam('extends');
        $official    = $this->getParam("official");

        Interceptor::ensureNotEmpty($bucket_name, ERROR_PARAM_IS_EMPTY, 'bucket_name');
        Interceptor::ensureNotFalse(Bucket::isGoodName($bucket_name), ERROR_PARAM_INVALID_FORMAT, 'bucket_name');
        Interceptor::ensureNotFalse(is_numeric($capacity), ERROR_PARAM_INVALID_FORMAT, 'capacity');
        Interceptor::ensureNotFalse(in_array($official, array("Y", "N")), ERROR_PARAM_INVALID_FORMAT, 'official');

        $bucket_name = $this->regionMapping($bucket_name);

        if (!is_array($extends)) {
            Interceptor::ensureNotFalse(json_decode($extends) !== false, ERROR_PARAM_INVALID_FORMAT, 'extends');
            $extends = json_decode($extends);
        }

        $bucket = new Bucket();
        $bucket->setBucket($bucket_name, $capacity, $official, $extends);

        $this->render();
    }

    public function delBucketAction()
    {
        $bucket_name = $this->getParam('bucket_name');

        Interceptor::ensureNotEmpty($bucket_name, ERROR_PARAM_IS_EMPTY, 'bucket_name');

        $bucket_name = $this->regionMapping($bucket_name);
        Interceptor::ensureNotFalse(Bucket::isGoodName($bucket_name), ERROR_PARAM_INVALID_FORMAT, 'bucket_name');

        $bucket = new Bucket();
        $bucket->delBucket($bucket_name);

        $this->render();
    }

    public function setForwardAction()
    {
        $bucket_name  = $this->getParam('bucket_name');
        $forward_name = $this->getParam('forward_name');

        Interceptor::ensureNotEmpty($bucket_name, ERROR_PARAM_IS_EMPTY, 'bucket_name');

        $bucket_name = $this->regionMapping($bucket_name);
        Interceptor::ensureNotFalse(Bucket::isGoodName($bucket_name), ERROR_PARAM_INVALID_FORMAT, 'bucket_name');

        $forward_name = $this->regionMapping($forward_name);
        Interceptor::ensureNotEmpty($forward_name, ERROR_PARAM_IS_EMPTY, 'forward_name');
        Interceptor::ensureNotFalse(Bucket::isGoodName($forward_name), ERROR_PARAM_INVALID_FORMAT, 'forward_name');

        $bucket = new Bucket();
        $bucket->setForward($bucket_name, $forward_name);

        $this->render();
    }

    public function truncateAction()
    {
        $bucket_name = $this->getParam('bucket_name');
        $offset      = $this->getParam('offset', 0);

        Interceptor::ensureNotEmpty($bucket_name, ERROR_PARAM_IS_EMPTY, 'bucket_name');

        $bucket_name = $this->regionMapping($bucket_name);
        Interceptor::ensureNotFalse(Bucket::isGoodName($bucket_name), ERROR_PARAM_INVALID_FORMAT, 'bucket_name');
        Interceptor::ensureNotFalse(is_numeric($offset) && $offset >= 0, ERROR_PARAM_INVALID_FORMAT, 'offset');

        $bucket_element = new BucketElement();
        $bucket_element->truncate($bucket_name, $offset);

        $this->render();
    }

    public function setAction()
    {
        $bucket_name = $this->getParam('bucket_name');
        $relateid    = $this->getParam('relateid');
        $type        = $this->getParam('type');
        $score       = $this->getParam('score', 0);
        $extends     = $this->getParam('extends', '[]');

        Interceptor::ensureNotEmpty($bucket_name, ERROR_PARAM_IS_EMPTY, 'bucket_name');

        $bucket_name = $this->regionMapping($bucket_name);
        $bucket     = new Bucket();
        $bucket_info = $bucket->getBucket($bucket_name);
        Interceptor::ensureNotEmpty($bucket_info, ERROR_PARAM_DATA_NOT_EXIST, 'bucket_name');
        Interceptor::ensureNotFalse(Bucket::isGoodName($bucket_name), ERROR_PARAM_INVALID_FORMAT, 'bucket_name');
        Interceptor::ensureNotFalse($bucket_info['open']==1, ERROR_CUSTOM, 'bucket is close');

        Interceptor::ensureNotEmpty($relateid, ERROR_PARAM_IS_EMPTY, 'relateid');
        Interceptor::ensureNotFalse(is_numeric($relateid) && $relateid > 0, ERROR_PARAM_INVALID_FORMAT, 'relateid');

        Interceptor::ensureNotEmpty($type, ERROR_PARAM_IS_EMPTY, 'type');
        Interceptor::ensureNotFalse(is_numeric($type) && $type >= 0, ERROR_PARAM_INVALID_FORMAT, 'type');
        if (!is_array($extends)) {
            Interceptor::ensureNotFalse(json_decode($extends, true) !== false, ERROR_PARAM_INVALID_FORMAT, 'extends');
        }

        $len = strlen($relateid);
        $i=0;
        $bit = 1;
        while($i<$len){
            $bit = $bit*10;
            $i++;
        }
        $score = $score+($relateid/$bit);

        $bucket_element = new BucketElement();
        $bucket_element->set($bucket_name, $relateid, $type, $score, $extends);

        $this->render();
    }

    public function importAction()
    {
        $bucket_name = $this->getParam('bucket_name');
        Interceptor::ensureNotEmpty($bucket_name, ERROR_PARAM_IS_EMPTY, 'bucket_name');

        $bucket_name = $this->regionMapping($bucket_name);
        Interceptor::ensureNotFalse(Bucket::isGoodName($bucket_name), ERROR_PARAM_INVALID_FORMAT, 'bucket_name');

        $bucket = new Bucket();
        $bucket_info = $bucket->getBucket($bucket_name);
        Interceptor::ensureNotEmpty($bucket_info, ERROR_PARAM_DATA_NOT_EXIST, 'bucket_name');
        Interceptor::ensureNotFalse(Bucket::isGoodName($bucket_name), ERROR_PARAM_INVALID_FORMAT, 'bucket_name');
        Interceptor::ensureNotFalse($bucket_info['open']==1, ERROR_CUSTOM, 'bucket is close');


        $data = $this->getParam('data');
        $data = json_decode($data, true);
        Interceptor::ensureNotFalse(json_last_error() == JSON_ERROR_NONE && $data, ERROR_PARAM_INVALID_FORMAT, 'data');
        foreach ($data as $d) {
            Interceptor::ensureNotFalse(isset($d['relateid']) && isset($d['type']) && isset($d['score']), ERROR_PARAM_INVALID_FORMAT, 'data');
        }

        $bucket_element = new BucketElement();
        $bucket_element->import($bucket_name, $data);

        $this->render();
    }

    public function fetchAction()
    {
        $bucket_name = $this->getParam('bucket_name');
        $offset      = $this->getParam('offset') ? intval($this->getParam('offset')) : 0;
        $num         = (int) $this->getParam('num', 20);
        $paging      = $this->getParam('paging') ? $this->getParam('paging') : 'limit';

        Interceptor::ensureNotEmpty($bucket_name, ERROR_PARAM_IS_EMPTY, 'bucket_name');

        $bucket_name = $this->regionMapping($bucket_name);
        Interceptor::ensureNotFalse(Bucket::isGoodName($bucket_name), ERROR_PARAM_INVALID_FORMAT, 'bucket_name');
        Interceptor::ensureNotFalse(is_numeric($offset) && $offset >= 0, ERROR_PARAM_INVALID_FORMAT, 'offset');
        Interceptor::ensureNotFalse(is_numeric($num) && $num > 0, ERROR_PARAM_INVALID_FORMAT, 'num');
        Interceptor::ensureNotFalse(in_array($paging, array('offset', 'limit')), ERROR_PARAM_INVALID_FORMAT, 'paging');

        $bucket     = new Bucket();
        $bucket_info = $bucket->getBucket($bucket_name);
        Interceptor::ensureNotEmpty($bucket_info, ERROR_PARAM_DATA_NOT_EXIST, 'bucket_name');

        if ($bucket_info["forward"]) {
            $bucket_name = $bucket_info["forward"];
            $bucket_info = $bucket->getBucket($bucket_name);
        }

        $bucket_element = new BucketElement();
        list ($list, $next) = $bucket_element->fetch($bucket_name, $offset, $num, $paging);

        $this->render(
            array(
            'list' => $list,
            'total' => $bucket_info["total"],
            'offset' => $offset,
            'extends' => $bucket_info['extends']
            )
        );
    }

    public function deleteAction()
    {
        $bucket_name = $this->getParam('bucket_name');
        $relateid    = $this->getParam('relateid');
        $type        = $this->getParam('type');

        Interceptor::ensureNotEmpty($bucket_name, ERROR_PARAM_IS_EMPTY, 'bucket_name');

        $bucket_name = $this->regionMapping($bucket_name);
        Interceptor::ensureNotFalse(Bucket::isGoodName($bucket_name), ERROR_PARAM_INVALID_FORMAT, 'bucket_name');

        Interceptor::ensureNotEmpty($relateid, ERROR_PARAM_IS_EMPTY, 'relateid');
        Interceptor::ensureNotFalse(is_numeric($relateid) && $relateid > 0, ERROR_PARAM_INVALID_FORMAT, 'relateid');

        Interceptor::ensureNotEmpty($type, ERROR_PARAM_IS_EMPTY, 'type');
        Interceptor::ensureNotFalse(is_numeric($type) && $type >= 0, ERROR_PARAM_INVALID_FORMAT, 'type');

        $bucket_element = new BucketElement();
        $bucket_element->delete($bucket_name, $relateid, $type);

        $this->render();
    }

    public function cleanAction()
    {
        $relateid = $this->getParam('relateid');
        $type     = $this->getParam('type');

        Interceptor::ensureNotEmpty($relateid, ERROR_PARAM_IS_EMPTY, 'relateid');
        Interceptor::ensureNotFalse(is_numeric($relateid) && $relateid > 0, ERROR_PARAM_INVALID_FORMAT, 'relateid');

        Interceptor::ensureNotEmpty($type, ERROR_PARAM_IS_EMPTY, 'type');
        Interceptor::ensureNotFalse(is_numeric($type) && $type >= 0, ERROR_PARAM_INVALID_FORMAT, 'type');

        $bucket_element = new BucketElement();
        $bucket_element->clean($relateid, $type);

        $this->render();
    }

    private function regionMapping($bucket_name)
    {
        $region = Context::get("region");
        Interceptor::ensureNotFalse(in_array(strtolower($region), Region::REGION_ALL), ERROR_PARAM_INVALID_FORMAT, "region");
        if(!empty($region)) {
            $bucket_name = $region."_".$bucket_name;
        }

        return $bucket_name;
    }

    public function existsAction()
    {
        $bucket_name = $this->getParam('bucket_name');
        $type = $this->getParam('type');
        $relateid = $this->getParam('relateid');

        Interceptor::ensureNotEmpty($bucket_name, ERROR_PARAM_IS_EMPTY, 'bucket_name');

        $bucket_name = $this->regionMapping($bucket_name);
        Interceptor::ensureNotFalse(Bucket::isGoodName($bucket_name), ERROR_PARAM_INVALID_FORMAT, 'bucket_name');
        Interceptor::ensureNotFalse(is_numeric($type) && $type >= 0, ERROR_PARAM_INVALID_FORMAT, 'type');
        Interceptor::ensureNotFalse(is_numeric($relateid) && $relateid > 0, ERROR_PARAM_INVALID_FORMAT, 'relateid');

        $bucket     = new Bucket();
        $bucket_info = $bucket->getBucket($bucket_name);
        Interceptor::ensureNotEmpty($bucket_info, ERROR_PARAM_DATA_NOT_EXIST, 'bucket_name');

        if ($bucket_info["forward"]) {
            $bucket_name = $bucket_info["forward"];
            $bucket_info = $bucket->getBucket($bucket_name);
        }

        $bucket_element = new BucketElement();
        $list = $bucket_element->exists($bucket_name, $type, $relateid);

        $this->render(
            array(
            'list' => $list,
            'total' => $bucket_info["total"],
            'extends' => $bucket_info['extends']
            )
        );
    }

    public function bucketOpenAction()
    {
        $bucket_name = $this->getParam('bucket_name');
        Interceptor::ensureNotFalse(Bucket::isGoodName($bucket_name), ERROR_PARAM_INVALID_FORMAT, 'bucket_name');

        $bucket_name = $this->regionMapping($bucket_name);
        $bucket = new Bucket();
        Interceptor::ensureNotFalse($bucket->openBucket($bucket_name), ERROR_CUSTOM, 'error');

        $this->render();
    }

    public function bucketCloseAction()
    {
        $bucket_name = $this->getParam('bucket_name');
        Interceptor::ensureNotFalse(Bucket::isGoodName($bucket_name), ERROR_PARAM_INVALID_FORMAT, 'bucket_name');

        $bucket_name = $this->regionMapping($bucket_name);
        $bucket = new Bucket();
        Interceptor::ensureNotFalse($bucket->closeBucket($bucket_name), ERROR_CUSTOM, 'error');

        $this->render();
    }
    
    /**
     * 置顶
     */
    public function topAction()
    {
        $bucket_name = trim($this->getParam('name'));
        $relateid    = intval($this->getParam('relateid'));
        $type        = intval($this->getParam('type'));
        
        Interceptor::ensureNotFalse(Bucket::isGoodName($bucket_name), ERROR_PARAM_INVALID_FORMAT, 'bucket_name');
        Interceptor::ensureNotFalse(is_numeric($relateid) && $relateid > 0, ERROR_PARAM_INVALID_FORMAT, 'relateid');
        Interceptor::ensureNotFalse(is_numeric($type) && $type > 0, ERROR_PARAM_INVALID_FORMAT, 'type');
        $bucket_name = $this->regionMapping($bucket_name);
        
        
        $extends = array('top'=>'Y');
        $BucketElement = new DAOBucketElement();
        Interceptor::ensureNotFalse($BucketElement->topBucketElement($bucket_name, $relateid, $type, json_encode($extends)), ERROR_CUSTOM, 'error');
        
        $this->render();
    }
    
    /**
     * 取消置顶
     */
    public function unTopAction()
    {
        $bucket_name = trim($this->getParam('name'));
        $relateid    = intval($this->getParam('relateid'));
        $type        = intval($this->getParam('type'));
        
        Interceptor::ensureNotFalse(Bucket::isGoodName($bucket_name), ERROR_PARAM_INVALID_FORMAT, 'bucket_name');
        Interceptor::ensureNotFalse(is_numeric($relateid) && $relateid > 0, ERROR_PARAM_INVALID_FORMAT, 'relateid');
        Interceptor::ensureNotFalse(is_numeric($type) && $type > 0, ERROR_PARAM_INVALID_FORMAT, 'type');
        $bucket_name = $this->regionMapping($bucket_name);
        
        
        $extends = array('top'=>'N');
        $BucketElement = new DAOBucketElement();
        Interceptor::ensureNotFalse($BucketElement->unTopBucketElement($bucket_name, $relateid, $type, json_encode($extends)), ERROR_CUSTOM, 'error');
        
        $this->render();
    }
    
    
}
?>
