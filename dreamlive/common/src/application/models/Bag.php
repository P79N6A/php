<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/26
 * Time: 15:58
 */
class Bag
{
    const BAG_LIST_BY_UID="bag:list:";
    const BAG_USER_ITEMS="bag:user:items:";
    const LOGIN_SEND_GIFTID = 3860;

    //暂时保留
    public static function reloadBagList($uid)
    {
        /*$cache = Cache::getInstance("REDIS_CONF_CACHE");
        $bagList=self::bagList($uid,0 ,false,true);
        if(empty($bagList))$bagList=[];
        $cache->set(self::BAG_LIST_BY_UID.$uid,json_encode($bagList));*/
    }

    //使用礼物
    public static function useBagGift($bagid,$sender,$receiver,$giftid,$num,$liveid,$doublehit, $giftUniTag)
    {
        //$pay_account=Account::ACTIVE_ACCOUNT7000;
        $gift=new BagGift();
        $ret=$gift->sendGiftProcess($sender, $receiver, $giftid, $num, $liveid, $doublehit, $giftUniTag, true);

        /*$bag_dao=new DAOBag();
        $bag_gift=$bag_dao->getItemById($bagid);*/
        $bag_gift=self::getItemById($sender, $bagid);
        if ($bag_gift) {
            $ret['num_left']=$bag_gift['num'];
        }
        return $ret;
    }

    public static function useHorn($uid,$hornType)
    {
        $num=-1;//代表余额为零
        $re=self::getUserHorn($uid);
        if ($hornType==DAOBag::BAG_CATEID_BIG_HORN) {
            if (isset($re['big_horn'])&&$re['big_horn']['num']>0) {
                self::itemOfNum($re['big_horn']['id'], 1);
                $num=$re['big_horn']['num']-1;
            }
        }elseif ($hornType==DAOBag::BAG_CATEID_SMALL_HORN) {
            if (isset($re['small_horn'])&&$re['small_horn']['num']>0) {
                self::itemOfNum($re['small_horn']['id'], 1);
                $num=$re['small_horn']['num']-1;
            }
        }
        return $num;
    }

    public static function useFreeLottoTicket($uid,$num=1)
    {
        $daoBag=new DAOBag();
        $ticket=$daoBag->getUserFreeLottoTicket($uid);
        if (!$ticket) { return ;
        }
        self::itemOfNum($ticket['id'], $num);
    }

    public static function processHornResult($num)
    {
        $re=0;
        if ($num>99999999) {
            $re=-2;
        }elseif($num==0) {
            $re=-1;
        }else{
            $re=$num;
        }
        return $re;
    }

    //没用，暂时保留
    public static function useBagHorn($bagid,$liveid,$content)
    {
        $num=1;
        self::itemOfNum($bagid, $num);
        //$daoBag=new DAOBag();
        //$item=$daoBag->getItemById($bagid);
        /*if (!$item)return;
        $uid=$item['uid'];

        if ($item['cateid']==DAOBag::BAG_CATEID_BIG_HORN){
            MyHorn::send($uid,$content ,$liveid , MyHorn::TYPE_BIG_HORN);
        }elseif ($item['cateid']==DAOBag::BAG_CATEID_SMALL_HORN){
            MyHorn::send($uid, $content, $liveid, MyHorn::TYPE_SMALL_HORN);
        }else{
            return;
        }*/
        //return array('num'=>$item['num']);
    }

    public static function getUserBagListByCateIds($uid,array $cateids=array())
    {
        $re=[];
        $t=self::getValidUserBag($uid);
        foreach ($t as $i){
            if (in_array($i['cateid'], $cateids)) {
                $re[]=$i;
            }
        }
        return $re;
        /*$bagDao=new DAOBag();
        $bag=$bagDao->getUserBagByCateids($uid,$cateids);
        $re=[];
        foreach ($bag as $i){
            if ($i['type']==DAOBag::BAG_TYPE_EXP){
                if (strtotime($i['expiretime'])<=time())continue;
            }
            $re[]=$i;
        }
        return $re;*/
    }

