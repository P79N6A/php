<?php
class DAOMessage extends DAOProxy
{
    public function __construct($receiver)
    {
        $this->setDBConf("MYSQL_CONF_MESSAGE");
        $this->setShardId($receiver);
        $this->setTableName("message");
    }
    
    public function addMessage($sender, $receiver, $type, $content, $replace_keyword)
    {
        $message = array(
            "sender"            => $sender,
            "receiver"            => $receiver,
            "type"                => $type,
            "content"            => $content,
            "replace_keyword"    => $replace_keyword,
            "addtime"            => date("Y-m-d H:i:s")
        );
        
        return $this->insert($this->getTableName(), $message);
    }
}
?>
