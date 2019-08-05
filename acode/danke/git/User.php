<?php
/**
 * Created by PhpStorm.
 * User: zhongming
 * Date: 2018/7/28
 * Time: 下午3:34
 */

namespace App\Logics\Pangu\MAPI\V3;

use API\ApiConstant;
use API\BusinessNumber;
use API\Exceptions\BadRequestException;
use API\Exceptions\RateLimitException;
use API\OutputMsg;
use App\Libs\ApiJavaServer\CouponServer;
use App\Logics\Pangu\MAPI\Service;
use App\Logics\Pangu\MAPI\Traits\Contract\CustomerContractChangeTrait;
use App\ServiceApi\SCM\Purchase\Entity\Task;
use App\ServiceApi\SCM\SuiteManage\Rooms\RoomServiceIf;
use App\Logics\Pangu\MAPI\Logic\UserMessageNotification\UserMessageNotification;
use CommonData\CustomerContractChangeData;
use CommonData\Steward;
use CustomerService\Repair\PasswordAuth;
use Carbon\Carbon;
use Vendor\Grant;
use Contract\BaseContract;

use Contract\CustomerContractChangeReasonSetting;
use Contract\CustomerContractChangeReason;
use App\Http\Controllers\User\CouponController;

use App\Logics\Pangu\MAPI\AbstractAPI;

// Services
use App\ServiceApi\ARM\Booking\Service\BookingServiceIf;
use App\ServiceApi\CRM\Task\Steward\Service\StewardServiceIf;
use App\ServiceApi\ARM\Contract\Customer\WithCustomerServiceIf;
use App\ServiceApi\CRM\Task\Cleaning\CleaningServiceIf;
use App\ServiceApi\CRM\Task\Repair\RepairServiceIf;
use App\ServiceApi\SCM\SuiteManage\Suites\SuiteServiceIf;
use App\ServiceApi\CSG\Location\Service\LocationServiceIf;
use App\ServiceCore\ARM\Contract\Customer\WithCustomerService;
use App\ServiceApi\CRM\Task\ContractChange\CustomerContractChangeServiceIf;
use App\ServiceApi\CSG\User\Service\UserServiceIf;

// Traits
use App\Logics\Pangu\MAPI\UtilTrait;
use App\Logics\Pangu\MAPI\Traits\Steward\CustomerStewardTrait;
use App\Logics\Pangu\MAPI\Traits\Contract\ContractChangeTrait;
use App\Logics\Pangu\MAPI\Traits\User\HumanTrait;
use App\Logics\Pangu\MAPI\Traits\Contract\WithCustomer01Trait;
use App\Logics\Pangu\MAPI\Traits\Room\RoomTrait;

use App\ServiceApi\CRM\Task\ContractChange\Entity\CustomerContractChange;
use App\Logics\Pangu\MAPI\Traits\Contract\ContractChangeExecutorBlockTrait;
use App\Logics\Pangu\MAPI\Contract\Change;
use FileStore\Image;
use App\Logics\Pangu\MAPI\Contract\WithCustomer;
use Contract\CustomerBooking;
use App\ServiceApi\ARM\Elecontract\EleContractServiceIf;
use App\ServiceApi\FMS\Payment\Service\RoomBillServiceIf;
use App\Logics\Pangu\CRM\Task\CustomerContractChange\SimulateClearingLogic;
use \App\ServiceCore\ARM\Elecontract\EleContractService;
use CommonData\EvaluateData;
use CustomerService\CustomerProposal;
class User extends AbstractAPI implements \API\Authenticate\User
{
    private $yuding_signed = false;//预定协议是否签署
    private $yuding_payed = false;//预定金是否已经支付
    private $can_sign_contract = false;//是否可以签约合同

    use UtilTrait;
    use CustomerStewardTrait;
    use HumanTrait;
    use WithCustomer01Trait;
    use RoomTrait;
    use CustomerContractChangeTrait;
    use ContractChangeExecutorBlockTrait;


    /**
     * API重构 - 获取优惠券列表
     * 对应旧接口: https://api.dankegongyu.com/api/v3/user/coupon-list
     * 请求类型: GET
     *
     * type :1= 未使用 2=已过期 3=已使用
     * FIXME 逻辑中中未发现查库操作，直接copy原始逻辑 @2018-07-28 by zhongming
     */
    public function getCouponList()
    {
        $this->setData($this->query());
        $this->rule(['type' => 'required', 'page' => 'required'], ["type" => "优惠券类型", "page" => "当前页"]);
        $type = $this->data("type");
        $page = $this->data("page");
        if ($page < 1) {
            $page = 1;
        }
        $types = [
            "1" => CouponServer::USESTATUS_未使用,
            "2" => CouponServer::USESTATUS_已过期,
            "3" => CouponServer::USESTATUS_已使用
        ];
        if (!array_key_exists($type, $types)) {
            throw BadRequestException::describe("优惠券类型不正确");
        }
        $user = \Auth::user();
        $pageSize = 10;
        $data = [
            "userId" => $user->id,
            "useStatus" => $types[$type],
            "page" => ["pageNum" => $page, "pageSize" => $pageSize]
        ];
        $ret = (new CouponServer())->findCouponList($data);
        $data = $ret["data"]["rows"];
        foreach ($data as &$item) {
            $item['amount'] = $item['couponType'] === CouponServer::COUPONTYPE_折扣 ? ($item['amount'] / 10) : $item['amount'];
        }
        return [
            "code" => $ret["code"],
            "msg" => $ret["msg"],
            "data" => [
                "count" => $ret["data"]["total"],
                "pages" => ceil($ret["data"]["total"] / $pageSize),
                "list" => $data,
                "desc_url" => action('\App\Http\PanguControllers\User\CouponController@getTip')
            ]
        ];
    }