    public static function bagList($uid,$cateid,$contain_expire=false,$update=false)
    {
        /*if ($uid>0 && $cateid==0 && $contain_expire==false &&$update==false){
            $cache = Cache::getInstance("REDIS_CONF_CACHE");
            $bagList=$cache->get(self::BAG_LIST_BY_UID.$uid);
            $bagList=@json_decode($bagList,true);
            if($bagList){
                foreach($bagList as &$value){
                    $value['image']     = Util::joinStaticDomain($value['image']);
                }
            }
            if (is_array($bagList)){
                return $bagList;
            }
        }*/

        $bagDao=new DAOBag();
        $bag=$bagDao->getUserBagByCateid($uid, $cateid);

        $product=new Product();
        $gift=new Gift();
        $allow_cateid=[DAOBag::BAG_CATEID_BIG_HORN,DAOBag::BAG_CATEID_GIFT,DAOBag::BAG_CATEID_SMALL_HORN];//只显示礼物和喇叭

        $bag_list=[];
        foreach ($bag as $i){
            if(!$cateid) {
                if (!in_array($i['cateid'], $allow_cateid)) { continue;
                }
            }

            if ($i['type']==DAOBag::BAG_TYPE_NUM) {
                if($i['num']<=0) { continue;
                }
                $i['time_str']='9999d';
            }elseif ($i['type']==DAOBag::BAG_TYPE_EXP) {
                if ($contain_expire==false) {
                    if (strtotime($i['expiretime'])<=time()) { continue;
                    }
                }
                $exp_left=strtotime($i['expiretime'])-time();
                if($exp_left>=3600*24) {
                    $i['time_str']=floor($exp_left/(3600*24)).'d';
                }elseif($exp_left>=3600) {
                    $i['time_str']=floor($exp_left/3600).'h';
                }elseif ($exp_left>=60) {
                    $i['time_str']=floor($exp_left/60).'m';
                }elseif($exp_left>=0) {
                    $i['time_str']=$exp_left.'s';
                }else{
                    $i['time_str']='';//过期
                }

            }

            $i['bagid']=$i['id'];
            $i["itype"]=$i["type"];
            $i["itag"]=$i["tag"];
            $i['is_msend']=self::isSendMulti($i['cateid']);

            if ($i['source']==DAOBag::BAG_SOURCE_GIFT) {
                $giftInfo=$gift->getGiftInfo($i['relateid'], true);
                if (empty($giftInfo)) { continue;
                }

                $i['name']=!empty($giftInfo['name'])?$giftInfo['name']:"";
                $i["image"]=!empty($giftInfo['image'])?$giftInfo['image']:"";
                $i["type"]=!empty($giftInfo['type'])?$giftInfo['type']:"";
                $i["price"]=!empty($giftInfo['price'])?$giftInfo['price']:"";
                $i["consume"]=!empty($giftInfo['consume'])?$giftInfo['consume']:"DIAMOND";
                $i["ticket"]=!empty($giftInfo['ticket'])?$giftInfo['ticket']:0;
                $i['extends']=null;
            }elseif ($i['source']==DAOBag::BAG_SOURCE_STORE) {
                $productInfo=$product->getOne($i['relateid']);
                if (empty($productInfo)) { continue;
                }

                $i['image']=!empty($productInfo['image'])?$productInfo['image']:"";
                $i['name']=!empty($productInfo['name'])?$productInfo['name']:"";
                $i['price']=!empty($productInfo['price'])?$productInfo['price']:0;
                $i['online']=!empty($productInfo['online'])?$productInfo['online']:"N";
                
                $i['remark']=!empty($productInfo['remark'])?$productInfo['remark']:"";
                $i['extends']=null;
                $i['unit_str']=Product::getUnitName($productInfo['unit']);
                if (!empty($productInfo['extends'])) {
                    $ext=Product::productExtends($productInfo);
                    $i['extends']=@json_decode($ext, true);
                }
            }

            $bag_list[]=$i;
        }
        if($bag_list) {
            foreach($bag_list as &$value){
                $value['image']     = Util::joinStaticDomain($value['image']);
            }
        }
        return $bag_list;
    }

    private static function isSendMulti($cateid)
    {
        $ms_cateids=array(DAOBag::BAG_CATEID_BIG_HORN,DAOBag::BAG_CATEID_SMALL_HORN);
        if (in_array($cateid, $ms_cateids)) { return "N";
        }
        return 'Y';
    }
    //
    public static function itemOfExpire($bagid,$status="OFF")
    {
        $bag_dao=new DAOBag();
        $item=$bag_dao->getItemById($bagid);
        Interceptor::ensureNotEmpty($item, ERROR_PARAM_DATA_NOT_EXIST, 'item is null');
        if ($status==$item['status']) { return true;
        }
        if ($status=='ON') {
            Interceptor::ensureNotFalse(strtotime($item['expiretime'])>time(), ERROR_BIZ_BAG_USE_RIDE_OUT_DATE, 'out of expire');
        }
        $bag_dao->startTrans();
        try{
            if ($status=='ON') {
                $rides=$bag_dao->getBagByStatus($item['uid'], $item['cateid'], "ON");
                foreach ($rides as $i){
                    $res=$bag_dao->updateStatusById($i['id'], 'OFF');
                    if (!$res) { throw new Exception('update status fail');
                    }
                }
            }

            $res=$bag_dao->updateStatusById($bagid, $status);
            if (!$res) { throw new Exception('update status fail');
            }
            $bag_use_dao=new DAOBagUsed();
            $bag_use_dao->add($item['uid'], $item['relateid'], $bagid, 0, ['old_status'=>$item['status'],'new_status'=>$status]);
            $bag_dao->commit();
            //self::reloadBagList($item['uid']);
            self::reloadUserBag($item['uid']);

            if ($item['cateid']==DAOBag::BAG_CATEID_ANCHOR_LEVEL_TOKEN) {
                self::putAnchorTokenInMedal($item['uid']);
            }elseif ($item['cateid']==DAOBag::BAG_CATEID_RIDE) {
                self::putRideInMedal($item['uid']);
            }
        }catch (Exception $e){
            $bag_dao->rollback();
            throw $e;
            return false;
        }
    }

