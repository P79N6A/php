<?php
class DAOJournal extends DAOProxy
{
    public function __construct($userid)
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setShardId($userid);
        $this->setTableName("journal");
    }
    
    public function add($orderid, $uid, $type, $direct, $currency, $amount, $remark, $extends = array())
    {
        $r=$this->getRow("select * from ".$this->getTableName()." where type=? and  uid=? and orderid=? and direct=?", array('type'=>1,'uid'=>$uid,'orderid'=>$orderid,'direct'=>$direct));
        if ($r) { throw new Exception("orderid 和direct 重复");
        }
        $trade_info = array(
        "orderid"    => $orderid,
        "uid"        => $uid,
        "type"        => $type,
        "direct"    => $direct,
        "currency"  => $currency,
        "amount"    => $amount,
        "remark"    => $remark,
        "extends"    => !empty($extends) ? json_encode($extends) : '',
        "addtime"    => date("Y-m-d H:i:s")
        );

        return $this->insert($this->getTableName(), $trade_info);
    }
    //获取消耗星钻
    public function getStarOne($uid, $orderid)
    {
        $sql    = "select amount from {$this->getTableName()} where uid=? and orderid=?";
        return $this->getOne($sql, [$uid,$orderid]);
    }

    public function getJournalList($uid, $offset, $num, $direct, $currency, $type, $addtime, $endtime, $all = '3,5,6,17,18,30')
    {

        $where = array('uid'=>$uid);
        $where_sql = '';
        if($type) {
            if($type == 3&&$currency==1) {
                $where_sql .= " and type in (3,50,53)";
            }else{
                $where_sql .= " and type=?";
                $where['type'] = $type;
            }
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

    public function getJournalNum( $uid, $direct, $currency, $type, $addtime, $endtime, $all = '3,5,6,17,18,30')
    {
        $where = array('uid'=>$uid);
        $where_sql = '';
        if($type) {
            if($type == 3&&$currency==1) {
                $where_sql .= " and type in (3,50,53)";
            }else{
                $where_sql .= " and type=?";
                $where['type'] = $type;
            }
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


    public function getJournalListByActive($uid, $direct, $currency, $type, $addtime, $endtime)
    {

        $where = array('uid'=>$uid);
        $where_sql = '';
        if($type) {
            $where_sql .= " and type=?";
            $where['type'] = $type;
        }else{
            $where_sql .= " and type in (3,6,17,18,30)";
        }
        if($direct) {
            $where_sql .= " and direct=?";
            $where['direct'] = $direct;
        }
        if($addtime) {
            $where_sql .= " and addtime>='$addtime'";
        }
        if($endtime) {
            $where_sql .= " and addtime<='$endtime'";
        }
        if($currency) {
            $where_sql .= " and currency=?";
            $where['currency']  = $currency;
        }


        $sql = "select id, orderid, type, direct, currency, amount, remark, extends as extend, addtime from {$this->getTableName()} where uid=? $where_sql  order by id desc";
        $data = $this->getAll($sql, $where);
        
        return $data;
    }


    public function getJournalNumByActive($activeid, $uid, $direct, $currency, $type, $addtime, $endtime)
    {
        $where = array('uid'=>$uid);
        $where_sql = '';
        if($type) {
            $where_sql .= " and type=?";
            $where['type'] = $type;
        }else{
            $where_sql .= " and type in (3,6,17,18,30)";
        }
        if($direct) {
            $where_sql .= " and direct=?";
            $where['direct'] = $direct;
        }
        if($addtime) {
            $where_sql .= " and addtime>='$addtime'";
        }
        if($endtime) {
            $where_sql .= " and addtime<='$endtime'";
        }
        if($currency) {
            $where_sql .= " and currency=?";
            $where['currency']  = $currency;
        }
        if($activeid) {
            $where_sql .= " and extends like '{\"activeid\":\"$activeid\"%'";
        }

        $sql = "select sum(amount) from {$this->getTableName()} where uid=? ".$where_sql;

        return $this->getOne($sql, $where);
    }


    
    public  function createTable($id)
    {
        $create_table_sql = "CREATE TABLE `journal_{$id}` (`id` int(11) unsigned NOT NULL AUTO_INCREMENT,`orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',`uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',`type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1充值,2提现,3送礼,4票换钻,5红包(转账),6钻换票,7系统道具(喇叭,飞屏)',`direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',`currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',`amount` double(10,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',`remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',`extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',`addtime` datetime NOT NULL COMMENT '创建时间',PRIMARY KEY (`id`),KEY `uid` (`uid`) USING BTREE,KEY `orderid` (`orderid`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';";
        $this->execute($create_table_sql);
    }

    /**
     * 获取主播收获的星票数
     */
    public function getReceivedTicketsByUid($uid)
    {
        $sql="select sum(amount) as num from {$this->getTableName()} where uid=? and type=3 and direct='IN' and currency=1";
        return $this->getOne($sql, array('uid'=>$uid));
    }
    /**/
    public function getTotalNum($uid, $startime, $endtime, $direct, $all = '3,6,17,18,30')
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

    public function getUserIncomeByTypeInDate($uid,$stime,$etime)
    {
        return $this->getAll("select sum(amount) as num,uid,`type` from ".$this->getTableName()." where uid=? and direct='IN' and currency=".Account::CURRENCY_TICKET." and addtime >= '".$stime."' and addtime <= '".$etime."' group by type", array('uid'=>$uid));
    }
}

?>