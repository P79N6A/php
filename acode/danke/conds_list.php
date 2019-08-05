<?php
GET /api/v3/room/conds-list?city_id=1&timestamp=1541393199 HTTP/1.1
http://172.16.31.54:3333/api/v3/room/conds-list?city_id=1&timestamp=1541393199

http://172.16.31.54:3333/api/v5/room/conds-list

{
	"code": "0000",
	"msg": "",
	"data": {
		"subways": [],
		"areas": [],
		"rent_types": {
			"filter_type": "rent_type",
			"title": "整租/合租",
			"input_type": "radio",
			"data": [{
				"title": "不限",
				"code": ""
			}, {
				"title": "合租",
				"room_type":{
			"filter_type": "room_type",
			"title": "户型",
			"input_type": "checkbox",
			"filter_sub_type": "",
			"data": [{
				"title": "1居",
				"code": "1"
			}, {
				"title": "2居",
				"code": "2"
			}, {
				"title": "3居",
				"code": "3"
			}, {
				"title": "3居+",
				"code": "4000"
			}]
		},
				"code": 2
			}, {
				"title": "整租",
				"room_type":{
			"filter_type": "room_type",
			"title": "户型",
			"input_type": "checkbox",
			"filter_sub_type": "",
			"data": [{
				"title": "1居",
				"code": "1"
			}, {
				"title": "2居",
				"code": "2"
			}, {
				"title": "3居",
				"code": "3"
			}, {
				"title": "3居+",
				"code": "4000"
			}]
		},
				"code": 1
			}, {
				"title": "月租",
				"code": 3
			}]
		},
		"prices": {
			"filter_type": "price",
			"title": "价格",
			"input_type": "radio",
			"data": [{
				"title": "不限",
				"code": ""
			}, {
				"title": "1500以下",
				"code": "0_1500"
			}, {
				"title": "1500-2000元",
				"code": "1500_2000"
			}, {
				"title": "2000-2500元",
				"code": "2000_2500"
			}, {
				"title": "2500-3000元",
				"code": "2500_3000"
			}, {
				"title": "3000以上",
				"code": "3000_30000"
			}]
		},
		"max_price": 30000,
		"quick_tags": [{
			"filter_type": "feature",
			"title": "房屋特色",
			"input_type": "checkbox",
			"filter_sub_type": "",
			"data": [{
				"title": "独立卫生间",
				"code": "hasToilet_有"
			}, {
				"title": "独立阳台",
				"code": "hasBalcony_有"
			}, {
				"title": "独立淋浴",
				"code": "hasShower_有"
			}, {
				"title": "近地铁",
				"code": "isNearSubway_是"
			}, {
				"title": "品质公寓",
				"code": "brand_蛋壳公寓"
			}, {
				"title": "集中供暖",
				"code": "heating_集中供暖"
			}, {
				"title": "可月租",
				"code": "isMonth_是"
			}]
		}, {
			"filter_type": "room_type",
			"title": "户型",
			"input_type": "checkbox",
			"filter_sub_type": "",
			"data": [{
				"title": "1居",
				"code": "1"
			}, {
				"title": "2居",
				"code": "2"
			}, {
				"title": "3居",
				"code": "3"
			}, {
				"title": "3居+",
				"code": "4000"
			}]
		}, {
			"filter_type": "status",
			"title": "房屋状态",
			"input_type": "checkbox",
			"filter_sub_type": "",
			"data": [{
				"title": "可立即入住",
				"code": "现房"
			}, {
				"title": "可预定",
				"code": "配置中"
			}]
		}, {
			"filter_type": "direction",
			"title": "朝向",
			"input_type": "checkbox",
			"filter_sub_type": "",
			"data": [{
				"title": "南",
				"code": "南"
			}, {
				"title": "东",
				"code": "东"
			}, {
				"title": "西",
				"code": "西"
			}, {
				"title": "北",
				"code": "北"
			}, {
				"title": "东南",
				"code": "东南"
			}, {
				"title": "西南",
				"code": "西南"
			}, {
				"title": "东北",
				"code": "东北"
			}, {
				"title": "西北",
				"code": "西北"
			}]
		}, {
			"filter_type": "style",
			"title": "房源风格",
			"input_type": "radio",
			"filter_sub_type": "",
			"data": [{
				"title": "MUJI风",
				"code": "MUJI风"
			}, {
				"title": "工业风",
				"code": "工业风"
			}, {
				"title": "北欧宜家",
				"code": "北欧宜家"
			}, {
				"title": "现代简约",
				"code": "现代简约"
			}]
		}],
		"sorts": {
			"filter_type": "orders",
			"title": "排序",
			"input_type": "radio",
			"filter_sub_type": "",
			"data": [{
				"title": "默认排序",
				"code": ""
			}, {
				"title": "价格从低到高",
				"code": "price_asc"
			}, {
				"title": "价格从高到低",
				"code": "price_desc"
			}, {
				"title": "面积从高到低",
				"code": "roomArea_desc"
			}, {
				"title": "面积从低到高",
				"code": "roomArea_asc"
			}]
		}
	}
}