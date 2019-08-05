<?php
class DAOPreviewResource extends DAOProxy
{

    const

        STATUS_COMMON       = 200,
        STATUS_FAIL         = 500,

        END = '';

    public function __construct(){
        $this->setDBConf("MYSQL_CONF_MALL");
        $this->setTableName("preview_resource");
        parent::__construct();
    }

    public static function getStatusArr($status='')
    {
        $statusArr = [
            self::STATUS_COMMON     => '正常',
            self::STATUS_FAIL       => '异常',
        ];

        return isset($statusArr[$status]) ? $statusArr[$status] : $statusArr;
    }

    public function getListByPackageid($packageid)
    {
        $sql = "SELECT * FROM ".$this->getTableName()." WHERE packageid =? and status = ? and url <> ''";
        return $this->getAll($sql,array($packageid, self::STATUS_COMMON));
    }

    public function getListBySellid($sellid)
    {
        $sql = "SELECT * FROM ".$this->getTableName()." WHERE sellid =? ";
        return $this->getAll($sql,array('sellid'=>$sellid));
    }

    public function add($type, $sellid, $is_cover, $url)
    {

        $data = [
            'type'          => $type,
            'sellid'        => $sellid,
            'is_cover'      => $is_cover,
            'url'           => $url,
            'status'        => self::STATUS_COMMON,
        ];

        return $this->insert($this->getTableName(), $data);

    }

    public function updateInfo($id, $data)
    {

        $condition = "id = ? ";

        $param = [
            'id'    => $id,
        ];

        return $this->update($this->getTableName(), $data, $condition, $param);
    }

    public function updatePackageIdBySellId($sellid, $packageid)
    {
        $record = [
            'packageid' => $packageid
        ];
        $condition = " sellid = ? and status = ? ";
        $param = [
            $sellid, self::STATUS_COMMON
        ];

        return $this->update($this->getTableName(), $record, $condition, $param);
    }



    public function getOneById($id)
    {
        $sql = "SELECT * FROM ".$this->getTableName()." WHERE id = ? ";
        return $this->getRow($sql, [$id]);
    }

    public function getListByIds($ids)
    {
        $sql = "SELECT * FROM ".$this->getTableName()." WHERE id in ($ids) ";
        return $this->getAll($sql);
    }


}