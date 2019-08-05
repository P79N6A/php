<?php

/**
 * PHP SDK for weibo.com (using OAuth2)
 * 
 * @author Elmer Zhang <freeboy6716@gmail.com>
 */

/**
 * ����΢�� OAuth ��֤��(OAuth2)
 *
 * ��Ȩ����˵�����Ҳο�΢������ƽ̨�ĵ���{@link http://open.weibo.com/wiki/Oauth2}
 *
 * @package sae
 * @author Elmer Zhang
 * @version 1.0
 */
class SaeTOAuthV2
{

    /**
     *
     * @ignore
     *
     */
    public $client_id;

    /**
     *
     * @ignore
     *
     */
    public $client_secret;

    /**
     *
     * @ignore
     *
     */
    public $access_token;

    /**
     *
     * @ignore
     *
     */
    public $refresh_token;

    /**
     * Contains the last HTTP status code returned.
     *
     *
     * @ignore
     *
     */
    public $http_code;

    /**
     * Contains the last API call.
     *
     * @ignore
     *
     */
    public $url;

    /**
     * Set up the API root URL.
     *
     * @ignore
     *
     */
    public $host = "https://api.weibo.com/2/";

    /**
     * Set timeout default.
     *
     * @ignore
     *
     */
    public $timeout = 3;

    /**
     * Set connect timeout.
     *
     * @ignore
     *
     */
    public $connecttimeout = 2;

    /**
     * Verify SSL Cert.
     *
     * @ignore
     *
     */
    public $ssl_verifypeer = FALSE;

    /**
     * Respons format.
     *
     * @ignore
     *
     */
    public $format = 'json';

    /**
     * Decode returned json data.
     *
     * @ignore
     *
     */
    public $decode_json = TRUE;

    /**
     * Contains the last HTTP headers returned.
     *
     * @ignore
     *
     */
    public $http_info;

    /**
     * Set the useragnet.
     *
     * @ignore
     *
     */
    public $useragent = 'Sae T OAuth2 v0.1';

    /**
     * print the debug info
     *
     * @ignore
     *
     */
    public $debug = FALSE;

    /**
     * boundary of multipart
     * 
     * @ignore
     *
     */
    public static $boundary = '';

    /**
     * Set API URLS
     */
    /**
     *
     * @ignore
     *
     */
    function accessTokenURL()
    {
        return 'https://api.weibo.com/oauth2/access_token';
    }

    /**
     *
     * @ignore
     *
     */
    function authorizeURL()
    {
        return 'https://api.weibo.com/oauth2/authorize';
    }

    /**
     * construct WeiboOAuth object
     */
    function __construct($client_id, $client_secret, $access_token = NULL, $refresh_token = NULL)
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->access_token = $access_token;
        $this->refresh_token = $refresh_token;
    }

    /**
     * authorize�ӿ�
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/Oauth2/authorize Oauth2/authorize}
     *
     * @param string $url
     *            ��Ȩ��Ļص���ַ,վ��Ӧ������ص���ַһ��,վ��Ӧ����Ҫ��дcanvas page�ĵ�ַ
     * @param string $response_type
     *            ֧�ֵ�ֵ���� code ��token Ĭ��ֵΪcode
     * @param string $state
     *            ���ڱ�������ͻص���״̬���ڻص�ʱ,����Query Parameter�лش��ò���
     * @param string $display
     *            ��Ȩҳ������ ��ѡ��Χ:
     *            - default Ĭ����Ȩҳ��
     *            - mobile ֧��html5���ֻ�
     *            - popup ������Ȩҳ
     *            - wap1.2 wap1.2ҳ��
     *            - wap2.0 wap2.0ҳ��
     *            - js js-sdk ר�� ��Ȩҳ���ǵ��������ؽ��Ϊjs-sdk�ص�����
     *            - apponweibo վ��Ӧ��ר��,վ��Ӧ�ò���display����,����response_typeΪtokenʱ,Ĭ��ʹ�ø�display.��Ȩ�󲻻᷵��access_token��ֻ�����jsˢ��վ��Ӧ�ø����
     * @return array
     */
    function getAuthorizeURL($url, $response_type = 'code', $state = NULL, $display = NULL)
    {
        $params = array();
        $params['client_id'] = $this->client_id;
        $params['redirect_uri'] = $url;
        $params['response_type'] = $response_type;
        $params['state'] = $state;
        $params['display'] = $display;
        return $this->authorizeURL() . "?" . http_build_query($params);
    }

    /**
     * access_token�ӿ�
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/OAuth2/access_token OAuth2/access_token}
     *
     * @param string $type
     *            ���������,����Ϊ:code, password, token
     * @param array $keys
     *            �������
     *            - ��$typeΪcodeʱ�� array('code'=>..., 'redirect_uri'=>...)
     *            - ��$typeΪpasswordʱ�� array('username'=>..., 'password'=>...)
     *            - ��$typeΪtokenʱ�� array('refresh_token'=>...)
     * @return array
     */
    function getAccessToken($type = 'code', $keys)
    {
        $params = array();
        $params['client_id'] = $this->client_id;
        $params['client_secret'] = $this->client_secret;
        if ($type === 'token') {
            $params['grant_type'] = 'refresh_token';
            $params['refresh_token'] = $keys['refresh_token'];
        } elseif ($type === 'code') {
            $params['grant_type'] = 'authorization_code';
            $params['code'] = $keys['code'];
            $params['redirect_uri'] = $keys['redirect_uri'];
        } elseif ($type === 'password') {
            $params['grant_type'] = 'password';
            $params['username'] = $keys['username'];
            $params['password'] = $keys['password'];
        } else {
            throw new OAuthException("wrong auth type");
        }
        
        $response = $this->oAuthRequest($this->accessTokenURL(), 'POST', $params);
        $token = json_decode($response, true);
        if (is_array($token) && ! isset($token['error'])) {
            $this->access_token = $token['access_token'];
            // $this->refresh_token = $token['refresh_token'];
        } else {
            throw new OAuthException("get access token failed." . $token['error']);
        }
        return $token;
    }

    /**
     * ���� signed_request
     *
     * @param string $signed_request
     *            Ӧ�ÿ���ڼ���iframeʱ��ͨ����Canvas URL post�Ĳ���signed_request
     *            
     * @return array
     */
    function parseSignedRequest($signed_request)
    {
        list ($encoded_sig, $payload) = explode('.', $signed_request, 2);
        $sig = self::base64decode($encoded_sig);
        $data = json_decode(self::base64decode($payload), true);
        if (strtoupper($data['algorithm']) !== 'HMAC-SHA256')
            return '-1';
        $expected_sig = hash_hmac('sha256', $payload, $this->client_secret, true);
        return ($sig !== $expected_sig) ? '-2' : $data;
    }

    /**
     *
     * @ignore
     *
     */
    function base64decode($str)
    {
        return base64_decode(strtr($str . str_repeat('=', (4 - strlen($str) % 4)), '-_', '+/'));
    }

    /**
     * ��ȡjssdk��Ȩ��Ϣ�����ں�jssdk��ͬ����¼
     *
     * @return array �ɹ�����array('access_token'=>'value', 'refresh_token'=>'value'); ʧ�ܷ���false
     */
    function getTokenFromJSSDK()
    {
        $key = "weibojs_" . $this->client_id;
        if (isset($_COOKIE[$key]) && $cookie = $_COOKIE[$key]) {
            parse_str($cookie, $token);
            if (isset($token['access_token']) && isset($token['refresh_token'])) {
                $this->access_token = $token['access_token'];
                $this->refresh_token = $token['refresh_token'];
                return $token;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * �������ж�ȡaccess_token��refresh_token
     * �����ڴ�Session��Cookie�ж�ȡtoken����ͨ��Session/Cookie���Ƿ����token�жϵ�¼״̬��
     *
     * @param array $arr
     *            ����access_token��secret_token������
     * @return array �ɹ�����array('access_token'=>'value', 'refresh_token'=>'value'); ʧ�ܷ���false
     */
    function getTokenFromArray($arr)
    {
        if (isset($arr['access_token']) && $arr['access_token']) {
            $token = array();
            $this->access_token = $token['access_token'] = $arr['access_token'];
            if (isset($arr['refresh_token']) && $arr['refresh_token']) {
                $this->refresh_token = $token['refresh_token'] = $arr['refresh_token'];
            }
            
            return $token;
        } else {
            return false;
        }
    }

    /**
     * GET wrappwer for oAuthRequest.
     *
     * @return mixed
     */
    function get($url, $parameters = array())
    {
        $response = $this->oAuthRequest($url, 'GET', $parameters);
        if ($this->format === 'json' && $this->decode_json) {
            return json_decode($response, true);
        }
        return $response;
    }

    /**
     * POST wreapper for oAuthRequest.
     *
     * @return mixed
     */
    function post($url, $parameters = array(), $multi = false)
    {
        $response = $this->oAuthRequest($url, 'POST', $parameters, $multi);
        if ($this->format === 'json' && $this->decode_json) {
            return json_decode($response, true);
        }
        return $response;
    }

    /**
     * DELTE wrapper for oAuthReqeust.
     *
     * @return mixed
     */
    function delete($url, $parameters = array())
    {
        $response = $this->oAuthRequest($url, 'DELETE', $parameters);
        if ($this->format === 'json' && $this->decode_json) {
            return json_decode($response, true);
        }
        return $response;
    }

    /**
     * Format and sign an OAuth / API request
     *
     * @return string
     * @ignore
     *
     */
    function oAuthRequest($url, $method, $parameters, $multi = false)
    {
        if (strrpos($url, 'http://') !== 0 && strrpos($url, 'https://') !== 0) {
            $url = "{$this->host}{$url}.{$this->format}";
        }
        
        switch ($method) {
            case 'GET':
                $url = $url . '?' . http_build_query($parameters);
                return $this->http($url, 'GET');
            default:
                $headers = array();
                if (! $multi && (is_array($parameters) || is_object($parameters))) {
                    $body = http_build_query($parameters);
                } else {
                    $body = self::build_http_query_multi($parameters);
                    $headers[] = "Content-Type: multipart/form-data; boundary=" . self::$boundary;
                }
                return $this->http($url, $method, $body, $headers);
        }
    }

    /**
     * Make an HTTP request
     *
     * @return string API results
     * @ignore
     *
     */
    function http($url, $method, $postfields = NULL, $headers = array())
    {
        $this->http_info = array();
        $ci = curl_init();
        /* Curl settings */
        curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ci, CURLOPT_USERAGENT, $this->useragent);
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
        curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ci, CURLOPT_ENCODING, "");
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
        curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, 1);
        curl_setopt($ci, CURLOPT_HEADERFUNCTION, array(
            $this,
            'getHeader'
        ));
        curl_setopt($ci, CURLOPT_HEADER, FALSE);
        
        switch ($method) {
            case 'POST':
                curl_setopt($ci, CURLOPT_POST, TRUE);
                if (! empty($postfields)) {
                    curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
                    $this->postdata = $postfields;
                }
                break;
            case 'DELETE':
                curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
                if (! empty($postfields)) {
                    $url = "{$url}?{$postfields}";
                }
        }
        
        if (isset($this->access_token) && $this->access_token)
            $headers[] = "Authorization: OAuth2 " . $this->access_token;
        
        if (! empty($this->remote_ip)) {
            if (defined('SAE_ACCESSKEY')) {
                $headers[] = "SaeRemoteIP: " . $this->remote_ip;
            } else {
                $headers[] = "API-RemoteIP: " . $this->remote_ip;
            }
        } else {
            if (! defined('SAE_ACCESSKEY')) {
                $headers[] = "API-RemoteIP: " . $_SERVER['REMOTE_ADDR'];
            }
        }
        curl_setopt($ci, CURLOPT_URL, $url);
        curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE);
        
        $response = curl_exec($ci);
        $this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
        $this->http_info = array_merge($this->http_info, curl_getinfo($ci));
        $this->url = $url;
        
        if ($this->debug) {
            echo "=====post data======\r\n";
            var_dump($postfields);
            
            echo "=====headers======\r\n";
            print_r($headers);
            
            echo '=====request info=====' . "\r\n";
            print_r(curl_getinfo($ci));
            
            echo '=====response=====' . "\r\n";
            print_r($response);
        }
        curl_close($ci);
        return $response;
    }

    /**
     * Get the header info to store.
     *
     * @return int
     * @ignore
     *
     */
    function getHeader($ch, $header)
    {
        $i = strpos($header, ':');
        if (! empty($i)) {
            $key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
            $value = trim(substr($header, $i + 2));
            $this->http_header[$key] = $value;
        }
        return strlen($header);
    }

    /**
     *
     * @ignore
     *
     */
    public static function build_http_query_multi($params)
    {
        if (! $params)
            return '';
        
        uksort($params, 'strcmp');
        
        $pairs = array();
        
        self::$boundary = $boundary = uniqid('------------------');
        $MPboundary = '--' . $boundary;
        $endMPboundary = $MPboundary . '--';
        $multipartbody = '';
        
        foreach ($params as $parameter => $value) {
            
            if (in_array($parameter, array(
                'pic',
                'image'
            )) && $value{0} == '@') {
                $url = ltrim($value, '@');
                $content = file_get_contents($url);
                $array = explode('?', basename($url));
                $filename = $array[0];
                
                $multipartbody .= $MPboundary . "\r\n";
                $multipartbody .= 'Content-Disposition: form-data; name="' . $parameter . '"; filename="' . $filename . '"' . "\r\n";
                $multipartbody .= "Content-Type: image/unknown\r\n\r\n";
                $multipartbody .= $content . "\r\n";
            } else {
                $multipartbody .= $MPboundary . "\r\n";
                $multipartbody .= 'content-disposition: form-data; name="' . $parameter . "\"\r\n\r\n";
                $multipartbody .= $value . "\r\n";
            }
        }
        
        $multipartbody .= $endMPboundary;
        return $multipartbody;
    }
}

/**
 * ����΢��������V2
 *
 * ʹ��ǰ��Ҫ���ֹ�����saetv2.ex.class.php <br />
 *
 * @package sae
 * @author Easy Chen, Elmer Zhang,Lazypeople
 * @version 1.0
 */
class SaeTClientV2
{

    /**
     * ���캯��
     *
     * @access public
     * @param mixed $akey
     *            ΢������ƽ̨Ӧ��APP KEY
     * @param mixed $skey
     *            ΢������ƽ̨Ӧ��APP SECRET
     * @param mixed $access_token
     *            OAuth��֤���ص�token
     * @param mixed $refresh_token
     *            OAuth��֤���ص�token secret
     * @return void
     */
    function __construct($akey, $skey, $access_token, $refresh_token = NULL)
    {
        $this->oauth = new SaeTOAuthV2($akey, $skey, $access_token, $refresh_token);
    }

    /**
     * ����������Ϣ
     *
     * ����������Ϣ��SDK�Ὣÿ������΢��API���͵�POST Data��Headers�Լ�������Ϣ�������������������
     *
     * @access public
     * @param bool $enable
     *            �Ƿ���������Ϣ
     * @return void
     */
    function set_debug($enable)
    {
        $this->oauth->debug = $enable;
    }

