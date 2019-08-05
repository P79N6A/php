
//入职大于28天非主管
SELECT base_data.id,
       base_data.`name`,
       user_dep.department_id FROM
  (SELECT *
   FROM Laputa.corp_users
   WHERE TIMESTAMPDIFF(DAY, created_at, '2019-05-31') >= 28
     AND `status` = 'active'
     AND NOT EXISTS
       (SELECT *
        FROM Laputa.corp_departments
        WHERE corp_users.id = corp_departments.leader_id)) base_data
INNER JOIN Laputa.corp_user_departments AS user_dep ON base_data.id = user_dep.staff_id
WHERE user_dep.department_id IS NOT NULL AND((EXISTS
                                                (SELECT *
                                                 FROM Laputa.coterie_block_teams
                                                 WHERE coterie_block_teams.team_id = user_dep.department_id)) OR(EXISTS
                                                                                                                   (SELECT *
                                                                                                                    FROM Laputa.super_block_teams
                                                                                                                    WHERE super_block_teams.team_id = user_dep.department_id)))

//今日打卡
select * from Laputa.check_sales order by id desc limit 10

6694马思敏

select sale_id, sum(week_look) from `sale_bigdate_count` where city_id = 540 and `block_id` != 743 and sale_id=4113 group by sale_id



//获取房子价格 单个
select a.city_name,a.id,a.code,a.price,a.search_text,a.month_price,b.is_month,a.status,a.available_date,a.rent_end_date,b.rent_end_date
from Laputa.rooms as a
left join Laputa.suites as b
on a.suite_id = b.id
where a.id in ('127005','142577');

select a.images,a.city_name,a.id,a.code,a.price,a.search_text,a.month_price,a.status,a.available_date,a.rent_end_date
from Laputa.rooms as a
where a.code = '25887-A';

/*
当房源主体为“杭州建信爱上租住房服务有限公司”，“建信住房服务（浙江）有限责任公司”的房源，
所有端（APP\PC\H5\小程序）详情页不展示有关“分期月付免手续费”且付款方式中不展示分期月付
*/
select * from Laputa.danke_subcompanies where name like '%杭州建信爱上租住房服务有限公司%' or name like '%建信住房服务（浙江）有限责任公司%' limit 10;

select suite_id from Laputa.contract_with_landlords  where  subcompany_id in (28,31) limit 10;

select subcompany_id from Laputa.contract_with_landlords  where  suite_id = 23866 limit 10;
select subcompany_id from Laputa.contract_with_landlords  where  suite_id = 85789 limit 10;

select * from Laputa.purchasing_tasks where type = '新风' limit 10;
//获取新风
select prepare_id from Laputa.purchasing_tasks where suite_id = 23656 and type = '新风' limit 1
select prepare_id from Laputa.purchasing_tasks where type like '%新风%' limit 10;
select * from Laputa.supple_config_items where supple_config_id=18030;

//查公寓信息
select is_month,xiaoqu_id,rent_end_date,equip_end_date from Laputa.suites where id = 941;
//查小区
select district,block,block_id from Laputa.xiaoqus where id = 7183;

//查区域
select * from Laputa.areas where id = 35;
//获取房子价格 多个
select a.city_name,a.id,a.code,a.price,a.search_text,a.month_price,b.is_month,a.status,a.available_date,a.rent_end_date,b.rent_end_date
from Laputa.rooms as a
left join Laputa.suites as b
on a.suite_id = b.id
where a.code in ('167-D','600-C', '167-C')


vendor

select * from Mapi.room_view_push_log where push_date = '20190514';

select * from Mapi.subscribe_push_log where uid = 763882;
select * from Mapi.subscribe where id = 136;
//订阅push
SELECT *
FROM Mapi.subscribe_push_log
WHERE UID = 763882
  AND push_date = '20190513'
ORDER BY price ASC,
         faceSouth DESC,
         hasToilet DESC,
         area DESC,
         room_id ASC
LIMIT 20

//查消息浏览push
SELECT *
FROM Laputa.user_message_notifications
WHERE user_id = 759018
  AND TYPE = '推荐商圈'
  AND push_time > '2019-05-10 00:00:00'
ORDER BY id DESC
LIMIT 1
//亮
SELECT *
FROM Laputa.user_message_notifications
WHERE user_id = 763959
  AND TYPE = '推荐商圈'
  AND push_time > '2019-05-10 00:00:00'
ORDER BY id DESC
LIMIT 10

SELECT id,name,param FROM Mapi.subscribe  WHERE 1=1  and city_name = '天津市'  and (district = '河西区' or district = '' or district = '南楼')  and (block = '南楼' or block = '')  and (rent_type like '蛋壳租房_分租%' or rent_type = '')  and (room_type like '%2%' or room_type = '')  and ((min_price <= 1460 and max_price >= 1460) or (min_price = '0.00' and max_price = '0.00'))  and (min_area = '0' and max_area = '0')  and (status like '现房%' or status = '')  and (direction like '西%' or direction = '')  and (style like 'MUJI风%' or style = '')  and isMonth = ''  and hasVideo = ''  and (heating = '是' or heating = '')  and brand = ''  and (isNearSubway = '是' or isNearSubway = '')  and hasShower = ''  and (hasBalcony = '是' or hasBalcony = '')  and hasToilet = ''


