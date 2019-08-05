<?php
include_once("BaseLogic.php");
include_once("Optimus.php");
include_once("ForgePublicId.php");
$public = new ForgePublicId(8807);

var_dump($public->publicId());

$decode_id = \ForgePublicId::optimusDecode(1424561697);



var_dump($decode_id);


$a = md5("8150716192f275fde017e44bb29f79f186dcfe3422");

var_dump($a);exit;
/**
 *
 *
 * 'env' => 'test',
    'java_host_common'=>'boss.danke.life',
    'java_host_room_es' => '172.21.50.38:8383',
    'java_host_promotion' => '172.18.130.13:8080',
    'java_host_es' => '172.21.50.10',
    'api_auth_config'=>[],
    'api_access_config'=>[],
    'url'   => 'http://www.dankegongyu.com/',
    'room_promotion_new' => '1',
    'java_caiji_es' => ['172.21.40.31:9200']
select status,id,updated_at,area,isz_house_id,isz_room_no from Laputa.rooms where code = '113708-A';



https://www.danke.com/pangu-activity/marketing/custom-house-resource/district/178
$user = \User::whereMobile($mobile)->first() ?: \User::newFromApplet($mobile);
$user = \User::whereUniqueId($info['unionId'])->first();
$info_set = [
            'mobile'    => $mobile,
            'nickname'  => $info['nickName'],
            'sex'       => $info['gender'],
            'headimgurl' => $info['avatarUrl'],
            'province'  => $info['province'],
            'city'      => $info['city'],
            'unique_id' => $info['unionId'],
            'open_id'   => $info['openId']
        ];
$login_tag = \User::registerByApplet($info_set);

$user = \User::where($term, $data)->first();
getEffectiveCustomerContracts
$user->getAvatar(),

return WithCustomer::where('stage', WithCustomer::STAGE_执行中)
            ->whereCustomerId($this->id)
            ->get();
https://api.room.danke.com/api/v1/configure/sugText?city_id=1&q=%E4%B8%89%E9%87%8C%E5%B1%AF&timestamp=1545968194

public static function newFromApplet($mobile)
    {
        $user = new self;
        $user->mobile = $mobile;
        $user->login_type = self::LOGIN_TYPE_APPLET;
        $user->nickname = preg_replace('@([0-9]{3})([0-9]{4})([0-9]{4})@', '$1****$3', $mobile);

        return $user;
    }




sudo ln -s /Applications/Xcode.app/Contents/Developer/Platforms/MacOSX.platform/Developer/SDKs/MacOSX.sdk/usr/include/ /usr
 *#select id,room_id,start_date from Laputa.contract_with_customers where stage ='执行中' and status = '已签约' and room_id IN (select id from Laputa.rooms where suite_id = '35565')

 * select id,room_number from Laputa.rooms where suite_id = '35565';
//
//    public function testAction()
//    {
//        $appRoom = new SearchRoom();
//        $a = [];
//        $a['xiaoquLonLat'] = '39.936207,116.190006';
//        $a['subwayLonLat'] = '39.932391,116.18407';
//        $a['subwayTitle'] = '苹果园';
//        $a['subwayLines'] = '1号线,6号线,S1线';
//        $string = $appRoom->nearestSubwayTitleEs($a);
//        var_dump($string);exit;
//    }
humans 单独采集 我自己在开发测试环境建了 laputa_humans
contract_with_customers 单独采集 我自己在开发测试环境建了laputa_contract_customers
areas 单独采集 我自己在开发测试环境建了  laputa_room_area
xiaoqus 单独采集 我自己在开发测试环境建了  laputa_room_xiaoqu
logistic_associate_items 单独采集 laputa_logistic_associate_items
Fico库的bi_communitiy_ext_infos表 单独采集
mapi_laputa_contract_with_landlords

mapi_laputa_configuration

业务1和业务2能否合并组建一个索引
业务1：suites表的关联表 2层
suite_decorations suites关联表

业务2：suites表的关联表 两个  3层
prepare_config_bills suites关联表
prepare_config_bill_items prepare_config_bills关联表 要这里的字段
supple_configs suites关联表
supple_config_items supple_configs关联表 要这里的字段
prepare_config_bills
prepare_config_bill_items
supple_configs
supple_config_items

git push origin --delete pangu-master-bugfix-xbg-20181207
git branch -d pangu-master-bugfix-xbg-20181207

详情页 登录和未登录记录足迹	      已完成     v7/room/detail
关注走库						  已完成     v7/collect/room-list
足迹走库						  已完成		v7/user/foot-list
app未登录按设备列表			  进行中		接口名子待定
app从未登录到已登录把数据同步    进行中     在登录逻辑调已经写好的队列方法
上线前关注数据导入				  未开始
筛选条件加面积和入住日期		  已完成     v5/room/conds-list
列表加紧迫感					  未开始		v7/search/list
列表加热门筛选				  未开始		v7/search/list
详情加紧迫感					  未开始		v7/room/detail
预约加紧迫感					  未开始
详情小区周边20字以下优化		  进行中 	v7/room/detail

*/

