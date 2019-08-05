<?php

class SeizingController extends BaseController
{

    public function indexAction()
    {
        $uid       = $this->getParam('uid', '');
        $packageid = $this->getParam('packageid', '');
        $type      = $this->getParam('type', '');
        $page      = $this->getParam("page") ? intval($this->getParam("page")) : 1;
        $num       = $this->getParam("num")  ? intval($this->getParam("num"))  : 20;
        
        $option = array(
            'uid'       => $uid,
            'packageid' => $packageid,
            'type'      => $type
        );
        $Seizing = new Seizing();
        list ($list, $total) = $Seizing->getList($page, $num, $option);
        
        $uids = $packageids = $userInfos = array();
        foreach($list as $item){
            array_push($uids, $item['uid']);
        }
        $User = new User();
        list($count,$userInfosList) = $User->getListByUids($uids);
        foreach($userInfosList as $key=>$val){
            $userInfos[$userInfosList[$key]['uid']] = $userInfosList[$key];
        }
        
        foreach($list as $key=>$val){
            $list[$key]['nickname'] = $userInfos[$list[$key]['uid']]['nickname'];
        }
        
        $mutipage = $this->mutipage($total, $page, $num, http_build_query($option), "/seizing/index");

        $this->assign("page", $mutipage);
        $this->assign("list", $list);
        $this->assign("total", $total);
        $this->assign("option", $option);
        $this->display("include/header.html", "seizing/index.html", "include/footer.html");
    }
}