<?php

class DAOFamilyAuthorDaily extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_FAMILY");
        $this->setTableName("author_daily");
    }

    public function updateDaily($id, $newfans, $share, $livelength)
    {
        $info = [
            'newfans_manual' => $newfans,
            'share_manual' => $share,
            'livelength_manual' => $livelength,
        ];

        return $this->update($this->getTableName(), $info, "id=? ", [$id]);
    }

    public function syncDaily($id)
    {
        $count = count(explode(',', $id));
        $sql = "update {$this->getTableName()} set sync=1,newfans=newfans_manual,share=share_manual,livelength=livelength_manual where id in ({$id}) limit ?";
        return $this->execute($sql, $count);
    }

}