    /**
     * �����û�IP
     *
     * SDKĬ�Ͻ���ͨ��$_SERVER['REMOTE_ADDR']��ȡ�û�IP��������΢��APIʱ���û�IP���ӵ�Request Header�С���ĳЩ�����$_SERVER['REMOTE_ADDR']ȡ����IP�����û�IP������һ���̶���IP������ʹ��SAE��Cron��TaskQueue����ʱ������ʱ���п��ܻ���ɸù̶�IP�ﵽ΢��API����Ƶ���޶����API����ʧ�ܡ���ʱ��ʹ�ñ����������û�IP���Ա�������⡣
     *
     * @access public
     * @param string $ip
     *            �û�IP
     * @return bool IPΪ�Ƿ�IP�ַ�ʱ������false�����򷵻�true
     */
    function set_remote_ip($ip)
    {
        if (ip2long($ip) !== false) {
            $this->oauth->remote_ip = $ip;
            return true;
        } else {
            return false;
        }
    }

    /**
     * ��ȡ���µĹ���΢����Ϣ
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/statuses/public_timeline statuses/public_timeline}
     *
     * @access public
     * @param int $count
     *            ��ҳ���صļ�¼����Ĭ��Ϊ50��
     * @param int $page
     *            ���ؽ���ҳ�룬Ĭ��Ϊ1��
     * @param int $base_app
     *            �Ƿ�ֻ��ȡ��ǰӦ�õ���ݡ�0Ϊ��������ݣ���1Ϊ�ǣ�����ǰӦ�ã���Ĭ��Ϊ0��
     * @return array
     */
    function public_timeline($page = 1, $count = 50, $base_app = 0)
    {
        $params = array();
        $params['count'] = intval($count);
        $params['page'] = intval($page);
        $params['base_app'] = intval($base_app);
        return $this->oauth->get('statuses/public_timeline', $params); // �����ǽӿڵ�bug���ܲ�ȫ
    }

    /**
     * ��ȡ��ǰ��¼�û��������ע�û�������΢����Ϣ��
     *
     * ��ȡ��ǰ��¼�û��������ע�û�������΢����Ϣ�����û���¼ http://weibo.com ���ڡ��ҵ���ҳ���п�����������ͬ��ͬfriends_timeline()
     * <br />��ӦAPI��{@link http://open.weibo.com/wiki/2/statuses/home_timeline statuses/home_timeline}
     *
     * @access public
     * @param int $page
     *            ָ�����ؽ���ҳ�롣��ݵ�ǰ��¼�û����ע���û�����Щ����ע�û������΢����ҳ��������ܲ鿴���ܼ�¼�������ͬ��ͨ������ܲ鿴1000�����ҡ�Ĭ��ֵ1����ѡ��
     * @param int $count
     *            ÿ�η��صļ�¼��ȱʡֵ50�����ֵ200����ѡ��
     * @param int $since_id
     *            ��ָ���˲�����ֻ����ID��since_id���΢����Ϣ������since_id����ʱ�����΢����Ϣ������ѡ��
     * @param int $max_id
     *            ��ָ���˲����򷵻�IDС�ڻ����max_id��΢����Ϣ����ѡ��
     * @param int $base_app
     *            �Ƿ�ֻ��ȡ��ǰӦ�õ���ݡ�0Ϊ��������ݣ���1Ϊ�ǣ�����ǰӦ�ã���Ĭ��Ϊ0��
     * @param int $feature
     *            ��������ID��0��ȫ����1��ԭ����2��ͼƬ��3����Ƶ��4�����֣�Ĭ��Ϊ0��
     * @return array
     */
    function home_timeline($page = 1, $count = 50, $since_id = 0, $max_id = 0, $base_app = 0, $feature = 0)
    {
        $params = array();
        if ($since_id) {
            $this->id_format($since_id);
            $params['since_id'] = $since_id;
        }
        if ($max_id) {
            $this->id_format($max_id);
            $params['max_id'] = $max_id;
        }
        $params['count'] = intval($count);
        $params['page'] = intval($page);
        $params['base_app'] = intval($base_app);
        $params['feature'] = intval($feature);
        
        return $this->oauth->get('statuses/home_timeline', $params);
    }

    /**
     * ��ȡ��ǰ��¼�û��������ע�û�������΢����Ϣ��
     *
     * ��ȡ��ǰ��¼�û��������ע�û�������΢����Ϣ�����û���¼ http://weibo.com ���ڡ��ҵ���ҳ���п�����������ͬ��ͬhome_timeline()
     * <br />��ӦAPI��{@link http://open.weibo.com/wiki/2/statuses/friends_timeline statuses/friends_timeline}
     *
     * @access public
     * @param int $page
     *            ָ�����ؽ���ҳ�롣��ݵ�ǰ��¼�û����ע���û�����Щ����ע�û������΢����ҳ��������ܲ鿴���ܼ�¼�������ͬ��ͨ������ܲ鿴1000�����ҡ�Ĭ��ֵ1����ѡ��
     * @param int $count
     *            ÿ�η��صļ�¼��ȱʡֵ50�����ֵ200����ѡ��
     * @param int $since_id
     *            ��ָ���˲�����ֻ����ID��since_id���΢����Ϣ������since_id����ʱ�����΢����Ϣ������ѡ��
     * @param int $max_id
     *            ��ָ���˲����򷵻�IDС�ڻ����max_id��΢����Ϣ����ѡ��
     * @param int $base_app
     *            �Ƿ���ڵ�ǰӦ������ȡ��ݡ�1Ϊ���Ʊ�Ӧ��΢����0Ϊ�������ơ�Ĭ��Ϊ0����ѡ��
     * @param int $feature
     *            ΢�����ͣ�0ȫ����1ԭ����2ͼƬ��3��Ƶ��4����. ����ָ�����͵�΢����Ϣ���ݡ�תΪΪ0����ѡ��
     * @return array
     */
    function friends_timeline($page = 1, $count = 50, $since_id = 0, $max_id = 0, $base_app = 0, $feature = 0)
    {
        return $this->home_timeline($since_id, $max_id, $count, $page, $base_app, $feature);
    }

    /**
     * ��ȡ�û�������΢����Ϣ�б�
     *
     * �����û��ķ��������n����Ϣ�����û�΢��ҳ�淵��������һ�µġ��˽ӿ�Ҳ�������������û������·���΢����
     * <br />��ӦAPI��{@link http://open.weibo.com/wiki/2/statuses/user_timeline statuses/user_timeline}
     *
     * @access public
     * @param int $page
     *            ҳ��
     * @param int $count
     *            ÿ�η��ص�����¼����෵��200����Ĭ��50��
     * @param mixed $uid
     *            ָ���û�UID��΢���ǳ�
     * @param int $since_id
     *            ��ָ���˲�����ֻ����ID��since_id���΢����Ϣ������since_id����ʱ�����΢����Ϣ������ѡ��
     * @param int $max_id
     *            ��ָ���˲����򷵻�IDС�ڻ����max_id���ᵽ��ǰ��¼�û�΢����Ϣ����ѡ��
     * @param int $base_app
     *            �Ƿ���ڵ�ǰӦ������ȡ��ݡ�1Ϊ���Ʊ�Ӧ��΢����0Ϊ�������ơ�Ĭ��Ϊ0��
     * @param int $feature
     *            ��������ID��0��ȫ����1��ԭ����2��ͼƬ��3����Ƶ��4�����֣�Ĭ��Ϊ0��
     * @param int $trim_user
     *            ����ֵ��user��Ϣ���أ�0�����������user��Ϣ��1��user�ֶν�����uid��Ĭ��Ϊ0��
     * @return array
     */
    function user_timeline_by_id($uid = NULL, $page = 1, $count = 50, $since_id = 0, $max_id = 0, $feature = 0, $trim_user = 0, $base_app = 0)
    {
        $params = array();
        $params['uid'] = $uid;
        if ($since_id) {
            $this->id_format($since_id);
            $params['since_id'] = $since_id;
        }
        if ($max_id) {
            $this->id_format($max_id);
            $params['max_id'] = $max_id;
        }
        $params['base_app'] = intval($base_app);
        $params['feature'] = intval($feature);
        $params['count'] = intval($count);
        $params['page'] = intval($page);
        $params['trim_user'] = intval($trim_user);
        
        return $this->oauth->get('statuses/user_timeline', $params);
    }

    /**
     * ��ȡ�û�������΢����Ϣ�б�
     *
     * �����û��ķ��������n����Ϣ�����û�΢��ҳ�淵��������һ�µġ��˽ӿ�Ҳ�������������û������·���΢����
     * <br />��ӦAPI��{@link http://open.weibo.com/wiki/2/statuses/user_timeline statuses/user_timeline}
     *
     * @access public
     * @param string $screen_name
     *            ΢���ǳƣ���Ҫ����������û�UID��΢���ǳƣ�������һ�����������ʱ�򣬽���ʹ�øò���
     * @param int $page
     *            ҳ��
     * @param int $count
     *            ÿ�η��ص�����¼����෵��200����Ĭ��50��
     * @param int $since_id
     *            ��ָ���˲�����ֻ����ID��since_id���΢����Ϣ������since_id����ʱ�����΢����Ϣ������ѡ��
     * @param int $max_id
     *            ��ָ���˲����򷵻�IDС�ڻ����max_id���ᵽ��ǰ��¼�û�΢����Ϣ����ѡ��
     * @param int $feature
     *            ��������ID��0��ȫ����1��ԭ����2��ͼƬ��3����Ƶ��4�����֣�Ĭ��Ϊ0��
     * @param int $trim_user
     *            ����ֵ��user��Ϣ���أ�0�����������user��Ϣ��1��user�ֶν�����uid��Ĭ��Ϊ0��
     * @param int $base_app
     *            �Ƿ���ڵ�ǰӦ������ȡ��ݡ�1Ϊ���Ʊ�Ӧ��΢����0Ϊ�������ơ�Ĭ��Ϊ0��
     * @return array
     */
    function user_timeline_by_name($screen_name = NULL, $page = 1, $count = 50, $since_id = 0, $max_id = 0, $feature = 0, $trim_user = 0, $base_app = 0)
    {
        $params = array();
        $params['screen_name'] = $screen_name;
        if ($since_id) {
            $this->id_format($since_id);
            $params['since_id'] = $since_id;
        }
        if ($max_id) {
            $this->id_format($max_id);
            $params['max_id'] = $max_id;
        }
        $params['base_app'] = intval($base_app);
        $params['feature'] = intval($feature);
        $params['count'] = intval($count);
        $params['page'] = intval($page);
        $params['trim_user'] = intval($trim_user);
        
        return $this->oauth->get('statuses/user_timeline', $params);
    }

    /**
     * ������ȡָ����һ���û���timeline
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/statuses/timeline_batch statuses/timeline_batch}
     *
     * @param string $screen_name
     *            ��Ҫ��ѯ���û��ǳƣ��ð�Ƕ��ŷָ���һ�����20��
     * @param int $count
     *            ��ҳ���صļ�¼����Ĭ��Ϊ50��
     * @param int $page
     *            ���ؽ���ҳ�룬Ĭ��Ϊ1��
     * @param int $base_app
     *            �Ƿ�ֻ��ȡ��ǰӦ�õ���ݡ�0Ϊ��������ݣ���1Ϊ�ǣ�����ǰӦ�ã���Ĭ��Ϊ0��
     * @param int $feature
     *            ��������ID��0��ȫ����1��ԭ����2��ͼƬ��3����Ƶ��4�����֣�Ĭ��Ϊ0��
     * @return array
     */
    function timeline_batch_by_name($screen_name, $page = 1, $count = 50, $feature = 0, $base_app = 0)
    {
        $params = array();
        if (is_array($screen_name) && ! empty($screen_name)) {
            $params['screen_name'] = join(',', $screen_name);
        } else {
            $params['screen_name'] = $screen_name;
        }
        $params['count'] = intval($count);
        $params['page'] = intval($page);
        $params['base_app'] = intval($base_app);
        $params['feature'] = intval($feature);
        return $this->oauth->get('statuses/timeline_batch', $params);
    }

    /**
     * ������ȡָ����һ���û���timeline
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/statuses/timeline_batch statuses/timeline_batch}
     *
     * @param string $uids
     *            ��Ҫ��ѯ���û�ID���ð�Ƕ��ŷָ���һ�����20����
     * @param int $count
     *            ��ҳ���صļ�¼����Ĭ��Ϊ50��
     * @param int $page
     *            ���ؽ���ҳ�룬Ĭ��Ϊ1��
     * @param int $base_app
     *            �Ƿ�ֻ��ȡ��ǰӦ�õ���ݡ�0Ϊ��������ݣ���1Ϊ�ǣ�����ǰӦ�ã���Ĭ��Ϊ0��
     * @param int $feature
     *            ��������ID��0��ȫ����1��ԭ����2��ͼƬ��3����Ƶ��4�����֣�Ĭ��Ϊ0��
     * @return array
     */
    function timeline_batch_by_id($uids, $page = 1, $count = 50, $feature = 0, $base_app = 0)
    {
        $params = array();
        if (is_array($uids) && ! empty($uids)) {
            foreach ($uids as $k => $v) {
                $this->id_format($uids[$k]);
            }
            $params['uids'] = join(',', $uids);
        } else {
            $params['uids'] = $uids;
        }
        $params['count'] = intval($count);
        $params['page'] = intval($page);
        $params['base_app'] = intval($base_app);
        $params['feature'] = intval($feature);
        return $this->oauth->get('statuses/timeline_batch', $params);
    }

    /**
     * ����һ��ԭ��΢����Ϣ������n��ת��΢����Ϣ�����ӿ��޷��Է�ԭ��΢�����в�ѯ��
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/statuses/repost_timeline statuses/repost_timeline}
     *
     * @access public
     * @param int $sid
     *            Ҫ��ȡת��΢���б��ԭ��΢��ID��
     * @param int $page
     *            ���ؽ���ҳ�롣
     * @param int $count
     *            ��ҳ���ص�����¼����෵��200����Ĭ��50����ѡ��
     * @param int $since_id
     *            ��ָ���˲�����ֻ����ID��since_id��ļ�¼����since_id����ʱ���?����ѡ��
     * @param int $max_id
     *            ��ָ���˲����򷵻�IDС�ڻ����max_id�ļ�¼����ѡ��
     * @param int $filter_by_author
     *            ����ɸѡ���ͣ�0��ȫ����1���ҹ�ע���ˡ�2��İ���ˣ�Ĭ��Ϊ0��
     * @return array
     */
    function repost_timeline($sid, $page = 1, $count = 50, $since_id = 0, $max_id = 0, $filter_by_author = 0)
    {
        $this->id_format($sid);
        
        $params = array();
        $params['id'] = $sid;
        if ($since_id) {
            $this->id_format($since_id);
            $params['since_id'] = $since_id;
        }
        if ($max_id) {
            $this->id_format($max_id);
            $params['max_id'] = $max_id;
        }
        $params['filter_by_author'] = intval($filter_by_author);
        
        return $this->request_with_pager('statuses/repost_timeline', $page, $count, $params);
    }

    /**
     * ��ȡ��ǰ�û�����ת����n��΢����Ϣ
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/statuses/repost_by_me statuses/repost_by_me}
     *
     * @access public
     * @param int $page
     *            ���ؽ���ҳ�롣
     * @param int $count
     *            ÿ�η��ص�����¼����෵��200����Ĭ��50����ѡ��
     * @param int $since_id
     *            ��ָ���˲�����ֻ����ID��since_id��ļ�¼����since_id����ʱ���?����ѡ��
     * @param int $max_id
     *            ��ָ���˲����򷵻�IDС�ڻ����max_id�ļ�¼����ѡ��
     * @return array
     */
    function repost_by_me($page = 1, $count = 50, $since_id = 0, $max_id = 0)
    {
        $params = array();
        if ($since_id) {
            $this->id_format($since_id);
            $params['since_id'] = $since_id;
        }
        if ($max_id) {
            $this->id_format($max_id);
            $params['max_id'] = $max_id;
        }
        
        return $this->request_with_pager('statuses/repost_by_me', $page, $count, $params);
    }

