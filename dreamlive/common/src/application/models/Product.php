<?php
class Product
{

    const CATEID_BEAUTY=1;//"美颜";
    const CATEID_EFFECT=2;//"滤镜";
    const CATEID_FACEU=3;//"FaceU";

    const LOCKTRADEPROCESS_TTL = 15;
    const LOCKTRADEPROCESS_TTL_RELEASE = -1;
    const DEFAULT_EXPIRE_DATE = '0000-00-00 00:00:00';
    const MAX_EXPIRE_DATE = '2049-12-12 23:59:59';
    
    

    private static function _getCacheKeyProduct()
    {
        return 'product_list';
    }

    /*  private static function _getCacheKeyProductOne($product_salt)
    {
        return 'product_info_' . $product_salt;
    }*/

    private static function _reloadCache()
    {
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $key = self::_getCacheKeyProduct();

        $product_list = self::all();
        if($product_list ) {
            $cache->set($key, json_encode($product_list));
            $cache->expire($key, 3600);
        }
        return true;
    }

    /*private static function lockTradeProcess($uid, $product_id, $seconds)
    {
        $key = sprintf("product_order_%s_%s", $uid, $product_id);
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        if( $seconds == self::LOCKTRADEPROCESS_TTL_RELEASE ) {
            return $cache->delete($key);
        }
        else {
            return $cache->add($key, 1, $seconds);
        }

        return false;
    }*/

    /* private static function productIsOnline($product_info)
    {
        return isset($product_info['online']) && $product_info['online']=='Y';
    }*/

    private static function productIsUsable($product_info, $bag_info)
    {
        $usable = 'N';
        if(!$bag_info ) {
            $usable = ((float)$product_info['price'] < 0.01) && $product_info['online']=='Y' ? 'Y' : 'N';
        } else {
            $usable = $product_info['prodcutid'] == $bag_info['prodcutid'] &&
               ( $bag_info['expiretime'] == self::DEFAULT_EXPIRE_DATE || ($bag_info['expiretime'] >= date('Y-m-d H:i:s'))
               ) ? 'Y' : 'N';
        }

        return $usable;
    }

    /* private static function bagExpiretimeCalc($base_date, $expire_seconds)
    {
        $time_base = strtotime($base_date);

        return date('Y-m-d H:i:s', intval($time_base + $expire_seconds));
    }*/

    private static function bagFinalExpiredByMultiRecord($bag_expire_records)
    {
        $expiretime_final = null;

        if($bag_expire_records ) {
            usort($bag_expire_records, create_function('$a,$b', 'return strcmp($a["addtime"], $b["addtime"]);'));
            if(($last = end($bag_expire_records)) && $last['expiretime'] == self::DEFAULT_EXPIRE_DATE ) {
                $expiretime_final = self::MAX_EXPIRE_DATE;
            } else {
                reset($bag_expire_records);
                $expiretime_pre = null;
                $addtime_pre = null;
                $pad_time = 0;
                foreach($bag_expire_records as $bag_info) {
                    if($bag_info['expiretime'] == self::DEFAULT_EXPIRE_DATE ) { continue;
                    }

                    if(!$expiretime_final || $expiretime_final < $bag_info['expiretime']) {
                        $expiretime_final = $bag_info['expiretime'];
                    }

                    if($expiretime_pre && $expiretime_pre >= $bag_info['addtime'] ) {
                        $pad_time++;
                        $expiretime_final = date('Y-m-d H:i:s', strtotime($addtime_pre) + $bag_info['product_expire'] * $pad_time);
                    } else {
                        $addtime_pre = $bag_info['addtime'];
                        $pad_time = 1;
                    }
                    $expiretime_pre = $bag_info['expiretime'];
                }
            }
        }

        return is_null($expiretime_final) && $bag_expire_records ? self::MAX_EXPIRE_DATE : $expiretime_final;
    }

