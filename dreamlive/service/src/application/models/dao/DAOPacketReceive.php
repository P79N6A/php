<?php

class DAOPacketReceive extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("packet_receive");
    }

    public function add($packetid, $uid, $amount, $liveid, $status, $num)
    {
        $field = [];
        $field = array_pad($field, $num, '(?,?,?,?,?,?)');
        $field = implode(',', $field);

        $addtime = date('Y-m-d H:i:s');
        $value = [];
        foreach ($amount as $item) {
            $value[] = $packetid;
            $value[] = $uid;
            $value[] = $item;
            $value[] = $liveid;
            $value[] = $status;
            $value[] = $addtime;
        }
        unset($item);
        $sql = "INSERT INTO {$this->getTableName()} (packetid,uid,amount,liveid,status,addtime) VALUES {$field}";

        return $this->execute($sql, $value);
    }

    public function getReceiveInfoByPackageid($packetid, $status)
    {
        $sql = "SELECT * from {$this->getTableName()} WHERE packetid=? AND status=? ORDER BY modtime DESC";

        return $this->getAll($sql, [$packetid, $status]);
    }

    public function getReceiveInfoByUid($packetid, $uid)
    {
        $sql = "SELECT * from {$this->getTableName()} WHERE packetid=? AND uid=? LIMIT 1";

        return $this->getRow($sql, $packetid, $uid);
    }

    public function getInfoByReceiveid($receiveid, $locked = false)
    {
        $sql = "SELECT * from {$this->getTableName()} WHERE id=?";
        $sql .= $locked ? " for update" : "";

        return $this->getRow($sql, $receiveid);
    }

    public function modify($receiveid, $uid, $status)
    {
        $info = [
            'uid' => $uid,
            'status' => $status,
            'modtime' => date('Y-m-d H:i:s'),
        ];

        return $this->update($this->getTableName(), $info, "id=? and uid=0", $receiveid);
    }
}