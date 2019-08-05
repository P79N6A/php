<?php

//git checkout -b pangu-master-air20190413 origin/pangu-master
//select * from Laputa.contract_with_customers where room_id = 117024 and rent_approve_status = '合格';
//select * from Laputa.rooms where code = '40672-A'
//select * from Laputa.rooms where code = '35287-B'
//select * from Laputa.rooms where code = '41413-B'
// 71277
//select * from Laputa.contract_with_customers where room_id = 128986 and rent_approve_status = '合格';
//select * from Laputa.contract_with_customers where room_id = 128497 and rent_approve_status = '合格';
//

namespace App\Logics\Pangu\FE\WebSite\SCM;

use App\ServiceApi\SCM\SuiteManage\Rooms\RoomServiceIf;

class AirQuality
{
    const DANKE_API_URL = 'http://172.18.130.28:83';
    //获取空气质量接口
    public static function getAirQualityThrift($suiteId)
    {
        $data = app(RoomServiceIf::class)->getAirCheckedListBySuiteId($suiteId);
        $return_data = json_encode($data, JSON_UNESCAPED_UNICODE);
        \Log::info("INFO GET " .$return_data);
        return $data;
    }


    //获取空气质量接口
    public static function getAirQuality($suiteId, $roomId = '')
    {
        $url = self::DANKE_API_URL . '/suite/get-air-list/' . $suiteId;


        if (empty(self::DANKE_API_URL)) {
            $string = '{"fromType":"检测单类型：品控自检/第三方","tester":"检测人","date":"检测日期","images":"图片","roomInfo":[{"roomName":"房间名称","roomId":"房间编号","data":{"windowtatus":"窗户状态","checkedInstrument":"检测仪器","itemsName":"检测类型 甲醛","standerdValue":"合格值","sceneValue":"检测值","sceneResult":"检测结果"}},{"roomName":"房间名称","roomId":"房间编号","data":{"windowtatus":"窗户状态","checkedInstrument":"检测仪器","itemsName":"检测类型 甲醛","standerdValue":"合格值","sceneValue":"检测值","sceneResult":"检测结果"}}]}';
            $content = json_decode($string, true);
        } else {
            $content = self::myCurlGet($url);
        }

        if (!empty($content['roomInfo'])) {
            foreach ($content['roomInfo'] as $key11 => $item_airs) {
                foreach ($item_airs['data'] as $key12 => $air_its) {
                    if ($air_its['sceneResult'] != '合格') {
                        unset($content['roomInfo'][$key11]['data'][$key12]);
                    }
                }
                sort($content['roomInfo'][$key11]['data']);//重新排序
                if (empty($content['roomInfo'][$key11]['data'])) {
                    unset($content['roomInfo'][$key11]);
                }
            }
            sort($content['roomInfo']);//重新排序
            if (empty($content['roomInfo'])) {
                \Log::info(" air {$suiteId}, {$roomId} 不合格");
                return [];
            }
        }

        //print_r($content);exit;
        $data = [];

        if (!empty($roomId) && !empty($content)) {
            $data['code']   = $content['code'];
            $data['tester'] = $content['tester'];
            $data['date']   = $content['date'];
            $data['images'] = $content['images'];
            $data['roomInfo'] = [];
            foreach ($content['roomInfo'] as $item) {
                if ($item['roomId'] == $roomId) {
                    $item_room = [];
                    $item_room['roomId'] = $item['roomId'];
                    $roomCode = explode('-', $item['roomName']);
                    $item_room['roomName'] = $roomCode[1] . '室';
                    $item_room['data'][] = $item['data'][0];
                    $data['roomInfo'][] = $item_room;
                    break;
                }
            }
        } else {
            $data['code']   = $content['code'];
            $data['tester'] = $content['tester'];
            $data['date']   = $content['date'];
            $data['images'] = $content['images'];
            $data['roomInfo'] = [];
            foreach ($content['roomInfo'] as $item) {
                if (!empty($item['data'])) {
                    $item_room = [];
                    $item_room['roomId'] = $item['roomId'];
                    $roomCode = explode('-', $item['roomName']);
                    $item_room['roomName'] = $roomCode[1] . '室';
                    $item_room['data'][] = $item['data'][0];
                    $data['roomInfo'][] = $item_room;
                }
            }
            //$data = $content;
        }

        if (isset($data['roomInfo'][0]['data'][0]) && !empty($data['roomInfo'][0]['data'][0])) {
            $data['checkedInstrument'] = $data['roomInfo'][0]['data'][0]['checkedInstrument'];
        }

        return $data;
    }

    public static function myCurlGet($url)
    {
        \Log::info("INFO {$url} GET ");
        try {
            //初始化
            $ch = curl_init();
            //设置抓取的url
            curl_setopt($ch, CURLOPT_URL, $url);
            //curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
            curl_setopt($ch, CURLOPT_NOSIGNAL, 1); //注意，毫秒超时一定要设置这个
            curl_setopt($ch, CURLOPT_TIMEOUT_MS, 5000); //超时毫秒，cURL 7.16.2中被加入。从PHP 5.2.3起可使用
            //设置头文件的信息作为数据流输出
            curl_setopt($ch, CURLOPT_HEADER, 0);
            //设置获取的信息以文件流的形式返回，而不是直接输出。
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //执行命令
            $response = curl_exec($ch);
            //关闭URL请求
            curl_close($ch);
            //显示获得的数据
            $result = json_decode($response, TRUE);
            \Log::info("INFO  get air {$url} data:" . $response);
            return $result;
        } catch (\Exception $e) {
            \Log::info("INFO  GET msg:" . $e->getMessage() . " code:" . $e->getCode());
            return [];
        }
    }

}