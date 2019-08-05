<?php


/**
 *
select id from Laputa.danz_decoration_config_bills where suite_id = 127800;
select id,area from Laputa.danz_decoration_config_areas where config_id = 83
897	A卫生间
898	卫生间1
899	公共区域1
915	厨房1
918	A

SELECT products_id,
       area_id
FROM Laputa.danz_decoration_config_area_details
WHERE area_id = 918
  AND goods_id <> 0
  AND cost_type = '配置'
  AND (is_goods_select = '是'
       OR pck_products_necessary = '是')

SELECT code,
       city_name
FROM Laputa.rooms
WHERE suite_id IN
    ( SELECT id
     FROM Laputa.suites
     WHERE SOURCE = '直营蛋仔')
  AND status = '可出租'

 * @var string
 */
$a = "{\"A\":{\"key\":[\"\\u5e8a\",\"\\u6c99\\u53d1\"],\"label\":[[\"\\u5165\\u5e93\\u7c7b-1.2*2.0\\u5e38\\u89c4\\u5e8a\",\"0.8*0.55*0.75\\u5e38\\u89c4\\u684c\\u5b50\",\"\\u5165\\u5e93\\u7c7b-1.6*0.8*0.75\\u6c99\\u53d1\"]]},\"\\u536b\\u751f\\u95f4\":{\"key\":[\"\\u5e8a\",\"\\u6c99\\u53d1\",\"\\u7a7a\\u8c03\",\"\\u6d17\\u8863\\u673a\",\"\\u7535\\u89c6\",\"\\u51b0\\u7bb1\"],\"label\":[[\"\\u5165\\u5e93\\u7c7b-1.2*2.0\\u5e38\\u89c4\\u5e8a\",\"0.8*0.55*0.75\\u5e38\\u89c4\\u684c\\u5b50\",\"\\u5165\\u5e93\\u7c7b-1.6*0.8*0.75\\u6c99\\u53d1\",\"\\u670d\\u52a1\\u7c7b-\\u5c0f\\u7c73\\u7535\\u89c6\",\"\\u670d\\u52a1\\u7c7b-1.5P\\u7a7a\\u8c03\",\"\\u76f4\\u4f9b\\u7c7b-\\u6ce2\\u8f6e\\u6d17\\u8863\\u673a\",\"\\u76f4\\u4f9b\\u7c7b-\\u4e24\\u5f00\\u95e8\\u51b0\\u7bb1\",\"\\u670d\\u52a1\\u7c7b-1.2m\\u5e2d\\u68a6\\u601d\\u5e8a\\u57ab\",\"\\u76f4\\u4f9b\\u7c7b-\\u62b1\\u6795\\/\\u6a59\"]]},\"\\u516c\\u5171\\u533a\\u57df\":{\"key\":[\"\\u5e8a\",\"\\u6c99\\u53d1\",\"\\u7a7a\\u8c03\",\"\\u6d17\\u8863\\u673a\",\"\\u7535\\u89c6\",\"\\u51b0\\u7bb1\"],\"label\":[[\"\\u5165\\u5e93\\u7c7b-1.2*2.0\\u5e38\\u89c4\\u5e8a\",\"0.8*0.55*0.75\\u5e38\\u89c4\\u684c\\u5b50\",\"\\u5165\\u5e93\\u7c7b-1.6*0.8*0.75\\u6c99\\u53d1\",\"\\u670d\\u52a1\\u7c7b-\\u5c0f\\u7c73\\u7535\\u89c6\",\"\\u670d\\u52a1\\u7c7b-1.5P\\u7a7a\\u8c03\",\"\\u76f4\\u4f9b\\u7c7b-\\u6ce2\\u8f6e\\u6d17\\u8863\\u673a\",\"\\u76f4\\u4f9b\\u7c7b-\\u4e24\\u5f00\\u95e8\\u51b0\\u7bb1\",\"\\u670d\\u52a1\\u7c7b-1.2m\\u5e2d\\u68a6\\u601d\\u5e8a\\u57ab\",\"\\u76f4\\u4f9b\\u7c7b-\\u62b1\\u6795\\/\\u6a59\"]]},\"\\u53a8\\u623f\":{\"key\":[\"\\u5e8a\",\"\\u6d17\\u8863\\u673a\",\"\\u7535\\u89c6\"],\"label\":[[\"\\u5165\\u5e93\\u7c7b-1.2*2.0\\u5e38\\u89c4\\u5e8a\",\"0.8*0.55*0.75\\u5e38\\u89c4\\u684c\\u5b50\",\"\\u670d\\u52a1\\u7c7b-\\u5c0f\\u7c73\\u7535\\u89c6\",\"\\u76f4\\u4f9b\\u7c7b-\\u6ce2\\u8f6e\\u6d17\\u8863\\u673a\",\"\\u76f4\\u4f9b\\u7c7b-\\u62b1\\u6795\\/\\u6a59\",\"\\u6728\\u95e8\"]]}}";

$a = "{\"A\":[1,3,5],\"\\u536b\\u751f\\u95f4\":[1,3,8,9,11,14,15,10,5],\"\\u516c\\u5171\\u533a\\u57df\":[1,3,5,8,9,11,14,15,10],\"\\u53a8\\u623f\":[8,1,3,18,10,15]}";
$b = json_decode($a, true);

print_r($b);