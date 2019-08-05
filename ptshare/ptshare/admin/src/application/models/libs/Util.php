<?php
class Util
{
	const EXPIRE = 7200;
	const TOKEN  = "eac63e66d8c4a6f0303f00bc76d0217c";

    const
        SELLID    = 1,

        PACKAGEID = 2,

        end = '';

	static public function formatFilterWord($content)
	{
		//去除标点符号
		$filter = '´ ` ^ ~ < = > ¬ | ¦ _ ¯ - , ; : ! ¡ ? ¿ / . ¸ \' ‘ ’ " “ ” « » ( ) [ ] { } @ $ * \ & # % + ˊ ， . , ; \' / [ ]  - = ` ~ ! @ # $ % ^ & * ( ) _ + { } \ | : " < > ， 。 、 ！ ？ ～  ： ； >  ﹑ • ＂ … ｀ ＇ ‘ ’ “ ” 〝 〞 ∕ ¦ ‖ — ﹛ ﹜ 〈 〉 ﹝ ﹞ 「 」 ‹ › 〖 〗 《 》 〔 〕 『 』 « » 【 】 ﹐ ﹕ ﹔ ﹖ ﹏ ˊ ﹫ ＠ ︳ ＿ ¸ ︰ ; ¡ ¿ ﹌ ﹋ ´ ˋ ― ︴ ¯ － ￣ ﹢ ＋ ﹦ ﹤ ¬ ˜ ﹟ ﹩ ﹠ ﹪ ﹡ ﹨ ﹍ ﹎ ˆ ‐ ﹉ ﹊ ˇ ︵ ︷ ︿ ︹ ︽ _ ﹁ ﹃ ︻ ︶ ︸ ﹀ ︺ ︾ ˉ ﹂ ﹄ ︼ ～ 😍 ☞';
		$content = str_replace(explode(' ', $filter), "", $content);
		$content = preg_replace("/\s+/", "", $content);
		//符号标准化
		$search   = "零 一 二 三 四 五 六 七 八 九 壹 贰 叁 肆 伍 陆 柒 捌 玖 久 酒 ① ② ③ ④ ⑤ ⑥ ⑦ ⑧ ⑨ ０ １ ２ ３ ４ ５ ６ ７ ８ ９ ⒈ ⒉ ⒊ ⒋ ⒌ ⒍ ⒎ ⒏ ⒐ O l Ⓐ Ⓑ Ⓒ Ⓓ Ⓔ Ⓕ Ⓖ Ⓗ Ⓘ Ⓙ Ⓚ Ⓛ Ⓜ Ⓝ Ⓞ Ⓟ Ⓠ Ⓡ Ⓢ Ⓣ Ⓤ Ⓥ Ⓦ Ⓧ Ⓨ Ⓩ ⓐ ⓑ ⓒ ⓓ ⓔ ⓕ ⓖ ⓗ ⓘ ⓙ ⓚ ⓛ ⓜ ⓝ ⓞ ⓟ ⓠ ⓡ ⓢ ⓣ ⓤ ⓥ ⓦ ⓧ ⓨ ⓩ ₀ ₁ ₂ ₃ ₄ ₅ ₆ ₇ ₈ ₉ ⑳ ⑬ ⓹ ② ⑥ ❶ ❷ ❸ ❹ ❺ ❽ ⒪ ⑴ ⑵ ⑶ ⑹ ⑺ 🅰 1̶ 3̶ 5̶ 8̶ 2̶̶ ① ② ③ ④ ⑤ ⑥ ⑧ ⑨ ⑩ ⑪ ⑫ ⑬ ⑭ ⑮ ⑯ ⑰ ⑱ ⑲ ⑳ ⑴ ⑵ ⑶ ⑷ ⑸ ⑹ ⑺ ⑻ ⑼ ⑽ ⑾ ⑿ ⒀ ⒁ ⒂ ⒃ ⒄ ⒅ ⒆ ⒇ ⒈ ⒉ ⒊ ⒋ ⒌ ⒎ ⒏ ⒐ ⒑ ⒒ ⒓ ⒔ ⒕ ⒖ ⒗ ⒙ ⒚ ⒛ ❶ ❷ ❸ ❹ ❺ ❻ ❼ ❽ ❾ ❿ ㈠ ㈡ ㈢ ㈣ ㈤ ㈥ ㈦ ㈧ ㈨ ㈩ º ¹ ² ³ ₁ ₂ ₃";
		$replace  = "0 1 2 3 4 5 6 7 8 9 1 2 3 4 5 6 7 8 9 9 9 1 2 3 4 5 6 7 8 9 0 1 2 3 4 5 6 7 8 9 1 2 3 4 5 6 7 8 9 0 1 A B C D E F G H I J K L M N O P Q R S T U V W X Y Z a b c d e f g h i j k l m n o p q r s t u v w x y z 0 1 2 3 4 5 6 7 8 9 20 13 5 2 6 1 2 3 4 5 8 0 1 2 3 6 7 A 1 3 5 8 2 1 2 3 4 5 6 7 8 9 10 11 12 13 14 15 16 17 18 19 20 1 2 3 4 5 6 7 8 9 10 11 12 13 14 15 16 17 18 19 20 1 2 3 4 5 6 7 8 9 10 11 12 13 14 15 16 17 18 19 20 1 2 3 4 5 6 7 8 9 10 1 2 3 4 5 6 7 8 9 10 0 1 2 3 1 2 3";
		
		$search   = explode(' ', $search);
		$replace  = explode(' ', $replace);
		
		$content  = str_replace($search, $replace, $content);
		
		return $content;
	}
	
