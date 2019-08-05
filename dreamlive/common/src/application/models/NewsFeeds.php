<?php
class NewsFeeds
{
    protected static $step = 200;

    public function getNewsFeeds($uid, $offset = 0, $num = 8)
    {
        $newsfeeds_list = array();

        if (!$offset) {
            $user_followings = Follow::getUserFollowings($uid, 0, 3000);

            $followings = array();
            foreach($user_followings as $key=>$user_following) {
                if(!Forbidden::isForbidden($user_following["uid"])) {
                    $followings[] = $user_following["uid"];
                }
            }
            $followings[] = $uid;

            $dao_news_feed = new DAONewsFeeds($uid);
            $max_relateid  = (int) $dao_news_feed->getMaxRelateid($uid);
            $dao_news_feed->receive($followings, $uid, $max_relateid);

            $dao_live = new DAOLive();
            $active_lives = $dao_live->getFollowersLives($followings);

            foreach($active_lives as $live_info) {
                $feeds = new Feeds();
                $liveinfo = $feeds->getFeedInfo($live_info["liveid"], Feeds::FEEDS_LIVE);
                if ($liveinfo) {
                    $newsfeeds_list[] = $liveinfo;
                }
            }
        }

        if (count($newsfeeds_list) >= $num) {
            $data = array(
                "feeds"=> $newsfeeds_list,
                "offset"=> (string) 2147483647
            );
            return $data;
        }
        return array(
            "feeds" => $newsfeeds_list,
            "offset" => (string) 2147483647
        );

        //补足数据
        $num -= count($newsfeeds_list);

        $dao_news_feed = new DAONewsFeeds($uid);
        $newsfeeds = $dao_news_feed->getNewsFeeds($uid, $offset, 200);

        $deleteids = array();
        foreach ($newsfeeds as $newsfeed) {
            $feeds = new Feeds();

            if(!($feed_info = $feeds->getFeedInfo($newsfeed["relateid"], $newsfeed["type"]))) {
                $newsfeeds_list[] = $feed_info;
                $max_relateid = $newsfeed["relateid"];
            } else {
                $deleteids[] = $newsfeed["feedid"];
            }

            if(count($newsfeeds_list) >= $num) {
                break;
            }
        }

        if (!empty($deleteids)) {
            $dao_news_feed->clean($uid, $deleteids);
        }

        $data = array(
        "feeds"=> $newsfeeds_list,
        "offset"=> (string) $max_relateid
        );

        return $data;
    }

    /**
     * 用户关注列表
     *
     * @param int $uid
     * @param int $offset
     * @param int $num
     * @param int $step   当前页是否直播
     *                    1是,0否
     */
    public function getNewsFeedsList($uid, $offset, $num = 8, $step=0)
    {
        if (!$offset) {
            // 批量拉去所有关注人
            //self::followingsReceive($uid);
            include_once "process_client/ProcessClient.php";
            // worker 拉取我关注人的feeds，插入我的newsfeed表
            $bool = ProcessClient::getInstance("dream")->addTask("followings_increase_newsfeeds", array("uid" => $uid,"type"=>'login'));
        }

        $newsfeeds_list = array();
        $time = $offset;

        $newsFeeds = new DAONewsFeeds($uid);
        $feeds = new Feeds();
        // 取直播数据
        if($step==0) {
            $deleteids = array();
            $newsfeeds_liveList = $newsFeeds->getNewsFeedsList($uid, $offset, 200, true);
            foreach ($newsfeeds_liveList as $item) {
                $info      = $feeds->getFeedInfo($item['relateid'], $item['type']);
                if ($info['feed']['privacy'] == true && Context::get("version") <= '2.6.3') {
                    continue;
                }
                if (! empty($info)) {
                    $newsfeeds_list[] = $info;
                    $time = strtotime($item['addtime']);
                }else {
                    $deleteids[] = $item["feedid"];
                }
                if(count($newsfeeds_list) >= $num) {
                    break;
                }
            }
            if (count($newsfeeds_list) >= $num) {
                $data = array(
                    "feeds"=> $newsfeeds_list,
                    "offset"=> $time,
                    "step" => 0
                );
                return $data;
            }
        }
        $num -= count($newsfeeds_list);
        if($step==0) {
            $offset = 0;
        }

        // 取回放、图片、短视频数据
        $newsfeeds = $newsFeeds->getNewsFeedsList($uid, $offset, 200);
        foreach ($newsfeeds as $item) {
            $info = $feeds->getFeedInfo($item['relateid'], $item['type']);
            if ($info['feed']['privacy'] == true && Context::get("version") < '2.6.0') {
                continue;
            }
            if (! empty($info)) {
                $newsfeeds_list[] = $info;
                $time = strtotime($item['addtime']);
            }else {
                $deleteids[] = $item["feedid"];
            }
            if(count($newsfeeds_list) >= $num) {
                break;
            }
        }

        if (!empty($deleteids)) {
            $newsFeeds->clean($uid, $deleteids);
        }
        return array(
            "feeds" => $newsfeeds_list,
            "offset" => $time,
            "step" => 1
        );
    }

