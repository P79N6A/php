<?php
class MessageController extends BaseController
{
	public function listAction()
	{
		$uid		= trim($this->getParam('uid', ''));
		$orderid	= trim($this->getParam('orderid', ''));
		$status 	= trim($this->getParam('status', '-1'));
		$end_time 	= trim($this->getParam('end_time', ''));
		$page       = $this->getParam("page") ? intval($this->getParam("page")) : 1;
		$num        = $this->getParam("num") ? intval($this->getParam("num")) : 20;
		$start  = ($page - 1) * $num;

		$data = array();

		$message = new Message();
		list($data, $total) = $message->getList($start, $num, $uid, $orderid, $status);


		$mutipage = $this->mutipage($total, $page, $num, http_build_query(array()), "/message/list");

		$this->assign("page", $mutipage);
		$this->assign("list", $data);

		$this->display("include/header.html", "message/index.html", "include/footer.html");
	}


	public function addAction()
	{
		if ($_POST) {
			$title		= trim($this->getParam('title', ''));
			$content 	= trim($this->getParam('content', ''));
			$type 		= trim($this->getParam('type', '1'));
			if (empty($title)) {
				Util::jumpMsg("请填写标题", '/message/add');
			}


			if (empty($content)) {
				Util::jumpMsg("请填写内容", '/message/add');
			}

			$message = new Message();
			$id = $message->addNotice($title, $content ,$this->adminid, $type);

			Util::jumpMsg("创建消息成功id是:{$id}", '/message/list');
			exit;
		} else {
			$this->display("include/header.html", "message/add.html", "include/footer.html");
		}

	}

	public function sendAction()
	{
		$id		= trim($this->getParam('id', ''));

		$message = new Message();
		$info = $message->getInfo($id);

	}
}