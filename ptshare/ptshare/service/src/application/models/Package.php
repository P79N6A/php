<?php
class Package
{
    public static $user_sellout = array(
        21000331,
        21000332,
        21000333,
        21000334,
        21000335,
        21000336,
        21000337,
        21000338,
        21000339,
        21000340
    );

    protected $daoPackage;
    protected $daoSell;
    protected $modelPackageGoods;
    protected $modelPackagePreview;
    protected $daoPackageGoods;
    protected $daoPackagePreview;
    protected $daoPreviewResource;



    const PACKET_SERVICES_FEE   = 3;
    const PACKET_EXPRESS_FEE    = 18;
    const PACKET_PERCENT_PRICE  = 0.05;
    const PACKET_VIP_PERCENT    = 0.8;



    public function __construct()
    {
        $this->daoPackage           = new DAOPackage();
        $this->daoSell              = new DAOSell();
        $this->daoGoods             = new DAOGoods();
        $this->daoPreviewResource   = new DAOPreviewResource();
        $this->daoPackagePreview    = new DAOPackagePreviews();
        $this->daoPackageGoods      = new DAOPackageGoods();
        $this->modelPackageGoods    = new PackageGoods();
        $this->modelPackagePreview  = new PackagePreviews();

    }

    /**
     * 获取列表
     * @param int $num
     * @param int $offset
     * @return array
     */
    public static function getPackageList($type, $num, $offset)
    {
        $DAOPackage = new DAOPackage();
        $list = $DAOPackage->getPackageList($type, $num, $offset);
        if (empty($list)) {
            return array(array(), 0, 0, false);
        }

        $userid = Context::get("userid");
        if($userid){
            $user_info = User::getUserInfo($userid);
        }
        foreach ($list as $key => $value){
            $list[$key]['vip']          = (bool)$list[$key]['vip'];
            $userInfo                   = User::getUserInfo($value['sell_user_id']);
            $list[$key]['show_time']    = Util::timeTransAfter($value['end_time']);
            $list[$key]['cover']        = Util::joinStaticDomain($value['cover']);
            $list[$key]['nickname']     = $userInfo['nickname'];
            if ($list[$key]['vip']) {
                $list[$key]['vip_price'] = ceil($list[$key]['deposit_price']);
            } else {
                $list[$key]['vip_price'] = ceil($list[$key]['deposit_price'] * Package::PACKET_VIP_PERCENT);
            }
        }

        $offset = end($list)['id'] ? end($list)['id'] : 0;

        $total = $DAOPackage->getPackageTotal($type);
        $more = (bool)$DAOPackage->getPackageMore($type, $offset);
        return array($list, $total, $offset, $more);
    }

    /**
     * 获取用户分享列表
     * @param int $uid
     * @return array|boolean
     */
    public static function getUserPackageList($uid, $status, $num, $offset)
    {
        $DAOPackage = new DAOPackage();
        $list = $DAOPackage->getUserPackageList($uid, $status);
        foreach ($list as $key => $value){
            $list[$key]['vip']          = (bool)$list[$key]['vip'];
            $userInfo                   = User::getUserInfo($value['sell_user_id']);
            $list[$key]['show_time']    = Util::timeTransAfter($value['end_time']);
            $list[$key]['cover']        = Util::joinStaticDomain($value['cover']);
            $list[$key]['nickname']     = $userInfo['nickname'];
            if ($list[$key]['vip']) {
                $list[$key]['vip_price'] = ceil($list[$key]['deposit_price']);
            } else {
                $list[$key]['vip_price'] = ceil($list[$key]['deposit_price'] * Package::PACKET_VIP_PERCENT);
            }

            $offset = $list[$key]['id'];
        }
        return array($list, count($list), $offset, false);
    }

