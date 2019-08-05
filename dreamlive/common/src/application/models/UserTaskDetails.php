<?php
class UserTaskDetails
{
    /**
     * 添加任务明细
     *
     * @param int   $uid
     * @param int   $taskid
     * @param int   $type
     * @param array $ext
     */
    public static function addUserTaskDetails($uid, $taskid, $type, $ext,$level=0)
    {
        $userTaskDetails = array(
            'uid'     => $uid,
            'taskid'  => $taskid,
            'type'    => $type,
            'level'   => $level,
            'ext'     => json_encode($ext),
            'addtime' => date("Y-m-d H:i:s")
        );
        $DAOUserTaskDetails = new DAOUserTaskDetails($uid);
        return $DAOUserTaskDetails->addData($userTaskDetails);
    }
    
    /**
     * 
     * @param int $uid
     * @param int $type
     */
    public static function getListType($uid,$type)
    {
        $arrTemp = array();
        $DAOUserTaskDetails = new DAOUserTaskDetails($uid);
        $result = $DAOUserTaskDetails->getListByType($type);
        foreach($result as $key=>$val){
            $arrTemp[$val['taskid']] = $val['taskid'];
        }
        return $arrTemp;
    }
}
