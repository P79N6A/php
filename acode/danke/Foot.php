<?php
namespace App\Logics\Pangu\MAPI\V6;

use API\Exceptions\BadRequestException;
use App\Logics\Pangu\MAPI\AbstractAPI;
use App\Logics\Pangu\MAPI\UtilTrait;
use App\Libs\ApiJavaServer\AppIndexServer;
use App\Logics\Pangu\MAPI\SuiteRoom\NewRooms;
use UserCollect;
use App\Logics\Pangu\MAPI\Service\CollectService;
use App\Logics\Pangu\MAPI\Service\RoomService;
use Danke\Elastic\ElasticClient;

class Foot extends AbstractAPI
{
    public function getUserCollect()
    {
        $page       = intval($this->query('page'));
        $perPage    = intval($this->query('per_page'));
        $last_id    = intval($this->query('last_id'));
        if ($perPage <= 0) {
            throw BadRequestException::describe('参数有误');
        }
        $user = \Auth::user();
        $result = array();
        if ($user) {
            $start_time = microtime(true);
            $client = new \UserCollect();
            $getUserCollect = $client->getPaginateList($user->id, $perPage);

            if (!empty($getUserCollect)) {
                echo count($getUserCollect['data']);
                $ids_array = [];
                $offset = 0;
                foreach ($getUserCollect['data'] as $row) {
                    $ids_array[] = $row['room_id'];
                    $offset = $row['id'];
                }

                $consume = round((microtime(true) - $start_time) * 1000);
                \Log::info('JAVA_FOOT_LIST_CONSUME '. $consume . json_encode($ids_array));
                $start_time = microtime(true);
                $tmpData = [];
                try {
                    ElasticClient::setDefaultHosts(['172.18.130.7:9200']);
                    $query = ElasticClient::query('scm_laputa_room_detail')
                        ->whereIn('id', $ids_array);
                    $tmpData = $query->get()->toArray();
                } catch (\Exception $e) {
                    \Log::info('ES_FOOT_LIST_ERROE ' . json_encode(['code' => $e->getCode(), 'msg' => $e->getMessage()]));
                }
                $consume = round((microtime(true) - $start_time) * 1000);
                \Log::info('ES_FOOT_LIST_CONSUME '. $consume);
                $formatList = array();

                $newArray = [];
                foreach ($tmpData as $row) {
                    $newArray[$row['id']] = $row;
                }

                if ($tmpData) {
                    $roomLogic = new NewRooms();
                    $roomLogic->commParameters = $this->commParameters;
                    foreach ($ids_array as $key => $roomId) {
                        $res = $roomLogic->detail($newArray[$roomId], 'list');
                        $formatList[] = $res;
                    }
                }
                $result = [
                    'current_page'  => $page,
                    'total'         => $getUserCollect['total'],
                    'offset'        => $offset,
                    'data'          => [
                        'list' => $formatList
                    ]
                ];
            }
        }

        return $this->toJson($result);
    }

    public function getList()
    {
        $page       = intval($this->query('page'));
        $perPage    = intval($this->query('per_page'));
        $last_id    = intval($this->query('last_id'));
        if ($perPage <= 0) {
            throw BadRequestException::describe('参数有误');
        }
        $user = \Auth::user();
        $result = array();
        if ($user) {
            $start_time = microtime(true);
            $client = new \UserFoot();
            $getUserFoot = $client->getList($user->id, $last_id, $perPage);

            if (!empty($getUserFoot['data'])) {
                $ids = collect($getUserFoot['data'])->pluck('id')->toArray();
                $ids_array = [];
                foreach ($ids as $decode_id) {
                    $ids_array[] = \ForgePublicId::optimusDecode($decode_id);
                }
                $consume = round((microtime(true) - $start_time) * 1000);
                \Log::info('JAVA_FOOT_LIST_CONSUME '. $consume . json_encode($ids_array));
                $start_time = microtime(true);
                $tmpData = [];
                try {
                    $query = ElasticClient::query('scm_laputa_room_detail')
                        ->whereIn('id', $ids_array);
                    $tmpData = $query->get();
                } catch (\Exception $e) {
                    \Log::info('ES_FOOT_LIST_ERROE ' . json_encode(['code' => $e->getCode(), 'msg' => $e->getMessage()]));
                }
                $consume = round((microtime(true) - $start_time) * 1000);
                \Log::info('ES_FOOT_LIST_CONSUME '. $consume);
                $formatList = array();
                if ($tmpData) {
                    $roomLogic = new NewRooms();
                    $roomLogic->commParameters = $this->commParameters;
                    foreach ($tmpData as $key => $room) {
                        $res = $roomLogic->detail($room, 'list');
                        $formatList[] = $res;
                    }
                }
                $result = [
                    'current_page'  => $page,
                    'total'         => $getUserFoot['total'],
                    'data'          => [
                        'list' => $formatList
                    ]
                ];
            }
        }

        return $this->toJson($result);
    }

    /**收藏
     * @return array
     */
    public function putRoom()
    {
        $start_time = microtime(true);
        $roomId = trim($this->data('room_id'));
        //
        if (!$roomId) {
            throw BadRequestException::describe('传参有误q');
        }

        $user = \Auth::user();
        if (!$user) {
            return [
                'code' => 1000,
                'msg' => '用户未登录'
            ];
        }
        $roomService = new RoomService();
        $room = $roomService->getRoomInfoById($roomId);

        if (!$room) {
            throw BadRequestException::describe('传参有误2');
        }
        $roomId = \ForgePublicId::optimusDecode($roomId);
        //请求Java接口
        $client = new CollectService();
        $response = $client->put($user->id, $roomId);

        $code = $response > 0 ? 200 : 400;

        return [
            'consume'   => round((microtime(true) - $start_time) * 1000),
            'code'      => $code,
            'msg'       => $code == 200 ? '收藏成功' : '收藏失败'
        ];
    }
}