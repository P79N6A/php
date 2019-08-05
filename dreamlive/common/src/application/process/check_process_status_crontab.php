<?php
//批量启动worker
//ssh dream@10.10.10.107
//DL@#2Ni16P+@TvH^ML0D
//ps -ef|grep clean|grep -v grep|cut -c 9-15|xargs kill -9
//ps -ef|grep finish_match|grep -v grep|cut -c 9-15|xargs kill -9
//ps -ef|grep live_receive_audience_worker.php|grep -v grep|cut -c 9-15|xargs kill -9
//*/5 * * * * php /home/dream/codebase/service/src/application/process/check_process_status_crontab.php
$path = "/home/dream/codebase/service/src/application/process";
//

$admin_path = "/home/dream/codebase/admin/src/application/process/receive";
if (empty($argv[1])) {
    exit("*****\n*****\n*****\n请输入参数:admin|front|both,admin代表后台，front代表前台,both代表前后台\n*****\n*****\n*****\n");
}
/**
 * 1、直播机器人4个
 * 2、直播提醒3个
 * 3、排行榜1个
 * 4、关注和取消关注各一个
 */
$workers = array(
        'live_robot_distribute_worker.php',
        'live_robot_join_chatroom_worker.php',
        'live_robot_quit_chatroom_worker.php',
        'live_robot_praise_chatroom_worker.php',
        'live_start_distribute_worker.php',
        'live_start_push_worker.php',
        'live_start_worker.php',
        'counter_sync_db_worker.php',
        'live_ranking_generate_worker.php',
        'followings_decrease_newsfeeds_worker.php',
        'followings_increase_newsfeeds_worker.php'
);

$admin_workers = array(
        //'liveMonitorWorker.php',//直播监控
        'liveStartBroadcastWorker.php',//直播提醒
        'liveStartPushWorker.php',//直播提醒
);
if ($argv[1] == 'front' || $argv[1] == 'both') {
    foreach ($workers as $entry) {
        $filename = $path . DIRECTORY_SEPARATOR . $entry;
        $command  = "/usr/local/php/bin/php $filename restart";
        exec($command, $output);
        print_r($output);
    }
}

if ($argv[1] == 'admin' || $argv[1] == 'both') {
    //admin worker
    foreach ($admin_workers as $entry) {
        $filename = $admin_path . DIRECTORY_SEPARATOR . $entry;
        $command  = "/usr/local/php/bin/php $filename restart";
        exec($command, $output);
        print_r($output);
    }
}
?>
