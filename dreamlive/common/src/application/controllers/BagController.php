<?php
class BagController extends BaseController
{

    public function getPurchasedListAction()
    {
        $uid= $uid = Context::get("userid", 0);
        Interceptor::ensureNotFalse($uid>0, ERROR_PARAM_INVALID_FORMAT, 'uid');
        $cateid=DAOBag::BAG_CATEID_RIDE;
        $contain_expire=true;
        $bag_list=Bag::bagList($uid, $cateid, $contain_expire);

        $this->render(['list'=>$bag_list]);
    }

    public function getBagListAction()
    {
        $uid= $uid = Context::get("userid", 0);
        $cateid=$this->getParam("cateid", 0);
        Interceptor::ensureNotFalse($uid>0, ERROR_PARAM_INVALID_FORMAT, 'uid');
        Interceptor::ensureNotFalse($cateid>=0, ERROR_PARAM_INVALID_FORMAT, 'cateid');

        //$contain_expire=$cateid==DAOBag::BAG_CATEID_RIDE?true:false;
        //$bag_list=Bag::bagList($uid, $cateid,$contain_expire);

        $this->render(['list'=>Bag::getBagList($uid)]);
    }

    public function getHornNumAction()
    {
        $uid= $uid = Context::get("userid", 0);
        Interceptor::ensureNotFalse($uid>0, ERROR_PARAM_INVALID_FORMAT, 'uid');
        //背包喇叭飞屏数
        list($big,$small)=Bag::getAllHornNum($uid);
        $this->render(['big_horn'=>$big,'small_horn'=>$small]);
    }

    public function useRideAction()
    {

        $bagid= $this->getParam('bagid', 0);
        $status=$this->getParam('status', '');
        Interceptor::ensureNotFalse($status&&in_array($status, ['ON','OFF']), ERROR_PARAM_IS_EMPTY, 'status');
        Interceptor::ensureNotFalse($bagid>0, ERROR_PARAM_IS_EMPTY, 'bagid');
        Interceptor::ensureNotFalse(Bag::itemOfExpire($bagid, $status), ERROR_BIZ_BAG_USE_RIDE_FAIL, 'use ride fail');
        $this->render();
        
    }

    public function useGiftAction()
    {
        $bagid= $this->getParam('bagid', 0);
        $uid = Context::get("userid");
        $giftid = intval($this->getParam("giftid"));
        $liveid = intval($this->getParam("liveid", 0));
        $num = intval($this->getParam("num"));
        $receiver = intval($this->getParam("receiver"));
        $doublehit = intval($this->getParam("doublehit"));
        $giftUniTag = trim(strip_tags($this->getParam("giftUniTag")));

        Interceptor::ensureNotFalse($bagid>0, ERROR_PARAM_IS_EMPTY, 'bagid');
        Interceptor::ensureNotEmpty($uid,    ERROR_PARAM_IS_EMPTY, "uid");
        Interceptor::ensureNotEmpty($giftid,    ERROR_PARAM_IS_EMPTY, "giftid");
        //Interceptor::ensureNotEmpty($liveid,    ERROR_PARAM_IS_EMPTY, "liveid");
        Interceptor::ensureNotEmpty($num,    ERROR_PARAM_IS_EMPTY, "num");
        Interceptor::ensureNotFalse($num>0,    ERROR_PARAM_INVALID_FORMAT, "num");
        Interceptor::ensureNotEmpty($receiver,    ERROR_PARAM_IS_EMPTY, "receiver");
        Interceptor::ensureNotFalse($uid!=$receiver, ERROR_BIZ_GIFT_NOT_SEND_SELF);
        Interceptor::ensureNotEmpty($giftUniTag,    ERROR_PARAM_IS_EMPTY, "giftUniTag");

        $this->render(Bag::useBagGift($bagid, $uid, $receiver, $giftid, $num, $liveid, $doublehit, $giftUniTag));

    }


    public function useHornAction()
    {
        $bagid= $this->getParam('bagid', 0);
        $this->render();
    }

    public function putGiftAction()
    {
        $uid=$this->getParam("uid", 0);
        $giftid=$this->getParam("giftid", 0);
        $num=$this->getParam("num", 0);
        $notice=$this->getParam("notice", '');

        Interceptor::ensureNotEmpty($uid, ERROR_PARAM_IS_EMPTY, ["uid 参数为空"]);
        Interceptor::ensureNotEmpty($giftid, ERROR_PARAM_IS_EMPTY, ["giftid 参数为空"]);
        Interceptor::ensureNotEmpty($num, ERROR_PARAM_IS_EMPTY, ["num 参数为空"]);
        Interceptor::ensureNotEmpty($notice, ERROR_PARAM_IS_EMPTY, ["notice 参数为空"]);

        $this->render(Bag::putGift($uid, $giftid, $num, $notice));
    }

    public function putRideAction()
    {
        $uid=$this->getParam("uid", 0);
        $cateid=$this->getParam("cateid", 0);
        $productid=$this->getParam("productid", 0);
        $expire=$this->getParam("expire", 0);
        $num=$this->getParam("num", 1);
        $notice=$this->getParam("notice", '');

        Interceptor::ensureNotEmpty($uid, ERROR_PARAM_IS_EMPTY, ["uid 参数为空"]);
        Interceptor::ensureNotEmpty($cateid, ERROR_PARAM_IS_EMPTY, ["cateid 参数为空"]);
        Interceptor::ensureNotEmpty($productid, ERROR_PARAM_IS_EMPTY, ["productid 参数为空"]);
        Interceptor::ensureNotEmpty($expire, ERROR_PARAM_IS_EMPTY, ["expire 参数为空"]);
        Interceptor::ensureNotEmpty($num, ERROR_PARAM_IS_EMPTY, ["num 参数为空"]);
        Interceptor::ensureNotEmpty($notice, ERROR_PARAM_IS_EMPTY, ["notice 参数为空"]);

        $this->render(Bag::putProduct($uid, $cateid, $productid, $expire, $num, $notice));
    }
}
