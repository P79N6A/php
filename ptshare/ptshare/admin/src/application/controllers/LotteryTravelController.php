<?php
class LotteryTravelController extends BaseController
{
	public function listAction()
	{
		$uid		= trim($this->getParam('uid', ''));
		$name		= trim($this->getParam('name', ''));
		$award	 	= trim($this->getParam('award', ''));
		$end_time 	= trim($this->getParam('end_time', ''));
		$page       = $this->getParam("page") ? intval($this->getParam("page")) : 1;
		$num        = $this->getParam("num") ? intval($this->getParam("num")) : 20;
		$start  = ($page - 1) * $num;

		$data = array();

		$lottery = new LotteryTravel();
		list($data, $total) = $lottery->getList($start, $num, $name, $award);

		$mutipage = $this->mutipage($total, $page, $num, http_build_query(array()), "/lotteryTravel/list");

		$this->assign("page", $mutipage);
		$this->assign("list", $data);
		$this->assign("total", $total);

		$this->display("include/header.html", "lotterytravel/index.html", "include/footer.html");
	}

	public function plwAction()
	{
		$name = 'plw';
		$uid = '1030240';
		$token = '5205353d8e96f473e443e9e44e914b2ad65cdfdc';

		$data = file_get_contents("http://api.caipiaokong.com/lottery/?name=".$name."&format=json&uid=".$uid."&token=".$token."");

		//$data缓存
		$array = json_decode($data, true);

		$first_val = reset($array);

		$first_key = key($array);

		$last_plw = [
			'dateline' => $first_key,
			'opentime' => $first_val['dateline'],
			'number'   => str_replace(',', '', $first_val['number']),
		];

		print_r($last_plw);
		exit;
	}
	public function createAction()
	{
		$id  			= $this->getParam("id");
		if(!empty($_POST)) {
			$name 			= $this->getParam('name');
			$award  		= $this->getParam("award");
			$startime  		= $this->getParam("startime");
			$endtime  		= $this->getParam("endtime");
			$total 			= $this->getParam("total");
			$num  			= $this->getParam("num");

			$list_result = ShareClient::uploadImage(urlencode(file_get_contents($_FILES['list_cover']['tmp_name'])));
			if (!isset($list_result['url']) || empty($list_result['url'])) {
                Util::jumpMsg('上传列表封面图片失败', "/lotteryTravel/create");
            }

            $gate_result = ShareClient::uploadImage(urlencode(file_get_contents($_FILES['gate_cover']['tmp_name'])));
            if (!isset($gate_result['url']) || empty($gate_result['url'])) {
            	Util::jumpMsg('上传入口封面图片失败', "/lotteryTravel/create");
            }

            $detail_result = ShareClient::uploadImage(urlencode(file_get_contents($_FILES['detail_cover']['tmp_name'])));
            if (!isset($detail_result['url']) || empty($detail_result['url'])) {
            	Util::jumpMsg('上传详情封面图片失败', "/lotteryTravel/create");
            }

            $share_result = ShareClient::uploadImage(urlencode(file_get_contents($_FILES['share_cover']['tmp_name'])));
            if (!isset($share_result['url']) || empty($share_result['url'])) {
            	Util::jumpMsg('上传分享封面图片失败', "/lotteryTravel/create");
            }

            $list_cover        = $list_result['url'];
            $detail_cover      = $detail_result['url'];
            $gate_cover        = $gate_result['url'];
            $share_cover       = $share_result['url'];
            if (empty($id)) {
	            //$fileUrl        = Util::getURLPath($result['url']);

            	if (empty($list_cover)) {
					Util::jumpMsg("请选择一张列表封面图", '/lotteryTravel/create');
				}

				if (empty($list_cover)) {
					Util::jumpMsg("请选择一张详情封面图", '/lotteryTravel/create');
				}

				if (empty($list_cover)) {
					Util::jumpMsg("请选择一张入口封面图", '/lotteryTravel/create');
				}

				if (empty($list_cover)) {
					Util::jumpMsg("请选择一张分享封面图", '/lotteryTravel/create');
				}

				if (empty($name)) {
					Util::jumpMsg("请填写活动名称", '/lotteryTravel/create');
				}

				if (empty($award)) {
					Util::jumpMsg("请填写奖品信息", '/lotteryTravel/create');
				}

				if (empty($total)) {
					Util::jumpMsg("请填写需要多少份组队", '/lotteryTravel/create');
				}

				if (empty($num)) {
					Util::jumpMsg("请填写每队人数", '/lotteryTravel/create');
				}
            }
			$lottery_travel = new LotteryTravel();

			try{

				if (!empty($id)) {
					$lottery_travel->updateTravel($id, $name, $award, $list_cover, $total, $num, $startime, $endtime, $detail_cover, $share_cover, $gate_cover);

					try {
		            	ShareClient::updateLotterTravel($id);
			        } catch (Exception $exception) {
			            Util::jumpMsg($exception->getMessage(), "/lotteryTravel/create?id=".$id);
			        }
				} else {
					$travel_id = $lottery_travel->createTravel($name, $award, $list_cover, $total, $num, $startime, $endtime, $detail_cover, $share_cover, $gate_cover);
				}

			} catch (Exception $e) {
				Util::jumpMsg("修改出错!" . $e->getCode() . "-" . $e->getMessage());
			}

			$operate = new Operate();
			if (!empty($id)) {
				Util::jumpMsg("修改活动成功", '/lotteryTravel/list');
				$operate->Add($this->adminid, 'updateLottery', 0, 0, [$travel_id, 'N'], "", "$travel_id", 1);
			} else {
				Util::jumpMsg("创建活动成功id是:{$travel_id}", '/lotteryTravel/list');
				$operate->Add($this->adminid, 'createLottery', 0, 0, [$travel_id, 'N'], "", "$travel_id", 1);
			}
			exit;
		}
		$id  			= $this->getParam("id");
		$lottery_travel = new LotteryTravel();
		$info = $lottery_travel->getInfo($id);
		$this->assign('data', $info);

		$this->display("include/header.html", "lotterytravel/add.html", "include/footer.html");
	}