	public static function getNumbers($content)
	{
		$length = strlen($content);
		$numbers = "";
		for($i = 0; $i < $length; $i++) {
			if(is_numeric($content{$i})) {
				$numbers .= $content{$i};
			}
		}
		
		return $numbers;
	}
	
	public static function getCharacters($content)
	{
		$length = strlen($content);
		
		$characters = "";
		for($i = 0; $i < $length; $i++) {
			if(preg_match("/[a-z\d_]/iu", $content{$i})) {
				$characters .= $content{$i};
			}
		}
		
		return $characters;
	}
	
	public static function validate($params)
	{
		//if(!isset($params["time"]) || (time() - $params["time"] > self::EXPIRE)) {
		if(!isset($params["time"])) {
			return false;
		}

		$guid = $params['guid'];

		$cache = Cache::getInstance();
		if(false !== $cache->get($guid)) {
			return false;
		}

		$cache->set($guid, 1, false, self::EXPIRE);

		ksort($params);
		$str = '';
		foreach($params as $k=>$v) {
			if(in_array($k, array("userid", "deviceid", "platform", "network", "version", "rand", "netspeed", "time"))) {
				$str .= $k . '=' . rawurldecode(urlencode($v));
			}
		}
		$token = self::TOKEN;
		$sign = md5($str.$token);

		return $guid == $sign ? true : false;
	}

	public static function ip2region($ip)
	{
		$res_json 		= @file_get_contents('http://ip.taobao.com/service/getIpInfo.php?ip=' . $ip);
		$local_array 	= json_decode($res_json, true);
		if(!empty($local_array['data'])){
			$province 	= empty($local_array['data']['region']) ? '' : $local_array['data']['region'];
			$city 		= empty($local_array['data']['city']) ? '' : $local_array['data']['city'];
			$district 	= empty($local_array['data']['isp']) ? '' : $local_array['data']['isp'];
			$province 	= str_replace('省', '', $province);
			$province 	= str_replace('自治区', '', $province);
			$province 	= str_replace('市', '', $province);
			$city 		= str_replace('市', '', $city);
			$district 	= str_replace('县', '', $district);
		} else {
			$province 	= '';
			$city 		= '';
			$district 	= '';
		}

		return array($province, $city, $district);
	}

	public static function ip2Location($ip)
	{
		//if (class_exists(Ip2region)) {
			$data  = Ip2region::btreeSearchString($ip);

			if (!empty($data['region'])) {
				$data_array = explode('|', $data['region']);

				$province = $data_array[2];
				$city     = $data_array[3];
				$district = $data_array[4];
				$province 	= str_replace('省', '', $province);
				$province 	= str_replace('自治区', '', $province);
				$province 	= str_replace('市', '', $province);
				$city 		= str_replace('市', '', $city);
				$district 	= str_replace('县', '', $district);
				if ((empty($province) && empty($city) && empty($district) )||($province == '0' && $city == '0' && $district == '0')) {
					return array('', '', '');
				}
			} else {
				$province 	= '';
				$city 		= '';
				$district 	= '';
			}
			return array($province, $city, $district);
// 		} else {
// 			return self::ip2region($ip);
// 		}
	}

