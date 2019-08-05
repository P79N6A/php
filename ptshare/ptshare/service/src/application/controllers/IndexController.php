<?php
class IndexController extends BaseController
{

    protected $userid;

    public function __construct()
    {
        parent::__construct();

        $this->userid = Context::get("userid");
    }

    /**
     * packet列表
     */
    public function getBannerListAction()
    {

        // banner
        $config = new Config();
        $region = "china";
        $names  = "index_banner";
        $platform = "xcx";
        $version = '1';

        $bannerList = '';
        $banner = $config->getConfigs($region, $names, $platform, $version);
        if (isset($banner['index_banner']['value']) && !empty($banner['index_banner']['value'])) {
            $bannerList = json_decode($banner['index_banner']['value'], true);
        }

        $this->render(['list' => $bannerList]);
    }


}