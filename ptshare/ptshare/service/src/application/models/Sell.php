<?php
class Sell
{

    protected $daoSell;
    protected $daoGoods;
    protected $daoOrders;
    protected $daoPackage;
    protected $modelPackage;
    protected $modelCategory;

    public function __construct()
    {
        $this->daoSell = new DAOSell();
        $this->daoGoods = new DAOGoods();
        $this->daoPackage = new DAOPackage();
        $this->daoOrders = new DAOOrders();
        $this->modelPackage = new Package();

    }

    // 添加分享闲置
    public function add($userid, $categoryid, $total_grape, $description, $contact, $free, $goods_draft, $previews, $extends, $cardid = 0, $grape_forward = 1,$sales_type = 0)
    {

        $sell_num   = IDGEN::generate(IDGEN::SELLID);
        $sn         = SnowFlake::nextId();

        try{

            $this->daoSell->startTrans();

            $cover      = '';
            $cover_type = 'image';
            $previews       = json_decode($previews, true);

            foreach ($previews as $preview)
            {
                if ($preview['file']) {
                    $cover      = Util::getURLPath($preview['file']);
//                    $cover_type = $preview['type'];
                    break;
                }
            }

            // 添加 goods
            $goods_drafts = json_decode($goods_draft, true);
            $num = count($goods_drafts);
            // 添加 sell
            $sellid = $this->daoSell->add($sell_num, $sn, $userid, $cover, $cover_type, $categoryid, $description, $num, $free, $contact, $extends, $cardid, $grape_forward, $sales_type);

            // 添加缩略
            $modelPreview   = new PreviewResource();

            foreach ($previews as $preview)
            {
                $type = isset($preview['type']) ? $preview['type'] : 'image';
                $modelPreview->addSell($type, $sellid, 1, Util::getURLPath($preview['file']));
            }

            // 总额除以总个数，直接取整，获取单价
            $grape = round($total_grape / $num);


            foreach ($goods_drafts as $goods_draft)
            {
                $file        = Util::getURLPath($goods_draft['file']);

                $good_desc   = (isset($goods_draft['description']) && !empty($goods_draft['description'])) ? $goods_draft['description'] : '';
                $categoryid  = isset($goods_draft['cateid']) ? $goods_draft['cateid'] : 0;
                $type        = isset($goods_draft['type']) ? $goods_draft['type'] : 'image';
                $labels      = (isset($goods_draft['labels']) && !empty($goods_draft['labels'])) ? json_encode($goods_draft['labels']) : '';
                $extends     = (isset($goods_draft['extends']) && !empty($goods_draft['extends'])) ? json_encode($goods_draft['extends']) : '';
//                $grape       = 20;

//                // 查找分类对应的价格信息
//                $grape = 0;
//                $categoryInfo = $this->modelCategory->getCategoryByCid($goods_draft['cateid']);
//                if ($categoryInfo && isset($categoryInfo['grape']) && $categoryInfo['grape']) {
//                    $grape = $categoryInfo['grape'];
//                }

                $this->daoGoods->add($sellid, $labels, $good_desc, $grape, $categoryid, $type, $file, $extends);
            }

            $this->daoSell->commit();

            // 执行任务
            try {
            	Task::execute($userid, Task::TASK_ID_TEN, 1, json_encode(array('grape'=>$grape)));
            } catch (Exception $exception) {
            	Logger::log('mall_sell', 'task excute1',  ["code" => $exception->getCode(), "msg" => $exception->getMessage()]);
            }

            // 发放任务奖励
            try {
                Task::execute($userid, 4, 1);
            } catch (Exception $exception) {
            	Logger::log('mall_sell', 'task excute2',  ["code" => $exception->getCode(), "msg" => $exception->getMessage()]);
            }

            // 推送捐赠成功消息
            $wxProgram = new WxProgram();

            $languageConfig = Context::getConfig("SYSTERM_COMMON_LANGUAGE");
            $remark = $languageConfig['wx_program_template_msg'][WxProgram::TYPE_SELL_ADD_SUCCESS]['remark'];

            $contact = json_decode($contact, true);

            $data = [
                Order::getOrderId(),
                $description,
                $total_grape,
                DAOSell::getStatusData(DAOSell::STATUS_WAIT_ONLINE_AUDIT),
                $contact['contact_city'].$contact['contact_county'].$contact['contact_address'],
                $remark,
            ];
            $wxProgram->sendTemplateMessage($userid, WxProgram::TYPE_SELL_ADD_SUCCESS, $data);

            // 微信群推送
            $this->sendQyWxNotice();

            return $sellid;

        } catch (Exception $exception){

            $this->daoSell->rollback();
            Logger::log('sell_log', 'add 1',  array("param" => json_encode(func_get_args()), "code" => $exception->getCode(), "msg" => $exception->getMessage()));

            throw new BizException(ERROR_MALL_SELL_ADD);
        }

    }