	public static function checkFlood($guid)
	{/*{{{检测恶意请求*/
		$cache = Cache::getInstance();

		if(true === $cache->add($guid, 1, false, self::EXPIRE)) {
			return true;
		}

		if(false === $cache->get($guid)) {
			$cache->set($guid, 1, false, self::EXPIRE);

			return true;
		}

		return false;
	}/*}}}*/

	public static function getTime($cache = true)
	{/*{{{*/
		static $time;

		if($cache) {
			if(!$time) {
				$time = isset($_SERVER['REQUEST_TIME']) && !empty($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time();
			}
		} else {
			$time = time();
		}

		return $time;
	}/*}}}*/

	public static function getError($errno)
	{/*{{{*/
		$error_list = Context::getConfig("ERROR_LIST");
		return isset($error_list[$errno]) ? $error_list[$errno] : "";
	}/*}}}*/

	public static function getIP()
	{/*{{{*/
		if ($_SERVER['HTTP_X_FORWARDED_FOR']) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} elseif (getenv("REMOTE_ADDR")) {
			$ip = getenv("REMOTE_ADDR");
		} elseif ($_SERVER['REMOTE_ADDR']) {
			$ip = $_SERVER['REMOTE_ADDR'];
		} elseif ($_SERVER['HTTP_CLIENT_IP']) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (getenv("HTTP_X_FORWARDED_FOR")) {
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		} elseif (getenv("HTTP_CLIENT_IP")) {
			$ip = getenv("HTTP_CLIENT_IP");
		} else {
			$ip = $HTTP_SERVER_VARS['REMOTE_ADDR'];
		}

		return $ip;
	}/*}}}*/

	public static function getImageUrl($url, $version, $user_agent, $addtime = "")
	{/*{{{*/
		/*if($addtime != "" && (time() - strtotime($addtime)) <= 60) {
		return $url;
		}*/

		if(empty($url)){
			return "";
		}

		$webp_support = false;

		if(!$webp_support && preg_match("/iPhone/i", $user_agent)) {
			$webp_support = true;
		}

		if(!$webp_support && preg_match("/Mozilla\/5.0 \(Linux; U; Android (.*); zh-cn/i", $user_agent, $matches)) {
			$android_version = $matches[1];
			if(Util::isCompatible($android_version, "4.0.0", ">=")) {
				$webp_support = true;
			}
		}

		if($webp_support) {
			if($addtime != "" && (time() - strtotime($addtime)) <= 60) {
				return $url;
			}

			if(substr($url, -4) != "webp") {
				$url = substr_replace($url, 'webp', -3);
			}
		} else {
			if(substr($url, -4) == "webp") {
				$url = substr_replace($url, "jpg", -4);
			}
		}

		return $url;
	}/*}}}*/

	public static function isCompatible($a, $b, $compare)
	{
		$aver = intval(str_replace(".", "", $a));
		$bver = intval(str_replace(".", "", $b));

		$len = max(strlen($aver), strlen($bver));

		if(strlen($aver) < $len) {
			$aver = $aver.str_repeat("0", $len - strlen($aver));
		}

		if(strlen($bver) < $len) {
			$bver = $bver.str_repeat("0", $len - strlen($bver));
		}

		return eval("return $aver $compare $bver;");
	}

	public static function compatible($platform, $compare, $version)
	{
		$compare_list = array(
		">"=>array(1),
		">="=>array(0, 1),
		"<"=>array(-1),
		"<="=>array(0, -1),
		"="=>array(0)
		);

		if(Context::get("platform") == $platform) {
			return in_array(Util::compareVersion(Context::get("version"), $version), $compare_list[$compare]);
		}

		return false;
	}

	public static function comparison($base_platform, $object_platform, $compare, $base_version, $object_version)
	{
		$compare_list = array(
				">"=>array(1),
				">="=>array(0, 1),
				"<"=>array(-1),
				"<="=>array(0, -1),
				"="=>array(0)
		);

		if($object_platform == $base_platform) {
			return in_array(Util::compareVersion($object_version, $base_version), $compare_list[$compare]);
		}

		return false;
	}

