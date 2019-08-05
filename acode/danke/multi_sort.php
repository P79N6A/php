<?php

$ar1 = array(10, 100, 100, 0);
$ar2 = array(1, 3, 2, 4);
array_multisort($ar1, $ar2);

print_r($ar1);
print_r($ar2);

Array
(
    [公共区域] => Array
        (
            [0] => 吧台
            [1] => 智能锁
        )

    [卫生间] => Array
        (
            [0] => 热水器
            [1] => 洗衣机
        )

    [厨房] => Array
        (
            [0] => 冰箱
            [1] => 微波炉
        )

    [B] => Array
        (
            [0] => 床
            [1] => 衣柜
            [2] => 书桌
            [3] => 空调
            [4] => 枕芯
        )

)
