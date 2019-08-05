<?php
class BlockedController extends BaseController
{
    public function getBlockedAction()
    {
        /* {{{ 拉黑列表 */
        $userid = Context::get("userid");
        $offset = intval($this->getParam("offset", 0));
        $num     = min(intval($this->getParam("num", 50)), 100);
        Interceptor::ensureNotEmpty($userid, ERROR_PARAM_IS_EMPTY, "uid");

        $blocked = new Blocked();
        $users = $blocked->getBlockedUserInfo($userid, $offset, $num);
        
        $this->render(
            array(
            'users'     => $users ? $users : array(),
            'offset'     => $offset + $num,
            'more'         => $offset + $num < $blocked->getBlockedTotal($userid)
            )
        );
    }/* }}} */
    public function getBidsAction()
    {
        /* {{{ */
        $userid = Context::get("userid");
        Interceptor::ensureNotEmpty($userid, ERROR_PARAM_IS_EMPTY, "userid");
        
        $blocked = new Blocked();
        $bids = $blocked->getBlockedIds($userid);

        $this->render(
            array(
            "bids"     => $bids,
            "md5"     => md5(implode("|", $bids))
            )
        );
    }/* }}} */
    public function addAction()
    {
        /* {{{ 拉黑*/
        $userid = Context::get("userid");
        $bid = (int) $this->getParam("bid", 0);
        $liveid = (int) $this->getParam("liveid", 0);
        
        Interceptor::ensureNotEmpty($userid, ERROR_PARAM_IS_EMPTY, "userid");
        Interceptor::ensureNotFalse($bid > 0, ERROR_PARAM_INVALID_FORMAT, "bid");
        
        $blocked = new Blocked();
        Interceptor::ensureNotFalse($blocked->getBlockedTotal($userid) < Blocked::BLOCK_LIMIT, ERROR_BIZ_BLOCKED_TOO_MUCH);
        
        if (! $blocked->exists($userid, $bid)) {
            Blocked::addBlocked($userid, $bid, $liveid);
            Follow::cancelFollow($userid, $bid, "$userid blocked $bid");
            Follow::cancelFollow($bid, $bid, "$userid blocked $bid");
        }
        
        $bids = $blocked->getBlockedIds($userid);

        $md5 = md5(implode("|", $bids));
        
        $this->render(
            array(
            "md5" => $md5
            )
        );
    }/* }}} */
    public function cancelAction()
    {
        /* {{{取消拉黑 */
        $userid = Context::get("userid");
        $bid = (int) $this->getParam("bid", 0);
        $liveid = (int) $this->getParam("liveid", 0);
        
        Interceptor::ensureNotEmpty($userid, ERROR_PARAM_IS_EMPTY, "userid");
        Interceptor::ensureNotFalse($bid > 0, ERROR_PARAM_INVALID_FORMAT, "bid");
        
        Blocked::delBlocked($userid, $bid, $liveid);
        
        $blocked = new Blocked();
        $bids = $blocked->getBlockedIds($userid);
        
        $md5 = md5(implode("|", $bids));
        
        $this->render(
            array(
            "md5" => $md5
            )
        );
    }/* }}} */
    public function isBlockedAction()
    {
        /* {{{ */
        $uid = intval($this->getParam("uid"));
        $bid = intval($this->getParam("bid"));
        
        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");
        Interceptor::ensureNotFalse($bid > 0, ERROR_PARAM_INVALID_FORMAT, "bid");
        
        $this->render(
            array(
            "blocked" => Blocked::exists($uid, $bid)
            )
        );
    } /* }}} */
}
?>