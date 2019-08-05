<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 2018/6/14
 * Time: 上午10:30
 * Desc: 小程序发送模板消息
 */

class WxProgram
{

    private $baseUrl                = "";
    private $appId                  = "";
    private $secret                 = "";
    private $accessTokenKey         = "";
    private $tokenExpireTime        = "";
    private $tokenCacheDriver       = "";
    private $sendTemplateMsgUrl     = "";
    private $jsApiTicketKey         = "";

    const

        TYPE_PAY_ORDER_SUCCESS      	= 10,   //订单支付成功 租赁
        TYPE_CANCEL_PAY_ORDER       	= 11,   //取消支付成功 租赁
        TYPE_CANCEL_ORDER_SUCCESS   	= 12,   //取消订单成功 租赁
        TYPE_CONFIRM_RECEIVE        	= 13,   //确认收货成功 租赁
        TYPE_RENEWAL_SUCCESS        	= 14,   //续期成功
        TYPE_SELL_ADD_SUCCESS       	= 15,   //分享成功
        TYPE_DONATE_ADD_SUCCESS     	= 16,   //捐赠成功
        TYPE_PAY_BUY_ORDER_SUCCESS  	= 100,   //订单支付成功 购买
        TYPE_CANCEL_BUY_PAY_ORDER   	= 110,   //取消支付成功 购买
        TYPE_CANCEL_BUY_ORDER_SUCCESS	= 120,   //取消订单成功 购买
        TYPE_CONFIRM_BUY_RECEIVE        = 130,   //确认收货成功 购买

    end = '';


    public function __construct()
    {
        $wxConfig               = Context::getConfig("WX_ACCOUNT_CONFIG");

        $this->baseUrl              = $wxConfig['base_url'];
        $this->appId                = $wxConfig['app_id'];
        $this->secret               = $wxConfig['secret'];
        $this->accessTokenKey       = $wxConfig['access_token_key'];
        $this->tokenExpireTime      = $wxConfig['token_expire_time'];
        $this->tokenCacheDriver     = $wxConfig['token_cache_driver'];
        $this->sendTemplateMsgUrl   = $this->baseUrl."/cgi-bin/message/wxopen/template/send?access_token=";
        $this->getTokenUrl          = $this->baseUrl."/cgi-bin/token?grant_type=client_credential&appid=".$this->appId."&secret=".$this->secret;
        $this->jsApiTicketKey       = $wxConfig['js_api_ticket_key'];
    }

    /**
     * 这里是关于模板的 demo 样本
     * 1、下单并支付成功
    模板ID：ZAqhO_8XnMYDNuOEHuy7gsSlYOFNmHPYFyBgxpWb8ao
    【内容】
    订单编号：（订单ID）
    商品名称：（商品名称）
    订单状态：待发货
    下单时间：xxxx年xx月xx日 xx:xx:xx
    订单金额：30葡萄+24元
    收货地址：（收件地址，不包含姓名电话）
    备注：您的订单已支付完成，该商品将在3天后从北京发出，请耐心等待哦~

    2、下单成功并取消支付
    模板ID：XXyuAoeGIUP0231az8DaLwCZBpDrWJsng2-n8gW3fZY
    【内容】
    订单编号：（订单ID）
    商品名称：（商品名称）
    订单状态：待支付
    下单时间：xxxx年xx月xx日 xx:xx:xx
    订单金额：30葡萄+24元
    温馨提示：您的订单尚未完成支付，15分钟后订单将失效，并扣除15颗葡萄，赶快去支付吧~

    3、取消订单成功
    模板ID：gf56qS9UYrtP91QCU_sYE37kca6krjD-D9dXD-vHTB4
    【内容】
    订单编号：（订单ID）
    商品名称：（商品名称）
    订单状态：已取消
    取消时间：xxxx年xx月xx日 xx:xx:xx
    订单退款：30葡萄+24元
    备注：押金20葡萄已解冻，取消订单扣除4葡萄。

    4、确认收货成功
    模板ID：Dt-E5CcMO4boH6YU12vrQDcGsN9P-u49gIUDsRUOFg4
    【内容】
    订单编号：（订单ID）
    商品名称：（商品名称）
    订单状态：租用中
    确认收货时间：xxxx年xx月xx日 xx:xx:xx
    订单金额：30葡萄+24元
    温馨提示：您的订单已签收，租用周期为1个月，从明天起计算，如需续期请在4月15日之前完成哦，最长可以租用6个月。

    5、续期成功
    模板ID：ZAqhO_8XnMYDNuOEHuy7gpgIWNdXzQDsYelKksUBpqA
    【内容】
    订单编号：（订单ID）
    商品名称：（商品名称）
    订单状态：租用中
    支付时间：xxxx年xx月xx日 xx:xx:xx
    订单金额：10葡萄+1元
    温馨提示：您的订单已续期2个月，总租用周期为3个月，如需续期请在6月15日之前完成哦，最长可以租用6个月。


    6、分享闲置提交成功
    模板ID：Y3z9ec_WlCLfyC_-znKCMG4-mwh7MfGk6LFDnVByV6w
    【内容】
    商品信息：5件宝贝
    订单金额：40葡萄
    订单状态：待审核
    地址：（取件地址）
    备注：您的订单正在审核中，审核通过后将有快递小哥联系您免费上门取件，请您提前准备好闲置宝贝并留意接听电话。

    7、捐赠闲置提交成功
    模板ID：Y3z9ec_WlCLfyC_-znKCME45pLH0W5XsPoGSX0sPB8U
    【内容】
    订单号：（订单ID）
    地址：（取件地址）
    备注：稍后快递小哥将会联系您免费上门取件，请您提前准备好闲置宝贝并留意接听电话，感谢您的善举。
     */

