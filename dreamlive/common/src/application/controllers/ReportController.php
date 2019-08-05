<?php
class ReportController extends BaseController
{
    public function addAction()
    {
        /* {{{ */
        $uid = intval($this->getParam("uid", 0));
        $type = trim($this->getParam("type"));//user,live image video replay举报类型
        $reason = $this->getParam("reason") ? trim(strip_tags($this->getParam('reason'))) : "";
        $relateid = intval($this->getParam("relateid", 0));

        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");
        Interceptor::ensureNotFalse($type, ERROR_PARAM_INVALID_FORMAT, "type");
        Interceptor::ensureNotEmpty($reason, ERROR_PARAM_INVALID_FORMAT, "reason");

        
        include_once 'process_client/ProcessClient.php';
        
        $uid_array_users = Operation::getUidsArray();

        if (empty($uid_array_users) || !is_array($uid_array_users)) {
            $uid_array_users = [22938458,22670661,10006699,10000001,11196354,20896110,10016044,11174936,20085301,10000195,10000194,10000190,10011002,10002000,11010375,22917390,10058492,10000009,10190370,20185824,11126729,10114376,10899808,20106579,10000022,20183977,10263689,10153473,10006699,10016075,10211564,20103845,11215424,22893043,22842753,11104667,10006520,10193752,20052263,10030992,10899916,10000025,10155027,10053518,10183864,10178284,10000068,10073011,20019227,20149307,10205989,10026652,10027095,20178553,10097254,10196673,10026521,22827356,22906862,22982466,11220158,20523051,22982472,10899808,22986709,10899808,22986709,20058394,23001315,23001309,22824394,23002819,23001306,22989843,22986963,10899916];
        }
        
        
        if (in_array(Context::get("userid"), $uid_array_users)) {
            ProcessClient::getInstance("dream")->addTask(
                "forbidden_control", array(
                "seconds"     => 315360000,
                "uid"         => !empty($uid) ? $uid : $relateid,
                "reason"     => $reason,
                "sender"    => Context::get("userid")
                )
            );
        }
        
        $cache = Cache::getInstance("REDIS_CONF_USER");
        $key = "report_" .$type."_". Context::get("userid") . "_{$uid}". "_{$relateid}";
        Interceptor::ensureNotFalse($cache->get($key) != 1, ERROR_USER_REPORTED);
        
        
        ProcessClient::getInstance("dream")->addTask(
            "report", array(
            "relateid" => $relateid,
            "userid" => $uid,
            "type" => $type,
            "reporter" => Context::get("userid"),
            "reason" => $reason,
            )
        );
        
       
        
        $cache->set($key, 1, 7200);
        
        $this->render();
    } /* }}} */
}
?>
