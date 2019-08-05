<?php
class King
{
    public function getList($userid)
    {
        $dao_king = new DAOKing();
        $list = $dao_king->getList($userid);

        return $list;
    }

    public function getTodayLevel($userid)
    {
        $dao_king = new DAOKing();
        $level = $dao_king->getTodayLevel($userid);

        return $level;
    }

}
?>