	public static function makeThumb($srcFile,$maxwidth,$maxheight,$rate=75,$markwords=null,$markimage=null)
	{
		if(is_file($srcFile)) {
			$data = GetImageSize($srcFile);

			switch($data[2])
			{
				case 1:
					$im=@ImageCreateFromGIF($srcFile);
					break;
				case 2:
					$im=@ImageCreateFromJPEG($srcFile);
					break;
				case 3:
					$im=@ImageCreateFromPNG($srcFile);
					break;
				default:
					break;
			}
			$imageheight = $data['1'];
			$imagewidth  = $data['0'];
		} else {
			$im = imagecreatefromstring($srcFile);
			$imageheight= imagesy($im);
			$imagewidth = imagesx($im);
		}

		if(!$im) return false;

		// 调整尺寸

		$imgorig     = $imagewidth;
		if ($imagewidth > $maxwidth)
		{
			$imageprop   = ($maxwidth*100)/$imagewidth;
			$imagevsize  = ($imageheight*$imageprop)/100 ;
			$imagewidth  = $maxwidth;
			$imageheight = ceil($imagevsize);
		}
		if ($imageheight > $maxheight)
		{
			$imageprop   = ($maxheight*100)/$imageheight;
			$imagehsize  = ($imagewidth*$imageprop)/100 ;
			$imageheight = $maxheight;
			$imagewidth  = ceil($imagehsize);
		}
		$dstW = $imagewidth;
		$dstH = $imageheight;

		$srcW=ImageSX($im);
		$srcH=ImageSY($im);
		$dstX=0;
		$dstY=0;
		if ($srcW*$dstH>$srcH*$dstW)
		{
			$fdstH = round($srcH*$dstW/$srcW);
			$dstY  = floor(($dstH-$fdstH)/2);
			$fdstW = $dstW;
		}
		else
		{
			$fdstW = round($srcW*$dstH/$srcH);
			$dstX  = floor(($dstW-$fdstW)/2);
			$fdstH = $dstH;
		}
		$ni=ImageCreateTrueColor($dstW,$dstH);// 创建指定大小的文件
		$dstX=($dstX<0)?0:$dstX;
		$dstY=($dstX<0)?0:$dstY;
		$dstX=($dstX>($dstW/2))?floor($dstW/2):$dstX;
		$dstY=($dstY>($dstH/2))?floor($dstH/s):$dstY;
		$white = ImageColorAllocate($ni,255,255,255);
		$black = ImageColorAllocate($ni,0,0,0);
		imagefilledrectangle($ni,0,0,$dstW,$dstH,$white);// 填充背景色
		imageCopyResampled($ni,$im,$dstX,$dstY,0,0,$fdstW,$fdstH,$srcW,$srcH);
		if($markwords!=null)
		{
			imagestring($ni,2,3,3,$markwords,$white);
		}
		elseif($markimage!=null)
		{
			$wimage =imagecreatefromgif($markimage);
			ImageAlphaBlending($im, true);
			$logoW = ImageSX($wimage);
			$logoH = ImageSY($wimage);
			// 将水印放到右下脚,偏移5个像素
			$poX = $dstW - $logoW-5;
			$poY = $dstH - $logoH-5;
			imagecopy($ni,$wimage,$poX ,$poY,0,0,$logoW,$logoH);
			imagedestroy($wimage);
		}

		ob_end_clean();
		ob_start();
		imagejpeg($ni, '', $rate);
		$imgcontent = ob_get_contents();
		ob_end_clean();
		imagedestroy($im);
		imagedestroy($ni);

		return $imgcontent;
	}

	public static function compareVersion($version1, $version2)
	{
		$version1_list = explode('.', $version1);
		$version2_list = explode('.', $version2);

		$count = max(count($version1_list), count($version2_list));
		for($i = 0; $i < $count; $i++) {
			if(intval($version1_list[$i]) > intval($version2_list[$i])) {
				return 1;
			}

			if(intval($version1_list[$i]) < intval($version2_list[$i])) {
				return -1;
			}
		}

		return 0;
	}

	public static function StringFilter($string){
		$cleaned = strip_tags($string);
		$cleaned = htmlspecialchars(mysql_escape_string($cleaned));
		return $cleaned;
	}

