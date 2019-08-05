<?php
class ActivityConfig
{
    const CONFIG_ENABLE  = "Y"; // 开启
    const CONFIG_DISABLE = "N"; // 关闭

    const QXC = "1";

    public function isOpen($id)
    {
        $dao = new DAOActivityConfig();

        return $dao->isOpen($id);
    }

    public function setConfig($id, $status)
    {
        $dao = new DAOActivityConfig();

        return $dao->setConfig($id, $status);
    }

    public function getEnabledList()
    {
        $dao = new DAOActivityConfig();

        return $dao->getEnabledList();
    }

}