    /**
     * ��ȡ@��ǰ�û���΢���б�
     *
     * ��������n���ᵽ��¼�û���΢����Ϣ������@username��΢����Ϣ��
     * <br />��ӦAPI��{@link http://open.weibo.com/wiki/2/statuses/mentions statuses/mentions}
     *
     * @access public
     * @param int $page
     *            ���ؽ���ҳ��š�
     * @param int $count
     *            ÿ�η��ص�����¼��ҳ���С����������200��Ĭ��Ϊ50��
     * @param int $since_id
     *            ��ָ���˲�����ֻ����ID��since_id���΢����Ϣ������since_id����ʱ�����΢����Ϣ������ѡ��
     * @param int $max_id
     *            ��ָ���˲����򷵻�IDС�ڻ����max_id���ᵽ��ǰ��¼�û�΢����Ϣ����ѡ��
     * @param int $filter_by_author
     *            ����ɸѡ���ͣ�0��ȫ����1���ҹ�ע���ˡ�2��İ���ˣ�Ĭ��Ϊ0��
     * @param int $filter_by_source
     *            ��Դɸѡ���ͣ�0��ȫ����1������΢����2������΢Ⱥ��Ĭ��Ϊ0��
     * @param int $filter_by_type
     *            ԭ��ɸѡ���ͣ�0��ȫ��΢����1��ԭ����΢����Ĭ��Ϊ0��
     * @return array
     */
    function mentions($page = 1, $count = 50, $since_id = 0, $max_id = 0, $filter_by_author = 0, $filter_by_source = 0, $filter_by_type = 0)
    {
        $params = array();
        if ($since_id) {
            $this->id_format($since_id);
            $params['since_id'] = $since_id;
        }
        if ($max_id) {
            $this->id_format($max_id);
            $params['max_id'] = $max_id;
        }
        $params['filter_by_author'] = $filter_by_author;
        $params['filter_by_source'] = $filter_by_source;
        $params['filter_by_type'] = $filter_by_type;
        
        return $this->request_with_pager('statuses/mentions', $page, $count, $params);
    }

    /**
     * ���ID��ȡ����΢����Ϣ����
     *
     * ��ȡ����ID��΢����Ϣ��������Ϣ��ͬʱ���ء�
     * <br />��ӦAPI��{@link http://open.weibo.com/wiki/2/statuses/show statuses/show}
     *
     * @access public
     * @param int $id
     *            Ҫ��ȡ�ѷ����΢��ID, ��ID�����ڷ��ؿ�
     * @return array
     */
    function show_status($id)
    {
        $this->id_format($id);
        $params = array();
        $params['id'] = $id;
        return $this->oauth->get('statuses/show', $params);
    }

    /**
     * ���΢��id�Ż�ȡ΢������Ϣ
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/statuses/show_batch statuses/show_batch}
     *
     * @param string $ids
     *            ��Ҫ��ѯ��΢��ID���ð�Ƕ��ŷָ�����಻����50����
     * @return array
     */
    function show_batch($ids)
    {
        $params = array();
        if (is_array($ids) && ! empty($ids)) {
            foreach ($ids as $k => $v) {
                $this->id_format($ids[$k]);
            }
            $params['ids'] = join(',', $ids);
        } else {
            $params['ids'] = $ids;
        }
        return $this->oauth->get('statuses/show_batch', $params);
    }

    /**
     * ͨ��΢�������ۡ�˽�ţ�ID��ȡ��MID
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/statuses/querymid statuses/querymid}
     *
     * @param int|string $id
     *            ��Ҫ��ѯ��΢�������ۡ�˽�ţ�ID������ģʽ�£��ð�Ƕ��ŷָ�����಻����20����
     * @param int $type
     *            ��ȡ���ͣ�1��΢����2�����ۡ�3��˽�ţ�Ĭ��Ϊ1��
     * @param int $is_batch
     *            �Ƿ�ʹ������ģʽ��0����1���ǣ�Ĭ��Ϊ0��
     * @return array
     */
    function querymid($id, $type = 1, $is_batch = 0)
    {
        $params = array();
        $params['id'] = $id;
        $params['type'] = intval($type);
        $params['is_batch'] = intval($is_batch);
        return $this->oauth->get('statuses/querymid', $params);
    }

    /**
     * ͨ��΢�������ۡ�˽�ţ�MID��ȡ��ID
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/statuses/queryid statuses/queryid}
     *
     * @param int|string $mid
     *            ��Ҫ��ѯ��΢�������ۡ�˽�ţ�MID������ģʽ�£��ð�Ƕ��ŷָ�����಻����20����
     * @param int $type
     *            ��ȡ���ͣ�1��΢����2�����ۡ�3��˽�ţ�Ĭ��Ϊ1��
     * @param int $is_batch
     *            �Ƿ�ʹ������ģʽ��0����1���ǣ�Ĭ��Ϊ0��
     * @param int $inbox
     *            ����˽����Ч����MID����Ϊ˽��ʱ�ô˲���0�������䡢1���ռ��䣬Ĭ��Ϊ0 ��
     * @param int $isBase62
     *            MID�Ƿ���base62���룬0����1���ǣ�Ĭ��Ϊ0��
     * @return array
     */
    function queryid($mid, $type = 1, $is_batch = 0, $inbox = 0, $isBase62 = 0)
    {
        $params = array();
        $params['mid'] = $mid;
        $params['type'] = intval($type);
        $params['is_batch'] = intval($is_batch);
        $params['inbox'] = intval($inbox);
        $params['isBase62'] = intval($isBase62);
        return $this->oauth->get('statuses/queryid', $params);
    }

    /**
     * ���췵������΢��ת�����΢���б�
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/statuses/hot/repost_daily statuses/hot/repost_daily}
     *
     * @param int $count
     *            ���صļ�¼������󲻳���50��Ĭ��Ϊ20��
     * @param int $base_app
     *            �Ƿ�ֻ��ȡ��ǰӦ�õ���ݡ�0Ϊ��������ݣ���1Ϊ�ǣ�����ǰӦ�ã���Ĭ��Ϊ0��
     * @return array
     */
    function repost_daily($count = 20, $base_app = 0)
    {
        $params = array();
        $params['count'] = intval($count);
        $params['base_app'] = intval($base_app);
        return $this->oauth->get('statuses/hot/repost_daily', $params);
    }

    /**
     * ���ܷ�������΢��ת�����΢���б�
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/statuses/hot/repost_weekly statuses/hot/repost_weekly}
     *
     * @param int $count
     *            ���صļ�¼������󲻳���50��Ĭ��Ϊ20��
     * @param int $base_app
     *            �Ƿ�ֻ��ȡ��ǰӦ�õ���ݡ�0Ϊ��������ݣ���1Ϊ�ǣ�����ǰӦ�ã���Ĭ��Ϊ0��
     * @return array
     */
    function repost_weekly($count = 20, $base_app = 0)
    {
        $params = array();
        $params['count'] = intval($count);
        $params['base_app'] = intval($base_app);
        return $this->oauth->get('statuses/hot/repost_weekly', $params);
    }

    /**
     * ���췵������΢�����۰��΢���б�
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/statuses/hot/comments_daily statuses/hot/comments_daily}
     *
     * @param int $count
     *            ���صļ�¼������󲻳���50��Ĭ��Ϊ20��
     * @param int $base_app
     *            �Ƿ�ֻ��ȡ��ǰӦ�õ���ݡ�0Ϊ��������ݣ���1Ϊ�ǣ�����ǰӦ�ã���Ĭ��Ϊ0��
     * @return array
     */
    function comments_daily($count = 20, $base_app = 0)
    {
        $params = array();
        $params['count'] = intval($count);
        $params['base_app'] = intval($base_app);
        return $this->oauth->get('statuses/hot/comments_daily', $params);
    }

    /**
     * ���ܷ�������΢�����۰��΢���б�
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/statuses/hot/comments_weekly statuses/hot/comments_weekly}
     *
     * @param int $count
     *            ���صļ�¼������󲻳���50��Ĭ��Ϊ20��
     * @param int $base_app
     *            �Ƿ�ֻ��ȡ��ǰӦ�õ���ݡ�0Ϊ��������ݣ���1Ϊ�ǣ�����ǰӦ�ã���Ĭ��Ϊ0��
     * @return array
     */
    function comments_weekly($count = 20, $base_app = 0)
    {
        $params = array();
        $params['count'] = intval($count);
        $params['base_app'] = intval($base_app);
        return $this->oauth->get('statuses/hot/comments_weekly', $params);
    }

    /**
     * ת��һ��΢����Ϣ��
     *
     * �ɼ����ۡ�Ϊ��ֹ�ظ�����������Ϣ��������Ϣһ�����ᱻ���ԡ�
     * <br />��ӦAPI��{@link http://open.weibo.com/wiki/2/statuses/repost statuses/repost}
     *
     * @access public
     * @param int $sid
     *            ת����΢��ID
     * @param string $text
     *            ��ӵ�������Ϣ����ѡ��
     * @param int $is_comment
     *            �Ƿ���ת����ͬʱ�������ۣ�0����1�����۸�ǰ΢����2�����۸�ԭ΢����3�������ۣ�Ĭ��Ϊ0��
     * @return array
     */
    function repost($sid, $text = NULL, $is_comment = 0)
    {
        $this->id_format($sid);
        
        $params = array();
        $params['id'] = $sid;
        $params['is_comment'] = $is_comment;
        if ($text)
            $params['status'] = $text;
        
        return $this->oauth->post('statuses/repost', $params);
    }

    /**
     * ɾ��һ��΢��
     *
     * ���IDɾ��΢����Ϣ��ע�⣺ֻ��ɾ���Լ���������Ϣ��
     * <br />��ӦAPI��{@link http://open.weibo.com/wiki/2/statuses/destroy statuses/destroy}
     *
     * @access public
     * @param int $id
     *            Ҫɾ���΢��ID
     * @return array
     */
    function delete($id)
    {
        return $this->destroy($id);
    }

    /**
     * ɾ��һ��΢��
     *
     * ɾ��΢����ע�⣺ֻ��ɾ���Լ���������Ϣ��
     * <br />��ӦAPI��{@link http://open.weibo.com/wiki/2/statuses/destroy statuses/destroy}
     *
     * @access public
     * @param int $id
     *            Ҫɾ���΢��ID
     * @return array
     */
    function destroy($id)
    {
        $this->id_format($id);
        $params = array();
        $params['id'] = $id;
        return $this->oauth->post('statuses/destroy', $params);
    }

    /**
     * ����΢��
     *
     * ����һ��΢����Ϣ��
     * <br />ע�⣺lat��long���������ʹ�ã����ڱ�Ƿ���΢����Ϣʱ���ڵĵ���λ�ã�ֻ���û�������geo_enabled=trueʱ�����λ����Ϣ����Ч��
     * <br />ע�⣺Ϊ��ֹ�ظ��ύ�����û�������΢����Ϣ���ϴγɹ�������΢����Ϣ����һ��ʱ��������400���󣬸��������ʾ����40025:Error: repeated weibo text!����
     * <br />��ӦAPI��{@link http://open.weibo.com/wiki/2/statuses/update statuses/update}
     *
     * @access public
     * @param string $status
     *            Ҫ���µ�΢����Ϣ����Ϣ���ݲ�����140������, Ϊ�շ���400����
     * @param float $lat
     *            γ�ȣ����?ǰ΢�����ڵĵ���λ�ã���Ч��Χ -90.0��+90.0, +��ʾ��γ����ѡ��
     * @param float $long
     *            ���ȡ���Ч��Χ-180.0��+180.0, +��ʾ��������ѡ��
     * @param mixed $annotations
     *            ��ѡ����Ԫ��ݣ���Ҫ��Ϊ�˷������Ӧ�ü�¼һЩ�ʺ����Լ�ʹ�õ���Ϣ��ÿ��΢�����԰�һ�����߶��Ԫ��ݡ�����json�ִ�����ʽ�ύ���ִ����Ȳ�����512���ַ�������鷽ʽ��Ҫ��json_encode���ִ����Ȳ�����512���ַ�������ݿ����Զ������磺'[{"type2":123}, {"a":"b", "c":"d"}]'��array(array("type2"=>123), array("a"=>"b", "c"=>"d"))��
     * @return array
     */
    function update($status, $lat = NULL, $long = NULL, $annotations = NULL)
    {
        $params = array();
        $params['status'] = $status;
        if ($lat) {
            $params['lat'] = floatval($lat);
        }
        if ($long) {
            $params['long'] = floatval($long);
        }
        if (is_string($annotations)) {
            $params['annotations'] = $annotations;
        } elseif (is_array($annotations)) {
            $params['annotations'] = json_encode($annotations);
        }
        
        return $this->oauth->post('statuses/update', $params);
    }

    /**
     * ����ͼƬ΢��
     *
     * ����ͼƬ΢����Ϣ��Ŀǰ�ϴ�ͼƬ��С����Ϊ<5M��
     * <br />ע�⣺lat��long���������ʹ�ã����ڱ�Ƿ���΢����Ϣʱ���ڵĵ���λ�ã�ֻ���û�������geo_enabled=trueʱ�����λ����Ϣ����Ч��
     * <br />��ӦAPI��{@link http://open.weibo.com/wiki/2/statuses/upload statuses/upload}
     *
     * @access public
     * @param string $status
     *            Ҫ���µ�΢����Ϣ����Ϣ���ݲ�����140������, Ϊ�շ���400����
     * @param string $pic_path
     *            Ҫ������ͼƬ·��, ֧��url��[ֻ֧��png/jpg/gif���ָ�ʽ, ���Ӹ�ʽ���޸�get_image_mime����]
     * @param float $lat
     *            γ�ȣ����?ǰ΢�����ڵĵ���λ�ã���Ч��Χ -90.0��+90.0, +��ʾ��γ����ѡ��
     * @param float $long
     *            ��ѡ����ȡ���Ч��Χ-180.0��+180.0, +��ʾ��������ѡ��
     * @return array
     */
    function upload($status, $pic_path, $lat = NULL, $long = NULL)
    {
        $params = array();
        $params['status'] = $status;
        $params['pic'] = '@' . $pic_path;
        if ($lat) {
            $params['lat'] = floatval($lat);
        }
        if ($long) {
            $params['long'] = floatval($long);
        }
        
        return $this->oauth->post('statuses/upload', $params, true);
    }

    /**
     * ָ��һ��ͼƬURL��ַץȡ���ϴ���ͬʱ����һ����΢��
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/statuses/upload_url_text statuses/upload_url_text}
     *
     * @param string $status
     *            Ҫ������΢���ı����ݣ����ݲ�����140�����֡�
     * @param string $url
     *            ͼƬ��URL��ַ��������http��ͷ��
     * @return array
     */
    function upload_url_text($status, $url)
    {
        $params = array();
        $params['status'] = $status;
        $params['url'] = $url;
        return $this->oauth->post('statuses/upload', $params, true);
    }

