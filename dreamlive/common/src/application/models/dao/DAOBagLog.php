<?php
class DAOBagLog extends DAOProxy
{
    const TYPE_LOTTO=1;
    const TYPE_ADMIN=2;
    const TYPE_BUY=3;
    const TYPE_OTHER=4;

    const DIRECT_ADD='IN';
    const DIRECT_SUB='OUT';

    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("bag_log");
    }

    public function record($uid,$type,$direct,$relateid,$data,$ext)
    {
        $d=array(
        'uid'=>$uid,
        'type'=>$type,
        'direct'=>$direct,
        'relateid'=>$relateid,
        'data'=>$data,
        'extends'=>json_encode($ext),
        'addtime'=>date("Y-m-d H:i:s")
        );
        return $this->insert($this->getTableName(), $d);
    }

    private static function log($uid,$type,$direct,$relateid,$data,$ext)
    {
        $the=new DAOBagLog();
        return $the->record($uid, $type, $direct, $relateid, $data, $ext);
    }

    public static function logLotto($uid,$lottoLogId,$prize,$ext)
    {
        $type=self::TYPE_LOTTO;
        $direct=self::DIRECT_ADD;
        return self::log($uid, $type, $direct, $lottoLogId, $prize, $ext);
    }

    public static function logDefault($uid,$direct,$data,$ext)
    {
        return self::log($uid, self::TYPE_OTHER, $direct, 0, $data, $ext);
    }
}
?>
