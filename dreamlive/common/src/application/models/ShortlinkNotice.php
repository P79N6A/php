<?php

class ShortlinkNotice
{
    public static function getChannel($channel, $ip, $active_time, $platform, $brand, $model, $deviceid)
    {
        $platform = strtolower(trim($platform));
        $effective_time = date('Y-m-d H:i:s', strtotime($active_time) - 12 * 60 * 60);

        if ($platform == 'ios') {
            $channel = self::platformIos($deviceid, $ip, $effective_time);
        } else {
            $channel = self::platformAndroid($channel, $ip, $effective_time, $deviceid);
        }

        return $channel;
    }

    public static function addProcess($uid, $channel, $ip, $active_time, $platform, $brand, $model, $deviceid)
    {
        ProcessClient::getInstance("dream")->addTask(
            "channel_shortlink",
            [$uid, $channel, $ip, $active_time, $platform, $brand, $model, $deviceid]
        );

        $shortlink_user = new DAOShortlinkUser();
        $shortlink_user->add($uid, $channel, $ip, $active_time, $platform, $brand, $model, $deviceid);
    }

    public static function processChannel($uid, $channel, $ip, $active_time, $platform, $brand, $model, $deviceid)
    {
        $channel = self::getChannel($channel, $ip, $active_time, $platform, $brand, $model, $deviceid);

        $dao_user = new DAOUser();
        $loginInfo = $dao_user->getUserInfo($uid);
        if ($loginInfo['channel'] === "unknown") {
            $dao_user->setUserChannel($uid, $channel);

            $shortlink_user = new DAOShortlinkUser();
            $shortlink_user->deleteByUid($uid);
        }

        return true;
    }

    private static function platformIos($deviceid, $ip, $effective_time)
    {
        $shortlink_notice = new DAOShortlinkNotice();
        $shortlink_info = $shortlink_notice->getNotice($ip, $effective_time);

        $wall_notice = new DAOWallnotice();
        $wall_info = $wall_notice->getInfoByDeviceid($deviceid, $effective_time);

        if ($shortlink_info && empty($wall_info)) {
            $platform = new DAOChannelPlatform();
            $platform_info = $platform->getInfoByChannel($shortlink_info['channel']);
            if ($platform_info) {
                $channel = $platform_info['ioschannel'];
            } else {
                $channel = substr($shortlink_info['channel'], 0, 20);
            }

            $shortlink_notice->updatedeviceId($shortlink_info['id'], date('Y-m-d H:i:s'), $deviceid, $channel);

            return $channel;
        }

        if (empty($shortlink_info) && $wall_info) {
            $channel = $wall_info['partner'];
            // 需要callback
            $wall_notice->updateCallbackStatus($wall_info['id'], 1);

            return $channel;
        }

        if ($shortlink_info && $wall_info) {
            if (strtotime($shortlink_info['link_time']) >= strtotime($wall_info['addtime'])) {
                $platform = new DAOChannelPlatform();
                $platform_info = $platform->getInfoByChannel($shortlink_info['channel']);
                if ($platform_info) {
                    $channel = $platform_info['ioschannel'];
                } else {
                    $channel = substr($shortlink_info['channel'], 0, 20);
                }

                $shortlink_notice->updatedeviceId($shortlink_info['id'], date('Y-m-d H:i:s'), $deviceid, $channel);
                $wall_notice->updateCallbackStatus($wall_info['id'], 2);    // 不发送callback

                return $channel;
            } else {
                $channel = $wall_info['partner'];

                return $channel;
            }
        }

        return '0';
    }

    private static function platformAndroid($channel, $ip, $effective_time, $deviceid)
    {
        $shortlink_notice = new DAOShortlinkNotice();
        $notice_info = $shortlink_notice->getNotice($ip, $effective_time);

        if ($notice_info) {
            $platform = new DAOChannelPlatform();
            $platform_info = $platform->getInfoByChannel($notice_info['channel']);
            if ($platform_info) {
                $channel = $platform_info['androidchannel'];
            } else {
                $channel = substr($channel, 0, 20);
            }

            $shortlink_notice->updatedeviceId($notice_info['id'], date('Y-m-d H:i:s'), $deviceid, $channel);
        }

        return $channel;
    }

    public static function getLive($ip)
    {
        $result = [
            'liveid' => 0,
            'authorid' => 0,
        ];

        $ip = ip2long($ip);
        if ($ip) {
            $dao_shorklinklive = new DAOShortlinkLive();
            $info = $dao_shorklinklive->getInfoByIp($ip);
            if ($info) {
                $dao_shorklinklive->deleteByIp($ip);

                $result['liveid'] = $info['liveid'];
                $result['authorid'] = $info['authorid'];
            }
        }

        return $result;
    }
}