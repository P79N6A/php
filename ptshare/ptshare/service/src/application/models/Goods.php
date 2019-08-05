<?php
class Goods
{

    protected $daoGoods;

    public function __construct()
    {
        $this->daoGoods = new DAOGoods();
    }

    /**
     * 根据packageid获取goods信息
     * @param int $packageid
     * @return array
     */
    public static function getListByPackageid($packageid)
    {
        $DAOGoods = new DAOGoods();
        $list = $DAOGoods->getListByPackageid($packageid);
        if (!empty($list)) {
            foreach ($list as $key => $value) {
                $list[$key]['labels'] = !empty($value['labels']) ? json_decode($value['labels'], true) : '';
                $list[$key]['extends'] = !empty($value['extends']) ? json_decode($value['extends'], true) : '';
            }
        }
        return $list;
    }

    public function updateInfo($goods)
    {
        $this->daoGoods->startTrans();

        try{
            foreach ($goods as $key => $good)
            {
                $show_grape          = isset($good['show_grape']) ? $good['show_grape'] : 0;
                $worth_grape         = isset($good['worth_grape']) ? $good['worth_grape'] : 0;
                $status              = isset($good['status']) ? $good['status'] : DAOGoods::STATUS_DEFAULT;
                $refuse_reason		 = isset($good['refuse_reason']) ? $good['refuse_reason'] : '';
                $id                  = $good['id'];

                if (!in_array($good['status'], [DAOGoods::STATUS_DEFAULT, DAOGoods::STATUS_AUDIT]) ) {
                    $show_grape = $worth_grape = 0;
                }

                $record = [
                    'show_grape'    => $show_grape,
                    'worth_grape'   => $worth_grape,
                    'status'        => $status,
                	'refuse_reason' => $refuse_reason
                ];

                $effectRow = $this->daoGoods->updateInfoById($id, $record);

                if (false === $effectRow) {
                    throw new BizException(ERROR_MALL_GOODS_UPDATE, $id);
                }

            }
            $this->daoGoods->commit();
        } catch (Exception $exception) {
            $this->daoGoods->rollback();
            throw new BizException(ERROR_MALL_GOODS_UPDATE, json_encode($goods));
        }

    }



}