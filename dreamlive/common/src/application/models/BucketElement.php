<?php
class BucketElement
{
    const TOP_SCORE = 100000000; // 置顶分数

    public function set($bucket_name, $relateid, $type, $score, $extends = array())
    {
        $content_cache_key = $this->bucket_element_content_cache_key($bucket_name);
        $extend_cache_key = $this->bucket_element_extend_cache_key($bucket_name);
        $cache = Cache::getInstance("REDIS_CONF_CACHE");

        $dao_bucket = new DAOBucket();

        try {
            $dao_bucket->startTrans();

            $bucket = new Bucket();
            $bucket_info = $bucket->getBucket($bucket_name);
            if(!empty($bucket_info['forward'])) {
                $bucket_name = $bucket_info['forward'];
            }

            $dao_bucket_element = new DAOBucketElement();
            $dao_bucket_element->set($bucket_name, $relateid, $type, $score, $extends);

            //添加cache
            $content_cache_value = array(
                'type'=>$type,
                'relateid'=>$relateid,
            );

            $cache->zAdd($content_cache_key, $score, json_encode($content_cache_value));

            if($extends) {
                $cache_extends = !is_array($extends) ? $extends : json_encode($extends);
                $cache->hset($extend_cache_key, "{$type}_{$relateid}", $cache_extends);
            }

            $total = $this->getTotal($bucket_name);

            $dao_bucket->setTotal($bucket_name, $total);

            $dao_bucket->commit();
        } catch (MySQLException $e) {
            $dao_bucket->rollback();

            throw $e;
        }

        return true;
    }

    public function import($bucket_name, $data)
    {
        $content_cache_key = $this->bucket_element_content_cache_key($bucket_name);
        $extend_cache_key = $this->bucket_element_extend_cache_key($bucket_name);
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $dao_bucket = new DAOBucket();

        try {
            $dao_bucket->startTrans();

            $dao_bucket_element = new DAOBucketElement();
            $dao_bucket_element->truncate($bucket_name, 0);

            $dao_bucket_element->import($bucket_name, $data);

            //添加cache
            foreach($data as $v){
                $content_cache_value = array(
                    'type'=>$v['type'],
                    'relateid'=>$v['relateid'],
                );
                $cache->zAdd($content_cache_key, $v['score'], json_encode($content_cache_value));

                if($v['extends']) {
                    $cache_extends = !is_array($v['extends']) ? $v['extends'] : json_encode($v['extends']);
                    $cache->hset($extend_cache_key, "{$v['type']}_{$v['relateid']}", $cache_extends);
                }
            }

            $total = $this->getTotal($bucket_name);

            $dao_bucket = new DAOBucket();
            $dao_bucket->setTotal($bucket_name, $total);

            $dao_bucket->commit();
        } catch (MySQLException $e) {
            $dao_bucket->rollback();

            throw $e;
        }

        return true;
    }

    public function fetch($bucket_name, $offset, $num, $paging=DAOBucketElement::PADING_LIMIT)
    {
        if(empty($offset)) {
            $offset = $paging==DAOBucketElement::PADING_OFFSET ? PHP_INT_MAX : $offset;
        }

        $content_cache_key = $this->bucket_element_content_cache_key($bucket_name);
        $extend_cache_key = $this->bucket_element_extend_cache_key($bucket_name);
        $cache = Cache::getInstance("REDIS_CONF_CACHE");

        if($paging==DAOBucketElement::PADING_OFFSET) {
            $start = $offset==PHP_INT_MAX ? 0 : 1;
            $elements = $cache->zRevRangeByScore($content_cache_key, $offset, 0, ['withscores' => true, 'limit' => [$start, $num]]);
        }else{
            $elements = $cache->zRevRangeByScore($content_cache_key, PHP_INT_MAX, 0, ['withscores' => true, 'limit' => [$offset, $num]]);
        }

        $list = $extends_arr_field = array();
        foreach($elements as $key=>$score){
            $element_info = json_decode($key, true);
            array_push($extends_arr_field, "{$element_info['type']}_{$element_info['relateid']}");
        }
        $extend_element_info = $cache->hmget($extend_cache_key, $extends_arr_field);

        foreach($elements as $key=>$score){
            $element_info = json_decode($key, true);
            $element_info['score'] = $score;
            $extends = $extend_element_info["{$element_info['type']}_{$element_info['relateid']}"]? json_decode($extend_element_info["{$element_info['type']}_{$element_info['relateid']}"], true) : '';
            $element_info['extends'] = $extends;
            $element_info['bucket_name'] = $bucket_name;
            array_push($list, $element_info);
        }

        //$dao_bucket_element = new DAOBucketElement();
        //$list = $dao_bucket_element->fetch($bucket_name, $offset, $num, $paging);

        if($paging==DAOBucketElement::PADING_LIMIT) {
            $offset = $offset+$num;
        }else if($paging==DAOBucketElement::PADING_OFFSET) {
            if(!empty($list)) {
                $latest = end($list);
                $offset = $latest['score'];
            }else{
                $offset = 1;
            }
        }

        return array($list, $offset);
    }

