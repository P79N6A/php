<?php
class DController extends BaseController
{
    // http://60.205.82.28:8589/d/l
    public function LAction()
    {
        $this->render(
            [
            'yijianjiaoyou.com',
            'chosseone.com',
            ]
        );
    }
}