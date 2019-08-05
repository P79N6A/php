<?php
class DefriendController extends BaseController
{
    const FORBIDDEN_BIGLIVE_KEY = 'forbidden_biglive_key';
    const FORBIDDEN_REGION_BIGLIVE_KEY = 'forbidden_region_biglive_key';
    const FORBIDDEN_HOT_WHITE_KEY = 'forbidden_hot_white_key';

    public function defriendAction()
    {
        /* {{{封禁 */
        $uid = intval($this->getParam("uid", 0));
        $expire = intval($this->getParam("expire", 0));

        Interceptor::ensureNotEmpty($uid, ERROR_PARAM_IS_EMPTY, "uid");
        Interceptor::ensureNotEmpty($expire, ERROR_PARAM_IS_EMPTY, "expire");

        Defriend::addDefriend($uid, $expire);

        $this->render();
    }/* }}} */

    public function unDefriendAction()
    {
        $uid = trim(strip_tags($this->getParam("uid", "")));
        Interceptor::ensureNotEmpty($uid, ERROR_PARAM_IS_EMPTY, "uid");

        Defriend::unDefriend($uid);

        $this->render();
    }

    public function bigLiveHideAction()
    {
        $uids = trim(strip_tags($this->getParam("uids", "")));
        $platform = trim(strip_tags($this->getParam("plat", "android")));
        Interceptor::ensureNotEmpty($uids, ERROR_PARAM_IS_EMPTY, "uids");
        Interceptor::ensureNotFalse(in_array($platform, array('ios', 'android')), ERROR_PARAM_INVALID_FORMAT, 'platform');

        $uids = explode(",", $uids);
        $uids = implode(",", array_map("intval", $uids));
        $cache = Cache::getInstance("REDIS_CONF_FORBIDDEN");
        $cache->set(self::FORBIDDEN_BIGLIVE_KEY."_{$platform}", $uids);

        $this->render();
    }

    public function bigLiveShowAction()
    {
        $platform = trim(strip_tags($this->getParam("plat", "android")));
        Interceptor::ensureNotFalse(in_array($platform, array('ios', 'android')), ERROR_PARAM_INVALID_FORMAT, 'platform');
        $cache = Cache::getInstance("REDIS_CONF_FORBIDDEN");
        $cache->del(self::FORBIDDEN_BIGLIVE_KEY."_{$platform}");

        $this->render();
    }

    public function isBigLiveAction()
    {
        $platform = trim(strip_tags($this->getParam("plat", "android")));
        Interceptor::ensureNotFalse(in_array($platform, array('ios', 'android')), ERROR_PARAM_INVALID_FORMAT, 'platform');
        $cache = Cache::getInstance("REDIS_CONF_FORBIDDEN");
        $big_live_list = $cache->get(self::FORBIDDEN_BIGLIVE_KEY."_{$platform}");
        $is_big_live = !empty($big_live_list) ? true : false;

        $this->render($is_big_live);
    }

    public function regionBigLiveHideAction()
    {
        $cache = Cache::getInstance("REDIS_CONF_FORBIDDEN");
        $cache->set(self::FORBIDDEN_REGION_BIGLIVE_KEY, 1);

        $this->render();
    }

    public function regionBigLiveShowAction()
    {
        $cache = Cache::getInstance("REDIS_CONF_FORBIDDEN");
        $cache->del(self::FORBIDDEN_REGION_BIGLIVE_KEY);

        $this->render();
    }

    public function isRegionBigLiveHideAction()
    {
        $cache = Cache::getInstance("REDIS_CONF_FORBIDDEN");
        $isRegionHide = $cache->get(self::FORBIDDEN_REGION_BIGLIVE_KEY);
        $is_big_live = !empty($isRegionHide) ? true : false;

        $this->render($is_big_live);
    }

    public function addHotWhiteAction()
    {
        $uid = trim(strip_tags($this->getParam("uid", "")));
        Interceptor::ensureNotEmpty($uid, ERROR_PARAM_IS_EMPTY, "uid");

        $cache = Cache::getInstance("REDIS_CONF_FORBIDDEN");
        $cache->sAdd(self::FORBIDDEN_HOT_WHITE_KEY, $uid);

        $this->render();
    }

    public function delHotWhiteAction()
    {
        $uid = trim(strip_tags($this->getParam("uid", "")));
        Interceptor::ensureNotEmpty($uid, ERROR_PARAM_IS_EMPTY, "uid");

        $cache = Cache::getInstance("REDIS_CONF_FORBIDDEN");
        $cache->sRemove(self::FORBIDDEN_HOT_WHITE_KEY, $uid);

        $this->render();
    }
}
?>