    public static function getList($online)
    {
        $key = self::_getCacheKeyProduct();

        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $product_list = $cache->get($key);
        $product_list = $product_list ? json_decode($product_list, true) : [];
        if(! $product_list ) {
            $dao_product = new DAOProduct();
            $product_list = $dao_product->getListWithCateid(null);
            if($product_list ) {
                $cache->set($key, json_encode($product_list));
                $cache->expire($key, 3600);
            }
        }
        if($product_list ) {
            foreach($product_list as &$item) {
                $item['usable'] = self::productIsUsable($item, null);
                $item['expired'] = $item['usable'] == 'Y' ? self::MAX_EXPIRE_DATE : 'N';
            }
        }
        if($online && $product_list ) {
            $product_list = array_filter($product_list, __CLASS__ . '::productIsOnline');
        }
        if($product_list) {
            foreach($product_list as &$value){
                $value['image']     = Util::joinStaticDomain($value['image']);
                $value['extends']['zipurl']     = Util::joinStaticDomain($value['extends']['zipurl']);
            }
        }
        return $product_list;
    }

    //合并背包faceu
    public static function mergeUserBag($uid,$cateid,&$productList)
    {
        if ($cateid!=DAOProduct::CATEID_FACEU) { return;
        }
        $bagFaceU=Bag::getUserBagListByCateIds($uid, array(DAOBag::BAG_CATEID_FACEU,));
        if ($bagFaceU) {
            foreach ($bagFaceU as $i){$item['usable'] = "Y";
                $item['expired'] = "Y";
                $p=self::getOne($i['relateid']);
                if ($p) {
                    $p['usable'] = "Y";
                    $p['expired'] = "Y";
                    array_unshift($productList, $p);
                }
            }
        }
    }

    //所有产品
    public static function getAll()
    {
        $cache = Cache::getInstance("REDIS_CONF_PAYMENT");
        $key = self::_getCacheKeyProduct();
        $product_list = $cache->get($key);
        $product_list = $product_list ? json_decode($product_list, true) : [];

        if(! $product_list ) {
            $product_list=self::all();
            $cache->set($key, json_encode($product_list));
            $cache->expire($key, 3600);
        }
        /*if($product_list){
            foreach($product_list as &$value){
                $value['image']     = Util::joinStaticDomain($value['image']);
                $extends             = json_decode($value['extends'],true);
                $extends['zipurl']  = Util::joinStaticDomain($extends['zipurl']);
                $value['extends']   = json_encode($extends);
                unset($extends);
            }
        }*/
        return $product_list;
    }
    //返回美颜，faceu，滤镜
    public static function getBeautyFaceuFilter($cateid=0,$containOffline=false)
    {

        $cateids=[self::CATEID_BEAUTY,self::CATEID_FACEU,self::CATEID_EFFECT];
        if ($cateid) {
            $cateids=[$cateid];
        }

        $filter_products=[];

        $product_list=self::getAll();
        if($product_list ) {
            foreach($product_list as $item) {
                if (!in_array($item['cateid'], $cateids)) { continue;
                }
                if (!$containOffline) {
                    if ($item['online']=="N") { continue;
                    }
                }
                
                $item['usable'] = "Y";
                $item['expired'] = "Y";
                $filter_products[]=$item;
            }
        }

        return $filter_products;
    }
    //返回商城
    public static function getStore($cateids=[])
    {
        if (empty($cateids)) {
            $cateids=[DAOProduct::CATEID_RIDE,DAOProduct::CATEID_BIG_HORN,DAOProduct::CATEID_SMALL_HORN];
        }

        $filterProducts=[];
        $productList=self::getAll();
        if($productList ) {
            foreach($productList as &$item) {
                if (!in_array($item['cateid'], $cateids)) { continue;
                }
                if ($item['online']=="N") { continue;
                }
                if ($item['deleted']=="Y") { continue;
                }

                $item['unit_str']=self::getUnitName($item['unit']);
                if (in_array($item['cateid'], $cateids)) {
                    $item['extends']=@json_decode(self::productExtends($item), true);
                }
                $item['is_expire']=self::getTypeByCateid($item['cateid']);
                $filterProducts[]=$item; 
            }
        }

        return self::storeListProcess($filterProducts);

        /*$version=Context::get('version');
        //$platform=Context::get('platform');
        if (version_compare($version,'2.9.6','>' )){
            return self::storeListProcess($filterProducts);
        }
        $re=array_filter($filterProducts,function($v){
            return $v['cateid']==DAOProduct::CATEID_RIDE;
        });
        return array_values($re);*/

    }