	public static function getLocation($location_info){
		$location = '未知';
		$province = $city = $district = '';
		if(empty($location_info['province'])&&empty($location_info['city'])&&empty($location_info['district'])){
			return empty($location_info["location"]) ? "" : $location;
		}

		$province = $location_info['province'];
		$city = $location_info['city'];
		$district = $location_info['district'];
		$location = $location_info["location"];

		$pattern = empty($location) ? "/(火星)/i" : "/($location)/i";

		if($province == "宇宙"){
			$province = $city = $district = $location = "";
		}

		if(in_array($city, array('北京市', '上海市', '重庆市', '天津市'))){
			$province = "";
		}
		if(preg_match($pattern, $province)){
			$province = "";
		}
		if(preg_match($pattern, $city)){
			$city = "";
		}
		if(preg_match($pattern, $district)){
			$district = "";
		}
		//$location = $province.$city.$district.$location;
		$location = $province.$city;//地址信息只返回到市一级

		return empty($location) ? "" : $location;
	}

	public function getDistance($distance)
	{
		$sections = array(
		array("start"=>0, "end"=>10, "label"=>"10米以内"),
		array("start"=>10, "end"=>50, "label"=>"50米以内"),
		array("start"=>50, "end"=> 100, "label"=>"100米以内"),
		array("start"=>100, "end"=> 200, "label"=>"200米以内"),
		array("start"=>200, "end"=> 500, "label"=>"500米以内"),
		array("start"=>500, "end"=> 1000, "label"=>"1公里以内"),
		array("start"=>1000, "end"=> 2000, "label"=>"2公里以内"),
		array("start"=>2000, "end"=> 5000, "label"=>"5公里以内"),
		array("start"=>5000, "end"=> 10000, "label"=>"10公里以内"),
		array("start"=>10000, "end"=> 20000, "label"=>"20公里以内"),
		array("start"=>20000, "end"=> 50000, "label"=>"50公里以内"),
		array("start"=>50000, "end"=> 100000, "label"=>"100公里以内"),
		array("start"=>100000, "end"=> 10000000000, "label"=>"100公里以外"),
		);

		foreach ($sections as $section) {
			if($distance >= $section["start"] && $distance < $section["end"]) {
				return $section["label"];
			}
		}

		return "未知";
	}

	public static function isPrivateIp($ip) {
		$long = self::ip2longu($ip);
		return ($long & 0xFF000000) === 0x0A000000 //10.0.0.0-10.255.255.255
		|| ($long & 0xFFF00000) === 0xAC100000 //172.16.0.0-172.31.255.255
		|| ($long & 0xFFFF0000) === 0xC0A80000;//192.168.0.0-192.168.255.255
	}

	public static function ip2longu($ip) {
		return sprintf('%u', ip2long($ip));
	}

	public static function arrayKeyAutoincr($baseArray, $key, $step = 0.01){
		for($i=0;$i<10000;$i++){
			if(is_array($baseArray["$key"])){
				$key = $key + $step;
			}else{
				break;
			}
		}

		return $key;
	}

	/**
	 * 判断是否为手机号
	 * 目前只支持国内手机号
	 * @param $mobile
	 * @return bool
	 */
	public static function isMobile($mobile) {
		if(preg_match("/1[34578]{1}\d{9}$/",trim($mobile))){//国内手机号
			return true;
		}/*elseif(preg_match("/\d{6,20}$/",trim($value))){//todo 海外手机号 暂时放在这里 不开通 再屏蔽
			return $value;
		}*/
		return false;
	}

    public static function sendMail($to, $key, $params = array(), $cc = null, $bcc = null)
    {/*{{{*/
        $mail_conf = Context::getConfig("MAIL_TPL");
        if(isset($mail_conf[$key])) {
            $content = vsprintf($mail_conf[$key]["content"], $params);

            try{
                return Sendmail::send($to, $mail_conf[$key]["title"], $content, $cc, $bcc);
            } catch(Exception $e) {
                throw new BizException(ERROR_CUSTOM, $e->getMessage());
            }
        }

        return false;
    }/*}}}*/

