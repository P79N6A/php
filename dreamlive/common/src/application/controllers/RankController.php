<?php
class RankController extends BaseController
{
    public function getRankingAction()
    {
        $name   = $this->getParam("name")   ? trim(strip_tags($this->getParam("name"))) : "";
        $offset = $this->getParam("offset") ? intval($this->getParam("offset"))         : 0;
        $num    = $this->getParam("num")    ? intval($this->getParam("num"))            : 10;
        
        if (strpos($name, 'date') !== false) {
            if (intval(date('YmdH')) < intval(date("Ymd") . '05')) {
                $name   = str_replace('date', 'date_'. date("Ymd", strtotime("-1 day ")) . '05', $name);
            } else {
                $name   = str_replace('date', 'date_'. date("Ymd") . '05', $name);
            }
        } else {
            //$name   = str_replace('date', 'date_'. date("Ymd"), $name);
            $name   = str_replace('week', 'week_'. date("W"), $name);
            $name   = str_replace('month', 'month_'. date("Ym"), $name);
        }
        
        $rank = new Rank();
        list($total, $ranking, $offset, $more) = $rank->getRanking($name, $offset, $num);
        
        if($num==3) {
            $arrTemp = array();
            $i=0;
            foreach($ranking as $item){
                $i++;
                if($i>3) {
                    continue;
                }
                array_push($arrTemp, $item);
            }
            unset($ranking);
            $ranking = $arrTemp;
        }
        
        $this->render(array("total" => $total, "ranking" => $ranking, "offset" => $offset, "more" => $more));
    }

    
    public function getLiveUserNumAction()
    {
        /* {{{ 直播间真实在线人数*/
        $liveids   = $this->getParam("liveids")   ? trim(strip_tags($this->getParam("liveids"))) : "";
        
        $liveid_array = explode(",", $liveids);
        Interceptor::ensureNotEmpty($liveid_array, ERROR_PARAM_IS_EMPTY, "liveids");
        
        $list = array();
        
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        foreach ($liveid_array AS $key => $val) {
            $key = "dreamlive_live_user_real_num_".$val;
            $result = json_decode($cache->get($key), true);
            $list[$val] = $result['num']?$result['num']:0;
        }
        
        $this->render($list);
    }/*}}}*/
    
    
    
    //周星榜
    public function getWeekStarRankAction()
    {
        $uid=$this->getParam("anchorid", 0);
        $this->render(WeekStarTopn::getWeekStar($uid));
    }
    
    /**
     * 获取值
     */
    public function getRankingElementAction()
    {
        $name = $this->getParam("name") ? trim(strip_tags($this->getParam("name"))) : "";
        $uid  = $this->getParam("uid")  ? intval($this->getParam("uid")) : 0;
        
        $rank = new Rank();
        
        $rank_value = 0;
        if (strpos($name, 'date') !== false) {
            if (intval(date('YmdH')) < intval(date("Ymd") . '05')) {
                $name = str_replace('date', 'date_' . date("Ymd", strtotime("-1 day ")) . '05', $name);
            } else {
                $name = str_replace('date', 'date_' . date("Ymd") . '05', $name);
            }
            
            if (strpos($name, 'receivegift_ranking_date') !== false) {
                $rank_value = $rank->getRankValueByRankName("receivegift_ranking_date", $uid);
            }
        } else {
            // $name = str_replace('date', 'date_'. date("Ymd"), $name);
            $name = str_replace('week', 'week_' . date("W"), $name);
            $name = str_replace('month', 'month_' . date("Ym"), $name);
        }
        
        Interceptor::ensureNotFalse($uid >= 0, ERROR_PARAM_NOT_SMALL_ZERO, "uid");
        
        $score = $rank->getRankingElement($name, $uid);
        $score = $score ? $score : 0;
        $this->render(array('score'=>$score, "rank" => $rank_value));
    }
    //直播时长统计排行
    public function liveTimeRankAction()
    {
        $anchorid   = $this->getParam("anchorid", 0)?$this->getParam("anchorid", 0):Context::get("userid");;
        Interceptor::ensureNotEmpty($anchorid>0, ERROR_PARAM_IS_EMPTY, "anchorid");

        $rank       = new Rank();
        $rank_info  = $rank->liveTimeRank($anchorid);
        $this   -> render($rank_info);
    }
    //
    public function addLiveTimeWarningAction()
    {
        $info       = $this -> getParam('info', '');
        $info       = json_decode($info, true);
        $cache      = Cache::getInstance("REDIS_CONF_CACHE");
        $cache -> set('live_time_waring_uids', json_encode($info['info']));
        $cache -> set('live_time_active_time', json_encode(array('stime'=>$info['stime'],'etime'=>$info['etime'])));
    }
}
?>
