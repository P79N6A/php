<?php
class PosterController extends BaseController
{
    public function getPosterAction()
    {
        $userid = Context::get("userid");

        Interceptor::ensureNotFalse($userid > 0, ERROR_PARAM_INVALID_FORMAT, "userid");

        $userinfo = User::getUserInfo($userid);
        Interceptor::ensureNotEmpty($userinfo, ERROR_PARAM_INVALID_FORMAT, "userid2");

        $daoSell = new DAOSell();
        $sellNum = (int) $daoSell->getLastSellNum($userid);

        $xcx = new WxProgram();
        $xcxCode = $xcx->getUserInviteWxacodeunlimit($userid);
        Interceptor::ensureNotEmpty($xcxCode, ERROR_PARAM_INVALID_FORMAT, "userid3");

        $configs = array(
            array(
                "background"  => "/res/poster/moban1.png",
                "avatar"      => [195, 252],
                "avatar_size" => 84,
                "avatar_nick" => true,
                "code"        => [591, 570],
                "code_size"   => 120,
                "font_color"  => "#353535",
                "font_nick"    => [418, 306],
                "font_count"   => [470, 407],
                "font_date"    => [568, 927],
                "avatar_bg"   => "/res/poster/white.png",
            ),
            array(
                "background"  => "/res/poster/moban2.png",
                "avatar"      => [195, 390],
                "avatar_size" => 84,
                "avatar_nick" => true,
                "code"        => [120, 780],
                "code_size"   => 138,
                "font_color"  => "#353535",
                "font_nick"    => [418, 444],
                "font_count"   => [521, 528],
                "font_date"    => [597, 999],
                "avatar_bg"   => "/res/poster/yellow.png",
            ),
            array(
                "background"  => "/res/poster/moban3.png",
                "avatar"      => [372, 294],
                "avatar_size" => 84,
                "avatar_nick" => false,
                "avatar_bg"   => "/res/poster/white.png",
                "code"        => [129, 768],
                "code_size"   => 138,
                "font_color"  => "#353535",
                "font_nick"    => [418, 440],
                "font_count"   => [495, 532],
                "font_date"    => [600, 1005]
            ),
            array(
                "background"  => "/res/poster/moban4.png",
                "avatar"      => [195, 252],
                "avatar_size" => 84,
                "avatar_nick" => true,
                "code"        => [591, 570],
                "code_size"   => 120,
                "font_color"  => "#353535",
                "font_nick"    => [418, 306],
                "font_count"   => [470, 407],
                "font_date"    => [541, 895],
                "avatar_bg"   => "/res/poster/white.png",
            ),
            array(
                "background"  => "/res/poster/moban5.png",
                "avatar"      => [195, 390],
                "avatar_size" => 84,
                "avatar_nick" => true,
                "code"        => [120, 780],
                "code_size"   => 138,
                "font_color"  => "#ffffff",
                "font_nick"    => [418, 444],
                "font_count"   => [521, 528],
                "font_date"    => [597, 999],
                "avatar_bg"   => "/res/poster/pink.png",
            ),
            array(
                "background"  => "/res/poster/moban6.png",
                "avatar"      => [372, 294],
                "avatar_size" => 84,
                "avatar_nick" => false,
                "code"        => [129, 768],
                "code_size"   => 138,
                "font_color"  => "#353535",
                "font_nick"    => [418, 440],
                "font_count"   => [495, 528],
                "font_date"    => [600, 1005],
                "avatar_bg"   => "/res/poster/white.png",
            ),
        );
        $path   = Context::getConfig("ROOT_PATH");
        $fontTTC = $path."/res/poster/PingFang.ttc";

        $config = $configs[mt_rand(0,5)];

        $im = new Imagick($path.$config['background']);

        $canvas = new Imagick();
        $canvas->newImage($im->getImageWidth(), $im->getImageHeight(), 'black', 'jpg');

        if($config["avatar_nick"]){
            list($x1,$y1,$x2,$y2) = imagettfbbox(56,0,$fontTTC,$userinfo['nickname']);

            $avatarOffset = 438 - ($x2 - $x1)/2 ;

            $config['avatar'][0] = $avatarOffset < 0? 0 : ( $avatarOffset > 194 ? 194: $avatarOffset);
        }
        $storage = new Storage();
        if(preg_match("/wx\.qlogo\.cn/",$userinfo['avatar'])){
            $avatarContent = file_get_contents($userinfo['avatar']);
        }else{
            try{
                $avatarContent = $storage->getContent(ltrim(Util::getURLPath($userinfo['avatar']),"/"));
            }catch(Exception $e){

            }
        }

        $avatar = new Imagick();
        $avatar->readImageBlob($avatarContent);
        $avatar->thumbnailImage($config['avatar_size'], $config['avatar_size'], true );

        $avatarBg = new Imagick($path.$config['avatar_bg']);
        $avatar->compositeImage($avatarBg, imagick::COMPOSITE_OVER, 0, 0);

        $im->compositeImage($avatar,Imagick::COMPOSITE_OVER, $config['avatar'][0], $config['avatar'][1]);

        $code = new Imagick();
        $code->readImageBlob($xcxCode);
        $code->thumbnailImage($config['code_size'], $config['code_size'], true );

        $im->compositeImage($code,Imagick::COMPOSITE_OVER, $config['code'][0], $config['code'][1]);

        $canvas->compositeImage($im, imagick::COMPOSITE_OVER, 0, 0);

        $draw = new ImagickDraw();
        $draw->setFont($fontTTC);
        $draw->setTextAlignment(2);

        $draw->setFillColor(new ImagickPixel($config['font_color']));
        $draw->setFontSize(42);
        $draw->annotation($config['font_nick'][0], $config['font_nick'][1], $userinfo['nickname']);

        $draw->setFontSize(36);
        $draw->annotation($config['font_count'][0], $config['font_count'][1], $sellNum);
        $draw->setFontSize(32);
        $draw->annotation($config['font_date'][0], $config['font_date'][1], date("Y年m月d日"));

        $canvas->drawImage($draw);

        $storage = new Storage();
        $url = $storage->addImageCache("jpg", $canvas->getImageBlob(), 600);

        $code->destroy();
        // $avatarbg->destroy();
        $avatar->destroy();

        $draw->destroy();
        $im->destroy();
        $canvas->destroy();

        $this->render(
            array(
                "url" => $url
            )
        );

    }

