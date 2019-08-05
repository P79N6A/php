<?php
class PackagePreviews
{

    protected $daoPackagePreviews;
    protected $daoPreviews;

    public function __construct()
    {
        $this->daoPackagePreviews = new DAOPackagePreviews();
        $this->daoPreviews = new DAOPreviewResource();
    }

    public function add($packageId, $list = [], $field = 'id')
    {
        Interceptor::ensureNotFalse($packageId > 0, ERROR_PARAM_INVALID_FORMAT, 'error package id: '.$packageId);
        Interceptor::ensureNotFalse(is_array($list), ERROR_PARAM_INVALID_FORMAT, 'empty list');

        foreach ($list as $value)
        {
            $result = $this->daoPackagePreviews->add($packageId, $value[$field]);
            Interceptor::ensureNotFalse($result > 0, ERROR_PARAM_INVALID_FORMAT, 'add package previews error');
        }

    }

    public function getListByPackageId($packageId)
    {
        $packagePreviews = $this->daoPackagePreviews->getListByPackageId($packageId);
        Interceptor::ensureNotFalse(is_array($packagePreviews), ERROR_PARAM_INVALID_FORMAT, 'empty package previews');
        $previewIdArr = Util::arrayToIds($packagePreviews, 'preview_id');
        $ids = implode(',', $previewIdArr);
        $list = $this->daoPreviews->getListByIds($ids);
        if (!empty($list)) {
            foreach ($list as $key => $value)
            {
                $list[$key]['url'] = Util::joinStaticDomain($value['url']);
            }
        }
        return $list;

    }


}