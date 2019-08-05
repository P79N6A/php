<?php
class TestController extends  BaseController
{
    public function indexAction()
    {
        $sn        = 'WS_1489653515_21000231_8774.ab24';
        $partner   = 'ws';

        $live = new Live();
        $liveInfo = $live->getLiveInfoBySn($sn, $partner);
        $info = array(
            'uid'      => $liveInfo['uid'],
            'type'     => 2,
            'relateid' => $liveInfo['liveid'],
            'addtime'  => $liveInfo['addtime'],
        );
        include_once 'process_client/ProcessClient.php';
        ProcessClient::getInstance("dream")->addTask("resources_add_newsfeeds", $info);
    }


    public function gameAction()
    {
        $engine=new HorseracingEngine(true);
        $engine->init();
        $timeline=$engine->getTimeline();

        /* $engine->startGame();
        $engine->startBanker();
        sleep($timeline['banker_time_span_robot']);
        $engine->robotBanker();
        sleep($timeline['banker_time_span']-$timeline['banker_time_span_robot']+$timeline['banker_to_stake_span']);
        $engine->startStake();
        sleep($timeline['stake_time_span']+$timeline['stake_to_run_span']);
        $engine->startRun();
        sleep(1);
        $engine->settle();
        $engine->gameOver();
        sleep(HorseracingEngine::NEXT_ROUND_TIME);*/
    }

    public function accountTestAction()
    {
        try{

            $uid=10031311;
            $taskid=555555;
            $award=array(
              'starlight'=>100,
              'diamonds'=>10,
            );
            StarTask::increase($uid, $taskid, $award);
        }catch (Exception  $e){
            echo '<pre>';
            print_r($e);
        }

    }
    
    public function testMessageAction()
    {
        $liveid = $this->getParam('liveid');
        $userid = $this->getParam('userid');
        
        if (!empty($userid)) {
            $d = Messenger::isDisturbed($userid);
            $pushid = Messenger::getUserPushId($userid);
            var_dump($d);
            var_dump($pushid);
            exit;
            
        }
    }
    
    
    public function testAction()
    {
        ini_set("display_errors", "On");
        include_once 'antispam_client/AntiSpamClient.php';
        $a = new AntiSpamClient();
        var_dump($a->isDirty('message', 'http://static.dreamlive.com/images/f4f7b53799ae820f41707d76b9a3072d.jpg'));
        exit;
    }

    public function giftTestAction()
    {
        try{

            $sender=10031311;
            $receiver=10026912;
            $liveid=1234;
            $giftid=13;
            $price=2;
            $ticket=1;
            $num=2;

            $g=new Gift();
            $g->sendStarGift($sender, $liveid, $receiver, $giftid, $price, $ticket, $num);
        }catch (Exception  $e){
            echo '<pre>';
            print_r($e);
        }
    }

    public function sfTestAction()
    {
        exit('ddd');
        echo  Account::getOrderId(123);
    }

    public function addgiftAction()
    {
        $dao_gift = new DAOGift();
        $t=$dao_gift->addGift($name, $image, $label, $type, $price, $ticket, $consume, $score, $status, $url, $zip_md5, $extends, $tag);
    }