    /**
     * @param $id
     * @desc 更新状态为待发出
     * 更新 sell 表状态
     * 生成 package 数据
     * 生成 order 表信息
     */
    public function setSellWaitSendOut($id, $type, $vip)
    {

        try{

            $this->daoSell->startTrans();

            $sellDetail = $this->daoSell->getOneById($id);

            // 判断条件
            // 非本人订单
            // 订单状态非待确认
            Interceptor::ensureNotEmpty($sellDetail, ERROR_PARAM_INVALID_FORMAT, 'id');
            Interceptor::ensureNotFalse(($sellDetail['status'] == DAOSell::STATUS_WAIT_ONLINE_AUDIT), ERROR_PARAM_INVALID_FORMAT, 'status');

            // 更新状态
            $effectRow = $this->daoSell->setStatus($id, $sellDetail['uid'], $sellDetail['status'],  DAOSell::STATUS_WAIT_SENT, $type, $vip);
            Interceptor::ensureNotFalse(($effectRow > 0 && is_numeric($effectRow)), ERROR_PARAM_INVALID_FORMAT, 'update sell status error');

            // 是否没有审核通过宝贝
            $canCreatePackage = $this->daoGoods->getCanCreatePackageTotalBySellId($id);
            Interceptor::ensureNotEmpty($canCreatePackage, ERROR_PARAM_INVALID_FORMAT, 'please audit goods first');

            // 生成 package
            $this->modelPackage->addBySellId($sellDetail['id']);

            $this->daoSell->commit();

        } catch (Exception $exception){
            $this->daoSell->rollback();
            Logger::log('sell_log', 'setSellWaitSendOut 1',  array("id" => $id, "code" => $exception->getCode(), "msg" => $exception->getMessage()));

            throw new BizException(ERROR_MALL_SELL_ACCEPT, $id.$exception->getMessage());
        }

        // 获取总葡萄数
        $totalGrapes = $this->daoGoods->getGrapeBySellId($id);

        try {
            $this->daoOrders->startTrans();

            // 先发钱
            Account::distribute($sellDetail['uid'], $totalGrapes['show_grape'], '分享成功过审，发放奖励');

            // 再冻结
            Account::freeze($sellDetail['uid'], $totalGrapes['show_grape'], $id, '租用未完成，暂时冻结');

            $this->daoOrders->commit();

            // 发送Message
            // 您分享的宝贝已经通过线上审核，总定价50葡萄，分享成功后，。
            //（其中%s件宝贝审核未通过，原因：%s）

            $resons = $this->daoGoods->getRefuseReasonListBySellid($id);

            $refuse_message_str = "";
            if (!empty($resons)) {
            	$refuse_total = count($resons);

            	$reason_array = [];
            	foreach ($resons as $re) {
            		if (!empty($re['refuse_reason'])) {
            			$reason_array[] = $re['refuse_reason'];
            		}
            	}
				if (!empty($reason_array)) {
	            	$refuse_str = implode(';', $reason_array);
	            	$refuse_message_str = "（其中{$refuse_total}件宝贝审核未通过，原因：{$refuse_str}）";
				}
			}
            $modelMessage = new Message($sellDetail['uid']);
            $modelMessage->sendMessage(DAOMessage::TYPE_SELL_AUDIT_SUCCESS, [$refuse_message_str, $totalGrapes['show_grape']]);

        }catch (Exception $exception) {

            $this->daoOrders->rollback();

            Logger::log('sell_log', 'setSellWaitSendOut2',  array("id" => $id, "code" => $exception->getCode(), "msg" => $exception->getMessage()));


        }

    }

