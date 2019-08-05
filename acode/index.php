<?php

phpinfo();
if ($other = $searchConds['other'] ?? '') {
            $otherArr = explode('_', $other);
            if (in_array($otherArr[0], ['blockId', 'xiaoquId'])) {
                $queryData['exactQuery'][$otherArr[0]] = $otherArr[1];
            } elseif ($otherArr[0] == 'searchText') {
                $queryData['searchText'] = $otherArr[1];
            }
        }
        if ($item_row['siteIdColum'] == 'searchText') {
                    $item_arr['filter_type']    = 'search_text';
                    $item_arr['app_filter']     = true;
                    $item_arr['code']           = $item_row['siteId'];
                } else {
                    $item_arr['filter_type']    = "other";
                    $item_arr['app_filter']     = false;
                    $item_arr['code']           = $item_row['siteIdColum'] ."_". $item_row['siteId'];
                }
//弹屏广告位
    public function getPopup()
    {
        $this->checkRequest();
        $this->rule([
            'city_id' => 'required',
            'applet_type' => 'required'
        ]);
        $type = 'popup';
        $isCustomer = 0;

        $cityId = intval(\Input::input('city_id'));
        $appletType = intval(\Input::input('applet_type')) ?: 1;

        $this->validateCityId($cityId);
        $banner = self::getPopupBanner($cityId, $type, $appletType, $isCustomer);
        if (!$banner) {
            return $this->ajax(false, '当前无活动');
        }

        return $this->ajax(true, '获取弹屏成功', $banner);
    }

    public static function getPopupBanner($cityId, $type, $appletType)
    {
        $banner = app(AppletBannerServiceIf::class)->findBannerByCityIdAndType($cityId, $type, $appletType);

        if (!$banner) {
            return [];
        }

        $all = json_decode($banner->images, true);
        $fs = \FileStore::gen('image');
        $bannerArray = [
            'name' => $banner->name,
            'image_icon' => $fs->url(array_last($all['icon'])),
            'image_popup' => $fs->url(array_last($all['popup'])),
            'id' => $banner->id,
            'resource_data' => $banner->resourceData,
            'resource_type' => $banner->resourceType
        ];

        return $bannerArray;
    }

    /**
     * applet_banners
     * @param int $id
     * @param string $type
     * @param int $appletType
     * @return \App\ServiceApi\FE\WebSite\Banner\Entity\AppletBanner
     */
    public function findBannerByCityIdAndType($id, $type, $appletType)
    {
        $banner = AppletBanner::onReader()
            ->SearchCityId($id)
            ->whereStatus('启用')
            ->where('applet_type', $appletType)
            ->where('effective_begin', '<=', Carbon::today())
            ->where('effective_end', '>=', Carbon::today())
            ->where('type', $type)
            ->orderBy('sort')
            ->orderBy('id', 'desc')
            ->first();
        return $banner;
    }

    //个人中心banner
    public function getUserBannerList()
    {
        $this->checkRequest();
        $this->rule([
            'city_id' => 'required',
            'applet_type' => 'required',
            'is_customer' => 'required'
        ]);
        $type = 'landlord';
        $isCustomer = intval(\Input::input('is_customer'));

        $cityId = intval(\Input::input('city_id'));
        $appletType = intval(\Input::input('applet_type')) ?: 1;

        $this->validateCityId($cityId);
        $bannerList = self::getAppletBannerList($cityId, $type, $appletType, $isCustomer);
        if (empty($bannerList)) {
            return $this->ajax(false, '获取banner列表失败');
        }

        return $this->ajax(true, '获取banner列表成功', $bannerList);
    }