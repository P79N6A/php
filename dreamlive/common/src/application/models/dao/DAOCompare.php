<?php
class DAOCompare extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("gift");
    }



    public function get()
    {
        $sql = "select * from account_0 where 1 union  select * from account_1 where 1 union  select * from account_2 where 1 union  select * from account_3 where 1 union  select * from account_4 where 1 union  select * from account_5 where 1 union  select * from account_6 where 1 union  select * from account_7 where 1 union  select * from account_8 where 1 union  select * from account_9 where 1 union  select * from account_10 where 1 union  select * from account_11 where 1 union  select * from account_12 where 1 union  select * from account_13 where 1 union  select * from account_14 where 1 union  select * from account_15 where 1 union  select * from account_16 where 1 union  select * from account_17 where 1 union  select * from account_18 where 1 union  select * from account_19 where 1 union  select * from account_20 where 1 union  select * from account_21 where 1 union  select * from account_22 where 1 union  select * from account_23 where 1 union  select * from account_24 where 1 union  select * from account_25 where 1 union  select * from account_26 where 1 union  select * from account_27 where 1 union  select * from account_28 where 1 union  select * from account_29 where 1 union  select * from account_30 where 1 union  select * from account_31 where 1 union  select * from account_32 where 1 union  select * from account_33 where 1 union  select * from account_34 where 1 union  select * from account_35 where 1 union  select * from account_36 where 1 union  select * from account_37 where 1 union  select * from account_38 where 1 union  select * from account_39 where 1 union  select * from account_40 where 1 union  select * from account_41 where 1 union  select * from account_42 where 1 union  select * from account_43 where 1 union  select * from account_44 where 1 union  select * from account_45 where 1 union  select * from account_46 where 1 union  select * from account_47 where 1 union  select * from account_48 where 1 union  select * from account_49 where 1 union  select * from account_50 where 1 union  select * from account_51 where 1 union  select * from account_52 where 1 union  select * from account_53 where 1 union  select * from account_54 where 1 union  select * from account_55 where 1 union  select * from account_56 where 1 union  select * from account_57 where 1 union  select * from account_58 where 1 union  select * from account_59 where 1 union  select * from account_60 where 1 union  select * from account_61 where 1 union  select * from account_62 where 1 union  select * from account_63 where 1 union  select * from account_64 where 1 union  select * from account_65 where 1 union  select * from account_66 where 1 union  select * from account_67 where 1 union  select * from account_68 where 1 union  select * from account_69 where 1 union  select * from account_70 where 1 union  select * from account_71 where 1 union  select * from account_72 where 1 union  select * from account_73 where 1 union  select * from account_74 where 1 union  select * from account_75 where 1 union  select * from account_76 where 1 union  select * from account_77 where 1 union  select * from account_78 where 1 union  select * from account_79 where 1 union  select * from account_80 where 1 union  select * from account_81 where 1 union  select * from account_82 where 1 union  select * from account_83 where 1 union  select * from account_84 where 1 union  select * from account_85 where 1 union  select * from account_86 where 1 union  select * from account_87 where 1 union  select * from account_88 where 1 union  select * from account_89 where 1 union  select * from account_90 where 1 union  select * from account_91 where 1 union  select * from account_92 where 1 union  select * from account_93 where 1 union  select * from account_94 where 1 union  select * from account_95 where 1 union  select * from account_96 where 1 union  select * from account_97 where 1 union  select * from account_98 where 1 union  select * from account_99 where 1 ";
        
        $data = $this->getAll($sql,  null, false);
        
        return $data;
    }

    
}
?>