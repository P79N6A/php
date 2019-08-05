<?php

https://api.dankegongyu.com/api/v3/room/list?city_id=1&timestamp=1541223507&search_text=%E5%9B%9E%E9%BE%99%E8%A7%82&isnewformat=1&page=1

请求参数
city_id
search_text
room_type
status
feature

Array
(
    [exactQuery] => Array
        (
            [isNearSubway] => 是
            [isMonth] => 是
            [heating] => 集中供暖
            [city] => 北京市
        )

    [page] => Array
        (
            [pageNum] => 1
            [pageSize] => 20
        )

    [type] => 3
)

返回数据


{
	"code": "0000",
	"msg": "",
	"data": [{
		"current_page": 1,
		"last_page": 1,
		"total": 0,
		"data": [],
		"title": "",
		"cond": {
			"city_id": "1",
			"feature": "hasBalcony_有",
			"isnewformat": "1",
			"page": "1",
			"room_type": "2",
			"search_text": "回龙观",
			"status": "现房",
			"timestamp": "1541223695"
		},
		"is_reset_cond": false
	}, {
		"current_page": 1,
		"last_page": 1,
		"total": 19,
		"data": [{
			"id": 299694443,
			"public_id": 299694443,
			"name": "龙泽 龙华园-次卧 朝南",
			"map_address": "龙华园",
			"district": "昌平区",
			"block": "龙泽",
			"direction": "南",
			"bedroom_num": 2,
			"parlor": 1,
			"floor_num": 1,
			"floor_total_num": 6,
			"area": "建筑面积约11㎡",
			"price": 2690,
			"price_unit": "元/月",
			"list_tags": ["可月租", "独立阳台", "集中供暖"],
			"list_pic": "https://public.wutongwan.org/public-20180826-Fsj1FVLxR96T0PKCAryR3oCt8xpb?imageView2/1/w/380/h/285",
			"rent_type": 2,
			"rent_type_label": 2,
			"brand_type": 0,
			"nearest_subway_title": "距13号线龙泽站800米",
			"has_3d": false,
			"workroom_distance": "",
			"promotion_info": {
				"promotion_type": "FANXIAN_LIJIAN",
				"promotion_desc": "首月租金立减1000元！还有返现哦！",
				"promotion_price": 1690,
				"promotion_price_title": "首月租金",
				"promotion_label": "立减再返现",
				"promotion_label_color": "FE5A66",
				"promotion_url": "https://www.dankegongyu.com/zhuanti/20181101November?citycode=bj",
				"promotion_title": "立减",
				"font_color": "ffffff",
				"bg_color": "FF4C59",
				"border_color": "FF4C59",
				"type": 6
			}
		}, {
			"id": 1337609744,
			"public_id": 1337609744,
			"name": "西二旗 安宁庄后街13号院-主卧 朝南",
			"map_address": "安宁庄后街13号院",
			"district": "海淀区",
			"block": "西二旗",
			"direction": "南",
			"bedroom_num": 2,
			"parlor": 1,
			"floor_num": 3,
			"floor_total_num": 6,
			"area": "建筑面积约20㎡",
			"price": 3430,
			"price_unit": "元/月",
			"list_tags": ["可月租", "独立阳台", "集中供暖"],
			"list_pic": "https://public.wutongwan.org/public-20180918-Fk7vrc5hLxXpDhoAokpwUhRLQ2P8?imageView2/1/w/380/h/285",
			"rent_type": 2,
			"rent_type_label": 2,
			"brand_type": 0,
			"nearest_subway_title": "距13号线,昌平线西二旗站1800米",
			"has_3d": false,
			"workroom_distance": "",
			"promotion_info": {
				"promotion_type": "FANXIAN_LIJIAN",
				"promotion_desc": "首月租金立减1000元！还有返现哦！",
				"promotion_price": 2430,
				"promotion_price_title": "首月租金",
				"promotion_label": "立减再返现",
				"promotion_label_color": "FE5A66",
				"promotion_url": "https://www.dankegongyu.com/zhuanti/20181101November?citycode=bj",
				"promotion_title": "立减",
				"font_color": "ffffff",
				"bg_color": "FF4C59",
				"border_color": "FF4C59",
				"type": 6
			}
		}, {
			"id": 547787572,
			"public_id": 547787572,
			"name": "上地 毛纺厂北小区-主卧 朝东",
			"map_address": "毛纺厂北小区",
			"district": "海淀区",
			"block": "上地",
			"direction": "东",
			"bedroom_num": 2,
			"parlor": 1,
			"floor_num": 3,
			"floor_total_num": 6,
			"area": "建筑面积约17㎡",
			"price": 3430,
			"price_unit": "元/月",
			"list_tags": ["可月租", "独立阳台", "集中供暖"],
			"list_pic": "https://public.wutongwan.org/public-20180715-Fms3428Hc3Xlt3kMaRgrKMqsAtSl?imageView2/1/w/380/h/285",
			"rent_type": 2,
			"rent_type_label": 2,
			"brand_type": 0,
			"nearest_subway_title": "距13号线上地站1450米",
			"has_3d": false,
			"workroom_distance": "",
			"promotion_info": {
				"promotion_type": "FANXIAN_LIJIAN",
				"promotion_desc": "首月租金立减1000元！还有返现哦！",
				"promotion_price": 2430,
				"promotion_price_title": "首月租金",
				"promotion_label": "立减再返现",
				"promotion_label_color": "FE5A66",
				"promotion_url": "https://www.dankegongyu.com/zhuanti/20181101November?citycode=bj",
				"promotion_title": "立减",
				"font_color": "ffffff",
				"bg_color": "FF4C59",
				"border_color": "FF4C59",
				"type": 6
			}
		} {
			"id": 360885916,
			"public_id": 360885916,
			"name": "上地 财经大学家属院-主卧 朝南",
			"map_address": "财经大学家属院",
			"district": "海淀区",
			"block": "上地",
			"direction": "南",
			"bedroom_num": 2,
			"parlor": 1,
			"floor_num": 4,
			"floor_total_num": 6,
			"area": "建筑面积约19㎡",
			"price": 3860,
			"price_unit": "元/月",
			"list_tags": ["独立阳台", "集中供暖", "品质公寓"],
			"list_pic": "https://s1.wutongwan.org/build/img/room/no-picture-e63a495fc7.jpg?imageView2/1/w/380/h/285",
			"rent_type": 2,
			"rent_type_label": 2,
			"brand_type": 0,
			"nearest_subway_title": "距13号线上地站300米",
			"has_3d": false,
			"workroom_distance": "",
			"promotion_info": null
		}],
		"title": "为您推荐 回龙观 附近的房源",
		"cond": {
			"city_id": "1",
			"feature": "hasBalcony_有",
			"isnewformat": "1",
			"page": "1",
			"room_type": "2",
			"search_text": "回龙观",
			"status": "现房",
			"timestamp": "1541223695",
			"use_recommend": 1
		},
		"is_reset_cond": false
	}]
}