    /**
     * API重构 - 绑定优惠券
     * 对应旧接口: https://api.dankegongyu.com/api/v3/user/bind-coupon
     * 请求类型: PUT
     *
     * type :1= 未使用 2=已过期 3=已使用
     * FIXME 逻辑中中未发现查库操作，直接copy原始逻辑 @2018-07-28 by zhongming
     * @return array
     */
    public function putBindCoupon()
    {
        $user = \Auth::user();

        $this->rule(['couponCode' => 'required']);

        $ret = (new CouponServer())->bindCoupon($user->id, $this->data('couponCode'));
        return [
            "code" => $ret["code"],
            "msg" => $ret["code"] === "9001" ? "您输入的兑换码错误，请重新输入" : $ret["msg"],
            "data" => $ret['data']
        ];
    }


    /**
     * API重构 - 获取个人中心 消息区块
     * @return array
     */
    public function getCenterBlock(): array
    {
        $human = $this->currentHuman();
        $withCustomerService = Service::withCustomerService();
        $contract = $withCustomerService->getEffectiveCustomerContracts($human->id);

        //没有有效合同
        $blocks = $gift_block = [];

        //租户在这段时间内签约，走签约送好礼的入口
        if (Carbon::now()->between(
            Carbon::parse(config('app_api.new_contract_gift.push_start')),
            Carbon::parse(config('app_api.new_contract_gift.push_end'))
        )) {
            $contractWillGift = $withCustomerService->findManyContractsByCustomerAndRentApproveAt($human->id,
                config('app_api.new_contract_gift.contract_start'), config('app_api.new_contract_gift.contract_end'));

            $userHasGift = Service::SignGiftService()->findActiveSignGiftByUserId(\Auth::id());
            if (!$userHasGift && $contractWillGift) {
                $gift_block = [
                    'title' => '欢迎入住蛋壳公寓，我们为您准备了一份大礼！',
                    'desc' => '',
                    'task_url' => 'https://www.dankegongyu.com/pangu-activity/sign-gift/activity',
                ];
            }
        }
        //签约送豪礼，如果有合同，就合并到auth里面，否则就合并到合同相关的里面
        if ($contract->isNotEmpty()) {//有 有效合同 返回工单授权
            return $this->getAuthorizedPwd($gift_block);
        } else {
            $blocks = array_merge($blocks, $gift_block);
        }

        if (config('app.app_sign_open')) {//开启了 签约
            $timeNow = date('Y-m-d H:i:s');
            $twentyMinAgo = date('Y-m-d H:i:s', time() - 20 * 60);
            //预定 定金 协议 按钮
            $bookingService = app(BookingServiceIf::class);
            $customerBookingStatusArray = [CustomerBooking::STATUS_待审核, CustomerBooking::STATUS_通过];
            $bookingInfo = $bookingService->findManyBookingByCustomerIdBeginDateEndDateStatus($human->id, $timeNow,
                $timeNow, $customerBookingStatusArray);
            $unCompleteBooking = $bookingInfo->where("paymentStatus", CustomerBooking::PAYMENT_STATUS_未付款)
                ->where('createdAt', '>=', $twentyMinAgo)
                ->map(function ($customerBooking) {
                    //查询合同 判断支付和签署两种状态
                    $electronic = Service::EleContractService()->findEleContractByContractIdAndType($customerBooking->id,
                        '出房定金协议');
                    $this->yuding_signed = $electronic && $electronic->isSigned();
                    $this->yuding_payed = app(BookingServiceIf::class)->isPaid($customerBooking->id);

                    if (!$this->yuding_signed) {
                        return [
                            'title' => '定金协议',
                            'desc' => '您有一笔定金协议待签署',
                            'task_url' => config('app_url.customer_booking_list'),
                        ];
                    }

                    if ($this->yuding_signed && !$this->yuding_payed) {
                        return [
                            'title' => '定金支付',
                            'desc' => '您有一笔定金待支付',
                            'task_url' => config('app_url.customer_booking_list'),
                        ];
                    }
                })->filter(function ($item) {
                    return !empty($item);
                })->values()->toArray();

            if ($unCompleteBooking) {
                $blocks = array_merge($blocks, $unCompleteBooking);
            }

            //调用盘古 ithCustomerServiceWIf 服务方法
            $userContracts = $withCustomerService->getCustomerContracts($human->id);//human已签署的所有的合同
            if ($userContracts) {
                $signButtons = $userContracts->map(function ($withCustomer) {
                    if (!$this->can_sign_contract
                        && Service::withCustomerService()->canSignElectron($withCustomer->id)['status']
                        && $withCustomer->monthlyPayWay != '网商银行'
                        && $withCustomer->status == BaseContract::STATUS_签约待确认) {
                        $code = cacheData($withCustomer->id, 60 * 200);
                        $this->can_sign_contract = true;

                        return [
                            'title' => '合同',
                            'desc' => '蛋壳管家邀请您签约租房合同',
                            'task_url' => action('\App\Http\PanguControllers\User\Elecontract\CustomerSignController@anySign',
                                $code)
                        ];
                    }
                })->filter(function ($item) {
                    return !empty($item);
                })->values()->toArray();

                if ($this->can_sign_contract) {
                    $blocks = array_merge($blocks, $signButtons);
                }
            }

            if ($userContracts) {
                //首笔租金支付
                $firstMoneyButtons = $userContracts->map(function ($wc) {
                    // 记录日志logo
                    info('------------->>>>>|getCenterBlock||>>>>>>' . $wc->id);

                    $firstPayBills = Service::withCustomerService()->getFirstPayBills($wc);
                    //hasSignElectron
                    $eleService = app(EleContractService::class);
                    $elecon = $eleService->findEleContractByContractIdAndType($wc->id, '出房合同');
                    $hasSignElectron = $elecon && $eleService->hasSigned($elecon);

                    if ($hasSignElectron && !$firstPayBills->isPaid() && $wc->status == BaseContract::STATUS_签约待确认) {
                        $billIds = $firstPayBills->ids();
                        if ($billIds) {
                            return [
                                'title' => '支付',
                                'desc' => '您有一笔租金待支付',
                                'task_url' => config('app_url.pay_bill') . '?id=' . implode(',', $billIds),
                            ];
                        }
                    }
                })->filter(function ($item) {
                    return !empty($item);
                })->values()->toArray();

                if ($firstMoneyButtons) {
                    $blocks = array_merge($blocks, $firstMoneyButtons);
                }
            }
        }

        return $this->toJson($blocks);
    }

