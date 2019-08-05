<?php
class AccountController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function teAction()
    {

    	$goods_model = new Goods();
    	$goods_model->syncGoodsAndPackage(1);

    	var_dump("2");
    }
    public function tranAction()
    {
		$this->setAuthId('AUTH_ACCOUNT_LIST');

		if($_POST){
			$uid         = $this->getParam("uid")? trim(strip_tags($this->getParam("uid"))): '';
			$amount      = $this->getParam("amount")? trim(strip_tags($this->getParam("amount"))): '';
			$title      = $this->getParam("title")? trim(strip_tags($this->getParam("title"))): '';

			Interceptor::ensureNotEmpty($uid, ERROR_PARAM_IS_EMPTY, "uid");
			Interceptor::ensureNotEmpty($amount, ERROR_PARAM_IS_EMPTY, "amount");

			//if($amount <= 10000){ //小于等于1w就直接转入
			if(true){ //此功能关闭. 不再直接转入, 全由财务审核.
				try {
					//require_once('/home/xubaoguo/work/client/ShareClient.php');
					$res = ShareClient::activeTransfer('后台转账', $amount, $uid);
				} catch (Exception $e) {
					Util::showError($e->getCode() . "-". $e->getMessage());
				}

				if(!empty($res)){
					try{
						$account=new AccountManage();
						$account->add($this->adminid, $title . "转账orderid:". $res['orderid'], 1000, $uid, 1, $amount);
						$admin      = new Admin();
						$admin_info = $admin->getAdminInfo($this->adminid);
// 						require('PHPMailer/Mailer.php');
// 						$mail=new Mailer('smtp.ym.163.com', 'xubaoguo@ptshare.vip', 'xubaoguo1985');
// 						@$mail->send_yjj( array(
// 							'zengqiang@ptshare.vip',
// 							'liuchenguang@ptshare.vip',
// 						), $title, "系统帐号1000转入活动帐号$uid, 葡萄:$amount, 操作人:{$admin_info['name']}");

					}  catch (Exception $e) {
						Util::showError($e->getMessage());
					}
				} else {

					Util::showError($res['errmsg']);
				}

			} else {
				try{
					$account=new AccountManage();
					$account->add($this->adminid, $title, 1000, $uid, 0, $amount);

				}  catch (Exception $e) {
					Util::showError($e->getMessage());
				}
			}

			Util::jumpMsg("转账成功!", "/account/list",1);

		} else {
			$uid = $this->getParam("uid");
// 			var_dump($uid);
// 			var_dump($uid<10000);

			$account = new AccountManage();
			$list = $account->getList(0, 100);
			$uids = [];

			foreach ($list as $user) {
				$uids[] = $user['uid'];
			}
			Interceptor::ensureNotFalse(in_array($uid, $uids), ERROR_PARAM_IS_EMPTY, "uid");
			$this->display("include/header.html", "account/tran.html", "include/footer.html");
		}

    }

	public function tranCashAction()
    {
		$this->setAuthId('AUTH_ACCOUNT_LIST');

		if($_POST){
			$uid         = $this->getParam("uid")? trim(strip_tags($this->getParam("uid"))): '';
			$amount      = $this->getParam("amount")? trim(strip_tags($this->getParam("amount"))): '';
			$title      = $this->getParam("title")? trim(strip_tags($this->getParam("title"))): '';

			Interceptor::ensureNotEmpty($uid, ERROR_PARAM_IS_EMPTY, "uid");
			Interceptor::ensureNotEmpty($amount, ERROR_PARAM_IS_EMPTY, "amount");

			try{
				$account=new AccountManage();
				$account->add($this->adminid, $title, 1000, $uid, 0, $amount);

			}  catch (Exception $e) {
				Util::showError($e->getMessage());
			}

			Util::jumpMsg("添加成功, 请等待财务审核!", "/account/list/",1);

		} else {
			$uid = $this->getParam("uid");
			Interceptor::ensureNotFalse($uid<10000, ERROR_PARAM_IS_EMPTY, "uid");
			$this->display("include/header.html", "account/tran_cash.html", "include/footer.html");
		}

    }

	public function listAction()
    {
		$this->setAuthId('AUTH_ACCOUNT_LIST');

        $page = $this->getParam("page", 1);
        $num = $this->getParam("num", 50);

        $account = new AccountManage();
        $list = $account->getList(0, $num);
//print_r($list);


		foreach($list as $key => $value){
			//var_dump(1);
			$account = new AccountManage($value['uid']);
			//var_dump(2);
			$data = $account->getAllBalanceByUid($value['uid']);
			//var_dump(3);
			$JournalReadonlyBeta = new JournalReadonlyBeta($value['uid']);
			//var_dump(4);
			$sum  = $JournalReadonlyBeta->getSumUidJournal($value['uid'], 15, 2, 'IN');
			$list[$key]['sum'] = $sum;
			//var_dump(5);
			$current_month = date('Y-m', time()); //本月
			$pre_month     = date('Y-m', strtotime('-1 month')); //上月
			$pre_sum  = $JournalReadonlyBeta->getSumUidJournal($value['uid'], 15, 2, 'IN', "{$pre_month}-01 00:00:00", "{$current_month}-01 00:00:00");
			$list[$key]['pre_sum'] = $pre_sum;

			$current_month = date('Y-m', time()); //本月
			$now     = date('Y-m-d h:i:s', time()); //上月
			$current_sum  = $JournalReadonlyBeta->getSumUidJournal($value['uid'], 15, 2, 'IN', "{$current_month}-01 00:00:00", $now);
			$list[$key]['current_sum'] = $current_sum;

			foreach($data as $key1 => $value1){
				if($value1['currency'] == 1){
					$list[$key]['amount2'] = $value1['balance'];
				}
				if($value1['currency'] == 2){
					$list[$key]['amount'] = $value1['balance'];
				}
				if($value1['currency'] == 3){
					$list[$key]['amount1'] = $value1['balance'];
				}
			}
        }

        $mutipage = $this->mutipage(count($list), $page, $num, '', "/account/list/");

        $this->assign(array('list'=>$list, 'mutipage'=>$mutipage));

        $this->display("include/header2.html", "account/list.html", "include/footer.html");

    }

	public function applylistAction()
    {
		$this->setAuthId('AUTH_ACCOUNT_APPLYLIST');

		$id = $this->getParam("id");
		if($id){
			$account = new AccountManage();
			$data = $account->getApplyId($id);
			$res = ShareClient::activeTransfer('Yjjfdasdfasf2312sdyuty43', $data['amount'], $data['targetid']);
			if(!$res){
				try{
					$account=new AccountManage();
					$account->update($id);

					$admin      = new Admin();
					$admin_info = $admin->getAdminInfo($this->adminid);
					require('PHPMailer/Mailer.php');
					$mail=new Mailer('smtp.exmail.qq.com', 'system@dreamlive.com', 'System12345');
					@$mail->send_yjj( array(
						'fangqin@dreamlive.com',
						'stevewu@dreamlive.com',
						'zhouqin@dreamlive.com',
						'hujingjing@dreamlive.com',
						'chenjun@dreamlive.com',
						'liuchenguang@dreamlive.com',
						'yujinjia@dreamlive.com'
					), "系统帐号1000转入活动帐号{$data['targetid']}", "系统帐号1000转入活动帐号{$data['targetid']}, 数量:{$data['amount']}, 原因: {$data['title']}. 操作人:{$admin_info['name']}");

				}  catch (Exception $e) {
					Util::showError($e->getMessage());
				}
				Util::jumpMsg("添加成功!", "/account/applylist/",1);
			} else {

				Util::showError($res['errmsg']);
			}

		} else {
			$page = $this->getParam("page", 1);
			$num   = $this->getParam("num", 50);
			$begin_time = $this->getParam("begin_time", '1970-01-01 00:00:00');
			$end_time = $this->getParam("end_time", date("Y-m-d H:i:s"));
			$uid = $this->getParam("uid", 0);
			if($uid){
				$status = 1;
			}
			$search['begin_time'] = $begin_time;
			$search['end_time'] = $end_time;
			$search['uid'] = $uid;
			$start = ($page - 1) * $num;
			$account = new AccountManage();
			$list = $account->getApplyListWhere($uid, $begin_time, $end_time, $status, $start, $num);
			$total = $account->getTotalWhere($uid, $begin_time, $end_time, $status);
			$sum = $account->getSumWhere($uid, $begin_time, $end_time, $status);
			$account = new AccountManage(1000);

			$mutipage = $this->mutipage($total, $page, $num, http_build_query($search), "/account/applylist/");

			$this->assign(array('list'=>$list, 'mutipage'=>$mutipage, 'search'=>$search, 'sum'=>$sum));

			$this->display("include/header2.html", "account/applylist.html", "include/footer.html");
		}

    }


	public function searchlistAction()
    {
		$page = $this->getParam("page", 1);
        $num   = $this->getParam("num", 50);
		$uid   = $this->getParam("uid")? trim(strip_tags($this->getParam("uid"))): '';
		$orderid =$this->getParam("orderid")? trim(strip_tags($this->getParam("orderid"))): '';

        $start = ($page - 1) * $num;

        $deposit = new Deposit();
        $list = $deposit->getListByUidOrderid($uid,$orderid,$start, $num);

        $mutipage = $this->mutipage(99999, $page, $num, '', "/deposit/searchlist/");

        $this->assign(array('list'=>$list, 'mutipage'=>$mutipage));
		$this->assign('search',array('uid'=>$uid,'orderid'=>$orderid));

        $this->display("include/header2.html", "deposit/searchlist.html", "include/footer.html");

    }
    /**
     * 币种互转
     */
    public function currencyChangeAddAction(){
        $this->setAuthId('AUTH_ACCOUNT_CURRENCYCHANGELIST');

        $currency   = array(
            1       => '星票',
            2       => '星钻',
            3       => '现金',
            4       => '星光',
            5       => '星星'
        );
        $this -> assign('currency',$currency);

        $this -> display("include/header.html", "account/currencychangeadd.html", "include/footer.html");
    }
    public function currencyChangeSaveAction(){
        $uid            = (int)$this->getParam('uid',0);
        $out_currency   = (int)$this->getParam('out_currency',2);
        $amount         = (int)$this->getParam('amount',0);
        $in_currency    = (int)$this->getParam('in_currency',4);

        $currency_change = new CurrencyChange();
        try{
            ShareClient::diamondTransferStar($uid,'Yjjfdasdfasf2312s43ddyuty4313',$amount);
            $currency_change->addInfo($this->adminid,$uid,$out_currency,$amount,$in_currency);
            Util::jumpMsg("添加信息成功", "/account/currencyChangeList", 3);
        }catch (Exception $e){
            Util::jumpMsg("添加信息失败:{$e->getCode()}:{$e->getMessage()}", "/account/currencyChangeAdd", 3);
        }

    }
    public function currencyChangeListAction(){
        $this->setAuthId('AUTH_ACCOUNT_CURRENCYCHANGELIST');

        $currency   = array(
            1       => '星票',
            2       => '星钻',
            3       => '现金',
            4       => '星光',
            5       => '星星'
        );
        $page = $this->getParam("page", 1);
        $num   = $this->getParam("num", 20);
        $uid    = (int)$this->getParam('uid','');
        $start = ($page - 1) * $num;



        $currency_change   = new CurrencyChange();
        $info       = $currency_change->getInfos($uid,$start,$num);

        $total      = $currency_change->getTotal($uid);

        if($info){
            $admin  = new Admin();
            $user   = new User('passport');
            foreach($info as &$v){
                $admin_info = $admin->getAdminInfo($v['adminid']);
                $v['admin_info']    = $admin_info['name'];
                $user_info  = $user->getUserInfo($v['uid']);
                $v['nickname']     = $user_info['nickname'];
            }
        }

        $mutipage = $this->mutipage($total, $page, $num, http_build_query(array('uid'=>$uid)), "/account/currencychangelist");

        if($uid>0)$this -> assign('uid',$uid);
        $this -> assign('info',$info);
        $this -> assign('currency',$currency);
        $this -> assign('mutipage',$mutipage);

        $this -> display("include/header2.html", "account/currencychangelist.html", "include/footer.html");
    }
}