    public function getQaPosterAction()
    {
        $name = $this->getParam("name");

        $configs = array(
            array(
                "background"  => "/res/poster/qa-A.png",
                "avatar_size" => 84,
                "code"        => [591, 570],
                "code_size"   => 120,
                "font_color"  => "#353535",
                "font_nick"    => [200, 150],
                "font_count"   => [470, 407],
                "font_date"    => [568, 927]
            ),
            array(
                "background"  => "/res/poster/qa-B.png",
                "avatar"      => [195, 390],
                "avatar_size" => 84,
                "code"        => [120, 780],
                "code_size"   => 138,
                "font_color"  => "#353535",
                "font_nick"    => [418, 444],
                "font_count"   => [521, 528],
                "font_date"    => [597, 999]
            ),
            array(
                "background"  => "/res/poster/qa-C.png",
                "avatar"      => [372, 294],
                "avatar_size" => 84,
                "code"        => [129, 768],
                "code_size"   => 138,
                "font_color"  => "#353535",
                "font_nick"    => [418, 440],
                "font_count"   => [495, 532],
                "font_date"    => [600, 1005]
            ),
            array(
                "background"  => "/res/poster/qa-D.png",
                "avatar"      => [195, 252],
                "avatar_size" => 84,
                "code"        => [591, 570],
                "code_size"   => 120,
                "font_color"  => "#353535",
                "font_nick"    => [418, 306],
                "font_count"   => [470, 407],
                "font_date"    => [541, 895]
            )
        );
        $path   = Context::getConfig("ROOT_PATH");
        $fontTTC = $path."/res/poster/PingFang.ttc";

        $answerConfig = [
            1 => [
                'A' => 5,
                'B' => 0,
                'C' => 10,
                'D' => 20,
            ],
            2 => [
                'A' => 5,
                'B' => 0,
                'C' => 10,
                'D' => 20,
            ],
            3 => [
                'A' => 5,
                'B' => 0,
                'C' => 10,
                'D' => 20,
            ],
            4 => [
                'A' => 5,
                'B' => 0,
                'C' => 20,
                'D' => 10,
            ],
            5 => [
                'A' => 0,
                'B' => 20,
                'C' => 5,
                'D' => 10,
            ],
            6 => [
                'A' => 20,
                'B' => 5,
                'C' => 0,
                'D' => 10,
            ],
        ];
        $param = [
            1 => 'D',
            2 => 'D',
            3 => 'D',
            4 => 'D',
            5 => 'A',
            6 => 'A'
        ];
        $data = json_encode($param);
//
//        $data = Context::get("data");
        $data = json_decode($data, true);
        $score = 0;
        foreach ($data as $key => $value)
        {
            $score += isset($answerConfig[$key][$value]) ? $answerConfig[$key][$value] : 0;
        }

//            echo '$score :'.$score."\n";
        $config = '';
        if ($score < 20) {
            $config = $configs[0];
        } else if ($score >= 20 && $score < 50) {
            $config = $configs[1];
        } else if ($score >= 50 && $score < 80) {
            $config = $configs[2];
        } else if ($score >= 80) {
            $config = $configs[3];
        }

        $im = new Imagick($path.$config['background']);

        $canvas = new Imagick();
        $canvas->newImage($im->getImageWidth(), $im->getImageHeight(), 'black', 'jpg');
        $canvas->compositeImage($im, imagick::COMPOSITE_OVER, 0, 0);

        $draw = new ImagickDraw();
        $draw->setFont($fontTTC);
        $draw->setTextAlignment(2);

        $draw->setFillColor(new ImagickPixel($config['font_color']));
        $draw->setFontSize(42);
        $draw->annotation($config['font_nick'][0], $config['font_nick'][1], $name);
//        $draw->setFontSize(36);
//        $draw->annotation($config['font_count'][0], $config['font_count'][1], '1');
//        $draw->setFontSize(32);
//        $draw->annotation($config['font_date'][0], $config['font_date'][1], date("Y年m月d日"));

        $canvas->drawImage($draw);

        $storage = new Storage();
        $url = $storage->addImage("jpg", $canvas);

        $im->destroy();
//        $code->destroy();
//        $avatar->destroy();
        $draw->destroy();
        $canvas->destroy();
//echo "<img src='{$url}'>";die;
        $this->render(
            array(
                "url" => $url
            )
        );

    }

}