    /**
     * Api重构 - 授权消息模块
     * @return array
     */
    public function getAuthorizedPwd($gift_block = [])
    {
        $human = $this->currentHuman();
        $humanId = $human->id;

        $contractInfo = Service::withCustomerService()->getEffectiveCustomerContracts($humanId);
        //拿到所有有效的胡同后,取出第一个
        $contractLatest = $contractInfo->first();
        $customerId = $contractLatest->customerId;
        $suiteId = Service::roomService()->findRoomById($contractLatest->roomId)->suiteId;
        $contractId = $contractLatest->id;

        $auths = [];

        if (!empty($gift_block)) {
            $auths[] = $gift_block;
        }

        //调用 CleaningServiceIf 服务
        $cleaningService = app(CleaningServiceIf::class);

        //获取保洁的授权信息
        $cleanGrants = $cleaningService->findFeGrantDtoBySuiteIdAndTaskId($humanId, $suiteId);
        $cleanGrants = Collect($cleanGrants);

        $cleanGrants = $cleanGrants->keyBy('task_id')->map(function ($item) {
            if (!$item->acceptanceStatus && $item->status == Grant::STATUS_未授权 && ($item->appliedAt >= Carbon::now()->subHours(2))) {
                if ($this->compareAppVersion('v1.3.0') >= 0) {
                    return [
                        'title' => '授权',
                        'desc' => '保洁员已上门，正在申请您的房间密码',
                        'task_url' => config('app_url.cleaning')
                    ];
                } else {
                    return [
                        'title' => '保洁员已上门，正在申请您的房间密码',
                        'desc' => '',
                        'task_url' => config('app_url.cleaning')
                    ];
                }
            }
        })->toArray();

        if ($cleanGrants) {
            sort($cleanGrants);
            $auths = array_merge($auths, $cleanGrants);
        }

        //调用 RepairServiceIf 服务
        $repairService = app(RepairServiceIf::class);

        //获取报修的授权信息
        $repairAuths = $repairService->findManyAppPasswordAuthDtoByCustomerId($customerId);
        $repairAuths = Collect($repairAuths);

        $authDay = date('Y-m-d H:i:s');
        $repairAuths = $repairAuths->keyBy('task_id')->map(function ($item) use ($authDay) {
            //fix jira PGXM-2376 修改$item中的键名为小驼峰命名方式;
            if (!$item->acceptanceStatus && $item->status == PasswordAuth::STATUS_未授权 && ($authDay >= $item->appointDate && $authDay <= $item->expectFinishDate)) {
                if ($this->compareAppVersion('v1.3.0') >= 0) {
                    return [
                        'title' => '授权',
                        'desc' => '维修员已上门，正在申请您的房间密码',
                        'task_url' => config('app_url.repair')
                    ];
                } else {
                    return [
                        'title' => '维修员已上门，正在申请您的房间密码',
                        'desc' => '',
                        'task_url' => config('app_url.repair')
                    ];
                }
            }
        })->toArray();

        if ($repairAuths) {
            sort($repairAuths);
            $auths = array_merge($auths, $repairAuths);
        }

        $versionAndSwitchFlag = $this->compareAppVersion('v1.3.0') >= 0 && config('app.app_sign_open');
        if ($versionAndSwitchFlag) {
            //物业交割按钮
            $canSignElectronProperty = Service::withCustomerService()->canSignElectronProperty($contractId);
            if ($canSignElectronProperty['status'] ?? false) {
                $auths[] = [
                    'title' => '物业交割单',
                    'desc' => '您有一份物业交割信息待确认',
                    'task_url' => action('\App\Http\PanguControllers\User\Elecontract\CustomerCenterController@anySignProperty',
                        $contractId),
                ];
            }

            //活动补充协协议按钮
            $canSignElectronSupple = Service::withCustomerService()->canSignElectronSupple($contractId);
            if ($canSignElectronSupple['status'] ?? false) {
                $auths[] = [
                    'title' => '活动补充协议',
                    'desc' => '您有一份活动补充协议待确认',
                    'task_url' => action('\App\Http\PanguControllers\User\Elecontract\CustomerCenterController@anySignSupple',
                        $contractId),
                ];
            }
        }

        return $this->toJson(array_values(array_filter($auths)));

    }

