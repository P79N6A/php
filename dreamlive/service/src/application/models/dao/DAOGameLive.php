<?php
class DAOGameLive extends DAOProxy
{
    const TYPE_ON=1;//开启
    const TYPE_OFF=2;//关闭

    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_GAME");
        $this->setTableName("game_live");
    }
    /**
     * 添加游戏开启记录
     *
     * @param int $gameid 游戏id
     * @param int $userid 主播id
     * @param int $liveid 直播id
     */
    public function insertGameLive($gameid,$userid,$liveid)
    {
        $param      = array(
            'gameid'      => $gameid,
            'uid'         => $userid,
            'liveid'      => $liveid,
            'addtime'     => date("Y-m-d H:i:s")
        );
        return $this->insert($this->getTableName(), $param);
    }
    public function getGameId($uid)
    {
        $sql    = "select gameid,type from {$this->getTableName()} where uid = ? order by addtime desc limit 1";
        return $this ->getRow($sql, $uid);
    }
    public function updateGameLive($uid)
    {
        $param      = array(
            'type'      => 2,
            'endtime'   => date("Y-m-d H:i:s")
        );
        return $this->update($this->getTableName(), $param, "uid=? and type = ?", array($uid,1));
    }
    /**
     * 获取当天所有玩游戏的直播间
     */
    public function getAllLiveInGame($gameid)
    {
        $sql="select uid,liveid from ".$this->getTableName()." where gameid=? and type=?";
        return $this->getAll($sql, ['gameid'=>$gameid,'type'=>self::TYPE_ON]);
    }
}
