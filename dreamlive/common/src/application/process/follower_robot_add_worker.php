<?php
/**
 * 临时给用户增加机器人粉丝
 *
 * @author xubaoguo
 */
class followerRobotAddWorker
{
    public static function execute($value)
    {

        $userid         = $value["params"]["userid"];
        $adminid           = $value["params"]["adminid"];
        $adminname         = $value["params"]["adminname"];
        $num             = $value["params"]["num"];
        $expired         = $value["params"]["expired"];
        $action            = $value["params"]["action"];

        if ($action == 'delete') {
            $dao_follower_robot = new DAOFollowerRobot();
            $dao_follower_robot_log = new DAOFollowerRobotLog();

            $info = $dao_follower_robot->getRobot($userid);

            $all_robot = $dao_follower_robot_log->getRobotList($userid);

            $followers = array();
            foreach ($all_robot as $row) {
                if (Follow::cancelFollow($row['fid'], $userid)) {
                    $followers[] = $row['fid'];
                }
            }

            $dao_follower_robot_log->deleteRecord($userid);
            $dao_follower_robot->updateR($info['id']);

        } else {
            if ($num > 3000) {
                $fakeNum = $num - 3000;
                $num = 3000;
            }

            $cache = Cache::getInstance("REDIS_CONF_USER");
            $robot_total = $cache->zCard("robots");

            $dao_follower_robot = new DAOFollowerRobot();
            $dao_follower_robot_log = new DAOFollowerRobotLog();

            $robots = array();
            for($i = 0; $i < $num; $i++) {
                $offset = rand(0, $robot_total - 1);
                $array = $cache->zRevRange("robots", $offset, $offset+1);
                $robots[] = $array[rand(0, 1)];
            }

            $followers = array();
            foreach ($robots AS $fid) {

                if (Follow::addFollow($fid, $userid, 0)) {
                    $followers[] = $fid;
                }
            }

            $total_followers = Follow::countFollowers($userid, true);
            Counter::set(Counter::COUNTER_TYPE_FOLLOWERS, $userid, $total_followers+$fakeNum);

            $dao_follower_robot->add($userid, count($followers)+$fakeNum, $adminid, $adminname, $expired);

            $dao_follower_robot_log->add($userid, $followers);
        }




        return true;
    }
}

set_time_limit(0);
ini_set('memory_limit', '1G');
ini_set("display_errors", "On");
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
$ROOT_PATH = dirname(dirname(dirname(dirname(__FILE__))));
$LOAD_PATH = array(
        $ROOT_PATH."/src/www",
        $ROOT_PATH."/config",
        $ROOT_PATH."/src/application/controllers",
        $ROOT_PATH."/src/application/models",
        $ROOT_PATH."/src/application/models/libs",
        $ROOT_PATH."/src/application/models/dao"
);
set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $LOAD_PATH));

function __autoload($classname)
{
    $classname = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
    include_once "$classname.php";
}

require $ROOT_PATH."/config/server_conf.php";

class DAOFollowerRobot extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PASSPORT");
        $this->setTableName("follower_robot");
    }

    public function add($userid, $num, $adminid, $adminname, $expired)
    {
        $info = array(
        "uid"        => $userid,
        "num"        => $num,
        "adminname" => $adminname,
        "adminid"    => $adminid,
        "expired"    => $expired,
        "addtime"    => date("Y-m-d H:i:s")
        );

        return $this->insert($this->getTableName(), $info);
    }

    public function getRobot($userid)
    {
        $sql = "select * from {$this->getTableName()} where uid = ? limit 1";

        return $this->getRow($sql, $userid);
    }
    public function updateR($id)
    {
        $info = [
        'deleted' => 'Y',
        'modtime' => date("Y-m-d H:i:s"),
        ];
        return $this->update($this->getTableName(), $info, "id=?", $id);
    }
}


class DAOFollowerRobotLog extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PASSPORT");
        $this->setTableName("follower_robot_log");
    }

    public function add($userid, $robots)
    {
        $inertsql = ' insert into follower_robot_log (uid,fid,addtime) values ';
        $addtime = date('Y-m-d H:i:s');
        if (!empty($robots)) {
            foreach ($robots as $robot) {
                $inertsql .= "('{$userid}', '{$robot}', '{$addtime}'),";
            }
        }

        return $this->Execute(trim($inertsql, ','));
    }

    public function getRobotList($userid)
    {
        $sql = "select uid,fid from follower_robot_log where 1=1 and uid=? ";

        return $this->getAll($sql, $userid);
    }


    public function deleteRecord($userid)
    {
        $sql = "delete from {$this->getTableName()} where uid=? ";

        return $this->Execute($sql, $userid);
    }
}
require_once 'process_client/ProcessClient.php';

try {
    $process = new ProcessClient("dream");
    $process->addWorker("follower_robot_add",  array("followerRobotAddWorker", "execute"),  1, 2000);
    $process->run();
} catch (Exception $e) {
    ;
}
?>