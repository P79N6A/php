<?php
class AccountAnswerController extends BaseController
{
    public function cashAction()
    {
        /*{{{奖现金*/
        $cash =  $this->getParam('cash');
        $key = $this->getParam('key');
        Interceptor::ensureFalse($key != 'Yjjfdasdfasf2312sdyuty43dfasdf', ERROR_PARAM_INVALID_FORMAT, "key");
        Interceptor::ensureNotEmpty($cash, ERROR_PARAM_IS_EMPTY, "cash");

        $info = AccountAnswerInterface::cash($cash);

        $this->render($info);
    }/*}}}*/
}
?>
