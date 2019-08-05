<?php

//https://api.dankegongyu.com/api/v5/rank/
$date = '2018-10-14';
$diff_date = "2019-02-20";

if ($date < $diff_date) {
	var_dump("w");
}

var_dump(date("Y-m-d H:i:s", strtotime("+ 1 year")));


curl -H 'X-Device-Info: {"os-type":"Android","os-ver":"7.1.2","app-ver":"V1.9.8.507","net-type":"wifi","imei":"866338036418710","timestamp":"1543287920","device-uuid":"fff1gAkupkxwqGgUXATLP2jVWSXW67afGEniO0uX","device-brand":"vivo","device-model":"vivoX9","app-channel":"umeng","package-name":"com.dankegongyu.customer","width":"1080","height":"1920","idfa":"","ClientId":"162980387f9dc41a58194ce2693c05fc","city_id":"1","loc_latlng":"4.9E-324_4.9E-324","loc_city":"%E5%8C%97%E4%BA%AC","loc_city_code":""}' -H 'sign: 6D7231770A7C741695ECE2FEB8BA4E36' -H 'X-App-ID: 4' -H 'X-Device-ID: fff1gAkupkxwqGgUXATLP2jVWSXW67afGEniO0uX' -H 'Authorization: ' -H 'Host: 172.16.31.54:3333' -H 'User-Agent: okhttp/3.10.0' --compressed 'http://172.16.31.54:3333/api/v6/search/list?city_id=1&isnewformat=1&page=1&timestamp=1543287920'