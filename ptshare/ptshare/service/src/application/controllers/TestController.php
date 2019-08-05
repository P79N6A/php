<?php
class TestController extends BaseController
{
    public function IndexAction(){
        for($i=0;$i<100;$i++){
            $sql1 = "DROP TABLE IF EXISTS `user_task_award_".$i."`;";
            $sql2 = "
                    CREATE TABLE `user_task_award_".$i."` (
                      `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类id',
                      `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
                      `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
                      `type` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '1,经验;2,葡萄,4,其他',
                      `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
                      `totalaward` varchar(2000) NOT NULL DEFAULT '' COMMENT '累计奖励',
                      `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
                      `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
                      PRIMARY KEY (`id`),
                      KEY `idx_uid` (`uid`) USING BTREE,
                      KEY `idx_taskid` (`taskid`) USING BTREE
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ";
            echo $sql1;echo "\n";
            echo $sql2;echo "\n";
        }
        exit;
    }

//    public function testTimeAction()
//    {
//        $time = time()-86400;
//        echo Util::timeTrans(date('Y-m-d H:i:s', $time));
//
//    }
//
//    public function checkNumAction()
//    {
//        $id = IDGEN::generate(IDGEN::SELLID);
//        file_put_contents('test.log',$id."\n", FILE_APPEND);
//        echo $id;
//        $id = Util::getUniqueSellId();
//        file_put_contents('test2.log',$id."\n", FILE_APPEND);
//        echo $id;
//    }
//
//    public function checkRequestAction()
//    {
//        $id = $this->getParam('id');
//        $startTime = time();
//        $daoSell = new DAOSell();
//        $sql = "select * from sell";
//        $res = $daoSell->getAll($sql);
//        $endTime = time();
//        file_put_contents('check_request.log', $endTime-$startTime."\n", FILE_APPEND);
//        $this->render($res);
//    }

}