<?php
class DAORivivalUsedLog extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_LIVE");
        $this->setTableName("rivival_used_log");
    }
    
    public function add($userid, $questionid, $roundid, $answer, $correct_answer, $addtime, $order)
    {
        $message = array(
        "uid"                => $userid,
        "questionid"        => $questionid,
        "roundid"            => $roundid,
        "answer"            => $answer,
        "addtime"            => $addtime,
        "correct_answer"    => $correct_answer,
        "order"                => $order
        );
        
        return $this->insert($this->getTableName(), $message);
    }
}
?>