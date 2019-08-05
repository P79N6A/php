<?php
class ClientController extends BaseController
{
    // 日志收集
    public function reportLogAction()
    {
        $model       = trim(strip_tags($this->getParam("model")));
        $brand         = trim(strip_tags($this->getParam('brand')));
        $logurl        = trim(strip_tags($this->getParam('logurl')));
        $version    = trim(strip_tags($this->getParam('version')));
        $userid     = intval(Context::get('userid'));

        $info = array(
        "userid"        => $userid,
        "logurl"        => $logurl,
        "brand"         => $brand,
        "model"            => $model,
        "version"        => $version,

        );
        include_once 'process_client/ProcessClient.php';
        ProcessClient::getInstance("dream")->addTask("client_log_worker", $info);

        $this->render();
    }
}

?>