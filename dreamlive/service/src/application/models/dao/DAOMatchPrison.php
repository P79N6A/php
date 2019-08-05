<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2017/12/11
 * Time: 11:41
 */
class DAOMatchPrison extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_GAME");
        $this->setTableName("match_prison");
    }
    /*
     * 判断用户是否在小黑屋
     * @param int $uid
     * */
    public function isPrison($uid)
    {
        $sql = "select count(*) from {$this->getTableName()} where uid=? and endtime>? and deleted=?";

        return $this->getOne($sql, [$uid,date("Y-m-d H:i:s"),'N']);
    }
    /*
     * 添加小黑屋
     * */
    public function addPrison($uid, $endtime, $source, $matchid, $adminid)
    {
        $info       = array(
            'uid'  => $uid,
            'addtime' => date("Y-m-d H:i:s"),
            'endtime' => $endtime,
            'source'  => $source,
            'matchid' => $matchid,
            'adminid' => $adminid
        );
        return $this->replace($this->getTableName(), $info);
    }
    
    /*
     * 添加小黑屋
     * */
    public function addPrisonWorker($uid, $prison_day, $source, $matchid, $adminid)
    {
        $now = time();
        $prison_time = ($now +($prison_day * 24 * 3600));
        
        $info       = array(
        'uid'  => $uid,
        'addtime' => date("Y-m-d H:i:s", $now),
        'endtime' => date("Y-m-d H:i:s", $prison_time),
        'source'  => $source,
        'matchid' => $matchid,
        'adminid' => $adminid
        );
        return $this->replace($this->getTableName(), $info);
    }
    /*
     * 移除小黑屋
     * */
    public function delPrison($prisonid, $note)
    {
        $update = [];
        $update['note']     = $note;
        $update['modtime']     = date("Y-m-d H:i:s");
        $update['deleted']    = 'Y';
        return $this->update($this->getTableName(), $update, "prisonid=?", $prisonid);
    }
    /*
     * 移除小黑屋
     * */
    public function delPrisonByUid($uid, $note)
    {
        $update = [];
        $update['note']     = $note;
        $update['modtime']     = date("Y-m-d H:i:s");
        $update['deleted']    = 'Y';
        return $this->update($this->getTableName(), $update, "uid=?", $uid);
    }
    /**
     * 调用入口
     * clean_relict_finish_match_worker.php
     *
     * @return boolen
     */
    public function delOverDueData()
    {
        $update = [];
        $update['note']     = "定是脚本移除过期数据";
        $update['modtime']     = date("Y-m-d H:i:s");
        $update['deleted']    = 'Y';
        return $this->update($this->getTableName(), $update, " endtime <= ? ", date("Y-m-d H:i:s"));
    }
}
