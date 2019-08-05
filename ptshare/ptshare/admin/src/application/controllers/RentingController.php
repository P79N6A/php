<?php
class RentingController extends BaseController
{
    public $type = array(
        '100' => '待收货',
        '200' => '租用中',
        '300' => '待传递',
        '400' => '完成',
    );
    public $status = array(
        '101' => '待支付',
        '102' => '待发货',
        '103' => '已发货',
        '200' => '租用中',
        '300' => '待传递',
        '401' => '待评价',
        '402' => '已评价',
        '403' => '已取消',
        '404' => '已撤销',
    );

    public function listAction()
    {
        $uid         = trim($this->getParam('uid', ''));
        $packageid   = trim($this->getParam('packageid', ''));
        $sn          = trim($this->getParam('sn', ''));
        $status      = trim($this->getParam('status', ''));
        $end_time    = trim($this->getParam('end_time', ''));
        $page        = $this->getParam("page") ? intval($this->getParam("page")): 1;
        $num         = $this->getParam("num") ? intval($this->getParam("num"))  : 20;
        $start       = ($page - 1) * $num;

        $renting = new UserRenting();
        list($list, $total) = $renting->getList($start, $num, $packageid, $sn, $status, $uid);
        $packageidList = array();
        foreach ($list as $val) {
            $packageidList[] = $val["packageid"];
        }
        $package = new Package();
        $packageList = $package->getCoverByPackageids($packageidList);
        $packageList = array_column($packageList, 'cover', 'packageid');
        foreach($list as &$val){
            $val['packageCover'] = $packageList[$val['packageid']];

            if (strpos($val['packageCover'], 'http') === false) {
            	$val['packageCover'] = 'https://static.putaofenxiang.com' . $val['packageCover'];
            }
        }
        $param = [
            'packageid' => $packageid,
            'sn'        => $sn,
            'status'    => $status,
            'uid'       => $uid
        ];
        $mutipage = $this->mutipage($total, $page, $num, http_build_query($param), "/renting/list");

        $data = array();
        $data['data']     = $list;
        $data['total']    = $total;
        $data['mutipage'] = $mutipage;

        $this->assign("param", $param);
        $this->assign("data", $data);

        $this->display("include/header.html", "renting/index.html", "include/footer.html");
    }

    public function cancelAction()
    {
        $relateid = trim($this->getParam('relateid', ''));
        Interceptor::ensureNotEmpty($relateid, ERROR_PARAM_IS_EMPTY, "relateid");

        try{
            ShareClient::adminRevoke($relateid);
            //日志
            $operate = new Operate();
            $operate_content = array(
                'relateid'    => $relateid,
            );
            $operate->add($this->adminid, 'renting_cancel', 0, 0, $operate_content, '', '', 1);

            Util::jumpMsg("修改成功!", "/Renting/list");
        }catch (Exception $e){
            Util::jumpMsg("修改失败:{$e->getCode()}:{$e->getMessage()}", "/Renting/list", 3);
        }
    }
}