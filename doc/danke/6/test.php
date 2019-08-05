<?php

$a = '{\"\\u516c\\u5171\\u533a\\u57df\":[\"\\u667a\\u80fd\\u9501\"],\"\\u536b\\u751f\\u95f4\":[\"\\u70ed\\u6c34\\u5668\",\"\\u6d17\\u8863\\u673a\"],\"\\u53a8\\u623f\":[\"\\u62bd\\u6cb9\\u70df\\u673a\",\"\\u51b0\\u7bb1\",\"\\u5fae\\u6ce2\\u7089\"],\"C\":[\"\\u5e8a\",\"\\u8863\\u67dc\",\"\\u4e66\\u684c\",\"\\u7a7a\\u8c03\",\"\\u5e8a\\u5355\",\"\\u6795\\u82af\",\"\\u6795\\u5957\"]}';

$b = json_decode($a, true);
print_r($b);
/*



resources/views/pangu/home/mobile_list_base.blade.php

for ($i = 0; $i<10;$i++) {

$table = "CREATE TABLE `room_views_{$i}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `room_id` int(11) NOT NULL DEFAULT '0' COMMENT '房间id',
  `public_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '可公开id',
  `rent_type` varchar(16) COLLATE utf8mb4_bin NOT NULL DEFAULT '0' COMMENT '租房形式',
  `price` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `created_at` datetime NOT NULL COMMENT '创建时间',
  `updated_at` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index` (`user_id`,`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
";
echo $table;
}


alter table `Mapi`.`price_reduction_push` add `collect_user` longtext COMMENT '关注用户id' after `send_date` ,add `view_user` longtext  COMMENT '浏览用户id' after `collect_user`;

select * from Mapi.price_reduction_push

select distinct(user_id), public_id from Mapi.room_views_2 where room_id = 124312 AND created_at > '2019-06-10 17:35:32'

select distinct(user_id), public_id from Mapi.room_collect_2 where room_id = 124312 AND cancel = 'N' AND created_at > '2019-05-31 18:35:32'

select * from Laputa.users where id in(321732,758987,762165)

$danke_steward = [
                'sale_entrance'     => true,
                'sale_has_apply'    => 1,//1 默认 | 2 等待分配 | 3 已分配未到达| 4已到达
                'sale_info'         => [
                		"name"		=> "张三",
                		"avatar" 	=> "",
                		"gender"	=> "男",
                		"id"		=> 2,
                		"phone"		=> 13400000000,
                		"content"	=> "",
                		"tags"		=> ['管家']
                	],
                'sale_desc'         => '我带看过本房，清楚本房特色，我正向您飞驰而来，请您耐心等待',
                'buttonText'        => '联系管家',
                'record_id'         => 11,
            ];

            $result['danke_steward'] = $danke_steward;

            echo json_encode($result);

sale_passenger_id
$a = [
	"name"	=> "张三",
	"gender"=> "男"
];

echo json_encode($a);

redis-cli -h 172.18.130.5 -p 6379

GEORADIUS geohash:sales:position_1 116.4485549927 39.8931433124 12 km WITHDIST WITHCOORD
GEORADIUS geohash:sales:position_1 116.4485549927 39.8931433124 12 km WITHDIST WITHCOORD WITHHASH COUNT 20

GEORADIUS geohash:sales:position_1 116.4527599135 39.8930995819 12 km WITHDIST WITHCOORD
GEOPOS geohash:sales:position_1 6694
ZRANGE sale_list_rank_782 0 -1 WITHSCORES
ZREVRANGE sale_list_rank_782 0 -1 WITHSCORES
 */