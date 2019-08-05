<?php
class KingController extends BaseController
{

    public function getListAction()
    {
        $uid = Context::get("userid");
        Interceptor::ensureNotEmpty($uid,    ERROR_PARAM_IS_EMPTY, "uid");

        //| 得到本月的列表
        $king = new King();
        $list = $king->getList($uid);

        //| 得到级别
        $level = $king->getTodayLevel($uid);
        if($level) {
            $arr = array($level);
        }
        if(is_array($list)) {
            foreach ($list  as $key => $value){
                $arr[] = $value['level'];
            }
        }
        $ret = array_count_values($arr);

        $this->render($ret);
    }

   
}
