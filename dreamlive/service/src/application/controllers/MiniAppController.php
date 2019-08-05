
<?php
class MiniAppController extends BaseController
{
    //探探图片获取
    public function TantanAction()
    {
        $offset      = $this->getParam('offset') ? trim($this->getParam('offset')) : 0;
        $num         = (int) $this->getParam('num', 20);

        $feeds       = new Feeds();
        //获取热门用户
        $live_hot    = $feeds -> newGetBucketFeedsTan("china_live_hot", $offset, $num);
        /*if(empty($live_hot)){

        }*/

        $this ->render($live_hot);
    }

    public function acodeAction()
    {
        $xuan=$this->getParam("xuan", "");
        Interceptor::ensureNotEmpty($xuan, ERROR_PARAM_IS_EMPTY, "xuan");
        $this->render(['url'=>MiniApp::mkAcode($xuan)]);
    }
}
