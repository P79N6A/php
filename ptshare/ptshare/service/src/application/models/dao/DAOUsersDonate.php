<?php

/**
 * Class DAOUsersDonate
 * @desc 用户捐赠信息
 */
class DAOUsersDonate extends DAOProxy
{


    const

        STATUS_DEFAULT              = 100,  // 默认
        STATUS_SUCCESS              = 200,  // 成功
        STATUS_ERROR                = 500,  // 失败

    END = '';



    public function __construct(){
        $this->setDBConf("MYSQL_CONF_MALL");
        $this->setTableName("users_donate");
        parent::__construct();
    }

    public static function getStatusData($status='')
    {

        $data = [
            self::STATUS_DEFAULT    => '默认',
            self::STATUS_SUCCESS    => '成功',
            self::STATUS_ERROR      => '失败',
        ];

        return (isset($data[$status]) && $status) ? $data[$status] : $data;

    }


    public function add($uid, $contact)
    {

        $data = [
            'uid'           => $uid,
            'contact'       => $contact,
        ];

        return $this->insert($this->getTableName(), $data);

    }

    public function updateStatus($id, $status, $remark='')
    {

        $record = [
            'status'        => $status,
        ];

        if (!empty($remark)) {
            $record['remark'] = $remark;
        }

        $condition = "id = ? and status <> ?";
        $params = [$id, $status];

        return $this->update($this->getTableName(), $record, $condition, $params);

    }

    public function updateExpressId($id, $expressId)
    {

        $record = [
            'express_id'        => $expressId,
        ];

        $condition = "id = ? ";
        $params = [$id];

        return $this->update($this->getTableName(), $record, $condition, $params);

    }

    public function getOneById($id)
    {
        $sql = "select * from ".$this->getTableName()." where id = ? ";
        return $this->getRow($sql, [$id]);
    }

    public function getList($uid, $limit, $offset)
    {

        $countSql = "select count(*) as total from " . $this->getTableName(). " WHERE uid = ? ";
        $countResult = $this->getRow($countSql, [$uid]);
        $total = $countResult['total'];
        $list = [];
        $more = false;

        if ($total > 0){
            $selectSql = "select * from " . $this->getTableName(). "  WHERE uid = ? ";
            $offsetSql = '';
            if ($offset) {
                $offsetSql = " and id < ".$offset;
            }
            $orderSql = " order by id desc limit ?";
            $list = $this->getAll($selectSql.$offsetSql.$orderSql, [$uid, $limit]);
            $offset = end($list)['id'] ? end($list)['id'] : 0;
            if ($offset) {
                $offsetSql .= " and id <".$offset;
                $moreList = $this->getAll($selectSql.$offsetSql, [$uid, $limit]);
                $more = (bool)$moreList;
            }
        }

        return ['list' => $list, 'offset' => $offset, 'more' => $more];

    }

    public function updateContact($id, $data)
    {
        $record = [
            'contact'        => json_encode($data),
        ];

        $condition = "id = ?";
        $params = [$id];

        return $this->update($this->getTableName(), $record, $condition, $params);
    }




}