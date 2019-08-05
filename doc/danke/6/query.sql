//降价push日志
A8AQJBHJ67D1L23Q
ALTER  TABLE  `user_foot_daily`  ADD  INDEX index_created_at (`created_at`)
select * from Mapi.price_reduction_push

select uid,number,taskid,query,addtime,modtime,status,city_name,channel from trainee_task

select * from Mapiactivity.trainee_task where uid = 759018;

select distinct(user_id), public_id from Mapi.room_views_2 where room_id = 124312 AND created_at > '2019-06-10 17:35:32'

select distinct(user_id), public_id from Mapi.room_collect_2 where room_id = 124312 AND cancel = 'N' AND created_at > '2019-05-31 18:35:32'

select * from Laputa.users where id in(321732,758987,762165)

http://172.18.130.29:8081/callback/pangu/dealer-block/check-dealers eyJkYXRhIjpbMTU2NTksNTc5MV0sInQiOjE1NjExOTE3MjUsInRva2VuIjoiNGFhMjdhNTI3NDc5YmFiNTI4NTdjNGZmOTJlYzJiMzQifQ==
//小区白名单
1105,52459,7035,3301,36

1105,7035,3301,36,816

select count(*) from Mapi.`sale_passenger`;
select * from Mapi.`sale_passenger` order by id desc ;

select count(*) from Laputa.telecom_services where dealer_id = 6694 limit 10;
select * from Laputa.telecom_services where dealer_id = 6694 limit 10;
select * from Laputa.corp_users where id = 5439 limit 10;
select * from Laputa.telecom_services where dealer_id = 5439 limit 10;
select gender,id,name,avatar,workplace,status,mobile,getui_cid,workplace,dingtalk_id from Laputa.corp_users where id = 29210;
select * from Laputa.corp_users where name = '郑镇宝'
2174862,892706
select id from Laputa.users where mobile = '15811412717'
SELECT attachment,
       exam_number,
       name,
       mobile,
       idcard,
       gender,
       school,
       department,
       major,
       graduation_date,
       auto,
       extends,
       audit_result
FROM Mapiactivity.starling_user
WHERE auto= '手填' and audit_result = '通过'
ORDER BY id DESC
LIMIT 10;
select * from Mapi.price_reduction_push limit 10;
学籍身份已认证蛋生计划
select count(*) from Mapi.sale_user_log where addtime > '2019-06-27 00:00:00' and sales = '[1]'limit 10;

select count(*) from Mapi.sale_user_log where addtime > '2019-06-24 00:00:00' and addtime > '2019-06-30 00:00:00' and sales = '[1]' limit 10;

select count(distinct(uid)) from Mapi.sale_user_log where addtime > '2019-06-24 00:00:00' and addtime > '2019-06-30 00:00:00' and sales = '[1]' limit 10;

select count(*) as cnt, date_format(addtime,'%Y-%m-%d') AS dat from Mapi.sale_user_log where sales = '[1]' group by dat order by dat desc;

select count(distinct(uid)) as cnt, date_format(addtime,'%Y-%m-%d') AS dat from Mapi.sale_user_log where sales = '[1]' group by dat order by dat desc;


SELECT count(*)
FROM Mapi.sale_user_log
WHERE addtime > '2019-06-26 00:00:00'
  AND addtime < '2019-06-27 00:00:00'
  AND sales = '[1]'
LIMIT 10;

select * from Mapi.sale_user_log order by id desc limit 10;

SELECT count(*) AS cnt
FROM Mapi.sale_passenger
WHERE sale_id = 6694
  AND status = '分配中'
  AND addtime > '2019-06-14 00:00:00'
  AND addtime < '2019-06-14 23:59:59'
LIMIT 1

SELECT count(*) AS cnt
FROM Mapi.sale_passenger
WHERE sale_id = 6694
  AND status = '已分配'
  AND addtime > '2019-06-14 00:00:00'
  AND addtime < '2019-06-14 23:59:59'
LIMIT 1

SELECT count(*) AS cnt
FROM Mapi.sale_passenger
WHERE sale_id = 7539
  AND status = '分配中'
  AND addtime > '2019-06-14 00:00:00'
  AND addtime < '2019-06-14 23:59:59'
LIMIT 1

SELECT count(*) AS cnt
FROM Mapi.sale_passenger
WHERE sale_id = 7539
  AND status = '已分配'
  AND addtime > '2019-06-14 00:00:00'
  AND addtime < '2019-06-14 23:59:59'
LIMIT 1

//查询某主体不显示分期月付
SELECT city_name,
       code,
       id,
       status
FROM Laputa.rooms
WHERE suite_id IN
    (SELECT suite_id
     FROM Laputa.contract_with_landlords
     WHERE subcompany_id IN (28,
                             31) ) and status = '可出租'

//获取房子价格 单个
select a.city_name,a.id,a.code,a.price,a.search_text,a.month_price,b.is_month,a.status,a.available_date,a.rent_end_date,b.rent_end_date
from Laputa.rooms as a
left join Laputa.suites as b
on a.suite_id = b.id
where a.id in ('127005','142577');

select a.images,a.city_name,a.id,a.code,a.price,a.search_text,a.month_price,a.status,a.available_date,a.rent_end_date
from Laputa.rooms as a
where a.code = '34072-A';


select xiaoqu_id from Laputa.suites where id = 34072;

select * from Laputa.xiaoqus where id = 5901;

select * from Laputa.subways where id = 188;

//查询销售
SELECT *
FROM Laputa.corp_users
WHERE `workplace` = '北京'
  AND `position` = '出房销售'
  AND status = 'active'

//去重订阅次数
SELECT city_name,
       count(*) AS cnt
FROM Mapi.subscribe
WHERE addtime > "2019-05-20 00:00:00"
GROUP BY city_name;

//订阅次数
SELECT city_id,
       count(*) AS cnt
FROM Mapi.subscribe_relate
WHERE addtime > "2019-05-20 00:00:00"
  AND deleted = 'N'
GROUP BY city_id;

//订阅人数
SELECT city_id,
       count(distinct(uid)) AS cnt
FROM Mapi.subscribe_relate
WHERE addtime > "2019-05-20 00:00:00"
  AND deleted = 'N'
GROUP BY city_id;
