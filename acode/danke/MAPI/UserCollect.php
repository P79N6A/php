<?php

/**
 * 用户关注房源
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
class UserCollect extends MAPIBaseModel
{
    protected $table;


    public function setTable($user_id)
    {
        $index = abs(substr($user_id, - 3)) % 100;
        $this->table = "user_collect_".$index;

        return $this;
    }


    public function add($user_id, $room_id, $public_id, $rent_type, $price)
    {
        $collect = new self();
        $collect = $collect->setTable($user_id)->where('user_id', $user_id)->where('rent_type', $rent_type)->where('room_id', $room_id)->first();

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
        $collect->setTable($user_id);
        $collect->save();

        return $collect;
    }

    public function getList($user_id, $id, $page_size = 10)
    {
        $collect = new self();
        if (empty($id)) {
            //setTable($user_id)
            $user_collects = $collect->setTable($user_id)->orderBy('updated_at', 'desc')->offset(0)->limit($page_size)->get()->toArray();//setTable($user_id)
        } else {
            $user_collects = $collect->setTable($user_id)->orderBy('updated_at', 'desc')->offset(0)->where('id', '<', $id)->offset(0)->limit($page_size)->get()->toArray();
        }

        return $user_collects;
    }

    public function getPaginateList($user_id, $page_size = 10)
    {
        $collect = new self();
        $user_collects = $collect->setTable($user_id)->orderBy('updated_at', 'desc')->paginate($page_size)->toArray();
        return $user_collects;
    }

}