    private static function storeListProcess($products)
    {
        $re=[];
        foreach ($products as $i){
            $re[$i['cateid']][]=$i;
        }
        $rides=is_array($re[DAOProduct::CATEID_RIDE])?$re[DAOProduct::CATEID_RIDE]:[];
        $bigHorns=is_array($re[DAOProduct::CATEID_BIG_HORN])?$re[DAOProduct::CATEID_BIG_HORN]:[];
        $smallHorns=is_array($re[DAOProduct::CATEID_SMALL_HORN])?$re[DAOProduct::CATEID_SMALL_HORN]:[];
        return [
            [
                'name'=>'进场特效',
                'list'=>$rides,
            ],
            [
                'name'=>'系统道具',
                'list'=>array_merge($bigHorns, $smallHorns),
            ]
        ];
    }

    public static function getOne($product_id)
    {
        $product_info=[];
        $all=self::getAll();
        foreach ($all as $i){
            if ($i['productid']==$product_id) {
                $product_info=$i;
                break;
            }
        }
        return $product_info;
    }

    public function listWithCateid($cateid, $online)
    {
        $product_list = self::getList($online);
        if($cateid > 0 && $product_list ) {
            $product_expected = [];
            foreach($product_list as $product_item) {
                if($product_item['cateid'] == $cateid ) {
                    $product_expected[] = $product_item;
                }
            }
            $product_list = $product_expected;
        }

        return $product_list;
    }

    public function listMerge($uid, $cateid, $online)
    {
        $uid = intval($uid);

        $product_list = $this->listWithCateid($cateid, false);
        $index_product_id = [];
        foreach($product_list as $key => &$product_item) {
            $product_item['usable'] = self::productIsUsable($product_item, null);
            $index_product_id[ $product_item['productid'] ] = $key;
        }

        $dao_bag = new DAOBag();
        $list = $dao_bag->getListByUid($uid);
        if($list ) {
            $bag_product_expire = [];
            foreach($list as $bag_info) {
                if(! array_key_exists($bag_info['productid'], $bag_product_expire) ) {
                    $bag_product_expire[ $bag_info['productid'] ] = [];
                }
                $bag_product_expire[ $bag_info['productid'] ][] = $bag_info;
            }
            $date_time_now = date('Y-m-d H:i:s');
            foreach($bag_product_expire as $productid => $group_list) {
                $product_info = array_key_exists($productid, $index_product_id)?$product_list[ $index_product_id[$productid] ]:null;
                if(! $product_info || $product_info['usable']=='Y' ) { continue;
                }
                if(($final_expired=self::bagFinalExpiredByMultiRecord($group_list)) && $final_expired > $date_time_now ) {
                    $product_list[ $index_product_id[$productid] ]['usable'] = 'Y';
                    $product_list[ $index_product_id[$productid] ]['expired'] = $final_expired;
                }
            }
        }

        if($online ) {
            $product_expected = [];
            foreach($product_list as $product_one) {
                if($product_one['online'] == 'Y' || $product_one['usable']=='Y' ) {
                    $product_expected[] = $product_one;
                }
            }
            $product_list = $product_expected;
        }
        if($product_list) {
            foreach($product_list as &$value){
                $value['image']     = Util::joinStaticDomain($value['image']);
                $extends             = json_decode($value['extends'], true);
                $extends['zipurl']  = Util::joinStaticDomain($extends['zipurl']);
                $value['extends']   = json_encode($extends);
                unset($extends);
            }
        }
        return $product_list;
    }

