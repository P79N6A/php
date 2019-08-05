<?php
class Image
{

    public static function quality($data, $qulity = 70)
    {
        $im = new Imagick();
        $im->readImageBlob($data);
        $im->setImageCompressionQuality($qulity);

        $data = $im->getImageBlob();

        $im->destroy();

        return $data;
    }

    public static function thumbnail($data, $width, $height, $qulity = 70)
    {
        $im = new Imagick();
        $im->readImageBlob($data);
        $im->setImageCompressionQuality($qulity);

        $im->thumbnailImage($width, $height, true);

        $data = $im->getImageBlob();
        $im->destroy();

        return $data;
    }

    public static function thumbnailSquare($data, $width, $height, $qulity = 70)
    {
        $im = new Imagick();
        $im->readImageBlob($data);

        $size = $im->getImagePage();
        $src_width = $size['width'];
        $src_height = $size['height'];
        $crop_x = 0;
        $crop_y = 0;

        $crop_w = $src_width;
        $crop_h = $src_height;

        if ($src_width * $height > $src_height * $width) {
            $crop_w = intval($src_height * $width / $height);
        } else {
            $crop_h = intval($src_width * $height / $width);
        }

        $crop_x = intval(($src_width - $crop_w) / 2);

        $im->setImageCompressionQuality($qulity);
        // $im->modulateImage(105, 100, 100);

        $im->cropImage($crop_w, $crop_h, $crop_x, $crop_y);
        $im->thumbnailImage($width, $height, true);

        $data = $im->getImageBlob();
        $im->destroy();

        return $data;
    }

    public static function squareImage($data)
    {
        $im = new Imagick();
        $im->readImageBlob($data);

        $size = $im->getImagePage();
        $src_width = $size['width'];
        $src_height = $size['height'];

        if($src_width == $src_height){
            return $data;
        }

        if($src_width>$src_height){
            $src_width = $src_height;
        }else{
            $src_height = $src_width;
        }

        $im->cropThumbnailImage($src_width, $src_height);
        $data = $im->getImageBlob();
        $im->destroy();

        return $data;
    }


}
?>
