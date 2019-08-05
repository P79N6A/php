<?php
class DepositHelperController extends BaseController
{
    public function __construct()
    {
        Consume::start();
        $this->init();
    }
    
    //对帐单
    public function billAction()
    {
        $source   = $this->getParam('source');
        Interceptor::ensureNotEmpty($source, ERROR_PARAM_IS_EMPTY, 'source');

        $deposithelper = new DepositHelper();
        $result = $deposithelper->bill($source);

        $this->render($result);
    }

}
?>