	public static function publishFile($data, $name)
	{/*{{{*/
		try{
			$dream_client = DreamClient::getInstance();

			$dream_client->addFile("file",$data,$name);
            $file_url = $dream_client->uploadFile($name);
            $url_arr = parse_url($file_url['url']);
            $file_url['path'] = $url_arr['path'];
            return $file_url;
		}  catch(Exception $e) {
			throw new BizException(ERROR_CUSTOM, " client: " . $e->getMessage());
		}
    }/*}}}*/
	public static function publishAvatar($photo_data, $realname,$kind='')
	{/*{{{*/
		try{
			$dream_client = DreamClient::getInstance();

			$dream_client->addFile("file",$photo_data, $realname);
            $image_url = $dream_client->uploadImage($photo_data, $kind);
            $url_arr = parse_url($image_url['url']);
            $image_url['path'] = $url_arr['path'];
            return $image_url;
		}  catch(Exception $e) {
			throw new BizException(ERROR_CUSTOM, " client: " . $e->getMessage());
		}
	}/*}}}*/
	public static function createTask($filename, $filesize)
	{/*{{{*/
		try{
			$dream_client = DreamClient::getInstance();

			return $dream_client->createTask($filename, $filesize);
		}  catch(Exception $e) {
			throw new BizException(ERROR_CUSTOM, " client: " . $e->getMessage());
		}
	}/*}}}*/
	public static function uploadSlice($filename, $uploadid, $partnumber, $md5 ,$tempname)
	{/*{{{*/
		try{
			$dream_client = DreamClient::getInstance();
			$dream_client->addFile("file",$tempname, $filename);
			return $dream_client->uploadPart($filename, $uploadid, $partnumber, $md5);
		}  catch(Exception $e) {
			throw new BizException(ERROR_CUSTOM, " client: " . $e->getMessage());
		}
	}/*}}}*/
	public static function completeTask($filename, $uploadid, $md5)
	{/*{{{*/
		try{
			$dream_client = DreamClient::getInstance();

			return $dream_client->completeTask($filename, $uploadid, $md5);
		}  catch(Exception $e) {
			throw new BizException(ERROR_CUSTOM, " client: " . $e->getMessage());
		}
	}/*}}}*/
	public static function sec2time($second){
		if($second < 60) {
			$time = $second.'秒';
		} else if($second >= 60 && $second < 60 * 60) {//分钟
			$time = floor($second / 60).'分钟'.($second % 60).'秒';
		} else if($second >= 60 * 60) {//小时
			$hour = floor($second / (60 * 60));
			$minute = floor(($second-$hour*60*60)/(60));
			$second = $second - $hour*60*60 - $minute*60;
			$time = $hour.'小时'.$minute."分钟".$second."秒";
		} else if($second >= 86400) {
			$day  = floor($second / 86400);
			$hour = floor(($second -$day * 86400) / (60 * 60));
			$minute = floor(($second-$day * 86400 - $hour*60*60)/(60));
			$second = $second -$day * 86400 - $hour*60*60 - $minute*60;
			$time = $day .'天'.$hour.'小时'.$minute."分钟".$second."秒";
		}
		return $time;
	}
	public static function formatTime($addtime)
	{/*{{{*/
		$current  = time();
		$addtime  = strtotime($addtime);
		$interval = $current - $addtime;

		$direct = $interval < 0 ? "后" : "前";
		$interval = abs($interval);
		$before   = "";
		if($interval >= 60 * 60 * 24 * 365) {
			$before = floor($interval / (60 * 60 * 24 * 365))."年".$direct;
		} else if($interval >= 60 * 60 * 24 * 31) {
			$before = floor($interval / (60 * 60 * 24 * 31))."月".$direct;
		} else if($interval >= 60 * 60 * 24 && $interval < 60 * 60 * 24 * 31) {
			$before = floor($interval / (60 * 60 * 24))."天".$direct;
		} else if($interval >= 60 * 60 && $interval < 60 * 60 * 24) {
			$before = floor($interval / (60 * 60))."小时".$direct;
		} else if ($interval >= 60 && $interval < 60 * 60) {
			$before = floor($interval / 60)."分钟".$direct;
		} else {
			$before = "刚刚";
		}

		return $before;
    }/*}}}*/

