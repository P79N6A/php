<?php
class MusicController extends BaseController
{
    public function searchAction()
    {
        $keyword = trim($this->getParam("keyword", ""));
        $type    = trim($this->getParam("type", "name"))? trim($this->getParam("type", "name")): 'name';//name singer
        $offset  = (int) $this->getParam("offset", 0);
        $num     = 20;

        Interceptor::ensureNotEmpty($keyword, ERROR_PARAM_IS_EMPTY, "keyword");
        Interceptor::ensureNotFalse(in_array($type, array("name", "singer")), ERROR_PARAM_INVALID_FORMAT, "type");

        $music = new Music();
        $musicList = $music->search($keyword, $type, $offset, $num);

        $this->render(
            array(
            'music'  => $musicList,
            'offset' => $offset + count($musicList),
            )
        );
    }

    public function getHotAction()
    {
        $offset  = (int) $this->getParam("offset", 0);
        $num     = 20;

        $music = new Music();
        $musicList = $music->getHot($offset, $num);

        $this->render(
            array(
            'music'  => $musicList,
            'offset' => $offset + count($musicList),
            )
        );
    }

    public function getFavoritesAction()
    {
        $uid    = Context::get("userid");
        $offset = (int) $this->getParam("offset", 0);
        $num    = (int) $this->getParam("num", 0);

        Interceptor::ensureNotEmpty($uid, ERROR_USER_NOT_EXIST);

        $music     = new MusicFavorite();
        $musicList = $music->getFavorites($uid, $offset, $num);
        $total     = $music->getTotalFavorite($uid);

        $offset    = $offset + count($musicList);
        
        $this->render(
            array(
            'music'  => $musicList,
            'offset' => $offset,
            'more'   => $offset < $total
            )
        );
    }

    public function addFavoriteAction()
    {
        $uid = Context::get("userid");
        $musicid  = (int) $this->getParam("musicid");

        Interceptor::ensureNotEmpty($uid, ERROR_USER_NOT_EXIST);
        Interceptor::ensureNotEmpty($musicid, ERROR_PARAM_IS_EMPTY, "musicid");

        $music = new MusicFavorite();
        $music->addFavorite($uid, $musicid);

        $ids = $music->getFavoriteIds($uid);

        $this->render(
            array(
            "md5" => md5(implode("|", $ids))
            )
        );
    }

    public function checkFavoriteAction()
    {
        $uid = Context::get("userid");
        Interceptor::ensureNotEmpty($uid, ERROR_PARAM_IS_EMPTY, "uid");

        $music = new MusicFavorite();
        $ids = $music->getFavoriteIds($uid);

        $this->render(
            array(
            "md5" => md5(implode("|", $ids))
            )
        );
    }
    public function reportAction()
    {
        $uid      = Context::get("userid");
        $musicid  = (int) $this->getParam("musicid");
        $content = $this->getParam("content");

        Interceptor::ensureNotEmpty($uid, ERROR_PARAM_IS_EMPTY, "uid");
        Interceptor::ensureNotEmpty($musicid, ERROR_PARAM_IS_EMPTY, "musicid");
        Interceptor::ensureNotFalse(strlen(trim($content)) > 0, ERROR_PARAM_INVALID_FORMAT, 'content');

        $music = new Music();
        $music->addErrorlog($uid, $musicid, $content);

        $this->render();
    }

    public function delFavoriteAction()
    {
        $uid = Context::get("userid");
        $musicid  = (int) $this->getParam("musicid");

        Interceptor::ensureNotEmpty($uid, ERROR_USER_NOT_EXIST);
        Interceptor::ensureNotEmpty($musicid, ERROR_PARAM_IS_EMPTY, "musicid");

        $music = new MusicFavorite();
        $music->delFavorite($uid, $musicid);

        $ids = $music->getFavoriteIds($uid);

        $this->render(
            array(
            "md5" => md5(implode("|", $ids))
            )
        );
    }
}
