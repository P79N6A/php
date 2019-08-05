<?php
class ProductController extends BaseController
{
    public function getProductListAction()
    {
        $cateid = intval($this->getParam("cateid", 0));
        $product = new Product();
        $product_list = $product->getBeautyFaceuFilter($cateid);

        if($product_list ) {
            $uid = Context::get("userid");
            if ($uid) {
                Product::mergeUserBag($uid, $cateid, $product_list);
            }

            foreach($product_list as &$product) {
                if(!isset($product['extends']) || !is_string($product['extends']) || !($extends = @json_decode($product['extends'], true)) ) { continue;
                }
                $product['extends'] = $extends;
                $platform=Context::get("platform");
                if ($product['cateid']==1&&$platform=='android') {
                    $product['extends']=$this->changeBeautyParamForAndroid($product['extends']);
                }
            }
        }

        $this->render($product_list);
    }
    private function changeBeautyParamForAndroid($extends)
    {
        $chin=isset($extends['beauty']['params']['chinShaperLevel'])?$extends['beauty']['params']['chinShaperLevel']:0.00;
        $extends['beauty']['params']['chinShaperLevel']=intval($chin);
        return $extends;

    }
    public function getCategoryListAction()
    {
        $cateid = intval($this->getParam("cateid", 0));
        $product = new Product();
        $product_list = $product->getBeautyFaceuFilter($cateid, true);
        if($product_list ) {
            foreach($product_list as &$product) {
                if(!isset($product['extends']) || !is_string($product['extends']) || !($extends = @json_decode($product['extends'], true)) ) { continue;
                }
                $product['extends'] = $extends;
            }
        }

        $this->render($product_list);
    }

    public function getGoodsListAction()
    {
        $cateid = intval($this->getParam("cateid", 0));
        $product = new Product();
        $cateids=empty($cateid)?[]:[$cateid,];
        $product_list = $product->getStore($cateids);
        
        $this->render(["list"=>$product_list]);
    }

    public function useAction()
    {
        $uid = Context::get("userid");
        $product_id = intval($this->getParam("productid"));

        Interceptor::ensureNotEmpty($uid, ERROR_PARAM_IS_EMPTY, "uid");
        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");
        Interceptor::ensureNotEmpty($product_id, ERROR_PARAM_IS_EMPTY, "productid");

        $product = new Product();
        $product_info = $product->used($uid, $product_id);
        $ext_info = json_decode($product_info['extends'], true);

        $this->render(['url' => isset($ext_info['zipurl']) ? $ext_info['zipurl'] : '', 'md5'=>isset($ext_info['zipmd5']) ? $ext_info['zipmd5'] : '' ]);
    }

    public function addProductAction()
    {
        $name=strip_tags(trim($this->getParam("name", "")));
        $image=strip_tags(trim($this->getParam("image", "")));
        $cateid=intval(trim($this->getParam("cateid", 0)));
        $type=intval($this->getParam("type", 0));
        $tag=strip_tags(trim($this->getParam("tag", "")));
        $price=strip_tags(trim($this->getParam("price", 0)));
        $expire=intval(strip_tags(trim($this->getParam("expire", 0))));
        $unit=$this->getParam("unit", "");
        $currency=intval(strip_tags(trim($this->getParam("currency", 2))));
        $online=strip_tags(trim($this->getParam("online", "N")));
        $deleted=$this->getParam("deleted", "N");
        $mark=strip_tags(trim($this->getParam("mark", "")));
        $weight=!empty($this->getParam("weight", 1))?$this->getParam("weight", 1):1;
        $remark=$this->getParam("remark", "");

        $extends=empty($this->getParam('extends', ""))?[]:json_decode($this->getParam('extends'), true);

        /*if ($cateid&&($cateid==Product::CATEID_BEAUTY||$cateid==Product::CATEID_EFFECT||$cateid==Product::CATEID_FACEU)){
            $extends=
            $zip_url=strip_tags(trim($this->getParam("zipurl","")));
            $zip_md5=strip_tags(trim($this->getParam("zipmd5","")));
            if ($zip_url&&$zip_md5){
                $extends=array('zipurl'=>$zip_url,'zipmd5'=>$zip_md5);
            }
        }*/


        Interceptor::ensureNotEmpty($name, ERROR_PARAM_IS_EMPTY, ["name 参数为空"]);
        Interceptor::ensureNotEmpty($image, ERROR_PARAM_IS_EMPTY, ["image 参数为空"]);
        Interceptor::ensureNotEmpty($cateid, ERROR_PARAM_IS_EMPTY, ["cateid 参数为空"]);
        Interceptor::ensureNotFalse($price>=0, ERROR_PARAM_IS_EMPTY, ["price 参数为空"]);
        //Interceptor::ensureNotEmpty($expire,ERROR_PARAM_IS_EMPTY,["expire 参数为空"] );
        Interceptor::ensureNotEmpty($currency, ERROR_PARAM_IS_EMPTY, ["currency 参数为空"]);
        Interceptor::ensureNotEmpty($online, ERROR_PARAM_IS_EMPTY, ["online 参数为空"]);
        Interceptor::ensureNotEmpty($weight, ERROR_PARAM_IS_EMPTY, ["weight 参数为空"]);

        $product=new Product();
        Interceptor::ensureNotFalse($product->addProduct($name, $image, $cateid, $type, $tag, $price, $expire, $unit, $currency, $online, $deleted, $mark, $weight, $remark, $extends), ERROR_BIZ_PAYMENT_PRODUCT_ADD_FAIL, '添加失败');
        $this->render();
    }

