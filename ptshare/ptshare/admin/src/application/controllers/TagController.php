<?php
class TagController extends BaseController
{

    protected $modelTag;

    public function __construct()
    {

        parent::__construct();

        $this->modelTag = new Tags();

    }

    public function indexAction()
    {

        $modelTag = new Tags();

        $list = $modelTag->getList();

        $this->assign('list', $list[1]);

        $this->display("include/header.html", "tags/index.html", "include/footer.html");


    }


}