    /**
     * @param $id
     * @param string $remark
     * @return mixed
     * @desc 标记 sell 状态为已发货
     * 标记 order 表已发货
     */
    public function setStatusSendOut($id)
    {

        try {

            $sellDetail = $this->daoSell->getOneById($id);
            Interceptor::ensureNotEmpty($sellDetail, ERROR_PARAM_INVALID_FORMAT, "sell detail");
            Logger::log('sell_log', 'setStatusSendOut',  array("id" => $id, "info" => json_encode($sellDetail)));
            if ($sellDetail['status'] == DAOSell::STATUS_SENT_OUT) {
                // 已经发出了
                return true;
            }

            // 更新 sell 表状态
            $this->daoSell->setSellStatus($id, DAOSell::STATUS_SENT_OUT, '发货成功');

            // 获取总葡萄数
            $totalGrapes = $this->daoGoods->getGrapeBySellId($id);

            $modelMessage = new Message($sellDetail['uid']);
            $modelMessage->sendMessage(DAOMessage::TYPE_SELL_SENT_OUT, [$totalGrapes['show_grape']]);

            return true;

        } catch (Exception $exception) {

        	Logger::log('sell_log', 'setStatusSendOut',  array("id" => $id, "code" => $exception->getCode(), "msg" => $exception->getMessage()));

            throw new BizException(ERROR_BIZ_ORDER_EXPRESS_STATUS_UNVALID, $id.$exception->getMessage());
        }

    }

    /**
     * @param $id
     * @throws BizException
     * @desc 宝贝被租用
     * 更新订单状态
     * 更新 sell 表状态
     * 发放奖励金额
     */
    public function setSuccess($id)
    {
        $sellDetail = $this->daoSell->getOneById($id);

        Interceptor::ensureNotEmpty($sellDetail, ERROR_PARAM_INVALID_FORMAT, "sell detail");
        if ($sellDetail['status'] == DAOSell::STATUS_SUCCESS) {
            // 已经成功
            return true;
        }
        Interceptor::ensureNotFalse(($sellDetail['status'] == DAOSell::STATUS_SENT_OUT), ERROR_PARAM_INVALID_FORMAT, $sellDetail['status']);


        try {
            // 更新 sell 状态
            $this->daoSell->setStatus($id, $sellDetail['uid'], $sellDetail['status'], DAOSell::STATUS_SUCCESS);
        } catch (Exception $exception) {
        	Logger::log('sell_log', 'setSuccess step one1',  array("id" => $id, "code" => $exception->getCode(), "msg" => $exception->getMessage()));
            throw new BizException(ERROR_MALL_SELL_SUCCESS, $exception->getMessage());
        }

        // 获取总葡萄数
        $totalGrapes = $this->daoGoods->getGrapeBySellId($id);

        try {
            // 解冻
            //$this->daoOrders->startTrans();
            Account::unfreeze($sellDetail['uid'], $totalGrapes['show_grape'], $sellDetail['id'], '分享的宝贝已被成功租用');

            //TODO
            if ($sellDetail['grape_forward'] == DAOPackage::FORWARD_AWARD) {
            	//吸粉赠葡萄
            	Account::distributeAward($sellDetail['uid'], $totalGrapes['show_grape'], '葡萄账户转奖励账户钱');
            	Attract::join($sellDetail['uid']);
            }

            $modelMessage = new Message($sellDetail['uid']);
            $modelMessage->sendMessage(DAOMessage::TYPE_SELL_RECIVE, [$totalGrapes['show_grape']]);
            //$this->daoOrders->commit();

        } catch (Exception $exception){

            //$this->daoOrders->rollback();

            Logger::log('sell_log', 'setSuccess step2',  array("id" => $id, "code" => $exception->getCode(), "msg" => $exception->getMessage()));
        }
        return true;

    }


