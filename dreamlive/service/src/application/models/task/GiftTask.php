<?php
class GiftTask
{
    const RATE = 10;

    public function getTaskProfit($userid, $taskid, $num, $data)
    {
        /* {{{ */
        $amount = 0;
        if ($num) {
            $amount = $num * self::RATE * $data['price'];
        }
        
        return $amount;
    } /* }}} */
}
