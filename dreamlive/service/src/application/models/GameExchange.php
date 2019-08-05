<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/15
 * Time: 15:21
 */
class GameExchange
{
    const EXCHANGE_CONFIG=array(
        array(//赛马游戏
            'type'=>5,
            'gameid'=>0,
            'game'=>'',
            'currency_name'=>'赛马币',
            'd2o_list'=>array(
                '1'=>100,
                '10'=>1000,
            ),
            'o2d_list'=>array(
                '100'=>1,
                '1000'=>10,
            ),
        ),
    );
    public static function exchangeList($game='')
    {
        $config=self::getConfig($game);
        $d2o=array();
        foreach ($config['d2o_list'] as $k=>$v){
            $d2o[]=array('diamond'=>$k,'coin'=>$v);
        }

        $o2d=array();
        foreach ($config['o2d_list'] as $k=>$v){
            $o2d[]=array('diamond'=>$v,'coin'=>$k);
        }
        return array(
            'game'=>$config['game'],
            'd2c'=>$d2o,
            'c2d'=>$o2d
        );
    }

    public static function prepare($uid,$amount,$game,$direct,$other_orderid="")
    {
        $config=self::getConfig($game);
        if (empty($config)) { throw new Exception("兑换配置异常");
        }
        if (!in_array($direct, array(DAOGameExchange::DIRECT_IN,DAOGameExchange::DIRECT_OUT))) { throw new Exception("兑换方向异常");
        }
        if (!is_int($amount)||$amount<=0) { throw new Exception("兑换金额异常");
        }
        if ($direct==DAOGameExchange::DIRECT_OUT) {//星钻兑换游戏币
            if (!in_array($amount, array_keys($config['d2o_list']))) { throw new Exception("兑换金额异常");
            }
            $diamond_amount=$amount;
            $game_amount=$config['d2o_list'][$amount];
            $balance=Account::getBalance($uid, Account::CURRENCY_DIAMOND);
            if (!$balance||$balance<$diamond_amount) { throw new Exception("星钻余额不足兑换失败");
            }
        }elseif ($direct==DAOGameExchange::DIRECT_IN) {//游戏币兑换星钻
            if (!in_array($amount, array_keys($config['o2d_list']))) { throw new Exception("兑换金额异常");
            }
            $game_amount=$amount;
            $diamond_amount=$config['d2o_list'][$amount];
        }
        $remark=$config;
        $id=self::add($uid, $diamond_amount, $game_amount, $game, $direct, $other_orderid, $remark);
        if (!$id) { throw new Exception("创建预兑换失败");
        }
        $daoGameExchange= new DAOGameExchange();
        $info=$daoGameExchange->byId($id);
        if (empty($info)) { throw new Exception("预兑换单不存在");
        }
        return array(
            'uid'=>$uid,
            'orderid'=>$info['orderid'],
            'trade_no'=>$info['other_orderid'],
            'diamond'=>$info['diamond_amount'],
            'coin'=>$info['game_amount'],
        );
    }

    private static function add($uid,$diamond_amount,$game_amount,$game,$direct,$other_orderid,array $remark=array())
    {
        $remark=is_array($remark)?$remark:array($remark);
        $orderid=Account::getOrderId();
        $daoGameExchange= new DAOGameExchange();
        return $daoGameExchange->add($uid, $orderid, $diamond_amount, $game_amount, $game, $direct, $other_orderid, json_encode($remark));
    }

    public static function complete($uid,$orderid)
    {
        if ($uid<=0) { throw new Exception("uid异常");
        }
        if (!$orderid) { throw new Exception("orderid异常");
        }
        $daoGameExchange=new DAOGameExchange();
        $info=$daoGameExchange->info($orderid);
        if (empty($info)) { throw new Exception("单子不存在");
        }
        if ($info['status']!=DAOGameExchange::STATUS_PREPARE) { throw new Exception("单子状态异常");
        }

        $daoGameExchange->startTrans();
        try{
            $balance=Account::getBalance($uid, Account::CURRENCY_DIAMOND);
            if ($balance<$info['diamond_amount']) { throw new Exception("金额不足");
            }
            Account::decrease(
                $uid, Account::TRADE_TYPE_GAME_RACE_HORSE,
                $orderid, $info['diamond_amount'], Account::CURRENCY_DIAMOND,
                "赛马游戏，钻石兑换游戏币[uid=$uid],[orderid=$orderid]" 
            );
            $daoGameExchange->status($orderid, DAOGameExchange::STATUS_SUCCESS);
            $daoGameExchange->commit();
        }catch (Exception $e){
            $daoGameExchange->rollback();
            $daoGameExchange->status($orderid, DAOGameExchange::STATUS_FAIL);
            //throw $e;
        }
        return self::result($daoGameExchange->info($orderid));
    }

    public static function verify($uid,$orderid)
    {
        if (!$orderid) { throw new Exception("单子id异常");
        }
        $daoGameExchange=new  DAOGameExchange();
        $info=$daoGameExchange->info($orderid);
        if (empty($info)) { throw new Exception("单子不存在");
        }
        if ($info['uid']!=$uid) { throw new Exception("单子异常");
        }
        return self::result($info);
    }

    private static function getConfig($game)
    {
        $config=self::EXCHANGE_CONFIG;
        if ($game) {
            foreach ($config as $i){
                if ($i['game']==$game) {
                    return $i;
                }
            }
        }
        return $config;
    }
    
    public static function signCheck(array $param,$sign)
    {
        ksort($param);
        $str=http_build_query($param);
        if ($sign==md5($str)) { return true;
        }
        return false;
    }

    private static function result($info)
    {
        return array(
            'uid'=>$info['uid'],
            'game'=>$info['game'],
            'trade_no'=>$info['other_orderid'],
            'status'=>$info['status'],
            'orderid'=>$info['orderid'],
            'diamond'=>$info['diamond_amount'],
            'coin'=>$info['game_amount'],
            'addtime'=>$info['addtime'],
        );
    }
}