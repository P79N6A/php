<?php

class MiniApp
{
    public static function mkAcode($xuan)
    {
        $d = WxMiniProgram::getAcode($xuan);

        $str = @unpack("C2c", $d);
        $typeCode = intval($str['c1'].$str['c2']);

        switch($typeCode){
        case 255216:
            $suffix = 'jpg';
            break;
        case 7173:
            $suffix = 'gif';
            break;
        case 6677:
            $suffix = 'bmp';
            break;
        case 13780:
            $suffix = 'png';
            break;
        case 8273:
            $suffix = 'webp';
            break;
        default:
            $suffix = 'unknown';
        }


        $storage = new Storage();
        return  $storage->addImage($suffix, $d, 'other', false);

    }
    
}