    /**
     * ��ȡ�����б�
     *
     * ��������΢���ٷ����б��顢ħ������������Ϣ����������������͡�������࣬�Ƿ����ŵȡ�
     * <br />��ӦAPI��{@link http://open.weibo.com/wiki/2/emotions emotions}
     *
     * @access public
     * @param string $type
     *            �������"face":��ͨ���飬"ani"��ħ�����飬"cartoon"���������顣Ĭ��Ϊ"face"����ѡ��
     * @param string $language
     *            �������"cnname"���壬"twname"���塣Ĭ��Ϊ"cnname"����ѡ
     * @return array
     */
    function emotions($type = "face", $language = "cnname")
    {
        $params = array();
        $params['type'] = $type;
        $params['language'] = $language;
        return $this->oauth->get('emotions', $params);
    }

    /**
     * ���΢��ID����ĳ��΢���������б�
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/comments/show comments/show}
     *
     * @param int $sid
     *            ��Ҫ��ѯ��΢��ID��
     * @param int $page
     *            ���ؽ���ҳ�룬Ĭ��Ϊ1��
     * @param int $count
     *            ��ҳ���صļ�¼����Ĭ��Ϊ50��
     * @param int $since_id
     *            ��ָ���˲����򷵻�ID��since_id������ۣ�����since_idʱ��������ۣ���Ĭ��Ϊ0��
     * @param int $max_id
     *            ��ָ���˲����򷵻�IDС�ڻ����max_id�����ۣ�Ĭ��Ϊ0��
     * @param int $filter_by_author
     *            ����ɸѡ���ͣ�0��ȫ����1���ҹ�ע���ˡ�2��İ���ˣ�Ĭ��Ϊ0��
     * @return array
     */
    function get_comments_by_sid($sid, $page = 1, $count = 50, $since_id = 0, $max_id = 0, $filter_by_author = 0)
    {
        $params = array();
        $this->id_format($sid);
        $params['id'] = $sid;
        if ($since_id) {
            $this->id_format($since_id);
            $params['since_id'] = $since_id;
        }
        if ($max_id) {
            $this->id_format($max_id);
            $params['max_id'] = $max_id;
        }
        $params['count'] = $count;
        $params['page'] = $page;
        $params['filter_by_author'] = $filter_by_author;
        return $this->oauth->get('comments/show', $params);
    }

    /**
     * ��ȡ��ǰ��¼�û�����������б�
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/comments/by_me comments/by_me}
     *
     * @param int $since_id
     *            ��ָ���˲����򷵻�ID��since_id������ۣ�����since_idʱ��������ۣ���Ĭ��Ϊ0��
     * @param int $max_id
     *            ��ָ���˲����򷵻�IDС�ڻ����max_id�����ۣ�Ĭ��Ϊ0��
     * @param int $count
     *            ��ҳ���صļ�¼����Ĭ��Ϊ50��
     * @param int $page
     *            ���ؽ���ҳ�룬Ĭ��Ϊ1��
     * @param int $filter_by_source
     *            ��Դɸѡ���ͣ�0��ȫ����1������΢�������ۡ�2������΢Ⱥ�����ۣ�Ĭ��Ϊ0��
     * @return array
     */
    function comments_by_me($page = 1, $count = 50, $since_id = 0, $max_id = 0, $filter_by_source = 0)
    {
        $params = array();
        if ($since_id) {
            $this->id_format($since_id);
            $params['since_id'] = $since_id;
        }
        if ($max_id) {
            $this->id_format($max_id);
            $params['max_id'] = $max_id;
        }
        $params['count'] = $count;
        $params['page'] = $page;
        $params['filter_by_source'] = $filter_by_source;
        return $this->oauth->get('comments/by_me', $params);
    }

    /**
     * ��ȡ��ǰ��¼�û�����յ��������б�
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/comments/to_me comments/to_me}
     *
     * @param int $since_id
     *            ��ָ���˲����򷵻�ID��since_id������ۣ�����since_idʱ��������ۣ���Ĭ��Ϊ0��
     * @param int $max_id
     *            ��ָ���˲����򷵻�IDС�ڻ����max_id�����ۣ�Ĭ��Ϊ0��
     * @param int $count
     *            ��ҳ���صļ�¼����Ĭ��Ϊ50��
     * @param int $page
     *            ���ؽ���ҳ�룬Ĭ��Ϊ1��
     * @param int $filter_by_author
     *            ����ɸѡ���ͣ�0��ȫ����1���ҹ�ע���ˡ�2��İ���ˣ�Ĭ��Ϊ0��
     * @param int $filter_by_source
     *            ��Դɸѡ���ͣ�0��ȫ����1������΢�������ۡ�2������΢Ⱥ�����ۣ�Ĭ��Ϊ0��
     * @return array
     */
    function comments_to_me($page = 1, $count = 50, $since_id = 0, $max_id = 0, $filter_by_author = 0, $filter_by_source = 0)
    {
        $params = array();
        if ($since_id) {
            $this->id_format($since_id);
            $params['since_id'] = $since_id;
        }
        if ($max_id) {
            $this->id_format($max_id);
            $params['max_id'] = $max_id;
        }
        $params['count'] = $count;
        $params['page'] = $page;
        $params['filter_by_author'] = $filter_by_author;
        $params['filter_by_source'] = $filter_by_source;
        return $this->oauth->get('comments/to_me', $params);
    }

    /**
     * ��������(��ʱ��)
     *
     * ��������n�����ͼ��յ������ۡ�
     * <br />��ӦAPI��{@link http://open.weibo.com/wiki/2/comments/timeline comments/timeline}
     *
     * @access public
     * @param int $page
     *            ҳ��
     * @param int $count
     *            ÿ�η��ص�����¼����෵��200����Ĭ��50��
     * @param int $since_id
     *            ��ָ���˲�����ֻ����ID��since_id������ۣ���since_id����ʱ���?����ѡ��
     * @param int $max_id
     *            ��ָ���˲����򷵻�IDС�ڻ����max_id�����ۡ���ѡ��
     * @return array
     */
    function comments_timeline($page = 1, $count = 50, $since_id = 0, $max_id = 0)
    {
        $params = array();
        if ($since_id) {
            $this->id_format($since_id);
            $params['since_id'] = $since_id;
        }
        if ($max_id) {
            $this->id_format($max_id);
            $params['max_id'] = $max_id;
        }
        
        return $this->request_with_pager('comments/timeline', $page, $count, $params);
    }

    /**
     * ��ȡ���µ��ᵽ��ǰ��¼�û������ۣ���@�ҵ�����
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/comments/mentions comments/mentions}
     *
     * @param int $since_id
     *            ��ָ���˲����򷵻�ID��since_id������ۣ�����since_idʱ��������ۣ���Ĭ��Ϊ0��
     * @param int $max_id
     *            ��ָ���˲����򷵻�IDС�ڻ����max_id�����ۣ�Ĭ��Ϊ0��
     * @param int $count
     *            ��ҳ���صļ�¼����Ĭ��Ϊ50��
     * @param int $page
     *            ���ؽ���ҳ�룬Ĭ��Ϊ1��
     * @param int $filter_by_author
     *            ����ɸѡ���ͣ�0��ȫ����1���ҹ�ע���ˡ�2��İ���ˣ�Ĭ��Ϊ0��
     * @param int $filter_by_source
     *            ��Դɸѡ���ͣ�0��ȫ����1������΢�������ۡ�2������΢Ⱥ�����ۣ�Ĭ��Ϊ0��
     * @return array
     */
    function comments_mentions($page = 1, $count = 50, $since_id = 0, $max_id = 0, $filter_by_author = 0, $filter_by_source = 0)
    {
        $params = array();
        $params['since_id'] = $since_id;
        $params['max_id'] = $max_id;
        $params['count'] = $count;
        $params['page'] = $page;
        $params['filter_by_author'] = $filter_by_author;
        $params['filter_by_source'] = $filter_by_source;
        return $this->oauth->get('comments/mentions', $params);
    }

    /**
     * �������ID��������������Ϣ
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/comments/show_batch comments/show_batch}
     *
     * @param string $cids
     *            ��Ҫ��ѯ����������ID���ð�Ƕ��ŷָ������50
     * @return array
     */
    function comments_show_batch($cids)
    {
        $params = array();
        if (is_array($cids) && ! empty($cids)) {
            foreach ($cids as $k => $v) {
                $this->id_format($cids[$k]);
            }
            $params['cids'] = join(',', $cids);
        } else {
            $params['cids'] = $cids;
        }
        return $this->oauth->get('comments/show_batch', $params);
    }

    /**
     * ��һ��΢����������
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/comments/create comments/create}
     *
     * @param string $comment
     *            �������ݣ����ݲ�����140�����֡�
     * @param int $id
     *            ��Ҫ���۵�΢��ID��
     * @param int $comment_ori
     *            ������ת��΢��ʱ���Ƿ����۸�ԭ΢����0����1���ǣ�Ĭ��Ϊ0��
     * @return array
     */
    function send_comment($id, $comment, $comment_ori = 0)
    {
        $params = array();
        $params['comment'] = $comment;
        $this->id_format($id);
        $params['id'] = $id;
        $params['comment_ori'] = $comment_ori;
        return $this->oauth->post('comments/create', $params);
    }

    /**
     * ɾ��ǰ�û���΢��������Ϣ��
     *
     * ע�⣺ֻ��ɾ���Լ����������ۣ�����΢�����û�������ɾ�������˵����ۡ�
     * <br />��ӦAPI��{@link http://open.weibo.com/wiki/2/statuses/comment_destroy statuses/comment_destroy}
     *
     * @access public
     * @param int $cid
     *            Ҫɾ�������id
     * @return array
     */
    function comment_destroy($cid)
    {
        $params = array();
        $params['cid'] = $cid;
        return $this->oauth->post('comments/destroy', $params);
    }

    /**
     * �������ID����ɾ������
     *
     * ע�⣺ֻ��ɾ���Լ����������ۣ�����΢�����û�������ɾ�������˵����ۡ�
     * <br />��ӦAPI��{@link http://open.weibo.com/wiki/2/comments/destroy_batch comments/destroy_batch}
     *
     * @access public
     * @param string $ids
     *            ��Ҫɾ�������ID���ð�Ƕ��Ÿ��������20����
     * @return array
     */
    function comment_destroy_batch($ids)
    {
        $params = array();
        if (is_array($ids) && ! empty($ids)) {
            foreach ($ids as $k => $v) {
                $this->id_format($ids[$k]);
            }
            $params['cids'] = join(',', $ids);
        } else {
            $params['cids'] = $ids;
        }
        return $this->oauth->post('comments/destroy_batch', $params);
    }

    /**
     * �ظ�һ������
     *
     * Ϊ��ֹ�ظ�����������Ϣ�����һ������/�ظ���Ϣһ�����ᱻ���ԡ�
     * <br />��ӦAPI��{@link http://open.weibo.com/wiki/2/comments/reply comments/reply}
     *
     * @access public
     * @param int $sid
     *            ΢��id
     * @param string $text
     *            �������ݡ�
     * @param int $cid
     *            ����id
     * @param int $without_mention
     *            1���ظ��в��Զ����롰�ظ�@�û���0���ظ����Զ����롰�ظ�@�û���.Ĭ��Ϊ0.
     * @param int $comment_ori
     *            ������ת��΢��ʱ���Ƿ����۸�ԭ΢����0����1���ǣ�Ĭ��Ϊ0��
     * @return array
     */
    function reply($sid, $text, $cid, $without_mention = 0, $comment_ori = 0)
    {
        $this->id_format($sid);
        $this->id_format($cid);
        $params = array();
        $params['id'] = $sid;
        $params['comment'] = $text;
        $params['cid'] = $cid;
        $params['without_mention'] = $without_mention;
        $params['comment_ori'] = $comment_ori;
        
        return $this->oauth->post('comments/reply', $params);
    }

    /**
     * ����û�UID���ǳƻ�ȡ�û�����
     *
     * ���û�UID���ǳƷ����û����ϣ�ͬʱҲ�������û������·�����΢����
     * <br />��ӦAPI��{@link http://open.weibo.com/wiki/2/users/show users/show}
     *
     * @access public
     * @param int $uid
     *            �û�UID��
     * @return array
     */
    function show_user_by_id($uid)
    {
        $params = array();
        if ($uid !== NULL) {
            $this->id_format($uid);
            $params['uid'] = $uid;
        }
        
        return $this->oauth->get('users/show', $params);
    }

    /**
     * ����û�UID���ǳƻ�ȡ�û�����
     *
     * ���û�UID���ǳƷ����û����ϣ�ͬʱҲ�������û������·�����΢����
     * <br />��ӦAPI��{@link http://open.weibo.com/wiki/2/users/show users/show}
     *
     * @access public
     * @param string $screen_name
     *            �û�UID��
     * @return array
     */
    function show_user_by_name($screen_name)
    {
        $params = array();
        $params['screen_name'] = $screen_name;
        
        return $this->oauth->get('users/show', $params);
    }

    /**
     * ͨ����Ի������ȡ�û������Լ��û����µ�һ��΢��
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/users/domain_show users/domain_show}
     *
     * @access public
     * @param mixed $domain
     *            �û������������磺lazypeople������http://weibo.com/lazypeople
     * @return array
     */
    function domain_show($domain)
    {
        $params = array();
        $params['domain'] = $domain;
        return $this->oauth->get('users/domain_show', $params);
    }

    /**
     * ������ȡ�û���Ϣ��uids
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/users/show_batch users/show_batch}
     *
     * @param string $uids
     *            ��Ҫ��ѯ���û�ID���ð�Ƕ��ŷָ���һ�����20����
     * @return array
     */
    function users_show_batch_by_id($uids)
    {
        $params = array();
        if (is_array($uids) && ! empty($uids)) {
            foreach ($uids as $k => $v) {
                $this->id_format($uids[$k]);
            }
            $params['uids'] = join(',', $uids);
        } else {
            $params['uids'] = $uids;
        }
        return $this->oauth->get('users/show_batch', $params);
    }

    /**
     * ������ȡ�û���Ϣ��screen_name
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/users/show_batch users/show_batch}
     *
     * @param string $screen_name
     *            ��Ҫ��ѯ���û��ǳƣ��ð�Ƕ��ŷָ���һ�����20����
     * @return array
     */
    function users_show_batch_by_name($screen_name)
    {
        $params = array();
        if (is_array($screen_name) && ! empty($screen_name)) {
            $params['screen_name'] = join(',', $screen_name);
        } else {
            $params['screen_name'] = $screen_name;
        }
        return $this->oauth->get('users/show_batch', $params);
    }

    /**
     * ��ȡ�û��Ĺ�ע�б�
     *
     * ���û���ṩcursor����ֻ������ǰ���5000����עid
     * <br />��ӦAPI��{@link http://open.weibo.com/wiki/2/friendships/friends friendships/friends}
     *
     * @access public
     * @param int $cursor
     *            ���ؽ����α꣬��һҳ�÷���ֵ���next_cursor����һҳ��previous_cursor��Ĭ��Ϊ0��
     * @param int $count
     *            ��ҳ���صļ�¼����Ĭ��Ϊ50����󲻳���200��
     * @param int $uid
     *            Ҫ��ȡ���û���ID��
     * @return array
     */
    function friends_by_id($uid, $cursor = 0, $count = 50)
    {
        $params = array();
        $params['cursor'] = $cursor;
        $params['count'] = $count;
        $params['uid'] = $uid;
        
        return $this->oauth->get('friendships/friends', $params);
    }

