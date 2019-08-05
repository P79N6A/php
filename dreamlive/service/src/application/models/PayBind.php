<?php
class PayBind
{
    public function add($uid, $account, $realname, $source)
    {
        $dao_paybind = new DAOPayBind();
        return $dao_paybind->add($uid, $account, $realname, $source);
    }
    
    public function update($uid, $account, $realname, $source, $relateid)
    {
        $dao_paybind = new DAOPayBind();
        return $dao_paybind->edit($uid, $account, $realname, $source, $relateid);
    }
    
    public function getPayBindList($uid)
    {
        $dao_paybind = new DAOPayBind();
        return $dao_paybind->getPayBindList($uid);
    }
}
?>