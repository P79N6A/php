<?php
class GoodsController extends BaseController
{

    protected $modelGoods;

    public function __construct()
    {
        parent::__construct();

        $this->modelGoods = new Goods();

    }

//    public function addAction()
//    {
//        $userid         = $this->userid;
//        $cover          = $this->getParam("cover");
//        $description    = $this->getParam("description");
//        $num            = $this->getParam("num");
//        $contact        = $this->getParam("contact");
//        $free           = $this->getParam("free");
//        $goods_draft    = $this->getParam("goods_draft");
//
//        Interceptor::ensureNotEmpty($cover, ERROR_PARAM_INVALID_FORMAT, 'cover');
//        Interceptor::ensureNotEmpty($description, ERROR_PARAM_INVALID_FORMAT, 'description');
//        Interceptor::ensureJson($contact, ERROR_PARAM_INVALID_FORMAT, 'contact');
//        Interceptor::ensureJson($goods_draft, ERROR_PARAM_INVALID_FORMAT, 'goods_draft');
//        Interceptor::ensureNotFalse(($num > 0 && is_numeric($num)), ERROR_PARAM_INVALID_FORMAT, 'num');
//        Interceptor::ensureNotFalse(in_array($free, ['Y', 'N']), ERROR_PARAM_INVALID_FORMAT, 'free');
//
//        $result = $this->modelSell->add($userid, $cover, $description, $num, $contact, $free, $goods_draft);
//
//        $this->render(['id' => $result]);
//
//    }

    public function updateStatusAction()
    {

        $id = $this->getParam("id");
        $status = $this->getParam("status");
        $remark = $this->getParam("remark", '');
//        file_put_contents('update.log', $params, FILE_APPEND);
        Interceptor::ensureNotFalse(($id > 0 && is_numeric($id)), ERROR_PARAM_INVALID_FORMAT, 'id');
        $this->modelGoods->updateStatus($id, $status, $remark);
        $this->render();
    }





}