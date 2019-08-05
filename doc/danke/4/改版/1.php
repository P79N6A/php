<?php

$a = 'rent_type=z1&isnewformat=1&other=searchText_%E7%AB%8B%E6%B0%B4%E6%A1%A5&feature=isNearSubway_%E6%98%AF&page=1&city_id=1';

parse_str($a, $b);

echo json_encode($b);exit;

print_r($b);exit;