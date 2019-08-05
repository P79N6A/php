<?php
class ErrorController
{
    public function errorAction()
    {
        //不做渲染
        $this->setNoViewRender(true);

        $error  = $this->getParam('error_handle');

        $result = array(
	        "errno"   => $error->exception->getCode(),
	        "errmsg"  => $error->exception->getMessage(),
	        "consume" => Consume::getTime(),
	        "time"    => Util::getTime(false)
        );

		if(!$result['errno']){
			$result['errno']  = ERROR_SYS_UNKNOWN;
			$result['errmsg'] = Util::getError(ERROR_SYS_UNKNOWN). 's';
		}

        $content = json_encode($result);

        Logger::warning($content,array('token'=>$_REQUEST['token']), $result["errno"]);

        $callback = htmlspecialchars(trim($this->getRequest('callback')), ENT_QUOTES);

        header("Server: nginx/1.2.3");

        if (empty($callback)) {
            //header('Content-type: application/json; charset=UTF-8');
            //print $content;

            Util::showError("[{$result['errno']}]:{$result['errmsg']}");
        } else {
            //header("Content-type: application/x-javascript; charset=UTF-8");
            //print $callback . '(' . $content . ');';
            Util::showError($callback . '(' . $content . ');');
        }
        exit;
    }
}