    /**
     * ��ȡ�û��Ĺ�ע�б�
     *
     * ���û���ṩcursor����ֻ������ǰ���5000����עid
     * <br />��ӦAPI��{@link http://open.weibo.com/wiki/2/friendships/friends friendships/friends}
     *
     * @access public
     * @param int $cursor
     *            ���ؽ����α꣬��һҳ�÷���ֵ���next_cursor����һҳ��previous_cursor��Ĭ��Ϊ0��
     * @param int $count
     *            ��ҳ���صļ�¼����Ĭ��Ϊ50����󲻳���200��
     * @param string $screen_name
     *            Ҫ��ȡ���û��� screen_name
     * @return array
     */
    function friends_by_name($screen_name, $cursor = 0, $count = 50)
    {
        $params = array();
        $params['cursor'] = $cursor;
        $params['count'] = $count;
        $params['screen_name'] = $screen_name;
        return $this->oauth->get('friendships/friends', $params);
    }

    /**
     * ��ȡ�����û�֮��Ĺ�ͬ��ע���б�
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/friendships/friends/in_common friendships/friends/in_common}
     *
     * @param int $uid
     *            ��Ҫ��ȡ��ͬ��ע��ϵ���û�UID
     * @param int $suid
     *            ��Ҫ��ȡ��ͬ��ע��ϵ���û�UID��Ĭ��Ϊ��ǰ��¼�û���
     * @param int $count
     *            ��ҳ���صļ�¼����Ĭ��Ϊ50��
     * @param int $page
     *            ���ؽ���ҳ�룬Ĭ��Ϊ1��
     * @return array
     */
    function friends_in_common($uid, $suid = NULL, $page = 1, $count = 50)
    {
        $params = array();
        $params['uid'] = $uid;
        $params['suid'] = $suid;
        $params['count'] = $count;
        $params['page'] = $page;
        return $this->oauth->get('friendships/friends/in_common', $params);
    }

    /**
     * ��ȡ�û���˫���ע�б?�������б�
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/friendships/friends/bilateral friendships/friends/bilateral}
     *
     * @param int $uid
     *            ��Ҫ��ȡ˫���ע�б���û�UID��
     * @param int $count
     *            ��ҳ���صļ�¼����Ĭ��Ϊ50��
     * @param int $page
     *            ���ؽ���ҳ�룬Ĭ��Ϊ1��
     * @param int $sort
     *            �������ͣ�0������עʱ���������Ĭ��Ϊ0��
     * @return array
     *
     */
    function bilateral($uid, $page = 1, $count = 50, $sort = 0)
    {
        $params = array();
        $params['uid'] = $uid;
        $params['count'] = $count;
        $params['page'] = $page;
        $params['sort'] = $sort;
        return $this->oauth->get('friendships/friends/bilateral', $params);
    }

    /**
     * ��ȡ�û���˫���עuid�б�
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/friendships/friends/bilateral/ids friendships/friends/bilateral/ids}
     *
     * @param int $uid
     *            ��Ҫ��ȡ˫���ע�б���û�UID��
     * @param int $count
     *            ��ҳ���صļ�¼����Ĭ��Ϊ50��
     * @param int $page
     *            ���ؽ���ҳ�룬Ĭ��Ϊ1��
     * @param int $sort
     *            �������ͣ�0������עʱ���������Ĭ��Ϊ0��
     * @return array
     *
     */
    function bilateral_ids($uid, $page = 1, $count = 50, $sort = 0)
    {
        $params = array();
        $params['uid'] = $uid;
        $params['count'] = $count;
        $params['page'] = $page;
        $params['sort'] = $sort;
        return $this->oauth->get('friendships/friends/bilateral/ids', $params);
    }

    /**
     * ��ȡ�û��Ĺ�ע�б�uid
     *
     * ���û���ṩcursor����ֻ������ǰ���5000����עid
     * <br />��ӦAPI��{@link http://open.weibo.com/wiki/2/friendships/friends/ids friendships/friends/ids}
     *
     * @access public
     * @param int $cursor
     *            ���ؽ����α꣬��һҳ�÷���ֵ���next_cursor����һҳ��previous_cursor��Ĭ��Ϊ0��
     * @param int $count
     *            ÿ�η��ص�����¼��ҳ���С����������5000, Ĭ�Ϸ���500��
     * @param int $uid
     *            Ҫ��ȡ���û� UID��Ĭ��Ϊ��ǰ�û�
     * @return array
     */
    function friends_ids_by_id($uid, $cursor = 0, $count = 500)
    {
        $params = array();
        $this->id_format($uid);
        $params['uid'] = $uid;
        $params['cursor'] = $cursor;
        $params['count'] = $count;
        return $this->oauth->get('friendships/friends/ids', $params);
    }

    /**
     * ��ȡ�û��Ĺ�ע�б�uid
     *
     * ���û���ṩcursor����ֻ������ǰ���5000����עid
     * <br />��ӦAPI��{@link http://open.weibo.com/wiki/2/friendships/friends/ids friendships/friends/ids}
     *
     * @access public
     * @param int $cursor
     *            ���ؽ����α꣬��һҳ�÷���ֵ���next_cursor����һҳ��previous_cursor��Ĭ��Ϊ0��
     * @param int $count
     *            ÿ�η��ص�����¼��ҳ���С����������5000, Ĭ�Ϸ���500��
     * @param string $screen_name
     *            Ҫ��ȡ���û��� screen_name��Ĭ��Ϊ��ǰ�û�
     * @return array
     */
    function friends_ids_by_name($screen_name, $cursor = 0, $count = 500)
    {
        $params = array();
        $params['cursor'] = $cursor;
        $params['count'] = $count;
        $params['screen_name'] = $screen_name;
        return $this->oauth->get('friendships/friends/ids', $params);
    }

    /**
     * ������ȡ��ǰ��¼�û��Ĺ�ע�˵ı�ע��Ϣ
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/friendships/friends/remark_batch friendships/friends/remark_batch}
     *
     * @param string $uids
     *            ��Ҫ��ȡ��ע���û�UID���ð�Ƕ��ŷָ�����಻����50����
     * @return array
     *
     */
    function friends_remark_batch($uids)
    {
        $params = array();
        if (is_array($uids) && ! empty($uids)) {
            foreach ($uids as $k => $v) {
                $this->id_format($uids[$k]);
            }
            $params['uids'] = join(',', $uids);
        } else {
            $params['uids'] = $uids;
        }
        return $this->oauth->get('friendships/friends/remark_batch', $params);
    }

    /**
     * ��ȡ�û��ķ�˿�б�
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/friendships/followers friendships/followers}
     *
     * @param int $uid
     *            ��Ҫ��ѯ���û�UID
     * @param int $count
     *            ��ҳ���صļ�¼����Ĭ��Ϊ50����󲻳���200��
     * @param int $cursor
     *            false ���ؽ����α꣬��һҳ�÷���ֵ���next_cursor����һҳ��previous_cursor��Ĭ��Ϊ0��
     * @return array
     *
     */
    function followers_by_id($uid, $cursor = 0, $count = 50)
    {
        $params = array();
        $this->id_format($uid);
        $params['uid'] = $uid;
        $params['count'] = $count;
        $params['cursor'] = $cursor;
        return $this->oauth->get('friendships/followers', $params);
    }

    /**
     * ��ȡ�û��ķ�˿�б�
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/friendships/followers friendships/followers}
     *
     * @param string $screen_name
     *            ��Ҫ��ѯ���û����ǳ�
     * @param int $count
     *            ��ҳ���صļ�¼����Ĭ��Ϊ50����󲻳���200��
     * @param int $cursor
     *            false ���ؽ����α꣬��һҳ�÷���ֵ���next_cursor����һҳ��previous_cursor��Ĭ��Ϊ0��
     * @return array
     *
     */
    function followers_by_name($screen_name, $cursor = 0, $count = 50)
    {
        $params = array();
        $params['screen_name'] = $screen_name;
        $params['count'] = $count;
        $params['cursor'] = $cursor;
        return $this->oauth->get('friendships/followers', $params);
    }

    /**
     * ��ȡ�û��ķ�˿�б�uid
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/friendships/followers friendships/followers}
     *
     * @param int $uid
     *            ��Ҫ��ѯ���û�UID
     * @param int $count
     *            ��ҳ���صļ�¼����Ĭ��Ϊ50����󲻳���200��
     * @param int $cursor
     *            ���ؽ����α꣬��һҳ�÷���ֵ���next_cursor����һҳ��previous_cursor��Ĭ��Ϊ0��
     * @return array
     *
     */
    function followers_ids_by_id($uid, $cursor = 0, $count = 50)
    {
        $params = array();
        $this->id_format($uid);
        $params['uid'] = $uid;
        $params['count'] = $count;
        $params['cursor'] = $cursor;
        return $this->oauth->get('friendships/followers/ids', $params);
    }

    /**
     * ��ȡ�û��ķ�˿�б�uid
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/friendships/followers friendships/followers}
     *
     * @param string $screen_name
     *            ��Ҫ��ѯ���û�screen_name
     * @param int $count
     *            ��ҳ���صļ�¼����Ĭ��Ϊ50����󲻳���200��
     * @param int $cursor
     *            ���ؽ����α꣬��һҳ�÷���ֵ���next_cursor����һҳ��previous_cursor��Ĭ��Ϊ0��
     * @return array
     *
     */
    function followers_ids_by_name($screen_name, $cursor = 0, $count = 50)
    {
        $params = array();
        $params['screen_name'] = $screen_name;
        $params['count'] = $count;
        $params['cursor'] = $cursor;
        return $this->oauth->get('friendships/followers/ids', $params);
    }

    /**
     * ��ȡ���ʷ�˿
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/friendships/followers/active friendships/followers/active}
     *
     * @param int $uid
     *            ��Ҫ��ѯ���û�UID��
     * @param int $count
     *            ���صļ�¼����Ĭ��Ϊ20����󲻳���200��
     * @return array
     *
     */
    function followers_active($uid, $count = 20)
    {
        $param = array();
        $this->id_format($uid);
        $param['uid'] = $uid;
        $param['count'] = $count;
        return $this->oauth->get('friendships/followers/active', $param);
    }

    /**
     * ��ȡ��ǰ��¼�û��Ĺ�ע�����ֹ�ע��ָ���û����û��б�
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/friendships/friends_chain/followers friendships/friends_chain/followers}
     *
     * @param int $uid
     *            ָ���Ĺ�עĿ���û�UID��
     * @param int $count
     *            ��ҳ���صļ�¼����Ĭ��Ϊ50��
     * @param int $page
     *            ���ؽ���ҳ�룬Ĭ��Ϊ1��
     * @return array
     *
     */
    function friends_chain_followers($uid, $page = 1, $count = 50)
    {
        $params = array();
        $this->id_format($uid);
        $params['uid'] = $uid;
        $params['count'] = $count;
        $params['page'] = $page;
        return $this->oauth->get('friendships/friends_chain/followers', $params);
    }

    /**
     * ���������û���ϵ����ϸ���
     *
     * ���Դ�û���Ŀ���û������ڣ�������http��400����
     * <br />��ӦAPI��{@link http://open.weibo.com/wiki/2/friendships/show friendships/show}
     *
     * @access public
     * @param mixed $target_id
     *            Ŀ���û�UID
     * @param mixed $source_id
     *            Դ�û�UID����ѡ��Ĭ��Ϊ��ǰ���û�
     * @return array
     */
    function is_followed_by_id($target_id, $source_id = NULL)
    {
        $params = array();
        $this->id_format($target_id);
        $params['target_id'] = $target_id;
        
        if ($source_id != NULL) {
            $this->id_format($source_id);
            $params['source_id'] = $source_id;
        }
        
        return $this->oauth->get('friendships/show', $params);
    }

    /**
     * ���������û���ϵ����ϸ���
     *
     * ���Դ�û���Ŀ���û������ڣ�������http��400����
     * <br />��ӦAPI��{@link http://open.weibo.com/wiki/2/friendships/show friendships/show}
     *
     * @access public
     * @param mixed $target_name
     *            Ŀ���û���΢���ǳ�
     * @param mixed $source_name
     *            Դ�û���΢���ǳƣ���ѡ��Ĭ��Ϊ��ǰ���û�
     * @return array
     */
    function is_followed_by_name($target_name, $source_name = NULL)
    {
        $params = array();
        $params['target_screen_name'] = $target_name;
        
        if ($source_name != NULL) {
            $params['source_screen_name'] = $source_name;
        }
        
        return $this->oauth->get('friendships/show', $params);
    }

    /**
     * ��עһ���û���
     *
     * �ɹ��򷵻ع�ע�˵����ϣ�Ŀǰ����ע2000�ˣ�ʧ���򷵻�һ���ַ��˵��������Ѿ���ע�˴��ˣ��򷵻�http 403��״̬����ע�����ڵ�ID������400��
     * <br />��ӦAPI��{@link http://open.weibo.com/wiki/2/friendships/create friendships/create}
     *
     * @access public
     * @param int $uid
     *            Ҫ��ע���û�UID
     * @return array
     */
    function follow_by_id($uid)
    {
        $params = array();
        $this->id_format($uid);
        $params['uid'] = $uid;
        return $this->oauth->post('friendships/create', $params);
    }

    /**
     * ��עһ���û���
     *
     * �ɹ��򷵻ع�ע�˵����ϣ�Ŀǰ������ע2000�ˣ�ʧ���򷵻�һ���ַ��˵��������Ѿ���ע�˴��ˣ��򷵻�http 403��״̬����ע�����ڵ�ID������400��
     * <br />��ӦAPI��{@link http://open.weibo.com/wiki/2/friendships/create friendships/create}
     *
     * @access public
     * @param string $screen_name
     *            Ҫ��ע���û��ǳ�
     * @return array
     */
    function follow_by_name($screen_name)
    {
        $params = array();
        $params['screen_name'] = $screen_name;
        return $this->oauth->post('friendships/create', $params);
    }

    /**
     * ����û�UID������ע�û�
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/friendships/create_batch friendships/create_batch}
     *
     * @param string $uids
     *            Ҫ��ע���û�UID���ð�Ƕ��ŷָ�����಻����20����
     * @return array
     */
    function follow_create_batch($uids)
    {
        $params = array();
        if (is_array($uids) && ! empty($uids)) {
            foreach ($uids as $k => $v) {
                $this->id_format($uids[$k]);
            }
            $params['uids'] = join(',', $uids);
        } else {
            $params['uids'] = $uids;
        }
        return $this->oauth->post('friendships/create_batch', $params);
    }

    /**
     * ȡ���עĳ�û�
     *
     * ȡ���עĳ�û����ɹ��򷵻ر�ȡ���ע�˵����ϣ�ʧ���򷵻�һ���ַ��˵����
     * <br />��ӦAPI��{@link http://open.weibo.com/wiki/2/friendships/destroy friendships/destroy}
     *
     * @access public
     * @param int $uid
     *            Ҫȡ���ע���û�UID
     * @return array
     */
    function unfollow_by_id($uid)
    {
        $params = array();
        $this->id_format($uid);
        $params['uid'] = $uid;
        return $this->oauth->post('friendships/destroy', $params);
    }

