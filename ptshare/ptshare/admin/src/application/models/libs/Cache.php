<?php
class Cache
{
	private static $handler = null;

	public static function getInstance($arr_cache_config = null)
	{
        if($arr_cache_config == null) {
            $arr_cache_config = Context::getConfig("REDIS_CONF");
        }

		if(self::$handler == null) {
           /*
            $memcache = new Memcache();
			foreach($arr_cache_config as $server) {
				$memcache->addServer($server["host"], $server["port"]);
            }
            */
            $memcache = new Redis();
            $memcache->pconnect($arr_cache_config["host"], $arr_cache_config["port"]);
            $memcache->auth($arr_cache_config["password"]);

            self::$handler = $memcache;
		}

		return self::$handler;
	}

	public static function setpro($key, $data, $expire, $compressed = true){
		self::getInstance();
		$flag = $compressed ? MEMCACHE_COMPRESSED : null;
		$result = self::$handler->set(self::getProKey($key), array(
			'data' => $data,
			'expire' => $expire,
			'time' => time()
		), $flag);

		self::$handler->set(self::getLockKey($key), 0);
		return $result;
	}

	public static function getpro($key){
		self::getInstance();
		$result = self::$handler->get(self::getProKey($key));
		if (!$result){
			return false;
		}

		if (time() - $result['time'] > $result['expire']){
			if (self::$handler->increment(self::getLockKey($key)) == 1){
				return false;
			}
			// 30s timeout reset lock
			if (time() - $result['time'] > 30){
				self::$handler->set(self::getLockKey($key), 0);
			}
		}
		return $result['data'];
	}

	/**
	 * [getKeys 批量读取缓存信息，需要使用Memcached]
	 * @param  [type] $key_arr [key的列表]
	 * @return [type]          [description]
	 */
	/*
	public static function getKeys($key_arr){
		self::getInstance();
		return self::$handler->getMulti($key_arr);
	}*/

	private static function getProKey($key){
		return $key . '_pro';
	}

	private static function getLockKey($key){
		return $key . '_lock';
	}

    public function __destruct()
    {
        self::$handler->close();
    }
}
?>