    public function getUserFavoriteList($userid, $num=7, $offset=0)
    {
        $daoFavorite = new DAOFavorite();
        $result = $daoFavorite->getListByUserId($userid, $num, $offset);
        if (!empty($result['list'])) {
            $packageIds = Util::arrayToIds($result['list'], 'packageid');
            $result['list'] = $this->daoPackage->getListByIds($packageIds);
            if (!empty($result['list'])) {
                foreach ($result['list'] as $key => $value) {
                    $result['list'][$key]['vip']       = (bool)$result['list'][$key]['vip'];
                    $result['list'][$key]['show_time'] = Util::timeTransAfter($value['end_time']);
                    $result['list'][$key]['cover']     = Util::joinStaticDomain($value['cover']);
                    $user = User::getUserInfo($value['sell_user_id']);
                    $result['list'][$key]['nickname']  = $user['nickname'];
                    if ($result['list'][$key]['vip']) {
                        $result['list'][$key]['vip_price'] = ceil($result['list'][$key]['deposit_price']);
                    } else {
                        $result['list'][$key]['vip_price'] = ceil($result['list'][$key]['deposit_price'] * Package::PACKET_VIP_PERCENT);
                    }
                }
            }
        }

        return $result;
    }

    /**
     * 获取PackageInfo
     * @param int $id
     * @return array
     */
    public static function getPackageInfo($id)
    {
        $DAOPackage = new DAOPackage();
        $packageInfo = $DAOPackage->getOneById($id);

        Interceptor::ensureNotEmpty($packageInfo, ERROR_BIZ_PACKET_NOT_EXIST);

        $packageInfo['contact']      = (!empty($packageInfo['contact'])) ? json_decode($packageInfo['contact'], true) : array();
        $packageInfo['services_fee'] = self::PACKET_SERVICES_FEE;
        $packageInfo['express_fee']  = self::PACKET_EXPRESS_FEE;
        return $packageInfo;
    }

    /**
     * 获取PackageInfo
     * @param string $orderid
     */
    public static function getPackageInfoByOrderid($orderid){
        $DAOPackage = new DAOPackage();
        $packageInfo = $DAOPackage->getPackageInfoByOrderid($orderid);

        Interceptor::ensureNotEmpty($packageInfo, ERROR_BIZ_PACKET_NOT_EXIST);

        $packageInfo['contact']      = (!empty($packageInfo['contact'])) ? json_decode($packageInfo['contact'], true) : array();
        $packageInfo['services_fee'] = self::PACKET_SERVICES_FEE;
        $packageInfo['express_fee']  = self::PACKET_EXPRESS_FEE;
        return $packageInfo;
    }

    /**
     * 根据$packageId获取package详情
     * @param string $packageId
     */
    public static function getPackageInfoByPackageid($packageId){
        $DAOPackage = new DAOPackage();
        $packageInfo = $DAOPackage->getPackageInfoByPackageid($packageId);

        Interceptor::ensureNotEmpty($packageInfo, ERROR_BIZ_PACKET_NOT_EXIST);

        $packageInfo['contact']      = (!empty($packageInfo['contact'])) ? json_decode($packageInfo['contact'], true) : array();
        $packageInfo['cover']        = Util::joinStaticDomain($packageInfo['cover']);
        $packageInfo['services_fee'] = self::PACKET_SERVICES_FEE;
        $packageInfo['express_fee']  = self::PACKET_EXPRESS_FEE;
        return $packageInfo;
    }

    /**
     * 根据$packageIds批量获取package
     * @param array $packageId
     * @return array
     */
    public static function getPackageInfosByPackageids($packageIds)
    {
        if (! $packageIds) {
            return array();
        }
        if (! is_array($packageIds)) {
            $packageIds = array($packageIds);
        }

        $DAOPackage = new DAOPackage();
        $list = $DAOPackage->getPackageInfosByPackageids($packageIds);
        $packageInfos = array();
        foreach ($list as $item) {
            $packageInfo = $item;
            $packageInfo['services_fee'] = self::PACKET_SERVICES_FEE;
            $packageInfo['express_fee']  = self::PACKET_EXPRESS_FEE;

            $packageInfos[$item['packageid']] = $packageInfo;
        }
        return $packageInfos;
    }

    /**
     * 获取Package详情页
     * @param int $id
     * @return array
     */
    public static function getPackageDetails($id)
    {
        $DAOPackage = new DAOPackage();
        $packetInfo = $DAOPackage->getOneById($id);

        Interceptor::ensureNotEmpty($packetInfo, ERROR_BIZ_PACKET_NOT_EXIST);

        $goods = Goods::getListByPackageid($id);

        return array($packetInfo, $goods);
    }