    /**
     * ȡ���עĳ�û�
     *
     * ȡ���עĳ�û����ɹ��򷵻ر�ȡ���ע�˵����ϣ�ʧ���򷵻�һ���ַ��˵����
     * <br />��ӦAPI��{@link http://open.weibo.com/wiki/2/friendships/destroy friendships/destroy}
     *
     * @access public
     * @param string $screen_name
     *            Ҫȡ���ע���û��ǳ�
     * @return array
     */
    function unfollow_by_name($screen_name)
    {
        $params = array();
        $params['screen_name'] = $screen_name;
        return $this->oauth->post('friendships/destroy', $params);
    }

    /**
     * ���µ�ǰ��¼�û����ע��ĳ�����ѵı�ע��Ϣ
     *
     * ֻ���޸ĵ�ǰ��¼�û����ע���û��ı�ע��Ϣ�����򽫸��400����
     * <br />��ӦAPI��{@link http://open.weibo.com/wiki/2/friendships/remark/update friendships/remark/update}
     *
     * @access public
     * @param int $uid
     *            ��Ҫ�޸ı�ע��Ϣ���û�ID��
     * @param string $remark
     *            ��ע��Ϣ��
     * @return array
     */
    function update_remark($uid, $remark)
    {
        $params = array();
        $this->id_format($uid);
        $params['uid'] = $uid;
        $params['remark'] = $remark;
        return $this->oauth->post('friendships/remark/update', $params);
    }

    /**
     * ��ȡ��ǰ�û�����˽���б�
     *
     * �����û�������n��˽�ţ��������ߺͽ����ߵ���ϸ���ϡ�
     * <br />��ӦAPI��{@link http://open.weibo.com/wiki/2/direct_messages direct_messages}
     *
     * @access public
     * @param int $page
     *            ҳ��
     * @param int $count
     *            ÿ�η��ص�����¼����෵��200����Ĭ��50��
     * @param int64 $since_id
     *            ����ID����ֵsince_id�󣨱�since_idʱ����ģ���˽�š���ѡ��
     * @param int64 $max_id
     *            ����ID������max_id(ʱ�䲻����max_id)��˽�š���ѡ��
     * @return array
     */
    function list_dm($page = 1, $count = 50, $since_id = 0, $max_id = 0)
    {
        $params = array();
        if ($since_id) {
            $this->id_format($since_id);
            $params['since_id'] = $since_id;
        }
        if ($max_id) {
            $this->id_format($max_id);
            $params['max_id'] = $max_id;
        }
        
        return $this->request_with_pager('direct_messages', $page, $count, $params);
    }

    /**
     * ��ȡ��ǰ�û����͵�����˽���б�
     *
     * ���ص�¼�û��ѷ�������50��˽�š����������ߺͽ����ߵ���ϸ���ϡ�
     * <br />��ӦAPI��{@link http://open.weibo.com/wiki/2/direct_messages/sent direct_messages/sent}
     *
     * @access public
     * @param int $page
     *            ҳ��
     * @param int $count
     *            ÿ�η��ص�����¼����෵��200����Ĭ��50��
     * @param int64 $since_id
     *            ����ID����ֵsince_id�󣨱�since_idʱ����ģ���˽�š���ѡ��
     * @param int64 $max_id
     *            ����ID������max_id(ʱ�䲻����max_id)��˽�š���ѡ��
     * @return array
     */
    function list_dm_sent($page = 1, $count = 50, $since_id = 0, $max_id = 0)
    {
        $params = array();
        if ($since_id) {
            $this->id_format($since_id);
            $params['since_id'] = $since_id;
        }
        if ($max_id) {
            $this->id_format($max_id);
            $params['max_id'] = $max_id;
        }
        
        return $this->request_with_pager('direct_messages/sent', $page, $count, $params);
    }

    /**
     * ��ȡ�뵱ǰ��¼�û���˽���������û��б?����û�����������˽��
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/direct_messages/user_list direct_messages/user_list}
     *
     * @param int $count
     *            ��ҳ���صļ�¼����Ĭ��Ϊ20��
     * @param int $cursor
     *            ���ؽ����α꣬��һҳ�÷���ֵ���next_cursor����һҳ��previous_cursor��Ĭ��Ϊ0��
     * @return array
     */
    function dm_user_list($count = 20, $cursor = 0)
    {
        $params = array();
        $params['count'] = $count;
        $params['cursor'] = $cursor;
        return $this->oauth->get('direct_messages/user_list', $params);
    }

    /**
     * ��ȡ��ָ���û�������˽���б�
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/direct_messages/conversation direct_messages/conversation}
     *
     * @param int $uid
     *            ��Ҫ��ѯ���û���UID��
     * @param int $since_id
     *            ��ָ���˲����򷵻�ID��since_id���˽�ţ�����since_idʱ�����˽�ţ���Ĭ��Ϊ0��
     * @param int $max_id
     *            ��ָ���˲����򷵻�IDС�ڻ����max_id��˽�ţ�Ĭ��Ϊ0��
     * @param int $count
     *            ��ҳ���صļ�¼����Ĭ��Ϊ50��
     * @param int $page
     *            ���ؽ���ҳ�룬Ĭ��Ϊ1��
     * @return array
     */
    function dm_conversation($uid, $page = 1, $count = 50, $since_id = 0, $max_id = 0)
    {
        $params = array();
        $this->id_format($uid);
        $params['uid'] = $uid;
        if ($since_id) {
            $this->id_format($since_id);
            $params['since_id'] = $since_id;
        }
        if ($max_id) {
            $this->id_format($max_id);
            $params['max_id'] = $max_id;
        }
        $params['count'] = $count;
        $params['page'] = $page;
        return $this->oauth->get('direct_messages/conversation', $params);
    }

    /**
     * ���˽��ID������ȡ˽������
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/direct_messages/show_batch direct_messages/show_batch}
     *
     * @param string $dmids
     *            ��Ҫ��ѯ��˽��ID���ð�Ƕ��ŷָ���һ�����50��
     * @return array
     */
    function dm_show_batch($dmids)
    {
        $params = array();
        if (is_array($dmids) && ! empty($dmids)) {
            foreach ($dmids as $k => $v) {
                $this->id_format($dmids[$k]);
            }
            $params['dmids'] = join(',', $dmids);
        } else {
            $params['dmids'] = $dmids;
        }
        return $this->oauth->get('direct_messages/show_batch', $params);
    }

    /**
     * ����˽��
     *
     * ����һ��˽�š��ɹ�����������ķ�����Ϣ��
     * <br />��ӦAPI��{@link http://open.weibo.com/wiki/2/direct_messages/new direct_messages/new}
     *
     * @access public
     * @param int $uid
     *            �û�UID
     * @param string $text
     *            Ҫ�������Ϣ���ݣ��ı���С����С��300�����֡�
     * @param int $id
     *            ��Ҫ���͵�΢��ID��
     * @return array
     */
    function send_dm_by_id($uid, $text, $id = NULL)
    {
        $params = array();
        $this->id_format($uid);
        $params['text'] = $text;
        $params['uid'] = $uid;
        if ($id) {
            $this->id_format($id);
            $params['id'] = $id;
        }
        return $this->oauth->post('direct_messages/new', $params);
    }

    /**
     * ����˽��
     *
     * ����һ��˽�š��ɹ�����������ķ�����Ϣ��
     * <br />��ӦAPI��{@link http://open.weibo.com/wiki/2/direct_messages/new direct_messages/new}
     *
     * @access public
     * @param string $screen_name
     *            �û��ǳ�
     * @param string $text
     *            Ҫ�������Ϣ���ݣ��ı���С����С��300�����֡�
     * @param int $id
     *            ��Ҫ���͵�΢��ID��
     * @return array
     */
    function send_dm_by_name($screen_name, $text, $id = NULL)
    {
        $params = array();
        $params['text'] = $text;
        $params['screen_name'] = $screen_name;
        if ($id) {
            $this->id_format($id);
            $params['id'] = $id;
        }
        return $this->oauth->post('direct_messages/new', $params);
    }

    /**
     * ɾ��һ��˽��
     *
     * ��IDɾ��˽�š������û�����Ϊ˽�ŵĽ����ˡ�
     * <br />��ӦAPI��{@link http://open.weibo.com/wiki/2/direct_messages/destroy direct_messages/destroy}
     *
     * @access public
     * @param int $did
     *            Ҫɾ���˽������ID
     * @return array
     */
    function delete_dm($did)
    {
        $this->id_format($did);
        $params = array();
        $params['id'] = $did;
        return $this->oauth->post('direct_messages/destroy', $params);
    }

    /**
     * ����ɾ��˽��
     *
     * ����ɾ��ǰ��¼�û���˽�š������쳣ʱ������400����
     * <br />��ӦAPI��{@link http://open.weibo.com/wiki/2/direct_messages/destroy_batch direct_messages/destroy_batch}
     *
     * @access public
     * @param mixed $dids
     *            ��ɾ���һ��˽��ID���ð�Ƕ��Ÿ�����������һ������ID��ɵ����顣���20�������磺"4976494627, 4976262053"��array(4976494627,4976262053);
     * @return array
     */
    function delete_dms($dids)
    {
        $params = array();
        if (is_array($dids) && ! empty($dids)) {
            foreach ($dids as $k => $v) {
                $this->id_format($dids[$k]);
            }
            $params['ids'] = join(',', $dids);
        } else {
            $params['ids'] = $dids;
        }
        
        return $this->oauth->post('direct_messages/destroy_batch', $params);
    }

    /**
     * ��ȡ�û�����Ϣ
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/account/profile/basic account/profile/basic}
     *
     * @param int $uid
     *            ��Ҫ��ȡ����Ϣ���û�UID��Ĭ��Ϊ��ǰ��¼�û���
     * @return array
     */
    function account_profile_basic($uid = NULL)
    {
        $params = array();
        if ($uid) {
            $this->id_format($uid);
            $params['uid'] = $uid;
        }
        return $this->oauth->get('account/profile/basic', $params);
    }

    /**
     * ��ȡ�û��Ľ�����Ϣ
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/account/profile/education account/profile/education}
     *
     * @param int $uid
     *            ��Ҫ��ȡ������Ϣ���û�UID��Ĭ��Ϊ��ǰ��¼�û���
     * @return array
     */
    function account_education($uid = NULL)
    {
        $params = array();
        if ($uid) {
            $this->id_format($uid);
            $params['uid'] = $uid;
        }
        return $this->oauth->get('account/profile/education', $params);
    }

    /**
     * ������ȡ�û��Ľ�����Ϣ
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/account/profile/education_batch account/profile/education_batch}
     *
     * @param string $uids
     *            ��Ҫ��ȡ������Ϣ���û�UID���ð�Ƕ��ŷָ�����಻����20��
     * @return array
     */
    function account_education_batch($uids)
    {
        $params = array();
        if (is_array($uids) && ! empty($uids)) {
            foreach ($uids as $k => $v) {
                $this->id_format($uids[$k]);
            }
            $params['uids'] = join(',', $uids);
        } else {
            $params['uids'] = $uids;
        }
        
        return $this->oauth->get('account/profile/education_batch', $params);
    }

    /**
     * ��ȡ�û���ְҵ��Ϣ
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/account/profile/career account/profile/career}
     *
     * @param int $uid
     *            ��Ҫ��ȡ������Ϣ���û�UID��Ĭ��Ϊ��ǰ��¼�û���
     * @return array
     */
    function account_career($uid = NULL)
    {
        $params = array();
        if ($uid) {
            $this->id_format($uid);
            $params['uid'] = $uid;
        }
        return $this->oauth->get('account/profile/career', $params);
    }

    /**
     * ������ȡ�û���ְҵ��Ϣ
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/account/profile/career_batch account/profile/career_batch}
     *
     * @param string $uids
     *            ��Ҫ��ȡ������Ϣ���û�UID���ð�Ƕ��ŷָ�����಻����20��
     * @return array
     */
    function account_career_batch($uids)
    {
        $params = array();
        if (is_array($uids) && ! empty($uids)) {
            foreach ($uids as $k => $v) {
                $this->id_format($uids[$k]);
            }
            $params['uids'] = join(',', $uids);
        } else {
            $params['uids'] = $uids;
        }
        
        return $this->oauth->get('account/profile/career_batch', $params);
    }

    /**
     * ��ȡ��˽��Ϣ�������
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/account/get_privacy account/get_privacy}
     *
     * @access public
     * @return array
     */
    function get_privacy()
    {
        return $this->oauth->get('account/get_privacy');
    }

    /**
     * ��ȡ���е�ѧУ�б�
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/account/profile/school_list account/profile/school_list}
     *
     * @param array $query
     *            ����ѡ���ʽ��array('key0'=>'value0', 'key1'=>'value1', ....)��֧�ֵ�key:
     *            - province int ʡ�ݷ�Χ��ʡ��ID��
     *            - city int ���з�Χ������ID��
     *            - area int ����Χ����ID��
     *            - type int ѧУ���ͣ�1����ѧ��2�����С�3����ר��У��4�����С�5��Сѧ��Ĭ��Ϊ1��
     *            - capital string ѧУ����ĸ��Ĭ��ΪA��
     *            - keyword string ѧУ��ƹؼ��֡�
     *            - count int ���صļ�¼����Ĭ��Ϊ10��
     *            ����keyword��capital���߱�ѡ��һ����ֻ��ѡ��һ��������ĸcapital��ѯʱ�������ṩprovince����
     * @access public
     * @return array
     */
    function school_list($query)
    {
        $params = $query;
        
        return $this->oauth->get('account/profile/school_list', $params);
    }

    /**
     * ��ȡ��ǰ��¼�û���API����Ƶ���������
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/account/rate_limit_status account/rate_limit_status}
     *
     * @access public
     * @return array
     */
    function rate_limit_status()
    {
        return $this->oauth->get('account/rate_limit_status');
    }

    /**
     * OAuth��Ȩ֮�󣬻�ȡ��Ȩ�û���UID
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/account/get_uid account/get_uid}
     *
     * @access public
     * @return array
     */
    function get_uid()
    {
        return $this->oauth->get('account/get_uid');
    }

    /**
     * ����û�����
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/account/profile/basic_update account/profile/basic_update}
     *
     * @access public
     * @param array $profile
     *            Ҫ�޸ĵ����ϡ���ʽ��array('key1'=>'value1', 'key2'=>'value2', .....)��
     *            ֧���޸ĵ��
     *            - screen_name string �û��ǳƣ�����Ϊ�ա�
     *            - gender i string �û��Ա�m���С�f��Ů������Ϊ�ա�
     *            - real_name string �û���ʵ����
     *            - real_name_visible int ��ʵ����ɼ�Χ��0���Լ��ɼ�1����ע�˿ɼ�2�������˿ɼ�
     *            - province true int ʡ�ݴ���ID������Ϊ�ա�
     *            - city true int ���д���ID������Ϊ�ա�
     *            - birthday string �û����գ���ʽ��yyyy-mm-dd��
     *            - birthday_visible int ���տɼ�Χ��0�����ܡ�1��ֻ��ʾ���ա�2��ֻ��ʾ����3�������˿ɼ�
     *            - qq string �û�QQ���롣
     *            - qq_visible int �û�QQ�ɼ�Χ��0���Լ��ɼ�1����ע�˿ɼ�2�������˿ɼ�
     *            - msn string �û�MSN��
     *            - msn_visible int �û�MSN�ɼ�Χ��0���Լ��ɼ�1����ע�˿ɼ�2�������˿ɼ�
     *            - url string �û����͵�ַ��
     *            - url_visible int �û����͵�ַ�ɼ�Χ��0���Լ��ɼ�1����ע�˿ɼ�2�������˿ɼ�
     *            - credentials_type int ֤�����ͣ�1�����֤��2��ѧ��֤��3�����֤��4�����ա�
     *            - credentials_num string ֤�����롣
     *            - email string �û����������ַ��
     *            - email_visible int �û����������ַ�ɼ�Χ��0���Լ��ɼ�1����ע�˿ɼ�2�������˿ɼ�
     *            - lang string ���԰汾��zh_cn���������ġ�zh_tw���������ġ�
     *            - description string �û��������������70�����֡�
     *            ��дbirthday����ʱ��������Լ����
     *            - ֻ�����ʱ������1986-00-00��ʽ��
     *            - ֻ���·�ʱ������0000-08-00��ʽ��
     *            - ֻ��ĳ��ʱ������0000-00-28��ʽ��
     * @return array
     */
    function update_profile($profile)
    {
        return $this->oauth->post('account/profile/basic_update', $profile);
    }