    public function used($uid, $product_id)
    {
        $product_info = self::getOne($product_id);
        Interceptor::ensureNotEmpty($product_info, ERROR_BIZ_PAYMENT_PRODUCT_NOT_FOUND);
        return $product_info;
    }

    public function addProduct($name,$image,$cateid,$type,$tag,$price,$expire,$unit,$currency,$online,$deleted,$mark,$weight,$remark,$extends=array())
    {
        $unit=self::getFaceuBeautyUnit($cateid, $unit);
        $product_dao=new DAOProduct();
        $re=$product_dao->addProduct($name, $image, $cateid, $type, $tag, $price, $expire, $unit, $currency, $online, $deleted, $mark, $weight, $remark, $extends);
        self::_reloadCache();
        return $re;
    }

    public function modifyProduct($productid,$name,$image,$cateid,$type,$tag,$price,$expire,$unit,$currency,$online,$deleted,$mark,$weight,$remark,$extends=array())
    {
        $unit=self::getFaceuBeautyUnit($cateid, $unit);
        $product_dao=new DAOProduct();
        $re=$product_dao->modifyProduct($productid, $name, $image, $cateid, $type, $tag, $price, $expire, $unit, $currency, $online, $deleted, $mark, $weight, $remark, $extends);
        self::_reloadCache();
        return $re;
    }

    private static function getFaceuBeautyUnit($cateid,$unit='')
    {
        $unitAutoSet=array(DAOProduct::CATEID_FACEU,DAOProduct::CATEID_BEAUTY,DAOProduct::CATEID_EFFECT);
        if(!$unit&&in_array($cateid, $unitAutoSet)) {
            $unit=DAOProduct::UNIT_SECOND;
        }
        return $unit;
    }

    //送大小喇叭
    public function sendHornByActive($toUid,$hornid,$num,$notice='',array  $ext=array())
    {
        return $this->sendHorn($toUid, $hornid, $num, DAOOrderLog::SOURCE_ACTIVE, $notice, $ext);
    }

    public function sendHornByLotto($toUid,$hornid,$num,$notice='',$ext=array())
    {
        return $this->sendHorn($toUid, $hornid, $num, DAOOrderLog::SOURCE_LOTTO, $notice, $ext);
    }

    private function sendHorn($toUid,$hornid,$num,$source,$notice='',$ext=array())
    {
        return $this->sendProduct($toUid, $hornid, $source, $num, 0, $notice, $ext);
    }


    //送座驾:活动送，任务送，vip送
    private function sendRide($toUid,$rideid,$inExpire,$source,$notice='',array $ext=array())
    {
        return $this->sendProduct($toUid, $rideid, $source, 1, $inExpire, $notice, $ext);
    }

    public function sendRideByLotto($toUid,$rideid,$expire,$notice='',array $ext=array())
    {
        return $this->sendRide($toUid, $rideid, $expire, DAOOrderLog::SOURCE_LOTTO, $notice, $ext);
    }

    public function sendRideByVip($toUid,$rideid,$expire,$notice='',array $ext=array())
    {
        return $this->sendRide($toUid, $rideid, $expire, DAOOrderLog::SOURCE_VIP, $notice, $ext);
    }

    public function sendRideByTask($toUid, $rideid,$expire,$notice='',array $ext=array())
    {
        return $this->sendRide($toUid, $rideid, $expire, DAOOrderLog::SOURCE_TASK, $notice, $ext);
    }

    public function sendRideByActive($toUid, $rideid,$expire,$notice='',array $ext=array())
    {
        return $this->sendRide($toUid, $rideid, $expire, DAOOrderLog::SOURCE_ACTIVE, $notice, $ext);
    }

