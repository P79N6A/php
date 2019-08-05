<?php
class DAOShare extends DAOProxy
{
    const STATUS_NORMAL     = 0;
    const STATUS_DELETE     = 1;

    const TYPES=array('live', 'before_live', 'replay', 'image', 'video', 'user', 'html5', 'screenshot', 'record');
    const TARGETS=array('wx', 'weibo', 'qq', 'qzone', 'circle','facebook', 'twitter','miniapp');

    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_LIVE");
        $this->setTableName('share');
    }

    public function get($uid, $role, $type, $target, $titled = 'Y')
    {
        $sql = "select * from {$this->getTableName()} where uid = ? and role = ? and type = ? and target = ? and titled = ? and status = ?";
        return $this->getAll($sql, array($uid, $role, $type, $target, $titled, self::STATUS_NORMAL));
    }


    public function fetch($offset, $num, $uid, $role, $type, $target, $titled = 'Y')
    {
        $where = '';
        $where .=$target == 'all' ? " and target in (".self::wrapQoute(self::TARGETS).") ":" and target = '{$target}' ";
        $where .= empty($role) ? " and 1 = 1 ":" and role = {$role} ";
        $where .= empty($type) ? " and type in (".self::wrapQoute(self::TYPES).")":" and type = '{$type}' ";
        $sql = "select * from {$this->getTableName()} where id > ? and status = ? and uid= ?  $where and titled = ? order by id desc limit $offset, $num ";

        return $this->getAll($sql, array($offset, self::STATUS_NORMAL, $uid, $titled));
    }

    private static function wrapQoute($arr)
    {
        $tt=$arr;
        array_walk(
            $tt, function (&$v,$k) {
                $v="'".$v."'";
            }
        );
        return implode(",", $tt);
    }

    public function add($uid, $role, $type, $target, $titled, $begin, $finish, $starttime, $endtime, $title, $content)
    {
        $data = array(
        'uid'         => $uid,
        'role'         => $role,
        'type'         => $type,
        'target'     => $target,
        'begin'     => $begin,
        'finish'     => $finish,
        'starttime'    => $starttime,
        'endtime'     => $endtime,
        'title'     => $title,
        'titled'     => $titled,
        'content'     => $content,
        'addtime'     => date('Y-m-d H:i:s'),
        );
        return $this->insert($this->getTableName(), $data);
    }

    public function update($id, $uid, $role, $type, $target, $titled, $begin, $finish, $starttime, $endtime, $title, $content)
    {
        $data = array(
        'uid'         => $uid,
        'role'         => $role,
        'type'         => $type,
        'target'     => $target,
        'titled'     => $titled,
        'begin'     => $begin,
        'finish'     => $finish,
        'starttime' => $starttime,
        'endtime'     => $endtime,
        'title'     => $title,
        'content'     => $content,
        'modtime' => date('Y-m-d H:i:s'),
        );
        return parent::update($this->getTableName(), $data, 'id = ?', $id) !== false;
    }
    
    public function getType($shareid)
    {
        $sql = "select type from {$this->getTableName()} where id = ?";
    
        return $this->getOne($sql, array($shareid));
    }

    public function delete($id)
    {
        return parent::update($this->getTableName(), array('status' => self::STATUS_DELETE), 'id = ?', $id) !== false;
    }
}