    /**
     * API重构 - 预定列表
     * 对应旧接口: https://api.dankegongyu.com/api/v3/user/book-list
     * 请求类型: GET
     * @return array
     */
    public function getBookList(): array
    {
        if (!config('app.app_sign_open')) {
            return $this->toJson([]);
        }

        $human = $this->currentHuman();

        $timeNow = date('Y-m-d');

        //thrift 接口由服务端@宣振斌提供
        $bookingService = app(BookingServiceIf::class);
        $roomService = app(RoomServiceIf::class);
        $customerBookingStatusArray = [CustomerBooking::STATUS_待审核, CustomerBooking::STATUS_通过];
        $bookingInfo = $bookingService->findManyBookingByCustomerIdBeginDateEndDateStatus($human->id, $timeNow,
            $timeNow, $customerBookingStatusArray);
        $electronicService = app(EleContractServiceIf::class);
        $suiteService = app(SuiteServiceIf::class);
        $billService = app(RoomBillServiceIf::class);
        //预定按钮
        $BookingList = $bookingInfo
            ->map(function ($customerBooking) use (
                $human,
                $electronicService,
                $roomService,
                $suiteService,
                $billService,
                $bookingService
            ) {
                $electronic = $electronicService->findNewEleContractByContractIdAndType($customerBooking->id,
                    '出房定金协议');// findEleContractByContractIdAndType($customerBooking->id, '出房合同');
                $signed = null;
                $payed = null;
                if ($electronic) {
                    $signed = $electronic && $this->isSigned($electronic->status);
                    $payed = $bookingService->isPaid($customerBooking->id);
                }
                $room = $roomService->findRoomById($customerBooking->roomId);
                $roomAddress = "";
                $suite = $suiteService->findSuiteById($room->suiteId);
                if ($room) {
                    $roomAddress = $room->roomNumber . ' ' . $suite->address;
                }
                $roomAddress = $room->roomNumber . ' ' . $suite->address;
                $bill = $billService->findBookingBillByBookingId($customerBooking->id);

                $cacheKey = md5('booking_pay' . $customerBooking->id);
                if ($payed && (Carbon::now()->diffInSeconds($bill->paidAt) < 10) && !\Cache::get($cacheKey)) {//支付5秒内 发push
                    try {
                        UserMessageNotification::createMessageForHuman(
                            $human,
                            $customerBooking->id,
                            '您已成功预定蛋壳公寓：' . $roomAddress,
                            '您已成功预定蛋壳公寓：' . $roomAddress,
                            config('app_url.customer_booking_list')
                        );

                        \Cache::put($cacheKey, 1, 1);
                    } catch (\Exception $exception) {
                        \Log::info(config('app_url.main') . $exception->getMessage());
                    }
                }

                $roomCanRent = $room->status === \Room::STATUS_可出租;
                $isRoomLocked = $bookingService->isLockRoom($room->id);
                $isUserLockTheRoom = $isRoomLocked ? $bookingService->isLockCustomerId($customerBooking->customerId,
                    $customerBooking->roomId) : false;

                $billId = $bill->id ?? 0;
                return [
                    'id' => $customerBooking->id,
                    'address' => '#' . $roomAddress,
                    'date_desc' => '预定日期：' . $customerBooking->createdAt,
                    'deposit_aggrement' => [
                        'label' => '定金协议',
                        'button_url' => $signed ? '' : action('\App\Http\PanguControllers\User\Elecontract\CustomerCenterController@anySignDeposit',
                            $customerBooking->id),
                        'button_value' => $signed ? '已签署' : (!$roomCanRent || ($isRoomLocked && !$isUserLockTheRoom) ? '房间已被预订' : '签署'),
                        'selected' => !$signed,
                    ],
                    'deposit' => [
                        'label' => '定金 ： ' . $customerBooking->deposit . '元',
                        'button_url' => ($payed || !$billId) ? '' : config('app_url.pay_bill') . '?id=' . $billId,
                        'button_value' => $payed ? '已付款' : ($signed ? '付款' : '协议待签署'),
                        'selected' => $signed && !$payed,
                    ]
                ];
            })->filter(function ($item) {
                return !empty($item);
            })->values()->toArray();
        return $this->toJson($BookingList);
    }

    /**
     * API重构 - 我的管家数据
     * 对应旧接口: https://api.dankegongyu.com/api/v3/user/steward
     *
     */
    public function getSteward()
    {

        try {
            $responseData = [
                'service_phone' => '4001-551-551'
            ];

            $contract = $this->getCustomerContract();
            if (empty($contract->toArray())) {
                return $this->toError(OutputMsg::CONTRACT_CUSTOMER_UN_ACTIVE);
            }
            //查找roomId
            $room = app(RoomServiceIf::class)->findRoomById($contract->all()[0]->roomId);

            //查找 suitedId
            $suite = app(SuiteServiceIf::class)->findSuiteById($room->suiteId);

            //查找xiaoquId
            $block = app(LocationServiceIf::class)->findBlockAreaByXiaoquId($suite->xiaoquId);

            //查找 customerSteward 并 获取一条商圈管家数据
            $stewardService = app(StewardServiceIf::class);
            $blockSteward = $stewardService->findCustomerStewardAreaByBlockId($block->id) ?? null;

            if (!$blockSteward) {
                return $this->toError(OutputMsg::BLOCK_NO_STEWARD, '', $responseData);
            }

            //获取管家基本信息
            $steward = $stewardService->findCustomerStewardByStewardId($blockSteward->stewardId);
            if (!$steward) {
                return $this->toError(OutputMsg::BLOCK_NO_STEWARD, '', $responseData);
            }
            //根据管家的状态获取相应的管家信息
            $stewardInfo = $this->getStewardBaseInfo($steward);
            if (!$stewardInfo) {
                return $this->toError(OutputMsg::BLOCK_NO_STEWARD, '', $responseData);
            }

            $stewardInfo['service_items'] = Steward::getServiceItems();

            $responseData['steward'] = $stewardInfo;

            return $this->toJson($responseData);
        } catch (BadRequestException $badRequestException) {
            return $this->toError(OutputMsg::CONTRACT_CUSTOMER_UN_ACTIVE);
        }
    }

    /**
     * 下载合同
     * @return array
     */
    public function postDownloadContract()
    {
        $withCustomerService = app(WithCustomerService::class);

        $human = $this->currentHuman();

        $contractId = intval($this->data('contract_id', 0));

        $contract = $this->isContractBelongToCustomer($contractId, $human->id);

        if (!($contract && $contract->id)) {
            throw new RateLimitException("请求异常");
        }

        //自然天 下载次数限制
        if ((new \Firewall(__FUNCTION__ . $contractId, 86400, 10))->hit()) {
            throw new RateLimitException("发送过于频繁");
        }

        $this->rule([
            'email' => 'required|email',
        ], [
            'email' => '邮箱',
        ]);

        $email = $this->data('email');

        $previewUrl = $this->electronic_contract($contract->id)->preview_url ?? '';
        //发送
        \Email::send($email, '蛋壳公寓合同', '尊敬的蛋壳租户您好：感谢您选择蛋壳公寓，合同下载请<a href="' . $previewUrl . '">点击此处</a>。', true);

        return $this->toJson([]);
    }

