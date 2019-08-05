<?php
class Active
{
    public static function check($sender, $receiver)
    {
        //|================================================================================================|
        //| 流程: 
        //| 1. 得到送礼人在哪些活动中. 
        //| 2. 去掉回收的活动. 过期的活动
        //| 3. 查找这些活动有没有对应的主播
        $DAOActive = new DAOActive();
        $data = $DAOActive->getList($sender);
        $activeid = array();
        if(is_array($data)) {
            foreach ($data as $key => $value){
                if($value['activeid']) {
                    $active_info = $DAOActive->getInfo($value['activeid']);
                    $current_time = date("Y-m-d H:i:s", time());
                    if($active_info['status'] == 'N' && ($current_time>=$active_info['starttime'] && $current_time<=$active_info['endtime'])) {
                        $activeid[$value['activeid']] = $value['activeid'];
                    }
                }
            }
            $activeid_str = implode(',', $activeid);
            if($activeid_str) { //是活动号， 只能送给主播, 否则就不能送.
                $data_sender = $DAOActive->get($activeid_str, $receiver);
                if($data_sender['id']) {
                    return true;
                } else {
                    return false;
                }
            } else { //不是活动号， 可以送
                return true;
            }
        } else { //不是活动号， 可以送
            return true;
        }
    }
}
?>