<?php
class PackageController extends BaseController
{
	public function __construct()
	{
		parent::__construct();
	}


	public function listAction()
	{
		$package_num= trim($this->getParam('packageid', ''));

		$sn  		= trim($this->getParam('sn', ''));
		$online 	= trim($this->getParam('status', ''));
		$vip 		= trim($this->getParam('vip', '-1'));
		$type 		= trim($this->getParam('type', '-1'));
		$sales_type	= $this->getParam("sales_type", '-1');
		$end_time 	= trim($this->getParam('end_time', ''));
		$page       = $this->getParam("page") ? intval($this->getParam("page")) : 1;
		$num        = $this->getParam("num") ? intval($this->getParam("num")) : 20;
		$start  = ($page - 1) * $num;

		$data = array();

		$goods = new Package();
		list($data, $total) = $goods->getList($start, $num, $package_num, $sn, $online, $sales_type, $type, $vip);

		if (!empty($data)) {
			foreach ($data as &$item) {
				if (strpos($item['cover'], 'https://static.putaofenxiang.com/') === false) {
					$item['cover'] = 'https://static.putaofenxiang.com'. $item['cover'];
				}
			}
		}

		$mutipage = $this->mutipage($total, $page, $num, http_build_query(array("type"=>$type,"vip" => $vip, "sales_type" => $sales_type, "status" => $online)), "/package/list");

		$this->assign("page", $mutipage);
		$this->assign("list", $data);
		$this->assign("total", $total);
		$this->assign("statusData", Package::getStatusData());
		$this->assign("typeStatus", [1=> '出售', 2=> '出租']);
		$this->assign("vipStatus", [0=> '否', 1 => '是']);

		$this->display("include/header.html", "package/index.html", "include/footer.html");
	}

	/**
	 * 删除包裹
	 */
	public function delAction()
	{
		$id = intval($this->getParam('id'));
		$goods = new Package();

		if($goods->del($id)){
			$msg = '删除成功';
		}else{
			$msg = '删除失败';
		}
		$operate = new Operate();
		$operate->Add($this->adminid, 'delGoods', 0, 0, "", "", "$id", 1);
		Util::jumpMsg($msg, '/goods/list');
	}

	public function modifyAction()
	{
		$id = intval($this->getParam('id'));

		if ($_POST) {
			$online = $this->getParam('status');
			$description = $this->getParam('description');
			$deposit_price  = $this->getParam("deposit_price");
			$rent_price  	= $this->getParam("rent_price");
			$location  	= $this->getParam("location");
			$num  	= $this->getParam("num");
			$type  	= $this->getParam("type");
			$vip  	= $this->getParam("vip");

			$package = new Package();
			$info = $package->getInfo($id);

			$up_online = '';
			if ($online != $info['status']) {
				$up_online = $online;
			}

			$up_type = '';
			if ($type != $info['type']) {
				$up_type = $type;
			}

			$up_vip = '';
			if ($vip != $info['vip']) {
				$up_vip = $vip;
			}

			$up_num = '';
			if ($num != $info['num']) {
				$up_num = $num;
			}

			$up_address = '';
			if ($location != $info['location']) {
				$up_address = $location;
			}

			$up_des = '';
			if ($description != $info['description']) {
				$up_des = $description;
			}

			$up_de_price = '';
			if ($deposit_price != $info['deposit_price']) {
				$up_de_price = $deposit_price;
			}


			$up_rent_price = '';
			if ($rent_price != $info['description']) {
				$up_rent_price = $rent_price;
			}
			try {
				if (!empty($up_num) || !empty($up_address) || !empty($up_des) ||!empty($up_online) || !empty($up_de_price) || !empty($up_rent_price) || !empty($up_type) || !empty($up_vip)) {
					ShareClient::packageModify($id, '', $up_des, $up_de_price, $up_rent_price, $up_online, $up_num, $up_address, $up_type, $up_vip);
				}
			} catch (Exception $e) {
				Util::jumpMsg("修改出错!" . $e->getCode() . "-" . $e->getMessage());
			}

			$operate = new Operate();
			$operate->Add($this->adminid, 'updatePackage', 0, 0, [$id, $up_des, $up_online], "", "$id", 1);
			Util::jumpMsg("修改成功", '/package/list');
		} else {
			$package = new Package();
			$package_info = $package->getInfo($id);
			$modelGoods = new Goods();

            $goods = $modelGoods->getListByPackageId($id);
			//print_r($package_info);
            $card = new Card();
            $cardinfo = $card->getInfo($package_info['cardid']);
            $this->assign('cardinfo', $cardinfo);
			$this->assign('package_info', $package_info);
			$this->assign('goods', $goods);

			$this->display("include/header.html", "package/modify.html", "include/footer.html");
		}
	}

