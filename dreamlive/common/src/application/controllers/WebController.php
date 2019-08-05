<?php
class WebController extends BaseController
{
    public function indexAction()
    {
        $fid = $this->getParam('fid') ? intval($this->getParam('fid')) : 0;

        $web_category = new WebCategory();
        $index = $web_category->getCategory($fid);

        $this->render($index);
    }

    public function getListAction()
    {
        $fid = $this->getParam('fid') ? intval($this->getParam('fid')) : 0;
        $offset = !empty($this->getParam('offset')) ? intval($this->getParam('offset')) : 0;
        $limit = $this->getParam('limit') ? intval($this->getParam('limit')) : 20;

        $web_content = new WebContent();
        $list = $web_content->getContentList($fid, $offset, $limit);

        $this->render($list);

    }

    public function contentAction()
    {
        $contentid = $this->getParam('contentid') ? intval($this->getParam('contentid')) : 0;

        $web_content = new WebContent();
        $content = $web_content->getContent($contentid);

        $this->render($content);
    }
}
