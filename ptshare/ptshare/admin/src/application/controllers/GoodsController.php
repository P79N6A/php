<?php
class GoodsController extends BaseController
{

    protected $modelGoods;

    public function __construct()
    {
        parent::__construct();
        $this->modelGoods = new Goods();
    }

    public function indexAction()
    {

        $page               = $this->getParam("page", 1);
        $limit              = $this->getParam("num", 20);
        $status             = $this->getParam("status", 0);
        $sellid             = $this->getParam("sellid", 0);
        $packageid          = $this->getParam("packageid", 0);
        $category           = $this->getParam("category", '');
        $create_start_time  = $this->getParam("create_start_time", '');
        $create_end_time    = $this->getParam("create_end_time", '');

        $data = $this->modelGoods->getList($page, $limit, $status, $sellid, $packageid, $category, $create_start_time, $create_end_time);
        $buildQuery = [
            'status'                => $status,
            'sellid'                => $sellid,
            'packageid'             => $packageid,
            'category'              => $category,
            'create_start_time'     => $create_start_time,
            'create_end_time'       => $create_end_time,
        ];
        $mutipage = $this->mutipage($data['total'], $page, $limit, http_build_query($buildQuery), "/goods/index");

        $statusData = Goods::getStatusArr();

        $this->assign('statusData', $statusData);
        $this->assign('mutipage', $mutipage);
        $this->assign('data', $data);

        $this->display("include/header.html", "goods/index.html", "include/footer.html");

    }

    public function detailAction()
    {

        $id = $this->getParam('id');

        if (empty($id)){
            Util::jumpMsg("参数异常!", "/goods/index");
        }

        $goodsDetail = $this->modelGoods->getOneById($id);
        if (empty($goodsDetail)) {
            Util::jumpMsg("信息不存在!", "/goods/index");
        }

        $goodsDetail['labels']       = json_decode($goodsDetail['labels'], true);
        $goodsDetail['extends']      = json_decode($goodsDetail['extends'], true);
//print_r($goodsDetail);die;
        $statusData = Goods::getStatusArr();


        $this->assign('goodsDetail', $goodsDetail);
        $this->assign('statusData', $statusData);

        $this->display("include/header.html", "goods/detail.html", "include/footer.html");

    }

    public function updateAction()
    {
        $id = $this->getParam('id');
        $status = $this->getParam('status');
        $description = $this->getParam('description');
        $remark = $this->getParam('remark');

        try {
            $goodsDetail = $this->modelGoods->getOneById($id);
            if (empty($goodsDetail)) {
                Util::jumpMsg('信息不存在', "/goods/index");
            }

            $data = [
                'status'        => $status,
                'description'   => $description,
                'remark'        => $remark
            ];
            $res = $this->modelGoods->setRecord($id, $data);
            if ($res !== false) {
                Util::jumpMsg('修改成功', "/goods/detail?id=".$id);
            }else{
                Util::jumpMsg('修改失败', "/goods/detail?id=".$id);
            }

        } catch (Exception $exception) {
            Util::jumpMsg($exception->getMessage(), "/goods/detail?id=".$id);
        }



    }

    public function addGoodsAction()
    {
        $submit = $this->getParam('submit');
        if (!empty($submit)) {

            $result = ShareClient::uploadImage(urlencode(file_get_contents($_FILES['file']['tmp_name'])));
            if (!isset($result['url']) || empty($result['url'])) {
                Util::jumpMsg('上传图片失败', "/goods/create");
            }

            $fileUrl        = Util::getURLPath($result['url']);
            $grape          = $this->getParam('show_grape');
            $description    = $this->getParam('description');
            $data = [
                'good_sn'       => SnowFlake::nextId(),
                'show_grape'    => $grape,
                'file'          => $fileUrl,
                'description'   => $description,
                'labels'        => '',
                'type'          => 'image',
                'extends'       => '',
                'status'        => Goods::STATUS_AUDIT,
                'is_packed'     => Goods::UNPACKED,
           ];

            $insertId = $this->modelGoods->addRecord($data);
            if ($insertId) {
                Util::jumpMsg('添加成功', "/goods/index");
            }else{
                Util::jumpMsg('添加失败', "/goods/index");

            }

        }
        $this->display("include/header.html", "goods/create.html", "include/footer.html");
    }


}