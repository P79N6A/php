<?php

/**
 * 房源下的用户
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
class RoomCollect extends MAPIBaseModel
{
    protected $table;

    public function setTable($room_id)
    {
        $index = abs(substr($room_id, - 3)) % 100;
        $this->table = "room_collect_".$index;

        return $this;
    }

    public function add($user_id, $room_id, $public_id, $rent_type, $price)
    {
        $collect = new self();
        $collect = $collect->setTable($room_id)->where('room_id', $room_id)->where('rent_type', $rent_type)->where('user_id', $user_id)->first();

        //$collect = $collect->setTable(1)->where('user_id', 1)->where('room_id', 1)->first();
        if (!$collect) {
            $collect = new self();
            $collect->user_id   = $user_id;
            $collect->room_id   = $room_id;
            $collect->public_id = $public_id;
            $collect->price     = $price;
        } else {
            $collect->updated_at = \Carbon\Carbon::now();
        }
        $collect->setTable($room_id);
        $collect->save();

        return $collect;
    }

    public function getPaginateList($user_id, $page_size = 10)
    {
        $collect = new self();
        $user_collects = $collect->setTable($user_id)->orderBy('updated_at', 'desc')->paginate($page_size)->toArray();
        return $user_collects;
    }
}