    public static function getRideByUid($uid)
    {
        $re=self::getValidUserBag($uid);
        foreach ($re as $i){
            if ($i['cateid']==DAOBag::BAG_CATEID_RIDE&&$i['status']==DAOBag::BAG_STATUS_ON) {
                $product=new Product();
                $info=$product->getOne($i['relateid']);
                if ($info) {
                    if ($info['extends']) {
                        $ext=@json_decode($info['extends'], true);
                        if ($ext) {
                            $url=isset($ext['zipurl'])?$ext['zipurl']:$info['image'];
                            return Util::joinStaticDomain($url);
                        }
                    }
                }
                break;
            }
        }
        return '';

        /* $bag_dao=new DAOBag();
        $ride=$bag_dao->getUsedRide($uid);
        if ($ride){
            $product=new Product();
            $info=$product->getOne($ride['relateid']);
            if ($info){
                if ($info['extends']){
                    $ext=@json_decode($info['extends'],true);
                    if ($ext){
                        $url=isset($ext['zipurl'])?$ext['zipurl']:$info['image'];
                        return Util::joinStaticDomain($url);
                    }
                }
            }
        }*/
        return "";
    }

    public static function consumeGift()
    {
        $bagid=isset($_REQUEST['bagid'])?$_REQUEST['bagid']:0;
        $num=isset($_REQUEST['num'])?$_REQUEST['num']:0;
        if ($bagid) {
            self::itemOfNum($bagid, $num, false);
        }
    }
    public static function itemOfNum($bagid,$num=1,$trans=true)
    {
        $bag_dao=new DAOBag();
        if ($trans) {
            $bag_dao->startTrans();
        }
        try{
            $item=$bag_dao->getItemById($bagid);
            Interceptor::ensureNotEmpty($item, ERROR_PARAM_DATA_NOT_EXIST, 'item is null');
            Interceptor::ensureNotFalse($item['num']>0, ERROR_BIZ_BAG_USE_GIFT_NUM_LESS, 'num is not enough');
            Interceptor::ensureNotFalse($item['num']>=$num, ERROR_BIZ_BAG_USE_GIFT_NUM_LESS, 'num is not enough');

            $bag_dao->updateBagNum($bagid, $num);

            $bag_use_dao=new DAOBagUsed();
            $bag_use_dao->add($item['uid'], $item['relateid'], $bagid, $num);

            if ($trans) {
                $bag_dao->commit();
            }
            //self::reloadBagList($item['uid']);
            self::reloadUserBag($item['uid']);
        }catch (Exception $e){
            if($trans) {
                $bag_dao->rollback();
            }
            if (!$trans) {
                throw $e;
            }
            return false;
        }
        
        return true;
    }

    public static function putAnchorToken($uid,$tokenid,$expire,$notice='')
    {
        $product=new Product();
        $product_info=$product->getOne($tokenid);
        if (empty($product_info)) { throw new Exception("product not exist");
        }
        if ($product_info['cateid']!=DAOProduct::CATEID_ANCHOR_LEVEL_TOKEN) { throw new Exception("not anchor token");
        }
        self::put($uid, DAOBag::BAG_CATEID_ANCHOR_LEVEL_TOKEN, $tokenid, $expire, 1, DAOBag::BAG_SOURCE_STORE, $notice);
        self::putAnchorTokenInMedal($uid);
    }
    public static function putFreeLottoTicket($uid,$ticketid,$num=1,$notice='')
    {
        if ($num<=0) { return;
        }
        $daoProduct=new DAOProduct();
        $info=$daoProduct->getOneByProductId($ticketid);
        if (!$info) { return;
        }
        return self::put($uid, $info['cateid'], $ticketid, 0, $num, DAOBag::BAG_SOURCE_STORE, $notice);
    }
    public static function putHorn($uid,$hornid,$num,$notice='')
    {
        if ($num<=0) { return;
        }
        $daoProduct=new DAOProduct();
        $info=$daoProduct->getOneByProductId($hornid);
        if (!$info) { return;
        }
        return self::put($uid, $info['cateid'], $hornid, 0, $num, DAOBag::BAG_SOURCE_STORE, $notice);
    }

    public static function putGift($uid,$giftid,$num,$notice='')
    {
        return self::put($uid, DAOBag::BAG_CATEID_GIFT, $giftid, 0, $num, DAOBag::BAG_SOURCE_GIFT, $notice);
    }
    public static function putRide($uid,$rideid,$expire,$notice='')
    {
        $product=new Product();
        $product_info=$product->getOne($rideid);
        if (empty($product_info)) { throw new Exception("product not exist");
        }
        if ($product_info['cateid']!=DAOProduct::CATEID_RIDE) { throw new Exception("not ride");
        }
        self::put($uid, DAOBag::BAG_CATEID_RIDE, $rideid, $expire, 1, DAOBag::BAG_SOURCE_STORE, $notice);
        self::putRideInMedal($uid);
    }

