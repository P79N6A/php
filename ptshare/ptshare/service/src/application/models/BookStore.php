<?php
class BookStore {
	const DOUBAN_BOOK_ISBN_URL 		= "https://api.douban.com/v2/book/isbn/%s";//isbn定位图书
	const DOUBAN_BOOK_SEARCH_URL 	= "https://api.douban.com/v2/book/search?q=%s";//搜索图书列表

	static public function getStreamContext()
	{
		$context = stream_context_create(array(
				'http' => array(
				'timeout' => 5 //超时时间，单位为秒
				)
		));

		return $context;
	}
	static public function getImageUrl($file)
	{
		// 初始化一个 cURL 对象
		$curl = curl_init();
		// 设置你需要抓取的URL
		curl_setopt($curl, CURLOPT_URL, $file);
		// 设置header
		curl_setopt($curl, CURLOPT_HEADER, 0);
		// 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		// 运行cURL，请求网页
		$data = curl_exec($curl);
		curl_close($curl);
		return $data;
	}
	static public function getBookInfoByIsbn($isbn)
	{
		$url = sprintf(self::DOUBAN_BOOK_ISBN_URL, $isbn);
		$json_content = @file_get_contents($url, 0, self::getStreamContext());

		$storage = new Storage();
		$book_info = json_decode($json_content, true);

		$return = [];

		$return['title'] = empty($book_info['title']) ? $book_info['origin_title'] : $book_info['title'];
		$return['author'] = implode(" ", $book_info['author']);
		$return['translator'] = implode(" ", $book_info['translator']);
		$return['desc']   = $book_info['summary'];

		$image_url = $book_info['images']['large'];
		$pathinfo = pathinfo($image_url);
		$image_data = self::getImageUrl($image_url);
		$url = $storage->addImage($pathinfo['extension'], $image_data, 'default');
		$return['cover']  = $url;
		//$return['images'] = $book_info['images'];
		$return['publisher'] = $book_info['publisher'];


		return $return;
	}

	static public function getBookListByQ($query)
	{
		$url = sprintf(self::DOUBAN_BOOK_SEARCH_URL, $query);
		$json_content = @file_get_contents($url, 0, self::getStreamContext());

		return $json_content;
	}
}