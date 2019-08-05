<?php
class FeedController extends BaseController
{
    public function getNewsFeedsAction()
    {
        /*{{{ 我关注的feeds流 */
        //$offset     = $this->getParam("offset")     ? trim(strip_tags($this->getParam("offset")))     : 0;
        $offset     = $this->getParam("offset")     ? (int)($this->getParam("offset"))   : 0;
        $num        = $this->getParam("num")        ? intval($this->getParam("num"))     : 10;

        $newsfeeds  = new NewsFeeds();
        $data       = $newsfeeds->getNewsFeeds(Context::get("userid"), $offset, $num);

        $this->render($data);
    }/*}}}*/


    public function getNewsFeedsListAction()
    {
        $offset     = $this->getParam("offset")     ? (int)($this->getParam("offset"))   : 0;
        $num        = $this->getParam("num")        ? intval($this->getParam("num"))     : 10;
        $step       = $this->getParam("step")       ? intval($this->getParam("step"))     : 0;

        $newsfeeds  = new NewsFeeds();
        $data       = $newsfeeds->getNewsFeedsList(Context::get("userid"), $offset, $num, $step);

        $this->render($data);
    }

    public function getUserFeedsAction()
    {
        /*{{{ 我自己的feed流*/
        $offset     = $this->getParam("offset")     ? (int)($this->getParam("offset"))   : PHP_INT_MAX;
        $uid        = $this->getParam("uid")        ? intval($this->getParam("uid"))     : 0;
        $num        = $this->getParam("num")        ? intval($this->getParam("num"))     : 10;

        $userid  = Context::get("userid");
        $uid = empty($uid) ? $userid : $uid;
        Interceptor::ensureNotFalse(is_numeric($uid), ERROR_PARAM_INVALID_FORMAT, "uid");

        $is_forbidden = Forbidden::isForbidden($uid);
        Interceptor::ensureNotFalse(! $is_forbidden, ERROR_USER_NOT_EXIST);
        $userfeed     = new UserFeeds();
        $data         = $userfeed->getUserFeeds($uid, $num, $offset);

        $this->render($data);
    }/*}}}*/

    public function getActivingLiveAction()
    {
        /*{{{通过用户id获取正在直播的用户*/
        $uids        = $this->getParam("uids")              ? trim($this->getParam("uids"))              : '';

        $uids = explode(',', $uids);

        $feeds = new Feeds();
        $activing_live_user = $feeds->getActivingLiveUser($uids);

        $this->render($activing_live_user);
    }/*}}}*/

    public function getFeedsAction()
    {
        $bucket_name = $this->getParam('bucket_name');
        $offset      = $this->getParam('offset') ? trim($this->getParam('offset')) : 0;
        $num         = (int) $this->getParam('num', 20);
        $platform    = Context::get("platform");

        Interceptor::ensureNotEmpty($bucket_name, ERROR_PARAM_IS_EMPTY, 'bucket_name');

        $region = Context::get("region");
        Interceptor::ensureNotFalse(in_array(strtolower($region), Region::REGION_ALL), ERROR_PARAM_INVALID_FORMAT, "region");
        if(!empty($region)) {
            $bucket_name = $region."_".$bucket_name;
        }

        Interceptor::ensureNotFalse(Bucket::isGoodName($bucket_name), ERROR_PARAM_INVALID_FORMAT, 'bucket_name');
        Interceptor::ensureNotFalse(is_numeric($num) && $num > 0, ERROR_PARAM_INVALID_FORMAT, 'num');
        
        $feeds = new Feeds();
        $feeds_info = $feeds->getBucketFeeds($bucket_name, $offset, $num);

        //最新拉完后数据
        if($bucket_name == 'china_live_latest' && empty($feeds_info['feeds'])) {
            $offset = 0;
            $feeds_info = $feeds->getBucketFeeds($bucket_name, $offset, $num);
            if($feeds_info['feeds']) {
                shuffle($feeds_info['feeds']);
            }
        }

        $this->render($feeds_info);
    }

    public function getCityFeedsAction()
    {
        $bucket_name = $this->getParam('bucket_name');
        $offset      = $this->getParam('offset') ? trim($this->getParam('offset')) : 0;
        $num         = (int) $this->getParam('num', 20);
        
        Interceptor::ensureNotEmpty($bucket_name, ERROR_PARAM_IS_EMPTY, 'bucket_name');
        $bucket_name = "same_city_".$bucket_name;

        Interceptor::ensureNotFalse(Bucket::isGoodName($bucket_name), ERROR_PARAM_INVALID_FORMAT, 'bucket_name');
        Interceptor::ensureNotFalse(is_numeric($num) && $num > 0, ERROR_PARAM_INVALID_FORMAT, 'num');
        
        $feeds = new Feeds();
        $feeds_info = $feeds->getCityBucketFeeds($bucket_name, $offset, $num);

        $this->render($feeds_info);
    }
    /**
     * 删除个人作品接口
     */
    public function delUserFeedsAction()
    {
        $type      = $this->getParam("type")      ? intval($this->getParam("type"))  : 0;
        $relateid  = $this->getParam("relateid") ? intval($this->getParam("relateid")) : 0;

        Interceptor::ensureNotFalse(is_numeric($type), ERROR_PARAM_INVALID_FORMAT, "type");
        Interceptor::ensureNotFalse(is_numeric($relateid), ERROR_PARAM_INVALID_FORMAT, "relateid");

        $feeds = new Feeds();
        $feedInfo = $feeds->getFeedInfo($relateid, $type);
        Interceptor::ensureNotFalse($feedInfo, ERROR_PARAM_INVALID_FORMAT, "feedInfo");

        $uid = Context::get("userid");

        $userFeeds = new UserFeeds();
        $userFeeds ->delUserFeeds($uid, $relateid, $type);

        $this->render();
    }
}
?>