    /**
     * ���ý�����Ϣ
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/account/profile/edu_update account/profile/edu_update}
     *
     * @access public
     * @param array $edu_update
     *            Ҫ�޸ĵ�ѧУ��Ϣ����ʽ��array('key1'=>'value1', 'key2'=>'value2', .....)��
     *            ֧�����õ��
     *            - type int ѧУ���ͣ�1����ѧ��2�����С�3����ר��У��4�����С�5��Сѧ��Ĭ��Ϊ1���������
     *            - school_id ` int ѧУ���룬�������
     *            - id string ��Ҫ�޸ĵĽ�����ϢID��������Ϊ�½�������Ϊ���¡�
     *            - year int ��ѧ��ݣ���СΪ1900����󲻳���ǰ���
     *            - department string Ժϵ���߰��
     *            - visible int ���ŵȼ���0�����Լ��ɼ�1����ע���˿ɼ�2�������˿ɼ�
     * @return array
     */
    function edu_update($edu_update)
    {
        return $this->oauth->post('account/profile/edu_update', $edu_update);
    }

    /**
     * ���ѧУIDɾ���û��Ľ�����Ϣ
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/account/profile/edu_destroy account/profile/edu_destroy}
     *
     * @param int $id
     *            ������Ϣ���ѧУID��
     * @return array
     */
    function edu_destroy($id)
    {
        $this->id_format($id);
        $params = array();
        $params['id'] = $id;
        return $this->oauth->post('account/profile/edu_destroy', $params);
    }

    /**
     * ����ְҵ��Ϣ
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/account/profile/car_update account/profile/car_update}
     *
     * @param array $car_update
     *            Ҫ�޸ĵ�ְҵ��Ϣ����ʽ��array('key1'=>'value1', 'key2'=>'value2', .....)��
     *            ֧�����õ��
     *            - id string ��Ҫ���µ�ְҵ��ϢID��
     *            - start int ���빫˾��ݣ���СΪ1900�����Ϊ������ݡ�
     *            - end int �뿪��˾��ݣ�������0��
     *            - department string �������š�
     *            - visible int �ɼ�Χ��0���Լ��ɼ�1����ע�˿ɼ�2�������˿ɼ�
     *            - province int ʡ�ݴ���ID������Ϊ��ֵ��
     *            - city int ���д���ID������Ϊ��ֵ��
     *            - company string ��˾��ƣ�����Ϊ��ֵ��
     *            ����province��city���߱�ѡ��һ<br />
     *            ����idΪ�գ���Ϊ�½�ְҵ��Ϣ������company��Ϊ���������id�ǿգ���Ϊ���£�����company��ѡ
     * @return array
     */
    function car_update($car_update)
    {
        return $this->oauth->post('account/profile/car_update', $car_update);
    }

    /**
     * ��ݹ�˾IDɾ���û���ְҵ��Ϣ
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/account/profile/car_destroy account/profile/car_destroy}
     *
     * @access public
     * @param int $id
     *            ְҵ��Ϣ��Ĺ�˾ID
     * @return array
     */
    function car_destroy($id)
    {
        $this->id_format($id);
        $params = array();
        $params['id'] = $id;
        return $this->oauth->post('account/profile/car_destroy', $params);
    }

    /**
     * ���ͷ��
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/account/avatar/upload account/avatar/upload}
     *
     * @param string $image_path
     *            Ҫ�ϴ���ͷ��·��, ֧��url��[ֻ֧��png/jpg/gif���ָ�ʽ, ���Ӹ�ʽ���޸�get_image_mime����] ����ΪС��700K����Ч��GIF, JPGͼƬ. ���ͼƬ����500���ؽ����������š�
     * @return array
     */
    function update_profile_image($image_path)
    {
        $params = array();
        $params['image'] = "@{$image_path}";
        
        return $this->oauth->post('account/avatar/upload', $params);
    }

    /**
     * ������˽��Ϣ
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/account/update_privacy account/update_privacy}
     *
     * @param array $privacy_settings
     *            Ҫ�޸ĵ���˽���á���ʽ��array('key1'=>'value1', 'key2'=>'value2', .....)��
     *            ֧�����õ��
     *            - comment int �Ƿ���������ҵ�΢����0�������ˡ�1����ע���ˣ�Ĭ��Ϊ0��
     *            - geo int �Ƿ���������Ϣ��0����������1��������Ĭ��Ϊ1��
     *            - message int �Ƿ���Ը��ҷ�˽�ţ�0�������ˡ�1����ע���ˣ�Ĭ��Ϊ0��
     *            - realname int �Ƿ����ͨ�������������ң�0�������ԡ�1�����ԣ�Ĭ��Ϊ0��
     *            - badge int ѫ���Ƿ�ɼ�0�����ɼ�1���ɼ�Ĭ��Ϊ1��
     *            - mobile int �Ƿ����ͨ���ֻ�����������ң�0�������ԡ�1�����ԣ�Ĭ��Ϊ0��
     *            ���ϲ���ȫ��ѡ��
     * @return array
     */
    function update_privacy($privacy_settings)
    {
        return $this->oauth->post('account/update_privacy', $privacy_settings);
    }

    /**
     * ��ȡ��ǰ�û����ղ��б�
     *
     * �����û��ķ��������20���ղ���Ϣ�����û��ղ�ҳ�淵��������һ�µġ�
     * <br />��ӦAPI��{@link http://open.weibo.com/wiki/2/favorites favorites}
     *
     * @access public
     * @param int $page
     *            ���ؽ���ҳ�룬Ĭ��Ϊ1��
     * @param int $count
     *            ��ҳ���صļ�¼����Ĭ��Ϊ50��
     * @return array
     */
    function get_favorites($page = 1, $count = 50)
    {
        $params = array();
        $params['page'] = intval($page);
        $params['count'] = intval($count);
        
        return $this->oauth->get('favorites', $params);
    }

    /**
     * ����ղ�ID��ȡָ�����ղ���Ϣ
     *
     * ����ղ�ID��ȡָ�����ղ���Ϣ��
     * <br />��ӦAPI��{@link http://open.weibo.com/wiki/2/favorites/show favorites/show}
     *
     * @access public
     * @param int $id
     *            ��Ҫ��ѯ���ղ�ID��
     * @return array
     */
    function favorites_show($id)
    {
        $params = array();
        $this->id_format($id);
        $params['id'] = $id;
        return $this->oauth->get('favorites/show', $params);
    }

    /**
     * ��ݱ�ǩ��ȡ��ǰ��¼�û��ñ�ǩ�µ��ղ��б�
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/favorites/by_tags favorites/by_tags}
     *
     *
     * @param int $tid
     *            ��Ҫ��ѯ�ı�ǩID��'
     * @param int $count
     *            ��ҳ���صļ�¼����Ĭ��Ϊ50��
     * @param int $page
     *            ���ؽ���ҳ�룬Ĭ��Ϊ1��
     * @return array
     */
    function favorites_by_tags($tid, $page = 1, $count = 50)
    {
        $params = array();
        $params['tid'] = $tid;
        $params['count'] = $count;
        $params['page'] = $page;
        return $this->oauth->get('favorites/by_tags', $params);
    }

    /**
     * ��ȡ��ǰ��¼�û����ղر�ǩ�б�
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/favorites/tags favorites/tags}
     *
     * @access public
     * @param int $count
     *            ��ҳ���صļ�¼����Ĭ��Ϊ50��
     * @param int $page
     *            ���ؽ���ҳ�룬Ĭ��Ϊ1��
     * @return array
     */
    function favorites_tags($page = 1, $count = 50)
    {
        $params = array();
        $params['count'] = $count;
        $params['page'] = $page;
        return $this->oauth->get('favorites/tags', $params);
    }

    /**
     * �ղ�һ��΢����Ϣ
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/favorites/create favorites/create}
     *
     * @access public
     * @param int $sid
     *            �ղص�΢��id
     * @return array
     */
    function add_to_favorites($sid)
    {
        $this->id_format($sid);
        $params = array();
        $params['id'] = $sid;
        
        return $this->oauth->post('favorites/create', $params);
    }

    /**
     * ɾ��΢���ղء�
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/favorites/destroy favorites/destroy}
     *
     * @access public
     * @param int $id
     *            Ҫɾ����ղ�΢����ϢID.
     * @return array
     */
    function remove_from_favorites($id)
    {
        $this->id_format($id);
        $params = array();
        $params['id'] = $id;
        return $this->oauth->post('favorites/destroy', $params);
    }

    /**
     * ����ɾ��΢���ղء�
     *
     * ����ɾ��ǰ��¼�û����ղء������쳣ʱ������HTTP400����
     * <br />��ӦAPI��{@link http://open.weibo.com/wiki/2/favorites/destroy_batch favorites/destroy_batch}
     *
     * @access public
     * @param mixed $fids
     *            ��ɾ���һ��˽��ID���ð�Ƕ��Ÿ�����������һ������ID��ɵ����顣���20�������磺"231101027525486630,201100826122315375"��array(231101027525486630,201100826122315375);
     * @return array
     */
    function remove_from_favorites_batch($fids)
    {
        $params = array();
        if (is_array($fids) && ! empty($fids)) {
            foreach ($fids as $k => $v) {
                $this->id_format($fids[$k]);
            }
            $params['ids'] = join(',', $fids);
        } else {
            $params['ids'] = $fids;
        }
        
        return $this->oauth->post('favorites/destroy_batch', $params);
    }

    /**
     * ����һ���ղص��ղر�ǩ
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/favorites/tags/update favorites/tags/update}
     *
     * @access public
     * @param int $id
     *            ��Ҫ���µ��ղ�ID��
     * @param string $tags
     *            ��Ҫ���µı�ǩ���ݣ��ð�Ƕ��ŷָ�����಻����2����
     * @return array
     */
    function favorites_tags_update($id, $tags)
    {
        $params = array();
        $params['id'] = $id;
        if (is_array($tags) && ! empty($tags)) {
            foreach ($tags as $k => $v) {
                $this->id_format($tags[$k]);
            }
            $params['tags'] = join(',', $tags);
        } else {
            $params['tags'] = $tags;
        }
        return $this->oauth->post('favorites/tags/update', $params);
    }

    /**
     * ���µ�ǰ��¼�û������ղ��µ�ָ����ǩ
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/favorites/tags/update_batch favorites/tags/update_batch}
     *
     * @param int $tid
     *            ��Ҫ���µı�ǩID������
     * @param string $tag
     *            ��Ҫ���µı�ǩ���ݡ�����
     * @return array
     */
    function favorites_update_batch($tid, $tag)
    {
        $params = array();
        $params['tid'] = $tid;
        $params['tag'] = $tag;
        return $this->oauth->post('favorites/tags/update_batch', $params);
    }

    /**
     * ɾ��ǰ��¼�û������ղ��µ�ָ����ǩ
     *
     * ɾ���ǩ�󣬸��û������ղ��У�����˸ñ�ǩ���ղؾ�����ñ�ǩ�Ĺ�����ϵ
     * <br />��ӦAPI��{@link http://open.weibo.com/wiki/2/favorites/tags/destroy_batch favorites/tags/destroy_batch}
     *
     * @param int $tid
     *            ��Ҫ���µı�ǩID������
     * @return array
     */
    function favorites_tags_destroy_batch($tid)
    {
        $params = array();
        $params['tid'] = $tid;
        return $this->oauth->post('favorites/tags/destroy_batch', $params);
    }

    /**
     * ��ȡĳ�û��Ļ���
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/trends trends}
     *
     * @param int $uid
     *            ��ѯ�û���ID��Ĭ��Ϊ��ǰ�û�����ѡ��
     * @param int $page
     *            ָ�����ؽ���ҳ�롣��ѡ��
     * @param int $count
     *            ��ҳ��С��ȱʡֵ10����ѡ��
     * @return array
     */
    function get_trends($uid = NULL, $page = 1, $count = 10)
    {
        $params = array();
        if ($uid) {
            $params['uid'] = $uid;
        } else {
            $user_info = $this->get_uid();
            $params['uid'] = $user_info['uid'];
        }
        $this->id_format($params['uid']);
        $params['page'] = $page;
        $params['count'] = $count;
        return $this->oauth->get('trends', $params);
    }

    /**
     * �жϵ�ǰ�û��Ƿ��עĳ����
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/trends/is_follow trends/is_follow}
     *
     * @access public
     * @param string $trend_name
     *            ����ؼ��֡�
     * @return array
     */
    function trends_is_follow($trend_name)
    {
        $params = array();
        $params['trend_name'] = $trend_name;
        return $this->oauth->get('trends/is_follow', $params);
    }

    /**
     * �������һСʱ�ڵ����Ż���
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/trends/hourly trends/hourly}
     *
     * @param int $base_app
     *            �Ƿ���ڵ�ǰӦ������ȡ��ݡ�1��ʾ���ڵ�ǰӦ������ȡ��ݣ�Ĭ��Ϊ0����ѡ��
     * @return array
     */
    function hourly_trends($base_app = 0)
    {
        $params = array();
        $params['base_app'] = $base_app;
        
        return $this->oauth->get('trends/hourly', $params);
    }

    /**
     * �������һ���ڵ����Ż���
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/trends/daily trends/daily}
     *
     * @param int $base_app
     *            �Ƿ���ڵ�ǰӦ������ȡ��ݡ�1��ʾ���ڵ�ǰӦ������ȡ��ݣ�Ĭ��Ϊ0����ѡ��
     * @return array
     */
    function daily_trends($base_app = 0)
    {
        $params = array();
        $params['base_app'] = $base_app;
        
        return $this->oauth->get('trends/daily', $params);
    }

    /**
     * �������һ���ڵ����Ż���
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/trends/weekly trends/weekly}
     *
     * @access public
     * @param int $base_app
     *            �Ƿ���ڵ�ǰӦ������ȡ��ݡ�1��ʾ���ڵ�ǰӦ������ȡ��ݣ�Ĭ��Ϊ0����ѡ��
     * @return array
     */
    function weekly_trends($base_app = 0)
    {
        $params = array();
        $params['base_app'] = $base_app;
        
        return $this->oauth->get('trends/weekly', $params);
    }

    /**
     * ��עĳ����
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/trends/follow trends/follow}
     *
     * @access public
     * @param string $trend_name
     *            Ҫ��ע�Ļ���ؼ�ʡ�
     * @return array
     */
    function follow_trends($trend_name)
    {
        $params = array();
        $params['trend_name'] = $trend_name;
        return $this->oauth->post('trends/follow', $params);
    }

