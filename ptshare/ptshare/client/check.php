<?php
require_once (dirname(__FILE__) . '/config/ShareConfig.php');

$api_conf = ShareConfig::$api_conf;

$errors = array();

foreach ($api_conf as $method => $conf) {
    if (! isset($conf['host'])) {
        $errors[] = sprintf('方法[%s] 配置项[host]为空', $method);
    }
    if (! empty($conf['ip']) && ! preg_match('/^((25[0-5]|2[0-4]\d|[01]?\d\d?)($|(?!\.$)\.)){4}$/', $conf['ip'])) {
        $errors[] = sprintf('方法[%s] 配置项[ip]不正确', $method);
    }
    if (! empty($conf['method']) && (strcasecmp($conf['method'], 'get') !== 0 && strcasecmp($conf['method'], 'post') !== 0)) {
        $errors[] = sprintf('方法[%s] 配置项[method]不正确', $method);
    }
    if (isset($conf['params']) && ! is_array($conf['params'])) {
        $errors[] = sprintf('方法[%s] 配置项[params]不是数组', $method);
    }
}

$errors = array(
    1,
    2,
    3
);

if (empty($errors)) {
    echo '没有发现配置错误' . PHP_EOL;
} else {
    echo '发现以下配置错误[' . implode("\n", $errors) . ']';
}
