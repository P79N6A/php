<?php
class OperateController extends BaseController
{
    public function indexAction()
    {
        $page = intval($this->getParam("page", 1));
        $num = intval($this->getParam("num", 100));
        $admin_name     = $this->getParam("admin_name") ? trim(strip_tags($this->getParam("admin_name"))) : "";
        $uid    = $this->getParam("uid") ? trim(strip_tags($this->getParam("uid"))) : "";
        $relate_type   = $this->getParam("relate_type") ? trim(strip_tags($this->getParam("relate_type"))) : "";
        $operate_type   = $this->getParam("operate_type") ? trim(strip_tags($this->getParam("operate_type"))) : "";
        $start  = ($page - 1) * $num;
        $sTime          = $this->getParam("sTime") ? trim(strip_tags($this->getParam("sTime"))) : date("Y-m-d 00:00:00");
        $eTime          = $this->getParam("eTime") ? trim(strip_tags($this->getParam("eTime"))) : date("Y-m-d 23:59:59");
        $relateid          = $this->getParam("relateid") ? trim(strip_tags($this->getParam("relateid"))) : "";

        if(!empty($relateid)){
            if(empty($relate_type)){
                Util::jumpMsg('资源搜索必须选择资源类型', "/operate/");
            }
        }

        $param = array(
            'admin_name'=>$admin_name,
            'uid'=>$uid,
            'relate_type'=>$relate_type,
            'operate_type'=>$operate_type,
            'stime'=>$sTime,
            'etime'=>$eTime,
            'relateid'=>$relateid,
        );

        $data = $this->getOperateList($param, $start, $num);

        $mutipage = $this->mutipage($data['total'], $page, $num, http_build_query($param), "/operate/");
        $this->assign("operate_type", Context::getConfig("OPERATE_TYPE"));
        $this->assign("mutipage", $mutipage);
        $this->assign("data", $data);
        $this->assign("get_data", $param);
        $this->assign("issearch", 'Y');

        $this->display("include/header.html", "operate/index.html", "include/footer.html");

    }

    public function getOperateList($param, $start, $num)
    {
        $con['start_time'] = $param['stime'] ? trim(strip_tags($param['stime'])) : '';
        $con['end_time'] = $param['etime'] ? trim(strip_tags($param['etime'])) : '';


        $admin = new Admin();
        if ($param['admin_name']) {
            $admin_info = $admin->getAdminByName(trim(strip_tags($param['admin_name'])));
            $con['adminid'] = $admin_info['adminid'];
        }

        if ($param['relateid']) {
            $con['relateid'] = trim(strip_tags($param['relateid']));
        }
        if ($param['uid']) {
            $con['uid'] = intval($param['uid']);
        }
        if ($param['relate_type']) {
            $con['type'] = trim(strip_tags($param['relate_type']));
        }
        if ($param['operate_type']) {
            $con['name'] = trim(strip_tags($param['operate_type']));
        }

        $operate = new Operate();
        list($total, $list) = $operate->getOperateList($con, $start, $num);

        $operate_type = Context::getConfig("OPERATE_TYPE");
        $relate_type = Context::getConfig("OPERATE_RELATE_TYPE");
        foreach ($list as $key=>&$val) {
            $admin_info = $admin->getAdminInfo($val['adminid']);
            $val['admin_name'] = $admin_info['name'];
            $val['realname']   = $admin_info['realname'];
            $val['operate_type'] = $operate_type[$val['name']];
            $val['relate_type'] = $relate_type[$val['type']];
            $val['type_name'] = $relate_type[$val['type']];
        }

        return array("total"=>$total, "list"=>$list, "operate_type"=>$operate_type, "relate_type"=>$relate_type);
    }
}
?>