    /**
     * Package上下架
     * @param int $id
     * @param string $online Y上线，N下线
     * @return boolean
     */
    public static function updatePackageOnline($packageId, $online)
    {
        $DAOPackage = new DAOPackage();
        Interceptor::ensureNotFalse($DAOPackage->updatePackageOnline($packageId, $online) > 0, ERROR_BIZ_ORDER_UPDATE_FAIL, 'update');
        return true;
    }

    /**
     * 修改orderid
     * @param int $packageId
     * @param int $orderid
     */
    public static function updatePackageOrderid($packageId, $orderid){
        $DAOPackage = new DAOPackage();
        Interceptor::ensureNotFalse($DAOPackage->updatePackageOrderid($packageId, $orderid) > 0, ERROR_BIZ_ORDER_UPDATE_FAIL, 'update');
        return true;
    }

    public static function isOnline($packageId)
    {
        $DAOPackage = new DAOPackage();
        return ($DAOPackage->getPackageOnlineStatus($packageId) == 'ONLINE');
    }

    public function doFavoriteOrCancel($id, $userid)
    {
        // 检测是否已经关注
        $info = $this->getFavoriteInfoByIdUserId($id, $userid);
        if (!empty($info)) {
            return $this->unFavorite($id, $userid);
        } else {
            return $this->favorite($id, $userid);
        }
    }

    public function getFavoriteInfoByIdUserId($id, $userid)
    {
        $daoFavorite = new DAOFavorite();
        return $daoFavorite->getInfo($id, $userid);
    }

    public function favorite($id, $userid)
    {

        $this->daoPackage->startTrans();

        try {
            $packageInfo = $this->daoPackage->getOneById($id);
            $result = $this->daoPackage->increaseFavorite($id);
            Interceptor::ensureNotFalse($result > 0, ERROR_PARAM_INVALID_FORMAT, "collect package fail");

            $daoFavorite = new DAOFavorite();
            $daoFavorite->add($id, $userid);

            $this->daoPackage->commit();

            return [
                'favorite_num'  => $packageInfo['favorite_num'] + 1,
                'favorite'      => 'favorite'
            ];

        } catch (Exception $exception) {

            $this->daoPackage->rollback();
            $data = [
                'log' => [
                    'id' => $id,
                    'userid' => $userid,
                ],
                'error_msg' => $exception->getMessage(),
            ];

            Logger::notice('mall_package', __CLASS__ . '_' . __FUNCTION__, json_encode($data));

            throw new BizException(ERROR_MALL_PACKAGE_FAVORITE, 'collect package fail');

        }

    }

    public function unFavorite($id, $userid)
    {
        $this->daoPackage->startTrans();

        try {
            $packageInfo = $this->daoPackage->getOneById($id);

            $result = $this->daoPackage->decreaseFavorite($id);
            Interceptor::ensureNotFalse($result > 0, ERROR_PARAM_INVALID_FORMAT, "cancel collect package fail");


            $daoFavorite = new DAOFavorite();
            $daoFavorite->deleteInfo($id, $userid);
            $this->daoPackage->commit();

            return [
                'favorite_num'  => $packageInfo['favorite_num'] - 1,
                'favorite'      => ''
            ];

        } catch (Exception $exception) {

            $this->daoPackage->rollback();
            $data = [
                'log' => [
                    'id' => $id,
                    'userid' => $userid,
                ],
                'error_msg' => $exception->getMessage(),
            ];

            Logger::notice('mall_package', __CLASS__.'_'.__FUNCTION__, json_encode($data));

            throw new BizException(ERROR_MALL_PACKAGE_FAVORITE, 'cancel collect package fail');

        }

    }