    /**
     * 选择合同变更类型
     * @param $contract_id
     * @return array
     */
    public function getContractChangeType($contract_id)
    {
        $customer = $this->currentHuman();

        // todo
        $withCustomerService = app(WithCustomerService::class);
        $contract = $withCustomerService->findCustomerContractById($contract_id);

        if (!($contract && $contract->id) || $contract->customerId != $customer->id) {
            throw BadRequestException::describe('合同不存在', BusinessNumber::BUSINESS_CUSTOMER_合同不存在);
        }
        return $this->toJson([
            'types' => [
                $this->getXuzuConfig($contract),
                $this->getTuizuConfig($contract),
            ],
            'tips' => [
                'text' => '查看租约变更说明 >',
                'url' => action('\App\Http\PanguControllers\FE\HomeController@getChangeContractExplain')
            ]
        ]);
    }

    //  获取退租的配置
    private function getTuizuConfig($contract)
    {
        $conf = [
            'status' => true,
            'title' => '我要退租',
            'desc' => '单方面解约按合同约定比例收取违约金',
            'url' => config("app_url.contract_terminate") . "?contract_id=" . $contract->id,
        ];

        $is_nianzu = $contract->rentMonths >= 12;
        if (!$is_nianzu) {
            $conf['status'] = false;
            $conf['desc'] = '月租用户请联系客服办理退租：4001-551-551';
        } elseif ((new Carbon($contract->endDate))->addDay(-5)->lt(Carbon::today())) {
            $conf['status'] = false;
            $conf['desc'] = '不在业务办理范围时间内，详情联系客服：4001-551-551';
        }

        // 1.9.8 发起续租申请后
        $customerContractChangeServiceIf = app(CustomerContractChangeServiceIf::class);
        // 查工单
        $contractChange = $customerContractChangeServiceIf->findEffectiveChangeByContractId($contract->id);
        if(!empty($contractChange) && $contractChange->type == Change::TYPE_退租){
            $conf['status'] = false;
            $conf['desc'] = '您发起续租申请，续租完成后，退租入口将开放。如需帮助可致电：4001-551-551';
        }

        if ($conf['status']) {
            $conf['icon'] = (new Image())->url('public-20180206-qQi7reik8F3k3UkwnWe');
        } else {
            $conf['icon'] = (new Image())->url('public-20180206-0nVg6XEN5S4OhNqJ2kd');
        }

        return $conf;
    }

    // 获取续租的配置
    private function getXuzuConfig($contract)
    {
        $conf = [
            'status' => true,
            'title' => '我要续租',
            'desc' => '请在合同列表查看您的续约合同',
            'url' => config("app_url.contract_continue") . "?contract_id=" . $contract->id,
        ];
        // >= 12月长租
        $is_nianzu = $contract->rentMonths >= 12;
        //续租截止时间
        $xuzu_end_time = $is_nianzu ? (new Carbon($contract->endDate))->addYear(1) : (new Carbon($contract->endDate))->addMonth(1);

        //查找 roomId
        $room = app(RoomServiceIf::class)->findRoomById($contract->roomId);
        if(empty($room)){
            $conf['status'] = false;
            $conf['desc'] = '当前房源不支持续租,如有需要请联系客服：4001-551-551';
            $conf['icon'] = (new Image())->url('public-20180206-KUcG5wwyvGolfzvj7me');
            return $conf;
        }
        //查找 suitedId
        $suite = app(SuiteServiceIf::class)->findSuiteById($room->suiteId);
        if(empty($suite)){
            $conf['status'] = false;
            $conf['desc'] = '当前房源不支持续租,如有需要请联系客服：4001-551-551';
            $conf['icon'] = (new Image())->url('public-20180206-KUcG5wwyvGolfzvj7me');
            return $conf;
        }

        $rent_end_date = $suite->rentEndDate;
        // 公寓截止日期
        if ($xuzu_end_time->gt($rent_end_date)) {
            $conf['status'] = false;
            $conf['desc'] = '当前房源不支持续租,如有需要请联系客服：4001-551-551';
        } elseif ((new Carbon($contract->endDate))->addDay($is_nianzu ? -90 : -15)->gt(Carbon::today()) || (new Carbon($contract->endDate))->addDay(-3)->lt(Carbon::today())) {
            $conf['status'] = false;
            $conf['desc'] = '您的合同到期前3-15天可发起续租申请，如需帮助可致电：400-1551-551';
            if($is_nianzu){
                $conf['status'] = false;
                $conf['desc'] = '您的合同到期前3-90天可发起续租申请，如需帮助可致电：400-1551-551';
            }
        }

        if ($conf['status']) {
            $conf['icon'] = (new Image())->url('public-20180206-dJj3C3MQTqyeum6NHtE');
        } else {
            $conf['icon'] = (new Image())->url('public-20180206-KUcG5wwyvGolfzvj7me');
        }

        return $conf;
    }

