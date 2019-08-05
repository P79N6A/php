<?php

require_once 'ip_client/IpLocationClient.php';

class Region
{

    const REGION_ALL = ['china', 'abroad'];
    const REGION_CHINA = 'china';
    const REGION_ABROAD = 'abroad';
    const REGION_UNKNOWN = 'unknown';

    public static function getRegion($lng, $lat, $country_code, $uid)
    {
        $country_region = self::REGION_UNKNOWN;
        // 如果无定位信息, 则不考虑定位条件
        if ($country_region) {
            if ($country_code != 'CN') {
                $country_region = self::REGION_ABROAD;
            } else {
                $country_region = self::REGION_CHINA;
            }
        }

        $ip = Util::getIP();
        $iplocation = new IpLocationClient();
        $location = $iplocation->findLocation($ip);

        $ip_region = self::REGION_CHINA;
        if ($location) {
            if ($location['country'] != '中国' && $location['country'] !='未分配或者内网IP') {
                $ip_region = self::REGION_ABROAD;
            }
        }

        if ($uid) {
            return self::getLoginRegion($ip_region, $country_region, $uid);
        } else {
            return self::getNotLoginRegion($ip_region, $country_region);
        }
    }

    private static function getLoginRegion($ip_region, $country_region, $uid)
    {
        $user_region = self::REGION_CHINA;
        $user_info = User::getUserInfo($uid);
        if (in_array($user_info['region'], self::REGION_ALL)) {
            $user_region = $user_info['region'];
        }

        if ($country_region == self::REGION_UNKNOWN) {
            if ($user_region == self::REGION_ABROAD && $user_region == $ip_region) {
                $region = self::REGION_ABROAD;
            } else {
                $region = self::REGION_CHINA;
            }
        } else {
            if ($user_region == self::REGION_ABROAD && $user_region == $ip_region && $user_region == $country_region) {
                $region = self::REGION_ABROAD;
            } else {
                $region = self::REGION_CHINA;
            }
        }

        return $region;
    }

    private static function getNotLoginRegion($ip_region, $country_region)
    {
        if ($country_region == self::REGION_UNKNOWN) {
            $region = $ip_region;
        } else {
            if ($ip_region == self::REGION_ABROAD && $ip_region == $country_region) {
                $region = self::REGION_ABROAD;
            } else {
                $region = self::REGION_CHINA;
            }
        }

        return $region;
    }

}
