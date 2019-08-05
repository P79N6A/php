<?php
域名地址修改路径/ashangzu/dkzoo/config/app/product/app.php
数据库地址修改路径 /ashangzu/dkzoo/config/app/product/mysql.php
select is_month,search_text from rooms where code = '51793-C';

    public function getAddTitle($roomInfo)
    {
        try {
            $title = (new RoomServer())->getRoomNearSite($roomInfo['id']);
        } catch (\Exception $e) {
            \Tool::log('roomNearSiteError' . $e->getMessage() . $e->getFile() . $e->getLine());
            return $this->nearestSubwayTitle($roomInfo['xiaoquLngLat'], $roomInfo['subwayLngLat'], $roomInfo['subwayName'], $roomInfo['lines']);
        }
        $near_array = [];
        if (in_array($title)) {
            foreach ($title as $es_title) {
                if (!empty($es_title)) {
                    $near_array[] = $es_title;
                }
            }
        } else {
            $near_array = $title;
        }

        if (empty($near_array)) {
            return $this->nearestSubwayTitle($roomInfo['xiaoquLngLat'], $roomInfo['subwayLngLat'], $roomInfo['subwayName'], $roomInfo['lines']);
        }
        return implode('|', $near_array) ?? '';
    }