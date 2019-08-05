<?php
class SystemController extends BaseController
{

    public function getConfigAction()
    {
        $lng = trim(strip_tags($this->getParam('lng')));
        $lat = trim(strip_tags($this->getParam('lat')));
        $country_code = $this->getParam('countryCode');
        $uid = Context::get("userid", '');

        //$region = Region::getRegion($lng, $lat, $country_code, $uid);

        // $abroad_array = array(20016557, 20071349, 12345678);
        // if(in_array($uid, $abroad_array)){
        //     $region = 'abroad';
        // }
        $region = 'china';

        $platform = Context::get("platform");
        $version = Context::get("version");
        $channel = Context::get("channel");
        $armour = Context::get("armour");
        $config = new Config();
        $cover = $config->getConfig(Context::get("region"), "cover", $platform, $version);
        if($armour=='mini' && $platform=='ios') {
            $upgrade = $config->getConfig(Context::get("region"), "mini_upgrade", $platform, $version);
        }else{
            $upgrade = $config->getConfig(Context::get("region"), "upgrade", $platform, $version);
        }

        $this->render(array("region" => $region, "cover" => $cover, "upgrade" => $upgrade));
    }

}