	public function viewAction()
	{
		$id  			= $this->getParam("id");

		if (empty($id)) {
			Util::jumpMsg("缺少活动id参数", '/lotteryTravel/list');
		}
		$lotterylog = new LotteryTravelLog();
		$this->assign('data', $lotterylog->getListByTravelid($id));

		$lottery = new LotteryTravel();
		$info = $lottery->getInfo($id);
		$this->assign('lottery', $info);
		$remark = json_decode($info['remark'], true);
		$remark['yushu'] = $remark['sum'] % $info['total'];
		$this->assign('remark', $remark);

		$this->display("include/header.html", "lotterytravel/view.html", "include/footer.html");
	}


	public function groupAction()
	{
		$id  		= $this->getParam("id");
		$uid		= trim($this->getParam('uid', ''));
		$code		= trim($this->getParam('code', ''));
		$isfinish	= trim($this->getParam('isfinish', ''));
		$page       = $this->getParam("page") ? intval($this->getParam("page")) : 1;
		$num        = $this->getParam("num") ? intval($this->getParam("num")) : 20;
		$start  = ($page - 1) * $num;

		if (empty($id)) {
			Util::jumpMsg("缺少活动id参数", '/lotteryTravel/list');
		}
		$data = array();

		$group = new LotteryTravelGroup();
		list($data, $total) = $group->getList($start, $num, $code, $uid, $id, $isfinish);

		if (!empty($data)) {
			foreach ($data as &$item) {

				if ($item['isfinish'] == 'Y') {
					$item['finish_note'] = "组队完成";
				} else {
					$item['finish_note'] = "组队未完成";
				}
			}
		}

		$mutipage = $this->mutipage($total, $page, $num, http_build_query(array("id" => $id, "uid" => $uid)), "/lotteryTravel/group");

		$this->assign("page", $mutipage);
		$this->assign("list", $data);
		$this->assign("total", $total);
		$this->assign("id", $id);

		$this->display("include/header.html", "lotterytravel/group.html", "include/footer.html");
	}


}