    // 适用于前端，用户中心列表查看
    public function getList($userid, $type, $limit, $offset)
    {

        $allowType = [
            'audit'    => [
                DAOSell::STATUS_WAIT_ONLINE_AUDIT,
//                DAOSell::STATUS_WAIT_CONFIRM
            ],
            'wait_sent'    => [
                DAOSell::STATUS_WAIT_SENT
            ],
            'sent' => [
                DAOSell::STATUS_SENT_OUT
            ],
            'finish'    => [
                DAOSell::STATUS_SUCCESS,            //成功
                DAOSell::STATUS_ONLINE_AUDIT_ERROR, //线上审核失败
                DAOSell::STATUS_EXPRESS_PICK_ERROR, //快递取件失败
                DAOSell::STATUS_OFFLINE_AUDIT_ERROR,//线下审核失败
                DAOSell::STATUS_OFFLINE_BACK_ERROR, //线下审核退回
                DAOSell::STATUS_USER_AUDIT_CANCEL,  //用户审核过程取消
                DAOSell::STATUS_USER_EXPRESS_CANCEL,//快递取件过程取消
                DAOSell::STATUS_AWARD_ERROR,        //奖励失败
            ]
        ];

        $statusArr = isset($allowType[$type]) ? $allowType[$type] : $allowType['audit'];
        $result = $this->daoSell->getList($userid, $statusArr, $limit, $offset);

        if (!empty($result['list'])) {
            $sellids = Util::arrayToIds($result['list']);
            $grapeList = $this->daoGoods->getGrapeBySellIds($sellids);
            $grapeList = Util::arrayToKey($grapeList, 'sellid');
            foreach ($result['list'] as $key => $value)
            {
                if (isset($grapeList[$value['id']])) {
                    $result['list'][$key]['show_grape'] = $grapeList[$value['id']]['show_grape'];
                }
                $result['list'][$key]['cover'] = Util::joinStaticDomain($value['cover']);
            }
        }

        return $result;
    }

	public function setErrorMessage($sellid)
	{
		$sellDetail = $this->daoSell->getOneById($sellid);
		$resons = $this->daoGoods->getRefuseReasonListBySellid($sellid);

        $refuse_message_str = "";
        if (!empty($resons)) {
            $refuse_total = count($resons);

            $reason_array = [];
            foreach ($resons as $re) {
                if (!empty($re['refuse_reason'])) {
                    $reason_array[] = $re['refuse_reason'];
                }
            }
			if (!empty($reason_array)) {
            	$refuse_message_str = implode(';', $reason_array);
			}
        }
        $modelMessage = new Message($sellDetail['uid']);
        $modelMessage->sendMessage(DAOMessage::TYPE_SELL_AUDIT_REFUSE_ERROR, [$refuse_message_str]);
		try {
			$result = $this->daoSell->setSellStatus($sellid, DAOSell::STATUS_ONLINE_AUDIT_ERROR, "线上审核失败");
		} catch (Exception $exception) {
			Logger::log('sell_log', 'setSellStatus',  array("id" => $id, "code" => $exception->getCode(), "msg" => $exception->getMessage()));
        	Interceptor::ensureNotFalse(($result > 0), ERROR_PARAM_INVALID_FORMAT, 'status' . $exception->getMessage());
		}
        return true;
	}

    /**
     * @param $id
     * @param $status
     * @param $remark
     * @return mixed
     * @throws BizException
     * @desc 更新状态，通用
     */
	public function setSellStatus($id, $status, $type, $vip, $remark='')
    {

        try{
            $statusArr = DAOSell::getStatusData();
            Interceptor::ensureNotFalse((isset($statusArr[$status])), ERROR_PARAM_INVALID_FORMAT, 'status');

            if ($status == DAOSell::STATUS_WAIT_SENT) {
            	$company_buyed = false;
            	if ($type == DAOSell::STATUS_COMPANY_BUYED) {//公司买断
            		$type = DAOSell::SELL_TYPE_BUY;
            		$company_buyed = true;
            	}
                $this->setSellWaitSendOut($id, $type, $vip);
                
                if ($company_buyed) {
                	$contact = array(
                			"contact_name"     => "曹林",
                			"contact_zipcode"  => "621000",
                			"contact_province" => "四川",
                			"contact_city"     => "绵阳市",
                			"contact_county"   => "涪城区",
                			"contact_address"  => "跃进路北段圣水一队106-13号",
                			"contact_national" => "CN",
                			"contact_phone"    => "13691284186"
                	);
                	$DAOPackage 	= new DAOPackage();
                	$packageInfo 	= $DAOPackage->getPackageInfoBySourceid($id);
                	try {
	                	//用公司帐号把东西买下来
	                	Buying::order(Account::ACCOUNT_GRAPE_PERI, $packageInfo['id'], $contact);
                	} catch (Exception $exception) {
                		Logger::log('sell_log', 'setSellStatus1',  array("id" => $id, "code" => $exception->getCode(), "msg" => $exception->getMessage()));
                	}
                }

            } elseif ($status == DAOSell::STATUS_SENT_OUT) {
                $this->setStatusSendOut($id);
            } elseif ($status == DAOSell::STATUS_SUCCESS) {
                $this->setSuccess($id);
            } elseif ($status == DAOSell::STATUS_ONLINE_AUDIT_ERROR) {
            	$this->setErrorMessage($id);
            } else {
                $result = $this->daoSell->setSellStatus($id, $status, $remark);
                Interceptor::ensureNotFalse(($result > 0), ERROR_PARAM_INVALID_FORMAT, 'status');
            }

            return $result;
        } catch (Exception $exception) {
            Logger::log('sell_log', 'setSellStatus2',  array("id" => $id, "code" => $exception->getCode(), "msg" => $exception->getMessage()));
            throw new BizException(ERROR_MALL_SELL_STATUS, $id.'-'.$status);
        }

    }



