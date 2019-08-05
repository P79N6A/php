<?php
/**
 * 收出房接口
 */
namespace App\Logics\Pangu\FE\WebSite\ARM;

class RoomCompany
{
    /**
     * 从缓存获取房子公司主体
     * @param $roomId
     * @return bool
     */
    public static function checkHasShowMonthlyInstalmentFromCache($roomId)
    {
        $content = \Cache::remember(__CLASS__ .'-'. $roomId, 60, function () use ($roomId) {
            $content = self::getInfoFromUrl($roomId);
            return $content;
        });

        if (!empty($content)) {
            if ($content['data']['is_show'] == true) {
                return true;
            } else {
                return false;
            }
        }

        return true;
    }

    public static function getInfoFromUrl($roomId)
    {
        $time       = time();
        $host       = "https://www.danke.com";//http://www.danke.com
        $token      = md5($roomId.$time.'finance');

        $url        = $host . "/callback/pangu/to-web/room-company?room_id=" . $roomId . "&token=" . $token . "&timestamp=" . $time;
        $start_time = microtime(true);
        $content    = self::curlGet($url);
        $consume    = round((microtime(true) - $start_time) * 1000);
        \Log::info("RoomCompany $roomId {$url} consume {$consume} response " . json_encode($content));

        return $content;
    }

    /**
     * 是否显示分期付款文案标识
     * @param $roomId
     * @return bool
     */
    public static function checkHasShowMonthlyInstalment($roomId)
    {
        $content = self::getInfoFromUrl($roomId);

        if (!empty($content)) {
            if ($content['data']['is_show'] == true) {
                return true;
            } else {
                return false;
            }
        }

        return true;
    }

    public static function curlGet($url)
    {
        \Log::info("INFO curl {$url} GET ");
        try {
            //初始化
            $ch = curl_init();
            //设置抓取的url
            curl_setopt($ch, CURLOPT_URL, $url);
            //curl_setopt($ch, CURLOPT_TIMEOUT, 5000);
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
            $result = json_decode($response, true);
            \Log::info("INFO  get utils {$url} data:"  . $response);
            return $result;
        } catch (\Exception $e) {
            \Log::info("INFO  GET msg:", $e->getMessage(), " code:" . $e->getCode());
            return [];
        }
    }
}
