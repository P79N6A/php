<?php
class AntiSpamClient
{
    private static $_policy_conf = array(
    "chat"=>array(
                 array("name"=>"Contact"),
                array("name"=>"Repeat", "options"=>array("min"=>20, "max"=>PHP_INT_MAX, "interval"=>60, "frequency"=>3)),
                array("name"=>"Repeat", "options"=>array("min"=>3, "max"=>19, "interval"=>60, "frequency"=>10)),
                 array("name"=>"Length", "options"=>array("min"=>0, "max"=>300)),
                array("name"=>"Worth", "options"=>array("ratio"=>0.5)),
                array("name"=>"Keyword"),
    ),
    "message"=>array(
                 array("name"=>"Contact"),
                array("name"=>"Repeat", "options"=>array("min"=>20, "max"=>PHP_INT_MAX, "interval"=>60, "frequency"=>3)),
                array("name"=>"Repeat", "options"=>array("min"=>3, "max"=>19, "interval"=>60, "frequency"=>10)),
                 array("name"=>"Length", "options"=>array("min"=>0, "max"=>300)),
                 array("name"=>"Worth", "options"=>array("ratio"=>0.5)),
                array("name"=>"Keyword"),
            ),
    "nickname"=>array(
                 array("name"=>"Contact"),
                array("name"=>"Repeat", "options"=>array("min"=>20, "max"=>PHP_INT_MAX, "interval"=>60, "frequency"=>3)),
                array("name"=>"Repeat", "options"=>array("min"=>3, "max"=>19, "interval"=>60, "frequency"=>10)),
                 array("name"=>"Length", "options"=>array("min"=>0, "max"=>300)),
                 //array("name"=>"Worth", "options"=>array("ratio"=>0.5)),
                array("name"=>"Keyword"),
    ),
    );

    public static function isDirty($type, $content, $relateid = 0, $uid = 0, $traceid = "", $user_ip = "", $deviceid = "")
    {
        if (empty($content)) {
            return false;
        }
        if(!self::filterUrl($content)) {
            return false;
        }

        if(!isset(self::$_policy_conf[$type])) {
            return false;
        }

        foreach (self::$_policy_conf[$type] as $policy_conf) {
            $policy = $policy_conf["name"];
            include_once "policy/$policy.php";

            $obj_policy = new $policy();
            $obj_policy->setType($type);

            if(isset($policy_conf["options"])) {
                $obj_policy->setOptions($policy_conf["options"]);
            }

            if($obj_policy->isDirty($content)) {

                if ($policy == 'Repeat' && $type == 'chat') {

                    $send_gift_total = (int) Counter::get(Counter::COUNTER_TYPE_PAYMENT_SEND_GIFT, $uid);
                    $receive_gift_total = (int) Counter::get(Counter::COUNTER_TYPE_PAYMENT_RECEIVE_GIFT, $uid);
                    if (!($send_gift_total >= 50 || $receive_gift_total >= 10)) {
                        $cache = $obj_policy->getCache();
                        $key = "room_slience_{$relateid}";
                        $cache->sAdd($key, $uid);
                        $cache->expire($key, 1200);

                        $dao = new DAOChat($relateid);
                        $dao->addMessage($relateid, $uid, 7, $content, '1分钟发送5次重复内容');
                    }
                    
                }

                include_once 'process_client/ProcessClient.php';
                $antispam_info = array(
                "sender"     => $uid,
                "relateid"     => $relateid,
                "content"    => $content,
                "type"        => $type,
                "ip"        => $user_ip,
                "deviceid"    => $deviceid,
                "policy"    => $policy,
                "addtime"    => date("Y-m-d H:i:s"),
                    "traceid"   => $traceid
                );
                ProcessClient::getInstance("dream")->addTask("antispam_gather", $antispam_info);
                return true;
            }
        }

        return false;
    }

    public static function filterUrl($content)
    {
        $key = 'dreamlive.com';
        $regex = '@(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))@';

        if(!preg_match($regex, $content)) {
            return true;
        }

        if(strpos($content, $key)>0) {
            return false;
        }

        return true;
    }
}

//var_dump(AntiSpamClient::isDirty("chat","看~美~女~ 逼~裸~秀~加~微’ee77h7  午夜福利"));
?>
