<?php
/**
 * 粉丝送奖励
 */
class Attract
{
    const PATRONS  = "patrons";
    const FOLLOWED = "followed_%s";
    const RANDOM   = "patron_rand_%s";

    public static function join($uid)
    {
        $cache = Cache::getInstance("REDIS_CONF_CACHE");

        return $cache->sAdd(self::PATRONS, $uid);
    }

    public static function quit($uid)
    {
        $cache = Cache::getInstance("REDIS_CONF_CACHE");

        return $cache->sRem(self::PATRONS, $uid);
    }

    public static function isPatron($uid)
    {
    	$cache = Cache::getInstance("REDIS_CONF_CACHE");

    	return $cache->sismember(self::PATRONS, $uid);
    }

    public static function getPatrons($uid, $num)
    {
        $user_followed_key = sprintf(self::FOLLOWED, $uid);
        $user_random_key   = sprintf(self::RANDOM, $uid);
        $patrons_key = self::PATRONS;

        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $cache->sDiffStore($user_random_key, $patrons_key, $user_followed_key);

        $members = $cache->sRandMember($user_random_key, $num);
        $cache->delete($user_random_key);

        foreach($members as $key=>$member) {
            $result = Follow::isFollowed($uid, $member);
            if($result[$member]) {
                unset($members[$key]);
            }
        }

        $patrons = User::getFollowUserInfos($members);
	    $patrons = array_values($patrons);

        return $patrons;
    }

    public static function isFollowed($uid, $fid)
    {
        $key = sprintf(self::FOLLOWED, $uid);

        $cache = Cache::getInstance("REDIS_CONF_CACHE");

        return $cache->sismember($key, $fid);
    }

    public static function follow($uid, $fid)
    {
        $key = sprintf(self::FOLLOWED, $uid);

        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        return $cache->sAdd($key, $fid);
    }
}
?>