    /**
     * 批量拉去所有关注人的NewsFeeds
     *
     * @param int $receiver
     */
    public static function followingsReceive($receiver)
    {
        try {
            $dao_following = new DAOFollowing($receiver);
            $total = $dao_following->countFollowings();

            $i = 0;
            while (($offset = $i * self::$step) < $total) {
                $followingsIds = self::getFollowingsIds($receiver, $offset, self::$step);
                foreach ($followingsIds as $uid) {
                    self::newsFeedsReceive($receiver, $uid);
                }
                $i ++;
            }
            return true;
        } catch (Exception $e) {
            Logger::log("followings_add_newsfeeds", null, array("uid" => $receiver,"errno" => $e->getCode(),"errmsg" => $e->getMessage()));
            print_r($e);exit;
            return $e;
        }
    }

    /**
     * 拉数据
     *
     * @param int $receiver
     * @param int $author
     */
    public static function newsFeedsReceive($receiver, $author)
    {
        $newsFeeds = new DAONewsFeeds($receiver);
        $newsFeedsMaxRelateid = $newsFeeds->getMaxRelateidByRelateidAuthor($receiver, $author);
        $newsFeedsMaxRelateid = $newsFeedsMaxRelateid ? $newsFeedsMaxRelateid : 0;

        $userFeeds = new DAOUserFeeds($author);
        $userFeedsMaxRelateid = $userFeeds->getMaxRelateid($author);
        $userFeedsMaxRelateid = $userFeedsMaxRelateid ? $userFeedsMaxRelateid : 0;

        if ($userFeedsMaxRelateid > $newsFeedsMaxRelateid) {
            $list = $userFeeds->getUserFeedsList($author, $newsFeedsMaxRelateid);
            self::addNewsFeedsBatch($list, $receiver);
        }
        return;
    }


    /**
     * 拼接sql语句批量插入NewsFeeds
     *
     * @param  array $list
     * @param  int   $uid
     * @param  int   $type
     * @return string
     */
    public static function addNewsFeedsBatch($list, $receiver)
    {
        if(empty($list)) {
            return ;
        }

        $str = "";
        $creatime = date("Y-m-d H:i:s");
        foreach ($list as $item) {
            $str .= "(" . $receiver . "," . $item['relateid'] . "," . $item['uid'] . "," . $item['type'] . ",'" . $creatime . "','" . $item['addtime'] . "'),";
        }
        $str = substr($str, 0, strlen($str) - 1);
        $newsFeeds = new DAONewsFeeds($receiver);

        return $newsFeeds->addNewsFeedsBatch($str);
    }

    /**
     * 获取关注的人的id列表
     *
     * @param  int $uid
     * @return array
     */
    public static function getFollowingsIds($uid, $offset, $num)
    {
        $userFollowings = Follow::getUserFollowings($uid, $offset, $num);
        $followingsIds = array();
        foreach ($userFollowings as $key => $following) {
            if (! Forbidden::isForbidden($following["uid"])) {
                array_push($followingsIds, $following["uid"]);
            }
        }
        return $followingsIds;
    }
}
?>