    //rank数据替换
    public static function setRankReplace($resort, $type, $tagname)
    {
        // 组织结构
        $data = array();
        foreach ($resort as $v){
            $data[] = array(
                'relateid' => $v['relateid'],
                'type' => $type,
                'score_operate' => $v['score_operate'],
                'score_nature' => $v['nature_score'],
                'extends' => !empty($v['extend']) ? $v['extend'] : '',
            );
        }

        $exception = false;
        try{
            // 开新榜单，导入数据
            $newRankName = $tagname . '_' . date('YmdHis');
            echo 'rank:', $newRankName, PHP_EOL;
            $result = PepperClient::importRankElement($newRankName, json_encode($data));
            if ($result['rows'] < $total){
                $x = $total - $result['rows'];
                echo 'cha:', $x, PHP_EOL;
                if ($x > 10){
                    #warning("导入元素失败{$x}个", "从user_pool导入到rank的数据丢失", $x);
                }
            }
            if ($result['rows'] == 0){
                throw new Exception("批量导入元素到榜单{$newRankName}，结果为0");
            }

            // 将榜单指向到实际工作的新榜单
            $result = PepperClient::setRankExtends($tagname, $newRankName);
        }catch (Exception $e){
            $exception = true;
            //warning("批量导入元素到榜单{$newRankName}，及设置{$tagname}的操作异常: {$e->getMessage()}", "批量导入榜单元素&重设榜单属性异常", $e->getCode());
        }

        if (!$exception){
            $rankHistory = new RankHistory();
            list($total, $historys) = $rankHistory->getHistory($tagname);
            if ($total > 10){
                $expired = array_slice($historys, 0, $total - 10);
                foreach ($expired as $v){
                    try{
                        $result = PepperClient::delRank($v['rankname']);
                        if ($result['result']){
                            $result = $rankHistory->delRecord($v['id']);
                        }else{
                            throw new Exception("返回false");
                        }
                    }catch (Exception $e){
                        #warning("删除历史榜单{$v['rankname']}的操作异常: {$e->getMessage()}", "删除历史榜单异常", $e->getCode());
                    }
                }
            }

            if (!$rankHistory->record($tagname, $newRankName, 60)){
                //warning("记录历史榜单{$v['rankname']}失败", "记录历史榜单失败", 0);
            }
        }
    }

    public static function showError($msg)
    {/*{{{*/
        $content = <<<EOT
<!DOCTYPE html>
<html><head><meta charset="UTF-8"/></head><body>
<div style="margin:0 auto;width:640px;padding:5px;text-align:center;border:1px solid #ddd">
    <div style="color:red;text-align:center;background:#f5f5f5;font-size:14px;font-weight:bold;line-height:2;">出错了!</div>
    <div style="font-size:12px;padding:10px;word-break: break-all; word-wrap: break-word;">$msg<p><a href="javascript:history.go(-1);">&lt; 点击这里返回前页 &gt; </a></p></div>
</div>
</body></html>
EOT;
        echo $content;
        exit;
    }/*}}}*/

    public static function jumpMsg($msg, $url = null, $time = 3)
    {/*{{{*/
        $url = $url ? $url : $_SERVER['HTTP_REFERER'];
        $msg = $msg ? $msg : '操作成功！';

        $content = <<<EOT
<!DOCTYPE html>
<html><head><meta charset="UTF-8"/>
<meta http-equiv="Refresh" content="$time; URL=$url" />
</head><body>
<div style="margin:0 auto;width:640px;padding:5px;text-align:center;border:1px solid #ddd">
    <div style="text-align:center;background:#f5f5f5;font-size:14px;font-weight:bold;line-height:2;">提示信息:</div>
    <div style="font-size:12px;padding:10px;word-break: break-all; word-wrap: break-word;"><b style="color:green">$msg</b><p>$time 秒后自动跳转，
                若没有自动跳转请<a href="$url">[点击此处]</a></p></div>
</div>
</body></html>
EOT;
        echo "$content";
        exit;
    }/*}}}*/

    /**
     * htmlspecialchars封装
     * @param string $var
     * @param string $default
     * @param string $return
     * @return string
     */
    public static function S($var, $default = '', $return = false){
        if ($return){
            return $var ? htmlspecialchars($var, ENT_QUOTES) : htmlspecialchars($default, ENT_QUOTES);
        }
        echo ($var !== '' && $var !== null) ? htmlspecialchars($var, ENT_QUOTES) : htmlspecialchars($default, ENT_QUOTES);
    }

	public static function curl($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);

		$result = curl_exec($ch);

		if (curl_errno($ch)) {
			throw new Exception(curl_error($ch), 0);
		} else {
			$httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if (200 !== $httpStatusCode) {
				throw new Exception($result, $httpStatusCode);
			}
		}

