<?php
class Bucket
{
    public function getBucketList($bucket_names)
    {
        $buckets = array();
        foreach($bucket_names as $bucket_name) {
            $buckets[$bucket_name] = $this->getBucket($bucket_name);
        }

        return $buckets;
    }

    public function getBucket($bucket_name)
    {
        $bucket_info = $bucket_cache = array();

        $cache_key = $this->bucket_cache_key($bucket_name);
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $bucket_cache = $cache->get($cache_key);

        if(empty($bucket_cache)) {
            $dao_bucket = new DAOBucket();
            $bucket_info =  $dao_bucket->getBucket($bucket_name);

            $cache->set($cache_key, json_encode($bucket_info));
        }else{
            $bucket_info = json_decode($bucket_cache, true);
        }

        if(!empty($bucket_info)) {
            $bucket_name = !empty($bucket_info['forward']) ? $bucket_info['forward'] : $bucket_name;
            $bucket_element = new BucketElement();
            $bucket_info['total'] = $bucket_element->getTotal($bucket_name);
            $bucket_info['extends'] = !empty($bucket_info['extends']) ? $bucket_info['extends'] : '';
        }

        return $bucket_info;
    }

    public function setBucket($bucket_name, $capacity, $official, $extends)
    {
        $dao_bucket = new DAOBucket();
        return $dao_bucket->setBucket($bucket_name, $capacity, $official, $extends);
    }

    public function setForward($bucket_name, $forward_name)
    {
        $dao_bucket = new DAOBucket();
        $set_forward_return = $dao_bucket->setForward($bucket_name, $forward_name);

        $this->_reload($bucket_name);

        return $set_forward_return;
    }

    public function delBucket($bucket_name)
    {
        $fixed=array('china_live_hot','china_live_latest');
        if (in_array($bucket_name, $fixed)) {
            throw new Exception("live_hot 和 live_latest不能删除");
        }
        
        $dao_bucket = new DAOBucket();
        try {
            $dao_bucket->startTrans();

            // $dao_bucket_element = new DAOBucketElement();
            // $dao_bucket_element->deleteAll($bucket_name);
            $bucket_element = new BucketElement();
            $bucket_element->deleteAll($bucket_name);

            $dao_bucket->delBucket($bucket_name);

            $cache_key = $this->bucket_cache_key($bucket_name);
            $cache = Cache::getInstance("REDIS_CONF_CACHE");
            $cache->delete($cache_key);

            $dao_bucket->commit();
        } catch (MySQLException $e) {
            $dao_bucket->rollback();

            throw $e;
        }

        return true;
    }

    public function openBucket($bucket_name)
    {
        $dao_bucket = new DAOBucket();
        try {
            $dao_bucket->bucketOpen($bucket_name);
            $this->_reload($bucket_name);
        } catch (MySQLException $e) {
            return false;
        }

        return true;
    }

    public function closeBucket($bucket_name)
    {
        $dao_bucket = new DAOBucket();
        try {
            $dao_bucket->bucketClose($bucket_name);
            $this->_reload($bucket_name);
        } catch (MySQLException $e) {
            return false;
        }

        return true;
    }

    public static function isGoodName($bucket_name)
    {
        return str_replace(array('\\','/','#','"',"'"), '', $bucket_name) == $bucket_name;
    }

    public function _reload($bucket_name)
    {
        $cache_key = $this->bucket_cache_key($bucket_name);
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $cache->delete($cache_key);

        $this->getBucket($bucket_name);

        return true;
    }

    public function bucket_cache_key($bucket_name)
    {
        return "cache_bucket_{$bucket_name}";
    }

    public static function checkBucketNameAndType($bucket_short_name,$type)
    {
        if($bucket_short_name=='live_hot') {
            if ($type!=1) {
                return false;
            }
            return true;
        }
        return true;
    }
}
?>
