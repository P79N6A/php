<?php
class GameRobotsStar
{
    /**
     * @return array $game_robots_info 游戏机器人列表
     */
    public static function getGameRobotsList()
    {
        $game_robots = new DAOGameRobotsStar();
        $game_robots_info = $game_robots->getGameRobotsAll();
       
        return $game_robots_info;
    }

    /**
     * 机器人抢庄
     */
    public static function robotsBanker($gameid, $amount)
    {
        //| 抽取一个uid
        //| 从系统帐户1007转入机器人帐户,  相应的钻 $amount. 
        //| 调用抢庄接口
        $game_robots = new DAOGameRobotsStar();
        $game_robots_info = $game_robots->getGameRobotsOne();
        $uid = $game_robots_info['uid'];
        return Horseracing::starBanker($gameid, $amount, 0, $uid, 1);
        
    }
    
}