    public function modifyProductAction()
    {
        $productid=intval($this->getParam("productid", 0));
        $name=strip_tags(trim($this->getParam("name", "")));
        $image=strip_tags(trim($this->getParam("image", "")));
        $cateid=intval(trim($this->getParam("cateid", 0)));
        $type=intval($this->getParam("type", 0));
        $tag=strip_tags(trim($this->getParam("tag", "")));
        $price=strip_tags(trim($this->getParam("price", 0)));
        $expire=intval(strip_tags(trim($this->getParam("expire", 0))));
        $unit=$this->getParam("unit", "");
        $currency=intval(strip_tags(trim($this->getParam("currency", 0))));
        $online=strip_tags(trim($this->getParam("online", "")));
        $deleted=$this->getParam("deleted", "N");
        $mark=strip_tags(trim($this->getParam("mark", "")));
        $weight=strip_tags(trim($this->getParam("weight", 0)));
        $remark=$this->getParam("remark", "");

        Interceptor::ensureNotFalse($price>=0, ERROR_PARAM_IS_EMPTY, ["price 参数为空"]);
        /* $extends=[];
        if ($cateid&&($cateid==Product::CATEID_BEAUTY||$cateid==Product::CATEID_EFFECT||$cateid==Product::CATEID_FACEU)){
            $zip_url=strip_tags(trim($this->getParam("zipurl","")));
            $zip_md5=strip_tags(trim($this->getParam("zipmd5","")));
            if ($zip_url&&$zip_md5){
                $extends=array('zipurl'=>$zip_url,'zipmd5'=>$zip_md5);
            }
        }*/
        $extends=empty($this->getParam('extends', ""))?[]:json_decode($this->getParam('extends'), true);

        Interceptor::ensureNotEmpty($productid, ERROR_PARAM_IS_EMPTY, ["productid 参数为空"]);
        //$this->render(get_defined_vars());
        $product=new Product();
        Interceptor::ensureNotFalse($product->modifyProduct($productid, $name, $image, $cateid, $type, $tag, $price, $expire, $unit, $currency, $online, $deleted, $mark, $weight, $remark, $extends), ERROR_BIZ_PAYMENT_PRODUCT_UPDATE_FAIL);
        $this->render();
    }


    public function buyAction()
    {
        $uid = Context::get("userid");
        $productid = intval($this->getParam("productid"));
        $num = intval($this->getParam("num", 1));
        $num=$num<=0?1:$num;
        $activeid=intval($this->getParam("activeid", 0));

        Interceptor::ensureNotEmpty($uid, ERROR_PARAM_IS_EMPTY, "uid");
        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");
        Interceptor::ensureNotEmpty($productid, ERROR_PARAM_IS_EMPTY, "productid");

        $product = new Product();
        $order_id = $product->buy($uid, $productid, $num, $activeid);

        $this->render(['orderid' => $order_id]);
    }

    public function getPurchasedListAction()
    {
        $uid= $uid = Context::get("userid", 0);
        Interceptor::ensureNotFalse($uid>0, ERROR_PARAM_INVALID_FORMAT, 'uid');

        $version=Context::get('version');
        if (version_compare($version, '3.0.3', '>')) {
            $bag_list=Bag::getPurchasedList($uid);
        }else{
            $cateid=DAOBag::BAG_CATEID_RIDE;
            $contain_expire=true;
            $bag_list=Bag::bagList($uid, $cateid, $contain_expire);
            $bag_list=['list'=>$bag_list];
        }

        $this->render($bag_list);
    }

    public function sendRideByActivityAction()
    {
        $type=$this->getParam("type", 0);
        $uid=$this->getParam('uid', 0);
        $rideid=$this->getParam('rideid', 0);
        $expire=$this->getParam('expire', 0);
        $num=$this->getParam("num", 1);
        $notice=$this->getParam('notice', '');
        $ext=$this->getParam('extends', "");
        Interceptor::ensureNotFalse($type>0, ERROR_PARAM_INVALID_FORMAT, 'type');
        Interceptor::ensureNotFalse($uid>0, ERROR_PARAM_INVALID_FORMAT, 'uid');
        Interceptor::ensureNotFalse($rideid>0, ERROR_PARAM_INVALID_FORMAT, 'rideid');
        Interceptor::ensureNotFalse($expire>0, ERROR_PARAM_INVALID_FORMAT, 'expire');
        Interceptor::ensureNotFalse($num>0, ERROR_PARAM_INVALID_FORMAT, 'num');

        $_ext=@json_decode($ext, true);
        if (!$_ext) { $_ext=[];
        }
        $product =new Product();
        $r=null;
        switch ($type){
        case DAOProduct::CATEID_RIDE:
            $r=$product->sendRideByActive($uid, $rideid, $expire, $notice, $_ext);
            break;
        case DAOProduct::CATEID_ANCHOR_LEVEL_TOKEN:
            $r=$product->sendAnchorTokenByActive($uid, $rideid, $expire, $notice, $_ext);
            break;
        case DAOProduct::CATEID_BIG_HORN:
            $r=$product->sendHornByActive($uid, $rideid, $notice, $_ext);
            break;
        default:
            throw new Exception('type is exception');
        }

        $this->render($r);
    }
}
