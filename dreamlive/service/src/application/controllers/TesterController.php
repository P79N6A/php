<?php
class TesterController extends BaseController
{
    public function transferAction()
    {
        $uid         = $this->getParam('uid');
        $key         = $this->getParam('key');
        $deposit_num = (int)$this->getParam('deposit_num');
        $operater    = $this->getParam('adminid');
        $remark      = $this->getParam('remark');

        Interceptor::ensureFalse($key != 'yjjertert345krtwerwertwert', ERROR_PARAM_INVALID_FORMAT, "key");
        Interceptor::ensureNotEmpty($remark, ERROR_PARAM_IS_EMPTY, 'remark');
        Interceptor::ensureNotEmpty($operater, ERROR_PARAM_IS_EMPTY, 'operater');

        $remark = $remark . ",操作:$operater.";

        $result = Tester::transfer($uid, $deposit_num, $remark);

        $this->render($result);
    }
}
