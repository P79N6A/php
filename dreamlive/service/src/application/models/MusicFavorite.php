<?php
class MusicFavorite
{
    public static $_MAX_FAVORITE = '500';//收藏上限

    public function addFavorite($uid, $musicid)
    {
        $dao = new DAOMusic();
        $music = $dao->exists($musicid);

        Interceptor::ensureNotEmpty($music, ERROR_PARAM_NOT_EXIST, "musicid");

        $dao = new DAOMusicFavorite();
        $total = $this->getTotalFavorite($uid);

        if($total >= self::$_MAX_FAVORITE) {
            $dao->deleteTheLastMusicId($uid);
        }

        $effected_rows = $dao->incFavorite($uid, $musicid);
        
        if (! $effected_rows) {
            $dao->addFavorite($uid, $musicid);
        }
        
        return true;
    }
    public function getTotalFavorite($uid)
    {
        $dao = new DAOMusicFavorite();
        return $dao->getTotalFavorite($uid);
    }

    public function getFavorites($uid, $offset = 0, $num = 0)
    {
        $dao = new DAOMusicFavorite();
        $ids = $dao->getFavoriteIds($uid, $offset, $num);
        
        if(!$ids) {
            return array();
        }
        
        $music = new Music();
        $musicList = $music->getMusicListByIds($ids);

        $result = array();
        foreach ($ids as $k => $v) {
            $result[] = $musicList[$v];
        }

        return $result;
    }

    public function getFavoriteIds($uid)
    {
        $dao = new DAOMusicFavorite();

        return $dao->getFavoriteIds($uid);
    }

    public function delFavorite($uid, $musicid)
    {
        $dao = new DAOMusic();
        $music = $dao->exists($musicid);

        Interceptor::ensureNotEmpty($music, ERROR_PARAM_NOT_EXIST, "musicid");

        $dao = new DAOMusicFavorite();

        return $dao->deletetMusicId($uid, $musicid);
    }
}

