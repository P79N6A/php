<?php
class GiftController extends BaseController
{
    public function getListAction()
    {
        $tag = trim($this->getParam("tag", ""));
        $gift_list=Gift::giftListProcess();
        $this->render($gift_list);
    }

    public function sendAction()
    {
        $uid = Context::get("userid");
        $giftid = intval($this->getParam("giftid"));
        $liveid = intval($this->getParam("liveid", 0));
        $num = intval($this->getParam("num"));
        $receiver = intval($this->getParam("receiver"));
        $doublehit = intval($this->getParam("doublehit"));
        $giftUniTag = trim(strip_tags($this->getParam("giftUniTag")));

        Interceptor::ensureNotEmpty($uid,    ERROR_PARAM_IS_EMPTY, "uid");
        Interceptor::ensureNotEmpty($giftid,    ERROR_PARAM_IS_EMPTY, "giftid");
        //Interceptor::ensureNotEmpty($liveid,    ERROR_PARAM_IS_EMPTY, "liveid");
        Interceptor::ensureNotEmpty($num,    ERROR_PARAM_IS_EMPTY, "num");
        Interceptor::ensureNotFalse($num>0,    ERROR_PARAM_INVALID_FORMAT, "num");
        Interceptor::ensureNotEmpty($receiver,    ERROR_PARAM_IS_EMPTY, "receiver");
        Interceptor::ensureNotFalse($uid!=$receiver, ERROR_BIZ_GIFT_NOT_SEND_SELF);
        Interceptor::ensureNotEmpty($giftUniTag,    ERROR_PARAM_IS_EMPTY, "giftUniTag");

        $gift=new Gift();
        $ret=$gift->sendGiftProcess($uid, $receiver, $giftid, $num, $liveid, $doublehit, $giftUniTag);
        
        $this->render($ret);
    }

    public function addGiftAction()
    {
        $name = trim(strip_tags($this->getParam("name")));
        $image = trim(strip_tags($this->getParam("image")));
        $url = trim(strip_tags($this->getParam("uri")));
        $zip_md5 = trim(strip_tags($this->getParam("zip_md5")));
        $type = intval($this->getParam("type"));
        $label = trim(strip_tags($this->getParam("label")));
        $price = intval($this->getParam("price"));
        $ticket = intval($this->getParam("ticket"));
        $consume = trim(strip_tags($this->getParam("consume")));
        $score = intval($this->getParam("score"));
        $status = trim(strip_tags($this->getParam("status")));
        $extends = strip_tags($this->getParam("extends")) ? trim(strip_tags($this->getParam("extends"))) : '';
        $tag=strip_tags($this->getParam("tag")) ? trim(strip_tags($this->getParam("tag"))) : '';
        $region=strip_tags($this->getParam("region")) ? trim(strip_tags($this->getParam("region"))) : '';


        Interceptor::ensureNotEmpty($name,    ERROR_PARAM_IS_EMPTY, "name");
        Interceptor::ensureNotEmpty($image,    ERROR_PARAM_IS_EMPTY, "image");
        Interceptor::ensureNotEmpty($type,    ERROR_PARAM_IS_EMPTY, "type");
        Interceptor::ensureNotEmpty($price,    ERROR_PARAM_IS_EMPTY, "price");
        Interceptor::ensureNotEmpty($consume,    ERROR_PARAM_IS_EMPTY, "consume");
        Interceptor::ensureNotEmpty($status,    ERROR_PARAM_IS_EMPTY, "status");
        Interceptor::ensureNotFalse($price>=$ticket, ERROR_PARAM_INVALID_FORMAT, "ticket");

        $gift = new Gift();
        $gift->addGift($name, $image, $label, $type, $price, $ticket, $consume, $score, $status, $url, $zip_md5, $extends, $tag, $region);
        
        $this->render();
    }

    public function setGiftAction()
    {
        $giftid = intval($this->getParam("giftid"));
        $name = trim(strip_tags($this->getParam("name")));
        $image = trim(strip_tags($this->getParam("image")));
        $url = trim(strip_tags($this->getParam("uri")));
        $zip_md5 = trim(strip_tags($this->getParam("zip_md5")));
        $label = trim(strip_tags($this->getParam("label")));
        $type = intval($this->getParam("type"));
        $price = intval($this->getParam("price"));
        $ticket = intval($this->getParam("ticket"));
        $consume = trim(strip_tags($this->getParam("consume")));
        $score = intval($this->getParam("score"));
        $status = trim(strip_tags($this->getParam("status")));
        $extends = strip_tags($this->getParam("extends")) ? trim(strip_tags($this->getParam("extends"))) : '';
        $tag=strip_tags($this->getParam("tag")) ? trim(strip_tags($this->getParam("tag"))) : '';
        $region=strip_tags($this->getParam("region")) ? trim(strip_tags($this->getParam("region"))) : '';

        Interceptor::ensureNotEmpty($giftid, ERROR_PARAM_IS_EMPTY, "giftid");
        Interceptor::ensureNotEmpty($status,    ERROR_PARAM_IS_EMPTY, "status");

        $gift = new Gift();
        $gift->setGift($giftid, $name, $image, $label, $type, $price, $ticket, $consume, $score, $status, $url, $zip_md5, $extends, $tag, $region);

        $this->render();
    }

    public function getPrivateGiftWhiteListAction()
    {
        $this->render(WhiteList::getPrivateGiftWhiteList());
    }
    public function setPrivateGiftWhiteListAction()
    {
        $data=$this->getParam('data', array());
        $data=json_decode($data, true);
        Interceptor::ensureNotFalse($data, ERROR_PARAM_INVALID_FORMAT, 'data');
        WhiteList::setPrivateGiftWhiteList($data);
        $this->render();
    }
}