    public static function putFaceu($uid,$faceuid,$expire,$notice='')
    {
        $product=new Product();
        $product_info=$product->getOne($faceuid);
        if (empty($product_info)) { throw new Exception("product not exist");
        }
        if ($product_info['cateid']!=DAOProduct::CATEID_FACEU) { throw new Exception("not faceu");
        }
        return self::put($uid, DAOBag::BAG_CATEID_FACEU, $faceuid, $expire, 1, DAOBag::BAG_SOURCE_STORE, $notice);
    }

    public static function putProduct($uid,$cateid,$productid,$expire,$num,$notice='')
    {
        return self::put($uid, $cateid, $productid, $expire, $num, DAOBag::BAG_SOURCE_STORE, $notice);
    }

    public static function put($uid,$cateid,$relateid,$expire,$num,$source,$notice='',$status='OFF')
    {
        if (!in_array($cateid, DAOBag::getCateids())) { throw new Exception('cateid out of range');
        }
        //验证礼物和商城物品，不是不添加,只添加背包礼物
        if (DAOBag::BAG_SOURCE_STORE==$source) {
            $product=new Product();
            $product_info=$product->getOne($relateid);
            if (empty($product_info)) { throw new  Exception("not product");
            }
        }
        if (DAOBag::BAG_SOURCE_GIFT==$source) {
            $gift=new Gift();
            $gift_info=$gift->getGiftInfo($relateid, true);
            if (empty($gift_info)) { throw new Exception("gift not exist");
            }
            // if ($gift_info['tag']!=DAOGift::GIFT_TAG_BAG)throw new Exception("not bag gift");
        }
        $dao_bag = new DAOBag();
        $dao_bag->add($uid, $cateid, $relateid, $expire, $status, $num, $source);
        //self::reloadBagList($uid);
        //清理一下
        //self::clearUserBag($uid);
        self::reloadUserBag($uid);
        //todo加入放入背包的记录

        if ($notice) {
            $content=$notice;
            Messenger::sendCollectLog(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $uid, '系统消息', $content, 0);
        }

    }

    public static function getUserHorn($uid)
    {
        $r=[];
        $re=self::getValidUserBag($uid);
        foreach ($re as $i){
            if ($i['cateid']==DAOBag::BAG_CATEID_BIG_HORN) {
                $r['big_horn']=$i;
            }elseif($i['cateid']==DAOBag::BAG_CATEID_SMALL_HORN) {
                $r['small_horn']=$i;
            }
        }
        /*
        $re=[];
        $daoBag=new DAOBag();
        $horns=$daoBag->getUserHorn($uid);
        $horns=empty($horns)?array():$horns;
        foreach ($horns as $i){
            if ($i['cateid']==DAOBag::BAG_CATEID_BIG_HORN){
                $re['big_horn']=$i;
            }elseif($i['cateid']==DAOBag::BAG_CATEID_SMALL_HORN){
                $re['small_horn']=$i;
            }
        }*/
        if (empty($r['big_horn'])) { $r['big_horn']=0;
        }
        if (empty($r['small_horn'])) { $r['small_horn']=0;
        }
        return $r;
    }

    public static function getAllHornNum($uid)
    {
        $re=self::getUserHorn($uid);
        $bigNum=isset($re['big_horn']['num'])?$re['big_horn']['num']:0;
        $smallNum=isset($re['small_horn']['num'])?$re['small_horn']['num']:0;

        $vipBig=Vip::getLeftNumber($uid, Vip::TYPE_HORN_NUM);//
        $bigNum=self::vipHornTransform($vipBig, $bigNum);

        $vipSmall=Vip::getLeftNumber($uid, Vip::TYPE_PROP_NUM);
        $smallNum=self::vipHornTransform($vipSmall, $smallNum);

        return array($bigNum,$smallNum);
    }

