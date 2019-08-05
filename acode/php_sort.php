<?php

function insert_sort($arr)
{

}
C:\xampp\htdocs\laravels>composer update
Loading composer repositories with package information
    Authentication required (packagist.phpcomposer.com):
      Username:
      Password:
Invalid credentials for 'https://packagist.phpcomposer.com/packages.json'
//后面把https://packagist.phpcomposer.com改成https://https://packagist.org才可以安装依赖成功
//目前解决办法：
composer config -g repo.packagist composer https://https://packagist.org

