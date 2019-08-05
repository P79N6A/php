<?php
class DAOConfig extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_LIVE");
        $this->setTableName("config");
    }

    public function getConfigById($id)
    {
        $sql = 'select * from ' . $this->getTableName() . ' where id = ?';
        $data = $this->getRow($sql, $id);

        $data && $data['value'] = json_decode($data['value'], true);

        return $data;
    }

    public function getConfig($region, $name, $platform, $min_version, $max_version)
    {
        // 取最小版本要求最接近的 order by min_version desc
        $sql = 'select * from ' . $this->getTableName() . ' where `region` = ? and `name` = ? and `platform` = ? and min_version <= ? and (max_version >= ? or max_version = 0) order by min_version desc, id desc limit 1';
        $data = $this->getRow(
            $sql, array(
            $region,
            $name,
            $platform,
            $min_version,
            $max_version
            )
        );

        $data && $data['value'] = json_decode($data['value'], true);

        return $data;
    }

    public function getConfigs($region, array $names, $platform, $min_version, $max_version)
    {
        $values = array_merge(
            array($region), $names, array(
            $platform,
            $min_version,
            $max_version
            )
        );

        $placeholder = implode(',', array_fill(0, count($names), '?'));
        // 取最小版本要求最接近的 order by minversion desc
        $sql = 'select * from ' . $this->getTableName() . ' where `region` = ? and `name` in (' . $placeholder . ') and `platform` = ? and min_version <= ? and (max_version >= ? or max_version = 0) order by min_version asc, id asc';
        $datas = $this->getAll($sql, $values);

        $result = array();
        foreach ($datas as $data) {
            $data['value'] = json_decode($data['value'], true);
            $result[$data['name']] = $data;
        }

        return $result;
    }

    public function setConfig($id, $region, $name, $value, $expire, $platform, $min_version, $max_version)
    {
        $info = array(
            'id' => $id,
            'region' => $region,
            'name' => $name,
            'platform' => $platform,
            'min_version' => $min_version,
            'max_version' => $max_version,
            'value' => json_encode($value),
            'expire' => $expire,
            'addtime' => date('Y-m-d H:i:s'),
            'modtime' => date('Y-m-d H:i:s')
        );

        return $this->replace($this->getTableName(), $info);
    }

    public function delConfig($id)
    {
        return $this->delete($this->getTableName(), 'id = ?', $id);
    }
}
?>