    //登录送背包礼物
    public static function loginSendGift($uid, $gift)
    {
        $login_gift     = new DAOLoginGiftLog();
        $now_date       = date("Y-m-d");
        $stime          = $now_date." 00:00:00";
        $etime          = $now_date." 23:59:59";
        //$log_num        = $login_gift -> isSendLog($uid, $stime, $etime);
        //if($log_num>0)return true;
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $key   = "login_send_gift_".$uid;
        $key1  = "login_send_gift_".$uid.$now_date;
        if(Counter::increase('login_send', $key1)>1) {
            return true;
        }

        $addtime    = $cache -> get($key);
        if(!$addtime) {
            //获取注册时间
            $dao_user = new DAOUser();
            $userinfo = $dao_user->getUserInfo($uid);
            $cache -> set($key, $userinfo['addtime']);
            $addtime  = $userinfo['addtime'];
        }
        $regin_long = strtotime($addtime) - strtotime(date("Y-m-d", strtotime($addtime))." 00:00:00");
        $date       = time()-strtotime($addtime)+$regin_long;
        if(is_int($date/3600/24)) {
            $num        = ceil($date/3600/24)+1;
        }else{
            $num        = ceil($date/3600/24);
        }
        if($num>3) { return true;
        }
        //$cache -> set($key1,1);
        $str = '欢迎来到追梦大家庭，特别赠送你5个'.$gift[1]['name'].'哦，送给你喜欢的主播吧～ 新用户登陆第2天送10个'.$gift[2]['name'].'、第3天送15个'.$gift[3]['name'].'。领取方式：点开直播间最下面中间的礼物按钮然后点左下角的背包按钮领取并送出。';
        self::putGift($uid, $gift[$num]['giftid'], $gift[$num]['num'], $str);

        $login_gift->addLoginGift($uid, $gift[$num]['giftid'], $gift[$num]['num'], $addtime);
        return true;
    }
    public static function vipHornTransform($vipNum,$bagNum)
    {
        $num=$bagNum;
        if ($vipNum>99999999) {
            $num=-2;
        }elseif($vipNum==-1) {
             $num=$bagNum==0?-1:$num;
            //$smallNum+=$vipSmall;
        }else{
            $num+=$vipNum;
        }
        return $num;
    }

    public static function hasFreeLottoTicket($uid)
    {
        $re=self::getValidUserBag($uid);
        foreach ($re as $i){
            if ($i['cateid']==DAOBag::BAG_CATEID_FREE_LOTTO_TICKET&&$i['num']>0) {
                return true;
            }
        }
        return false;
        /*$daoBag=new DAOBag();
        $re=$daoBag->getUserFreeLottoTicket($uid);
        if ($re)return true;
        return false;*/
    }

    //是否有某个座驾
    public static function hasTheRide($uid,$relateid)
    {
        $re=self::getValidUserBag($uid);
        foreach ($re as $i){
            if ($i['cateid']==DAOBag::BAG_CATEID_RIDE&&$i['relateid']==$relateid) {
                return true;
            }
        }
        return false;
        /* $daoBag=new DAOBag();
        $re=$daoBag->getUsedRideByRelateid($uid,$relateid);
        if ($re)return true;
        return false;*/
    }

    //获取背包所有物品
    public static function getUserBag($uid)
    {
        $re=array();
        if (!$uid) { return $re;
        }

        $cache = Cache::getInstance("REDIS_CONF_PAYMENT");
        $key=self::BAG_USER_ITEMS.$uid;
        $d=$cache->get($key);
        $re=$d?json_decode($d, true):[];
        if (empty($re)) {
            $re=self::reloadUserBag($uid);
        }
        return $re;
    }

    //重置用户背包缓存
    public static function reloadUserBag($uid)
    {
        try{
            $cache = Cache::getInstance("REDIS_CONF_PAYMENT");
            $key=self::BAG_USER_ITEMS.$uid;

            $re=array();
            if ($uid) {
                $bagDao=new DAOBag();
                $re=$bagDao->getUserBagByCateids($uid, array());
                $cache->set($key, json_encode($re), 60*60*24*30*6);//,
            }
            return $re;
        }catch (Exception $e){

        }
    }

    //获取所有有效的背包礼物
    public static function getValidUserBag($uid)
    {
        $all=self::getUserBag($uid);
        $re=array();
        foreach ($all as $i){
            if ($i['type']==DAOBag::BAG_TYPE_NUM) {
                if($i['num']<=0) { continue;
                }
            }
            if ($i['type']==DAOBag::BAG_TYPE_EXP) {
                if (strtotime($i['expiretime'])<=time()) { continue;
                }
            }
            $re[]=$i;
        }
        return $re;
    }

    //获取物品详细信息
    public static function getItemExt(array $i)
    {
        $re=array();
        if (empty($i)) { return $re;
        }
        $i['isDynamic']=0;
        if ($i['source']==DAOBag::BAG_SOURCE_GIFT) {
            $gift=new Gift();
            $giftInfo=$gift->getGiftInfo($i['relateid'], true);
            if (empty($giftInfo)) { return $re;
            }

            $i['name']=!empty($giftInfo['name'])?$giftInfo['name']:"";
            $i["image"]=!empty($giftInfo['image'])?Util::joinStaticDomain($giftInfo['image']):"";
            $i["type"]=!empty($giftInfo['type'])?$giftInfo['type']:"";
            $i["price"]=!empty($giftInfo['price'])?$giftInfo['price']:"";
            $i["consume"]=!empty($giftInfo['consume'])?$giftInfo['consume']:"DIAMOND";
            $i["ticket"]=!empty($giftInfo['ticket'])?$giftInfo['ticket']:0;
            $i['extends']=null;
            return $i;
        }elseif ($i['source']==DAOBag::BAG_SOURCE_STORE) {
            $product=new Product();
            $productInfo=$product->getOne($i['relateid']);
            if (empty($productInfo)) { return $re;
            }

            $i['image']=!empty($productInfo['image'])?Util::joinStaticDomain($productInfo['image']):"";
            $i['name']=!empty($productInfo['name'])?$productInfo['name']:"";
            $i['price']=!empty($productInfo['price'])?$productInfo['price']:0;
            $i['online']=!empty($productInfo['online'])?$productInfo['online']:"N";
            $i['isDynamic']=isset($productInfo['isDynamic'])?$productInfo['isDynamic']:0;
            $i['productid']=$productInfo['productid'];

            $i['remark']=!empty($productInfo['remark'])?$productInfo['remark']:"";
            $i['extends']=null;
            $i['unit_str']=Product::getUnitName($productInfo['unit']);
            if (!empty($productInfo['extends'])) {
                $ext=Product::productExtends($productInfo);
                $i['extends']=@json_decode($ext, true);
            }
            return $i;
        }else{
            return $re;
        }
    }

