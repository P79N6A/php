<?php
class ConfigModel extends Table
{
    const VERSION_CONVERT_BASE = 3;

    const VERSION_CONVERT_POWER = 10000;

    protected static $regions = array('china');
    protected static $platforms = array('xcx', 'server');

    public function __construct($table = 'config', $primary = 'id')
    {
        $this->setDBConf("MYSQL_CONF_ADMIN");
        $this->setTableName($table);
        $this->setPrimary($primary);
    }

    public function getConfigInfo($region, $name, $platform, $minVersion, $maxVersion)
    {
        $minVersionConverted = self::convertVersion($minVersion);
        $maxVersionConverted = self::convertVersion($maxVersion);

        // 取最小版本要求最接近的 order by min_version desc
        $sql = 'select * from ' . $this->getTableName() . ' where `region` = ? and `name` = ? and `platform` = ? and minversion <= ? and (maxversion >= ? or maxversion = 0) order by minversion desc, id desc limit 1';
        $data = $this->getRow($sql, array(
            $region,
            $name,
            $platform,
            $minVersionConverted,
            $maxVersionConverted
        ));

        $data && $data['value'] = json_decode($data['value'], true);

        return $data;
    }

    public function listConfigs($page, $num, $tag = '')
    {
        $condition = '';
        $values = array();
        if ($tag) {
            $condition = 'tag = ?';
            $values[] = $tag;
        }

        list($total, $list) = $this->getRecords($condition, $values, max(0, $page - 1) * $num, $num);

        foreach ($list as &$l) {
            $l['minversion'] = self::recoverVersion($l['minversion']);
            $l['maxversion'] = self::recoverVersion($l['maxversion']);
        }
        unset($l);

        return array(
            $total,$list
        );
    }

    public function listByName($region, $name, $page, $num)
    {
        list($total, $list) = $this->getRecords('region = ? and name = ?', array($region, $name), max(0, $page - 1) * $num, $num);

        foreach ($list as &$l) {
            $l['minversion'] = self::recoverVersion($l['minversion']);
            $l['maxversion'] = self::recoverVersion($l['maxversion']);
        }

        return array($total, $list);
    }

    public function search($keyword, $page, $num)
    {
        list($total, $list) = $this->getRecords('remark like ? or value like ?', array("%{$keyword}%", "%{$keyword}%"), max(0, $page - 1) * $num, $num);

        foreach ($list as &$l){
            $l['minversion'] = self::recoverVersion($l['minversion']);
            $l['maxversion'] = self::recoverVersion($l['maxversion']);
        }

        return array($total, $list);
    }

    public function history($configId)
    {
        $sql = "select * from config_history where config_id = ? order by id desc limit 20";
        return $this->getAll($sql, array($configId));
    }

    public function addConfig($region, $name, $value, $expire, $platform, $minVersion, $maxVersion, $remark, $tag, $schema, $adduser)
    {
        $minVersionConverted = self::convertVersion($minVersion);
        $maxVersionConverted = self::convertVersion($maxVersion);

        try{
            $id = $this->addRecord(array('region' => $region,
                'name' => $name,'value' => $value,'expire' => $expire,'platform' => $platform,'minversion' => $minVersionConverted,'maxversion' => $maxVersionConverted,'remark' => $remark, 'tag' => $tag, 'jsonschema' => $schema, 'adduser' => $adduser
            ));
            if ($id) {
                // 如果是json格式，转换为数组上传，前端读取结果时直接返回数组
                //json_decode($value) && $value = json_decode($value);
                ShareClient::setConfig($platform, $id, $region, $name, $value, $expire, $minVersion, $maxVersion);
            }
        }catch(Exception $e){
            return false;
        }
        return true;
    }

    public function updateConfig($id, $region, $name, $value, $expire, $platform, $minVersion, $maxVersion, $remark, $tag, $schema, $adduser, $operator)
    {
        $minVersionConverted = self::convertVersion($minVersion);
        $maxVersionConverted = self::convertVersion($maxVersion);

        ShareClient::setConfig($platform, $id, $region, $name, $value, $expire, $minVersion, $maxVersion);
        $result = $this->setRecord($id, array('region' => $region,
            'name' => $name,'value' => $value,'expire' => $expire,'platform' => $platform,'minversion' => $minVersionConverted,'maxversion' => $maxVersionConverted,'remark' => $remark, 'tag' => $tag, 'jsonschema' => $schema, 'adduser' => $adduser
        )) !== false;
        $this->insert('config_history', array(
            'config_id' => $id,
            'region' => $region,
            'name' => $name,
            'value' => $value,
            'expire' => $expire,
            'platform' => $platform,
            'minversion' => $minVersionConverted,
            'maxversion' => $maxVersionConverted,
            'remark' => $remark,
            'addtime' => date('Y-m-d H:i:s'),
            'jsonschema' => $schema,
            'operator' => $operator));


        return $result;
    }

    public function delConfig($id)
    {
        // 先保证线上
        ShareClient::deleteConfig($id);
        return $this->delRecord($id);
    }

    public function getConfig($id, $region='china')
    {
        $config = array();

        $sql = "select * from {$this->getTableName()} where id=?";
        $config = $this->getRow($sql, $id);
        if (!empty($config)) {
            $config['minversion'] = self::recoverVersion($config['minversion']);
            $config['maxversion'] = self::recoverVersion($config['maxversion']);

            try {
                $return = ShareClient::getConfigs($region, $config['platform'], $config['minversion'], $config['name']);
            } catch(Exception $e) {
                $return = array();
            }
            $config[$config['name']] = $return[$config['name']];
        }

        return $config;
    }

    public function getAllTags()
    {
        $sql = "select distinct tag from {$this->getTableName()} where tag != ''";
        $result = $this->getAll($sql, array(), false);
        $tags = array();
        foreach ($result as $v) {
            $tags[] = $v['tag'];
        }

        return $tags;
    }

    public function getAllPlatforms()
    {
        return self::$platforms;
    }

    public function getAllRegions()
    {
        return self::$regions;
    }
    public static function convertVersion($version, $base = self::VERSION_CONVERT_BASE, $power = self::VERSION_CONVERT_POWER)
    {
        $version = explode('.', $version);
        $result = 0;
        foreach ($version as $v) {
            $result += $v * pow($power, $base--);
        }

        return ceil($result);
    }

    public static function recoverVersion($version, $base = self::VERSION_CONVERT_BASE, $power = self::VERSION_CONVERT_POWER)
    {
        $versions = array();
        for ($i = 0; $i <= $base; ++$i) {
            $versions[] = $version % $power;
            $version /= $power;
        }

        return implode('.', array_reverse($versions));
    }
    public function setConfig($region, $name, $value, $expire, $platform, $minVersion, $maxVersion, $remark, $tag, $schema, $adduser, $adminid)
    {
        list($total, $list) = $this->listByName($region, $name, 1, 100);
        if(empty($list)){
            $this->addConfig($region, $name, $value, $expire, $platform, $minVersion, $maxVersion, $remark, $tag, $schema, $adduser);
        }else{
            foreach($list as $val){
                $this->updateConfig($val['id'], $region, $name, $value, $expire, $platform, $minVersion, $maxVersion, $remark, $tag, $schema, $adduser, $adminid);
            }
        }
    }
}
