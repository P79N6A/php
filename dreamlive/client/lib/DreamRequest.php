<?php
class DreamRequest
{
    const MODE_DEFAULT      = 1;
    const MODE_PARALLEL     = 2;
    const MAX_REQUESTS      = 100;    // 最大请求数
    const CONNECT_TIMEOUT   = 2000;   // 连接超时（单位ms）
    const READ_TIMEOUT      =60000;   // 读取超时（单位ms）
    const ERROR_START       = - 11;
    const ERROR_NOT_START   = - 12;
    const ERROR_MAX_REQUEST = - 13;
    const ERROR_HANDLE_LOST = - 14;
    const ERROR_CONNECT     = - 15;
    const ERROR_HTTP_CODE   = - 16;
    const ERROR_API         = - 17;
    const ERROR_BIND        = - 18;
    const ERROR_COMMIT      = - 19;

    public static $error_descs = array(
        self::ERROR_START => '已启动并行调用',
        self::ERROR_NOT_START => '没有启动并行调用',
        self::ERROR_MAX_REQUEST => '超过请求个数',
        self::ERROR_HANDLE_LOST => '句柄丢失',
        self::ERROR_CONNECT => '连接错误',
        self::ERROR_HTTP_CODE => 'HTTP响应码错误',
        self::ERROR_API => '接口错误',
        self::ERROR_BIND => '绑定错误',
        self::ERROR_COMMIT => '并行调用错误[%s]'
    );

    protected $multi_start = false;

    protected $multi_handle = null;

    protected $multi_requests = array();

    protected $multi_errors = array();

    protected $multi_key = '';

    private static $instance;