    /**
     * 获取合同退转换初始数据   //1：退租 :2：续租
     * @param $contract_id
     * @return array
     */
    public function getContractCebInitData($contract_id)
    {
        $customer = $this->currentHuman();

        // todo
        $withCustomerService = app(WithCustomerService::class);
        $contract = $withCustomerService->findCustomerContractById($contract_id);
        //查找 roomId
        $room = app(RoomServiceIf::class)->findRoomById($contract->roomId);

        if (!($contract && $contract->id) || $contract->customerId != $customer->id) {
            throw BadRequestException::describe('合同不存在', BusinessNumber::BUSINESS_CUSTOMER_合同不存在);
        }

        $data = [
            'contract_id' => $contract_id,
            'user_name' => $customer->idName,
            'address' => $this->internalTitle($room),
            'mobile' => $customer->mobile,
        ];


        // todo
        //1：退租 :2：续租
        if ($this->query("type") == 1) {
            // 退租原因
            $data['reasons'] = Change::listTerminateNoteForAPP(Change::TYPE_退租);
            // 接口返回原始数据
            $reasons_new = CustomerContractChangeReasonSetting::getReasonList();
            // 组装数据
            foreach ($reasons_new as $key => $val) {
                $data['reasons_new'][] = ['id' => $key, 'name' => $val];
            }

//            $data['tips'] = [
//                '选择在当前时间30天之后退租，扣除30%押金',
//                '选择在当前时间30天之内退租，扣除100%押金'
//            ];
            $data['start_date'] = Carbon::now()->addDay(3)->timestamp;
            $data['end_date'] = strtotime($contract->endDate);
        }
        if ($this->query("type") == 2) {
            $data['tips'] = [
                '提交申请续租后，蛋壳公寓将主动联系您确认续租详情',
            ];
            $roomService = app(RoomServiceIf::class);
            $room = $roomService->findRoomById($contract->roomId);
            $data['amout'] = $contract->rentMonths >= 12 ? $room->price : $room->monthPrice;
            $data['start_date'] = Carbon::now()->addDay(1)->timestamp;
            $data['end_date'] = strtotime($contract->endDate);
        }
        return $this->toJson($data);
    }

    //获取试算金额
    public function getClearingMoney()
    {
        $this->setData($this->query);
        $this->rule([
            'contract_id' => 'required',
            'expect_end_date' => 'required',
        ]);

        $terminate_date = new Carbon($this->query['expect_end_date']);
        $report_date = Carbon::today();
        $logic = new SimulateClearingLogic($this->data('contract_id'), '退租', $report_date, $terminate_date);
        $result = $logic->getResult();

        return $this->toJson($this->getShisuanAmount($result));
    }

    /**
     * 创建 续租，换租，退租 工单  //1：退租 :2：续租
     */
    public function postChangeContractTask()
    {

        // 接收（新）原因
        $reason_ids = $this->data('reason_ids', '');
        $reason_names = $this->data('reason_names', '');

        $this->rule([
            'type' => 'required|in:' . join(',', [1, 2]),
            'contract_id' => 'required',
            'plan_date' => 'required',
        ]);
        $type = $this->data('type');
        //此处为了distrabute操作准备变量  退租==> 结旧  续租===>新签
        $executor_type = ['1' => $this->EXECUTOR_TYPE_结旧, '2' => $this->EXECUTOR_TYPE_新签][$type];
        $customer = $this->currentHuman();
        $WithCustomerService = app(WithCustomerServiceIf::class);
        $contract = $WithCustomerService->findByCustomerId($customer->id);
        $contractChangeService = app(CustomerContractChangeServiceIf::class);

        if (!$contract || $contract->customerId != $customer->id) {
            throw BadRequestException::describe('合同不存在', BusinessNumber::BUSINESS_CUSTOMER_合同不存在);
        }

        // 验证退转换工单的有效日期
        try {
            $customerContractChangeService = app(CustomerContractChangeServiceIf::class);
            $old = $customerContractChangeService->findRepeatContractChangeByCustomerContract($contract);
        } catch (\ErrorMessageException $exception) {
            return $this->toError(OutputMsg::CONTRACT_CUSTOMER_STATUS_ERROR, $exception->getMessage());
        }
        $roomService = app(RoomServiceIf::class);
        $roomInfo = $roomService->findRoomById($contract->roomId);
        // 如果没有重复的合同记录，则可以直接创建（如报修）新工单
        if (!$old) {
            //创建
            $contractChange = new CustomerContractChange();
            $contractChange->status = Change::STATUS_待分派;
            $contractChange->city = $roomInfo->cityName ?? null;
            $contractChange->roomId = $contract->roomId;
            $contractChange->contractId = $this->data('contract_id');
            $contractChange->type = $this->getChangeType($this->data('type'));
            $contractChange->planDate = toDateString($this->data('plan_date'));
            $contractChange->reportDate = Carbon::now();
            $contractChange->reason = $this->data('reason') ?: '';
            $contractChange->note = $this->data('reason') ?: '';
            $contractChange->source = Change::SOURCE_APP;

            // 创建保存 与某工单相关的 原因记录（换独立表）：（APP1.7）
            if (isset($reason_ids) && is_array($reason_ids) && count($reason_ids) > 0) {
                // 同时改一下兼容老版本的字段
                $contractChange->note = implode('|', $reason_names);    // 以|分割数组形成字符串。与emplode相反
            }
            // ---------------------------------------

            if (!$contractChange = $contractChangeService->saveCustomerContractChange($contractChange)) {
                return $this->toError(OutputMsg::CHANGE_ORDER_CREATE_FAIL);
            } else {
                // 创建保存 与某工单相关的 原因记录（换独立表）：（APP1.7）
                if (isset($reason_ids) && is_array($reason_ids) && count($reason_ids) > 0) {
                    // 获得Modle
                    $contractChangeService->saveReason($contractChange->id, $reason_ids);
                    // 为此‘退租工单’创建保存（新原因）
                }
            }
            $contractChangeService->autoContractDistribute($contractChange->id, $executor_type, null);
            return $this->toJson($contractChange->id);
        }

        // 提前续租的老合同在新合同起租日前创建新工单
        if ($old->type === Change::TYPE_续租 && in_array($old->status, [Change::STATUS_已完成, Change::STATUS_已验收])) {
            return $this->toError(OutputMsg::CHANGE_ORDER_CAN_NOT_CHANGE);
        }

        $changeLogic = new Change($old);
        $changeType = $this->getChangeType($this->data('type'));
        $planDate = toDateString($this->data('plan_date'));
        $canCovered = $changeLogic->allowCovered($changeType);

        if ($canCovered !== true) {
            return $this->toError(OutputMsg::CHANGE_ORDER_CAN_NOT_CHANGE, $canCovered);
        }

        $old->planDate = $planDate;
        try {
            $humanIsBlocked = app(UserServiceIf::class)->humanIsBlocked($contract->customerId);
            $contractChangeService->setChangeInfoByContractAndType($old->id, $contract, $changeType, $humanIsBlocked);

            $old->status = Change::STATUS_待分派;
            $old->reason = $this->data('reason', '');
            $old->note = $this->data('reason', '');

            $old->source = Change::SOURCE_APP;

            // 创建保存 与某工单相关的 原因记录（换独立表）：（APP1.7）
            if (isset($reason_ids) && is_array($reason_ids) && count($reason_ids) > 0) {
                // 为此合同创建保存（新原因）
                $contractChangeService->saveReason($old->id, $reason_ids);
                // 同时改一下兼容老版本的字段
                $old->note = implode('|', $reason_names);   // 以|分割数组形成字符串。与emplode相反
            }
            // ---------------------------------------

            $contractChangeService->saveCustomerContractChange($old);

            //自动分派执行人
            $contractChangeService->autoContractDistribute($old->id, $executor_type, null);
            if ($old->type === Change::TYPE_逾期清退) {
                $changeLogic->remindPaymentDay();

                $changeLogic->noticeCustomer();
            }
        } catch (\ErrorMessageException $exception) {
            return $this->toError(OutputMsg::CHANGE_ORDER_CAN_NOT_CHANGE, $exception->getMessage());
        }


        return $this->toJson($old->id);
    }

