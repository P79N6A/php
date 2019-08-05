<?php

class ClubController extends BaseController
{
    public function setupAction()
    {
        $uid = Context::get('userid');
        $name = trim(strip_tags($this->getParam('name', '')));
        $shortname = trim(strip_tags($this->getParam('shortname', '')));
        $announcement = trim(strip_tags($this->getParam('announcement', '')));

        $club = new Club();
        $data = $club->setup($name, $shortname, $uid, $announcement);

        $this->render($data);
    }

    /**
     * 修改宣言
     */
    public function announcementAction()
    {
        $uid = Context::get('userid');
        $clubid = (int)$this->getParam('clubid', '');
        $announcement = trim(strip_tags($this->getParam('announcement', '')));

        $club = new Club();
        $data = $club->announcement($clubid, $announcement);

        $this->render($data);
    }

    public function breakupAction()
    {
        $uid = Context::get('userid');
        $clubid = (int)$this->getParam('clubid', '');

        $club = new Club();
        $data = $club->breakup($clubid);

        $this->render($data);
    }

    public function applyAction()
    {
        $uid = Context::get('userid');
        $clubid = (int)$this->getParam('clubid', '');

        $club = new Club();
        $data = $club->apply($uid, $clubid);

        $this->render($data);
    }

    public function acceptAction()
    {
        $uid = Context::get('userid');
        $applyid = (int)$this->getParam('applyid', '');

        $club = new Club();
        $data = $club->accept($applyid, $uid);

        $this->render($data);
    }

    public function rejectAction()
    {
        $uid = Context::get('userid');
        $applyid = (int)$this->getParam('applyid', '');

        $club = new Club();
        $data = $club->reject($applyid, $uid);

        $this->render($data);
    }

    public function cleanApplyAction()
    {
        $uid = Context::get('userid');

        $club = new Club();
        $data = $club->cleanApply($uid);

        $this->render($data);
    }

    public function applyListAction()
    {
        $uid = Context::get('userid');
        $offset = (int)$this->getParam("offset", 0);
        $num = (int)$this->getParam("num", 20);

        $club = new Club();
        $data = $club->applyList($uid, $offset, $num);

        $this->render($data);
    }

    public function quitAction()
    {
        $uid = Context::get('userid');

        $club = new Club();
        $data = $club->quit($uid);

        $this->render($data);
    }

    public function outAction()
    {
        $uid = Context::get('userid');
        $memberuid = (int)$this->getParam('memberuid', '');

        $club = new Club();
        $data = $club->out($uid, $memberuid);

        $this->render($data);
    }

    public function setStaffAction()
    {
        $uid = Context::get('userid');
        $memberuid = (int)$this->getParam('memberuid', '');
        $action = trim(strip_tags($this->getParam('action', '')));

        $club = new Club();
        $data = $club->setStaff($uid, $memberuid, $action);

        $this->render($data);
    }

    public function memberListAction()
    {
        $uid = Context::get('userid');
        $clubid = (int)$this->getParam('clubid', '');
        $offset = (int)$this->getParam("offset", 0);
        $num = (int)$this->getParam("num", 20);

        $club = new Club();
        $data = $club->memberList($clubid, $offset, $num);

        $this->render($data);
    }

    public function clubListAction()
    {
        $uid = Context::get('userid');
        $search_text = trim(strip_tags($this->getParam('search_text', '')));
        $offset = (int)$this->getParam("offset", 0);
        $num = (int)$this->getParam("num", 20);

        $club = new Club();
        $data = $club->clubList($offset, $num, $search_text);

        $this->render($data);
    }
}