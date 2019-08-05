<?php
class WebCategory
{
    const WEB_CATEGORYKEY = 'web_category_key';
    const TYPE_ARTICLE  =  1;
    const TYPE_ACTIVITY =  2;
    const TYPE_ANCHOR   =  3;
    const TYPE_CONTENT  =  4;
    const TYPE_HELP     =  5;
    

    public function getCategory($fid=0)
    {
        $index = array();
        $key = WebCategory::WEB_CATEGORYKEY;
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $category_list = $cache->get($key);
        $index = json_decode($category_list, true);
        if(empty($index)) {
            $index = $this->tree(0);
            if(!empty($index)) {
                $cache->set($key, json_encode($index));
            }
        }

        return $index;
    }

    public function tree($fid)
    {
        $index = array();

        $dao_web_category = new DAOWebcategory();
        $category_list = $dao_web_category->getCategory($fid);
        foreach($category_list as $val){
            $clist = array();
            $clist = $this->tree($val['id']);
            $index[$val['id']] = array(
                'title'=>$val['title'],
                'type'=>$val['type'],
                'id'=>$val['id'],
            );
            if(!empty($clist)) {
                $index[$val['id']]['sub'] = $clist;
            }
        }

        return $index;
    }
}