    /**
     * 取消退租申请
     * @param $change_id
     * @return array
     */
    public function getCancelChangeContract($change_id)
    {
        $customer = $this->currentHuman();

        // 获取退转换工单
        $customerContractChangeService = app(CustomerContractChangeServiceIf::class);
        $customerContractChange = $customerContractChangeService->findCustomerContractChangeById($change_id);
        $contract = null;
        if ($customerContractChange) {
            $customerContractService = app(WithCustomerServiceIf::class);
            $contract = $customerContractService->findCustomerContractById($customerContractChange->contractId);
        }
        $ChangeLogic = new Change($customerContractChange);

        if (!$contract || $contract->customerId != $customer->id) {
            throw BadRequestException::describe('没有可撤销的退转换工单', BusinessNumber::BUSINESS_TASK_未找到工单);
        }
        if (!$ChangeLogic->canCancle($customerContractChange)) {
            throw BadRequestException::describe('此工单不可取消', BusinessNumber::BUSINESS_TASK_不允许撤销);
        }
        $updatedCallback = $ChangeLogic->doCancel();
        $customerContractChangeEntity = $ChangeLogic->getContractChange();
//        var_dump($customerContractChangeEntity);exit;
        $customerContractChangeService->saveCustomerContractChange($customerContractChangeEntity);
//        if ($updatedCallback) {
//            call_user_func($updatedCallback, $customerContractChangeEntity);
//        }

        return $this->toJson($change_id);
    }

    private function getShisuanAmount($result)
    {
        $amounts = [
            [
                "name" => "剩余租金",
                "amount" => $this->reverse_int($result->remainRentYuan)
            ],
            [
                "name" => "剩余服务费",
                "amount" => $this->reverse_int($result->remainServiceFeeYuan)
            ],
            [
                "name" => "剩余押金",
                "amount" => $this->reverse_int($result->remainDepositYuan)
            ],
            [
                "name" => "剩余水燃气费",
                "amount" => $this->reverse_int($result->remainWaterFuelYuan)
            ],
            [
                "name" => "剩余维修管理费",
                "amount" => $this->reverse_int($result->remainMaintainFeeYuan)
            ],
//            [
//                "name" => "物业交割总金额",
//                "amount" => Amount::fen2yuan($changeLogic->calcHandOverFen())
//            ],
            [
                "name" => "活动扣款",
                "amount" => $this->reverse_int($result->promotionFineYuan)
            ]
        ];
        $total = 0;
        foreach ($amounts as &$amount) {
            $total = $total + $amount['amount'];
            $amount['name'] = $amount['name'] . "：";
            $amount['amount'] = "¥" . $amount['amount'];
        }
        return [
            "count" => strval($total),
            "amounts" => $amounts
        ];

    }


