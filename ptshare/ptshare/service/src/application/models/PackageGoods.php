<?php
class PackageGoods
{

    protected $daoPackageGoods;
    protected $daoGoods;

    public function __construct()
    {
        $this->daoPackageGoods = new DAOPackageGoods();
        $this->daoGoods = new DAOGoods();
    }

    public function add($packageId, $list = [], $field = 'id')
    {
        Interceptor::ensureNotFalse($packageId > 0, ERROR_PARAM_INVALID_FORMAT, 'error package id: '.$packageId);
        Interceptor::ensureNotFalse(is_array($list), ERROR_PARAM_INVALID_FORMAT, 'empty list');

        foreach ($list as $value)
        {
            $result = $this->daoPackageGoods->add($packageId, $value[$field]);
            Interceptor::ensureNotFalse($result > 0, ERROR_PARAM_INVALID_FORMAT, 'add package goods error');
        }

    }

    public function getListByPackageId($packageId)
    {
        $packageGoods = $this->daoPackageGoods->getListByPackageId($packageId);
        Interceptor::ensureNotEmpty($packageGoods, ERROR_PARAM_INVALID_FORMAT, 'empty package list');

        $goodsIdArr = Util::arrayToIds($packageGoods, 'goods_id');
        $ids = implode(',', $goodsIdArr);
        $list = $this->daoGoods->getListByIds($ids);
        if (!empty($list)) {
            foreach ($list as $key => $value)
            {
                $list[$key]['labels'] = !empty($value['labels']) ? json_decode($value['labels'], true) : '';
                $list[$key]['show_name'] = '宝贝'.Util::intToCn($key + 1);
                $list[$key]['file'] = Util::joinStaticDomain($value['file']);
            }
        }
        return $list;
    }




}