    public function delete($bucket_name, $relateid, $type)
    {
        $content_cache_key = $this->bucket_element_content_cache_key($bucket_name);
        $extend_cache_key = $this->bucket_element_extend_cache_key($bucket_name);
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $dao_bucket = new DAOBucket();

        try {
            $dao_bucket->startTrans();

            $dao_bucket = new DAOBucket();
            $bucket_info = $dao_bucket->getBucket($bucket_name);
            if(!empty($bucket_info['forward'])) {
                $bucket_name = $bucket_info['forward'];
            }

            $dao_bucket_element = new DAOBucketElement();
            $dao_bucket_element->delete($bucket_name, $relateid, $type);

            $content_cache_value = array(
                'type'=>$type,
                'relateid'=>$relateid,
            );
            $cache->zRem($content_cache_key, json_encode($content_cache_value));
            $cache->hDel($extend_cache_key, "{$type}_{$relateid}");

            $total = $this->getTotal($bucket_name);

            $dao_bucket->setTotal($bucket_name, $total);

            $dao_bucket->commit();
        } catch (MySQLException $e) {
            $dao_bucket->rollback();

            throw $e;
        }

        return true;
    }

    public function truncate($bucket_name, $limit)
    {
        $content_cache_key = $this->bucket_element_content_cache_key($bucket_name);
        $extend_cache_key = $this->bucket_element_extend_cache_key($bucket_name);
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $dao_bucket = new DAOBucket();

        try {
            $dao_bucket->startTrans();

            $dao_bucket_element = new DAOBucketElement();
            $dao_bucket_element->truncate($bucket_name, $limit);

            $content_del_list = $cache->Zrank($content_cache_key, 0, $limit);
            $cache->Zremrangebyrank($content_cache_key, 0, $limit);
            foreach($content_del_list as $v){
                $delete_info = json_decode($v, true);
                $cache->hdel($extend_cache_key, "{$delete_info['type']}_{$delete_info['relateid']}");
            }

            $total = $this->getTotal($bucket_name);

            $dao_bucket = new DAOBucket();
            $dao_bucket->setTotal($bucket_name, $total);

            $dao_bucket->commit();
        } catch (MySQLException $e) {
            $dao_bucket->rollback();

            throw $e;
        }

        return true;
    }

    public function clean($relateid, $type)
    {
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $dao_bucket = new DAOBucket();

        try {
            $dao_bucket->startTrans();
            $dao_bucket_element = new DAOBucketElement();
            $dao_bucket_element = new DAOBucketElement();
            $bucket_names = $dao_bucket_element->getBucketNames($relateid, $type);

            $dao_bucket_element->clean($relateid, $type);

            foreach($bucket_names as $bucket_name) {
                $content_cache_key = $this->bucket_element_content_cache_key($bucket_name);
                $extend_cache_key = $this->bucket_element_extend_cache_key($bucket_name);

                $content_cache_value = array(
                    'type'=>$type,
                    'relateid'=>$relateid,
                );

                $cache->zRem($content_cache_key, json_encode($content_cache_value));
                $cache->hDel($extend_cache_key, "{$type}_{$relateid}");

                $total = $this->getTotal($bucket_name);

                $dao_bucket = new DAOBucket();
                $dao_bucket->setTotal($bucket_name, $total);
            }

            $dao_bucket->commit();
        } catch (MySQLException $e) {
            $dao_bucket->rollback();

            throw $e;
        }

        return true;
    }
    public function exists($bucket_name, $type, $relateid)
    {
        $content_cache_key = $this->bucket_element_content_cache_key($bucket_name);
        $extend_cache_key = $this->bucket_element_extend_cache_key($bucket_name);
        $cache = Cache::getInstance("REDIS_CONF_CACHE");

        $score = $cache->ZSCORE($content_cache_key, "{$type}_{$relateid}");
        $extends = $cache->hget($extend_cache_key, "{$type}_{$relateid}");
        $extends = !empty($extends) ? $extends : '';

        $data = array(
            'bucket_name'=>$bucket_name,
            'relateid'=>$relateid,
            'type'=>$type,
            'score'=>$score,
            'extends'=>$extends
        );

        return $data;
        // $dao_bucket_element = new DAOBucketElement();
        // return $dao_bucket_element->exists($bucket_name, $type, $relateid);
    }

    public function getTotal($bucket_name)
    {
        $cache_key = $this->bucket_element_content_cache_key($bucket_name);
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        return $cache->zcard($cache_key);
    }

    public function deleteAll($bucket_name)
    {
        $dao_bucket_element = new DAOBucketElement();
        $dao_bucket_element->deleteAll($bucket_name);

        $content_cache_key = $this->bucket_element_content_cache_key($bucket_name);
        $extend_cache_key = $this->bucket_element_extend_cache_key($bucket_name);
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $cache->delete($content_cache_key);
        $cache->delete($extend_cache_key);

        return true;
    }

    public function bucket_element_content_cache_key($bucket_name)
    {
        return "cache_bucket_element_content_{$bucket_name}";
    }

    public function bucket_element_extend_cache_key($bucket_name)
    {
        return "cache_bucket_element_extend_{$bucket_name}";
    }
}
?>
