<?php

/**
 * 用户历史足迹
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $room_id
 * @property integer $public_id
 * @property integer $rent_type
 * @property integer $price
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class UserFoot extends MAPIBaseModel
{
    protected $table;

    public function setTable($user_id)
    {
        $index = abs(substr($user_id, - 3)) % 100;
        $this->table = "user_foot_".$index;

        return $this;
    }

    public function add($user_id, $room_id, $public_id, $rent_type, $price)
    {
        $userFoot = new self();
        $userFoot = $userFoot->setTable($user_id)->where('user_id', $user_id)->where('rent_type', $rent_type)->where('room_id', $room_id)->first();

        //$collect = $collect->setTable(1)->where('user_id', 1)->where('room_id', 1)->first();
        if (!$userFoot) {
            $userFoot = new self();
            $userFoot->user_id   = $user_id;
            $userFoot->room_id   = $room_id;
            $userFoot->public_id = $public_id;
            $userFoot->price     = $price;
        } else {
            $userFoot->updated_at = \Carbon\Carbon::now();
        }
        $userFoot->setTable($user_id);
        $userFoot->save();

        return $userFoot;
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