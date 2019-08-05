<?php
class ExpressController extends BaseController
{
    public function __construct()
    {
    }
    public function notifyAction()
    {
        header('Content-Type: application/json; charset=utf-8');
        header("Server: nginx/1.2.3");

        $source = $_GET['source'];
        Interceptor::ensureNotFalse(in_array($source,[Express::KUAIDIYIBAI]), ERROR_PARAM_IS_EMPTY, 'source');

        $return = Express::notify($source);

        print $return;
        exit();
    }
    // 快递揽件
    public function adminSetDeliverAction()
    {
        $orderid = $this->getParam('orderid');
        Interceptor::ensureNotEmpty($orderid, ERROR_PARAM_INVALID_FORMAT, 'orderid');

        Express::setStatusTook($orderid);

        $this->render();
    }
    // 用户签收
    public function adminSetReceiveAction()
    {
        $orderid = $this->getParam('orderid');
        Interceptor::ensureNotEmpty($orderid, ERROR_PARAM_INVALID_FORMAT, 'orderid');

        Express::setStatusSingin($orderid);

        $this->render();
    }

    // 下单
    public function adminSetSentdownAction()
    {
        $orderid = $this->getParam('orderid');
        $company = $this->getParam('company');
        $number  = $this->getParam('number');
        Interceptor::ensureNotEmpty($orderid, ERROR_PARAM_INVALID_FORMAT, 'orderid');
        Interceptor::ensureNotEmpty($company, ERROR_PARAM_INVALID_FORMAT, 'company');
        Interceptor::ensureNotEmpty($number, ERROR_PARAM_INVALID_FORMAT, 'number');

        Express::setStatusSentdown($orderid, $company, $number, '');
        // Express::poll();

        $this->render();
    }

    //快递查询
    public function adminQueryAction()
    {
        $orderid = $this->getParam('orderid');
        Interceptor::ensureNotEmpty($orderid, ERROR_PARAM_INVALID_FORMAT, 'orderid');
        $dao = new DAOExpress();
        $orderInfo = $dao->getExpressInfoByOrderid($orderid);

        $source  = $orderInfo['source'];
        $company = $orderInfo['company'];
        $number  = $orderInfo['number'];
        Interceptor::ensureNotEmpty($company, ERROR_PARAM_INVALID_FORMAT, 'company');
        Interceptor::ensureNotEmpty($number, ERROR_PARAM_INVALID_FORMAT, 'number');
        $data = Express::query($source, $company, $number);

        if($data['data']){
            Express::setContent($orderid, json_encode($data['data']));
        }

        $this->render(['data'=>$data['data']]);
    }

}
?>