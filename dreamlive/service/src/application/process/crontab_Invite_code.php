<?php
/**
 * 冲顶生成邀请码
 * User: User
 * Date: 2018/1/11
 * Time: 15:46
 */
set_time_limit(0);
ini_set('memory_limit', '1G');
ini_set("display_errors", "On");
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
$ROOT_PATH = dirname(dirname(dirname(dirname(__FILE__))));
$LOAD_PATH = array(
    $ROOT_PATH."/src/www",
    $ROOT_PATH."/config",
    $ROOT_PATH."/src/application/controllers",
    $ROOT_PATH."/src/application/models",
    $ROOT_PATH."/src/application/models/libs",
    $ROOT_PATH."/src/application/models/dao"
);
set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $LOAD_PATH));

function __autoload($classname)
{
    $classname = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
    include_once "$classname.php";
}

require $ROOT_PATH."/config/server_conf.php";

/*function make_coupon_card() {
    $code = '0123456789';
    $rand = $code[rand(0,9)]
        .strtoupper(dechex(date('m')))
        .date('d').substr(time(),-5)
        .substr(microtime(),2,5)
        .sprintf('%02d',rand(0,99));
    for(
        $a = md5( $rand, true ),
        $s = '0123456789',
        $d = '',
        $f = 0;
        $f < 8;
        $g = ord( $a[ $f ] ),
        $d .= $s[ ( $g ^ ord( $a[ $f + 8 ] ) ) - $g & 0x1F ],
        $f++
    );

}
for($i=0;$i<100;$i++){
    $code   = make_coupon_card();
    echo $code."\r\n";
}*/
for($i=0;$i<1000000;$i++){
    while(1){
        $code   = mt_rand(0, 999999);
        $summit = new DAOActivityHeaderSummit();
        $code   = sprintf("%'06d", $code);
        $sql_select    = "select * from activity_header_summit where code=?";
        if(!$summit->getAll($sql_select, [$code])) {
            $sql    = "replace into activity_header_summit (uid,`code`,addtime) values (0,'{$code}','".date("Y-m-d H:i:s")."')";
            $summit->Execute($sql);
            break;
        }
    }


    //$summit->addSummit($code);
}
