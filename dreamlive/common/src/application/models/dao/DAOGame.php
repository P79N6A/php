<?php

class DAOGame extends DAOProxy
{
    const TYPE_HORSERACING = 1;//跑马
    const TYPE_HORSERACING_STAR=2;//跑马星光场
    const TYPE_LOTTO=3;//大转盘游戏

    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_GAME");
        $this->setTableName("game");
    }

    /**
     * 获取游戏列表详情
     */
    public function getGameInfos()
    {
        $sql = "select * from " . $this->getTableName() . " where isshow = ? order by weight";

        return $this->getAll($sql, 'Y');
    }

    /**
     * 获取单个游戏详情
     *
     * @param  int $gameid 游戏id
     * @return mixed
     */
    public function getGameInfo($gameid)
    {
        $sql = "select * from " . $this->getTableName() . " where isshow = ? and gameid = ? limit 1";

        return $this->getRow($sql, ['Y', $gameid]);
    }

    public function getFiled()
    {
        return "gameid,name,icon,extends,type,chatroomid";
    }

    public function addGame($name, $icon, $type, $isshow, $extends, $h5_url, $weight)
    {
        $info = [
            'name' => $name,
            'icon' => $icon,
            'type' => $type,
            'isshow' => $isshow,
            'extends' => $extends,
            'h5_url' => $h5_url,
            'weight' => $weight
        ];

        return $this->insert($this->getTableName(), $info);
    }

    public function updateBaseInfo($gameid, $name, $icon, $type, $isshow,$chatroomid="", $h5_url, $weight)
    {
        $info = [
            'name' => $name,
            'type' => $type,
            'isshow' => $isshow,
            'h5_url' => $h5_url,
            'weight' => $weight
        ];

        if ($chatroomid) {
            $info['chatroomid']=$chatroomid;
        }
        if ($icon) {
            $info['icon'] = $icon;
        }

        return $this->update($this->getTableName(), $info, "gameid=?", $gameid);
    }

    public function updateExtendsInfo($gameid, $extends)
    {
        $info = [
            'extends' => $extends,
        ];

        return $this->update($this->getTableName(), $info, "gameid=?", $gameid);
    }

    public function getGameByType($type)
    {
        return $this->getRow("select * from " . $this->getTableName() . " where isshow='Y' and type=?", [$type]);
    }

    public function getGameById($gameid)
    {
        return $this->getRow("select * from ".$this->getTableName()." where gameid=? limit 1", [$gameid]);
    }
}