    /**
     * 获取租户退 转 换 续 工单详情
     * @param $change_id
     * @return array
     */
    public function getContractChangeDetail($change_id)
    {
        $customerContractChangeServiceIf = app(CustomerContractChangeServiceIf::class);
        $userServiceIf = app(UserServiceIf::class);

        // 查工单
        $contractChange = $customerContractChangeServiceIf->findCustomerContractChangeById($change_id);
        // 连查合同
        $withCustomerService = app(WithCustomerService::class);
        $contract = $withCustomerService->findCustomerContractById($contractChange->contractId);
        // 连查Human
        $humanInfo = $userServiceIf->findHumanById($contract->customerId);

        // todo 临时去掉调试！记得恢复！！
        if (!($contractChange && isset($contractChange->id)) || $contract->customerId != (\Auth::human() ? \Auth::human()->id : 0)) {
            throw BadRequestException::describe('无有效退转换工单', BusinessNumber::BUSINESS_TASK_无效工单);
        }

        // $change = $contractChange;
        $changeLogic = new change($contractChange);
        if (!$contract->terminateDate) {
            $contract->terminateDate = $contractChange->planDate;
            $contract->terminateType = $contractChange->type;
            // $change = CustomerContractChangeExt::getInstance($contract);            // 下边已被注释，就不需要了
        }

        // 连查房源
        $room = app(RoomServiceIf::class)->findRoomById($contract->roomId);

        // 管家
        $executor = [];
        if (!empty($contractChange->executorId)) {
            $corpUserService = Service::CorpUserService();
            $corpUser = $corpUserService->findCorpUserById($contractChange->executorId);
            $executor['executor_name'] = $corpUser->name;
            $executor['executor_mobile'] = $corpUser->mobile;
            $executor['executor_avatar'] = $corpUser->avatar;
            $stewardEntity = Service::StewardService()->findCustomerStewardByCorpuserId($contractChange->executorId);
            $telecomPhone = !empty($stewardEntity) && $stewardEntity->id ? Service::TelecomService()->findTelecomListPhoneByStewardId($stewardEntity->id) : '';
            if ($telecomPhone) {
                $executor['executor_mobile'] = $telecomPhone;
            }
        }

        // 查找评论
        $proposal = Service::CustomerProposalService();
        $human = $this->currentHuman();
        $ProposalResult = $proposal->findCustomerProposalByHumanIdAndTaskId($human->id, $contractChange->id);

        // 标签
        $tags = [];
        // 反馈
        $feedback_note = '';
        // 显示评论 is_remark 字段  评论状态 0不显示 1去评价 2已评价
        $is_remark= 0;
        $process = $changeLogic->listProcessPangu($executor['executor_mobile']??'',$contractChange->type);
        if (count($process) == 3) {
            $is_remark = 1;
        }
        if (count($ProposalResult) > 0) {
            $feedback_note = $ProposalResult[0]->proposalContent;
            $tags = explode('|', $ProposalResult[0]->proposalType);
            $is_remark = 2;
            $process[] = [
                'title' => '已评价',
                'desc' => '谢谢相伴，欢迎再回来!',
                'acceptance_time' => $ProposalResult[0]->createdAt
            ];
        }
        $process = collect($process)->sortByDesc('acceptance_time')->toArray();
        sort($process);
        // 评星
        $eval_data = [];
//      customer_service_review_records
        $reviewRecordService = Service::CustomerReviewService();
        $recordsResult = [];
        if($contractChange->type == EvaluateData::SYSTEM_续租){
            $recordsResult = $reviewRecordService->findReviewCategoryBySystemAndStatus(CustomerProposal::PROPOSAL_TYPE_续租服务,CustomerProposal::STATUS_TYPE_启用);
            $tagsArr = EvaluateData::getEvalTypesTagsV1_3()['xuzu']['tags'];
        }else{
//            $recordsResult = $reviewRecordService->findReviewCategoryBySystemAndStatus(CustomerProposal::PROPOSAL_TYPE_退租服务,CustomerProposal::STATUS_TYPE_启用);
//            $tagsArr = EvaluateData::getEvalTypesTagsV1_3()['tuizu']['tags'];
        }
        foreach ($recordsResult as $key => $item) {
            $eval_data[$key]['score_title'] = $item->system;
            $eval_data[$key]['score_value'] = $item->score;
            $eval_data[$key]['score_desc'] = $tagsArr[($item->score - 1) / 10];
        }
        $data = [
            'id' => $contractChange->id,
            'created_at' => (new Carbon($contractChange->createdAt))->toDateTimeString(),
            'note' => $contractChange->note ?? '',
            'plan_date' => toDateString($contractChange->planDate),
            'title' => "申请" . $contractChange->type . "详情",
            'address' => $this->internalTitle($room),

            'customer_name' => $humanInfo->idName,
            'customer_mobile' => $humanInfo->mobile ?? null,
            //管家
            'executor' => $executor ?? null,
            // 租户评星
            'eval_data' => $eval_data ?? null,
            // 标签
            'tags'=>$tags??null,
            'feedback_note'=>$feedback_note??null,
            'is_remark' =>$is_remark,
            'mobile' => $this->currentHuman()->mobile,
            //参考价格
            'amout' => $contract->rentMonths >= 12 ? $room->price : $room->monthPrice,
            // todo
            'allow_cancel' => $changeLogic->canCancle($contractChange),
            'process' => $process ?? null
        ];
        return $this->toJson($data);
    }

    //活动返现计划
    public function getContractRemits()
    {
        $contract_id = $this->query("contract_id");
        if (!$contract_id) {
            throw BadRequestException::describe("参数contract_id不能为空");
        }
        $customer = $this->currentHuman();
        $contract = \Contract\WithCustomer::find($contract_id);

        if (!$contract || $contract->customer_id != $customer->id) {
            throw BadRequestException::describe('合同不存在', BusinessNumber::BUSINESS_CUSTOMER_合同不存在);
        }
        $index = 0;
        $remits = $contract->remits->map(function (\Trade\Remit $remit) use (&$index) {
            $index++;
            $data = Carbon::createFromFormat('Y-m-d', $remit->date);
            return [
                "number" => $index,
                "start_time" => toDateString($data),
                "end_time" => toDateString($data->addMonth()),
                'amount' => $remit->amount_fen / 100,
                'status' => $this->getRemitStatus($remit->status),
                'payed_date' => $remit->paid_at,
                'pay_date' => toDateString($remit->date),
                'short_date' => toDateString($remit->paid_at ?? $remit->date, "m/d")
            ];
        });
        $data = [
            'id' => $contract->id,
            'address' => $contract->room->internalTitle(),
            'number' => $contract->number,
            'remits' => $remits
        ];
        return $this->toJson($data);
    }

    private function getChangeType($type)
    {
        $types = [
            "1" => WithCustomer::TERM_TYPE_退租,
            "2" => WithCustomer::TERM_TYPE_续租
        ];
        return $types[$type];
    }

    public function isSigned($status)
    {
        return in_array($status, ['已签署', '已归档']);
    }

    private function getRemitStatus($status)
    {
        $sts = [
            "无效" => ["status" => "无效", "bgcolor" => "3DBCC6", "color" => "FFFFFF"],
            "待付款" => ["status" => "待付款", "bgcolor" => "3DBCC6", "color" => "FFFFFF"],
            "付款成功" => ["status" => "已返现", "bgcolor" => "E4E8EB", "color" => "666666"],
            "银行退回" => ["status" => "财务处理中", "bgcolor" => "3DBCC6", "color" => "FFFFFF"],
            "已提交银行" => ["status" => "财务处理中", "bgcolor" => "3DBCC6", "color" => "FFFFFF"],
        ];
        return $sts[$status];
    }
}
