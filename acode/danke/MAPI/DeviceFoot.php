<?php

/**
 * 未登录按设备历史足迹
 *
 * @property integer $id
 * @property integer $device_id
 * @property integer $room_id
 * @property integer $public_id
 * @property integer $rent_type
 * @property integer $plateform
 * @property integer $version
 * @property integer $price
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class DeviceFoot extends MAPIBaseModel
{
    protected $table;

    public function setTable($deviceid)
    {
        //$index = abs(substr($user_id, - 3)) % 100;
        $this->table = "device_fooot";

        return $this;
    }

    public function add($deviceid, $room_id, $public_id, $rent_type, $price, $plateform, $version)
    {
        $deviceFoot = new self();
        $deviceFoot = $deviceFoot->setTable($deviceid)->where('deviceid', $deviceid)->where('rent_type', $rent_type)->where('room_id', $room_id)->first();

        //$collect = $collect->setTable(1)->where('user_id', 1)->where('room_id', 1)->first();
        if (!$deviceFoot) {
            $deviceFoot = new self();
            $deviceFoot->device_id = $deviceid;
            $deviceFoot->room_id   = $room_id;
            $deviceFoot->public_id = $public_id;
            $deviceFoot->price     = $price;
            $deviceFoot->plateform = $plateform;
            $deviceFoot->version   = $version;
        } else {
            $deviceFoot->updated_at = \Carbon\Carbon::now();
        }
        $deviceFoot->setTable($deviceid);
        $deviceFoot->save();

        return $deviceFoot;
    }

    public function getList($user_id, $id, $page_size = 10)
    {
        $userFoot = new self();
        if (empty($id)) {
            $user_collects = $userFoot->setTable($user_id)->orderBy('updated_at')->paginate($page_size)->toArray();//setTable($user_id)
        } else {
            $user_collects = $userFoot->setTable($user_id)->orderBy('updated_at')->where('id', '<', $id)->paginate($page_size)->toArray();
        }

        return $user_collects;
    }

    public function getPaginateList($user_id, $page_size = 10)
    {
        $userFoot = new self();
        $user_foot = $userFoot->setTable($user_id)->orderBy('updated_at', 'desc')->paginate($page_size)->toArray();
        return $user_foot;
    }
}