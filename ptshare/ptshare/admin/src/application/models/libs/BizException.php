<?php
class BizException
{
	public function __construct($errno, $args = array())
	{
		$args  = is_array($args) ? $args                  : array($args);
		$error = empty($args)    ? Util::getError($errno) : vsprintf(Util::getError($errno), $args);
        if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
            $this->render(array(), $errno, $error);
        }else{
            Util::showError("[{$errno}]:{$error}");
        }
	}

    public function render($data = array(), $errno = 0, $errmsg = "")
    {
        header('Content-Type: application/json; charset=UTF-8');
        header("Server: nginx/1.2.3");

        $result = array(
            "errno" => $errno,
            "errmsg" => $errmsg,
            "time" => time(),
        );

        $content = json_encode($result);

        print $content;
        exit;
    }
}