    /**
     * @param string $type
     * @return bool|mixed
     * @desc 通过 type 获取模板 ID
     */
    public static function getTplIdByType($type='')
    {
        $tplArr = [
            self::TYPE_PAY_ORDER_SUCCESS        => 'ZAqhO_8XnMYDNuOEHuy7gsSlYOFNmHPYFyBgxpWb8ao',
            self::TYPE_CANCEL_PAY_ORDER         => 'XXyuAoeGIUP0231az8DaLwCZBpDrWJsng2-n8gW3fZY',
            self::TYPE_CANCEL_ORDER_SUCCESS     => 'gf56qS9UYrtP91QCU_sYE37kca6krjD-D9dXD-vHTB4',
            self::TYPE_CONFIRM_RECEIVE          => 'Dt-E5CcMO4boH6YU12vrQDcGsN9P-u49gIUDsRUOFg4',
            self::TYPE_RENEWAL_SUCCESS          => 'ZAqhO_8XnMYDNuOEHuy7gpgIWNdXzQDsYelKksUBpqA',
            self::TYPE_SELL_ADD_SUCCESS         => 'Y3z9ec_WlCLfyC_-znKCMG4-mwh7MfGk6LFDnVByV6w',
            self::TYPE_DONATE_ADD_SUCCESS       => 'Y3z9ec_WlCLfyC_-znKCME45pLH0W5XsPoGSX0sPB8U',
        	self::TYPE_CONFIRM_BUY_RECEIVE  	=> 'Dt-E5CcMO4boH6YU12vrQDcGsN9P-u49gIUDsRUOFg4',
        	self::TYPE_CANCEL_BUY_PAY_ORDER		=> 'gf56qS9UYrtP91QCU_sYE37kca6krjD-D9dXD-vHTB4'

        ];
        return isset($tplArr[$type]) ? $tplArr[$type] : false;
    }

    public function sendTemplateMessage($userId, $type, $data, $param=[], $isBuy = 0, $color = '', $emphasisKeyword = '')
    {

        Logger::log('wechat_log', 'send_param',  [json_encode([$userId, $type, $data, $param, $color, $emphasisKeyword])]);

        $user = User::getUserInfo($userId);

        if (empty($user) || empty($user['openid'])) {
            return false;
        }
        $userOpenId = $user['openid'];

        if (empty($type)) {
            Logger::log('wechat_log', 'error_type',  [json_encode([$userId, $type, $data, $param, $color, $emphasisKeyword])]);
            return false;
        }

        $formId = Context::get("wx_program_form_id");

        if (empty($formId)) {
            Logger::log('wechat_log', 'error_form_id',  [json_encode([$userId, $type, $data, $param, $color, $emphasisKeyword])]);
            return false;
        }

        if (empty($data)) {
            Logger::log('wechat_log', 'error_data',  [json_encode([$userId, $type, $data, $param, $color, $emphasisKeyword])]);

            return false;
        }

        $token = $this->getToken();

        if ($token) {

            $url = $this->sendTemplateMsgUrl.$token;

            $temp = [];
            foreach ($data as $key => $value)
            {
                $key = $key + 1;
                $temp['keyword'.$key] = [
                    'value' => $value
                ];
            }

            $templateId = self::getTplIdByType($type);
            if (!empty($isBuy)) {
            	$type = $type . 0;
            }
            $page = self::getPageByType($type, $param);

            $param = [
                'touser'        => $userOpenId,
                'template_id'   => $templateId,
                'form_id'       => $formId,
                'page'          => $page, // 跳转参数
                'data'          => $temp
            ];

            $header = [
                'content-type'=> 'application/json'
            ];
            Logger::log('wechat_log', 'send_info',  [json_encode($param)]);
            $result = Util::myCurl($url, $header, $param, true);
            Logger::log('wechat_log', 'return_info',  [json_encode($result)]);

            $result = json_decode($result, true);

            if ($result['errcode']) {
                return false;
            }
            return true;

        } else {
            Logger::log('wechat_log', 'empty token',  json_encode([$userId, $type, $formId, $data, $param, $color, $emphasisKeyword]));
        }
    }

