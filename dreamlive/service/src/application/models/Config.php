<?php
class Config
{
    const VERSION_CONVERT_BASE = 3;

    const VERSION_CONVERT_POWER = 10000;

    private $_dao_config;

    public function __construct()
    {
        $this->_dao_config = DAOConfig::getInstance();
    }

    public function getConfigById($id)
    {
        return $this->_dao_config->getConfigById($id);
    }

    public function getConfig($region, $name, $platform, $version)
    {
        $version = self::convertVersion($version);

        $key = "config_{$region}_{$platform}_{$version}_{$name}";

        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $data = $cache->get($key);

        $cached = true;
        if ($data === false) {
            $data = $this->_dao_config->getConfig($region, $name, $platform, $version, $version);
            $data = serialize($data);

            if (! empty($data) && false !== $data) {
                $cache->set($key, $data, 30);
            }

            $cached = false;
        }

        $data = unserialize($data);

        if (empty($data)) {
            $data = array(
                'value' => '',
                'expire' => 0
            );
        } else {
            // 返回给客户端的均为字符串，具体业务自行解析
            is_array($data['value']) && $data['value'] = json_encode($data['value']);
            $data = array(
                'value' => (string) $data['value'],
                'expire' => (int) $data['expire']
            );
        }

        $data["cached"] = $cached;

        return $data;
    }

    public function getConfigs($region, $names, $platform, $version)
    {
        $version = self::convertVersion($version);

        $names_md5 = md5(implode(',', $names));
        $key = "config_{$region}_{$platform}_{$version}_{$names_md5}";

        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $data = $cache->get($key);

        $cached = true;
        if ($data === false) {
            $configs = $this->_dao_config->getConfigs($region, $names, $platform, $version, $version);
            $data = serialize($configs);

            if (! empty($data) && false !== $data) {
                $cache->set($key, $data, 300);
            }

            $cached = false;
        }

        $data = unserialize($data);

        foreach($names as $name){
            if (empty($data[$name])) {
                $data[$name] = array(
                    'value' => '',
                    'expire' => 0
                );
            } else {
                // 返回给客户端的均为字符串，具体业务自行解析
                is_array($data[$name]['value']) && $data[$name]['value'] = json_encode($data[$name]['value']);
                $data[$name] = array(
                    'value' => (string) $data[$name]['value'],
                    'expire' => (int) $data[$name]['expire']
                );
            }

        }
        $data["cached"] = $cached;

        return $data;
    }

    public function setConfig($id, $region, $name, $value, $expire, $platform, $min_version, $max_version = null)
    {
        $min_version = self::convertVersion($min_version);
        $max_version = self::convertVersion($max_version);

        return $this->_dao_config->setConfig($id, $region, $name, $value, $expire, $platform, $min_version, $max_version);
    }

    public function delConfig($id)
    {
        return $this->_dao_config->delConfig($id);
    }

    public static function convertVersion($version, $base = self::VERSION_CONVERT_BASE, $power = self::VERSION_CONVERT_POWER)
    {
        $version = explode('.', $version);
        $result = 0;
        foreach ($version as $v) {
            $result += (int)$v * pow($power, $base --);
        }

        return ceil($result);
    }
}
?>
