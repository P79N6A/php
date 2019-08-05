<?php
class IpLocationClient
{

    public function findLocation($ip)
    {
        return $this->ip2Location($ip);
    }
    
    public function ip2Location($ip)
    {
        $location = false;
        $data  = Ip2region::btreeSearchString($ip);

        if (!empty($data['region'])) {
            $data_array = explode('|', $data['region']);
    
            $province = $data_array[2];
            $city     = $data_array[3];
            $district = $data_array[4];
            $province = str_replace('省', '', $province);
            $province = str_replace('自治区', '', $province);
            $province = str_replace('市', '', $province);
            $city     = str_replace('市', '', $city);
            $district = str_replace('县', '', $district);
            
            $location['country']  = $data_array[0];
            $location['province'] = $province;
            $location['city']     = $city;
            $location['district'] = $district;
            
        } else {
            $location = false;
        }
        
        return $location;
    }
    
}
