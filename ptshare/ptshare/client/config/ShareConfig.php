<?php
require_once('host.php');

class ShareConfig
{
	const DEFAULT_HOST = CLIENT_API_HOST;
    const UPLOAD_HOST = "upload.putaofenxiang.com";
    public static $api_conf = [
        //============================sell 相关操作==================================//
        'updateLotterTravel' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/lotteryTravel/adminUpdate',
            'method' => 'post',
            'params' => [
                'id',
            ],
        ],
        'setSellStatus' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/sell/setSellStatus',
            'method' => 'post',
            'params' => [
                'id',
                'status',
            	'type',
            	'vip',
            ],
        ],
        'setSellSuccess' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/sell/setSellSuccess',
            'method' => 'post',
            'params' => [
                'id',
            ],
        ],
        'updateGoods' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/sell/updateGoods',
            'method' => 'post',
            'params' => [
                'goods',
            ],
        ],
        'updateSellContact' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/sell/updateContact',
            'method' => 'post',
            'params' => [
                'id',
                'contact_name',
                'contact_zipcode',
                'contact_province',
                'contact_city',
                'contact_county',
                'contact_address',
                'contact_national',
                'contact_phone'
            ],
        ],
        //============================钱包 相关操作==================================//
		'frozen' => [
			'host' => self::DEFAULT_HOST,
			'url' => '/wallet/freeze',
			'method' => 'get',
			'params' => [
					'userid',
					'amount',
					'remark',
			],
		],
		'unfrozen' => [
			'host' => self::DEFAULT_HOST,
			'url' => '/wallet/unfreeze',
			'method' => 'get',
			'params' => [
					'userid',
					'amount',
			],
		],
        'activeTransfer' => [//转账
            'host' => self::DEFAULT_HOST,
            'url' => '/wallet/distribute',
            'method' => 'post',
            'params' => [
                'remark',
                'amount',
                'userid',
            ],
        ],
        //============================package 相关操作==================================//
        'packageModify' => [/*{{{*/
    		'host' => self::DEFAULT_HOST,
    		'url' => '/package/update',
    		'method' => 'post',
    		'params' => [
    			'id',
    			'online',
    			'description',
    			'deposit_price',
    			'rent_price',
    			'status',
    			'num',
    			'location',
                'type',
                'vip'
    		],
    	],

        //============================godds 相关操作==================================

        'goodsUpdateStatus' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/goods/updateStatus',
            'method' => 'post',
            'params' => [
                'id',
                'status',
                'remark',
            ],
        ],
        'bagReload' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/bag/reloadBag',
            'method' => 'post',
            'params' => [
                'uid'
            ],
        ],
        //============================云控操作==================================
        'getConfigs' => array(/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/Config/gets',
            'method' => 'post',
            'params' => array(
                'region',
                'platform',
                'version',
                'names'
            )
        ),
        'setConfig' => array(/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/Config/set',
            'method' => 'post',
            'params' => array(
                'platform',
                'id',
                'region',
                'name',
                'value',
                'expire',
                'min_version',
                'max_version'
            )
        ),
        'deleteConfig' => array(/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/Config/delete',
            'method' => 'post',
            'params' => array(
                'id'
            )
        ),

        //============================message 相关操作==================================
        'messageAddSell' => [/*{{{*/
            'host' => self::DEFAULT_HOST,
            'url' => '/message/addSell',
            'method' => 'post',
            'params' => [
                'userid',
                'content'
            ],
        ],

        //============================ 租用 ==================================
        'adminRevoke' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/UserRenting/adminRevoke',
            'method' => 'post',
            'params' => [
                'relateid',
            ],
        ],

        'adminBuyRevoke' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/Buying/adminRevoke',
            'method' => 'post',
            'params' => [
                'orderid',
            ],
        ],
        //============================ 物流 ==================================
        'adminSetDeliver' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/Express/adminSetDeliver',
            'method' => 'post',
            'params' => [
                'orderid',
            ],
        ],
        'adminSetReceive' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/Express/adminSetReceive',
            'method' => 'post',
            'params' => [
                'orderid',
            ],
        ],
        'adminSetSentdown' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/Express/adminSetSentdown',
            'method' => 'post',
            'params' => [
                'orderid','company','number'
            ],
        ],
        'adminExpressQuery' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/Express/adminQuery',
            'method' => 'post',
            'params' => [
                'orderid'
            ],
        ],
        //============================ 图床 ==================================
        'uploadImage' => [
            'host' => self::UPLOAD_HOST,
            'url' => '/upload/uploadImage',
            'method' => 'post',
            'params' => [
                'data'
            ],
        ],
        //============================ 七星彩 ==================================
        'setLotteryConfig' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/lottery/adminSetLotteryConfig',
            'method' => 'post',
            'params' => [
                'issue', 'status', 'number'
            ],
        ],
        'setLotteryRun' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/lottery/adminSetLotteryRun',
            'method' => 'post',
            'params' => [
                'issue'
            ],
        ],

        'setWinnerPay' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/lottery/adminSetWinnerPay',
            'method' => 'post',
            'params' => [
                'id'
            ],
        ],
        'addQxcConfig' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/lottery/adminAddQxcConfig',
            'method' => 'post',
            'params' => [
                'issue', 'pt_issue', 'startime', 'endtime'
            ],
        ],
        //============================ 用户 ==================================
        'active' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/user/active',
            'method' => 'post',
            'params' => [
                'source', 'code', 'iv', 'encryptedData'
            ],
        ],
        'getMyUserinfo' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/user/getMyUserInfo',
            'method' => 'post',
            'params' => [],
        ],
        'payPrepare' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/pay/prepare',
            'method' => 'post',
            'params' => [
                'source', 'currency', 'type', 'amount', 'from'
            ],
        ],
        'getJsApiTicket' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/pay/getJsApiTicket',
            'method' => 'post',
            'params' => [],
        ],
        'vipCreateGroup' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/vip/createGroup',
            'method' => 'post',
            'params' => ['orderid'],
        ],
        'vipGroupDetail' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/vip/groupDetail',
            'method' => 'post',
            'params' => [],
        ],
        'getUserInviter' => [
            'host' => self::DEFAULT_HOST,
            'url' => '/invite/getUserInviter',
            'method' => 'post',
            'params' => [],
        ],
        
    ];
}