<?php

class UserTaskAward
{
    const EXP           = 1;
    const STARLIGHT     = 2;
    const DIAMONDE      = 4;

    /**
     * 添加任务奖励
     * 
     * @param int   $uid            
     * @param int   $taskid            
     * @param array $award            
     */
    public static function AddUserTaskAward($uid, $taskid, $award)
    {
        $DAOUserTaskAward = new DAOUserTaskAward($uid);
        $taskAward = $DAOUserTaskAward->getUserTaskAwardNew($uid);
        $totalaward = json_decode($taskAward['totalaward'], true);

        $type = 0;
        $total = array();
        if (isset($award['exp'])) {
            $total['exp'] = $totalaward['exp'] + $award['exp'];
            $type = self::binary($type, self::EXP);
        }
        if (isset($award['starlight'])) {
            $total['starlight'] = $totalaward['starlight'] + $award['starlight'];
            $type = self::binary($type, self::STARLIGHT);
        }
        if (isset($award['diamonds'])) {
            $total['diamonds'] = $totalaward['diamonds'] + $award['diamonds'];
            $type = self::binary($type, self::DIAMONDE);
        }
        
        $userTaskAward = array(
            'uid' => $uid,
            'taskid' => $taskid,
            'type' => $type,
            'award' => json_encode($award),
            'totalaward' => json_encode($total),
            'result' => 'N',
            'addtime' => date("Y-m-d H:i:s")
        );
        $DAOUserTaskAward = new DAOUserTaskAward($uid);
        return $DAOUserTaskAward->addData($userTaskAward);
    }
    
    /**
     * 二进制相加
     *
     * @param int $arg1
     * @param int $arg2
     */
    public static function binary($arg1,$arg2)
    {
        if($arg1 === '' || $arg2 === '') {
            return false;
        }
        $tmpsum = decbin($arg1) + decbin($arg2);
        return bindec($tmpsum);
    }
    
    /**
     * 
     * @param int    $uid
     * @param string $result
     */
    public static function update($uid,$id,$result)
    {
        $option = array(
            'result'  => $result,
            'modtime' => date("Y-m-d H:i:s")
        );
        $DAOUserTaskAward = new DAOUserTaskAward($uid);
        return $DAOUserTaskAward->updateData($id, $option);
    }
}