    private function sendAnchorToken($uid,$tokenid,$source,$expire,$notice='',array $ext=array())
    {
        return $this->sendProduct($uid, $tokenid, $source, 1, $expire, $notice, $ext);
    }

    public function sendAnchorTokenByActive($uid,$tokenid,$expire,$notice='',array $ext=array())
    {
        return $this->sendAnchorToken($uid, $tokenid, DAOOrderLog::SOURCE_ACTIVE, $expire, $notice, $ext);
    }
    private function sendProduct($toUid,$productid,$source,$num=1,$inExpire=0,$notice='',array $ext=array())
    {
        $daoProduct=new DAOProduct();
        $product_info =$daoProduct->getOneByProductId($productid);
        Interceptor::ensureNotEmpty($product_info, ERROR_BIZ_PAYMENT_PRODUCT_NOT_FOUND);

        $orderid = Account::getOrderId();
        $dao_order_log=new DAOOrderLog();
        try {
            $dao_order_log->startTrans();

            $dao_order_log->add($productid, $orderid, $toUid, $product_info['cateid'], $product_info['price'], $product_info['currency'], $num, $product_info['price'], 'Y', $source, $ext);
            if ($product_info['cateid']==DAOProduct::CATEID_RIDE) {
                Bag::putRide($toUid, $productid, $inExpire, $notice);
            }elseif ($product_info['cateid']==DAOProduct::CATEID_BIG_HORN||$product_info['cateid']==DAOProduct::CATEID_SMALL_HORN) {
                Bag::putHorn($toUid, $productid, $num, $notice);
            }elseif ($product_info['cateid']==DAOProduct::CATEID_ANCHOR_LEVEL_TOKEN) {
                Bag::putAnchorToken($toUid, $productid, $inExpire, $notice);
            }

            $dao_order_log->commit();
        } catch (Exception $e) {
            $dao_order_log->rollback();
            throw $e;
        }

        return $orderid;
    }