    public function depositAction()
    {
        /*
        $str='{"signature":"GpR17nxFZXQvoQt\/9e2Wyg6j7LWawkiZgpxcsfEqvQCAIrXp2X6vKVEtx32GtClz1oLYbF OzkqNWWRPggUWVKBU\/6lWTy79pKkjrXHKUVP8wCRLqx1KWTC1U 9SW14IhAi9wZ1gOwNKQFcEyizvc06dqQ71D bCHZQYk Fw2DWudu9fLpZxYC925WDigZKXYzMCPNYEouSYCAwv98GRL4i075c45gt4at il6FWcTUZfKU IulV8R3hF2quENmq1np2sziUY1sSbWzkAsEWkKHomFblDYcy2ULc04AiTsNsAwG0NkC0SZEYhMwtKBhvJWOBVi\/0GfhToNQ6s22 Og==","signature_data":"{\"packageName\":\"com.dreamer.tv\",\"productId\":\"com.dreamer.tv01\",\"purchaseTime\":1493880703637,\"purchaseState\":0,\"developerPayload\":\"321038208303378432\",\"purchaseToken\":\"hflelgphemdhfnkaeinlcphd.AO-J1OykP1j0b-wefI9Kc7QBFRojFLe1Sf-4ZtLnMHXoLalkwjWjlSJ9hVoIWrthzyUi7NF5Yosvip1gLqLbQ4QcHxhOLEDrwHAPne7j1s4Mn1Jree9pWVzk-7Bev3ipqummVNPpOwof\"}","time":"1493880708219","netspeed":"0","guid":"ee43d9aa763f07f28e58668ba54c84cd","region":"china","deviceid":"cfc498cf7612046e739fd8894b400c81","lng":"116.452592","userid":"10000025","network":"wifi","platform":"android","brand":"vivo","version":"2.2.3","rand":"1997","lat":"39.932543","channel":"default_channel","model":"vivo X9"}';
        $str_json=json_decode($str,true);
        $s1=$str_json['signature'];
        $sd1=$str_json['signature_data'];


        $s2='GpR17nxFZXQvoQt/9e2Wyg6j7LWawkiZgpxcsfEqvQCAIrXp2X6vKVEtx32GtClz1oLYbF+OzkqNWWRPggUWVKBU/6lWTy79pKkjrXHKUVP8wCRLqx1KWTC1U+9SW14IhAi9wZ1gOwNKQFcEyizvc06dqQ71D+bCHZQYk+Fw2DWudu9fLpZxYC925WDigZKXYzMCPNYEouSYCAwv98GRL4i075c45gt4at+il6FWcTUZfKU+IulV8R3hF2quENmq1np2sziUY1sSbWzkAsEWkKHomFblDYcy2ULc04AiTsNsAwG0NkC0SZEYhMwtKBhvJWOBVi/0GfhToNQ6s22+Og==';

        $sd2='{"packageName":"com.dreamer.tv","productId":"com.dreamer.tv01","purchaseTime":1493880703637,"purchaseState":0,"developerPayload":"321038208303378432","purchaseToken":"hflelgphemdhfnkaeinlcphd.AO-J1OykP1j0b-wefI9Kc7QBFRojFLe1Sf-4ZtLnMHXoLalkwjWjlSJ9hVoIWrthzyUi7NF5Yosvip1gLqLbQ4QcHxhOLEDrwHAPne7j1s4Mn1Jree9pWVzk-7Bev3ipqummVNPpOwof"}';

        print_r(urlencode($s1));
        echo "\n\n";
        print_r($s2);
        echo "\n\n";
        var_dump($s1==$s2);
        var_dump($sd1==$sd2);exit;*/
        /*        //data you want to sign
        $data = 'my data';

        //create new private and public key
        $public_key = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAs7WvrFJH5OJ+M2Mli9NWSFgMussvF5lvErQYbx2UWaSOP4IeDP/uxjH+R/N30HNRUzSYgTccNoFawpLzlGAyJ/8oXC7xmIWkCuLnlScgGMgY4cK1I4Dyp8KuD3R1JqKV0SQ7n6EdZMlDoniwKuW+B8rmhaIyCPHSLe3zcrL0+J3ZXso2nnuJqw0yAhlV1v3sjvb5c6gilwFsdwATQgR2EmwMOIR7M+KyBjijTqd0jUdAk3bChldisCu8J9y781N31x3rqxaoHVCmj0pFdZziOKEgyMhqB39SSlr0AoF3FC78qxy5e8MuLC2Wd0lcfmT5okYT8F8IzA3Q7QR/oT0MLwIDAQAB";

        $public_key = "-----BEGIN PUBLIC KEY-----\n" . chunk_split($public_key, 64, "\n") . "-----END PUBLIC KEY-----";
        var_dump($public_key);

        $public_key_handle = openssl_get_publickey($public_key);
        var_dump(openssl_pkey_get_details($public_key_handle));

        $private_key_res = openssl_pkey_new(array(
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ));
        $details = openssl_pkey_get_details($private_key_res);
        var_dump($details);
        $public_key_res = openssl_pkey_get_public($details['key']);

        //create signature
        openssl_sign($data, $signature, $private_key_res, "sha1WithRSAEncryption");

        //verify signature
        $ok = openssl_verify($data, $signature, $public_key_res, OPENSSL_ALGO_SHA1);
        if ($ok == 1) {
            echo "valid";
        } elseif ($ok == 0) {
            echo "invalid";
        } else {
            echo "error: ".openssl_error_string();
        }*/
        try{
            $t=new GooglePay();
            $t->notify();
        }catch (Exception $e){
            throw $e;
        }

    }
}
