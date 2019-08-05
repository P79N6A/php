<?php
class IndexController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

	public function indexAction()
	{
        $this->display("index/index.html");
    }

    public function topAction()
    {
        $this->display('index/top.html');
    }

    public function middleAction()
    {
        $this->display('index/middle.html');
    }

    public function leftAction()
    {
        $this->assign("menu_list", Context::getConfig("MENU_LIST"));

        $this->display('index/left.html');
    }

    public function WelcomeAction()
    {
        $this->display('index/welcome.html');
    }
}
?>
