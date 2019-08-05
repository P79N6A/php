<?php
class DAOUserMergeLog extends DAOProxy
{

    public function __construct()
    {
        /* {{{ */
        $this->setDBConf("MYSQL_CONF_PASSPORT");
        $this->setTableName("user_merge_log");
    }
    /* }}} */
    public function addLog($newuid, $olduid)
    {
        /* {{{ */

        $info = array(
            'newuid'     => $newuid,
            'olduid'     => $olduid,
            'addtime'    => date("Y-m-d H:i:s")
        );
        $this->setDebug(true);
        return $res = $this->insert($this->getTableName(), $info);
    }
    public function addLogByLogid($id,$data)
    {
        $info["modtime"] = date("Y-m-d H:i:s");

        if($data['followers']) {
            $info["followers"]  = $data['followers'];
        }
        if($data['followings']) {
            $info["followings"] = $data['followings'];
        }
        if($data['ticket']) {
            $info["ticket"] = $data['ticket'];
        }
        if($data['medals']) {
            $info["medals"]     = $data['medals'];
        }
        if($data['protect']) {
            $info["protect"]    = $data['protect'];
        }
        if($data['live']) {
            $info["live"]       = $data['live'];
        }
       

        return $this->update($this->getTableName(), $info, "newuid=?", $id);
    }

 
}
