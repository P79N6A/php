<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/5
 * Time: 10:47
 */
class DAOStarJournal extends DAOProxy
{
    function __construct($uid)
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setShardId($uid);
        $this->setTableName("star_journal");
    }

    /**
     * 添加星光余额记录
     */

    public function add($orderid,$type,$direct,$currency,$amount,$remark,$extends)
    {
        $sql="insert into ".$this->getTableName()
            ." (orderid,uid,type,direct,currency,amount,remark,extends,addtime) values(?,?,?,?,?,?,?,?,?)";
        $_ext=is_array($extends)||is_object($extends)?json_encode($extends):$extends;
        return $this->Execute(
            $sql, [$orderid,$this->getShardId(),$type ,$direct,
            $currency,$amount,$remark,$_ext,date('Y-m-d H:i:s')]
        );
    }
    //获取消耗星光
    public function getStarOne($uid, $orderid)
    {
        $sql    = "select amount from {$this->getTableName()} where uid=? and orderid=?";
        return $this->getOne($sql, [$uid,$orderid]);
    }
    public function getJournalList($uid, $offset, $num, $direct, $currency, $type, $addtime, $endtime, $all = '2,18')
    {
        $where = array('uid'=>$uid);
        $where_sql = '';
        if($type) {
            $where_sql .= " and type=?";
            $where['type'] = $type;
        }else{
            $where_sql .= " and type in (".$all.")";
        }
        if($direct) {
            $where_sql .= " and direct=?";
            $where['direct'] = $direct;
        }
        if($addtime) {
            $where_sql .= " and addtime>='".date("Y-m-d 00:00:00", $addtime)."'";
        }
        if($endtime) {
            $where_sql .= " and addtime<='".date("Y-m-d 23:59:59", $endtime)."'";
        }
        if($currency) {
            $where_sql .= " and currency=?";
            $where['currency']  = $currency;
        }
        $where_sql .= " and addtime>'2018-02-24 00:00:00'";
        $where['id'] = $offset;
        $where['num'] = $num;

        $sql = "select id, orderid, type, direct, currency, amount, remark, extends as extend, addtime from {$this->getTableName()} where uid=? $where_sql and id<?  order by id desc limit  ?";
        $data = $this->getAll($sql, $where);

        return $data;
    }

    public function getJournalNum( $uid, $direct, $currency, $type, $addtime, $endtime, $all = '2,18')
    {
        $where = array('uid'=>$uid);
        $where_sql = '';
        if($type) {
            $where_sql .= " and type=?";
            $where['type'] = $type;
        }else{
            $where_sql .= " and type in (".$all.")";
        }
        if($direct) {
            $where_sql .= " and direct=?";
            $where['direct'] = $direct;
        }
        if($addtime) {
            $where_sql .= " and addtime>='".date("Y-m-d 00:00:00", $addtime)."'";
        }
        if($endtime) {
            $where_sql .= " and addtime<='".date("Y-m-d 23:59:59", $endtime)."'";
        }
        if($currency) {
            $where_sql .= " and currency=?";
            $where['currency']  = $currency;
        }
        $where_sql .= " and addtime>'2018-02-24 00:00:00'";
        $sql = "select count(*) from {$this->getTableName()} where uid=? ".$where_sql;

        return $this->getOne($sql, $where);
    }
    /**/
    public function getTotalNum($uid, $startime, $endtime, $direct, $all = '2,18')
    {
        $where_sql = '';
        if($startime) {
            $where_sql .= " and addtime>='".date("Y-m-d 00:00:00", $startime)."'";
        }
        if($endtime) {
            $where_sql .= " and addtime<='".date("Y-m-d 23:59:59", $endtime)."'";
        }
        $sql    = "select sum(amount) from {$this->getTableName()} where type in (".$all.") and uid =? and direct=? {$where_sql}";

        return $this->getOne($sql, array($uid,$direct));
    }
    /**
     * 获取用户收入总次数
     */
    public function getIncomeNum($uid, $startime, $endtime, $direct, $all)
    {
        $where_sql = '';
        if($startime) {
            $where_sql .= " and addtime>='".date("Y-m-d 00:00:00", $startime)."'";
        }
        if($endtime) {
            $where_sql .= " and addtime<='".date("Y-m-d 23:59:59", $endtime)."'";
        }
        $sql    = "select count(*) from {$this->getTableName()} where type in (".$all.") and uid =? and direct=? {$where_sql}";

        return $this->getOne($sql, array($uid,$direct));
    }
}