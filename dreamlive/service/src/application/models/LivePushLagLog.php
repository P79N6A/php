<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/14
 * Time: 14:57
 */
class LivePushLagLog
{
    const MIN_FPS=9;
    const MIN_LFPS=9;

    public static function addLog($uid,$fre=5)
    {
        $user=User::getUserInfo($uid);
        $nickname=$user['nickname'];
        unset($user);
        $daoLivePushLagLog=new DAOLivePushLagLog();
        $daoStream=new DAOStream();
        $ps=$daoStream->getLastSomePointByUser($uid, $fre);

        $avg_fps=0;
        $avg_bps=0;
        $avg_plr=0;
        $avg_lfps=0;
        $newest_liveid=0;
        $result=array();

        $is_same_liveid=true;
        $is_same_position=true;
        $is_cdn_dispatch=false;
        $is_same_mobile=true;
        $is_newest_app=true;
        $is_same_network=true;

        $is_device_poor=false;
        $is_network_poor=false;

        $t_liveid=0;
        $t_position="";
        $t_mobile="";
        $t_network="";

        $new_platform="";
        $new=array();



        $n=0;
        foreach ($ps as $i){
            if (!$n) {
                $newest_liveid=$i['liveid'];
                $t_liveid=$i['liveid'];
                $t_position=$i['position'];
                $t_mobile=$i['deviceid'];
                $t_network=$i['network'];
                $new=$i;
            }

            if ($t_network!=$i['network']) {
                $is_same_network=false;
            }

            if ($t_mobile!=$i['deviceid']) {
                $is_same_mobile=false;
            }

            if ($i['province'].":".$i['city']!=$i['o_province'].":".$i['city']) {
                $is_cdn_dispatch=true;
            }

            if ($t_liveid!=$i['liveid']) {
                $is_same_liveid=false;
            }
            if ($t_position!=$i['location']) {
                $is_same_position=false;
            }

            $avg_fps+=$i['fps'];
            $avg_bps+=$i['bps'];
            $avg_plr+=$i['flr'];
            $avg_lfps+=$i['localbps'];

            $n++;
        }
        $avg_fps=intval($avg_fps/$fre);
        $avg_bps=intval($avg_bps/$fre);
        $avg_plr=intval($avg_plr/$fre);
        $avg_lfps=intval($avg_lfps/$fre);

        //0-9-13-
        if ($avg_fps <self::MIN_FPS) {//低于9真卡顿
            if ($avg_lfps<=self::MIN_LFPS) {
                $is_device_poor=true;
            }else{
                if($avg_plr>0) {
                    $is_network_poor=true;
                }
            }

            $result=array(
                'is_same_liveid'=>$is_same_liveid,
                'is_same_position'=>$is_same_position,
                'is_cdn_dispatch'=>$is_cdn_dispatch,
                'is_same_mobile'=>$is_same_mobile,
                'is_newest_app'=>'',
                'is_same_mobile'=>$is_same_mobile,
                'is_same_network'=>$is_same_network,
                'is_device_poor'=>$is_device_poor,
                'is_network_poor'=>$is_network_poor,
                'new'=>$new,
            );
            $daoLivePushLagLog->add($uid, $nickname, $newest_liveid, $avg_fps, $avg_bps, $avg_plr, $avg_lfps, $result, $ps);
            $msg="主播卡顿报警：用户={$nickname}({$uid}--{$newest_liveid});\n";
            $msg.="帧率={$avg_fps};码率={$avg_bps};丢包率={$avg_plr};本地帧率={$avg_lfps};\n";
            $msg.="原因=".($is_device_poor?"设备差":($is_network_poor?"网络差":"其它原因")).";\n";
            $msg.="建议=暂无;\n";
            //$msg.="详情=".json_encode($new).";\n";
            Util::sendWarnToDingDing($msg);
        }
    }
}