<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/18
 * Time: 15:09
 */
class LottoController extends BaseController
{
    public function getPrizeAction()
    {
        $uid=Context::get('userid', 0);
        $liveid=$this->getParam('liveid', 0);
        $type=$this->getParam('type', 0);
        Interceptor::ensureNotFalse($uid>0, ERROR_USER_NOT_LOGIN, 'uid');
        Interceptor::ensureNotFalse($type>0, ERROR_PARAM_INVALID_FORMAT, 'type');

        $this->render(LottoPrize::drawPrize($uid, $type, $liveid));
    }

    public function getLottoLogAction()
    {
        $uid=Context::get('userid', 0);
        $page=$this->getParam('page', 1);
        Interceptor::ensureNotFalse($uid>0, ERROR_USER_NOT_LOGIN, 'uid');
        Interceptor::ensureNotFalse($page>0, ERROR_PARAM_IS_EMPTY, 'page');

        $this->render(LottoLog::getListByUid($uid, $page));
    }

    public function editPrizeListAction()
    {
        $data=$this->getParam("prize_list", "");
        Interceptor::ensureNotEmpty($data, ERROR_PARAM_IS_EMPTY, "prize_list");
        $this->render(LottoPrize::editPrize($data));
    }
    public function modPrizeLogAction()
    {
        $id = $this->getParam('id', '');
        $prizeid =$this->getparam('prizeid', '');

        Interceptor::ensureNotEmpty($id>0, ERROR_PARAM_IS_EMPTY, "id");
        Interceptor::ensureNotEmpty($prizeid>0, ERROR_PARAM_IS_EMPTY, "prizeid");

        $this->render(LottoLog::modPrizeLog($id, $prizeid));
    }
}