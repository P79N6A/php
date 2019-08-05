<?php
class PreviewResource
{

    protected $daoPreview;

    public function __construct()
    {
        $this->daoPreview = new DAOPreviewResource();
    }

    public function getListByPackageid($packageid)
    {
        return $this->daoPreview->getListByPackageid($packageid);
    }

    public function getListBySellid($packageid)
    {
        return $this->daoPreview->getListBySellid($packageid);
    }

    public function updateStatus($id, $status)
    {
        $allowStatusArr = DAOPreviewResource::getStatusArr();

        if (!isset($allowStatusArr[$status])) {
            throw new BizException(ERROR_MALL_PREVIEW, $status);
        }

        $record = [
            'status'    => $status
        ];

        $affectRow = $this->daoPreview->updateInfo($id, $record);
        if ($affectRow < 1) {
            throw new BizException(ERROR_MALL_PREVIEW, $id);
        }
    }

    public function addSell($type, $sellid, $is_cover, $url)
    {
        return $this->daoPreview->add($type, $sellid, $is_cover, $url);
    }



}