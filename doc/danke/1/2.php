public static function isMonthlyRent($roomInfo, $check_from = true)
    {
        //必须是筛选过可月租才显示按钮
        if ($check_from && HttpHelper::getParam('rent_type') != 3) {
            return false;
        }

        if (!isProduction()) {
            $userId = \Tool::context("uid");
            \Tool::log("isMonthlyRent-userid:" . $userId);
            $white_list = [564707, 7202, 31946, 310747, 1286868];

            if (empty($userId) || (!empty($userId) && !in_array($userId, $white_list))) {
                return false;
            }
        }
        $next_month = date("Y-m-d", strtotime("+ 1 month"));
        $next_month_time = strtotime($next_month);
        $room_rent_end_date = strtotime($roomInfo['rentEndDate']);
        \Tool::log("isMonthlyRent-endDate:{$next_month} - {$roomInfo['rentEndDate']}");
        if (!empty($roomInfo['rentEndDate']) && $room_rent_end_date < $next_month_time) {
            return false;
        }

        $contract = EsContractLandlord::findEffectContractBySuiteId($roomInfo['suiteId']);
        $has_landlord = !empty($contract) ? true : false;
        \Tool::log("isMonthlyRent-findEffectContractBySuiteId:{$has_landlord}");
        if (isProduction()) {
            if (!$has_landlord) {
                return false;
            }
        }

        $monthRentCity = array_values(AreaModel::list());
        if (!in_array($roomInfo['cityName'], $monthRentCity)) {
            return false;
        }

        $configure = new ConfigureModel();
        $is_config = $configure->isMonthRentConfig($roomInfo);
        \Tool::log("isMonthlyRent-last:{$is_config} - {$roomInfo['isMonth']}");
        if ($roomInfo['isMonth'] === IS::IS_否 && !$is_config) {
            return false;
        }

        return true;
    }

    $result['online_contract'] = StaticRoomObj::isMonthlyRent($roomInfo);//不能有缓存

    /**
     * 获取config里的不可租房间id
     */
    public function getMonthRentConfigValue()
    {
        $redis_key = "room_detail_getMonthRentConfigValue";
        $cache = \Tool::redis('cache');
        $config = json_decode($cache->get($redis_key), true);
        if (empty($config)) {
            $config = EsConfigure::findConfigureByKey('月租房源列表');
            $cache->set($redis_key, json_encode($config));
            $cache->expire($redis_key, 600);
        }

        if ($config && $config['value']) {
            $configValue = explode("|", $config['value']);
            foreach ($configValue as $value) {
                $value = explode(":", $value);
                if ($value[0] === 'BI录入可月租房源') {
                    return explode(',', $value[1]);
                }
            }
        }
        return null;
    }
    public function isMonthRentConfig($roomInfo)
    {
        $roomCode = $this->getMonthRentConfigValue();
        if (!empty($roomInfo['code']) && !empty($roomCode) && is_array($roomCode)) {
            $has_in = in_array($roomInfo['code'], $roomCode);
            \Tool::log("isMonthlyRent-last:{$has_in}");
            return $roomCode && $has_in;
        } else {
            \Tool::log("isMonthlyRent-last: empty");
            return false;
        }
    }