		curl_close($ch);
		return $result;
	}

	public static function myCurl($url,$header=array(),$content=array(),$isPost=false){
		if (!empty($header)){
			$h=null;
			foreach ($header as $k=>$v){
				$h[]=$k.':'.$v;
			}
		}
		$ch = curl_init();
		if(substr($url,0,5)=='https'){
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);  // 从证书中检查SSL加密算法是否存在
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		if (!$isPost){
			$q=stripos($url,'?' )!==false?'':'?';
			$url.=$q.http_build_query($content);
		}
		curl_setopt($ch, CURLOPT_URL, $url);

		if ($h){
			curl_setopt($ch, CURLOPT_HTTPHEADER, $h);
		}

		if ($isPost){
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($content));
		}

		$response = curl_exec($ch);

		if (curl_errno($ch)) {
			throw new Exception(curl_error($ch), 0);
		} else {
			$httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if (200 !== $httpStatusCode) {
				throw new Exception($response, $httpStatusCode);
			}
		}

		curl_close($ch);
		return $response;
	}

    public static function excel($header, $data, $title)
    {
        require 'PHPExcel/PHPExcel/IOFactory.php';
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle($title);

        # 表头
        $object = $objPHPExcel->setActiveSheetIndex(0);
        foreach ($header as $key => $value) {
            $header_name = self::excelIntToChr($key) . '1';
            $object->setCellValue($header_name, $value);
        }
        unset($value);

        $i = 2;
        foreach ($data as $item) {
            foreach ($item as $colkey => $value) {
                $name = self::excelIntToChr($colkey) . $i;
                $object->setCellValue($name, $value);
            }
            unset($value);
            $i++;
        }
        unset($item);

        $objPHPExcel->getActiveSheet()->getStyle("A1:{$header_name}")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension());

        // Rename worksheet
        #$objPHPExcel->getActiveSheet()->setTitle($title);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $title . '.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }

    private static function excelIntToChr($index, $start = 65)
    {
        $str = '';
        if (floor($index / 26) > 0) {
            $str .= self::excelIntToChr(floor($index / 26) - 1);
        }
        return $str . chr($index % 26 + $start);
    }

    public static function loadExcel($file_path = '', $sheet = 0, $url)
    {
        require 'PHPExcel/PHPExcel/IOFactory.php';

        $PHPReader = new PHPExcel_Reader_Excel2007;
        if (!$PHPReader->canRead($file_path)) {
            $PHPReader = new PHPExcel_Reader_Excel5();
            if (!$PHPReader->canRead($file_path)) {
                Util::jumpMsg("文件格式错误, 不是excel文件", $url, 30);
                return;
            }
        }
        $PHPExcel = $PHPReader->load($file_path);        //建立excel对象
        $currentSheet = $PHPExcel->getSheet($sheet);        //**读取excel文件中的指定工作表*/
        $all_column = $currentSheet->getHighestColumn();        //**取得最大的列号*/
        $all_column = PHPExcel_Cell::columnIndexFromString($all_column);//**取得最大的列号*/
        $all_row = $currentSheet->getHighestRow();        //**取得一共有多少行*/
        $data = [];
        for ($row_index = 1; $row_index <= $all_row; $row_index++) {        //循环读取每个单元格的内容。注意行从1开始，列从A开始
            for ($column = 0; $column < $all_column; $column++) {
                //通过数字获取对应 列号
                $colIndex = PHPExcel_Cell::stringFromColumnIndex($column);
                $addr = $colIndex . $row_index;//对应下标
                $cell = $currentSheet->getCell($addr)->getValue();//获取对应值
                if ($cell instanceof PHPExcel_RichText) { //富文本转换字符串
                    $cell = $cell->__toString();
                }
                $data[$row_index][$colIndex] = $cell;
            }
        }
        return $data;
    }

    /**
     * 重新设置数组的key为自定义的key
     * @param  array $arr
     * @param  string $key
     * @return array
     */
    public static function arrayToKey($arr, $key = "id",$mul = 0) {

        $out = array();

        foreach ((array)$arr as $value){
            if($mul){
                $out[$value[$key]][] = $value;
            }else{
                $out[$value[$key]] = $value;
            }
        }

        return $out;
    }

    /**
     * 得到数组所有的key数组
     * @param  array $arr
     * @param  string $key
     * @return array
     */
    public static function arrayToIds($arr, $key = "id" ,$unique = true) {

        $ids    = array();

        foreach ((array)$arr as $value){
            $ids[] = $value[$key];
        }

        return $unique ? array_unique($ids) : $ids;
    }


    public static function getURLPath($url)
    {
        return parse_url($url, PHP_URL_PATH);
    }

    public static function joinStaticDomain($url)
    {
        if($url && stripos($url, 'http') !== 0){
            $url = Context::getConfig("STATIC_DOMAIN") .$url;
        }

        return $url;
    }


}