    public function addBySellId($sellId)
    {

        $packageId  = IDGEN::generate(IDGEN::PACKAGEID);
        $sn         = SnowFlake::nextId();

        // sell 信息是否存在
        $sellDetail = $this->daoSell->getOneById($sellId);
        if (empty($sellDetail)) {
            throw new BizException(ERROR_MALL_PACKAGE_ADD, $sellId);
        }

        // 查找宝贝，总和，
        $num = $this->daoGoods->getCanCreatePackageTotalBySellId($sellId);

        try {
            // 添加 package
            $contact = json_decode($sellDetail['contact'], true);

            $online = true;

            $totalGrapes = $this->daoGoods->getGrapeBySellId($sellId);
            $totalGrapes = $totalGrapes['show_grape'];
            // 商品售价自动计算为定价+5%。
            // 租金为定价5%每月
            $rentPrice = ceil($totalGrapes * self::PACKET_PERCENT_PRICE);
            // 商品出租时押金=售价
            $depositPrice = $totalGrapes;

            $endtime = '';
            $packageId = $this->daoPackage->add($sellDetail['uid'], $packageId, $sn, $sellDetail['categoryid'], DAOPackage::SOURCE_SELL, $sellDetail['id'], $sellDetail['cover'], $sellDetail['cover_type'], $sellDetail['contact'], $num, $sellDetail['description'], $sellDetail['uid'], $sellDetail['extends'], $online, $contact['contact_city'], $endtime, $depositPrice, $rentPrice, $sellDetail['cardid'], $sellDetail['grape_forward'],$sellDetail['sales_type'],$sellDetail['type'],$sellDetail['vip']);

            // 根据 sellid 查询关联的 goods 信息
            $goodsList = $this->daoGoods->getListBySellid($sellId);
            Interceptor::ensureNotEmpty($goodsList, ERROR_PARAM_INVALID_FORMAT, 'empty goods list');

            // 插入package_goods_关联关系
            $this->modelPackageGoods->add($packageId, $goodsList);

            // 查找封面
            $previews = $this->daoPreviewResource->getListBySellid($sellId);
            Interceptor::ensureNotEmpty($previews, ERROR_PARAM_INVALID_FORMAT, 'empty previews list');

            $this->modelPackagePreview->add($packageId, $previews);

            return $packageId;

        } catch (Exception $exception) {
            $data = [
                'log' => [
                    'sellid'        => $sellId,
                    'sn'            => $sn,
                    'cover'         => $sellDetail['cover'],
                    'cover_type'    => $sellDetail['cover_type'],
                    'description'   => $sellDetail['description'],
                    'extends'       => $sellDetail['extends'],
                    'num'           => $num,
                ],
                'error_msg' => $exception->getMessage(),
            ];
            Logger::log('mall_package', __CLASS__ . '_' . __FUNCTION__, json_encode($data));
            throw new BizException(ERROR_MALL_PACKAGE_ADD, $sellId);
        }
    }

    public function addByPackageId($packageId, $sourceUid=0, $contact=[], $location='', $endtime='')
    {
        $packageNum  = IDGEN::generate(IDGEN::PACKAGEID);

        try {
            $packageDetail = $this->daoPackage->getPackageInfoByPackageid($packageId);
            Interceptor::ensureNotEmpty($packageDetail, ERROR_PARAM_INVALID_FORMAT, 'empty package detail');

            $checkInfo = $this->daoPackage->getInfoBySourceIdFromPackage($packageDetail['id']);
            Interceptor::ensureEmpty($checkInfo, ERROR_PARAM_INVALID_FORMAT, 'package had been used');

            // 添加 package
            $contact = !empty($contact) ? json_encode($contact) : '';
            $online = true;

            $newPackageId = $this->daoPackage->add($packageDetail['sell_user_id'], $packageNum, $packageDetail['sn'], $packageDetail['categoryid'], DAOPackage::SOURCE_PACKAGE, $packageDetail['id'], $packageDetail['cover'], $packageDetail['cover_type'], $contact, $packageDetail['num'], $packageDetail['description'], $sourceUid,  $packageDetail['extends'], $online, $location, $endtime, $packageDetail['deposit_price'], $packageDetail['rent_price'], $packageDetail['cardid'], $packageDetail['grape_forward'],$packageDetail['sales_type'],$packageDetail['type'],$packageDetail['vip']);

            Interceptor::ensureNotFalse($newPackageId > 0, ERROR_PARAM_INVALID_FORMAT, 'package add error');

            // 根据 $packageId 查询关联的 goods 信息
            $goodsList = $this->daoPackageGoods->getListByPackageId($packageDetail['id']);
            Interceptor::ensureNotEmpty($goodsList, ERROR_PARAM_INVALID_FORMAT, 'empty goods list');

            // 插入package_goods_关联关系
            $this->modelPackageGoods->add($newPackageId, $goodsList, 'goods_id');

            // 查找封面
            $previews = $this->daoPackagePreview->getListByPackageid($packageDetail['id']);
            Interceptor::ensureNotEmpty($previews, ERROR_PARAM_INVALID_FORMAT, 'empty previews list');

            $this->modelPackagePreview->add($newPackageId, $previews, 'preview_id');

            return $newPackageId;

        } catch (Exception $exception) {
//            var_dump($exception->getMessage());
            $data = [
                'sn'        => $sn,
                'error_msg' => $exception->getMessage(),
            ];
            Logger::log('mall_package', __CLASS__ . '_' . __FUNCTION__, json_encode($data));
            throw new BizException(ERROR_MALL_PACKAGE_ADD, "add package [{$sn}] by sn error, error msg={$exception->getMessage()}");
        }
    }


