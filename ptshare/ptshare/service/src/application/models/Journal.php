<?php
class Journal
{
    public static function getJournalGrapeInList($uid, $num, $offset)
    {
        $arrTemp = array();

        $DAOJournal = new DAOJournal($uid);
        $list = $DAOJournal->getJournalList($uid, Account::CURRENCY_GRAPE, '2,9', 'IN', $num, $offset);
        foreach ($list as $key => $val) {
            $temp = array(
                'orderid' => $list[$key]['orderid'],
                'amount'  => intval($list[$key]['amount']),
                'type'    => $list[$key]['type'],
                'direct'  => $list[$key]['direct'],
                'remark'  => $list[$key]['remark'],
                'addtime' => $list[$key]['addtime'],
            );
            $arrTemp[] = $temp;
            $offset = $val['id'];
        }
        $total = $DAOJournal->getJournalTotal($uid, Account::CURRENCY_GRAPE, '2,9', 'IN');
        $more = (bool) $DAOJournal->getMoreJournalList($uid, Account::CURRENCY_GRAPE, '2,9', 'IN', $offset);
        return array($arrTemp, $total, $offset, $more);
    }
}