    public static function getInstance()
    {
        if (! isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
    }

    public function start()
    {
        if ($this->multi_start) {
            $errno = self::ERROR_START;
            $errmsg = self::$error_descs[$errno];
            throw new Exception($errmsg, $errno);
        }

        if (! isset($this->multi_handle)) {
            $this->multi_handle = curl_multi_init();
        }

        $this->multi_start = true;
    }

    public function add($url, $method, $data = array(), $options = array(), $connect_timeout = null, $read_timeout = null)
    {
        if (! $this->multi_start) {
            $errno = self::ERROR_NOT_START;
            $errmsg = self::$error_descs[$errno];
            throw new Exception($errmsg, $errno);
        }

        if (count($this->multi_requests) >= self::MAX_REQUESTS) {
            $errno = self::ERROR_MAX_REQUEST;
            $errmsg = self::$error_descs[$errno];
            throw new Exception($errmsg, $errno);
        }

        $ch = $this->init($url, $method, $data, $options, $connect_timeout, $read_timeout);
        $multi_key = (int) $ch;

        curl_multi_add_handle($this->multi_handle, $ch);

        $this->multi_key = $multi_key;
        $this->multi_requests[$multi_key] = array(
            'url' => $url,
            'data' => $data
        );
    }

    public function commit()
    {
        if (! $this->multi_start) {
            $errno = self::ERROR_NOT_START;
            $errmsg = self::$error_descs[$errno];

            throw new Exception($errmsg, $errno);
        }

        $this->multi_start = false;

        do {
            $mrc = curl_multi_exec($this->multi_handle, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);

        while ($active && $mrc == CURLM_OK) {
            if (- 1 === curl_multi_select($this->multi_handle, 0.5)) {
                usleep(50);
            }

            do {
                $mrc = curl_multi_exec($this->multi_handle, $active);
            } while ($mrc == CURLM_CALL_MULTI_PERFORM);
        }

        while ($info = curl_multi_info_read($this->multi_handle)) {
            $ch = $info['handle'];
            $multi_key = (int) $ch;
            if (! isset($this->multi_requests[$multi_key])) {
                $errno = self::ERROR_HANDLE_LOST;
                $errlog = self::$error_descs[self::ERROR_HANDLE_LOST];
                DreamLog::warning($errlog, $errno);
                $this->multi_errors[] = $errno.":".self::$error_descs[$errno];
                break;
            }

            $request = $this->multi_requests[$multi_key];
            $url = $request['url'];
            $data = $request['data'];

            $content = curl_multi_getcontent($ch);
            if (false === $content) {
                $errno = self::ERROR_CONNECT;
                $errlog = 'multi curl failure url[%s] data[%s] curl_errno[%d] curl_errmsg[%s]';
                $errlog = sprintf($errlog, $url, http_build_query($data), curl_errno($ch), curl_error($ch));
                DreamLog::warning($errlog, $errno);
                $this->multi_errors[] = $errno . ':' . self::$error_descs[$errno];
                break;
            }

            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($http_code !== 200) {
                $errno = self::ERROR_HTTP_CODE;
                $errlog = 'multi curl error url[%s] data[%s] http_code[%d]';
                $errlog = sprintf($errlog, $url, http_build_query($data), $http_code);
                DreamLog::warning($errlog, $errno);
                $this->multi_errors[] = $errno . ':' . self::$error_descs[$errno];
                break;
            }

            $result = json_decode($content, true);

            $api_errno     = isset($result['errno'])     ? intval($result['errno'])      : '';
            $api_errmsg    = isset($result['errmsg'])    ? $result['errmsg']             : '';
            $request_time  = defined('DREAM_START_TIME') ? ceil(DREAM_START_TIME / 1000) : time();
            $response_time = isset($result['time'])      ? intval($result['time'])       : '';
            $consume       = isset($result['consume'])   ? intval($result['consume'])    : '';

            if ($api_errno !== 0) {
                $errno = self::ERROR_API;
                $errlog = 'multi api error url[%s] data[%s] api_errno[%d] api_errmsg[%s] request_time[%d] response_time[%s] consume[%s]';
                $errlog = sprintf($errlog, $url, http_build_query($data), $api_errno, $api_errmsg, $request_time, $response_time, $consume);
                DreamLog::debug($errlog, $errno);
                $errmsg = sprintf(self::$error_descs[$errno], $api_errno, $api_errmsg, $consume);
                $this->multi_errors[] = $api_errno . ':' . $api_errmsg;
                break;
            }

            $debuglog = 'multi curl success url[%s] data[%s] content[%s] request_time[%d] response_time[%s] consume[%s]';
            $debuglog = sprintf($debuglog, $url, http_build_query($data), $content, $request_time, $response_time, $consume);
            DreamLog::debug($debuglog);

            $data = isset($result['data']) ? $result['data'] : array();
            $this->multi_requests[$multi_key]['bind'] = $data;

            curl_multi_remove_handle($this->multi_handle, $ch);
        }

        if (count($this->multi_errors) > 0) {
            $errno = self::ERROR_COMMIT;
            $errmsg = sprintf(self::$error_descs[$errno], implode("\n", $this->multi_errors));

            throw new Exception($errmsg, self::ERROR_COMMIT);
        }

        $this->multi_requests = array();
    }

    public function bind(&$value)
    {
        if (empty($this->multi_key)) {
            $errno = self::ERROR_BIND;
            $errmsg = self::$error_descs[$errno];
            throw new Exception($errmsg, $errno);
        }

        $multi_key = $this->multi_key;
        $this->multi_requests[$multi_key]['bind'] = &$value;
        $this->multi_key = '';
    }

    public function execute($url, $method, $data = array(), $options = array(), $connect_timeout = null, $read_timeout = null)
    {
        try{
            if ($this->multi_start) {
                $errno = self::ERROR_MODE_DEFAULT_ONLY;
                $errmsg = sprintf(self::$error_descs[$errno]);
                throw new Exception($errmsg, $errno);
            }
            $ch = $this->init($url, $method, $data, $options, $connect_timeout, $read_timeout);

            $content = curl_exec($ch);

            if (false === $content) {
                $errno = self::ERROR_CONNECT;
                $curl_errno = curl_errno($ch);
                $curl_errmsg = curl_error($ch);
                $errlog = 'curl failure url[%s] data[%s] curl_errno[%d] curl_errmsg[%s]';
                $errlog = sprintf($errlog, $url, http_build_query($data), $curl_errno, $curl_errmsg);
                DreamLog::warning($errlog, $errno);
                $errmsg = self::$error_descs[$errno];
                curl_close($ch);

                throw new Exception($errmsg, $errno);
            }

            // 获取curl各种执行信息，并打印日志
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($http_code !== 200) {
                $errno = self::ERROR_HTTP_CODE;
                $errlog = 'curl error url[%s] data[%s] http_code[%d]';
                $errlog = sprintf($errlog, $url, http_build_query($data), $http_code);
                DreamLog::warning($errlog, $errno);
                $errmsg = self::$error_descs[$errno];
                throw new Exception($errmsg, $errno);
            }
            $result = json_decode($content, true);

            $api_errno     = isset($result['errno'])     ? intval($result['errno'])      : '';
            $api_errmsg    = isset($result['errmsg'])    ? $result['errmsg']             : '';
            $request_time  = defined('DREAM_START_TIME') ? ceil(DREAM_START_TIME / 1000) : time();
            $response_time = isset($result['time'])      ? intval($result['time'])       : '';
            $consume       = isset($result['consume'])   ? intval($result['consume'])    : '';

            if ($api_errno != 0) {
                $errno = self::ERROR_API;
                $errlog = 'api error url[%s] data[%s] api_errno[%d] api_errmsg[%s] request_time[%d] response_time[%s] consume[%s]';
                $errlog = sprintf($errlog, $url, http_build_query($data), $api_errno, $api_errmsg, $request_time, $response_time, $consume);
                DreamLog::debug($errlog, $errno);
                throw new Exception($api_errmsg, $api_errno);
            }

            $debuglog = 'curl success url[%s] data[%s] content[%s] request_time[%d] response_time[%s] consume[%s]';
            $debuglog = sprintf($debuglog, $url, http_build_query($data), $content, $request_time, $response_time, $consume);
            DreamLog::debug($debuglog);

            return isset($result['data']) ? $result['data'] : array();
        }catch (Exception $e){
            file_put_contents("/tmp/dream_client.log", json_encode(array('r'=>$content,'url'=>$url,'data'=>$data,'op'=>$options)), FILE_APPEND);
            throw $e;
        }
    }

    public function isParallel()
    {
        return true === $this->multi_start;
    }

    protected function init($url, $method = 'POST', $data = array(), $options = array(), $connect_timeout = null, $read_timeout = null)
    {
        $ch = curl_init();

        $method = (0 === strcasecmp($method, 'post')) ? 'POST' : 'GET';

        if ('GET' === $method && ! empty($data)) {
            $url .= (false === strpos($url, '?')) ? '?' : '&';
            $url .= http_build_query($data, '', '&');
        }

        $options[CURLOPT_URL] = $url;
        $options[CURLOPT_RETURNTRANSFER] = 1;
        $options[CURLOPT_NOSIGNAL] = 1; // 屏蔽信号（libcurl不产生任何信号，防止打断主循环）

        if (null === $connect_timeout) {
            $connect_timeout = self::CONNECT_TIMEOUT;
        }

        if (null === $read_timeout) {
            $read_timeout = self::READ_TIMEOUT;
        }

        if (defined('CURLOPT_CONNECTTIMEOUT_MS')) {
            $options[CURLOPT_CONNECTTIMEOUT_MS] = $connect_timeout;
            $options[CURLOPT_TIMEOUT_MS] = $connect_timeout + $read_timeout;
        } else {
            $options[CURLOPT_CONNECTTIMEOUT] = ceil($connect_timeout / 1000);
            $options[CURLOPT_TIMEOUT] = ceil(($connect_timeout + $read_timeout) / 1000);
        }

        if ('POST' === $method) {
            $options[CURLOPT_POST] = 1;
            $options[CURLOPT_POSTFIELDS] = $data;
        }

        curl_setopt_array($ch, $options);

        return $ch;
    }
}
?>