    // 获取用户可以执行取消的状态
    public function getUserCanCancelStatus()
    {
        return [
            DAOSell::STATUS_WAIT_ONLINE_AUDIT,
//            DAOSell::STATUS_WAIT_CONFIRM,
            DAOSell::STATUS_WAIT_SENT
        ];
    }

    // 检测用户是否可以执行取消
    public function checkUserCanCancel($currentStatus)
    {
        $allowCancelStatus = $this->getUserCanCancelStatus();
        if (!in_array($currentStatus, $allowCancelStatus)) {
            throw new BizException(ERROR_MALL_SELL_CANCEL, $currentStatus);
        }
    }

    // 针对于用户取消环节的不同状态过渡
    public function getTargetStatus($currentStatus)
    {
        switch ($currentStatus) {
            // 待审核 -> 用户取消
            case DAOSell::STATUS_WAIT_ONLINE_AUDIT:
                $returnStatus = DAOSell::STATUS_USER_AUDIT_CANCEL;
                break;

            // 待接收报价 -> 用户取消
//            case DAOSell::STATUS_WAIT_CONFIRM:
//                $returnStatus = DAOSell::STATUS_USER_AUDIT_CANCEL;
//                break;

            // 待上门取件 -> 取件过程取消
            case DAOSell::STATUS_WAIT_SENT:
                $returnStatus = DAOSell::STATUS_USER_EXPRESS_CANCEL;
                break;

            default :
                $returnStatus = $currentStatus;

        }

        return $returnStatus;
    }

    // 用户执行取消
    public function setUserCancel($id, $userid)
    {
        $sellDetail = $this->daoSell->getOneById($id);
        Interceptor::ensureNotEmpty($sellDetail, ERROR_PARAM_INVALID_FORMAT, 'sellid');
        Interceptor::ensureNotFalse($sellDetail['uid'] == $userid, ERROR_PARAM_INVALID_FORMAT, 'userid');

        try{

            // 查看当前状态，是否能够取消
            $this->checkUserCanCancel($sellDetail['status']);
            $targetStatus = $this->getTargetStatus($sellDetail['status']);

            // 下架 package
            $this->daoSell->startTrans();
            $effectRow = $this->daoSell->setStatus($id, $userid, $sellDetail['status'], $targetStatus);
            Interceptor::ensureNotFalse($effectRow > 0, ERROR_PARAM_INVALID_FORMAT, 'set sell status error');

            if ($sellDetail['status'] == DAOSell::STATUS_WAIT_SENT) {
                $packageInfo = $this->daoPackage->getInfoBySourceIdFromSell($sellDetail['id']);
                $result = $this->daoPackage->setStatusOffLineById($packageInfo['id']);
                Interceptor::ensureNotFalse($result > 0, ERROR_PARAM_INVALID_FORMAT, 'set package offline error');
            }

            $this->daoSell->commit();

            return $effectRow;

        } catch (Exception $exception){
            $this->daoSell->rollback();

            $data = [
                'log'   => [
                    'id'            => $id,
                    'userid'        => $userid,
                ],
                'error_msg' => $exception->getMessage(),
            ];

            Logger::log('mall_sell', 'class='.__CLASS__.'_function='.__FUNCTION__,  [json_encode($data)]);

            throw new BizException(ERROR_MALL_SELL_CANCEL, $id);
        }

    }