    //获取剩余时间
    private static function getLeftTime(array  $i)
    {
        if ($i['type']==DAOBag::BAG_TYPE_NUM) {
            $i['time_str']='9999d';
        }elseif ($i['type']==DAOBag::BAG_TYPE_EXP) {
            $exp_left=strtotime($i['expiretime'])-time();
            if($exp_left>=3600*24) {
                $i['time_str']=floor($exp_left/(3600*24)).'d';
            }elseif($exp_left>=3600) {
                $i['time_str']=floor($exp_left/3600).'h';
            }elseif ($exp_left>=60) {
                $i['time_str']=floor($exp_left/60).'m';
            }elseif($exp_left>=0) {
                $i['time_str']=$exp_left.'s';
            }else{
                $i['time_str']='';//过期
            }

        }else{
            $i['time_str']='9999d';
        }
        return $i['time_str'];
    }

    //背包列表
    public static function getBagList($uid)
    {
        $re=array();
        $allow_cateid=[DAOBag::BAG_CATEID_BIG_HORN,DAOBag::BAG_CATEID_GIFT,DAOBag::BAG_CATEID_SMALL_HORN];//只显示礼物和喇叭
        $all=self::getValidUserBag($uid);
        foreach ($all as $i){
            if (!in_array($i['cateid'], $allow_cateid)) { continue;
            }
            $i['time_str']=self::getLeftTime($i);
            $i['bagid']=$i['id'];
            $i["itype"]=$i["type"];
            $i["itag"]=$i["tag"];
            $i['is_msend']=self::isSendMulti($i['cateid']);
            $t=self::getItemExt($i);
            if (empty($t)) { continue;
            }
            $re[]=$t;
        }
        return $re;
    }

    //已购商品
    public static function getPurchasedList($uid)
    {
            $result=array();
            $rideList=self::getPurchasedListByCateid($uid, DAOBag::BAG_CATEID_RIDE);
        if (!empty($rideList)) {
            $result[]=array(
                'name'=>'进场特效',
                'list'=>$rideList,
            );
        }
            $tokenList=self::getPurchasedListByCateid($uid, DAOBag::BAG_CATEID_ANCHOR_LEVEL_TOKEN);
        if (!empty($tokenList)) {
            $result[]=array(
                'name'=>'荣誉勋章',
                'list'=>$tokenList,
            );
        }
            return $result;

           /* return array(
                array(
                    'name'=>'进场特效',
                    'list'=>self::getPurchasedListByCateid($uid,DAOBag::BAG_CATEID_RIDE ),
                ),
                array(
                    'name'=>'荣誉勋章',
                    'list'=>self::getPurchasedListByCateid($uid,DAOBag::BAG_CATEID_ANCHOR_LEVEL_TOKEN ),
                ),
            );*/
    }
    private static function getPurchasedListByCateid($uid,$cateid)
    {
        $re=array();
        $all=self::getValidUserBag($uid);
        foreach ($all as $i){
            if ($i['cateid']!=$cateid) { continue;
            }
            $i['time_str']=self::getLeftTime($i);
            $i['bagid']=$i['id'];
            $i["itype"]=$i["type"];
            $i["itag"]=$i["tag"];
            $i['is_msend']=self::isSendMulti($i['cateid']);
            $t=self::getItemExt($i);
            if (empty($t)) { continue;
            }
            $re[]=$t;
        }
        usort(
            $re, function ($a,$b) {
                $n=time();
                $ta=strtotime($a['expiretime'])-$n;
                $tb=strtotime($b['expiretime'])-$n;

                if($ta==$tb) { return 0;
                }
                return ($ta>$tb)?1:-1;
            }
        );

        $on=null;
        $tk=null;
        foreach ($re as $k=>$i){
            if (empty($on)&&$i['cateid']==$cateid&&$i['status']==DAOBAG::BAG_STATUS_ON) {
                $on=$i;
                unset($re[$k]);
                break;
            }
        }
        if ($on) { array_unshift($re, $on);
        }

        return $re;
    }