    /**
     * ȡ���ĳ����Ĺ�ע
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/trends/destroy trends/destroy}
     *
     * @access public
     * @param int $tid
     *            Ҫȡ���ע�Ļ���ID��
     * @return array
     */
    function unfollow_trends($tid)
    {
        $this->id_format($tid);
        
        $params = array();
        $params['trend_id'] = $tid;
        
        return $this->oauth->post('trends/destroy', $params);
    }

    /**
     * ����ָ���û��ı�ǩ�б�
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/tags tags}
     *
     * @param int $uid
     *            ��ѯ�û���ID��Ĭ��Ϊ��ǰ�û�����ѡ��
     * @param int $page
     *            ָ�����ؽ���ҳ�롣��ѡ��
     * @param int $count
     *            ��ҳ��С��ȱʡֵ20�����ֵ200����ѡ��
     * @return array
     */
    function get_tags($uid = NULL, $page = 1, $count = 20)
    {
        $params = array();
        if ($uid) {
            $params['uid'] = $uid;
        } else {
            $user_info = $this->get_uid();
            $params['uid'] = $user_info['uid'];
        }
        $this->id_format($params['uid']);
        $params['page'] = $page;
        $params['count'] = $count;
        return $this->oauth->get('tags', $params);
    }

    /**
     * ������ȡ�û��ı�ǩ�б�
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/tags/tags_batch tags/tags_batch}
     *
     * @param string $uids
     *            Ҫ��ȡ��ǩ���û�ID�����20�����ŷָ�������
     * @return array
     */
    function get_tags_batch($uids)
    {
        $params = array();
        if (is_array($uids) && ! empty($uids)) {
            foreach ($uids as $k => $v) {
                $this->id_format($uids[$k]);
            }
            $params['uids'] = join(',', $uids);
        } else {
            $params['uids'] = $uids;
        }
        return $this->oauth->get('tags/tags_batch', $params);
    }

    /**
     * �����û�����Ȥ�ı�ǩ
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/tags/suggestions tags/suggestions}
     *
     * @access public
     * @param int $count
     *            ��ҳ��С��ȱʡֵ10�����ֵ10����ѡ��
     * @return array
     */
    function get_suggest_tags($count = 10)
    {
        $params = array();
        $params['count'] = intval($count);
        return $this->oauth->get('tags/suggestions', $params);
    }

    /**
     * Ϊ��ǰ��¼�û�����µ��û���ǩ
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/tags/create tags/create}
     *
     * @access public
     * @param mixed $tags
     *            Ҫ������һ���ǩ��ÿ����ǩ�ĳ��Ȳ��ɳ���7�����֣�14������ַ�����ǩ֮���ö��ż�������ɶ����ǩ���ɵ����顣�磺"abc,drf,efgh,tt"��array("abc", "drf", "efgh", "tt")
     * @return array
     */
    function add_tags($tags)
    {
        $params = array();
        if (is_array($tags) && ! empty($tags)) {
            $params['tags'] = join(',', $tags);
        } else {
            $params['tags'] = $tags;
        }
        return $this->oauth->post('tags/create', $params);
    }

    /**
     * ɾ���ǩ
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/tags/destroy tags/destroy}
     *
     * @access public
     * @param int $tag_id
     *            ��ǩID���������
     * @return array
     */
    function delete_tag($tag_id)
    {
        $params = array();
        $params['tag_id'] = $tag_id;
        return $this->oauth->post('tags/destroy', $params);
    }

    /**
     * ����ɾ���ǩ
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/tags/destroy_batch tags/destroy_batch}
     *
     * @access public
     * @param mixed $ids
     *            ��ѡ����Ҫɾ���tag id�����id�ð�Ƕ��ŷָ���10�������ɶ��tag id���ɵ����顣�磺��553,554,555"��array(553, 554, 555)
     * @return array
     */
    function delete_tags($ids)
    {
        $params = array();
        if (is_array($ids) && ! empty($ids)) {
            $params['ids'] = join(',', $ids);
        } else {
            $params['ids'] = $ids;
        }
        return $this->oauth->post('tags/destroy_batch', $params);
    }

    /**
     * ��֤�ǳ��Ƿ���ã������轨���ǳ�
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/register/verify_nickname register/verify_nickname}
     *
     * @param string $nickname
     *            ��Ҫ��֤���ǳơ�4-20���ַ�֧����Ӣ�ġ����֡�"_"����š�����
     * @return array
     */
    function verify_nickname($nickname)
    {
        $params = array();
        $params['nickname'] = $nickname;
        return $this->oauth->get('register/verify_nickname', $params);
    }

    /**
     * �����û�ʱ��������������
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/search/suggestions/users search/suggestions/users}
     *
     * @param string $q
     *            �����Ĺؼ��֣�������URLencoding������,�м���ò�Ҫ���ֿո�
     * @param int $count
     *            ���صļ�¼����Ĭ��Ϊ10��
     * @return array
     */
    function search_users($q, $count = 10)
    {
        $params = array();
        $params['q'] = $q;
        $params['count'] = $count;
        return $this->oauth->get('search/suggestions/users', $params);
    }

    /**
     * ����΢��ʱ��������������
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/search/suggestions/statuses search/suggestions/statuses}
     *
     * @param string $q
     *            �����Ĺؼ��֣�������URLencoding������
     * @param int $count
     *            ���صļ�¼����Ĭ��Ϊ10��
     * @return array
     */
    function search_statuses($q, $count = 10)
    {
        $params = array();
        $params['q'] = $q;
        $params['count'] = $count;
        return $this->oauth->get('search/suggestions/statuses', $params);
    }

    /**
     * ����ѧУʱ��������������
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/search/suggestions/schools search/suggestions/schools}
     *
     * @param string $q
     *            �����Ĺؼ��֣�������URLencoding������
     * @param int $count
     *            ���صļ�¼����Ĭ��Ϊ10��
     * @param
     *            int type ѧУ���ͣ�0��ȫ����1����ѧ��2�����С�3����ר��У��4�����С�5��Сѧ��Ĭ��Ϊ0��ѡ��
     * @return array
     */
    function search_schools($q, $count = 10, $type = 1)
    {
        $params = array();
        $params['q'] = $q;
        $params['count'] = $count;
        $params['type'] = $type;
        return $this->oauth->get('search/suggestions/schools', $params);
    }

    /**
     * ������˾ʱ��������������
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/search/suggestions/companies search/suggestions/companies}
     *
     * @param string $q
     *            �����Ĺؼ��֣�������URLencoding������
     * @param int $count
     *            ���صļ�¼����Ĭ��Ϊ10��
     * @return array
     */
    function search_companies($q, $count = 10)
    {
        $params = array();
        $params['q'] = $q;
        $params['count'] = $count;
        return $this->oauth->get('search/suggestions/companies', $params);
    }

    /**
     * ���û�ʱ�����뽨��
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/search/suggestions/at_users search/suggestions/at_users}
     *
     * @param string $q
     *            �����Ĺؼ��֣�������URLencoding������
     * @param int $count
     *            ���صļ�¼����Ĭ��Ϊ10��
     * @param int $type
     *            �������ͣ�0����ע��1����˿������
     * @param int $range
     *            ���뷶Χ��0��ֻ�����ע�ˡ�1��ֻ�����ע�˵ı�ע��2��ȫ����Ĭ��Ϊ2��ѡ��
     * @return array
     */
    function search_at_users($q, $count = 10, $type = 0, $range = 2)
    {
        $params = array();
        $params['q'] = $q;
        $params['count'] = $count;
        $params['type'] = $type;
        $params['range'] = $range;
        return $this->oauth->get('search/suggestions/at_users', $params);
    }

    /**
     * ������ָ����һ������������ƥ���΢��
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/search/statuses search/statuses}
     *
     * @param array $query
     *            ����ѡ���ʽ��array('key0'=>'value0', 'key1'=>'value1', ....)��֧�ֵ�key:
     *            - q string �����Ĺؼ��֣��������URLencode��
     *            - filter_ori int ���������Ƿ�Ϊԭ����0��ȫ����1��ԭ����2��ת����Ĭ��Ϊ0��
     *            - filter_pic int ���������Ƿ��ͼƬ��0��ȫ����1����2������Ĭ��Ϊ0��
     *            - fuid int ������΢�����ߵ��û�UID��
     *            - province int ������ʡ�ݷ�Χ��ʡ��ID��
     *            - city int �����ĳ��з�Χ������ID��
     *            - starttime int ��ʼʱ�䣬Unixʱ�����
     *            - endtime int ����ʱ�䣬Unixʱ�����
     *            - count int ��ҳ���صļ�¼����Ĭ��Ϊ10��
     *            - page int ���ؽ���ҳ�룬Ĭ��Ϊ1��
     *            - needcount boolean ���ؽ�����Ƿ��ؼ�¼��true�����ء�false�������أ�Ĭ��Ϊfalse��
     *            - base_app int �Ƿ�ֻ��ȡ��ǰӦ�õ���ݡ�0Ϊ��������ݣ���1Ϊ�ǣ�����ǰӦ�ã���Ĭ��Ϊ0��
     *            needcount����ͬ���ᵼ����Ӧ�ķ���ֵ�ṹ��ͬ
     *            ���ϲ���ȫ��ѡ��
     * @return array
     */
    function search_statuses_high($query)
    {
        return $this->oauth->get('search/statuses', $query);
    }

    /**
     * ͨ��ؼ�������û�
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/search/users search/users}
     *
     * @param array $query
     *            ����ѡ���ʽ��array('key0'=>'value0', 'key1'=>'value1', ....)��֧�ֵ�key:
     *            - q string �����Ĺؼ��֣��������URLencode��
     *            - snick int ������Χ�Ƿ���ǳƣ�0������1����
     *            - sdomain int ������Χ�Ƿ���������0������1����
     *            - sintro int ������Χ�Ƿ���飬0������1����
     *            - stag int ������Χ�Ƿ���ǩ��0������1����
     *            - province int ������ʡ�ݷ�Χ��ʡ��ID��
     *            - city int �����ĳ��з�Χ������ID��
     *            - gender string �������Ա�Χ��m���С�f��Ů��
     *            - comorsch string �����Ĺ�˾ѧУ��ơ�
     *            - sort int ����ʽ��1��������ʱ�䡢2������˿��Ĭ��Ϊ1��
     *            - count int ��ҳ���صļ�¼����Ĭ��Ϊ10��
     *            - page int ���ؽ���ҳ�룬Ĭ��Ϊ1��
     *            - base_app int �Ƿ�ֻ��ȡ��ǰӦ�õ���ݡ�0Ϊ��������ݣ���1Ϊ�ǣ�����ǰӦ�ã���Ĭ��Ϊ0��
     *            �������в���ȫ��ѡ��
     * @return array
     */
    function search_users_keywords($query)
    {
        return $this->oauth->get('search/users', $query);
    }

    /**
     * ��ȡϵͳ�Ƽ��û�
     *
     * ����ϵͳ�Ƽ����û��б?
     * <br />��ӦAPI��{@link http://open.weibo.com/wiki/2/suggestions/users/hot suggestions/users/hot}
     *
     * @access public
     * @param string $category
     *            ���࣬��ѡ�����ĳһ�����Ƽ��û���Ĭ��Ϊ default����������·����У����ؿ��б?<br />
     *            - default:�����ע
     *            - ent:Ӱ������
     *            - hk_famous:��̨����
     *            - model:ģ��
     *            - cooking:��ʳ&����
     *            - sport:��������
     *            - finance:�̽�����
     *            - tech:IT������
     *            - singer:����
     *            - writer������
     *            - moderator:������
     *            - medium:ý���ܱ�
     *            - stockplayer:���ɸ���
     * @return array
     */
    function hot_users($category = "default")
    {
        $params = array();
        $params['category'] = $category;
        
        return $this->oauth->get('suggestions/users/hot', $params);
    }

    /**
     * ��ȡ�û����ܸ���Ȥ����
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/suggestions/users/may_interested suggestions/users/may_interested}
     *
     * @access public
     * @param int $page
     *            ���ؽ���ҳ�룬Ĭ��Ϊ1��
     * @param int $count
     *            ��ҳ���صļ�¼����Ĭ��Ϊ10��
     * @return array
     * @ignore
     *
     */
    function suggestions_may_interested($page = 1, $count = 10)
    {
        $params = array();
        $params['page'] = $page;
        $params['count'] = $count;
        return $this->oauth->get('suggestions/users/may_interested', $params);
    }

    /**
     * ���һ��΢�������Ƽ����΢���û���
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/suggestions/users/by_status suggestions/users/by_status}
     *
     * @access public
     * @param string $content
     *            ΢���������ݡ�
     * @param int $num
     *            ���ؽ����Ŀ��Ĭ��Ϊ10��
     * @return array
     */
    function suggestions_users_by_status($content, $num = 10)
    {
        $params = array();
        $params['content'] = $content;
        $params['num'] = $num;
        return $this->oauth->get('suggestions/users/by_status', $params);
    }

    /**
     * �����ղ�
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/suggestions/favorites/hot suggestions/favorites/hot}
     *
     * @param int $count
     *            ÿҳ���ؽ����Ĭ��20��ѡ��
     * @param int $page
     *            ����ҳ�룬Ĭ��1��ѡ��
     * @return array
     */
    function hot_favorites($page = 1, $count = 20)
    {
        $params = array();
        $params['count'] = $count;
        $params['page'] = $page;
        return $this->oauth->get('suggestions/favorites/hot', $params);
    }

    /**
     * ��ĳ�˱�ʶΪ������Ȥ����
     *
     * ��ӦAPI��{@link http://open.weibo.com/wiki/2/suggestions/users/not_interested suggestions/users/not_interested}
     *
     * @param int $uid
     *            ������Ȥ���û���UID��
     * @return array
     */
    function put_users_not_interested($uid)
    {
        $params = array();
        $params['uid'] = $uid;
        return $this->oauth->post('suggestions/users/not_interested', $params);
    }

    function get_token_info($token)
    {
        $params['access_token'] = $token;
        return $this->oauth->post("https://api.weibo.com/oauth2/get_token_info", $params);
    }
    
    // =========================================
    
    /**
     *
     * @ignore
     *
     */
    protected function request_with_pager($url, $page = false, $count = false, $params = array())
    {
        if ($page)
            $params['page'] = $page;
        if ($count)
            $params['count'] = $count;
        
        return $this->oauth->get($url, $params);
    }

    /**
     *
     * @ignore
     *
     */
    protected function request_with_uid($url, $uid_or_name, $page = false, $count = false, $cursor = false, $post = false, $params = array())
    {
        if ($page)
            $params['page'] = $page;
        if ($count)
            $params['count'] = $count;
        if ($cursor)
            $params['cursor'] = $cursor;
        
        if ($post)
            $method = 'post';
        else
            $method = 'get';
        
        if ($uid_or_name !== NULL) {
            $this->id_format($uid_or_name);
            $params['id'] = $uid_or_name;
        }
        
        return $this->oauth->$method($url, $params);
    }

    /**
     *
     * @ignore
     *
     */
    protected function id_format(&$id)
    {
        if (is_float($id)) {
            $id = number_format($id, 0, '', '');
        } elseif (is_string($id)) {
            $id = trim($id);
        }
    }
}

