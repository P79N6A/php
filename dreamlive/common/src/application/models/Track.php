<?php

/**
 * Created by PhpStorm.
 * User: yyc
 * Date: 2017/7/24
 * Time: 10:15
 */
class Track
{
    public static function showTrackGift($sender, $receiver, $giftid, $num = 1)
    {
        try{
            $type=DAOTrack::TRACK_TYPE_GIFT;
            $gift = new Gift();
            $gift_info = $gift->getGiftInfo($giftid);
            $amount = isset($gift_info['price']) ? $gift_info['price'] * $num : 0;
            $gift_name = isset($gift_info['name']) ? $gift_info['name'] : "";
            $image = isset($gift_info['image']) ? $gift_info['image'] : "";
            $gift_num = $num;
            $track_dao = new DAOTrack();
            $track = $track_dao->getTrackEffectByType($type, $amount);
            if ($track) {
                $user = new User();
                $sender_info = $user->getUserInfo($sender);
                //$sender_name = isset($sender_info) ? $sender_info['nickname'] : "";
                $receiver_info = $user->getUserInfo($receiver);
                //$receiver_name = isset($receiver_info) ? $receiver_info['nickname'] : "";
                $ext = array(
                    'type'=>$type,
                    //'liveid'=>0,
                    'userid'=>$receiver_info['uid'],
                    'nickname'=>$receiver_info['nickname'],
                    'op_nickname'=>$sender_info['nickname'],
                    'op_avatar'=>$sender_info['avatar'],
                    'op_userid'=>$sender_info['uid'],
                    'op_level'=>intval($sender_info['level']),
                    'op_vip'=>intval($sender_info['vip']),
                    'msg' => $track['msg'],
                    'num' => $gift_num,
                    'gift_name' => $gift_name,
                    'image' => $image,
                );
                Messenger::sendTrackAllGift($ext);
                Messenger::sendTrackAll(
                    array(
                    'sender'=>$sender,
                    'receiver'=>$receiver,
                    'type'=>$type,
                    'effect'=>$track['effect'],
                    'msg'=>$sender_info['nickname']."送给了".$receiver_info['nickname'].",".$gift_info['name']."{image}*".$num.",大家快去围观吧",
                    'image'=>$image,
                    )
                );//兼容老版本
            }
        }catch (Exception $e){

        }
    }

    public static function showTrackGuard($sender, $receiver, $id)
    {
        try{
            $type=DAOTrack::TRACK_TYPE_GUARD;
            $num=1;
            $guard = UserGuard::$guard;
            $guard_info = isset($guard[$id]) ? $guard[$id] : [];
            $amount = isset($guard_info['price']) ? $guard_info['price'] * $num : 0;
            $guard_name=isset($guard_info['title'])?$guard_info['title']:"";
            $image = isset($guard_info['image']) ? $guard_info['image'] : "";
            $track_dao = new DAOTrack();
            $track = $track_dao->getTrackEffectByType($type, $amount);
            if ($track) {
                $user = new User();
                $sender_info = $user->getUserInfo($sender);
                $sender_name = isset($sender_info) ? $sender_info['nickname'] : "";
                $receiver_info = $user->getUserInfo($receiver);
                $receiver_name = isset($receiver_info) ? $receiver_info['nickname'] : "";
                $ext = array(
                    'type'=>$type,
                    //'liveid'=>0,
                    'userid'=>$receiver_info['uid'],
                    'nickname'=>$receiver_info['nickname'],
                    'op_nickname'=>$sender_info['nickname'],
                    'op_avatar'=>$sender_info['avatar'],
                    'op_userid'=>$sender_info['uid'],
                    'op_level'=>intval($sender_info['level']),
                    'op_vip'=>intval($sender_info['vip']),
                    'msg' => $track['msg'],
                    'image' => $image,
                    'guard_type'=>$guard_info['type'],
                );
                Messenger::sendTrackAllGuard($ext);
                Messenger::sendTrackAll(
                    array(
                    'sender'=>$sender,
                    'receiver'=>$receiver,
                    'type'=>$type,
                    'effect'=>$track['effect'],
                    'msg'=>$sender_info['nickname']."送给了".$receiver_info['nickname'].",".$guard_name."{image},大家快去围观吧",
                    'image'=>$image,
                    )
                );//兼容老版本
            }
        }catch (Exception $e){

        }
    }

    public static function showTrackLotto($uid,array $prize)
    {
        try{
            $type=DAOTrack::TRACK_TYPE_LOTTO;
            $track_dao = new DAOTrack();
            $track = $track_dao->getTrackEffectByType($type);
            if ($track) {
                $user = new User();
                $sender_info = $user->getUserInfo($uid);
                $sender_name = isset($sender_info) ? $sender_info['nickname'] : "";

                $ext = array(
                    'type'=>$type,
                    'nickname' => $sender_name,
                    'vip' => intval($sender_info['vip']),
                    'level' => intval($sender_info['level']),
                    'msg' => $track['msg'],
                    'game_name' => '抽奖游戏',
                    'prize_name' => isset($prize['name'])?$prize['name']:'',
                    'num' =>isset($prize['num'])?$prize['num']:1,
                    'unit' => DAOLottoPrize::getUnitByType($prize['type']),
                );
                Messenger::sendTrackAllLotto($ext);
                Messenger::sendTrackAll(
                    array(
                    'sender'=>Account::ACTIVE_ACCOUNT1300,
                    'receiver'=>$uid,
                    'type'=>1,
                    'effect'=>2,
                    'msg'=>str_replace("{user}", $sender_name, isset($prize['notice'])?$prize['notice']:""),
                    'image'=>'a',
                    )
                );//兼容老版本
            }
        }catch (Exception $e){

        }
    }

    public static function showTrackHorse($uid,$num=1)
    {
        try{
            $type=DAOTrack::TRACK_TYPE_LOTTO;
            $track_dao = new DAOTrack();
            $track = $track_dao->getTrackEffectByType($type);
            if ($track) {
                $user = new User();
                $sender_info = $user->getUserInfo($uid);
                $sender_name = isset($sender_info) ? $sender_info['nickname'] : "";

                $ext = array(
                    'type'=>$type,
                    'nickname' => $sender_name,
                    'vip' => intval($sender_info['vip']),
                    'level' => intval($sender_info['level']),
                    'msg' => $track['msg'],
                    'game_name' => '飞人大赛',
                    'prize_name' => '星钻',
                    'num' =>$num,
                    'unit' => '个',
                );
                Messenger::sendTrackAllLotto($ext);
            }
        }catch (Exception $e){

        }
    }

    public static function showTrackDefault($msg='',$img='')
    {
        try{
            $type=DAOTrack::TRACK_TYPE_DEFAULT;
            $track_dao = new DAOTrack();
            $track = $track_dao->getTrackEffectByType($type);
            if ($track) {
                $str=!empty($msg)?$msg:$track['msg'];
                $str.="{image}";
                $ext = array(
                    'type'=>$type,
                    'msg' => $str,
                    'image'=>$img,
                );
                Messenger::sendTrackAllDefault($ext);
            }
        }catch (Exception $e){

        }
    }
}