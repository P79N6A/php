<?php
class DAOChat extends DAOProxy
{
    protected $table_hash = array(
    'chat_0' => array(1, 1000000),
    'chat_1' => array(1000001, 2000000),
    'chat_2' => array(2000001, 3000000),
    'chat_3' => array(3000001, 4000000),
    'chat_4' => array(4000001, 5000000),
    'chat_5' => array(5000001, 6000000),
    'chat_6' => array(6000001, 7000000),
    'chat_7' => array(7000001, 8000000),
    'chat_8' => array(8000001, 9000000),
    'chat_9' => array(9000001, 10000000),
    'chat_10' => array(10000001, 11000000),
    'chat_11' => array(11000001, 12000000),
    'chat_12' => array(12000001, 13000000),
    'chat_13' => array(13000001, 14000000),
    'chat_14' => array(14000001, 15000000),
    'chat_15' => array(15000001, 16000000),
    'chat_16' => array(16000001, 17000000),
    'chat_17' => array(17000001, 18000000),
    'chat_18' => array(18000001, 19000000),
    'chat_19' => array(19000001, 20000000),
    'chat_20' => array(20000001, 21000000),
    'chat_21' => array(21000001, 22000000),
    'chat_22' => array(22000001, 23000000),
    'chat_23' => array(23000001, 24000000),
    'chat_24' => array(24000001, 25000000),
    'chat_25' => array(25000001, 26000000),
    'chat_26' => array(26000001, 27000000),
    'chat_27' => array(27000001, 28000000),
    'chat_28' => array(28000001, 29000000),
    'chat_29' => array(29000001, 30000000),
    'chat_30' => array(30000001, 31000000),
    'chat_31' => array(31000001, 32000000),
    'chat_32' => array(32000001, 33000000),
    'chat_33' => array(33000001, 34000000),
    'chat_34' => array(34000001, 35000000),
    'chat_35' => array(35000001, 36000000),
    'chat_36' => array(36000001, 37000000),
    'chat_37' => array(37000001, 38000000),
    'chat_38' => array(38000001, 39000000),
    'chat_39' => array(39000001, 40000000),
    'chat_40' => array(40000001, 41000000),
    'chat_41' => array(41000001, 42000000),
    'chat_42' => array(42000001, 43000000),
    'chat_43' => array(43000001, 44000000),
    'chat_44' => array(44000001, 45000000),
    'chat_45' => array(45000001, 46000000),
    'chat_46' => array(46000001, 47000000),
    'chat_47' => array(47000001, 48000000),
    'chat_48' => array(48000001, 49000000),
    'chat_49' => array(49000001, 50000000),
    'chat_50' => array(50000001, 51000000),
    'chat_51' => array(51000001, 52000000),
    'chat_52' => array(52000001, 53000000),
    'chat_53' => array(53000001, 54000000),
    'chat_54' => array(54000001, 55000000),
    'chat_55' => array(55000001, 56000000),
    'chat_56' => array(56000001, 57000000),
    'chat_57' => array(57000001, 58000000),
    'chat_58' => array(58000001, 59000000),
    'chat_59' => array(59000001, 60000000),
    'chat_60' => array(60000001, 61000000),
    'chat_61' => array(61000001, 62000000),
    'chat_62' => array(62000001, 63000000),
    'chat_63' => array(63000001, 64000000),
    'chat_64' => array(64000001, 65000000),
    'chat_65' => array(65000001, 66000000),
    'chat_66' => array(66000001, 67000000),
    'chat_67' => array(67000001, 68000000),
    'chat_68' => array(68000001, 69000000),
    'chat_69' => array(69000001, 70000000),
    'chat_70' => array(70000001, 71000000),
    'chat_71' => array(71000001, 72000000),
    'chat_72' => array(72000001, 73000000),
    'chat_73' => array(73000001, 74000000),
    'chat_74' => array(74000001, 75000000),
    'chat_75' => array(75000001, 76000000),
    'chat_76' => array(76000001, 77000000),
    'chat_77' => array(77000001, 78000000),
    'chat_78' => array(78000001, 79000000),
    'chat_79' => array(79000001, 80000000),
    'chat_80' => array(80000001, 81000000),
    'chat_81' => array(81000001, 82000000),
    'chat_82' => array(82000001, 83000000),
    'chat_83' => array(83000001, 84000000),
    'chat_84' => array(84000001, 85000000),
    'chat_85' => array(85000001, 86000000),
    'chat_86' => array(86000001, 87000000),
    'chat_87' => array(87000001, 88000000),
    'chat_88' => array(88000001, 89000000),
    'chat_89' => array(89000001, 90000000),
    'chat_90' => array(90000001, 91000000),
    'chat_91' => array(91000001, 92000000),
    'chat_92' => array(92000001, 93000000),
    'chat_93' => array(93000001, 94000000),
    'chat_94' => array(94000001, 95000000),
    'chat_95' => array(95000001, 96000000),
    'chat_96' => array(96000001, 97000000),
    'chat_97' => array(97000001, 98000000),
    'chat_98' => array(98000001, 99000000),
    'chat_99' => array(99000001, 100000000),
    );
    
    public function __construct($liveid)
    {
        $this->setDBConf("MYSQL_CONF_MESSAGE");
        $this->setShardId($liveid);
        $this->setTableName("chat");
    }

    public function addMessage($liveid, $sender, $type, $content, $replace_keyword)
    {
        $info = array(
            'liveid'             => $liveid,
            'sender'             => $sender,
            'type'                => intval($type),
            'content'             => $content,
            'replace_keyword'    => $replace_keyword,
            'addtime'             => date("Y-m-d H:i:s")
        );
        
        try {
            $result = $this->insert($this->getTableName(), $info);
        } catch (Exception $e) {            
            return false;
        }
        
        return true;
    }

    public function getMessageList($liveid, $offset, $num)
    {
        $condition['liveid = ?'] = $liveid;
        if ($offset) {
            $condition['id < ?'] = $offset;
        }
        $values = array_values($condition);
        $condition = implode(' and ', array_keys($condition));
        
        $sql = "select * from {$this->getTableName()} where {$condition} order by id desc limit $num";
        
        try{
            $messages = $this->getAll($sql, $values);
        }catch (Exception $e){
            $messages = array();
        }
        
        if ($messages) {
            $offset = $messages[count($messages) - 1]['id'];
        }else{
            $offset = 0;
        }
        
        return array($messages,    $offset);
    }

    protected function getTableName()
    {
        foreach ($this->table_hash as $table_name => $range){
            list($min, $max) = $range;
            
            if ($this->getShardId() >= $min && $this->getShardId() <= $max) {
                return $table_name;
            }
        }
        
        return 'chat_0';
    }
}
?>
