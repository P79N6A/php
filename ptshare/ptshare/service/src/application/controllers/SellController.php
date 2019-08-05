<?php
class SellController extends BaseController
{

    protected $modelSell;
    protected $modelGoods;
    protected $modelUsersDonate;
    protected $userid = null;

    public function __construct()
    {

        parent::__construct();

        $this->modelSell = new Sell();
        $this->modelGoods = new Goods();
        $this->modelUsersDonate = new UsersDonate();
        $this->userid = Context::get("userid");

    }

    /**
     * 用户提交分享入口
     */
    public function addAction()
    {
        // 包裹信息
        $userid             = $this->userid;
        $contact            = $this->getParam("contact");
        $previews           = $this->getParam("previews");
        $description        = $this->getParam("description");
        $free               = $this->getParam("free", 'N');
        $categoryid         = $this->getParam("category_id", 0);
        $extends            = $this->getParam("extends_info" ,'');
        $total_grape        = $this->getParam("total_grape");
        $cardid        		= $this->getParam("cardid", 0);
        $grape_forward		= $this->getParam("grape_forward", 1);
        $sales_type		    = $this->getParam("sales_type", 0);
        $lottery_groupid	= $this->getParam("lottery_groupid") 	? intval($this->getParam("lottery_groupid")) 	: 0;
//        $formId             = $this->getParam("form_id");
        if ($total_grape > 10000) {
            $total_grape = 10000;
        }

        // 宝贝信息
        $goods_draft        = $this->getParam("goods_draft");

        Interceptor::ensureJson($contact, ERROR_PARAM_INVALID_FORMAT, 'contact');
        Interceptor::ensureJson($previews, ERROR_PARAM_INVALID_FORMAT, 'previews');
        Interceptor::ensureNotEmpty($description, ERROR_PARAM_INVALID_FORMAT, 'description');
        Interceptor::ensureNotFalse(in_array($free, ['Y', 'N']), ERROR_PARAM_INVALID_FORMAT, 'free');
        Interceptor::ensureNotFalse(is_numeric($total_grape), ERROR_PARAM_INVALID_FORMAT, 'total_grape');
        Interceptor::ensureJson($goods_draft, ERROR_PARAM_INVALID_FORMAT, 'goods_draft');

        if (!empty($extends)) {
            Interceptor::ensureJson($extends, ERROR_PARAM_INVALID_FORMAT, 'extends');
        }

        $result = $this->modelSell->add($userid, $categoryid, $total_grape, $description, $contact, $free, $goods_draft, $previews, $extends, $cardid, $grape_forward, $sales_type);

        try{
        	if (!empty($lottery_groupid)) {
        		TravelLottery::join($userid, $lottery_groupid, $result, DAOTravelMember::MEMBER_FROM_SHARE);
        	}
        } catch (Exception $e) {
        	Logger::log("travel_lottery_log", "join", array("groupid" => $lottery_groupid,"userid" => $userid,"code" => $e->getCode(),"msg" => $e->getMessage()));
        }

        Logger::log("add_sell_params", "params", array("sellid" => $result,"userid" => $userid,"json" => json_encode($_REQUEST)));

        $this->render(['id' => $result]);
    }

    /**
     * 前端显示列表
     */
    public function getSellListAction()
    {
        $userid = $this->userid;
        $type = $this->getParam("type");
        $offset = $this->getParam("offset", 0);
        $limit = $this->getParam("num", 7);

        Interceptor::ensureNotEmpty($type, ERROR_PARAM_INVALID_FORMAT, 'type');
        Interceptor::ensureNotFalse(is_numeric($offset), ERROR_PARAM_INVALID_FORMAT, 'offset');
        Interceptor::ensureNotFalse(is_numeric($limit), ERROR_PARAM_INVALID_FORMAT, 'limit');

        $data = $this->modelSell->getList($userid, $type, $limit, $offset);

        $this->render(['list' => $data['list'], 'offset' => $data['offset'], 'more' => $data['more']]);

    }

    public function updateGoodsAction()
    {
        $param = $this->getParam("goods");
        Interceptor::ensureJson($param, ERROR_PARAM_INVALID_FORMAT, 'goods');
        $param = json_decode($param, true);
        $this->modelGoods->updateInfo($param);

        $this->render();
    }