    //获取唯一佩戴物品
    public static function getOnlyWithItem($uid,$cateid)
    {
        $onlyOneCateids=array(DAOBag::BAG_CATEID_ANCHOR_LEVEL_TOKEN,DAOBag::BAG_CATEID_RIDE);
        if (!in_array($cateid, $onlyOneCateids)) { return array();
        }
        $all=self::getValidUserBag($uid);
        foreach ($all as $i){
            if ($i['cateid']==$cateid&&$i['status']==DAOBAG::BAG_STATUS_ON) {
                return $i;
            }
        }
        return null;
    }
    //获取主播标识
    //todo 公聊区 后台
    public static function getAnchorToken($uid)
    {
        $t=self::getOnlyWithItem($uid, DAOBag::BAG_CATEID_ANCHOR_LEVEL_TOKEN);
        $url='';
        if (!empty($t)) {
            $product=new Product();
            $info=$product->getOne($t['relateid']);
            if ($info) {
                if ($info['extends']) {
                    $ext=@json_decode($info['extends'], true);
                    if ($ext) {
                        $url=isset($ext['zipurl'])?$ext['zipurl']:$info['image'];
                        //$url= Util::joinStaticDomain($url);
                    }
                }
            }
        }
        $t['url']=$url;
        $t['isDynamic']=Resource::SOURCE_TYPE_STATIC;
        return $t;
    }
    //获取有效座驾
    public static function getRide($uid)
    {
        $t=self::getOnlyWithItem($uid, DAOBag::BAG_CATEID_RIDE);
        $url='';
        $isDynamic=Resource::SOURCE_TYPE_STATIC;
        if (!empty($t)) {
            $product=new Product();
            $info=$product->getOne($t['relateid']);
            if ($info) {
                if ($info['extends']) {
                    $ext=@json_decode($info['extends'], true);
                    if ($ext) {
                        $url=isset($ext['zipurl'])?$ext['zipurl']:$info['image'];
                        //$url= Util::joinStaticDomain($url);
                        $isDynamic=isset($ext['isDynamic'])&&$ext['isDynamic']?Resource::SOURCE_TYPE_DYNAMIC:Resource::SOURCE_TYPE_STATIC;
                    }
                }
            }
        }
        $t['url']=$url;
        $t['isDynamic']=$isDynamic;
        return $t;
    }

    //获取背包礼物，通过id
    public static function getItemById($uid,$id)
    {
        $re=self::getBagList($uid);
        foreach ($re as $i){
            if ($i['id']==$id) {
                return $i;
            }
        }
        return null;
    }

    //把物品放入medal
    public static function putItemInMedal($uid,$cateid)
    {
        try{
            if ($cateid==DAOBag::BAG_CATEID_RIDE) {
                $mkind=UserMedal::KIND_RIDE;
                $r=self::getRide($uid);
            }elseif ($cateid==DAOBag::BAG_CATEID_ANCHOR_LEVEL_TOKEN) {
                $mkind=UserMedal::KIND_ANCHOR_TOKEN;
                $r=self::getAnchorToken($uid);
            }else{
                return;
            }

            if ($r) {
                $ext=array(
                    'url'=>$r['url'],
                    'expiretime'=>$r['expiretime'],
                    'productid'=>$r['relateid'],
                    'isDynamic'=>$r['isDynamic'],
                );
                $medal=new UserMedal();
                $medal->addUserMedal($uid, $mkind, json_encode($ext));
                User::reload($uid);
            }
        }catch (Exception $e){

        }
    }

    //把有效座驾放入medal
    public static function putRideInMedal($uid)
    {
        self::putItemInMedal($uid, DAOBag::BAG_CATEID_RIDE);
    }
    //把有效主播标识放入medal
    public static function putAnchorTokenInMedal($uid)
    {
        self::putItemInMedal($uid, DAOBag::BAG_CATEID_ANCHOR_LEVEL_TOKEN);
    }

    //解析medal获取主播标识
    public static function parseAnchorTokenFromMedal($userinfo)
    {
        if (!is_array($userinfo['medal'])) { return '';
        }
        foreach ($userinfo['medal'] as $i){
            if ($i['kind']==UserMedal::KIND_ANCHOR_TOKEN) {
                $ext=@json_decode($i['medal'], true);
                if ($ext) {
                    if (strtotime($ext['expiretime'])>time()) {
                        return $ext['url'];
                    }else{
                        $m=new UserMedal();
                        $info=$m->getUserMedal($userinfo['uid'], UserMedal::KIND_ANCHOR_TOKEN);
                        if ($info) {
                            $m->delUserMedal($userinfo['uid'], UserMedal::KIND_ANCHOR_TOKEN);
                            User::reload($userinfo['uid']);
                        }
                    }
                }
                break;
            }
        }
        return '';
    }
    //解析medal获取座驾
    public static function parseRideFromMedal($userinfo)
    {
        if (!is_array($userinfo['medal'])) { return array();
        }
        foreach ($userinfo['medal'] as $i){
            if ($i['kind']==UserMedal::KIND_RIDE) {
                $ext=@json_decode($i['medal'], true);
                if ($ext) {
                    if (strtotime($ext['expiretime'])>time()) {
                        $url=$ext['url'];
                        $rideInfo=Product::getOne($ext['productid']);
                        if ($rideInfo&&$ext['isDynamic']==Resource::SOURCE_TYPE_DYNAMIC) {
                            $url=$rideInfo['image'];
                        }
                        return array(
                            'url'=>$ext['url'],
                            'productid'=>$ext['productid']
                        );
                    }else{
                        $m=new UserMedal();
                        $info=$m->getUserMedal($userinfo['uid'], UserMedal::KIND_RIDE);
                        if ($info) {
                            $m->delUserMedal($userinfo['uid'], UserMedal::KIND_RIDE);
                            User::reload($userinfo['uid']);
                        }
                    }
                }
                break;
            }
        }
        return array();
    }

