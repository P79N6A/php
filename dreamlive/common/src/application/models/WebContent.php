<?php
class WebContent
{
    public function getContentList($category_fid, $offset, $limit)
    {
        $dao_web_content = new DAOWebcontent();
        list($total, $list) = $dao_web_content->getList($category_fid, $offset, $limit);
        return array('list'=>$list, 'total'=>$total);
    }

    public function getContent($contentid)
    {
        $dao_web_content = new DAOWebcontent();
        $content = $dao_web_content->getContent($contentid);

        return $content;
    }
}
