<?php
class JournalController extends BaseController
{
    public function getGrapeInListAction()
    {
        $offset = $this->getParam("offset") ? (int) ($this->getParam("offset")) : 0;
        $num    = $this->getParam("num")    ? intval($this->getParam("num")) : 20;
        $userid = Context::get("userid");

        Interceptor::ensureNotFalse(is_numeric($userid) && $userid > 0, ERROR_PARAM_INVALID_FORMAT, "userid");

        list($list,$total,$offset,$more) = Journal::getJournalGrapeInList($userid, $num, $offset);

        $this->render(array('list' => $list, 'total' => $total, 'offset'=>$offset, 'more'=>$more));
    }
}