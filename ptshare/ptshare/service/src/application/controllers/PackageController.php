<?php
class PackageController extends BaseController
{
    protected $modelPackage;
    protected $modelPackageGoods;
    protected $modelPackagePreview;
    protected $modelPreview;
    protected $userid;

    public function __construct()
    {
        parent::__construct();
        $this->modelPackage = new Package();
        $this->modelPackageGoods = new PackageGoods();
        $this->modelPackagePreview = new PackagePreviews();
        $this->userid = Context::get("userid");
    }

    /**
     * packet列表
     */
    public function getPackageListAction()
    {
        $offset = $this->getParam("offset") ? (int) ($this->getParam("offset")) : 0;
        $num    = $this->getParam("num")    ? (int) ($this->getParam("num"))    : 20;
        $type   = $this->getParam("type")    ? (int) ($this->getParam("type"))  : 1;

        list($list,$total,$offset,$more) = Package::getPackageList($type, $num, $offset);

        $userid   = Context::get("userid");
        $userInfo = array();
        if($userid){
            $userInfo = User::getUserInfo($userid);
        }
        $this->render(array('list' => $list, 'user'=>(object)$userInfo, 'total' => $total, 'offset'=>$offset, 'more'=>$more));
    }

    /**
     * 用户分享packet列表
     */
    public function getUserPackageListAction()
    {
        $offset = $this->getParam("offset") ? (int) ($this->getParam("offset")) : 0;
        $num    = $this->getParam("num")    ? (int) ($this->getParam("num"))    : 20;
        $status = $this->getParam("status") ? trim($this->getParam("status"))   : "";
        $uid    = $this->getParam("uid")    ? trim($this->getParam("uid"))      : "";

        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, 'uid');
        if ($status != "") {
            Interceptor::ensureNotFalse(in_array($status, array(DAOPackage::STATUS_PREPARE,DAOPackage::STATUS_ONLINE,DAOPackage::STATUS_OFFLINE,DAOPackage::STATUS_SELLOUT)), ERROR_PARAM_INVALID_FORMAT, 'status');
        }

        $userid   = Context::get("userid");
        $userInfo = array();
        if($userid){
            $userInfo = User::getUserInfo($userid);
        }
        list($list,$total,$offset,$more) = Package::getUserPackageList($uid, $status, $num, $offset);

        $this->render(array('list' => $list, 'user'=>(object)$userInfo, 'total' => $total, 'offset'=>$offset, 'more'=>$more));
    }

    /**
     * packekinfo
     */
    public function getPackageInfoAction()
    {
        $packageid  = $this->getParam("packageid");
        Interceptor::ensureNotFalse($packageid > 0, ERROR_PARAM_INVALID_FORMAT, 'id');

        $info = Package::getPackageInfo($packageid);

        $this->render(array('info'=>$info));
    }


    /**
     * 获取PackageDetails
     */
    public function getPackageDetailsAction()
    {
        $id = $this->getParam("id");
        Interceptor::ensureNotFalse($id > 0, ERROR_PARAM_INVALID_FORMAT, 'id');

        // 获取package 信息
        $packageDetail = $this->modelPackage->getOneById($id);
        Interceptor::ensureNotEmpty($packageDetail, ERROR_PARAM_INVALID_FORMAT, 'empty detail');

        // 获取 goods 列表
        $goodsList = $this->modelPackageGoods->getListByPackageId($id);
        Interceptor::ensureNotEmpty($goodsList, ERROR_PARAM_INVALID_FORMAT, 'empty goods');

        // 获取封面列表
        $previews = $this->modelPackagePreview->getListByPackageid($id);
        Interceptor::ensureNotEmpty($previews, ERROR_PARAM_INVALID_FORMAT, 'empty previews');

        // 是否是用户喜欢的
        $favorite = '';
        $userInfo = array();
        if ($this->userid) {
            $favoriteInfo = $this->modelPackage->getFavoriteInfoByIdUserId($id, $this->userid);
            if (!empty($favoriteInfo)) {
                $favorite = 'favorite';
            }
            $userInfo = User::getUserInfo($this->userid);
        }

        // 获取 user 信息
        $user = User::getUserInfo($packageDetail['sell_user_id']);

        $this->render(['packageDetail' => $packageDetail, 'goodsList' => $goodsList, 'previews' => $previews, 'favorite' => $favorite, 'user' => $user, 'userInfo'=>(object)$userInfo]);
    }



    public function favoriteAction()
    {

        $userid         = $this->userid;
        $id             = $this->getParam("id");

        Interceptor::ensureNotFalse($userid > 0, ERROR_PARAM_INVALID_FORMAT, 'user_id');
        Interceptor::ensureNotFalse($id > 0, ERROR_PARAM_INVALID_FORMAT, 'id');

        $favoriteResult = $this->modelPackage->doFavoriteOrCancel($id, $userid);
        $return = [
            'favorite_num'  => $favoriteResult['favorite_num'],
            'favorite'      => $favoriteResult['favorite']
        ];
        $this->render($return);

    }


    public function addAction()
    {

        $sellid = (int)$this->getParam("sellid");

        Interceptor::ensureNotFalse(($sellid > 0 && is_numeric($sellid)), ERROR_PARAM_INVALID_FORMAT, 'sellid');

        $packageid = $this->modelPackage->add($sellid);

        $this->render(['packageid' => $packageid]);

    }

    public function updateAction()
    {
        $id             = $this->getParam("id");
        $online         = trim(strip_tags($this->getParam("online")));
        $description    = trim(strip_tags($this->getParam("description")));
        $deposit_price  = $this->getParam("deposit_price");
        $rent_price  	= $this->getParam("rent_price");
        $status  		= $this->getParam("status");
        $location		= $this->getParam("location");
        $num	  		= $this->getParam("num");
        $type           = $this->getParam("type");
        $vip            = $this->getParam("vip");

        $package = new Package();
        $bool = $package->update($id, $online, $description, $deposit_price, $rent_price, $status, $num, $location, $type, $vip);

        $this->render();
    }

    public function getUserFavoriteListAction()
    {
        $offset = $this->getParam("offset") ? (int) ($this->getParam("offset")) : 0;
        $num    = $this->getParam("num")    ? intval($this->getParam("num")) : 20;
        $userid = $this->userid;

        // 获取用户添加收藏的列表
        $result = $this->modelPackage->getUserFavoriteList($userid, $num, $offset);

        $userid   = Context::get("userid");
        $userInfo = User::getUserInfo($userid);

        $this->render(array('list' => $result['list'], 'offset' => $result['offset'], 'more' => $result['more'],'userInfo'=>$userInfo));
    }

}