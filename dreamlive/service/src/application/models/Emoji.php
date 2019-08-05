<?php
class Emoji
{
    const EMOJI_CACHE_KEY = "emoji_";
    
    /**
     * 1 鼓掌
     * 2 牛(竖大拇指)
     * 3 笑脸
     * 4 亲亲
     * 5 开心
     *
     * @param int $id
     */
    static public function getEmoji($id)
    {
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $emoji_content = $cache->get(self::EMOJI_CACHE_KEY . $id);
        if (empty($emoji_content)) {
            $dao_emoji = new DAOEmoji();
            $emoji_content = $dao_emoji->getContentById($id);
            $cache->set(self::EMOJI_CACHE_KEY . $id, $emoji_content);
        }
        
        return $emoji_content;
    }
}

?>