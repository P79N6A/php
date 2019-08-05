<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/12
 * Time: 12:25
 */
class ImgMatch
{
    const SAME_IMG=5;
    const NOT_SAME_IMG=10;
    public static function resizeGray($img)
    {
        $imgInfo=getimagesize($img);
        $imgType=image_type_to_extension($imgInfo[2], false);
        $fun="imagecreatefrom{$imgType}";

        $oldImg=$fun($img);
        $oldW=$imgInfo[0];
        $oldH=$imgInfo[1];

        $newW=8;
        $newH=8;

        $newImg=imagecreatetruecolor($newW, $newH);
        imagecopyresampled($newImg, $oldImg, 0, 0, 0, 0, $newW, $newH, $oldW, $oldH);
        imagedestroy($oldImg);
        imagefilter($newImg, IMG_FILTER_GRAYSCALE, 64);
        return $newImg;

    }

    public static function avgGray($img)
    {
        $avg=0;
        $xP=imagesx($img);
        $yP=imagesy($img);
        for ($x=0;$x<$xP;$x++){
            for ($y=0;$y<$yP;$y++){
                $v=imagecolorat($img, $x, $y);
                $avg+=$v;
            }
        }
        return $avg/64;
        //foreach ($)
    }

    public static function setGray($img)
    {
        $re=[];
        $avg=self::avgGray($img);
        $xP=imagesx($img);
        $yP=imagesy($img);
        for ($x=0;$x<$xP;$x++){
            for ($y=0;$y<$yP;$y++){
                $v=imagecolorallocate($img, $x, $y);
                if ($v>$avg) {
                    $re[]=1;
                }else{
                    $re[]=0;
                }
            }
        }
        return $re;
    }

    public static function isSame($img1,$img2)
    {
        $p1=self::setGray($img1);
        $p2=self::setGray($img2);

        $n=0;
        for($i=0;$i<count($p1);$i++){
            if ($p1[$i]!=$p2[$i]) {
                $n+=1;
            }
        }

        if ($n>self::NOT_SAME_IMG) {
            return false;
        }
        if ($n<self::SAME_IMG) {
            return true;
        }
        return false;
    }
}

$img1=ImgMatch::resizeGray("");
$img2=ImgMatch::resizeGray("");

var_dump(ImgMatch::isSame($img1, $img2));