    // 更新地址
    public function updateContact($id, $paramsContact)
    {
        $allowStatus = [
            DAOSell::STATUS_WAIT_ONLINE_AUDIT,
//            DAOSell::STATUS_WAIT_CONFIRM,
            DAOSell::STATUS_WAIT_SENT,
        ];

        $sellDetail = $this->daoSell->getOneById($id);
        if (!in_array($sellDetail['status'], $allowStatus)) {
            throw new BizException(ERROR_MALL_SELL_STATUS, $sellDetail['status']);
        }

        $res = $this->daoSell->updateContact($id, $paramsContact);
        if ($res < 1) {
            throw new BizException(ERROR_MALL_SELL_UPDATE_CONTACT, $id);
        }
    }

    public function sendQyWxNotice()
    {
        $environment = Context::getConfig("ENVIRONMENT");
        if ($environment != 'release') {
            return false;
        }

        $cache = new Cache('REDIS_CONF_SESSION');
        $cacheKey = "new_sell_qy_wx_notice";
        $token = $cache->get($cacheKey);

        if (empty($token)) {
            $corpId = "ww4c18a3927875f42e";
            $corpSecret = "eebZ_jceAE_fUVumgLFLG6t51D3aMWWqo9KOrTTMNjk";
            $getTokenUrl = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid={$corpId}&corpsecret={$corpSecret}";
            $result = Util::myCurl($getTokenUrl);
            // demo
            //{"errcode":0,"errmsg":"ok","access_token":"QnltfLfwor3ozBX-_4Nn6M7VmkT1al6QBMXeU_nTvGN3S_y96A6CgZIVDoc1Of5dStfGBKq3YBZkHMU0A_x0LRxwyb0TXDV1k_nn02h5b4rqTuxhn6i1ZddQS6wwbYNhG5SpKn8w53Q5p-tsyPyYiqQV8vO06UOI3a0RpI-_bjzqOXRqSoxM1C5b3-jFo5aMRXDQs2b1PDd3l11o9NRLKg","expires_in":7200}

            $data = json_decode($result, true);
            if (!isset($data['access_token']) || empty($data['access_token'])) {
                // 日志记录
                Logger::log('wechat_log', 'get_qy_wx_token_error',  [json_encode($data)]);
                return false;
            }

            $cache->add($cacheKey, $data['access_token'], 3600);
            $token = $data['access_token'];
        }

        // 创建群
//        $createQun = "https://qyapi.weixin.qq.com/cgi-bin/appchat/create?access_token={$token}";
//        $data = [
//            "access_token"  => $token,
//            "name"          => "用户新分享通知【通知】",
//            "owner"         => "ZengQiang",
//            "userlist"      => ["ZengQiang",
//                "LiuChenGuang",
//                "GaoXing",
//                "xiachunqi",
//                "HaoWangJiao",
//                "YangQing",
//                "LiXiXi",
//                "XiaXiaoTu",
//                "stanleywang",
//                "ZhangXiaoBei",
//                "YunLuTing",
//                "ShuangMu",
//                "TanXiaoBo",
//                "WangJiChao"
//            ],
//        ];
//
        $chatId = "15167842072428157564";
        // 推送消息
        $pushUrl = "https://qyapi.weixin.qq.com/cgi-bin/appchat/send?access_token={$token}";
        $header = [
            'content-type'=> 'application/json'
        ];
        $data = [
            "chatid" => $chatId,
            "msgtype" => 'text',
            "text"  => [
                'content'   => "有新用户发布分享，请相关同事及时处理！\n 处理完成后，在群里回复处理结果！！！",
            ],
            "safe" => 0
        ];
        Logger::log('wechat_log', 'send_qy_wx_notice_data',  [json_encode($data)]);
        $result = Util::myCurl($pushUrl, $header, $data, true);
        //var_dump($result);
        Logger::log('wechat_log', 'send_qy_wx_notice_result',  [json_encode($result)]);

    }






}