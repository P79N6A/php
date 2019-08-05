<?php
/*
1、床对应的文案
	1、弹性软床 简约舒适
	2、大尺寸 高弹性
	3、你的温柔乡


配置的标签
1、chuang
2、taideng
3、yigui
4、luodiliangyijia
5、baozhen
6、guabu
7、diaodeng
8、shuozhuoyi
9、shounaguashi

//做法
用户请求详情页写入一个队列

该队列异步处理详情页的卧室图片，循环调百度接口，请求图片tag，如果有按照房屋id和图片记到redis缓存。
		循环算法:每张图片获取一个tag即可（分数最大的，第一个就是，并且score大于30%）

			假如有10张图片，循环第一张原图调百度接口，如果没有匹配到信息，走下一张图片
			如果匹配到多个，从第一个开始，加入第一个是chuang（百度接口返回的是拼音），则从床的三个文案选择一个放入缓存，走下一张图片


用户下次访问详情页直接从缓存取
详情页加一个开关是否展示tag

*/

//获取百度access_token 缓存一个月
if (false) {
	$url = "https://aip.baidubce.com/oauth/2.0/token?grant_type=client_credentials&client_id=WFp6twbDLZUDOkUGmPhz4LL3&client_secret=zFt3lWQz1cPXdIAdSQgGVbkTHZcGMpx5&";

	$string = file_get_contents($url);

	$array = json_decode($string, true);
	print_r($array);exit;
/*
	Array
(
    [refresh_token] => 25.5845358b83a9945ca714bd01b77fab94.315360000.1866685329.282335-15646732
    [expires_in] => 2592000
    [session_key] => 9mzdCuVmSeKX9X20dfdsxQBQ7rdakeD2Fkjia7YKwWG3F3dqRsE9OhSZrQXG5ebd+4rNNgKf1IXvUeekXH5ASpad1SPOrA==
    [access_token] => 24.1aec2de173b46fec34524e542411f902.2592000.1553917329.282335-15646732
    [scope] => ai_custom_housingallocation public brain_all_scope easydl_mgr wise_adapt lebo_resource_base lightservice_public hetu_basic lightcms_map_poi kaidian_kaidian ApsMisTest_Test权限 vis-classify_flower lpq_开放 cop_helloScope ApsMis_fangdi_permission smartapp_snsapi_base iop_autocar oauth_tp_app smartapp_smart_game_openapi oauth_sessionkey smartapp_swanid_verify smartapp_opensource_openapi
    [session_secret] => e2ff7e53df7b387874637e9a1dc00f9e
)
*/
}
/**
 * 发起http post请求(REST API), 并获取REST请求的结果
 * @param string $url
 * @param string $param
 * @return - http response body if succeeds, else false.
 */
function request_post($url = '', $param = '')
{
    if (empty($url) || empty($param)) {
        return false;
    }
    $header = array();
    $header[] = 'Content-Type:application/json;charset=utf-8';
    $postUrl = $url;
    $curlPost = $param;
    // 初始化curl
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $postUrl);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    // 要求结果为字符串且输出到屏幕上
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    // post提交方式
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl,CURLOPT_HTTPHEADER,$header);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
    // 运行curl
    $data = curl_exec($curl);
    curl_close($curl);

    return $data;
}


function base64EncodeImage ($image_file) {
    $base64_image = '';
    $image_info = getimagesize($image_file);
    $image_data = fread(fopen($image_file, 'r'), filesize($image_file));
    $base64_image = 'data:' . $image_info['mime'] . ';base64,' . chunk_split(base64_encode($image_data));
    return $base64_image;
}

function imgtobase64($img='', $imgHtmlCode=true)
{
$imageInfo = getimagesize($img);
$base64 = "" . chunk_split(base64_encode(file_get_contents($img)));
return chunk_split(base64_encode(file_get_contents($img)));
//return 'data:' . $imageInfo['mime'] . ';base64,' . chunk_split(base64_encode(file_get_contents($img)));
}
/*
(
                            [height] => 300
                            [left] => 4
                            [top] => 225
                            [width] => 200
                        )
(
                            [height] => 225
                            [left] => 4
                            [top] => 169
                            [width] => 147
                        )



 */

//获取百度easyDL数据
//
if (true) {
	$url = "https://aip.baidubce.com/rpc/2.0/ai_custom/v1/detection/housingallocation";
	$token = '24.1aec2de173b46fec34524e542411f902.2592000.1553917329.282335-15646732';
	$url = $url . '?access_token=' . $token;
	$img  = "https://public.danke.com.cn/public-20180713-FkQgD3iE93tGDtNcMM3F7cha3eoH-roomPcDetail.jpg";
	$img1 = "https://public.danke.com.cn/public-20181010-Fm0N08pli6jEuoPhOy9xjlZa-Ey0";//-roomMobileDetail.jpg
	/**
	 *
	 * @var string
	 */
	$img2 = "https://public.danke.com.cn/public-20180713-FkQgD3iE93tGDtNcMM3F7cha3eoH-roomMobileDetail.jpg";
	$a = imgtobase64($img1);

	$body = [
		'image' => $a
	];

	$encode = json_encode($body);

	$res = request_post($url, $encode);

	$res_array = json_decode($res, true);
	print_r($res_array);

}