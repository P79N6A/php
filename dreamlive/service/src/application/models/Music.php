<?php
class Music
{
    public function search($keyword, $type, $offset = 0, $num = 20)
    {
        $musicList = array();

        if($num == 0) {
            $num = 20;
        }
    
        $dao = new DAOMusic();
        if($type == "name") {
            $musicList = $dao->searchByName($keyword, $offset, $num);
        }elseif($type == "singer") {
            $musicList = $dao->searchBySinger($keyword, $offset, $num);
        }
        $result = [];
        foreach ($musicList as $k => $v) {
            $v['mp3']   = Context::getConfig("STATIC_DOMAIN") .$v['mp3'];
            $v['lyric'] = Context::getConfig("STATIC_DOMAIN") .$v['lyric'];
            $result[] = $v;
        }

        return $result;
    }

    public function getHot($offset = 0, $num = 20)
    {
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $musicList = $cache->zRevRange("DREAMLIVE_HOT_MUSIC", $offset, $num);
        
        if(!$musicList) {
            $dao = new DAOMusic();
            $musicList = $dao->getHotList($offset, $num);
        }
        $result = [];
        foreach ($musicList as $k => $v) {
            $v['mp3']   = Context::getConfig("STATIC_DOMAIN") .$v['mp3'];
            $v['lyric'] = Context::getConfig("STATIC_DOMAIN") .$v['lyric'];
            $result[] = $v;
        }
        
        return $result;
    }

    public function getMusicListByIds($ids)
    {
        if (! $ids) {
            return array();
        }
        if (! is_array($ids)) {
            $ids= array(
                    $ids
            );
        }

        $result = array();
        $dao = new DAOMusic();
        $list = $dao->getMusicListByIds($ids);

        if(!$list) {
            return array();
        }
        
        foreach ($ids as $k => $v) {
            foreach ($list as $music) {
                if($v == $music['musicid']) {
                    $music['mp3']   = Context::getConfig("STATIC_DOMAIN") .$music['mp3'];
                    $music['lyric'] = Context::getConfig("STATIC_DOMAIN") .$music['lyric'];
                    $result[$v] = $music;
                }
            }
        }

        return $result;
    }

    public function addErrorlog($uid, $musicid, $content)
    {
        $dao = new DAOMusic();
        $info = array(
            'uid' => $uid,
            'musicid' => $musicid,
            'content' => $content,
            'modtime' => date('Y-m-d H:i:s'),
        );
        return $dao->insert('music_errorlog', $info);
    }

}