    /**
     * 修改sell 状态，用于系统性操作
     */
    public function setSellStatusAction()
    {
        $id = $this->getParam("id");
        $remark = $this->getParam("remark");
        $status = $this->getParam("status");
        $type 	= $this->getParam("type");
        $vip 	= $this->getParam("vip");

        Interceptor::ensureNotFalse((is_numeric($id) && $id > 0), ERROR_PARAM_INVALID_FORMAT, 'id');
        Interceptor::ensureNotFalse((is_numeric($status) && $status > 0), ERROR_PARAM_INVALID_FORMAT, 'status');

        $this->modelSell->setSellStatus($id, $status, $type, $vip, $remark);
        $this->render(['id' => $id]);
    }

    public function setSellSuccessAction()
    {
        $id 	= $this->getParam("id");

        Interceptor::ensureNotFalse((is_numeric($id) && $id > 0), ERROR_PARAM_INVALID_FORMAT, 'id');
        $this->modelSell->setSuccess($id);
        $this->render();
    }


    public function setUserCancelAction()
    {
        $id     = $this->getParam("id");
        $userid = $this->userid;

        $this->modelSell->setUserCancel($id, $userid);
        $this->render();
    }

    // @todo 记得上线删除
    public function testCreateLoginUserAction()
    {
        $userid     = $this->getParam("userid");
        $code     = $this->getParam("code");
        if ($code != 'XrP8N63SlVMl3mPXPueHswaz9fRv9tSr') {
            $this->render('没事儿别瞎调用接口');
        }
        $token = Session::getToken($userid);
        $this->render(['token' => $token]);
    }

    public function updateContactAction()
    {
         $id                = $this->getParam("id");
         $contact_name      = $this->getParam("contact_name");
         $contact_zipcode   = $this->getParam("contact_zipcode");
         $contact_province  = $this->getParam("contact_province");
         $contact_city      = $this->getParam("contact_city");
         $contact_county    = $this->getParam("contact_county");
         $contact_address   = $this->getParam("contact_address");
         $contact_national  = $this->getParam("contact_national");
         $contact_phone     = $this->getParam("contact_phone");

        Interceptor::ensureNotFalse((is_numeric($id) && $id > 0), ERROR_PARAM_INVALID_FORMAT, 'id');
        Interceptor::ensureNotEmpty($contact_name    , ERROR_PARAM_INVALID_FORMAT, "contact_name");
        Interceptor::ensureNotEmpty($contact_zipcode    , ERROR_PARAM_INVALID_FORMAT, "contact_zipcode");
        Interceptor::ensureNotEmpty($contact_province, ERROR_PARAM_INVALID_FORMAT, "contact_province");
        Interceptor::ensureNotEmpty($contact_city    , ERROR_PARAM_INVALID_FORMAT, "contact_city");
        Interceptor::ensureNotEmpty($contact_county  , ERROR_PARAM_INVALID_FORMAT, "contact_county");
        Interceptor::ensureNotEmpty($contact_address , ERROR_PARAM_INVALID_FORMAT, "contact_address");
        Interceptor::ensureNotEmpty($contact_national  , ERROR_PARAM_INVALID_FORMAT, "contact_national");
        Interceptor::ensureNotEmpty($contact_phone  , ERROR_PARAM_INVALID_FORMAT, "contact_phone");

        $data = [
            'contact_name'      => $contact_name,
            'contact_zipcode'   => $contact_zipcode,
            'contact_province'  => $contact_province,
            'contact_city'      => $contact_city,
            'contact_county'    => $contact_county,
            'contact_address'   => $contact_address,
            'contact_national'  => $contact_national,
            'contact_phone'     => $contact_phone,
        ];

        $this->modelSell->updateContact($id, $data);

        $this->render();
    }


    public function addUsersDonateAction()
    {
        $userid             = $this->userid;
        $contact            = $this->getParam("contact");

        Interceptor::ensureJson($contact, ERROR_PARAM_INVALID_FORMAT, 'contact');

//        $result = $this->modelUsersDonate->add($userid, $formId, $contact, $send);
        $result = $this->modelUsersDonate->add($userid, $contact);
        $this->render(['id' => $result]);

    }




}