	public function onlineAction()
	{
		$id = intval($this->getParam('id'));

		try {
			ShareClient::packageModify($id, 'Y', '', 0, 0,'ONLINE');
		} catch (Exception $e) {
			Util::jumpMsg("修改出错!" . $e->getCode() . "-" . $e->getMessage());
		}

		$operate = new Operate();
		$operate->Add($this->adminid, 'updatePackageOnline', 0, 0, [$id, 'Y'], "", "$id", 1);
		Util::jumpMsg("上架成功", '/package/list');
	}


	public function offlineAction()
	{
		$id = intval($this->getParam('id'));

		try {
			ShareClient::packageModify($id, 'N', '', 0, 0, 'OFFLINE');
		} catch (Exception $e) {
			Util::jumpMsg("修改出错!" . $e->getCode() . "-" . $e->getMessage());
		}
		$operate = new Operate();
		$operate->Add($this->adminid, 'updatePackageOnline', 0, 0, [$id, 'N'], "", "$id", 1);
		Util::jumpMsg("下架成功", '/package/list');
	}

	public function createAction()
	{
		if(!empty($_POST)) {
			$select_ids = $this->getParam("items", "");

			$description = $this->getParam('description');
			$deposit_price  = $this->getParam("deposit_price");
			$rent_price  	= $this->getParam("rent_price");
			$cover_id  	= $this->getParam("cover_id");
			$categoryid = $this->getParam("categoryid");
			$location  	= $this->getParam("location");
			$contact  	= $this->getParam("contact");
			$type  	= $this->getParam("type");
			$vip  	= $this->getParam("vip");
			if (empty($select_ids)) {
				Util::jumpMsg("请选择商品生成包裹", '/package/create');
			}

			if (empty($rent_price)) {
				Util::jumpMsg("请填写租金", '/package/create');
			}

			if (empty($description)) {
				Util::jumpMsg("请填写描述", '/package/create');
			}

			if (empty($cover_id)) {
				Util::jumpMsg("请填写封面商品id", '/package/create');
			}

// 			if (empty($categoryid)) {
// 				Util::jumpMsg("请填写分类id", '/package/create');
// 			}
			$categoryid = 0;
			$goods_model = new Goods();
			$goods = $goods_model->getOneById($cover_id);

			$package_model = new Package();
			$num = count($select_ids);
			$goodsids = '';
			$id_ar = [];
			foreach ($select_ids as $id) {
				$id_ar[] = $id;
			}
			$goodsids = implode(',', $id_ar);

			$info = $goods_model->getPriceSum($goodsids);

			if ($num > 15) {
				Util::jumpMsg("一个包裹不能超过15件商品", '/package/create');
			}

			$packageid = $package_model->createPackage($rent_price, $goods['file'], $categoryid, $goods['type'], $num, $location, $description, $contact, $info['show_grape'], $type, $vip);

			$package_model->createPackageGoods($packageid, $select_ids);

			$model_resource = new PreviewResource();
			$resource_id = $model_resource->create($goods['type'], $goods['file']);

			$model_resource->relation($resource_id, $packageid);

			Util::jumpMsg("创建包裹成功id是:{$packageid}", '/package/list');
			exit;
		}
		$page               = $this->getParam("page", 1);
		$limit              = $this->getParam("num", 20);

		$modelGoods = new Goods();
		$data = $modelGoods->getUnpackedList($page, $limit);
		$buildQuery = [

		];
		$mutipage = $this->mutipage($data['total'], $page, $limit, http_build_query($buildQuery), "/package/create");

		$statusData = Goods::getStatusArr();

		$this->assign('statusData', $statusData);
		$this->assign('mutipage', $mutipage);
		$this->assign('data', $data);

		$this->display("include/header.html", "package/select_goods.html", "include/footer.html");
	}
}