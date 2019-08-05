<?php


class GameStatController extends BaseController
{

    //添加统计数据
    public function addDataAction()
    {
        $total_num=$this->getParam('total_num', 0);
        $stake_num=$this->getParam('stake_num', 0);
        $round_num=$this->getParam('round_num', 0);
        $stake_amount=$this->getParam('stake_amount', 0);
        $stake_income=$this->getParam("stake_income", 0);
        $banker_num=$this->getParam('banker_num', 0);
        $banker_income=$this->getParam('banker_income', 0);
        $robots_income=$this->getParam('robots_income', 0);
        $total_split=$this->getParam('total_split', 0);
        $total_income=$this->getParam('total_income', 0);
        $day=$this->getParam('day', '0000-00-00');
        $robots_deposit=$this->getParam('robots_deposit', 0);

        $stat=new DAOHorseracingStat();
        $r=$stat->addData($total_num, $stake_num, $round_num, $stake_amount, $stake_income, $banker_num, $banker_income, $robots_income, $total_split, $total_income, $day, $robots_deposit);

        $this->render($r);
    }

}