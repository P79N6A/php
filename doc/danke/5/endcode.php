<?php
include_once("./../4/code/BaseLogic.php");
include_once("./../4/code/Optimus.php");
include_once("./../4/code/ForgePublicId.php");
$public = new ForgePublicId(144541);
$encode_id = $public->publicId();
$decode_id = \ForgePublicId::optimusDecode(1515403537);
var_dump($encode_id);

echo urldecode("%E5%8C%97%E4%BA%AC%E5%B8%82");

var_dump($decode_id);exit;

/*
public function findNoEndExecutionCustomerContractByHumanId($id)
    {
        return WithCustomer::where('status', '已签约')
            ->where('customer_id', $id)
            ->where('stage', '!=', '执行结束')
            ->orderBy('stage', 'desc')
            ->orderBy('id', 'desc')
            ->first();
    }

http://172.18.130.62:8182/v1/room/getPaymentList?room_id=1035357044&rent_type=2&timestamp=1559195911443&_dbg=54d3322ac5623b0241e7f20bc578b105

redis-cli -h 172.18.130.5 -p 6379 --scan --pattern 'redis_xiaoqu_md5key_*' | xargs redis-cli -h 172.18.130.5 -p 6379 del


-a 密码 -h host -p 端口号 del

get l5:booking_lock_room_
 */