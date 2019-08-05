<?php
class Resource
{
    const SOURCE_GIFT='gift';
    const SOURCE_PRODUCT='product';

    const SOURCE_TYPE_DYNAMIC=1;
    const SOURCE_TYPE_STATIC=0;

    public static function getResource()
    {
        return self::getProductResource();
    }

    public static function getProductResource()
    {
        $resourceCateids=array(DAOProduct::CATEID_RIDE,);
        $re=[];
        $list=Product::getAll();//其实应该包含删除的
        foreach ($list as $i){
            if (!in_array($i['cateid'], $resourceCateids)) { continue;
            }
            $ext=@json_decode($i['extends'], true);
            if ($ext) {
                //if (isset($ext['isDynamic'])&&$ext['isDynamic']){
                    $zipmd5=isset($ext['zipmd5'])?$ext['zipmd5']:"";
                    $zipurl=isset($ext['zipurl'])?$ext['zipurl']:"";
                if (empty($zipurl)) { continue;
                }
                    $isDynamic=isset($ext['isDynamic'])&&$ext['isDynamic']?self::SOURCE_TYPE_DYNAMIC:self::SOURCE_TYPE_STATIC;
                    $re[]=array(
                        'id'=>$i['productid'],
                        'source'=>self::SOURCE_PRODUCT,
                        'isDynamic'=>$isDynamic,
                        'zipmd5'=>$zipmd5,
                        'zipurl'=>$zipurl,
                    );
                    //}
            }
        }
        return $re;
    }

    public static function getGiftResource()
    {
        return array();
    }
}