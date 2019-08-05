<?php
include_once("./../4/code/BaseLogic.php");
include_once("./../4/code/Optimus.php");
include_once("./../4/code/ForgePublicId.php");
$public = new ForgePublicId(144412);
$encode_id = $public->publicId();
$decode_id = \ForgePublicId::optimusDecode(1665037943);
var_dump($encode_id);

echo urldecode("%E5%8C%97%E4%BA%AC%E5%B8%82");

var_dump($decode_id);exit;
/*
select suite_id from Laputa.rooms where id = 145685;
select * from Laputa.contract_with_landlords where suite_id = 74281 and status = '已签约' and stage = '执行中'
SELECT a.code,a.room_number as roomNumber,a.price,a.area as roomArea,a.face,a.rent_type as rentType,a.month_price as monthPrice,
        a.status as roomStatus,a.images,a.city_name as cityName,a.suite_id as suiteId,a.has_toilet as hasToilet,a.has_shower as hasShower,a.has_balcony as hasBaloncy,
        a.style,a.version,a.is_rent_furniture as isRentFurniture,a.search_text as searchText,a.has_tv as hasTv,a.has_terrace as hasTerrace,a.has_storeroom as hasStoreroom,a.has_video as hasVideo,
        a.videos,a.video_rack_status as videoRackStatus,a.is_abs as isAbs,a.is_separated_room as isSeparatedRoom,a.bedroom_type as bedroomType,
        b.status as  suiteStatus,b.xiaoqu_id as xiaoquId,b.floor,b.total_floor as totalFloor,b.intro,b.bedroom_num as bedroomNum,b.public_space_num as publicSpaceNum,b.area as suiteArea,
        b.has_lift as hasLift,b.built_years as builtYears,b.address,b.type,b.is_month as isMonth,b.sign_type as signType, b.toilet_num as toiletNum,b.zhuangxiu_end_date as zhuangxiuEndDate,b.heating,b.ready_for_rent_date as readyForRentDate,rent_end_date as rentEndDate,b.source,b.equip_end_date as equipEndDate,
        c.district,c.name as xiaoquName,c.intro as xiaoquIntro,c.traffic_situation as trafficSituation,c.latitude as xiaoquLatitude,c.longitude as xiaoquLongitude, c.block,c.subway_duration as subwayDuration,c.city as xiaoquCity,
        c.lng_lat as xiaoquLngLat,c.subway_id as subwayId,c.block_id as blockId, d.lines,d.name as subwayName,d.city as subwayCity,d.lng_lat as subwayLngLat,e.name as areaName,f.images as kujialeImages
                FROM rooms as a

                right join suites as b on a.suite_id = b.id
                left join suite_decoration_kujiale as f on b.id = f.suite_id
                right join xiaoqus as c on b.xiaoqu_id = c.id
                right join subways as d on c.subway_id =d.id
                right join areas as e on c.block_id = e.id

                WHERE id=369400  LIMIT 1
                */
/*
1、取预配单配置id
select * from Laputa.danz_decoration_config_bills order by id desc
2、根据配置id取房子区域列表
select * from Laputa.danz_decoration_config_areas where config_id = 55;
select * from Laputa.danz_decoration_config_areas where suite_id = 127771
3、根据区域id获取物品id
select products_id, area_id,subject from Laputa.danz_decoration_config_area_details where area_id = 558
and suite_id = 127771
and cost_type = '配置'

purchasing_tasks 新风寓
web_sug_configurations
user_message_notifications 通知推送
humans 身份信息
danz_decoration_config_area_details  配置区域详情
danz_decoration_config_areas 配置区域
danz_decoration_config_bills 配置id
danz_products 配置项
contract_with_landlords  收房合同
contract_with_customers  出房合同
aroundList
logistic_associate_items
prepare_config_bills
prepare_config_bill_items
supple_configs

mapi

*/