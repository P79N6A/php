<?php
class CallbackController extends BaseController
{
    public $_url = array(
        'h5.putaofenxiang.com',
        'h5.test.putaofenxiang.com',
        'h5.xcq.putaofenxiang.com',
    );
        
    public function indexAction()
    {
        $code  = trim(strip_tags($_GET['code']));
        $state = trim(strip_tags($_GET['state']));

        $wxclient = new WxClient();
        try{
            $result = $wxclient->getAccessToken($code);
            $user_result = ShareClient::active('wx', $result['access_token'], $result['unionid'], $result['openid']);
            
            setcookie("token", $user_result['token'], time()+3600, "/", $_SERVER["SERVER_NAME"]);
        }catch(Exception $e){
            var_dump($e);die;
        }
        
        header("Location:{$state}");
    }

    public function bridgeAction()
    {
        $code  = trim(strip_tags($_GET['code']));
        $state = trim(strip_tags($_GET['state']));
        $host  = trim(strip_tags($_GET['host']));

        Interceptor::ensureNotFalse(in_array($host, array_keys($this->_url)), ERROR_PARAM_IS_EMPTY, 'host');

        $url = "http://". $host. "/callback/index?". "code=". $code. "&state=". $state;

        header("Location:{$url}");
    }

}