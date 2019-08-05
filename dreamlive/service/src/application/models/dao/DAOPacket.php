<?php

class DAOPacket extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("packet");
    }

    public function add($uid, $type, $amount, $num, $threshold, $remark, $liveid, $extends, $status, $active, $activetime)
    {
        $info = [
            'uid' => $uid,
            'type' => $type,
            'amount' => $amount,
            'num' => $num,
            'threshold' => $threshold,
            'remark' => $remark,
            'liveid' => $liveid,
            'extends' => $extends,
            'status' => $status,
            'active' => $active,
            'activetime' => $activetime,
            'addtime' => date('Y-m-d H:i:s'),
        ];

        return $this->insert($this->getTableName(), $info);
    }

    public function getPacketInfo($packetid)
    {
        $sql = "SELECT * FROM {$this->getTableName()} WHERE packetid=?";

        return $this->getRow($sql, $packetid);
    }

    public function modify($packetid, $status, $active)
    {
        $info = [
            'status' => $status,
            'active' => $active
        ];

        return $this->update($this->getTableName(), $info, "packetid=?", $packetid);
    }

    public function hasActive($liveid, $type)
    {
        $sql = "SELECT packetid FROM {$this->getTableName()} WHERE liveid=? AND type=? AND status=? LIMIT 1";

        return $this->getOne($sql, [$liveid, $type, 0]);
    }

    public function active($packetid)
    {
        $now = date('Y-m-d H:i:s');
        $sql = "UPDATE {$this->getTableName()} SET active=?, activetime=?, modtime=? WHERE packetid=? AND active=? AND status=? LIMIT 1";

        return $this->execute($sql, ['Y', $now, $now, $packetid, 'N', '0']);
    }
}