    /**
     * @param $type
     * @param array $param
     * @return mixed|string
     * @desc 通过 type 获取跳转 page 参数
     */
    public static function getPageByType($type, $param=[])
    {
        if (empty($type)) {
            return '';
        }

        $tplArr = [
            self::TYPE_PAY_ORDER_SUCCESS        => '/page/user/pages/myBorrow',
            self::TYPE_CANCEL_PAY_ORDER         => '/page/user/pages/myBorrow',
            self::TYPE_CANCEL_ORDER_SUCCESS     => '/page/user/pages/myBorrow',
            self::TYPE_CONFIRM_RECEIVE          => '/page/user/pages/myBorrow',
            self::TYPE_RENEWAL_SUCCESS          => '/page/user/pages/myBorrow',
            self::TYPE_SELL_ADD_SUCCESS         => '/page/user/pages/myShare',
            self::TYPE_DONATE_ADD_SUCCESS       => '/page/index/index',
        	self::TYPE_PAY_BUY_ORDER_SUCCESS	=> '/page/user/pages/myBuy',
        	self::TYPE_CANCEL_BUY_PAY_ORDER		=> '/page/user/pages/myBuy',
        	self::TYPE_CANCEL_BUY_ORDER_SUCCESS	=> '/page/user/pages/myBuy',
        	self::TYPE_CONFIRM_BUY_RECEIVE		=> '/page/user/pages/myBuy',
        ];
        $confPage = isset($tplArr[$type]) ? $tplArr[$type] : '';
        if ($confPage && !empty($param)) {
            $confPage .= "?";
            foreach ($param as $key => $value)
            {
                $confPage.= $key."=".$value."&";
            }
        }

        return $confPage;
    }



    protected function getToken()
    {
        $cache = new Cache($this->tokenCacheDriver);
        $token = $cache->get($this->accessTokenKey);

        if ($token) {
            return $token;
        }

        $result = Util::myCurl($this->getTokenUrl);
        // demo
        // {"access_token": "ACCESS_TOKEN", "expires_in": 7200}

        $data = json_decode($result, true);
        if (!isset($data['access_token']) || empty($data['access_token'])) {
            // 日志记录
            Logger::log('wechat_log', 'get_token_error',  [json_encode($data)]);
            return false;
        }

        $cache->add($this->accessTokenKey, $data['access_token'], $this->tokenExpireTime);

        return $data['access_token'];
    }

    //小程序码
    public function getUserInviteWxacodeunlimit($uid)
    {
        $filename = "wxcode/userinvite".$uid;
        $storage = new Storage();
        try{
            $content = $storage->getContent($filename);
        }catch(Exception $e){

        }

        if($content){
            return $content;
        }
        
        $token = $this->getToken();

        if ($token) {
            $url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=$token";

            $param = [
                'scene'      => "uid={$uid}&taskid=5&type=1",
                'page'       => "page/index/index",
                'width'      => 100,
                'is_hyaline' => true
            ];

            $content = Util::myCurl($url, [], $param, true);

            $storage->addFile($filename, $content);

            return $content;
        }

        return "";
    }

    public function getJsApiTicket()
    {
        $cache = new Cache($this->tokenCacheDriver);
        $ticket = $cache->get($this->jsApiTicketKey);

        if ($ticket) {
            return $ticket;
        }

        $token = $this->getToken();
        if ($token) {
            
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$token";

            $result = Util::myCurl($url);
            $res = json_decode($result, true);
            if (!isset($res['ticket']) || empty($res['ticket'])) {
                // 日志记录
                Logger::log('wechat_log', 'get_ticket_error',  [$result]);
                return false;
            }
    
            $cache->add($this->jsApiTicketKey, $res['ticket'], $this->tokenExpireTime);
    
            return $res['ticket'];
        }

        return false;
    }

}
