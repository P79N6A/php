<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 2018/5/8
 * Time: 下午9:09
 */

class DAOIdGenerator extends DAOProxy
{


    public function __construct(){
        $this->setDBConf("MYSQL_CONF_MALL");
        $this->setTableName("id_generator");
    }


    public function getId($namespace)
    {

        $sql = "UPDATE ".$this->getTableName()." SET number = LAST_INSERT_ID(number + 1) WHERE name = ?";

        $this->execute($sql, [$namespace]);

        return $this->getInsertId();

    }

}