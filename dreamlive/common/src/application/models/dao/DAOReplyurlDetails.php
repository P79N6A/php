<?php

class DAOReplyurlDetails extends DAOProxy
{

    /**
     * 构造方法
     */
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_LIVE");
        $this->setTableName("replyurl_details");
    }

    /**
     * 添加数据
     *
     * @param array $option
     */
    public function addData($option)
    {
        return $this->insert($this->getTableName(), $option);
    }
}
