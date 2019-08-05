首页请求的

https://www.dankegongyu.com/pangu-activity/marketing/custom-house-resource/city-list
https://www.dankegongyu.com/pangu-activity/marketing/custom-house-resource/district/1
https://www.dankegongyu.com/pangu-activity/marketing/custom-house-resource/block/2
https://www.dankegongyu.com/market/geturl-referurl
https://www.dankegongyu.com/web-api/user/info
https://www.dankegongyu.com/web-api/base-configure/city-list


https://www.dankegongyu.com/web-api/home-page/advertisement?city_id=1
https://www.dankegongyu.com/web-api/home-page/banner?city_id=1
https://www.dankegongyu.com/web-api/home-page/home-data?city_id=1



搜索页请求的

https://www.dankegongyu.com/web-api/user/info?_=1548906341400
https://www.dankegongyu.com/market/geturl-referurl
https://www.dankegongyu.com/pangu-activity/marketing/custom-house-resource/city-list
https://www.dankegongyu.com/pangu-activity/marketing/custom-house-resource/district/1?_=1548906341401
https://www.dankegongyu.com/pangu-activity/marketing/custom-house-resource/block/2?_=1548906341402
https://www.dankegongyu.com/room/guesslist/?_=1548906341403   猜你喜欢 好好房推荐
https://www.dankegongyu.com/web-api/base-configure/city-list?_=1548906341404
https://www.dankegongyu.com/room/hotroom/bj?_=1548906341405 热门房源
https://www.dankegongyu.com/web-api/base-configure/sug-text?city_id=1&query=回龙观&_=1548907344305


合租页面请求的
https://www.dankegongyu.com/web-api/user/info
https://www.dankegongyu.com/pangu-activity/marketing/custom-house-resource/city-list
https://www.dankegongyu.com/market/geturl-referurl
https://www.dankegongyu.com/web-api/base-configure/city-list
https://www.dankegongyu.com/pangu-activity/marketing/custom-house-resource/district/1
https://www.dankegongyu.com/web-api/room/recommend-list?city_id=1&rent_type=合租&style=工业风&limit=6
https://www.dankegongyu.com/web-api/room/recommend-list?city_id=1&rent_type=合租&style=MUJI风&limit=6
https://www.dankegongyu.com/pangu-activity/marketing/custom-house-resource/block/2

整租请求的
https://www.dankegongyu.com/web-api/user/info
https://www.dankegongyu.com/pangu-activity/marketing/custom-house-resource/city-list
https://www.dankegongyu.com/market/geturl-referurl
https://www.dankegongyu.com/web-api/base-configure/city-list
https://www.dankegongyu.com/pangu-activity/marketing/custom-house-resource/district/1
https://www.dankegongyu.com/web-api/room/recommend-list?city_id=1&rent_type=整租&limit=6
https://www.dankegongyu.com/pangu-activity/marketing/custom-house-resource/block/2
月租请求的
https://www.dankegongyu.com/web-api/user/info
https://www.dankegongyu.com/pangu-activity/marketing/custom-house-resource/city-list
https://www.dankegongyu.com/market/geturl-referurl
https://www.dankegongyu.com/web-api/base-configure/city-list
https://www.dankegongyu.com/pangu-activity/marketing/custom-house-resource/district/1
https://www.dankegongyu.com/web-api/room/recommend-list?city_id=1&rent_cycle=1&limit=6&_=1548907294534
地图找房
https://www.dankegongyu.com/map/room-search?city_code=bj&level=2&page=1&left_bottom_lng=116.116066&left_bottom_lat=39.885226&right_top_lng=116.343158&right_top_lat=39.93747&price=&bedroomNum=&rentType=&bedroomType=&faceTo=&hasToilet=&hasBalcony=&hasShower=&search_text=&_=1548914631674
https://www.dankegongyu.com/map/room-search?city_code=bj&level=1&page=1&left_bottom_lng=116.186508&left_bottom_lat=39.858735&right_top_lng=116.640692&right_top_lat=39.963225&price=&bedroomNum=&rentType=&bedroomType=&faceTo=&hasToilet=&hasBalcony=&hasShower=&search_text=&_=1548914631673
https://www.dankegongyu.com/map/room-search?city_code=bj&level=1&page=1&left_bottom_lng=116.186508&left_bottom_lat=39.858735&right_top_lng=116.640692&right_top_lat=39.963225&price=&bedroomNum=&rentType=&bedroomType=&faceTo=&hasToilet=&hasBalcony=&hasShower=&search_text=&_=1548914631672

房源详情
https://www.dankegongyu.com/room/guessroom/92533927?_=1548922125860


目前服务共8台机器，配置16核16G，单台机器请求数900/分钟，服务端最大处理请求数1500，总请求数约为7000/每分钟，推广或流量增长没法支撑，接口平均响应时长500~700毫秒；
房源全部优化完成后目标
1，接口平均响应时长100~300毫秒，降低50%；
2，服务端处理请求数10000~15000/分钟；
3，服务器降低至2~5台；
4，支撑流量2~4倍增长；

优化计划
一、pc,h5
	1、pc，h5官网首页
		1、合并三个接口为一个
			广告：https://www.dankegongyu.com/web-api/home-page/advertisement?city_id=1
			banner：https://www.dankegongyu.com/web-api/home-page/banner?city_id=1
			首页data：https://www.dankegongyu.com/web-api/home-page/home-data?city_id=1
		2、去掉繁琐的laravel语法糖变为更直接的php原生写法极大提升性能
		3、针对表查询做更为精细化的缓存策略
			web_home_page_blocks表
			web_home_page_block_items表
		4、banners

	2、pc, h5房源搜索列表
	3、pc、h5地图找房列表
	4、pc、h5房源详情页
二、小程序
	1、首页
	2、搜索房源列表
	3、房源详情
	4、筛选条件
	5、城市和ID对应接口
	6、电话
	7、搜索热词
	8、特惠
	9、活动开关
	10、BANNER
	11、地图找房
	12、房源详情更多配置
	13、我的优惠券列表
	14、绑定优惠券	
	15、收藏列表
	16、取消收藏
	17、收藏
	18、收藏状态
