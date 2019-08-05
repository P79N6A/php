<?php
class UserController extends BaseController
{
	public function __construct()
	{
		parent::__construct();
	}


	public function listAction()
	{

		$begin_time = trim($this->getParam('begin_time', ''));
		$content_s  = trim($this->getParam('content', ''));
		$uid 		= trim($this->getParam('uid', ''));
		$nickname 	= trim($this->getParam('nickname', ''));
		$page       = $this->getParam("page") ? intval($this->getParam("page")) : 1;
		$num        = $this->getParam("num") ? intval($this->getParam("num")) : 15;
		$start  = ($page - 1) * $num;

		$data = array();

		$users = new User();
		list($data, $total) = $users->getList($start, $num, $uid, $nickname);
		if (!empty($data)) {
			$frozen_user = array();
			$frozen = new Frozen();
			foreach ($data as $key => $value) {
				$idin_arr[$value['uid']] = $value['uid'];
				$frozen_user[$value['uid']] = $frozen->isFrozen($value['uid']);
			}
		}

		$mutipage = $this->mutipage($total, $page, $num, http_build_query(array()), "/user/list");

		$this->assign("frozen_user", $frozen_user);
		$this->assign("mutipage", $mutipage);
		$this->assign("list", $data);
		$this->assign("total", $total);

		$this->display("include/header.html", "user/index.html", "include/footer.html");
	}

	public function accountListAction()
	{

		$uid = trim($this->getParam('uid', ''));

		$page       = $this->getParam("page") ? intval($this->getParam("page")) : 1;
		$num        = $this->getParam("num") ? intval($this->getParam("num")) : 15;
		$start  = ($page - 1) * $num;

		$data = array();

		$users = new Account($uid);
		$data = $users->getAllBalanceByUid($uid);

		$total = count($data);

		$this->assign("list", $data);
		$this->assign("total", $total);

		$this->display("include/header.html", "user/account_list.html", "include/footer.html");
	}

	public function addAccountAction()
	{
		if (in_array($_SERVER['SERVER_NAME'], ['admin.putaofenxiang.com'])) {
			die("线上不开放此功能");
		}
		$uid  		= trim($this->getParam('uid', ''));
		$currency 	= trim($this->getParam('currency', ''));
		$balance	= trim($this->getParam('balance', ''));
		$edit	= trim($this->getParam('edit', ''));

		if (!empty($_POST)) {
			$users = new Account($uid);
			$users->updateAccount($uid, $currency, $balance, $edit);

			Util::jumpMsg("添加成功!", "/user/accountList?uid=$uid");
			exit;
		}

		$this->assign("uid", $uid);
		$this->assign("currency", $currency);
		$this->assign("balance", $balance);
		$this->display("include/header.html", "user/add_account.html", "include/footer.html");
	}

}