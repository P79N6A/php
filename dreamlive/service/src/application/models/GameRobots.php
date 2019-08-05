<?php
class GameRobots
{
    /**
     * @return array $game_robots_info 游戏机器人列表
     */
    public static function getGameRobotsList()
    {
        $game_robots = new DAOGameRobots();
        $game_robots_info = $game_robots->getGameRobotsAll();
       
        return $game_robots_info;
    }

    /**
     * 机器人抢庄
     */
    public static function robotsBanker($gameid, $amount)
    {
        $round      = new DAOHorseracingRound();
        $round_info = $round -> getNewestInfo();
        //| 抽取一个uid
        //| 从系统帐户1007转入机器人帐户,  相应的钻 $amount. 
        //| 调用抢庄接口
        $game_robots = new DAOGameRobots();
        $game_robots_info = $game_robots->getGameRobotsOne($amount);
        $uid = $game_robots_info['uid'];
        return Horseracing::banker($gameid, $amount, 0, $uid, 1);
        
    }
    
}
