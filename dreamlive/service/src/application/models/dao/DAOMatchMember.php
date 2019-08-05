<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2017/12/11
 * Time: 17:50
 */
class DAOMatchMember extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_GAME");
        $this->setTableName("match_member");
    }
    /*
     * 添加成员信息
     * */
    public function addMatchMember($matchid, $uid)
    {
        $info   = array(
            'matchid'   => $matchid,
            'uid'        => $uid,
            'finished'  => 'N',
            'addtime'   => date("Y-m-d H:i:s")
        );

        return $this -> insert($this->getTableName(), $info);
    }
    /*
     * 是否被接受
     * */
    public function isAccept($matchid)
    {
        return $this -> getRow("select * from {$this->getTableName()} where matchid=? limit 1", [$matchid]);
    }
    /*
     * 获取用户的pk结果
     * */
    public function getPKResult($uid,$matchid)
    {
        return $this -> getRow("select * from {$this->getTableName()} where matchid=? and uid=? limit 1", [$matchid,$uid]);
    }
    
    /**
     * 修改结束
     *
     * @param  int $matchid
     * @return boolean
     */
    public function modFinishMatch($matchid, $uid, $score)
    {
        $param = array(
        'finished'   => 'Y',
        'score'         => $score,
        'modtime'    => date("Y-m-d H:i:s")
        );
        return $this -> update($this->getTableName(), $param, " matchid=? and uid = ? ", [$matchid, $uid]);
    }
    /*
     * 获取每场次中分数较大的用户
     * */
    public function getMaxscore($matchid)
    {
        return $this -> getRow("select * from {$this->getTableName()} where matchid=? order by score desc limit 1", [$matchid]);
    }
}