Array
(
    [brand] => 蛋壳公寓
    [rentType] => 合租
    [city] => 北京市
    [district] => 朝阳区
    [subway] => 14号线,15号线
    [block] => 望京
    [price] => 2860
    [bedroomNum] => 4
    [hasToilet] => 无
    [hasBalcony] => 有
    [hasShower] => 无
    [bedroomType] => 主卧
    [face] => 西
    [searchText] => 46-D,望京新城-422-1812,陈玉辉,望京新城,,14号线,15号线,望京,朝阳区,北京市,销售分区主管-北京市,毕业生优惠活动
    [subwayLines] => 14号线,15号线
    [xiaoquName] => 望京新城
    [xiaoquAlias] => 
    [roomId] => 189
    [suiteId] => 46
    [roomNumber] => D
    [roomArea] => 16
    [monthPrice] => 3390
    [roomStatus] => 可出租
    [availableDate] => 2017-01-12
    [hasImage] => 有
    [roomImages] => http://public.wutongwan.org/public-20170116-FvmZ-YcpQq15Sr8VIGcekGRmhjD5?imageView2/1/w/380/h/285
    [floor] => 18
    [totalFloor] => 32
    [toiletNum] => 1
    [suiteArea] => 
    [suiteImages] => []
    [hasLift] => 有
    [heating] => 集中供暖
    [hotWaterType] => 燃气热水器
    [subwayNearby] => [{"to":242,"distance":1,"transform":0},{"to":274,"distance":1,"transform":0},{"to":65,"distance":1,"transform":0},{"to":275,"distance":1,"transform":0},{"to":66,"distance":2,"transform":1},{"to":241,"distance":2,"transform":0},{"to":254,"distance":2,"transform":0},{"to":273,"distance":2,"transform":0},{"to":64,"distance":2,"transform":1},{"to":276,"distance":2,"transform":0},{"to":75,"distance":3,"transform":1},{"to":239,"distance":3,"transform":0},{"to":60,"distance":3,"transform":1},{"to":57,"distance":3,"transform":0},{"to":272,"distance":3,"transform":0},{"to":55,"distance":3,"transform":2},{"to":145,"distance":3,"transform":2},{"to":277,"distance":3,"transform":0},{"to":59,"distance":4,"transform":1},{"to":238,"distance":4,"transform":0},{"to":278,"distance":4,"transform":0},{"to":76,"distance":4,"transform":1},{"to":253,"distance":4,"transform":0},{"to":54,"distance":4,"transform":2},{"to":56,"distance":4,"transform":2},{"to":58,"distance":4,"transform":1},{"to":144,"distance":4,"transform":2},{"to":61,"distance":4,"transform":1},{"to":74,"distance":4,"transform":1},{"to":146,"distance":4,"transform":2}]
    [isMonth] => 否
    [panorama] => 
    [isNearSubway] => 
    [subwayDistance] => 
    [suiteStatus] => 现房
    [districtLatLon] => 39.92556,116.448537
    [blockLatLon] => 40.004532,116.475304
    [xiaoquLonLat] => 40.004698,116.482524
    [subwayLonLat] => 40.004532,116.475304
    [subwayTitle] => 望京
    [subwayDuration] => 839
    [publicSpaceNum] => 
    [style] => 现代简约
    [publicArea] => 18
)