    public static function updateUserInfo(&$userinfo)
    {
        if ($userinfo) {
            $userinfo["anchor_token"]=Bag::parseAnchorTokenFromMedal($userinfo);
            $ride=Bag::parseRideFromMedal($userinfo);
            $userinfo["rideid"]=isset($ride['productid'])?$ride['productid']:0;
            $userinfo["rideurl"]=isset($ride['url'])?$ride['url']:'';
        }
    }
    
    //初始化背包缓存
    private static function initBagCache()
    {
        $daoBag=new DAOBag();
        $all=$daoBag->getAll("select uid from bag group by uid");
        foreach ($all as $i){
            self::reloadUserBag($i['uid']);
        }
    }
    //初始化坐骑缓存
    private static function initRideMedal()
    {
        $daoBag=new DAOBag();
        $all=$daoBag->getAll("select uid from bag where status='ON' and cateid=4 and UNIX_TIMESTAMP(expiretime)>UNIX_TIMESTAMP('".date('Y-m-d H:i:s')."')");
        $add=0;
        foreach ($all as $i){
            $add++;
            self::putRideInMedal($i['uid']);
            if ($add%3000==0) {
                sleep(1);
            }
        }
    }

    //删除旧redisbaglist
    private static function delBagListCache()
    {
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $daoBag=new DAOBag();
        $all=$daoBag->getAll("select uid from bag group by uid");
        foreach ($all as $i){
            $cache->del(self::BAG_LIST_BY_UID.$i['uid']);
        }
    }

    //清楚过期物品
    private static function clearUserBag($uid)
    {
        try{
            $daoBag=new DAOBag();
            $daoBag->clear($uid);
        }catch (Exception $e){

        }
    }
}

class BagGift extends Gift
{
    public static function giftSendAfter()
    {
        Bag::consumeGift();
    }
}

class MyHorn
{
    const TYPE_SMALL_HORN=1;
    const TYPE_BIG_HORN=2;

    public static function send($uid,$content,$liveid,$type)
    {

        Interceptor::ensureNotEmpty($uid,    ERROR_PARAM_IS_EMPTY, "uid");
        Interceptor::ensureNotEmpty($content,    ERROR_PARAM_IS_EMPTY, "content");
        Interceptor::ensureNotEmpty($liveid,    ERROR_PARAM_IS_EMPTY, "liveid");

        //检验是否包含屏蔽词
        $plus['liveid'] = $liveid;
        $plus['sender'] = $uid;

        Interceptor::ensureNotFalse(FilterKeyword::check_shield($content, $plus, false), ERROR_KEYWORD_SHIELD, 'content');

        //替换内容
        $replace_keyword = array();
        $content = FilterKeyword::content_replace($content, $replace_keyword);
        $replace_keyword = !empty($replace_keyword) ? implode(',', $replace_keyword) : '';

        if (!empty($replace_keyword)) {
            $dao_filter = new DAOFilter();
            $type = !empty($replace_keyword) ? FilterKeyword::REPLACE : FilterKeyword::NORMAL;
            $dao_filter->addFilter($uid, 0, $type, $content, $replace_keyword, $liveid);
        }


        $live = new Live();
        $liveinfo = $live->getLiveInfo($liveid);
        $user_guard = UserGuard::getUserGuardRedis($uid, $liveinfo['uid']);

        $userinfo = User::getUserInfo($uid);

        if ($type==self::TYPE_BIG_HORN) {
            $data = array(
                "userid"=>$uid,
                "nickname"=>$userinfo['nickname'],
                "avatar"=>$userinfo['avatar'],
                "level"=>$userinfo['level'],
                "gender"=>$userinfo['gender'],
                "medal"=>$userinfo['medal'],
                "founder"=>$userinfo['founder'],
                "vip" => (int)$userinfo['vip'],
                'isGuard'=>intval($user_guard),
                "fontcolor" => (string)Vip::getLevelConfig($userinfo['vip'], Vip::TYPE_FONT_COLOR),
            );

            Messenger::sendChatroomBroadcast($uid, Messenger::MESSAGE_TYPE_BROADCAST_HORN_ALL, $content, $data);
        }elseif ($type==self::TYPE_SMALL_HORN) {
            Messenger::sendProp($liveid, $content, $uid, $userinfo['nickname'], $userinfo['avatar'], $userinfo['level'], $userinfo['medal']);
        }

    }
}