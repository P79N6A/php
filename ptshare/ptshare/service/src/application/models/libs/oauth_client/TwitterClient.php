<?php

class TwitterClient
{
	/* {{{ */
	private $_baseurl = "https://api.twitter.com/";

	private $_version = "1.1";

	private $_appid = "2IR8wsaKGkUkKMm4OF4TH5Gq5";

	private $_secret = "jPMnEdteqo6uTPnlTCllZfFseHQn9m69Z7TbKPQUwJ3LD8QGUq";

	private $_redirect = "http://weiyingonline.com/test/twcallback";

	private $_token = '';

	private $_tokenSecret = '';

	private $_uid;

	public function __construct($token, $uid, $tokenSecret)
	{ /* {{{ */
		$this->_token = $token;
		$this->_uid = $uid;
		$this->_tokenSecret = $tokenSecret;
	}/* }}} */

	public function getOauthUrl($state = null)
	{ /* {{{ */
	}/* }}} */

	public function getAccessToken($code)
	{ /* {{{ */
	}/* }}} */

	public function getUser()
	{ /* {{{ */
		$user = $this->_callMethod("account/verify_credentials.json");

		if ($user) {
			return array(
				"rid" => $user["id"],
				"nickname" => $user["name"],
				"avatar" => "",
				"signature" => "",
			);
		}

		return false;
	}/* }}} */

	private function _callMethod($method, $params = array())
	{ /* {{{ */

		$data = $this->_get($method, $params);
		$data = json_decode($data, true);

		if (isset($data['errcode']) && isset($data['errmsg']) && $data['errcode'] != 0) {
			throw new OauthException($data['errmsg'], $data['errorcode']);
		}

		return $data;
	}/* }}} */

	private function _get($url, $params)
	{ /* {{{ */
		$url = $this->_baseurl .$this->_version .'/'. $url;

		$parameters = array(
				"oauth_version" =>'1.0',
				"oauth_nonce" => md5(microtime() . mt_rand()),
				"oauth_timestamp" => time(),
				"oauth_consumer_key" => $this->_appid,
				"oauth_token" => $this->_token,
				"oauth_signature_method"=>'HMAC-SHA1'
		);
		$parts = array(
				"GET",
				$url,
				$this->buildHttpQuery($parameters)
		);

		$signatureBase = implode('&', self::urlencodeRfc3986($parts));

		$key = implode('&', self::urlencodeRfc3986(array($this->_secret,$this->_tokenSecret)));

		$parameters['oauth_signature'] = base64_encode(hash_hmac('sha1', $signatureBase, $key, true));

		$authorization = array($this->toHeader($parameters));

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, $authorization);

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, true);
		curl_setopt($ch, CURLOPT_URL, $url);

		$curl_result = curl_exec($ch);
		$curl_errno = curl_errno($ch);
		$curl_errmsg = curl_error($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		curl_close($ch);

		if ((FALSE === $curl_result) || (0 !== $curl_errno)) {
			$error = "curl errno:$curl_errno,errmsg:$curl_errmsg\n";
			throw new OauthException($error);
		}

		if (200 != $http_code) {
			$error = "http code:$http_code,response:$curl_result\n";
			throw new OauthException($error);
		}

		return $curl_result;
	}/* }}} */


	private function buildHttpQuery(array $params)
	{/* {{{ */
		if (empty($params)) {
			return '';
		}

		$keys = self::urlencodeRfc3986(array_keys($params));
		$values = self::urlencodeRfc3986(array_values($params));
		$params = array_combine($keys, $values);

		uksort($params, 'strcmp');

		$pairs = array();
		foreach ($params as $parameter => $value) {
			if (is_array($value)) {
				sort($value, SORT_STRING);
				foreach ($value as $duplicateValue) {
					$pairs[] = $parameter . '=' . $duplicateValue;
				}
			} else {
				$pairs[] = $parameter . '=' . $value;
			}
		}
		return implode('&', $pairs);
	}/*}}}*/
	private static function urlencodeRfc3986($input)
	{/* {{{ */
		$output = '';
		if (is_array($input)) {
			$output = array_map('self::urlencodeRfc3986', $input);
		} elseif (is_scalar($input)) {
			$output = rawurlencode($input);
		}
		return $output;
	}/*}}}*/
	private function toHeader($parameters)
	{/* {{{ */
		$first = true;
		$out = 'Authorization: OAuth';
		foreach ($parameters as $k => $v) {
			if (substr($k, 0, 5) != "oauth") {
				continue;
			}
			if (is_array($v)) {
				throw new OauthException('Arrays not supported in headers');
			}
			$out .= ($first) ? ' ' : ', ';
			$out .= self::urlencodeRfc3986($k) . '="' . self::urlencodeRfc3986($v) . '"';
			$first = false;
		}

		return $out;
	}/*}}}*/
}/*}}}*/
