<?php
class Game
{
    /**
     * @param  int $liveid 直播id
     * @return array $gameInfo 游戏列表
     */
    public static function getGameList()
    {
        $cache      = Cache::getInstance("REDIS_CONF_CACHE");
        $gameInfo   = json_decode($cache ->get('horseracing_gamelist'), true);
        if(empty($gameInfo)) {
            $game       = new DAOGame();
            $gameInfo   = $game->getGameInfos();
            $cache ->set('horseracing_gamelist', json_encode($gameInfo));
        }
        if($gameInfo) {
            foreach($gameInfo as &$value){
                $value['icon']          = Util::joinStaticDomain($value['icon']);
                $value['h5_url']        = Util::joinStaticDomain($value['h5_url']);
                $value['extend_s']      = json_decode($value['extends']);
            }
        }

        return $gameInfo;
    }
    /**
     * 获取游戏详情
     *
     * @param  int $gameid 游戏id
     * @return array $gameone 游戏信息
     */
    public static function getGameInfo($gameid)
    {
        $game           = new DAOGame();
        $gameone        = $game ->getGameInfo($gameid);
        $gameone['icon']= Util::joinStaticDomain($gameone['icon']);
        if($gameone['extends']) {
            $gameone['extend_s']     = json_decode($gameone['extends'], true);
        }

        //加抽奖列表
        if ($gameone['type']==DAOGame::TYPE_LOTTO) {
            $gameone['prize_list']=LottoPrize::getPrizeList();
        }else{
            $gameone['prize_list']=null;
        }
        
        return $gameone;
    }
    /*
     * 获取游戏状态
     * @param int $gameid 游戏id
     * */
    public static function getGameState($gameid)
    {
        $cache          = Cache::getInstance("REDIS_CONF_CACHE");
        $info           = json_decode($cache ->get('horseracing_round_'.$gameid), true);
        if(empty($info)) {
            $game           = new DAOGame();
            $gameone        = $game ->getGameInfo($gameid);
            Interceptor::ensureNotFalse(!empty($gameone), ERROR_BIZ_GAME_NOT_EXIST, "gameid");
            switch ($gameone['type']){
            case 1://星钻跑马场
                $horseracing_round              = new DAOHorseracingRound();
                $info                           = $horseracing_round ->getNewestInfo();
                break;
            case 2://星光跑马场
                $horseracing_round_star         = new DAOHorseracingRoundStar();
                $info                           = $horseracing_round_star ->getNewestInfo();
                break;
            }
            $cache ->set('horseracing_round_'.$gameid, json_encode($info));
        }

        $cache          = Cache::getInstance("REDIS_CONF_CACHE");
        $info           = json_decode($cache ->get('horseracing_round_'.$gameid), true);
        Interceptor::ensureEmpty(!$info, ERROR_BIZ_GAME_NOT_DO, "gameid");
        $userinfo               = User::getUserInfo($info['bankerid']);
        $info['nickname']      = $userinfo['nickname'];
        $info['avatar']        = $userinfo['avatar'];

        $info['extend_s']    = json_decode($info['extends'], true);
        $info['extend_s']['timeline_option']['start_time'] = strtotime($info['extend_s']['timeline_option']['start_time']);
        $info['extend_s']['timeline_option']['stake_time'] = $info['extend_s']['timeline_option']['start_time']+$info['extend_s']['timeline_option']['banker_time_span']+$info['extend_s']['timeline_option']['banker_to_stake_span'];
        $info['extend_s']['timeline_option']['run_time'] = $info['extend_s']['timeline_option']['stake_time']+$info['extend_s']['timeline_option']['stake_time_span']+$info['extend_s']['timeline_option']['stake_to_run_span'];
        unset($info['extends']);

        return $info;
    }

    private static function getGameByType($type)
    {
        $daoGame=new DAOGame();
        return $daoGame->getGameByType($type);
    }

    public static function getLottoGame()
    {
        return self::getGameByType(DAOGame::TYPE_LOTTO);
    }

    public static function getLottoState($uid)
    {
        $gameone=[];
        $gameone['lotto_is_free']=$uid>0&&LottoLog::isFree($uid)?1:0;
        return $gameone;
    }
}
