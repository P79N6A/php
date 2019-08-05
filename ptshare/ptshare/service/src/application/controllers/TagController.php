<?php
class TagController extends BaseController
{

    public function addTagAction()
    {
        $uid = Context::get("userid");
        $name = $this->getParam("name");

        Interceptor::ensureNotEmpty($name, ERROR_PARAM_INVALID_FORMAT, 'name');

        $tag = new Tags();
        $tagInfo = $tag->addTag($name);
        if($uid){
            $tag->addUserHistory($uid, $tagInfo['tagid']);
        }

        $this->render(array(
            'tag' => $tagInfo)
        );

    }

    public function searchAction()
    {
        $keyword = trim($this->getParam("keyword", ""));
        $offset  = (int) $this->getParam("offset", 0);
        $num     = (int) $this->getParam("num", 20);

        Interceptor::ensureNotEmpty($keyword, ERROR_PARAM_IS_EMPTY, "keyword");
        if($num > 200){
            $num = 200;
        }
        if($offset > 1000){
            $this->render(array(
                'tag'  => [],
                'offset' => $offset,
            ));
        }

        $tag = new Tags();
        $tagList = $tag->search($keyword, $offset, $num);

        $this->render(array(
            'tag'  => $tagList,
            'offset' => $offset + count($musicList),
        ));
    }

    public function getUserHistoryAction()
    {
        $uid    = Context::get("userid");

        Interceptor::ensureNotEmpty($uid, ERROR_USER_NOT_EXIST);

        $tag = new Tags();
        $tagList = $tag->getUserHistory($uid);

        $this->render(array(
            'tag'  => $tagList,
        ));
    }


}