//查用户
select * from Laputa.users where mobile = '18500433927';
select * from Laputa.users where mobile = '13625802580';//林泽亮763959
select * from Laputa.users where mobile = '13701383184';//759018
//查单个人的推送
select id,title,content,push_time,status,push_result from Laputa.user_message_notifications where user_id = 759018 order by id DESC limit 5
SELECT id,
       title,
       content,
       push_time,
       status,
       push_result
FROM Laputa.user_message_notifications
WHERE user_id = 763882
ORDER BY id DESC
LIMIT 5
//查询单个人的推送
SELECT id,
       title,
       content,
       push_time,
       status,
       push_result
FROM Laputa.user_message_notifications
WHERE user_id = 763959
ORDER BY id DESC
LIMIT 5
//查询重复推送
SELECT count(*) from (
SELECT count(*) AS cnt,
       user_id
FROM Laputa.user_message_notifications
WHERE push_time > '2019-05-08 00:00:00'
  AND push_time < '2019-05-08 23:59:59'
GROUP BY user_id
HAVING cnt > 1

) AS a
//查询重复推送
SELECT count(*) from (
SELECT count(*) AS cnt,
       user_id
FROM Laputa.user_message_notifications
WHERE push_time > '2019-05-10 00:00:00'
  AND push_time < '2019-05-10 23:59:59'
  AND title = '温馨阳光房推荐~'
  AND status = 1
GROUP BY user_id
HAVING cnt > 1
) AS a

update Laputa.rooms set available_date = '2019-05-08', status = '可出租' where

//订阅推送
SELECT *
FROM Laputa.rooms
WHERE available_date = '2019-05-09'
  AND status = '可出租'
//
SELECT id,
       name,
       param
FROM Mapi.subscribe
WHERE 1=1
  AND city_name = '北京市'
  AND (district = '立水桥'
       OR district = '')
  AND (BLOCK = '北京市'
       OR BLOCK = '')
  AND (rent_type LIKE '合租%'
       OR rent_type = '')
  AND (room_type LIKE '%3%'
       OR room_type = '')
  AND ((min_price <= 3190
        AND max_price >= 3190)
       OR (min_price = '0.00'
           AND max_price = '0.00'))
  AND (min_area = '0'
       AND max_area = '0')
  AND (status LIKE '配置中%'
       OR status = '')
  AND (direction LIKE '南%'
       OR direction = '')
  AND (style LIKE '工业风%'
       OR style = '')
  AND isMonth = ''
  AND hasVideo = ''
  AND (heating = '是'
       OR heating = '')
  AND (brand = '是'
       OR brand = '')
  AND isNearSubway = ''
  AND hasShower = ''
  AND (hasBalcony = '是'
       OR hasBalcony = '')
  AND hasToilet = ''

//查订阅
SELECT b.name,b.param,b.district,a.deleted
FROM Mapi.subscribe_relate AS a
LEFT JOIN Mapi.subscribe AS b
ON a.subscribe_id = b.id
WHERE a.uid = 762165 and a.deleted = 'N'

select * from Mapi.subscribe_relate where uid = 1302725 order by id desc limit 5,1;
SELECT *
FROM Mapi.subscribe_relate
WHERE UID = 762165
ORDER BY id DESC;

select * from Laputa.xiaoqus where name like '%北京-空港国际%'

SELECT count(*) AS cnt,
       user_id
FROM Laputa.user_message_notifications
WHERE push_time > '2019-05-10 00:00:00'
  AND push_time < '2019-05-10 23:59:59'
  AND title = '温馨阳光房推荐~'
  AND status = 1
GROUP BY user_id
HAVING cnt > 1;


288


select count(*) from Mapi.subscribe_relate where uid = 763882
select count(*) as cnt, uid from Mapi.subscribe_relate group by uid having cnt > 5;

select * from Laputa.xiaoqus where name like '%空港国际%'


//获取椋鸟计划点击量
select * from Mapiactivity.starling_click order by id desc limit 10;

select status,mobile,idcard,auto,audit_result,graduation_date,sign_date,sign_status from starling_user where auto = '抓取'

select contract_no,sign_date,sign_status,contract_city,contract_status from starling_user where sign_status = '已签约';

select contract_no,sign_date,sign_status,contract_city,contract_status from starling_user where audit_result = '通过' and sign_status = '已签约';

select id,uid,name,exam_number,idcard,mobile,gender,school,major,human_id,audit_result,contract_no,sign_date,sign_status,contract_city,contract_status from starling_user where id = 892;

select * from starling_task where number = 'A0Z1RAUE2DXHM1Z1'

select count(*) from starling_user where audit_result = '通过';

select count(*) from starling_task where status = 'Y';

select count(*) from starling_user where audit_result = '通过' and sign_status = '已签约';
select count(*) from starling_user where audit_result = '通过' and auto = '抓取';

select auto,audit_result,contract_no,sign_date,sign_status,contract_city,contract_status,human_id from Mapiactivity.starling_user where uid = 763605

SELECT * FROM contract_with_customers WHERE stage = '执行中' and status = '已签约' and customer_id = 264407 ORDER BY stage DESC, id DESC LIMIT 1;
select count(*) from Laputa.rooms where bedroom_type != '' and status = '可出租'