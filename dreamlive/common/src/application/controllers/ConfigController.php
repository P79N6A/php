<?php
class ConfigController extends BaseController
{
    public function getsAction()
    {
        $region = !empty($this->getParam('region')) ? $this->getParam('region') : Region::REGION_CHINA;
        $names = $this->getParam('names');
        $platform = empty($this->getPost('platform')) ? Context::get("platform") : $this->getPost('platform');
        $version = empty($this->getPost("version")) ? Context::get("version") : $this->getPost('version');

        Interceptor::ensureNotEmpty($region, ERROR_PARAM_INVALID_FORMAT, 'region');
        Interceptor::ensureNotFalse(in_array(strtolower($region), Region::REGION_ALL), ERROR_PARAM_INVALID_FORMAT, "region");
        Interceptor::ensureNotEmpty($names, ERROR_PARAM_INVALID_FORMAT, 'names');
        Interceptor::ensureNotFalse(in_array(strtolower($platform), array("android","ios",'server','web', 'obs')), ERROR_PARAM_INVALID_FORMAT, "platform");
        Interceptor::ensureNotEmpty($version, ERROR_PARAM_IS_EMPTY, "version");

        $names = explode(',', $names);
        $results = array();

        $config = new Config();
        foreach ($names as $name) {
            if (false !== ($value = $config->getConfig($region, $name, $platform, $version))) {
                $results[$name] = $value;
            }
        }

        $this->render($results);
    }

    public function setAction()
    {
        $id             = $this->getParam('id');
        $region         = $this->getParam('region');
        $name             = $this->getParam('name');
        $value             = $this->getParam('value');
        $expire         = (int) $this->getParam('expire');
        $platform         = $this->getPost('platform');
        $min_version     = $this->getParam('min_version');
        $max_version     = $this->getParam('max_version');

        Interceptor::ensureNotEmpty($region, ERROR_PARAM_INVALID_FORMAT, 'region');
        Interceptor::ensureNotFalse(in_array(strtolower($region), Region::REGION_ALL), ERROR_PARAM_INVALID_FORMAT, "region");
        Interceptor::ensureNotFalse(in_array($platform, array("android", "ios", 'server', 'web')), ERROR_PARAM_INVALID_FORMAT, "platform");
        Interceptor::ensureNotEmpty($id, ERROR_PARAM_IS_EMPTY, 'id');
        Interceptor::ensureNotEmpty($name, ERROR_PARAM_IS_EMPTY, 'name');

        $config = new Config();
        $config->setConfig($id, $region, $name, $value, $expire, $platform, $min_version, $max_version);

        $this->render();
    }

    public function deleteAction()
    {
        $id = $this->getParam('id');

        Interceptor::ensureNotEmpty($id, ERROR_PARAM_IS_EMPTY, 'id');

        $config = new Config();
        $config->delConfig($id);

        $this->render();
    }
}
?>
