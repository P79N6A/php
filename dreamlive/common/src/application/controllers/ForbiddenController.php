<?php
class ForbiddenController extends BaseController
{
    public function forbiddenAction()
    {
        /* {{{封禁 */
        $relateid = trim(strip_tags($this->getParam("relateid", "")));
        $expire = intval($this->getParam("expire", 0));
        $reason = trim(strip_tags($this->getParam("reason")));
        $liveid = $this->getParam("liveid", 0);
        
        Interceptor::ensureNotEmpty($relateid, ERROR_PARAM_IS_EMPTY, "relateid");
        Interceptor::ensureNotEmpty($expire, ERROR_PARAM_IS_EMPTY, "expire");

        Forbidden::addForbidden($relateid, $expire, $reason, $liveid);

        $this->render();
    }/* }}} */

    public function unForbiddenAction()
    {
        $relateid = trim(strip_tags($this->getParam("relateid", "")));

        Interceptor::ensureNotEmpty($relateid, ERROR_PARAM_IS_EMPTY, "relateid");
        Forbidden::unForbidden($relateid);

        $this->render();
    }

    public function isForbiddenAction()
    {
        $userid = trim($this->getParam("uid"));
        Interceptor::ensureNotEmpty($userid, ERROR_PARAM_IS_EMPTY, "uid");
        $result = Forbidden::isForbidden($userid, Context::get("deviceid"));

        $this->render(
            array(
            "result" => $result
            )
        );
    }

    public function isForbiddenUsersAction()
    {
        $relateids = trim(strip_tags($this->getParam("relateids", "")));
        Interceptor::ensureNotFalse(preg_match("/^(\d+,?)+$/", $relateids) != 0, ERROR_PARAM_INVALID_FORMAT, "relateids($relateids)");

        $relateids = explode(",", $relateids);
        $relateids = array_slice($relateids, 0, 3000);

        $forbidden_info = Forbidden::isForbiddenUsers($relateids);

        $this->render($forbidden_info);
    }

    public function forbiddenMsgAction()
    {
        /* {{{封禁私信 */
        $relateid = trim(strip_tags($this->getParam("relateid", "")));
        
        Interceptor::ensureNotEmpty($relateid, ERROR_PARAM_IS_EMPTY, "relateid");

        ForbiddenMsg::addForbidden($relateid, 315360000);

        $this->render();
    }/* }}} */

    public function unForbiddenMsgAction()
    {
        $relateid = trim(strip_tags($this->getParam("relateid", "")));

        Interceptor::ensureNotEmpty($relateid, ERROR_PARAM_IS_EMPTY, "relateid");
        ForbiddenMsg::unForbidden($relateid);

        $this->render();
    }
}
?>