    public function buy($uid, $productid, $num, $activeid=0, $type=1, $inbag=false)
    {
        $productInfo = self::getOne($productid);
        Interceptor::ensureNotEmpty($productInfo, ERROR_BIZ_PAYMENT_PRODUCT_NOT_FOUND);

        //可购买产品
        $canBuy=array(DAOProduct::CATEID_RIDE,DAOProduct::CATEID_BIG_HORN,DAOProduct::CATEID_SMALL_HORN);
        Interceptor::ensureNotFalse(in_array($productInfo['cateid'], $canBuy), ERROR_BIZ_PAYMENT_PRODUCT_NOT_FOUND, '不可购买');

        //判断是否放入背包
        $inBagCateid=array(DAOBag::BAG_CATEID_RIDE,DAOBag::BAG_CATEID_BIG_HORN,DAOBag::BAG_CATEID_SMALL_HORN);
        if (in_array($productInfo['cateid'], $inBagCateid)) {
            $inbag=true;
        }

        //有效期型产品$num设置为1
        if (DAOBag::getTypeByCateid($productInfo['cateid'])==DAOBag::BAG_TYPE_EXP) {
            $num=1;
        }

        //获取sku
        $sku=self::getSku($activeid, $productInfo);

        //数量型产品，根据活动价修正sku
        if (DAOBag::getTypeByCateid($productInfo['cateid'])==DAOBag::BAG_TYPE_NUM) {
            if ($sku) {
                $num=isset($sku['num'])&&$sku['num']?$sku['num']:$num;
            }
        }

        $account = new Account();
        $currency = intval($productInfo['currency']);
        $num = abs(intval($num));
        $amount = $productInfo['price'] * $num;

        //活动产品金额修正
        $active_cateid=array(DAOBag::BAG_CATEID_RIDE,DAOBag::BAG_CATEID_BIG_HORN,DAOBag::BAG_CATEID_SMALL_HORN);
        if (in_array($productInfo['cateid'], $active_cateid)&&$sku) {
            $amount=isset($sku['price'])?$sku['price']:$productInfo['price'] * $num;
        }
        
        $balance = $account->getBalance($uid, $currency, true);
        Interceptor::ensureNotEmpty($balance, ERROR_BIZ_PAYMENT_ACCOUNT_BALANCE_LACK);
        Interceptor::ensureNotFalse($balance >= $amount, ERROR_BIZ_PAYMENT_ACCOUNT_BALANCE_LACK);

        $orderid = Account::getOrderId();
        $receiver = Account::ACTIVE_ACCOUNT8000;

        $daoOrder = new DAOOrder($uid);
        try {
            $daoOrder->startTrans();

            Interceptor::ensureNotFalse($account->decrease($uid, ACCOUNT::TRADE_TYPE_PRODUCT_ORDER, $orderid, $amount, $currency, "购买产品:{$productid},单价:{$productInfo['price']},数量:{$num},总价:{$amount},收款方:{$receiver},折扣：｛$activeid｝", array()), ERROR_BIZ_PAYMENT_TRADE_ACCOUNT_USER);
            Interceptor::ensureNotFalse($account->increase($receiver, ACCOUNT::TRADE_TYPE_PRODUCT_ORDER, $orderid, $amount, $currency, "用户:{$uid}购买产品:{$productid},单价:{$productInfo['price']},数量:{$num},总价:{$amount},折扣：｛$activeid｝", array()), ERROR_BIZ_PAYMENT_TRADE_ACCOUNT_SYSTEM);

            $daoOrder->add($productid, $orderid, $uid, $type, $productInfo['price'], $currency, $num, $amount, 'Y', json_encode($sku));
            $daoOrderLog=new DAOOrderLog();
            $daoOrderLog->add($productid, $orderid, $uid, $productInfo['cateid'], $productInfo['price'], $currency, $num, $amount, 'Y');
            
            if ($inbag) {
                $expire=$productInfo['expire'];
                if ($sku) {
                    if (DAOBag::getTypeByCateid($productInfo['cateid'])==DAOBag::BAG_TYPE_EXP) {
                        $expire=isset($sku['expire'])&&$sku['expire']?$sku['expire']:$productInfo['expire'];
                    }
                }

                //$daoBag = new DAOBag();
                //$daoBag->add($uid,$productInfo['cateid'] ,$productid, $expire,DAOBag::BAG_SATTUS_OFF,$num,DAOBag::BAG_SOURCE_STORE);
                Bag::put($uid, $productInfo['cateid'], $productid, $expire, $num, DAOBag::BAG_SOURCE_STORE, '', DAOBag::BAG_SATTUS_OFF);
            }

            $daoOrder->commit();
        } catch (Exception $e) {
            $daoOrder->rollback();
            throw $e;
        }

        return $orderid;
    }

    private function getSku($activeid,$productInfo)
    {
        $sku=null;
        if ($activeid) {
            $ext=@json_decode($productInfo['extends'], true);
            if ($ext) {
                $active=isset($ext['active'])?$ext['active']:[];
                foreach ($active as $i){
                    if ($i['id']==$activeid) {
                        $sku=$i;
                        break;
                    }
                }
            }
        }
        return $sku;
    }

    public static function getUnitName($unit='')
    {
        $unitMap=[
            DAOProduct::UNIT_SECOND=>'秒',
            DAOProduct::UNIT_DAY=>'天',
            DAOProduct::UNIT_MONTH=>'月',
            DAOProduct::UNIT_YEAR=>'年',
            DAOProduct::UNIT_NUM=>'个',
        ];
        if ($unit) {
            return isset($unitMap[$unit])?$unitMap[$unit]:"个";
        }
        return $unitMap;
    }

