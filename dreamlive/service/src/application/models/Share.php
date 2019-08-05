<?php
class Share
{
    public function get($uid, $role, $type, $target, $title, $hour = null, &$special = false)
    {
        $dao_share = new DAOShare();
        $datas = $dao_share->get(0, $role, $type, $target, trim($title) != '' ? 'Y' : 'N');
        $hour === null && $hour = date('G'); // 0 - 23
        $second = date("i");
        $time = date('Y-m-d H:i:s');
        $datas = $this->filter($datas, $hour, $time, $second);
        // 表示针对此uid设置了文案
        $datas && $special = true;
        if (!$datas && $uid) {
            $datas = $dao_share->get($uid, $role, $type, $target, trim($title) != '' ? 'Y' : 'N');
            $datas = $this->filter($datas, $hour, $time, $second);
        }

        return $datas;
    }

    public function getSpecial($uid, $role, $type, $target, $title)
    {
        $dao_share = new DAOShare();
        $datas = $dao_share->get($uid, $role, $type, $target, trim($title) != '' ? 'Y' : 'N');
        $hour = date('G'); // 0 - 23
        $time = date('Y-m-d H:i:s');
        $second = date("i");

        $datas = $this->filter($datas, $hour, $time, $second);

        return $datas;
    }

    public function fetch($offset, $num, $uid, $role, $type, $target, $titled)
    {
        $dao_share = new DAOShare();
        $data = $dao_share->fetch($offset, $num, $uid, $role, $type, $target, $titled);
        if ($data) {
            $last = end($data);
            $offset = $last['id'];
        } else {
            $offset = 0;
        }

        return array($offset, $data);
    }

    public function add($uid, $role, $type, $target, $titled, $begin, $finish, $starttime, $endtime, $title, $content)
    {
        $dao_share = new DAOShare();
        return $dao_share->add($uid, $role, $type, $target, $titled, $begin, $finish, $starttime, $endtime, $title, $content);
    }

    public function update($id, $uid, $role, $type, $target, $titled, $begin, $finish, $starttime, $endtime, $title, $content)
    {
        $dao_share = new DAOShare();
        return $dao_share->update($id, $uid, $role, $type, $target, $titled, $begin, $finish, $starttime, $endtime, $title, $content);
    }

    public function delete($id)
    {
        $dao_share = new DAOShare();
        return $dao_share->delete($id);
    }

    protected function filter($list, $hour, $time, $second)
    {
        foreach ($list as $k => $data)
        {
            // 过期时间检查
            if (($data['starttime'] != '0000-00-00 00:00:00' && $data['starttime'] > $time)
                || ($data['endtime'] != '0000-00-00 00:00:00' && $data['endtime'] < $time)
            ) {
                unset($list[$k]);continue;

            }
            $a = explode(':', $data['begin']);
            $b = explode(':', $data['finish']);
            $beginHour = $a[0];
            $beginSecond = empty($a[1]) ? 0 : $a[1];

            $finishHour    = $b[0];
            $finishSecond = empty($b[1]) ? 0 : $b[1];
            // 时段检查
            if ($beginHour < $finishHour) {
                if ($beginHour > $hour || $finishHour < $hour) {
                    unset($list[$k]);continue;
                } elseif (($beginHour == $hour &&  $beginSecond > $second) || ($finishHour == $hour && $finishSecond < $second)) {
                    unset($list[$k]);continue;
                }
            } elseif ($beginHour > $finishHour) {
                $tmpHour = $hour;
                $hour <= $finishHour && $tmpHour += 24;
                $toHour = $finishHour + 24;
                if ($beginHour > $tmpHour || $toHour <= $tmpHour) {
                    unset($list[$k]);continue;
                } elseif (($beginHour == $tmpHour &&  $beginSecond > $second) || ($finishHour == $tmpHour && $finishSecond < $second)) {
                    unset($list[$k]);continue;
                }
            }
        }

        return $list;
    }
    
    public static function mixed($types, array $relateids)
    {
        $dao_share_callback = new DAOShareCallback();
        $result = array();
        foreach ($types as $type) {
            foreach ($relateids as $relateid) {
                $counter = $dao_share_callback->getCount($type, $relateid);
                
                $result[$relateid][$type] = $counter!==false ? $counter : 0;
            }
        }
    
        return $result;
    }
    
    public function shareCounter($type, $relateid)
    {
        switch($type){
        case 'live':
        case 'before_live':
            $counter_type  = Counter::COUNTER_TYPE_SHARE_LIVE;
            break;
        case 'image':
            $counter_type  = Counter::COUNTER_TYPE_SHARE_IMAGE;
            break;
        case 'user':
            $counter_type  = Counter::COUNTER_TYPE_SHARE_USER;
            break;
        case 'reply':
            $counter_type  = Counter::COUNTER_TYPE_SHARE_REPLY;
            break;
        }

        return Counter::increase($counter_type, $relateid, 1);
    }
}