    public function update($id, $online, $description, $deposit_price, $rent_price, $status, $num, $location, $type, $vip)
    {
        $dao_package = new DAOPackage();
        Interceptor::ensureNotFalse($dao_package->modify($id, $online, $description, $deposit_price, $rent_price, $status, $num, $location, $type, $vip) > 0, ERROR_BIZ_ORDER_UPDATE_FAIL, 'update');
        return true;
    }

    public function getOneById($id)
    {
        $result = $this->daoPackage->getOneById($id);
        $result['extends']          = (!empty($result['extends'])) ? json_decode($result['extends'], true) : '';
        $result['show_time']        = Util::timeTransAfter($result['end_time']);
        $result['cover']            = Util::joinStaticDomain($result['cover']);
        $result['services_fee']     = self::PACKET_SERVICES_FEE;
        $result['express_fee']      = self::PACKET_EXPRESS_FEE;
        $result['total_fee']        = self::PACKET_EXPRESS_FEE + self::PACKET_SERVICES_FEE;
        $result['vip']              = (bool)$result['vip'] ;
        $uid = Context::get("userid");
        $result['isExistSeizing'] = false;
        if(!empty($uid)){
            $DAOSeizing = new DAOSeizing();
            $result['isExistSeizing'] = $DAOSeizing->isExistSeizing($uid, $result['packageid']);
            $userInfo = User::getUserInfo($uid);
        }
        if (!empty($result['cardid'])) {
        	$cardinfo = Card::getInfo($result['cardid']);
        	if (empty($cardinfo)) {
        		$result['cardid'] = 0;
        	} else {
        		$result['cardinfo'] = $cardinfo;
        	}
        }
        if ($result['vip']) {
            $result['vip_price'] = ceil($result['deposit_price']);
        } else {
            $result['vip_price'] = ceil($result['deposit_price'] * Package::PACKET_VIP_PERCENT);
        }

        return $result;
    }

    public function doExpressSendOutByPackageId($packageId)
    {
        $packageInfo = $this->daoPackage->getPackageInfoByPackageid($packageId);
        Logger::log('sell_log', 'doExpressSendOutByPackageId',  array("packageId" => $packageId, "info" => json_encode($packageInfo)));
        if ($packageInfo['source'] == DAOPackage::SOURCE_SELL) {
            $modelSell = new Sell();
            return $modelSell->setStatusSendOut($packageInfo['source_id']);
        }
        return true;
    }

    public function doExpressReceiveByPackageId($packageId)
    {
        $packageInfo = $this->daoPackage->getPackageInfoByPackageid($packageId);
        Logger::log('sell_log', 'doExpressReceiveByPackageId',  array("packageId" => $packageId, "info" => json_encode($packageInfo)));
        if ($packageInfo['source'] == DAOPackage::SOURCE_SELL) {
            $modelSell = new Sell();
            return $modelSell->setSuccess($packageInfo['source_id']);
        }
        return true;
    }







}