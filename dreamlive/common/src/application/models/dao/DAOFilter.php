<?php
class DAOFilter extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_MESSAGE");
        $this->setTableName("filter");
    }
    
    public function addFilter($sender, $receiver, $type, $content, $replace_keyword, $liveid, $source = 'PROP')
    {
        $message = array(
            "sender"            => $sender,
            "receiver"            => $receiver,
            "type"                => $type,
            "content_old"        => $content,
            "replace_content"    => $replace_keyword,
            "addtime"            => date("Y-m-d H:i:s"),
            "source"            => $source,
            "liveid"            => $liveid
        );
        
        return $this->insert($this->getTableName(), $message);
    }
}
?>