<?php
class SellController extends BaseController
{

    protected $modelUser;
    protected $modelSell;
    protected $modelGoods;

    public function __construct()
    {
        parent::__construct();
        $this->modelUser = new User();
        $this->modelSell = new Sell();
        $this->modelGoods = new Goods();
    }

    public function indexAction()
    {

        $page       = $this->getParam("page", 1);
        $limit      = $this->getParam("num", 20);
        $id         = $this->getParam("id", 0);
        $status     = $this->getParam("status", 0);
        $phone      = $this->getParam("phone", 0);
        $uid        = $this->getParam("uid", 0);
        $sell_num   = $this->getParam("sell_num", '');
        $sn         = $this->getParam("sn", '');
        $orderid	= $this->getParam("orderid", '');
        $sales_type	= $this->getParam("sales_type", '-1');
        $create_start_time        = $this->getParam("create_start_time", 0);
        $create_end_time        = $this->getParam("create_end_time", 0);

        if ($phone) {

            $userInfo = $this->modelUser->getOneByPhone($phone);
            if (empty($userInfo)) {
                Util::jumpMsg("用户信息不存在!", "/sell/index");
            }

            $uid = !empty($userInfo) ? $userInfo['uid'] : 0;
        }


        $data = $this->modelSell->getSellList($id, $uid, $status, $create_start_time, $create_end_time, $page, $limit, $sell_num, $sn, $orderid, $sales_type);
        if (!empty($data['list'])) {
        	foreach ($data['list'] as &$item) {
        		if (strpos($item['cover'], 'https://static.putaofenxiang.com/') === false) {
        			$item['cover'] = 'https://static.putaofenxiang.com'. $item['cover'];
        		}
        	}
        }

        $mutipage = $this->mutipage($data['total'], $page, $limit, http_build_query(['uid' => $uid, 'status' => $status]), "/sell/index");
        $statusData = Sell::getStatusData();

        $this->assign('mutipage', $mutipage);
        $this->assign('data', $data);
        $this->assign('statusData', $statusData);

        $this->display("include/header.html", "sell/index.html", "include/footer.html");

    }

    public function detailAction()
    {

        $id = $this->getParam('id');

        if (empty($id)){
            Util::jumpMsg("参数异常!", "/sell/index");
        }

        $sellDetail = $this->modelSell->getOneById($id);

        if (empty($sellDetail)) {
            Util::jumpMsg("信息不存在!", "/sell/index");
        }
        $goodGrape = $this->modelGoods->getGrapeBySellId($id);
        $sellDetail = array_merge($sellDetail, $goodGrape);

        $contact          = json_decode($sellDetail['contact'], true);
        $goods            = $this->modelGoods->getListBySellId($id);

        $userDetail = $this->modelUser->getOneById($sellDetail['uid']);
        $statusData = Sell::getStatusData();
        $goodsStatusData = Goods::getStatusArr();
        $card = new Card();
        $cardinfo = $card->getInfo($sellDetail['cardid']);
        $this->assign('goodsStatusData', $goodsStatusData);
        $this->assign('sellDetail', $sellDetail);
        $this->assign('userDetail', $userDetail);
        $this->assign('statusData', $statusData);
        $this->assign('contact', $contact);
        $this->assign('cardinfo', $cardinfo);
        $this->assign('goods', $goods);

        $this->display("include/header.html", "sell/detail.html", "include/footer.html");

    }

    public function updateContactAction()
    {
        $id                 = $this->getParam("id");
        $contact_name       = $this->getParam("contact_name");
        $contact_zipcode    = $this->getParam("contact_zipcode");
        $contact_province   = $this->getParam("contact_province");
        $contact_city       = $this->getParam("contact_city");
        $contact_county     = $this->getParam("contact_county");
        $contact_address    = $this->getParam("contact_address");
        $contact_national   = $this->getParam("contact_national");
        $contact_phone      = $this->getParam("contact_phone");

        if (empty($id)){
            Util::jumpMsg("参数异常!", "/sell/index");
        }

        try {
            ShareClient::updateSellContact($id, $contact_name, $contact_zipcode, $contact_province, $contact_city, $contact_county, $contact_address, $contact_national, $contact_phone);
            Util::jumpMsg('修改成功', "/sell/detail?id=".$id);
        } catch (Exception $exception) {
            Util::jumpMsg($exception->getMessage(), "/sell/detail?id=".$id);
        }

    }

    public function setSellStatusAction()
    {

        $id     = $this->getParam("id");
        $status = $this->getParam("status");
        $type 	= $this->getParam("type");
        $vip 	= $this->getParam("vip");
		
        if (empty($type)) {
        	$type = 1 ;
        }
        
        if (empty($vip)) {
        	$vip = 0;
        }
        try {
        	
            if ($status == Sell::STATUS_SUCCESS) {
                ShareClient::setSellSuccess($id);
            } else {
            	ShareClient::setSellStatus($id, $status, $type, $vip);
            }
            Util::jumpMsg('修改成功', "/sell/detail?id=".$id);
        } catch (Exception $exception) {
            Util::jumpMsg($exception->getMessage(), "/sell/detail?id=".$id);
        }

    }

    public function updateGoodsAction()
    {
        $sellid         = $this->getParam("sellid");
        $ids            = $this->getParam("id");
        $status         = $this->getParam("status");
        $show_grape     = $this->getParam("show_grape");
        $worth_grape    = $this->getParam("worth_grape");
        $refuse_reason	= $this->getParam("refuse_reason");

        $param = [];
        foreach ($ids as $key => $id)
        {
        	if ($status[$key] == Goods::STATUS_FAIL && empty($refuse_reason[$key])) {
        		Util::jumpMsg('拒收原因必填，请填写拒收原因！！！！', "/sell/detail?id=".$sellid);
        		break;
        	}

            $param[] = [
                'id'            => $id,
                'status'        => $status[$key],
                'show_grape'    => $show_grape[$key],
                'worth_grape'   => $worth_grape[$key],
            	'refuse_reason' => $refuse_reason[$key]
            ];
        }
        $param = json_encode($param);
        try {
            ShareClient::updateGoods($param);
            Util::jumpMsg('修改成功', "/sell/detail?id=".$sellid);
        } catch (Exception $exception) {
            Util::jumpMsg($exception->getMessage(), "/sell/detail?id=".$sellid);
        }
    }





}