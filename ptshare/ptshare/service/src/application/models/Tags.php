<?php
class Tags
{

    protected $daoTags;

    public function __construct()
    {

        $this->daoTags = new DAOTags();

    }

    /**
     * @param $name
     * @desc 添加先查询
     */
    public function addTag($tagname)
    {

        $info = $this->daoTags->getTagInfoByName($tagname);

        if (!empty($info)) {

            return $info;

        }
        $tagId = Util::to62(SnowFlake::nextId());

        $data = $this->daoTags->addTag($tagId, $tagname);

        Interceptor::ensureNotEmpty($data, ERROR_PARAM_IS_EMPTY, 'data');

        return array(
            'tagid' => $tagId,
            'tagname' => $tagname
        );
    }

    public function search($keyword, $offset = 0, $num = 20)
    {
        if($num == 0){
            $num = 20;
        }

        $dao = new DAOTags();
        $data = $dao->searchByName($keyword, $offset, $num);

        return $data;
    }

    public function getUserHistory($uid)
    {
        $dao = new DAOTags();
        $result = $dao->getUserHistory($uid);

        return $result;
    }

    public function addUserHistory($uid, $tagid)
    {
        $dao = new DAOTags();

        return $dao->addUserHistory($uid, $tagid);
    }

}