    public static function productExtends($product)
    {
        $extends=$product['extends'];
        $ext=@json_decode($extends, true);
        /*if ($product['cateid']==DAOProduct::CATEID_RIDE){
            $ext['isDynamic']=isset($ext['isDynamic'])&&$ext['isDynamic']?Resource::SOURCE_TYPE_DYNAMIC:Resource::SOURCE_TYPE_STATIC;
        }else{
            $ext['isDynamic']=Resource::SOURCE_TYPE_STATIC;
        }*/
        if ($ext&&isset($ext['active'])) {

            foreach ($ext['active'] as &$i){
                if ($product['unit']==DAOProduct::UNIT_DAY) {
                    $i['expire_str']=round($i['expire']/(24*60*60));
                }elseif ($product['unit']==DAOProduct::UNIT_MONTH) {
                    $i['expire_str']=round($i['expire']/(30*24*60*60));
                }elseif ($product['unit']==DAOProduct::UNIT_YEAR) {
                    $i['expire_str']=round($i['expire']/(365*24*60*60));
                }elseif ($product['unit']==DAOProduct::UNIT_SECOND) {
                    $i['expire_str']=$i['expire'];
                }elseif($product['unit']==DAOProduct::UNIT_NUM) {
                    $i['expire_str']=0;
                }

                $type=DAOBag::getTypeByCateid($product['cateid']);
                $unitName=self::getUnitName($product['unit']);
                if ($type==DAOBag::BAG_TYPE_EXP) {
                    $i['org_price']=intval($i['expire_str'])*$product['price'];
                    $i['name']=$i['expire_str'].$unitName;
                }elseif ($type==DAOBag::BAG_TYPE_NUM) {
                    $i['org_price']=intval($i['num'])*$product['price'];
                    $i['name']=$i['num'].$unitName;
                }
            }

        }else{$ext['active']=[];
        }


        return json_encode($ext);
    }

    public  static  function getTypeByCateid($cateid)
    {
        $type=DAOProduct::PRODUCT_TYPE_NUM;
        switch ($cateid){
        case DAOProduct::CATEID_RIDE:
        case DAOProduct::CATEID_BEAUTY:
        case DAOProduct::CATEID_EFFECT:
        case DAOProduct::CATEID_FACEU:
        case DAOProduct::CATEID_ANCHOR_LEVEL_TOKEN:
            $type=DAOProduct::PRODUCT_TYPE_EXPIRE;
            break;
        case DAOProduct::CATEID_BIG_HORN:
        case DAOProduct::CATEID_SMALL_HORN:
        case DAOProduct::CATEID_FREE_LOTTO_TICKET:
            $type=DAOProduct::PRODUCT_TYPE_NUM;
            break;

        }
        return $type;
    }

    private static function isExpire($cateid)
    {
        if (self::getTypeByCateid($cateid)==DAOProduct::PRODUCT_TYPE_EXPIRE) { return true;
        }
        return false;
    }

    public static function getOnlyOneProductByCateid($cateid)
    {
        $daoProduct=new DAOProduct();
        return $daoProduct->getOnlyOneByCateid($cateid);
    }

    public static function all()
    {
        $dao_product = new DAOProduct();
        $product_list = $dao_product->getProductByCateids([]);

        $expect_list=[];
        foreach ($product_list as $i){
            if ($i['deleted']==DAOProduct::DELETED_YES) { continue;
            }

            $i['image']     = Util::joinStaticDomain($i['image']);
            $extends             = @json_decode($i['extends'], true);
            $i['isDynamic']=isset($ext['isDynamic'])&&$ext['isDynamic']?Resource::SOURCE_TYPE_DYNAMIC:Resource::SOURCE_TYPE_STATIC;
            if (isset($extends['zipurl'])) {
                $extends['zipurl']  = Util::joinStaticDomain($extends['zipurl']);
                $i['extends']   = json_encode($extends);
            }

            $expect_list[]=$i;
        }
        return $expect_list;
    }
}
